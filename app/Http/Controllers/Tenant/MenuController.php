<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\SaveMenuRequest;
use App\Models\Category;
use App\Models\Menu;
use App\Models\Tenant;
use App\Services\MediaUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class MenuController extends Controller
{
    public function __construct(
        private readonly MediaUploadService $mediaUpload
    ) {}

    public function index(): View
    {
        $tenant = $this->requireTenant();

        $menus = Menu::where('id_tenant', $tenant->id_tenant)
            ->with('category')
            ->latest()
            ->get();

        return view('tenant.menus.index', compact('menus'));
    }

    public function create(): View
    {
        $this->requireTenant();
        $categories = Category::all();

        return view('tenant.menus.create', compact('categories'));
    }

    public function store(SaveMenuRequest $request): RedirectResponse
    {
        $tenant = $this->requireTenant();
        $validated = $request->validated();

        $imagePath = $request->hasFile('image')
            ? $this->mediaUpload->uploadImage($request->file('image'), 'menus')
            : null;

        Menu::create([
            'id_tenant' => $tenant->id_tenant,
            'id_category' => $validated['id_category'],
            'name' => $validated['name'],
            'price' => $validated['price'],
            'stock' => $validated['stock'],
            'description' => $validated['description'],
            'image' => $imagePath,
            'is_available' => true,
        ]);

        return redirect()->route('tenant.menus.index')->with('success', 'Menu berhasil ditambahkan.');
    }

    public function edit(int $id): View
    {
        $tenant = $this->requireTenant();
        $menu = Menu::where('id_tenant', $tenant->id_tenant)->findOrFail($id);
        $categories = Category::all();

        return view('tenant.menus.create', compact('menu', 'categories'));
    }

    public function update(SaveMenuRequest $request, int $id): RedirectResponse
    {
        $tenant = $this->requireTenant();
        $menu = Menu::where('id_tenant', $tenant->id_tenant)->findOrFail($id);
        $validated = $request->validated();

        $data = [
            'id_category' => $validated['id_category'],
            'name' => $validated['name'],
            'price' => $validated['price'],
            'stock' => $validated['stock'],
            'description' => $validated['description'],
        ];

        if ($request->hasFile('image')) {
            $data['image'] = $this->mediaUpload->uploadImage($request->file('image'), 'menus');
        }

        $menu->update($data);

        return redirect()->route('tenant.menus.index')->with('success', 'Menu berhasil diperbarui.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $tenant = $this->requireTenant();
        $menu = Menu::where('id_tenant', $tenant->id_tenant)->findOrFail($id);
        $menu->delete();

        return redirect()->route('tenant.menus.index')->with('success', 'Menu berhasil dihapus.');
    }

    public function exportPdf(): View
    {
        $tenant = $this->requireTenant();
        $menus = Menu::where('id_tenant', $tenant->id_tenant)->with('category')->get();

        return view('tenant.menus.pdf', compact('menus', 'tenant'));
    }

    private function requireTenant(): Tenant
    {
        $tenant = Auth::user()?->tenant;
        if (!$tenant) {
            abort(403, 'Akun Anda tidak terhubung dengan gerai tenant.');
        }

        return $tenant;
    }
}
