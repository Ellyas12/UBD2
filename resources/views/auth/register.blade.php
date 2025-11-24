<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login | UBD</title>
  <link rel="stylesheet" href="{{ asset('css/login.css') }}">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <script src="{{ asset('js/BlockAuth.js') }}"></script>
</head>
<body>
  <div class="login-container">
    <div class="logo-section">
      <img src="{{ asset('images/ubd-logo.png') }}" alt="UBD Logo">
    </div>

    <div class="form-section">
      <h2 class="form-title">SIGN UP</h2>

      <form action="/register" method="POST" novalidate>
        @csrf

        @error('username')
            <p class="error-text">{{ $message }}</p>
        @enderror
        <div class="input-group">
            <span class="material-icons input-icon">person</span>
            <input type="text" name="username" placeholder="Username" value="{{ old('username') }}" required>
        </div>

        @error('password')
            <p class="error-text">{{ $message }}</p>
        @enderror
        <div class="input-group">
          <span class="material-icons input-icon">lock</span>
          <input type="password" name="password" placeholder="Password" required>
        </div>


        @error('password')
            <p class="error-text">{{ $message }}</p>
        @enderror
        <div class="input-group">
          <span class="material-icons input-icon">lock</span>
          <input type="password" name="password_confirmation" placeholder="Confirm Password" required>
        </div>


        @error('email')
            <p class="error-text">{{ $message }}</p>
        @enderror
        <div class="input-group">
          <span class="material-icons input-icon">email</span>
          <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
        </div>


        @error('nidn')
            <p class="error-text">{{ $message }}</p>
        @enderror
        <div class="input-group">
            <span class="material-icons input-icon">assignment_ind</span>
            <input type="text" name="nidn" placeholder="NIDN" value="{{ old('nidn') }}" maxlength="5" required>
        </div>

        <button type="submit" class="btn-login">SIGN UP</button>
      </form>

      <p class="signup-link">have an account? <a href="/login">SIGN IN HERE</a></p>
    </div>
  </div>
</body>
</html>