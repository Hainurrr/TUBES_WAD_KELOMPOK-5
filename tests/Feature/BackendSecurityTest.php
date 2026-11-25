<?php

namespace Tests\Feature;

use App\Helpers\BadWordHelper;
use App\Models\Category;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Review;
use App\Models\Tenant;
use App\Models\User;
use App\Services\OrderCheckoutService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class BackendSecurityTest extends TestCase
{
    use DatabaseTransactions;

    private function createUser(string $role, string $suffix): User
    {
        return User::create([
            'name' => "Test {$role} {$suffix}",
            'email' => "test_{$role}_{$suffix}_" . uniqid() . '@example.test',
            'password' => Hash::make('Password123!'),
            'role' => $role,
        ]);
    }

    private function createTenantWithMenu(User $owner, array $tenantOverrides = [], array $menuOverrides = []): array
    {
        $category = Category::firstOrCreate(['name' => 'Makanan']);

        $tenant = Tenant::create(array_merge([
            'id_user' => $owner->id_user,
            'name' => 'Gerai Uji ' . uniqid(),
            'category' => 'Kantin TULT',
            'description' => 'Gerai uji keamanan',
            'owner_name' => $owner->name,
            'phone' => '08123456789',
            'address' => 'Gedung TULT',
            'city' => 'Bandung',
            'is_active' => true,
            'is_open' => true,
        ], $tenantOverrides));

        $menu = Menu::create(array_merge([
            'id_tenant' => $tenant->id_tenant,
            'id_category' => $category->id_category,
            'name' => 'Menu Uji ' . uniqid(),
            'price' => 25000,
            'stock' => 10,
            'description' => 'Deskripsi uji',
            'image' => 'https://example.test/menu.jpg',
            'is_available' => true,
        ], $menuOverrides));

        return [$tenant, $menu];
    }

    public function test_security_headers_are_attached_to_web_responses(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->assertHeader('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');
    }

    public function test_role_middleware_blocks_unauthorized_access(): void
    {
        $customer = $this->createUser(User::ROLE_CUSTOMER, 'rbac');
        $admin = $this->createUser(User::ROLE_ADMIN, 'rbac');
        $orphanTenant = $this->createUser(User::ROLE_TENANT, 'orphan');

        $this->get('/admin/dashboard')->assertRedirect('/login');
        $this->get('/tenant/dashboard')->assertRedirect('/login');

        $this->actingAs($customer)->get('/admin/dashboard')->assertStatus(403);
        $this->actingAs($customer)->get('/tenant/dashboard')->assertStatus(403);
        $this->actingAs($customer)->postJson('/tenant/update-status', ['is_open' => true])->assertStatus(403);

        $this->actingAs($admin)->get('/cart')->assertStatus(403);
        $this->actingAs($admin)->get('/history')->assertStatus(403);
        $this->actingAs($admin)->get('/profile')->assertStatus(200);

        $this->actingAs($orphanTenant)->get('/tenant/dashboard')->assertRedirect(route('home'));
        $this->actingAs($orphanTenant)->postJson('/tenant/update-status', ['is_open' => true])->assertStatus(403);
    }

    public function test_login_endpoint_is_rate_limited(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $this->post('/login', [
                'email' => 'rate-limit-check@example.test',
                'password' => 'wrong-password',
            ])->assertStatus(302);
        }

        $this->post('/login', [
            'email' => 'rate-limit-check@example.test',
            'password' => 'wrong-password',
        ])->assertStatus(429);
    }

    public function test_catalog_search_does_not_leak_closed_tenant_or_unavailable_menus(): void
    {
        $ownerClosed = $this->createUser(User::ROLE_TENANT, 'closed');
        [, $closedMenu] = $this->createTenantWithMenu(
            $ownerClosed,
            ['name' => 'Gerai Tutup Rahasia', 'is_open' => false],
            ['name' => 'RahasiaTutupSpesial']
        );

        $ownerOpen = $this->createUser(User::ROLE_TENANT, 'unavail');
        [, $unavailMenu] = $this->createTenantWithMenu(
            $ownerOpen,
            ['name' => 'Gerai Buka', 'is_open' => true],
            ['name' => 'RahasiaTutupNonaktif', 'is_available' => false]
        );

        $response = $this->get('/menus?q=RahasiaTutup');
        $response->assertStatus(200);
        $response->assertDontSee($closedMenu->name);
        $response->assertDontSee($unavailMenu->name);
    }

    public function test_checkout_ignores_tampered_session_price_and_uses_db_price(): void
    {
        $owner = $this->createUser(User::ROLE_TENANT, 'price');
        $customer = $this->createUser(User::ROLE_CUSTOMER, 'price');
        [$tenant, $menu] = $this->createTenantWithMenu($owner, [], ['price' => 25000, 'stock' => 5]);

        $capturedGrossAmount = null;
        $fakeService = new class($capturedGrossAmount) extends OrderCheckoutService {
            public function __construct(public &$captured)
            {
                parent::__construct();
            }

            protected function requestSnapToken(array $params): string
            {
                $this->captured = $params['transaction_details']['gross_amount'];
                return 'FAKE-SNAP-TOKEN-123';
            }
        };

        $this->app->instance(OrderCheckoutService::class, $fakeService);

        $tamperedCart = [
            $tenant->id_tenant => [
                'tenant_name' => $tenant->name,
                'items' => [
                    $menu->id_menu => [
                        'name' => $menu->name,
                        'price' => 1, // Manipulasi harga dari session
                        'qty' => 2,
                    ],
                ],
            ],
        ];

        $response = $this->actingAs($customer)
            ->withSession(['cart' => $tamperedCart])
            ->post('/checkout/store');

        $response->assertRedirect();
        $this->assertSame(50000, $capturedGrossAmount);

        $order = Order::where('id_user', $customer->id_user)->latest('id_order')->first();
        $this->assertNotNull($order);
        $this->assertSame(50000, $order->total_price);
        $this->assertSame(3, $menu->fresh()->stock);
    }

    public function test_midtrans_webhook_rejects_bad_signature_and_prevents_double_stock_restore(): void
    {
        config(['midtrans.server_key' => 'test-secret-server-key']);

        $owner = $this->createUser(User::ROLE_TENANT, 'hook');
        $customer = $this->createUser(User::ROLE_CUSTOMER, 'hook');
        [$tenant, $menu] = $this->createTenantWithMenu($owner, [], ['price' => 20000, 'stock' => 8]);

        $midtransId = 'TRX-HOOK-' . uniqid();
        $order = Order::create([
            'id_user' => $customer->id_user,
            'id_tenant' => $tenant->id_tenant,
            'transaction_group' => 'TRX-GROUP-' . uniqid(),
            'total_price' => 40000,
            'status' => Order::STATUS_PENDING,
            'payment_status' => Order::PAYMENT_UNPAID,
            'midtrans_transaction_id' => $midtransId,
        ]);

        OrderDetail::create([
            'id_order' => $order->id_order,
            'id_menu' => $menu->id_menu,
            'qty' => 2,
            'subtotal' => 40000,
        ]);

        // 1. Signature tidak valid -> 403
        $this->postJson('/api/midtrans/notification', [
            'order_id' => $midtransId,
            'status_code' => '202',
            'gross_amount' => '40000.00',
            'signature_key' => 'invalid-signature',
            'transaction_status' => 'expire',
        ])->assertStatus(403);

        // 2. Nominal tidak cocok meski signature cocok -> 400
        $wrongAmountSig = hash('sha512', $midtransId . '200' . '1000.00' . 'test-secret-server-key');
        $this->postJson('/api/midtrans/notification', [
            'order_id' => $midtransId,
            'status_code' => '200',
            'gross_amount' => '1000.00',
            'signature_key' => $wrongAmountSig,
            'transaction_status' => 'settlement',
        ])->assertStatus(400);

        // 3. Expire valid -> kembalikan stok 1x (8 -> 10)
        $validExpireSig = hash('sha512', $midtransId . '202' . '40000.00' . 'test-secret-server-key');
        $payload = [
            'order_id' => $midtransId,
            'status_code' => '202',
            'gross_amount' => '40000.00',
            'signature_key' => $validExpireSig,
            'transaction_status' => 'expire',
        ];

        $this->postJson('/api/midtrans/notification', $payload)->assertStatus(200);
        $this->assertSame(10, $menu->fresh()->stock);
        $this->assertSame(Order::STATUS_CANCELLED, $order->fresh()->status);

        // 4. Replay expire webhook yang sama -> stok tidak bertambah dua kali!
        $this->postJson('/api/midtrans/notification', $payload)->assertStatus(200);
        $this->assertSame(10, $menu->fresh()->stock);
    }

    public function test_tenant_cannot_record_other_tenants_menu_in_manual_order(): void
    {
        $ownerA = $this->createUser(User::ROLE_TENANT, 'a');
        $ownerB = $this->createUser(User::ROLE_TENANT, 'b');
        [$tenantA] = $this->createTenantWithMenu($ownerA);
        [, $menuB] = $this->createTenantWithMenu($ownerB);

        $beforeCount = Order::where('id_tenant', $tenantA->id_tenant)->count();

        $response = $this->actingAs($ownerA)->post('/tenant/orders/store-manual', [
            'transaction_date' => date('Y-m-d'),
            'items' => [
                ['id_menu' => $menuB->id_menu, 'qty' => 2],
            ],
        ]);

        $response->assertSessionHas('error');
        $this->assertSame($beforeCount, Order::where('id_tenant', $tenantA->id_tenant)->count());
    }

    public function test_admin_cannot_delete_own_account(): void
    {
        $admin = $this->createUser(User::ROLE_ADMIN, 'self');

        $this->actingAs($admin)
            ->delete("/admin/users/{$admin->id_user}")
            ->assertRedirect(route('admin.users.index'))
            ->assertSessionHas('error');

        $this->assertNotNull(User::find($admin->id_user));
    }

    public function test_customer_cannot_view_another_customers_receipt_and_csv_sanitizes_formulas(): void
    {
        $admin = $this->createUser(User::ROLE_ADMIN, 'csv');
        $owner = $this->createUser(User::ROLE_TENANT, 'csv');
        $custA = $this->createUser(User::ROLE_CUSTOMER, 'a');
        $custB = $this->createUser(User::ROLE_CUSTOMER, 'b');
        [$tenant, $menu] = $this->createTenantWithMenu($owner);

        $order = Order::create([
            'id_user' => $custA->id_user,
            'id_tenant' => $tenant->id_tenant,
            'transaction_group' => 'TRX-IDOR-' . uniqid(),
            'total_price' => 25000,
            'status' => Order::STATUS_COMPLETED,
            'payment_status' => Order::PAYMENT_PAID,
        ]);

        $this->actingAs($custB)->get("/orders/{$order->id_order}/print")->assertStatus(404);

        Review::create([
            'id_user' => $custA->id_user,
            'id_menu' => $menu->id_menu,
            'id_order' => $order->id_order,
            'rating' => 5,
            'comment' => '=CMD|\'/C calc\'!A0',
        ]);

        $csvResponse = $this->actingAs($admin)->get('/admin/reviews/export');
        $csvResponse->assertStatus(200);
        $csvBody = $csvResponse->streamedContent();
        $this->assertStringContainsString("'=CMD|", $csvBody);
    }

    public function test_bad_word_helper_detects_profanity_and_allows_clean_text(): void
    {
        $this->assertTrue(BadWordHelper::check('Makanan ini goblok banget!'));
        $this->assertFalse(BadWordHelper::check('Ayam gepreknya enak, sambalnya pas.'));
    }
}
