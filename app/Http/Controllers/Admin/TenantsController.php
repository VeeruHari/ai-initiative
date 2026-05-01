<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tenant;

class TenantsController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));
        $status = $request->query('status', null);

        $tenants = Tenant::query()
            ->when($search !== '', fn($q) => $q->where('name', 'like', "%{$search}%"))
            ->when($status !== null && $status !== '', fn($q) => $q->where('is_active', filter_var($status, FILTER_VALIDATE_BOOLEAN)))
            ->orderby('name', 'asc')
            ->paginate(20)
            ->withQueryString();

        return view('admin.tenants', compact('tenants'));
    }
}
