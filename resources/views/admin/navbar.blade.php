<link rel="stylesheet" href="{{ asset('css/login.css') }}">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<script src="{{ asset('js/BlockAuth.js') }}"></script>
    
    <aside class="sidebar">
        <img src="{{ asset('images/ubd-logo.png') }}" alt="UBD Logo">
        <nav>
            <a href="{{ route('admin.home') }}" class="{{ request()->routeIs('admin.home') ? 'active' : '' }}">
            <span class="material-icons">home</span> Home
            </a>

            <a href="{{ route('admin.announcement') }}" class="{{ request()->routeIs('admin.announcement') ? 'active' : '' }}">
            <span class="material-icons">campaign</span> Announcement
            </a>

            <a href="{{ route('admin.users') }}" class="{{ request()->routeIs('admin.users') ? 'active' : '' }}">
            <span class="material-icons">group</span> User Management
            </a>

            <a href="{{ route('admin.programs') }}" class="{{ request()->routeIs('admin.programs') ? 'active' : '' }}">
            <span class="material-icons">school</span> Program Management
            </a>

            <a href="{{ route('admin.logs') }}" class="{{ request()->routeIs('admin.logs') ? 'active' : '' }}">
            <span class="material-icons">description</span> Logs
            </a>

            <a href="{{ route('admin.profile') }}" class="{{ request()->routeIs('admin.profile') ? 'active' : '' }}">
            <span class="material-icons">person</span> Profile
            </a>
        </nav>

        <form action="{{ route('logout') }}" method="POST" style="display:inline;">
        @csrf
        <button type="submit" class="logout">
            <span class="material-icons">logout</span> Logout >>>
        </button>
        </form>
    </aside>