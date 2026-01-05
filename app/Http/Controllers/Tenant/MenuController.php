<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class MenuController extends Controller
{
    /**
     * Menampilkan daftar menu milik tenant.
     */
    public function index()
    {
        $tenant = Auth::user()->tenant;
        
        if (!$tenant) {
            abort(403, 'Anda bukan tenant.');
        }

        $menus = Menu::where('id_tenant', $tenant->id_tenant)
            ->with('category')
            ->latest()
            ->get();

        return view('tenant.menus.index', compact('menus'));
    }

    /**
     * Menampilkan form tambah menu baru.
     */
    public function create()
    {
        $categories = Category::all();
        return view('tenant.menus.create', compact('categories'));
    }

    /**
     * Menyimpan menu baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'id_category' => 'required|exists:categories,id_category',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $tenant = Auth::user()->tenant;

        $path = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('menus', 'public');
        }

        Menu::create([
            'id_tenant' => $tenant->id_tenant,
            'id_category' => $request->id_category,
            'name' => $request->name,
            'price' => $request->price,
            'stock' => $request->stock,
            'description' => $request->description,
            'image' => $path,
            'is_available' => true,
        ]);

        return redirect()->route('tenant.menus.index')->with('success', 'Menu berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit menu.
     */
    public function edit($id)
    {
        $tenant = Auth::user()->tenant;
        $menu = Menu::where('id_tenant', $tenant->id_tenant)->findOrFail($id);
        $categories = Category::all();
        
        // Menggunakan view create untuk mengedit (form yang sama)
        return view('tenant.menus.create', compact('menu', 'categories'));
    }

    /**
     * Memperbarui data menu.
     */
    public function update(Request $request, $id)
    {
        $tenant = Auth::user()->tenant;
        $menu = Menu::where('id_tenant', $tenant->id_tenant)->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'id_category' => 'required|exists:categories,id_category',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'id_category' => $request->id_category,
            'name' => $request->name,
            'price' => $request->price,
            'stock' => $request->stock,
            'description' => $request->description,
        ];

        // Upload gambar baru jika ada
        if ($request->hasFile('image')) {
            // Hapus gambar lama
            if ($menu->image) {
                Storage::disk('public')->delete($menu->image);
            }
            $data['image'] = $request->file('image')->store('menus', 'public');
        }

        $menu->update($data);

        return redirect()->route('tenant.menus.index')->with('success', 'Menu berhasil diperbarui.');
    }

    /**
     * Menghapus menu dari database.
     */
    public function destroy($id)
    {
        $tenant = Auth::user()->tenant;
        $menu = Menu::where('id_tenant', $tenant->id_tenant)->findOrFail($id);

        if ($menu->image) {
            Storage::disk('public')->delete($menu->image);
        }

        $menu->delete();

        return redirect()->route('tenant.menus.index')->with('success', 'Menu berhasil dihapus.');
    }

    /**
     * Export daftar menu ke PDF.
     */
    public function exportPdf()
    {
        $tenant = Auth::user()->tenant;
        if (!$tenant) abort(403);

        $menus = Menu::where('id_tenant', $tenant->id_tenant)->with('category')->get();

        return view('tenant.menus.pdf', compact('menus', 'tenant'));
    }
}
