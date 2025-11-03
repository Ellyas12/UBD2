<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Review Program - Dekan</title>
    <link rel="stylesheet" href="{{ asset('css/Lprogram.css') }}">
    <script src="{{ asset('js/Block.js') }}"></script>
</head>
<body>
<div class="dashboard-container">
    @include('lecturer.navbar')

    <div class="program-section">
        <h2>📋 Review Program</h2>

        <div class="program-info">
            <p><strong>Judul:</strong> {{ $program->judul }}</p>
            <p><strong>Ketua:</strong> {{ $program->dosen->nama ?? '-' }}</p>
            <p><strong>Tanggal:</strong> {{ $program->tanggal }}</p>
        </div>

        <form action="{{ $isEditable ? route('dekan.submitReview', $program->program_id) : '#' }}" 
      method="POST">
    @csrf

    <div class="form-group">
        <label for="status">Pilih Status:</label>
        <select name="status" id="status" class="form-control" {{ $isEditable ? '' : 'disabled' }} required>
            <option value="Accepted" {{ $program->status === 'Accepted' ? 'selected' : '' }}>✅ Accepted</option>
            <option value="Denied" {{ $program->status === 'Denied' ? 'selected' : '' }}>❌ Denied</option>
            <option value="Revisi" {{ $program->status === 'Revisi' ? 'selected' : '' }}>✏️ Revisi</option>
        </select>
    </div>

    <div class="form-group mt-3">
        <label for="content">Komentar:</label>
        <textarea name="content" id="content" class="form-control" rows="4" placeholder="Tuliskan komentar Anda di sini..." {{ $isEditable ? '' : 'readonly' }} required>
                {{ old('content', optional($program->comments->first())->content) }}
        </textarea>
    </div>

    @if($isEditable)
        <button type="submit" class="btn btn-success mt-3">Kirim Review</button>
    @else
        <p class="text-muted mt-3"><em>Program ini belum di-stamp ulang dan hanya dapat dilihat.</em></p>
    @endif
</form>
    </div>
</div>
</body>
</html>
