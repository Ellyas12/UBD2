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
  <!-- Sidebar -->
  <aside class="sidebar">
  <img src="{{ asset('images/ubd-logo.png') }}" alt="UBD Logo">

  <nav>
    <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">
      <span class="material-icons">home</span> Dashboard
    </a>

    <a href="{{ route('penelitian') }}" class="{{ request()->routeIs('penelitian') ? 'active' : '' }}">
      <span class="material-icons">science</span> Penelitian
    </a>

    <a href="{{ route('pkm') }}" class="{{ request()->routeIs('pkm') ? 'active' : '' }}">
      <span class="material-icons">lightbulb</span> PKM
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
    <button type="submit" class="logout">Logout >>></button>
  </form>
</aside>

  <!-- Main content -->
  <main class="main-content">
    <div class="announcement-box">
      Announcement :
    </div>

    <div class="section-title">Penelitian anda :</div>
    <div class="section-content">
      <!-- Your research data here -->
    </div>

    <div class="section-title">Penelitian lain :</div>
    <div class="section-content">
    </div>
  </main>
</div>
</form>
</body>
</html>