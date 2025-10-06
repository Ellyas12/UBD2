<link rel="stylesheet" href="{{ asset('css/login.css') }}">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    
<aside class="sidebar">
    <img src="{{ asset('images/ubd-logo.png') }}" alt="UBD Logo">
    <nav>
        <a href="{{ route('lecturer.home') }}" class="{{ request()->routeIs('lecturer.home') ? 'active' : '' }}">
          <span class="material-icons">home</span> Home
        </a>

        <a href="{{ route('program') }}" class="{{ request()->routeIs('program') ? 'active' : '' }}">
          <span class="material-icons">science</span> Penelitian & PKM
        </a>
    
        <a href="{{ route('profile') }}" class="{{ request()->routeIs('profile') ? 'active' : '' }}">
          <span class="material-icons">person</span> Profile
        </a>

        <a href="{{ route('settings') }}" class="{{ request()->routeIs('settings') ? 'active' : '' }}">
          <span class="material-icons">settings</span> Settings
        </a>
    </nav>

    <form action="{{ route('logout') }}" method="POST" style="display:inline;">
    @csrf
        <button type="submit" class="logout">
          <span class="material-icons">logout</span> Logout >>>
        </button>
    </form>
</aside>