<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Home | UBD</title>
  <link rel="stylesheet" href="{{ asset('css/login.css') }}">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>
  <div class="dashboard-container">
  <aside class="sidebar">
  <img src="{{ asset('images/ubd-logo.png') }}" alt="UBD Logo">

  <nav>
    <a href="{{ route('home') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
      <i class="fas fa-home"></i> Dashboard
    </a>

    <a href="{{ route('penelitian') }}" class="{{ request()->routeIs('penelitian') ? 'active' : '' }}">
      <i class="fas fa-flask"></i> Academmic Management
    </a>

    <a href="{{ route('pkm') }}" class="{{ request()->routeIs('pkm') ? 'active' : '' }}">
      <i class="fas fa-lightbulb"></i> User & Role Management
    </a>

    <a href="{{ route('profile') }}" class="{{ request()->routeIs('profile') ? 'active' : '' }}">
      <i class="fas fa-user"></i> Storage Management
    </a>

    <a href="{{ route('profile') }}" class="{{ request()->routeIs('profile') ? 'active' : '' }}">
      <i class="fas fa-user"></i> Profile
    </a>


    <a href="{{ route('settings') }}" class="{{ request()->routeIs('settings') ? 'active' : '' }}">
      <i class="fas fa-cog"></i> Settings
    </a>
  </nav>

  <form action="{{ route('logout') }}" method="POST" style="display:inline;">
    @csrf
    <button type="submit" class="logout">Logout >>></button>
  </form>
</aside>

  <main class="main-content">
    <div class="announcement-box">
      Announcement :
    </div>

    <div class="section-title">Penelitian lain :</div>
    <div class="section-content">
    </div>
  </main>
</div>
</form>
</body>
</html>