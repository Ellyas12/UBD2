<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Home | Universitas Buddhi Dharma</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
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
      @if($ann && ($ann->title || $ann->body))
          <div class="announcement-box danger-announcement">
              <strong>Announcement:</strong><br>

              @if($ann->title)
                  <h3>{{ $ann->title }}</h3>
              @endif

              @if($ann->body)
                  <p>{{ $ann->body }}</p>
              @endif
          </div>
      @endif

      <div class="home-container">
        <div class="section-box">
          <h2 class="section-title">My Program</h2>
          <div class="myprogram-actions">
            <a href="{{ route('program.create') }}" class="btn btn-danger">+ Tambah Program</a>
            <a href="{{ route('program') }}" class="btn btn-outline-danger">Lihat Program</a>
          </div>
          @php
              $myrecentPrograms = $myPrograms->take(5);
          @endphp
          <div class="program-grid-wrapper">
            <div class="program-grid mt-3">
                @foreach ($myrecentPrograms as $program)
                    <div class="program-card">
                      <span class="program-status d-none">{{ $program->status }}</span>
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
                @endforeach
                @if ($myPrograms->count() > 5)
                    <div class="program-card program-card-full">
                        <a href="{{ route('program') }}" class="btn btn-outline-danger program-button-full">
                            Lihat program sebelumnya →
                        </a>
                    </div>
                @endif
                @if ($myPrograms->count() == 0)
                    <p class="text-center text-muted">Belum ada program yang Anda buat.</p>
                @endif
            </div>
          </div>
        </section>

        <div class="section-box">
            <div class="search-section">
                <h2 class="section-title">Pencarian Penelitian dan PKM</h2>
                <div class="search-bar improved-search-bar">
                    <input type="text" id="search-input" placeholder="Cari berdasarkan judul, bidang, atau ketua...">
                    <button id="search-btn" class="btn btn-outline-danger w-100">Search</button>
                </div>
            </div>
            <div id="search-results" class="search-results hidden">
                <h3 class="search-results-title">Hasil Pencarian</h3>
                <div id="search-results-list" class="program-grid"></div>
                <p id="no-results" class="no-results hidden">Tidak ada hasil yang cocok.</p>
            </div>
        </div>

        <div id="program-section">
          <form method="GET" action="{{ route('lecturer.home') }}#program-section">
              <div class="filter-controls">
                  <div class="filter-box">

                      <select name="fakultas" id="filter-fakultas">
                          <option value="">Semua Fakultas</option>
                          @foreach ($fakultasList as $f)
                              <option value="{{ $f->fakultas_id }}"
                                  {{ request('fakultas') == $f->fakultas_id ? 'selected' : '' }}>
                                  {{ $f->nama }}
                              </option>
                          @endforeach
                      </select>

                      <select name="jenis" id="filter-jenis">
                          <option value="">Semua Jenis</option>
                          <option value="PKM" {{ request('jenis') === 'PKM' ? 'selected' : '' }}>PKM</option>
                          <option value="Penelitian" {{ request('jenis') === 'Penelitian' ? 'selected' : '' }}>Penelitian</option>
                      </select>

                      <select name="sort" id="sort-option">
                          <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Terbaru</option>
                          <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Terlama</option>
                      </select>

                      <button type="submit" class="btn btn-outline-danger">
                          Terapkan
                      </button>
                  </div>
              </div>
          </form>
          <div id="program-list" class="program-grid-listing fade-in mt-3">
            @if ($programList->count() == 0)
                <p class="text-center text-danger fw-bold mt-3">
                    Tidak ada program yang sesuai dengan filter.
                </p>
            @endif
            @foreach ($programList as $program)
              <div class="program-card" 
                  data-title="{{ $program->judul }}" 
                  data-date="{{ $program->tanggal }}"
                  data-fakultas-id="{{ $program->dosen->fakultas->fakultas_id ?? '' }}"
                  data-fakultas="{{ $program->dosen->fakultas->nama ?? '' }}">
                  <span class="program-status d-none">{{ $program->status }}</span>
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
                      data-nidn="{{ $program->dosen->user->nidn ?? 'Tidak tersedia' }}"
                      data-posisi="{{ $program->dosen->user->posisi ?? 'Tidak tersedia' }}"

                      data-jabatan="{{ $program->dosen->jabatan->nama ?? 'Tidak tersedia' }}"
                      data-bidang="{{ $program->dosen->bidang ?? 'Tidak tersedia' }}"
                      data-telp="{{ $program->dosen->telp ?? 'Tidak tersedia' }}"

                      data-picture="{{ $program->dosen && $program->dosen->profile_picture 
                            ? asset('storage/' . $program->dosen->profile_picture) 
                            : asset('images/default.jpg') }}"

                      data-profile-url="{{ route('dosen.profile', $program->dosen->dosen_id ?? 0) }}"
                    >
                  </div>
                  <div class="program-info">
                    <p class="submitted-by">
                      <strong>Submitted by:</strong> {{ $program->dosen->nama ?? 'Unknown Lecturer' }}
                    </p>
                    <h3>{{ $program->judul }}</h3>
                    <p><strong>Jenis:</strong> {{ $program->jenis }}</p>
                    <p><strong>Bidang:</strong> {{ $program->bidang }}</p>
                    <p><strong>Topik:</strong> {{ $program->topik }}</p>
                    <p><strong>Tanggal:</strong> {{ $program->tanggal }}</p>
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
          <div class="mt-3" id="program-section">
              {{ $programList->links('pagination::bootstrap-5') }}
          </div>
        </div>
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
    <div id="lecturer-modal" class="profile-modal">
    <div class="profile-modal-content">
        <span id="modal-close" class="modal-close">&times;</span>
        <div class="modal-top">
            <img id="modal-photo" class="modal-avatar" src="" alt="Profile Photo">
            <div class="red-divider"></div>
        </div>
        <div class="modal-info">
            <h3 id="modal-name" class="modal-name"></h3>

            <p><strong>Fakultas:</strong> <span id="modal-fakultas"></span></p>
            <p><strong>NIDN:</strong> <span id="modal-nidn"></span></p>
            <p><strong>Email:</strong> <span id="modal-email"></span></p>
            <p><strong>Posisi:</strong> <span id="modal-posisi"></span></p>
            <p><strong>Jabatan:</strong> <span id="modal-jabatan"></span></p>
            <p><strong>Bidang:</strong> <span id="modal-bidang"></span></p>
            <p><strong>Nomor Telpon:</strong> <span id="modal-telp"></span></p>
        </div>
        <div class="modal-footer">
            <a id="modal-profile-link" href="#" target="_blank" class="btn btn-outline-danger w-100">
                View Full Profile
            </a>
        </div>
      </div>
    </div>
  </div>
  <script src="{{ asset('js/Lhome.js') }}"></script>
</body>
</html>