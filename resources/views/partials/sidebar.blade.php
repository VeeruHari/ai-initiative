<aside class="w-64 min-h-screen bg-gray-800 text-white p-4">
    <nav class="space-y-2">
        <a href="{{ route('admin.dashboard') }}" class="block p-2 rounded hover:bg-gray-700">
            Dashboard
        </a>
        <!-- <a href="#" class="block p-2 rounded hover:bg-gray-700">
            Tenants
        </a> -->
        <a href="{{ route('logout') }}"
        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
        class="block p-2 rounded hover:bg-gray-700">
            Logout
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
            @csrf
        </form>
    </nav>
</aside>