<x-app-layout>
    <!-- MAIN CONTENT -->
    <div class="flex">
        <!-- LEFT SIDEBAR -->
        @include('partials.user-sidebar')

        <!-- RIGHT CONTENT -->
        <div class="flex-1 p-6 bg-gray-100 min-h-screen">
            <div class="bg-white p-6 rounded shadow">
                <h3 class="text-lg font-bold mb-4">Welcome, {{ auth()->user()->name }}</h3>

                <p>This is your admin panel content area.</p>
            </div>
        </div>
    </div>
</x-app-layout>