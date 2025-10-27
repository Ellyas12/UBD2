<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Review Program - Dekan</title>
    <link rel="stylesheet" href="{{ asset('css/Lprogram.css') }}">
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

        <form action="{{ route('dekan.submitReview', $program->program_id) }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="status">Pilih Status:</label>
            <select name="status" id="status" class="form-control" required>
                <option value="Accepted">✅ Accepted</option>
                <option value="Denied">❌ Denied</option>
                <option value="Revisi">✏️ Revisi</option> {{-- ✅ updated --}}
            </select>
            </div>

            <div class="form-group mt-3">
                <label for="content">Komentar:</label>
                <textarea name="content" id="content" class="form-control" rows="4" placeholder="Tuliskan komentar Anda di sini..." required></textarea>
            </div>

            <button type="submit" class="btn btn-success mt-3">Kirim Review</button>
        </form>
    </div>
</div>
</body>
</html>
