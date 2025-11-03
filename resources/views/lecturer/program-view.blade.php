<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Lihat Program | UBD</title>
  <link rel="stylesheet" href="{{ asset('css/Lprogram.css') }}">
</head>
<body>
  <div class="dashboard-container">
    @include('lecturer.navbar')

    <div class="profile-form">
      {{-- Flash Message --}}
      @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif
      @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
      @endif

      <h2>Detail Program</h2>

      <div class="program-details">
        <p><strong>Judul:</strong> {{ $program->judul }}</p>
        <p><strong>Jenis:</strong> {{ $program->jenis }}</p>
        <p><strong>Bidang:</strong> {{ $program->bidang }}</p>
        <p><strong>Topik:</strong> {{ $program->topik }}</p>
        <p><strong>Ketua:</strong> {{ $program->ketua->dosen->nama ?? '-' }}</p>
        <p><strong>Anggota:</strong> 
            @if ($program->anggota->isNotEmpty())
              @foreach ($program->anggota as $anggota)
                {{ $anggota->dosen->nama ?? '-' }}<br>
              @endforeach
            @else
              -
            @endif</p>
        <p><strong>Tanggal:</strong> {{ $program->tanggal }}</p>
        <p><strong>Biaya:</strong> {{ $program->biaya }}</p>
        <p><strong>Sumber Biaya:</strong> {{ $program->sumber_biaya }}</p>
        <p><strong>Pertemuan:</strong> {{ $program->pertemuan->nama ?? '-' }}</p>
        <p><strong>linkweb:</strong> {{ $program->linkweb }}</p>
        <p><strong>Deskripsi:</strong></p>
        <p>{{ $program->deskripsi ?? '-' }}</p>

        @if($files->count())
          <h3>Berkas Terunggah</h3>
          <ul>
            @foreach ($files as $file)
              <li>
                <a href="{{ asset('storage/' . $file->file) }}" target="_blank">
                  📄 {{ $file->nama }}
                </a>
              </li>
            @endforeach
          </ul>
        @endif
      </div>

      <div class="form-navigation">
        <a href="{{ route('program.edit', $program->program_id) }}" class="btn-submit">✏️ Edit Program</a>
      </div>
    </div>
  </div>
</body>
</html>
