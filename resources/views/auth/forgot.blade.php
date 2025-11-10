<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Forgot Password | UBD</title>

  <!-- Prevent caching -->
  <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
  <meta http-equiv="Pragma" content="no-cache">
  <meta http-equiv="Expires" content="0">

  <link rel="stylesheet" href="{{ asset('css/login.css') }}">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <script src="{{ asset('js/BlockAuth.js') }}"></script>
  <script>
    // Clear inputs on normal load
    window.onload = function () {
      document.querySelectorAll('input[type="text"], input[type="password"], input[type="email"]').forEach(el => {
        el.value = '';
      });
    };

    // Clear inputs if page restored from back/forward cache
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
      <h2 class="form-title">FORGOT PASSWORD</h2>

      @if(session('error'))
        <p class="error-text">{{ session('error') }}</p>
      @endif
      @if(session('success'))
        <p class="success-text">{{ session('success') }}</p>
      @endif

      <form action="/forgot" method="POST" autocomplete="off" novalidate>
        @csrf
        <div class="input-group">
          <span class="material-icons input-icon">email</span>
          <input type="email" name="email" placeholder="Enter your email" required autocomplete="off">
        </div>
        <button type="submit" class="btn-login">SEND CODE</button>
      </form>

      <p class="signup-link"><a href="/login">Back to Login</a></p>
    </div>
  </div>
</body>
</html>