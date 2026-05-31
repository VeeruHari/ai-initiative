<x-app-layout class="text-sm">
    <div class="flex">
        @include('partials.admin-sidebar')

        <div class="flex-1 p-6 bg-gray-100 min-h-screen">
            <div class="bg-white p-6 rounded-2xl shadow">

                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-semibold">{{ $tenant ? 'Edit Tenant' : 'Create Tenant' }}</h3>

                    <a href="{{ route('admin.tenants.index') . (count(request()->query()) ? '?' . http_build_query(request()->query()) : '') }}"
                       class="bg-gray-300 px-4 py-2 rounded-lg hover:bg-gray-400">
                        ← Back to List
                    </a>
                </div>

                @if(session('status'))
                    <div class="mb-4 p-3 bg-green-50 text-green-700 rounded">
                        {{ session('status') }}
                    </div>
                @endif

                <!-- EDIT FORM -->
                <form method="POST" action="{{ $tenant ? route('admin.tenants.update', $tenant) : route('admin.tenants.store') }}" class="space-y-4">
                    @csrf

                    @if($tenant)
                        @method('PUT')
                    @endif

                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                        <input id="name" name="name" type="text"
                               value="{{ old('name', $tenant->name ?? '') }}"
                               class="w-full border rounded px-3 py-2 @error('name') border-red-500 @enderror"
                               required>
                        @error('name')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="is_active" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select id="is_active" name="is_active" class="border rounded px-10 py-2">
                            <option value="1" {{ old('is_active', $tenant->is_active ?? '') == 1 ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('is_active', $tenant->is_active ?? '') == 0 ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('is_active')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- add other editable fields here as needed -->

                    <div class="flex items-center gap-3 mt-4">
                        <button type="submit"
                                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                            Save Changes
                        </button>

                        <a href="{{ route('admin.tenants.index') . (count(request()->query()) ? '?' . http_build_query(request()->query()) : '') }}"
                           class="bg-gray-200 px-4 py-2 rounded hover:bg-gray-300">
                            Cancel
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
