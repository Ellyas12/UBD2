<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>{{ $dosen->nama }} | Profile</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <link rel="stylesheet" href="{{ asset('css/Lprofile.css') }}">
</head>
<body>
  <div class="dashboard-container">
    @include('lecturer.navbar')

    <main class="main-content">
      <div class="profile-container">

        <!-- Sidebar -->
        <div class="profile-sidebar">
          <div class="profile-avatar">
            <img 
              src="{{ $dosen->profile_picture 
                    ? asset('storage/' . $dosen->profile_picture) 
                    : asset('images/default.jpg') }}" 
              alt="Profile Photo"
              class="avatar-img">
          </div>

          <nav class="profile-menu" aria-label="Profile sections">
            <ul role="tablist">
              <li role="presentation">
                <button role="tab" aria-selected="true" aria-controls="personal-info" id="tab-personal">
                  Personal Information
                </button>
              </li>
              <li role="presentation">
                <button role="tab" aria-selected="false" aria-controls="academic-info" id="tab-academic">
                  Academic & Professional Info
                </button>
              </li>
              <li role="presentation">
                <button role="tab" aria-selected="false" aria-controls="research-info" id="tab-research">
                  Research & Publications
                </button>
              </li>
            </ul>
          </nav>
        </div>

        <!-- Profile Content -->
        <div class="profile-form">

          <!-- Personal Info -->
          <div id="personal-info" role="tabpanel" aria-labelledby="tab-personal">
            <input type="text" placeholder="Nama Lengkap" value="{{ $dosen->nama }}" readonly class="readonly-field">
            <input type="text" placeholder="NIDN" value="{{ $dosen->user->nidn ?? '-' }}" readonly class="readonly-field">
            <input type="text" placeholder="Email" value="{{ $dosen->user->email ?? '-' }}" readonly class="readonly-field">
            <input type="text" placeholder="Fakultas" value="{{ $dosen->fakultas->nama ?? '-' }}" readonly class="readonly-field">
            <input type="text" placeholder="Jabatan" value="{{ $dosen->jabatan->nama ?? '-' }}" readonly class="readonly-field">
            <input type="text" placeholder="Nomor Telepon" value="{{ $dosen->telp ?? '-' }}" readonly class="readonly-field">
            <input type="text" placeholder="Pendidikan Akhir" value="{{ $dosen->pendidikan ?? '-' }}" readonly class="readonly-field">
            <input type="text" placeholder="Bidang Ahli" value="{{ $dosen->bidang ?? '-' }}" readonly class="readonly-field">
          </div>

          <!-- Academic Info -->
          <div id="academic-info" role="tabpanel" aria-labelledby="tab-academic" hidden>
            <h3>Mata Kuliah</h3>
            <div class="table-container">
              <table class="table">
                <thead>
                  <tr>
                    <th>Kode</th>
                    <th>Nama Mata Kuliah</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse(($dosen->mataKuliah ?? []) as $mk)
                    <tr>
                      <td>{{ $mk->kode_matkul }}</td>
                      <td>{{ $mk->nama }}</td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="2" style="text-align:center;">Tidak ada data mata kuliah</td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>

          <div id="research-info" role="tabpanel" aria-labelledby="tab-research" hidden>
            <h2>Research & Publications</h2>
            <table>
              <thead>
                <tr>
                  <th>Judul</th>
                  <th>Tanggal</th>
                  <th>Jenis</th>
                  <th>Status</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                @forelse(($dosen->program ?? []) as $program)
                  <tr>
                    <td>{{ $program->judul }}</td>
                    <td>{{ $program->tanggal }}</td>
                    <td>{{ $program->jenis }}</td>
                    <td>{{ $program->status }}</td>
                    <td>
                      <a href="{{ route('program.view', $program->program_id) }}" class="btn btn-info" target="_blank">👁️ View</a>
                    </td>
                  </tr>
                @empty
                  <tr><td colspan="5" style="text-align:center;">Belum ada publikasi</td></tr>
                @endforelse
              </tbody>
            </table>
          </div>

        </div> <!-- end profile-form -->
      </div>
    </main>

  </div>

  <script src="{{ asset('js/Lprofile.js') }}"></script>
</body>
</html>
