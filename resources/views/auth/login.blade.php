<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
  <meta http-equiv="Pragma" content="no-cache">
  <meta http-equiv="Expires" content="0">

  <title>Login | UBD</title>
  <link rel="stylesheet" href="{{ asset('css/login.css') }}">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

  <script>
    // Clear inputs when page loads fresh
    window.onload = function () {
      document.querySelectorAll('input[type="text"], input[type="password"], input[type="email"]').forEach(el => {
        el.value = '';
      });
    };

    // Clear inputs when page is restored from browser back/forward cache
    window.addEventListener("pageshow", function (event) {
      if (event.persisted) {
        document.querySelectorAll('input[type="text"], input[type="password"], input[type="email"]').forEach(el => {
          el.value = '';
        });
      }
    });
  </script>
</head>
<body>
  <div class="login-container">
    <div class="logo-section">
      <img src="{{ asset('images/ubd-logo.png') }}" alt="UBD Logo">
    </div>

    <div class="form-section">
      <h2 class="form-title">SIGN IN</h2>

      @if(session('success'))
        <p class="success-text">{{ session('success') }}</p>
      @endif

      <form action="/login" method="POST" autocomplete="off" novalidate>
        @csrf

        @error('username')
          <p class="error-text">{{ $message }}</p>
        @enderror
        <div class="input-group">
          <span class="material-icons input-icon">person</span>
          <input type="text" name="username" placeholder="Username"
                 value="{{ old('username') }}"
                 required autocomplete="off">
        </div>

        @error('password')
          <p class="error-text">{{ $message }}</p>
        @enderror
        <div class="input-group">
          <span class="material-icons input-icon">lock</span>
          <input type="password" name="password" placeholder="Password"
                 required autocomplete="new-password">
        </div>

        <a href="/forgot" class="forgot-link">Forgot Password?</a>

        <button type="submit" class="btn-login">LOGIN</button>
      </form>

      <p class="signup-link">Don't have an account? <a href="/register">SIGN UP HERE</a></p>
    </div>
  </div>
</body>
</html>