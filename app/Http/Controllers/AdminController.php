<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Menampilkan Dashboard Admin.
     */
    public function dashboard()
    {
        // Hitung statistik
        $totalTenants = DB::table('tenants')->count();
        $activeTenants = DB::table('tenants')->where('is_open', true)->count();
        $inactiveTenants = DB::table('tenants')->where('is_open', false)->count();

        // Kirim data ke view
        return view('admin.dashboard', compact('totalTenants', 'activeTenants', 'inactiveTenants'));
    }
}
