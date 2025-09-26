<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Reset Password | UBD</title>

  <!-- Prevent caching -->
  <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
  <meta http-equiv="Pragma" content="no-cache">
  <meta http-equiv="Expires" content="0">

  <link rel="stylesheet" href="{{ asset('css/login.css') }}">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

  <script>
    window.onload = function () {
      document.querySelectorAll('input[type="text"], input[type="password"], input[type="email"]').forEach(el => {
        el.value = '';
      });
    };

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
      <h2 class="form-title">RESET PASSWORD</h2>

      @if(session('error'))
        <p class="error-text">{{ session('error') }}</p>
      @endif

      <form action="/reset" method="POST" autocomplete="off" novalidate>
        @csrf
        <input type="hidden" name="email" value="{{ $email }}">
        
        <div class="input-group">
          <span class="material-icons input-icon">lock</span>
          <input type="password" name="password" placeholder="New Password" required autocomplete="off">
        </div>
        
        <div class="input-group">
          <span class="material-icons input-icon">lock</span>
          <input type="password" name="password_confirmation" placeholder="Confirm Password" required autocomplete="off">
        </div>
        
        <button type="submit" class="btn-login">RESET PASSWORD</button>
      </form>
    </div>
  </div>
</body>
</html>