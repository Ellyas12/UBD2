<link rel="stylesheet" href="{{ asset('css/Sidebar.css') }}">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<script src="{{ asset('js/Block.js') }}"></script>
<script src="{{ asset('js/Sidebar.js') }}"></script>
    
<aside class="sidebar-container" id="sidebar">

    <div class="sidebar-top-container">
        <button class="sidebar-hamburger" id="sidebarToggle">
            <span class="material-icons">menu</span>
        </button>
    </div>

    <div class="sidebar-logo-container">
        <img src="{{ asset('images/ubd-logo.png') }}" alt="UBD Logo">
    </div>

    <nav class="sidebar-nav">
        <a href="{{ route('admin.home') }}" class="sidebar-link {{ request()->routeIs('admin.home') ? 'active' : '' }}">
            <span class="material-icons">home</span>
            <span class="sidebar-label">Home</span>
        </a>

        <a href="{{ route('admin.announcement') }}" class="sidebar-link {{ request()->routeIs('admin.announcement') ? 'active' : '' }}">
            <span class="material-icons">campaign</span>
            <span class="sidebar-label">Announcement</span>
        </a>

        <a href="{{ route('admin.programs') }}" class="sidebar-link {{ request()->routeIs('admin.programs') ? 'active' : '' }}">
            <span class="material-icons">science</span>
            <span class="sidebar-label">Managemen Penelitian dan PKM</span>
        </a>

        <a href="{{ route('admin.users') }}" class="sidebar-link {{ request()->routeIs('admin.users') ? 'active' : '' }}">
            <span class="material-icons">group</span>
            <span class="sidebar-label">Managemen Users</span>
        </a>

        <a href="{{ route('admin.logs') }}" class="sidebar-link {{ request()->routeIs('admin.logs') ? 'active' : '' }}">
            <span class="material-icons">description</span>
            <span class="sidebar-label">Logs</span>
        </a>

        <a href="{{ route('admin.profile') }}" class="sidebar-link {{ request()->routeIs('admin.profile') ? 'active' : '' }}">
            <span class="material-icons">person</span>
            <span class="sidebar-label">Profile</span>
        </a>
        </nav>

        <form action="{{ route('logout') }}" method="POST" style="display:inline;"> 
      @csrf
      <div class="sidebar-bottom-container">
        <button type="submit" class="sidebar-logout-btn">
            <span class="material-icons">logout</span>
            <span class="sidebar-label">LOGOUT</span>
        </button>
      </div>
    </form>
    </aside>