<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tenant;
use App\Models\User;
use Cloudinary\Cloudinary;

class TenantController extends Controller
{
    /**
     * Menampilkan daftar tenant.
     */
    public function index()
    {
        $tenants = \DB::table('tenants')
            ->leftJoin('users', 'tenants.id_user', '=', 'users.id_user')
            ->select('tenants.*', 'users.name as user_account_name')
            ->get();

        return view('admin.tenants.index', compact('tenants'));
    }

    /**
     * Menampilkan form tambah tenant baru.
     */
    public function create()
    {
        $users = \DB::table('users')->select('id_user', 'name', 'email')->get();
        $categories = \DB::table('tenants')->whereNotNull('category')->distinct()->pluck('category');
        return view('admin.tenants.create', compact('users', 'categories'));
    }

    /**
     * Menyimpan tenant baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:100',
            'category_select' => 'nullable|string',
            'category_custom' => 'nullable|string|max:100',
            'owner_name' => 'required|string|max:255',
            'id_user' => 'nullable|exists:users,id_user|unique:tenants,id_user',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'city' => 'required|string',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Menentukan kategori (pilih dari dropdown atau input custom)
        $category = $request->category_select;
        if ($category === 'Lainnya' || empty($category)) {
            $category = $request->category_custom;
        }

        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'category' => $category,
            'owner_name' => $request->owner_name,
            'id_user' => $request->id_user,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // Upload gambar banner jika ada
        if ($request->hasFile('banner_image')) {
            $cloudinary = new Cloudinary(env('CLOUDINARY_URL'));
            $result = $cloudinary->uploadApi()->upload(
                $request->file('banner_image')->getRealPath(),
                ['folder' => 'tenants']
            );
            $data['banner_image'] = $result['secure_url'];
        }

        \DB::table('tenants')->insert($data);

        return redirect()->route('admin.tenants.index')->with('success', 'Tenant berhasil ditambahkan!');
    }

    /**
     * Menampilkan form edit tenant.
     */
    public function edit($id)
    {
        $tenant = \DB::table('tenants')->where('id_tenant', $id)->first();
        if (!$tenant) {
            return redirect()->route('admin.tenants.index')->with('error', 'Tenant tidak ditemukan');
        }
        $users = \DB::table('users')->select('id_user', 'name', 'email')->get();
        $categories = \DB::table('tenants')->whereNotNull('category')->distinct()->pluck('category');
        return view('admin.tenants.edit', compact('tenant', 'users', 'categories'));
    }

    /**
     * Memperbarui data tenant.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:100',
            'category_select' => 'nullable|string',
            'category_custom' => 'nullable|string|max:100',
            'owner_name' => 'required|string|max:255',
            'id_user' => 'nullable|exists:users,id_user|unique:tenants,id_user,' . $id . ',id_tenant',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'city' => 'required|string',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Menentukan kategori
        $category = $request->category_select;
        if ($category === 'Lainnya' || empty($category)) {
            $category = $request->category_custom;
        }

        $tenant = \DB::table('tenants')->where('id_tenant', $id)->first();
        if (!$tenant) {
            return redirect()->route('admin.tenants.index')->with('error', 'Tenant tidak ditemukan');
        }

        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'category' => $category,
            'owner_name' => $request->owner_name,
            'id_user' => $request->id_user,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'updated_at' => now(),
        ];

        // Update gambar banner jika ada file baru
        if ($request->hasFile('banner_image')) {
            // Upload ke Cloudinary
            $cloudinary = new Cloudinary(env('CLOUDINARY_URL'));
            $result = $cloudinary->uploadApi()->upload(
                $request->file('banner_image')->getRealPath(),
                ['folder' => 'tenants']
            );
            $data['banner_image'] = $result['secure_url'];
        }

        \DB::table('tenants')->where('id_tenant', $id)->update($data);

        return redirect()->route('admin.tenants.index')->with('success', 'Data Tenant berhasil diperbarui!');
    }

    /**
     * Export daftar tenant ke PDF.
     */
    public function exportPdf()
    {
        $tenants = \DB::table('tenants')
            ->leftJoin('users', 'tenants.id_user', '=', 'users.id_user')
            ->select('tenants.*', 'users.name as user_account_name')
            ->get();
            
        return view('admin.tenants.pdf', compact('tenants'));
    }
    
    /**
     * Menghapus tenant dari database.
     */
    public function destroy($id)
    {
        \DB::table('tenants')->where('id_tenant', $id)->delete();
        return redirect()->route('admin.tenants.index')->with('success', 'Tenant berhasil dihapus!');
    }
}
