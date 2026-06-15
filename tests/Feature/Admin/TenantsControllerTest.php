<?php

use App\Models\Tenant;
use App\Models\User;

beforeEach(function () {
    $this->admin = User::factory()->create([
        'role' => 'admin',
        'tenant_id' => null,
    ]);
});

test('admin can view tenants list', function () {
    Tenant::factory()->create(['name' => 'Zenith Labs', 'is_active' => true]);
    Tenant::factory()->create(['name' => 'Acme School', 'is_active' => false]);

    $response = $this
        ->actingAs($this->admin)
        ->get(route('admin.tenants.index'));

    $response
        ->assertOk()
        ->assertViewIs('admin.tenants.index')
        ->assertSee('Acme School')
        ->assertSee('Zenith Labs');

    expect($response->viewData('tenants')->pluck('name')->all())
        ->toBe(['Acme School', 'Zenith Labs']);
});

test('admin can filter tenants by search and status', function () {
    Tenant::factory()->create(['name' => 'Acme Active', 'is_active' => true]);
    Tenant::factory()->create(['name' => 'Acme Inactive', 'is_active' => false]);
    Tenant::factory()->create(['name' => 'Beta Active', 'is_active' => true]);

    $response = $this
        ->actingAs($this->admin)
        ->get(route('admin.tenants.index', [
            'search' => 'Acme',
            'status' => '1',
        ]));

    $response
        ->assertOk()
        ->assertSee('Acme Active')
        ->assertDontSee('Acme Inactive')
        ->assertDontSee('Beta Active');

    expect($response->viewData('tenants')->pluck('name')->all())
        ->toBe(['Acme Active']);
});

test('admin can view create tenant form', function () {
    $response = $this
        ->actingAs($this->admin)
        ->get(route('admin.tenants.create'));

    $response
        ->assertOk()
        ->assertViewIs('admin.tenants.tenant')
        ->assertViewHas('tenant', null)
        ->assertSee('Create Tenant');
});

test('admin can store a tenant', function () {
    $response = $this
        ->actingAs($this->admin)
        ->post(route('admin.tenants.store'), [
            'name' => 'New Tenant',
            'is_active' => '1',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('admin.tenants.index'))
        ->assertSessionHas('success', 'Tenant created successfully.');

    $this->assertDatabaseHas('tenants', [
        'name' => 'New Tenant',
        'is_active' => true,
    ]);
});

test('tenant store requires valid input', function () {
    $response = $this
        ->actingAs($this->admin)
        ->from(route('admin.tenants.create'))
        ->post(route('admin.tenants.store'), [
            'name' => '',
            'is_active' => 'not-a-boolean',
        ]);

    $response
        ->assertRedirect(route('admin.tenants.create'))
        ->assertSessionHasErrors(['name', 'is_active']);
});

test('admin can view edit tenant form', function () {
    $tenant = Tenant::factory()->create([
        'name' => 'Editable Tenant',
        'is_active' => true,
    ]);

    $response = $this
        ->actingAs($this->admin)
        ->get(route('admin.tenants.tenant', $tenant));

    $response
        ->assertOk()
        ->assertViewIs('admin.tenants.tenant')
        ->assertViewHas('tenant', $tenant)
        ->assertSee('Edit Tenant')
        ->assertSee('Editable Tenant');
});

test('admin can update a tenant', function () {
    $tenant = Tenant::factory()->create([
        'name' => 'Old Name',
        'is_active' => true,
    ]);

    $response = $this
        ->actingAs($this->admin)
        ->put(route('admin.tenants.update', $tenant), [
            'name' => 'Updated Name',
            'is_active' => '0',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('admin.tenants.index'))
        ->assertSessionHas('success', 'Tenant updated successfully.');

    $this->assertDatabaseHas('tenants', [
        'id' => $tenant->id,
        'name' => 'Updated Name',
        'is_active' => false,
    ]);
});

test('tenant update requires valid input', function () {
    $tenant = Tenant::factory()->create();

    $response = $this
        ->actingAs($this->admin)
        ->from(route('admin.tenants.tenant', $tenant))
        ->put(route('admin.tenants.update', $tenant), [
            'name' => '',
            'is_active' => 'not-a-boolean',
        ]);

    $response
        ->assertRedirect(route('admin.tenants.tenant', $tenant))
        ->assertSessionHasErrors(['name', 'is_active']);
});

test('admin can toggle tenant status and preserve list query parameters', function () {
    $tenant = Tenant::factory()->create([
        'name' => 'Toggle Tenant',
        'is_active' => true,
    ]);

    $response = $this
        ->actingAs($this->admin)
        ->patch(route('admin.tenants.status', [
            'tenant' => $tenant,
            'search' => 'Toggle',
            'status' => '1',
            'page' => '2',
        ]));

    $response
        ->assertRedirect(route('admin.tenants.index', [
            'search' => 'Toggle',
            'status' => '1',
            'page' => '2',
        ]))
        ->assertSessionHas('success', 'Tenant deactivated.');

    $this->assertDatabaseHas('tenants', [
        'id' => $tenant->id,
        'is_active' => false,
    ]);
});

test('non admin users cannot access tenant administration', function () {
    $user = User::factory()->create([
        'role' => 'user',
        'tenant_id' => null,
    ]);

    $response = $this
        ->actingAs($user)
        ->get(route('admin.tenants.index'));

    $response->assertForbidden();
});
