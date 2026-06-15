<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\UserService;
use Illuminate\Http\Request;
use App\Models\Tenant;
use App\Models\User;

class OwnersController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     */
    public function list(Request $request)
    {
        $tenantId = Auth::user()->tenant_id;

        $tenant = Tenant::findOrFail($tenantId);

        $search = trim((string) $request->query('search', ''));
        $status = $request->query('status', '');

        $users = $this->userService->getUsers($tenantId, $search, $status);

        return view('users.index', compact('users', 'tenantId', 'tenant'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.user', [
            'user' => null,
            'tenant' => Auth::user()->tenant_id,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'is_active' => ['required', 'boolean']
        ]);

        $this->userService->createUser([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'is_active' => false,
            'is_owner' => false,
            'tenant_id' => Auth::user()->tenant_id,
        ]);

        return redirect()
            ->route('users.users-list')
            ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::where('tenant_id', Auth::user()->tenant_id)->findOrFail($id);
        return view('users.user', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'is_active' => 'required|boolean',
        ]);

        $user->update($validated);

        return redirect()
            ->route('users.users-list')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
