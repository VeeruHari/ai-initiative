<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Password;

class UsersController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tenantId = $request->tenant;

        $tenant = Tenant::findOrFail($tenantId);

        $search = trim((string) $request->query('search', ''));

        $users = User::query()
            ->when($search !== '', fn($q) => $q->where('name', 'like', "%{$search}%"))
            ->where('tenant_id', $tenantId)
            ->orderby('name', 'asc')
            ->paginate(20)
            ->withQueryString();

        return view('admin.users.index', compact('users', 'tenantId', 'tenant'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Tenant $tenant)
    {
        return view('admin.users.user', [
            'user' => null,
            'tenant' => $tenant,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Tenant $tenant)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'is_active' => ['required', 'boolean']
        ]);

        $password = $this->generateRandomPassword();

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($password),
            'is_active' => $validated['is_active'],
            'tenant_id' => $tenant->id,
        ]);

        Password::sendResetLink([
            'email' => $validated['email']
        ]);

        return redirect()
            ->route('admin.users.index', $request->tenant)
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
    public function edit(Tenant $tenant, User $user)
    {
        return view('admin.users.user', compact('tenant', 'user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tenant $tenant, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'is_active' => 'required|boolean',
        ]);

        $user->update($validated);

        return redirect()
            ->route('admin.users.index', $tenant->id)
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    private function generateRandomPassword(): string
    {
        return Str::password(
            length: 12,
            letters: true,
            numbers: true,
            symbols: true
        );
    }
}
