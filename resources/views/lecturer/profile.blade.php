<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Profile | UBD</title>
  <link rel="stylesheet" href="{{ asset('css/login.css') }}">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>
  <div class="dashboard-container">
    
    <aside class="sidebar">
      <img src="{{ asset('images/ubd-logo.png') }}" alt="UBD Logo">

      <nav>
        <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">
          <span class="material-icons">home</span> Home
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
        <button type="submit" class="logout">
          <span class="material-icons">logout</span> Logout >>>
        </button>
      </form>
    </aside>

    <main class="main-content">
      <div class="profile-container">
        
        <div class="profile-sidebar">
          <div class="profile-avatar">
            <img src="{{ asset('images/avatar.jpg') }}" alt="Profile Photo">
          </div>

          <ul class="profile-menu">
            <li class="active">Personal Information</li>
            <li>Academic & Professional info</li>
            <li>Research & Publications</li>
            <li>Security & Accessibility</li>
          </ul>
        </div>

        <div class="profile-form">
  <form method="POST" action="{{ route('profile.update') }}">
    @csrf

    <input type="text" name="nama" placeholder="Nama Lengkap" 
           value="{{ old('nama', $dosen->nama ?? $user->name) }}" required>

    <input type="text" placeholder="NIDN" 
           value="{{ $user->nidn }}" disabled
           style="background-color:#f9f9f9; cursor:not-allowed;">

    <!-- Fakultas Dropdown -->
    <select name="fakultas_id" required>
      <option value="">-- Pilih Fakultas --</option>
      @foreach($fakultasList as $fakultas)
        <option value="{{ $fakultas->id }}"
          {{ old('fakultas_id', $dosen->fakultas_id ?? '') == $fakultas->id ? 'selected' : '' }}>
          {{ $fakultas->nama }}
        </option>
      @endforeach
    </select>

    <!-- Jabatan Dropdown -->
    <select name="jabatan_id" required>
      <option value="">-- Pilih Jabatan --</option>
      @foreach($jabatanList as $jabatan)
        <option value="{{ $jabatan->id }}"
          {{ old('jabatan_id', $dosen->jabatan_id ?? '') == $jabatan->id ? 'selected' : '' }}>
          {{ $jabatan->nama }}
        </option>
      @endforeach
    </select>

    <input type="email" name="email" placeholder="Email" 
           value="{{ old('email', $user->email) }}">

    <input type="text" name="telepon" placeholder="Nomor Telpon" 
           value="{{ old('telepon', $dosen->telepon ?? '') }}">

    <button type="submit" class="btn-submit">SUBMIT</button>
  </form>
</div>

      </div>
    </main>
  </div>
</body>
</html>
