<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Announcement</title>
  <link rel="stylesheet" href="{{ asset('css/announcement.css') }}">
</head>
<body>
  <div class="dashboard-container">
    @include('admin.navbar')
    <main class="main-content">
      <h2 class="announcement-title">Announcement Settings</h2>

      <div class="announcement-form-box fade-in">
          <form action="{{ route('admin.announcement.update') }}" method="POST">
              @csrf
              
              <label>Title</label>
              <input type="text" name="title" value="{{ old('title', $announcement->title) }}" required>

              <label>Paragraph</label>
              <textarea name="body" rows="5" required>{{ old('body', $announcement->body) }}</textarea>

              <button type="submit">Save Announcement</button>
          </form>
      </div>
      <div class="announcement-form-box fade-in">
      <h1 class="announcement-result-title">THE RESULT</h1>
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
      </div>
    </main>
  </div>
</body>
</html>
