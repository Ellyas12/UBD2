<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Home | Universitas Buddhi Dharma</title>

  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/Lhome.css') }}">
</head>
<body>
  <div class="dashboard-container">
    @include('lecturer.navbar')

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

      <div class="home-container">

      {{-- ========== 🧾 MY PROGRAM SECTION ========== --}}
      <div class="section-box">
        <h2 class="section-title">My Program</h2>

        <div class="myprogram-actions">
          <a href="{{ route('program.create') }}" class="btn btn-danger">+ Tambah Program</a>
          <a href="{{ route('program') }}" class="btn btn-outline-danger">Lihat Program</a>
        </div>

        <div class="program-grid mt-3">
          @forelse ($myPrograms as $program)
            <div class="program-card">
              <div class="program-info">
                <h3>{{ $program->judul }}</h3>
                <p><strong>Jenis:</strong> {{ $program->jenis }}</p>
                <p><strong>Bidang:</strong> {{ $program->bidang }}</p>
                <p><strong>Topik:</strong> {{ $program->topik }}</p>
                <p><strong>Tanggal:</strong> {{ $program->tanggal }}</p>
              </div>

              <div class="program-footer">
                <a href="{{ route('program.view', $program->program_id) }}" 
                  class="btn btn-outline-danger w-100"
                  target="_blank">
                  View Details
                </a>
              </div>
            </div>
          @empty
            <p class="text-center text-muted">Belum ada program yang Anda buat.</p>
          @endforelse
        </div>
      </div>

      </section>
        <div class="section-box">
              {{-- 🔍 Search Section --}}
              <div class="search-section">
                <h2>Pencarian Program</h2>
                <div class="search-bar">
                  <input type="text" id="search-input" placeholder="Cari berdasarkan judul, bidang, atau ketua...">
                  <button id="search-btn">Search</button>
                </div>
              </div>

              <div id="search-results" class="search-results hidden">
                <h3>Hasil Pencarian</h3>
                <div id="search-results-list" class="program-grid"></div>
                <p id="no-results" class="no-results hidden">Tidak ada hasil yang cocok.</p>
              </div>
          </div>

  {{-- ========== 🌍 SEMUA PROGRAM SECTION ========== --}}
  <div class="filter-controls">
    <div class="filter-box">
      <select id="filter-fakultas">
        <option value="">Semua Fakultas</option>
        @foreach ($fakultasList as $f)
          <option value="{{ $f->fakultas_id }}">{{ $f->nama }}</option>
        @endforeach
      </select>

      <select id="filter-jenis">
        <option value="">Semua Jenis</option>
        <option value="PKM">PKM</option>
        <option value="Penelitian">Penelitian</option>
      </select>

      <select id="sort-option">
        <option value="newest">Terbaru</option>
        <option value="oldest">Terlama</option>
      </select>

      <button id="apply-sort" class="btn btn-outline-danger">Terapkan</button>
    </div>
  </div>
    <div id="program-list" class="program-grid fade-in mt-3">
      @foreach ($programList as $program)
        <div class="program-card" 
             data-title="{{ $program->judul }}" 
             data-date="{{ $program->tanggal }}"
            data-fakultas-id="{{ $program->dosen->fakultas->fakultas_id ?? '' }}"
            data-fakultas="{{ $program->dosen->fakultas->nama ?? '' }}">
            <div class="profile-avatar">
              <img 
                src="{{ $program->dosen && $program->dosen->profile_picture 
                      ? asset('storage/' . $program->dosen->profile_picture) 
                      : asset('images/default.jpg') }}" 
                alt="Profile Photo"
                class="avatar-img profile-click"
                data-name="{{ $program->dosen->nama ?? 'Unknown Lecturer' }}"
                data-fakultas="{{ $program->dosen->fakultas->nama ?? 'Tidak diketahui' }}"
                data-email="{{ $program->dosen->user->email ?? 'Tidak tersedia' }}"
                data-bio="{{ $program->dosen->bio ?? 'Tidak ada deskripsi tersedia.' }}"
                data-picture="{{ $program->dosen && $program->dosen->profile_picture 
                      ? asset('storage/' . $program->dosen->profile_picture) 
                      : asset('images/default.jpg') }}"
                data-profile-url="{{ route('dosen.profile', $program->dosen->dosen_id ?? 0) }}"
              >
            </div>

          <div class="program-info">
            <h3>{{ $program->judul }}</h3>
            <p><strong>Jenis:</strong> {{ $program->jenis }}</p>
            <p><strong>Bidang:</strong> {{ $program->bidang }}</p>
            <p><strong>Topik:</strong> {{ $program->topik }}</p>
            <p><strong>Tanggal:</strong> {{ $program->tanggal }}</p>

            <p class="submitted-by">
              <strong>Submitted by:</strong> {{ $program->dosen->nama ?? 'Unknown Lecturer' }}
            </p>

            @if($program->dosen && $program->dosen->fakultas)
              <span class="faculty-badge">
                {{ $program->dosen->fakultas->nama }}
              </span>
            @endif
          </div>

          <div class="program-footer">
            <a href="{{ route('program.view', $program->program_id) }}" 
               class="btn btn-outline-danger w-100"
               target="_blank">
              View Details
            </a>
          </div>
        </div>
      @endforeach
    </div>
  </div>
    <div id="pagination-controls" class="pagination-controls hidden">
    <button id="view-more" class="btn btn-outline-primary">View More</button>
    <div id="page-buttons" class="page-buttons"></div>
  </div>
      <footer>
        <h4>Universitas Buddhi Dharma</h4>
        <p>Jl. Test, Tangerang</p>
        <p>Telp: (0711) 515679 | Fax: (0711) 515582</p>
        <p>Email: <a href="mailto:info@buddhidarma.ac.id">info@buddhidarma.ac.id</a></p>
        <p>Website: <a href="https://portal.buddhidarma.ac.id" target="_blank">portal.buddhidarma.ac.id</a></p>
      </footer>

    </main>
  </div>
<div id="lecturer-modal" class="profile-modal hidden">
  <div class="profile-modal-content">
    <span id="modal-close" class="modal-close">&times;</span>

    <div class="modal-header">
      <img id="modal-photo" src="" alt="Lecturer Photo" class="modal-avatar">
      <div>
        <h3 id="modal-name"></h3>
        <p class="modal-fakultas"><strong>Fakultas:</strong> <span id="modal-fakultas"></span></p>
        <p><strong>Email:</strong> <span id="modal-email"></span></p>
      </div>
    </div>

    <div class="modal-bio">
      <h4>Deskripsi Singkat</h4>
      <p id="modal-bio"></p>
    </div>

    <div class="modal-footer">
      <a id="modal-profile-link" href="#" target="_blank" class="btn btn-outline-danger w-100">
        View Full Profile
      </a>
    </div>
  </div>
</div>

  <script src="{{ asset('js/Lhome.js') }}"></script>
</body>
</html>