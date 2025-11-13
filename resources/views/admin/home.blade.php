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
  @include('admin.navbar')

  <main class="main-content">
      @php
          $ann = \App\Models\Announcement::find(1);
      @endphp

      <div class="announcement-box">
        <strong>Announcement:</strong><br>
        @if($ann && ($ann->title || $ann->body))
            <h3>{{ $ann->title }}</h3>
            <p>{{ $ann->body }}</p>
        @else
            <p>No announcement yet.</p>
        @endif
      </div>

    <div class="section-title">Penelitian lain :</div>
    <div class="section-content">
    </div>
  </main>
</div>
</form>
</body>
</html>