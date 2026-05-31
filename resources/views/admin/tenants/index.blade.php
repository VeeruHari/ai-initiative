<x-app-layout class="text-sm">
    <!-- MAIN CONTENT -->
    <div class="flex">
        <!-- LEFT SIDEBAR -->
        @include('partials.admin-sidebar')

        <!-- RIGHT CONTENT -->
        <div class="flex-1 p-6 bg-gray-100 min-h-screen">

            @if(session('success'))
            <div class="bg-white p-2 rounded-2xl shadow mb-4 h-auto">
                {{ session('success') }}
            </div>
            @endif

            <div class="bg-white p-6 rounded-2xl shadow">
                <!-- HEADER -->
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-semibold">Tenants List</h3>

                    <a href="{{ route('admin.tenants.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                        + Add Tenant
                    </a>
                </div>

                <!-- SEARCH + FILTER -->
                <form method="GET" action="{{ route('admin.tenants.index') }}" class="mb-4 flex gap-3">
                    <input type="text" name="search"
                        value="{{ request('search') }}"
                        placeholder="Search name"
                        class="border rounded px-3 py-2">

                    <select name="status" class="border rounded px-10 py-2">
                        <option value="">All Status</option>
                        <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inactive</option>
                    </select>

                    <button class="bg-blue-600 text-white px-4 rounded">Filter</button>

                    <a href="{{ route('admin.tenants.index') }}" class="bg-gray-300 px-4 rounded">Reset</a>
                </form>

                <!-- TABLE -->
                <div class="overflow-x-auto w-full">
                    <table class="w-full border border-gray-200 rounded-lg overflow-hidden table-auto">

                        <thead class="bg-gray-100">
                            <tr>
                                <td class="text-left p-3 text-left">#</td>
                                <td class="text-left p-3 text-left">Name</td>
                                <td class="text-left p-3 text-left">Status</td>
                                <td class="text-left p-3 text-right"></td>
                                <td class="text-left p-3 text-right"></td>
                            </tr>
                        </thead>

                        <tbody class="divide-y">

                            @forelse($tenants ?? [] as $tenant)
                            <tr class="hover:bg-gray-50">

                                <td class="text-left p-3">{{ $loop->iteration }}</td>

                                <td class="text-left p-3 text-sm">
                                    <a href="{{ route('admin.users.index', $tenant->id) }}">{{ $tenant->name }}</a>
                                </td>

                                <td class="text-left p-3">
                                    <span class="px-2 py-1 text-sm rounded-full
                                            {{ $tenant->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                        {{ $tenant->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>

                                <td class="text-right p-3 space-x-2">
                                    <form method="POST" action="{{ route('admin.tenants.status', $tenant) . '?' . http_build_query(request()->query()) }}">
                                        @csrf
                                        @method('PATCH')

                                        <button type="submit"
                                            title="{{ $tenant->is_active ? 'Deactivate Tenant' : 'Activate Tenant' }}"
                                            aria-label="{{ $tenant->is_active ? 'Deactivate Tenant' : 'Activate Tenant' }}"
                                            class="inline-flex items-center justify-center w-10 h-10 rounded-full
                                                    focus:outline-none transition-colors
                                                    {{ $tenant->is_active ? 'bg-red-50 text-red-600 hover:bg-red-100' : 'bg-green-50 text-green-600 hover:bg-green-100' }}">
                                            @if($tenant->is_active)
                                            <!-- Toggle ON (click to deactivate) -->
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                                <rect x="1" y="5" width="22" height="14" rx="7" ry="7"></rect>
                                                <circle cx="17" cy="12" r="3"></circle>
                                            </svg>
                                            @else
                                            <!-- Toggle OFF (click to activate) -->
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                                <rect x="1" y="5" width="22" height="14" rx="7" ry="7"></rect>
                                                <circle cx="7" cy="12" r="3"></circle>
                                            </svg>
                                            @endif
                                        </button>
                                    </form>
                                </td>

                                <td class="text-right p-3 space-x-2">
                                    <a href="{{ route('admin.tenants.tenant', $tenant)}}"
                                        title="Edit Tenant" aria-label="Edit tenant"
                                        class="inline-flex items-center text-blue-600 hover:underline">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536M9 11l6-6 3 3-6 6H9v-3z" />
                                        </svg>
                                    </a>

                                    <a href="#" title="Delete Tenant" aria-label="Delete tenant"
                                        class="inline-flex items-center text-red-600 hover:underline ml-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3" />
                                        </svg>
                                    </a>
                                </td>

                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-left pt-4">No tenants found</td>
                            </tr>
                            @endforelse

                        </tbody>

                    </table>
                </div>

                <!-- PAGINATION -->
                <div class="mt-4">
                    {{ $tenants->links() ?? '' }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>