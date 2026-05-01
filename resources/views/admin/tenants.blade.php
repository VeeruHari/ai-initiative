<x-app-layout>
    <!-- MAIN CONTENT -->
    <div class="flex">
        <!-- LEFT SIDEBAR -->
        @include('partials.sidebar')

        <!-- RIGHT CONTENT -->
        <div class="flex-1 p-6 bg-gray-100 min-h-screen">
            <div class="bg-white p-6 rounded-2xl shadow">

                <!-- HEADER -->
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-semibold">Tenants List</h3>

                    <a href="#" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                        + Add Tenant
                    </a>
                </div>

                <!-- SEARCH + FILTER -->
                <form method="GET" action="{{ route('admin.tenants') }}" class="mb-4 flex gap-3">
                    <input type="text" name="search"
                        value="{{ request('search') }}"
                        placeholder="Search name"
                        class="border rounded px-3 py-2">

                    <select name="status" class="border rounded px-3 py-2">
                        <option value="">All Status</option>
                        <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inactive</option>
                    </select>

                    <button class="bg-blue-600 text-white px-4 rounded">Filter</button>

                    <a href="{{ route('admin.tenants') }}" class="bg-gray-300 px-4 rounded">Reset</a>
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
                            </tr>
                        </thead>

                        <tbody class="divide-y">

                            @foreach($tenants ?? [] as $tenant)
                            <tr class="hover:bg-gray-50">

                                <td class="text-left p-3">{{ $loop->iteration }}</td>

                                <td class="text-left p-3 font-medium">
                                    {{ $tenant->name }}
                                </td>

                                <td class="text-left p-3">
                                    <span class="px-2 py-1 text-xs rounded-full
                                        {{ $tenant->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                        {{ $tenant->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>

                                <td class="text-right p-3 space-x-2">
                                    <a href="#" class="text-blue-600 hover:underline">Edit</a>
                                    <a href="#" class="text-red-600 hover:underline">Delete</a>
                                </td>

                            </tr>
                            @endforeach

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