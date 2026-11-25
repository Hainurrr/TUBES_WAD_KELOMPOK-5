<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\SaveTenantRequest;
use App\Models\Tenant;
use App\Models\User;
use App\Services\MediaUploadService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TenantController extends Controller
{
    public function __construct(
        private readonly MediaUploadService $mediaUpload
    ) {}

    public function index(): View
    {
        $tenants = $this->tenantsWithAccountName();

        return view('admin.tenants.index', compact('tenants'));
    }

    public function create(): View
    {
        $users = User::select('id_user', 'name', 'email')->get();
        $categories = Tenant::distinctCategories();

        return view('admin.tenants.create', compact('users', 'categories'));
    }

    public function store(SaveTenantRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $data = [
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'category' => $request->resolvedCategory(),
            'owner_name' => $validated['owner_name'],
            'id_user' => $validated['id_user'] ?? null,
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'city' => $validated['city'],
            'is_active' => true,
        ];

        if ($request->hasFile('banner_image')) {
            $data['banner_image'] = $this->mediaUpload->uploadImage($request->file('banner_image'), 'tenants');
        }

        Tenant::create($data);

        return redirect()->route('admin.tenants.index')->with('success', 'Tenant berhasil ditambahkan!');
    }

    public function edit(int $id): View|RedirectResponse
    {
        $tenant = Tenant::find($id);
        if (!$tenant) {
            return redirect()->route('admin.tenants.index')->with('error', 'Tenant tidak ditemukan.');
        }

        $users = User::select('id_user', 'name', 'email')->get();
        $categories = Tenant::distinctCategories();

        return view('admin.tenants.edit', compact('tenant', 'users', 'categories'));
    }

    public function update(SaveTenantRequest $request, int $id): RedirectResponse
    {
        $tenant = Tenant::find($id);
        if (!$tenant) {
            return redirect()->route('admin.tenants.index')->with('error', 'Tenant tidak ditemukan.');
        }

        $validated = $request->validated();

        $data = [
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'category' => $request->resolvedCategory(),
            'owner_name' => $validated['owner_name'],
            'id_user' => $validated['id_user'] ?? null,
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'city' => $validated['city'],
        ];

        if ($request->hasFile('banner_image')) {
            $data['banner_image'] = $this->mediaUpload->uploadImage($request->file('banner_image'), 'tenants');
        }

        $tenant->update($data);

        return redirect()->route('admin.tenants.index')->with('success', 'Data Tenant berhasil diperbarui!');
    }

    public function exportPdf(): View
    {
        $tenants = $this->tenantsWithAccountName();

        return view('admin.tenants.pdf', compact('tenants'));
    }

    public function destroy(int $id): RedirectResponse
    {
        $tenant = Tenant::findOrFail($id);
        $tenant->delete();

        return redirect()->route('admin.tenants.index')->with('success', 'Tenant berhasil dihapus!');
    }

    private function tenantsWithAccountName(): Collection
    {
        return Tenant::query()
            ->leftJoin('users', 'tenants.id_user', '=', 'users.id_user')
            ->select('tenants.*', 'users.name as user_account_name')
            ->get();
    }
}
