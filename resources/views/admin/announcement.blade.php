<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Announcement</title>
  <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<body>
  @include('admin.navbar')

  <div class="dashboard-container">
    <main class="main-content">

      <h2>Announcement Settings</h2>

      @if(session('success'))
        <p style="color: green">{{ session('success') }}</p>
      @endif

      <form action="{{ route('admin.announcement.update') }}" method="POST">
        @csrf
        
        <label>Title</label>
        <input type="text" name="title" value="{{ old('title', $announcement->title) }}" required>

        <label>Paragraph</label>
        <textarea name="body" rows="5" required>{{ old('body', $announcement->body) }}</textarea>

        <button type="submit">Save Announcement</button>
      </form>

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

    </main>
  </div>

</body>
</html>
