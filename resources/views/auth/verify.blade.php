<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Verify Code | UBD</title>

  <!-- Prevent caching -->
  <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
  <meta http-equiv="Pragma" content="no-cache">
  <meta http-equiv="Expires" content="0">

  <link rel="stylesheet" href="{{ asset('css/login.css') }}">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <script src="{{ asset('js/BlockAuth.js') }}"></script>
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
    <h2 class="form-title">VERIFY CODE</h2>

    @if(session('error'))
      <p class="error-text">{{ session('error') }}</p>
    @endif

    <p class="error-text">Code expires in 60 seconds and only one-time use.</p>
    <p>If you need a new code, please go back to the 
      <a href="{{ route('forgot.form') }}">Forgot Password page</a> and submit your email again.
    </p>

    <form action="{{ route('verify.code') }}" method="POST" autocomplete="off" novalidate>
      @csrf
      <div class="input-group">
        <span class="material-icons input-icon">vpn_key</span>
        <input type="text" name="code" placeholder="Enter 6-digit code" maxlength="6" required autocomplete="off">
      </div>
      <button type="submit" class="btn-login">VERIFY</button>
    </form>
  </div>
</div>
</body>
</html>