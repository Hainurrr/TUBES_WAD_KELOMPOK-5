<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function dashboard(): View
    {
        $totalTenants = Tenant::count();
        $activeTenants = Tenant::where('is_open', true)->count();
        $inactiveTenants = max(0, $totalTenants - $activeTenants);

        return view('admin.dashboard', compact('totalTenants', 'activeTenants', 'inactiveTenants'));
    }
}
