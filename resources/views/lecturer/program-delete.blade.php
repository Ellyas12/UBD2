<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Hapus Program | UBD</title>
  <link rel="stylesheet" href="{{ asset('css/Lprogram.css') }}">
</head>
<body>
  <div class="dashboard-container">
    @include('lecturer.navbar')

    <div class="profile-form">
      {{-- Flash Messages --}}
      @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif
      @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
      @endif

      <h2 style="color: red;">Konfirmasi Penghapusan Program</h2>

      <div class="program-details">
        <p><strong>Judul:</strong> {{ $program->judul }}</p>
        <p><strong>Jenis:</strong> {{ $program->jenis }}</p>
        <p><strong>Bidang:</strong> {{ $program->bidang }}</p>
        <p><strong>Topik:</strong> {{ $program->topik }}</p>
        <p><strong>Ketua:</strong> {{ $program->ketua }}</p>
        <p><strong>Anggota:</strong> {{ $program->anggota ?? '-' }}</p>
        <p><strong>Tanggal:</strong> {{ $program->tanggal }}</p>
        <p><strong>Biaya:</strong> {{ $program->biaya }}</p>
        <p><strong>Sumber Biaya:</strong> {{ $program->sumber_biaya }}</p>
        <p><strong>Pertemuan:</strong> {{ $program->pertemuan->nama ?? '-' }}</p>
        <p><strong>linkweb:</strong> {{ $program->linkweb }}</p>
        <hr>
        <p><strong>Deskripsi:</strong></p>
        <p>{{ $program->deskripsi ?? '-' }}</p>

        @if($program->files->count())
          <h3>Berkas Terunggah</h3>
          <ul>
            @foreach ($program->files as $file)
              <li>
                📄 {{ $file->nama }}
              </li>
            @endforeach
          </ul>
        @endif
      </div>

      <div class="warning-box" style="background-color: #fff3cd; border: 1px solid #ffeeba; padding: 15px; margin-top: 20px; border-radius: 6px;">
        ⚠️ <strong>Peringatan:</strong> 
        Program ini akan dipindahkan ke folder <strong>backup</strong> dan akan dihapus permanen setelah 7 hari.
        <br>
        Pastikan Anda sudah melakukan pencadangan data yang diperlukan.
      </div>

      <form action="{{ route('program.destroy', $program->program_id) }}" method="POST" style="margin-top: 30px;">
        @csrf
        @method('DELETE')

        <div class="form-navigation">
          <a href="{{ route('profile') }}" class="btn-cancel">Batal</a>
          <button type="submit" class="btn-danger">🗑️ Hapus Program</button>
        </div>
      </form>
    </div>
  </div>
</body>
</html>