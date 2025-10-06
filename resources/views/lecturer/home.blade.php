<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Home | UBD</title>
  <link rel="stylesheet" href="{{ asset('css/login.css') }}">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

  <script>
  window.addEventListener('pageshow', function(event) {
    if (event.persisted || (performance.getEntriesByType && performance.getEntriesByType('navigation')[0].type === 'back_forward')) {
      window.location.reload();
    }
  });
  </script>
</head>
<body>
  <div class="dashboard-container">
  @include('lecturer.navbar')
  
  <main class="main-content">
    <div class="announcement-box">
      Announcement :
    </div>

    <div class="section-title">Penelitian anda :</div>
    <div class="section-content">
    </div>

    <div class="section-title">Penelitian lain :</div>
    <div class="section-content">
    </div>
  </main>
</div>
</form>
</body>
</html>