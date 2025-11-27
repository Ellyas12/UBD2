<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>{{ $dosen->nama }} | Profile</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
              <li><button role="tab" aria-selected="true" aria-controls="personal-info" id="tab-personal">Personal Information</button></li>
              <li><button role="tab" aria-selected="false" aria-controls="academic-info" id="tab-academic">Academic & Professional Info</button></li>
              <li><button role="tab" aria-selected="false" aria-controls="research-info" id="tab-research">Research & Publications</button></li>
            </ul>
          </nav>
        </div>

        <!-- Main Profile View -->
        <div class="profile-form">

          <!-- PERSONAL INFO -->
          <div id="personal-info" role="tabpanel" aria-labelledby="tab-personal">
              <div class="mb-3">
                  <input type="text" value="{{ $dosen->nama }}" class="form-control" readonly>
              </div>
              <div class="mb-3">
                  <input type="text" value="{{ $dosen->user->nidn ?? '-' }}" class="form-control" readonly>
              </div>
              <div class="mb-3">
                  <input type="text" value="{{ $dosen->user->email ?? '-' }}" class="form-control" readonly>
              </div>
              <div class="mb-3">
                  <input type="text" value="{{ $dosen->fakultas->nama ?? '-' }}" class="form-control" readonly>
              </div>
              <div class="mb-3">
                  <input type="text" value="{{ $dosen->jabatan->nama ?? '-' }}" class="form-control" readonly>
              </div>
              <div class="mb-3">
                  <input type="text" value="{{ $dosen->telp ?? '-' }}" class="form-control" readonly>
              </div>
              <div class="mb-3">
                  <input type="text" value="{{ $dosen->pendidikan ?? '-' }}" class="form-control" readonly>
              </div>
              <div class="mb-3">
                  <input type="text" value="{{ $dosen->bidang ?? '-' }}" class="form-control" readonly>
              </div>
          </div>

          <!-- ACADEMIC INFO -->
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
                      <td colspan="2" class="text-center">Tidak ada data mata kuliah</td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
            <h3>Prestasi Akademik</h3>
                  <div class="table-container">
                    <table class="table">
                      <thead>
                        <tr>
                          <th>Nama Prestasi</th>
                          <th>Link</th>
                          <th>Aksi</th>
                        </tr>
                      </thead>
                      <tbody>
                        @forelse($myPrestasi as $p)
                          <tr>
                            <td>{{ $p->nama }}</td>
                            <td>
                              @if($p->Link)
                                <a href="{{ $p->Link }}" target="_blank">Lihat</a>
                              @else
                                -
                              @endif
                            </td>
                            <td>
                              <form method="POST" action="{{ route('prestasi.destroy', $p->prestasi_id) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                              </form>
                            </td>
                          </tr>
                        @empty
                          <tr><td colspan="3" class="text-center">Belum ada prestasi.</td></tr>
                        @endforelse
                      </tbody>
                    </table>
                  </div>
          </div>

          <!-- RESEARCH INFO -->
          <div id="research-info" role="tabpanel" aria-labelledby="tab-research" hidden>
            <h2>Research & Publications</h2>
            <div id="table-view">
              <table>
                <thead>
                  <tr>
                    <th>Judul</th>
                    <th>Tanggal</th>
                    <th>Jenis</th>
                    <th>Status</th>
                    <th>Lihat</th>
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
                        <a href="{{ route('program.view', $program->program_id) }}" target="_blank" class="btn btn-info btn-sm">👁️ View</a>
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="5" class="text-center">Belum ada publikasi</td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            <div class="mt-3" id="program-section">
              {{ $programList->links('pagination::bootstrap-5') }}
            </div>
            <button id="btn-analytics" class="btn btn-primary mt-3">📊 View Analytics</button>  
            </div>              
            <div id="analytics-view" style="display:none; margin-top:20px;">
                <h3>Program Analytics</h3>
                <canvas id="programChart" height="120"></canvas>
                <button id="btn-back" class="btn btn-secondary mb-3">⬅ Back</button>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
  <script src="{{ asset('js/Lprofile.js') }}"></script>
  <script src="{{ asset('js/Chart.js') }}"></script>
  <script id="program-data" type="application/json">
      {!! json_encode($programList->items()) !!}
  </script>
</body>
</html>
