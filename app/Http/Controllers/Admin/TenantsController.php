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

        return view('admin.tenants.index', compact('tenants'));
    }

    public function create()
    {
        return view('admin.tenants.tenant', [
            'tenant' => null
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'is_active' => 'required|boolean',
        ]);

        Tenant::create($validated);

        return redirect()
            ->route('admin.tenants.index')
            ->with('success', 'Tenant created successfully.');
    }

    public function status(Tenant $tenant)
    {
        $tenant->is_active = ! $tenant->is_active;
        $tenant->save();

        $message = $tenant->is_active ? 'Tenant activated.' : 'Tenant deactivated.';

        // Preserve filters / pagination when redirecting back to the list
        return redirect()
            ->route('admin.tenants.index', request()->query())
            ->with('success', $message);
    }

    public function edit(Tenant $tenant)
    {
        return view('admin.tenants.tenant', compact('tenant'));
    }

    public function update(Request $request, Tenant $tenant)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'is_active' => 'required|boolean',
        ]);

        $tenant->update($validated);

        return redirect()
            ->route('admin.tenants.index')
            ->with('success', 'Tenant updated successfully.');
    }
}
