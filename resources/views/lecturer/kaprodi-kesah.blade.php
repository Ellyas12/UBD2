<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Program Stamping | Kaprodi</title>
  <link rel="stylesheet" href="{{ asset('css/Lprogram.css') }}">
</head>
<body>
  <div class="dashboard-container">
    @include('lecturer.navbar')

    <div class="program-detail">
      <h2>Detail Program</h2>

      <div class="card">
        <p><strong>Judul:</strong> {{ $program->judul }}</p>
        <p><strong>Ketua:</strong> {{ $program->dosen->nama ?? '-' }}</p>
        <p><strong>Jenis:</strong> {{ $program->jenis }}</p>
        <p><strong>Tanggal:</strong> {{ $program->tanggal }}</p>
        <p><strong>Deskripsi:</strong> {{ $program->deskripsi }}</p>
        <p><strong>Status:</strong> {{ $program->stamp }}</p>
      </div>

      @if($program->stamp != 'Done')
      <form action="{{ route('kaprodi.stamp.confirm', $program->program_id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin men-stamp program ini?')">
        @csrf
        <button type="submit" class="btn btn-success">✅ Stamp Program</button>
      </form>
      @else
        <p><strong>Program sudah di-stamp ✅</strong></p>
      @endif

      <a href="{{ route('kaprodi') }}" class="btn btn-secondary">⬅️ Kembali</a>
    </div>
  </div>
</body>
</html>