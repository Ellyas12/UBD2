<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Hapus Program | UBD</title>
  <link rel="stylesheet" href="{{ asset('css/Lprogramcreate.css') }}">
  <link rel="stylesheet" href="{{ asset('css/Lprogramview.css') }}">
  <meta name="viewport" content="width=device-width,initial-scale=1">
</head>
<body>
  <div class="dashboard-container">
    @include('lecturer.navbar')
    <main class="main-content">
      <div class="program-wrapper">
        <div class="profile-form">
          {{-- Flash Message --}}
          @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
          @endif
          @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
          @endif

          <h2 class="section-title">Detail Program</h2>

          <div class="details-grid">

            {{-- Left column: Program info --}}
            <div class="detail-card">
              <h3>Program Info</h3>
              <p><strong>Judul:</strong> {{ $program->judul ?? '-' }}</p>
              <p><strong>Jenis:</strong> {{ $program->jenis ?? '-' }}</p>
              <p><strong>Bidang:</strong> {{ $program->bidang ?? '-' }}</p>
              <p><strong>Topik:</strong> {{ $program->topik ?? '-' }}</p>
              <p><strong>Pertemuan:</strong> {{ $program->pertemuan->nama ?? '-' }}</p>
              <p><strong>Tanggal:</strong> {{ $program->tanggal ?? '-' }}</p>
            </div>

            {{-- Right column: Team & financial --}}
            <div class="detail-card">
              <h3>Team</h3>
              <p>
                <strong>Ketua:</strong>
                {{ $program->ketua->dosen->nama ?? '-' }}
                - {{ $program->ketua->dosen->user->nidn ?? '-' }}
              </p>

              <p><strong>Anggota:</strong></p>
              @if ($program->anggota->isNotEmpty())
                <ul>
                  @foreach ($program->anggota as $anggota)
                    <li>
                      {{ $anggota->dosen->nama ?? '-' }}
                      - {{ $anggota->dosen->user->nidn ?? '-' }}
                    </li>
                  @endforeach
                </ul>
              @else
                <p>-</p>
              @endif

              <h3 style="margin-top:12px;">Financial</h3>
              <p><strong>Biaya:</strong> Rp {{ number_format($program->biaya, 0, ',', '.') }}</p>
              <p><strong>Sumber Biaya:</strong> {{ $program->sumber_biaya ?? '-' }}</p>
              <p><strong>Website:</strong>
                @if($program->linkweb)
                  <a href="{{ $program->linkweb }}" target="_blank" rel="noopener noreferrer">{{ $program->linkweb }}</a>
                @else
                  -
                @endif
              </p>
            </div>

            {{-- Full-width description --}}
            <div class="description-card" style="grid-column: 1 / -1;">
              <h3>Deskripsi</h3>
              <p>{{ $program->deskripsi ?? '-' }}</p>
            </div>
            @if($files->count())
              <div class="files-card" style="grid-column: 1 / -1;">
                <h3>Berkas Terunggah</h3>
                <ul>
                  @foreach ($files as $file)
                    <li>
                      <a href="{{ asset('storage/' . $file->file) }}" target="_blank" rel="noopener noreferrer">
                        📄 {{ $file->nama }}
                      </a>
                    </li>
                  @endforeach
                </ul>
              </div>
            @endif
          </div>
        <div class="warning-box">
          ⚠️ <strong>Peringatan:</strong> 
          Program ini akan dipindahkan ke folder <strong>backup</strong> dan akan dihapus permanen setelah 7 hari.
          <br>
          Pastikan Anda sudah melakukan pencadangan data yang diperlukan.
        </div>

        <form action="{{ route('program.destroy', $program->program_id) }}" method="POST" class="delete-form">
          @csrf
          @method('DELETE')

          <div class="form-navigation">
            <a href="{{ route('program') }}" class="btn-red-outline">Batal</a>
            <button type="submit" class="btn-red">🗑️ Hapus Program</button>
          </div>
        </form>
        </div>
      </div>
    </main>
  </div>
</body>
</html>