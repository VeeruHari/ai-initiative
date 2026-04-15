<nav class="navbar navbar-light bg-light">
    <div class="container-fluid">
        <span class="navbar-brand mb-0 h1">Dashboard</span>

        <div>
            <span>{{ auth()->user()->name ?? 'Admin' }}</span>
            <a href="{{ route('logout') }}" class="btn btn-sm btn-danger ms-2">Logout</a>
        </div>
    </div>
</nav>