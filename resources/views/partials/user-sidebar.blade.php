<aside class="w-64 min-h-screen bg-gray-800 text-white p-4">
    <nav class="space-y-2">
        <a href="{{ route('users.dashboard') }}" class="block p-2 rounded hover:bg-gray-700">
            Dashboard
        </a>

        @if(auth()->check() && auth()->user()->is_owner)
            <a href="{{ route('users.users-list') }}" class="block p-2 rounded hover:bg-gray-700">
                Users
            </a>
        @endif

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