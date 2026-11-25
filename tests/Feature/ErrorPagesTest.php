<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ErrorPagesTest extends TestCase
{
    use DatabaseTransactions;

    public function test_unknown_route_returns_custom_404_error_page(): void
    {
        $response = $this->get('/halaman-tidak-ditemukan-uji-404');

        $response->assertStatus(404);
        $response->assertSee('404');
        $response->assertSee('Halaman yang kamu cari tidak tersedia.');
        $response->assertSee('Cari Menu');
        $response->assertSee('Kembali ke Beranda');
        $response->assertSee('Kantin Gedung TULT');
        $response->assertSee('Kantin Gedung GKU');
        $response->assertSee('Pujasera Kampus');
        $response->assertDontSee('cdn.jsdelivr.net');
        $response->assertDontSee('fonts.googleapis.com');
    }

    public function test_unauthorized_role_request_returns_custom_403_error_page(): void
    {
        $customer = User::create([
            'name' => 'Pelanggan Uji 403',
            'email' => 'uji_403_' . uniqid() . '@example.test',
            'password' => Hash::make('Password123!'),
            'role' => User::ROLE_CUSTOMER,
        ]);

        $response = $this->actingAs($customer)->get('/admin/dashboard');

        $response->assertStatus(403);
        $response->assertSee('403');
        $response->assertSee('Kamu tidak memiliki izin ke halaman ini.');
        $response->assertSee('Kembali ke Beranda');
        $response->assertSee('Masuk ke Akun');
        $response->assertSee('Pemisahan Area Pengguna');
        $response->assertDontSee('cdn.jsdelivr.net');
        $response->assertDontSee('fonts.googleapis.com');
    }

    public function test_500_and_503_error_views_render_standalone_without_external_cdn(): void
    {
        $html500 = view('errors.500')->render();

        $this->assertStringContainsString('500', $html500);
        $this->assertStringContainsString('Terjadi kendala saat memproses permintaanmu.', $html500);
        $this->assertStringContainsString('Muat Ulang Halaman', $html500);
        $this->assertStringContainsString('Kembali ke Beranda', $html500);
        $this->assertStringContainsString('Data Transaksi Tetap Aman', $html500);
        $this->assertStringNotContainsString('cdn.jsdelivr.net', $html500);
        $this->assertStringNotContainsString('fonts.googleapis.com', $html500);

        $html503 = view('errors.503')->render();

        $this->assertStringContainsString('503', $html503);
        $this->assertStringContainsString('TelyuCan sedang dalam pemeliharaan singkat.', $html503);
        $this->assertStringContainsString('Muat Ulang Halaman', $html503);
        $this->assertStringContainsString('Kembali ke Beranda', $html503);
        $this->assertStringContainsString('Akan Segera Pulih', $html503);
        $this->assertStringNotContainsString('cdn.jsdelivr.net', $html503);
        $this->assertStringNotContainsString('fonts.googleapis.com', $html503);
    }
}
