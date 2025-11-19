<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Profile | UBD</title>
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
        <div class="profile-sidebar">
          <div class="profile-avatar">
            <img 
              src="{{ $dosen && $dosen->profile_picture 
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
              <li role="presentation">
                <button role="tab" aria-selected="false" aria-controls="security-info" id="tab-security">
                  Security & Accessibility
                </button>
              </li>
            </ul>
          </nav>
        </div>

        <!-- Profile Content -->
        <div class="profile-form">

          {{-- Flash Message --}}
          @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
          @endif

          <!-- Personal Info -->
          <div id="personal-info" role="tabpanel" aria-labelledby="tab-personal">
            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
              @csrf
              <input type="text" name="nama" placeholder="Nama Lengkap" 
                     value="{{ old('nama', $dosen->nama ?? $user->nama) }}" required>

              <input type="text" placeholder="NIDN" value="{{ $user->nidn }}" readonly class="readonly-field">

              <input type="email" name="email" placeholder="Email" 
                     value="{{ $user->email }}" readonly class="readonly-field">

              <div class="form-group">
                <label for="fakultas_id">Fakultas</label>

                @php
                    $selectedFakultas = old('fakultas_id', $dosen->fakultas_id ?? '');
                @endphp

                <select id="fakultas_id"
                        name="fakultas_id"
                        {{ $selectedFakultas ? 'disabled' : '' }}
                        required>
                    <option value="">-- Pilih Fakultas --</option>
                    @foreach($fakultasList as $fakultas)
                        <option value="{{ $fakultas->fakultas_id }}"
                            {{ $selectedFakultas == $fakultas->fakultas_id ? 'selected' : '' }}>
                            {{ $fakultas->nama }}
                        </option>
                    @endforeach
                </select>

                {{-- Send hidden only if select is disabled AND not null --}}
                @if($selectedFakultas)
                    <input type="hidden" name="fakultas_id" value="{{ $selectedFakultas }}">
                @endif
              </div>

              <div class="form-group">
                <label for="jabatan_id">Jabatan</label>

                @php
                    $selectedJabatan = old('jabatan_id', $dosen->jabatan_id ?? '');
                @endphp

                <select id="jabatan_id"
                        name="jabatan_id"
                        {{ $selectedJabatan ? 'disabled' : '' }}
                        required>
                    <option value="">-- Pilih Jabatan --</option>
                    @foreach($jabatanList as $jabatan)
                        <option value="{{ $jabatan->jabatan_id }}"
                            {{ $selectedJabatan == $jabatan->jabatan_id ? 'selected' : '' }}>
                            {{ $jabatan->nama }}
                        </option>
                    @endforeach
                </select>

                @if($selectedJabatan)
                    <input type="hidden" name="jabatan_id" value="{{ $selectedJabatan }}">
                @endif
              </div>

              <input type="text" name="telepon" placeholder="Nomor Telpon" 
                     value="{{ old('telepon', $dosen->telp ?? '') }}">

              <input type="text" name="pendidikan" placeholder="Pendidkan Akhir" 
                     value="{{ old('pendidikan', $dosen->pendidikan ?? '') }}">

              <input type="text" name="bidang" placeholder="Bidang Ahli" 
                     value="{{ old('bidang', $dosen->bidang ?? '') }}">                     
              <label for="profile_picture">Upload Profile Picture</label>
              <input type="file" name="profile_picture" id="profile_picture" accept=".jpg,.jpeg,.png">

              <button type="submit" class="btn-submit">SUBMIT</button>
            </form>
            
            @if($dosen && $dosen->profile_picture)
              <form method="POST" action="{{ route('profile.remove-picture') }}" class="remove-picture-form">
                @csrf
                <button type="submit" class="btn-danger"
                        onclick="return confirm('Are you sure you want to remove your profile picture?');">
                  Remove Profile Picture
                </button>
              </form>
            @endif
          </div>

          <!-- Academic Info -->
          <div id="academic-info" role="tabpanel" aria-labelledby="tab-academic">
            <div id="academic-form">

              {{-- === Default View === --}}
              <div class="academic-sections" id="academic-table-view">
                {{-- Mata Kuliah Section --}}
                <div class="academic-section">
                  <h3>Mata Kuliah</h3>
                  <div class="table-container">
                    <table class="table">
                      <thead>
                        <tr>
                          <th>Kode</th>
                          <th>Nama Mata Kuliah</th>
                          <th>Aksi</th>
                        </tr>
                      </thead>
                      <tbody>
                        @forelse($myMatkul as $mk)
                          <tr>
                            <td>{{ $mk->kode_matkul }}</td>
                            <td>{{ $mk->nama }}</td>
                            <td>
                              <form method="POST" action="{{ route('user.matkul.destroy', $mk->matkul_id) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                              </form>
                            </td>
                          </tr>
                        @empty
                          <tr>
                            <td colspan="3" class="text-center">Belum ada mata kuliah yang ditambahkan.</td>
                          </tr>
                        @endforelse
                      </tbody>
                    </table>
                  </div>
                </div>

                {{-- Prestasi Akademik Section --}}
                <div class="academic-section">
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
              </div>

              {{-- === Add Mata Kuliah View === --}}
              <div id="mata-kuliah-form" hidden>
                <h3>Tambah Mata Kuliah</h3>
                <div class="table-container mb-4">
                  <h4>Mata Kuliah Ditambahkan</h4>
                  <table class="table" id="selected-mk-table">
                    <thead>
                      <tr>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    <tbody id="selected-mk-body"></tbody>
                  </table>
                </div>

                <div class="table-container">
                  <h4>Daftar Semua Mata Kuliah</h4>
                  <table class="table" id="available-mk-table">
                    <thead>
                      <tr>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    <tbody id="available-mk-body">
                      @foreach($matkulList as $mk)
                        <tr data-id="{{ $mk->matkul_id }}" data-kode="{{ $mk->kode_matkul }}" data-nama="{{ $mk->nama }}">
                          <td>{{ $mk->kode_matkul }}</td>
                          <td>{{ $mk->nama }}</td>
                          <td><button type="button" class="btn btn-success btn-sm add-mk">Tambah</button></td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>

                <div class="mt-3 d-flex gap-2">
                  <button type="button" id="back-mk" class="btn btn-secondary">Kembali</button>
                  <form id="submit-selected-form" method="POST" action="{{ route('user.matkul.bulkStore') }}">
                    @csrf
                    <input type="hidden" name="matkul_ids" id="matkul-ids-input">
                    <button type="submit" class="btn btn-primary">Simpan Semua</button>
                  </form>
                </div>
              </div>

              {{-- === Add Prestasi View === --}}
              <div id="prestasi-form" hidden>
                <h3>Tambah Prestasi Akademik</h3>
                <form method="POST" action="{{ route('prestasi.store') }}" id="add-prestasi-form">
                  @csrf
                  <div class="mb-3">
                    <label for="prestasi-nama" class="form-label">Nama Prestasi</label>
                    <input type="text" id="prestasi-nama" name="nama" class="form-control" required>
                  </div>
                  <div class="mb-3">
                    <label for="prestasi-link" class="form-label">Link (Website)</label>
                    <input type="url" id="prestasi-link" name="Link" class="form-control" required>
                  </div>
                  <div class="d-flex gap-2 mt-3">
                    <button type="button" id="back-prestasi" class="btn btn-secondary">Kembali</button>
                    <button type="submit" class="btn btn-primary">Simpan Prestasi</button>
                  </div>
                </form>
              </div>

              {{-- === Bottom Buttons === --}}
              <div class="academic-buttons" id="academic-buttons">
                <button type="button" id="add-mata-kuliah" class="btn btn-primary">Tambah Mata Kuliah</button>
                <button type="button" id="add-prestasi" class="btn btn-primary">Tambah Prestasi</button>
              </div>

            </div>
          </div>

          <!-- Research -->
          <div id="research-info" role="tabpanel" aria-labelledby="tab-research" hidden>
            <h2>Research & Publications</h2>
            <table>
              <thead>
                <tr>
                  <th>Judul</th>
                  <th>Tanggal</th>
                  <th>Jenis</th>
                  <th>Ketua</th>
                  <th>Status</th>
                  <th>Stample</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @forelse($programList as $program)
                <tr>
                  <td>{{ $program->judul }}</td>
                  <td>{{ $program->tanggal }}</td>
                  <td>{{ $program->jenis }}</td>
                  <td>{{ $program->ketua->dosen->nama ?? '-' }}</td>
                  <td>{{ $program->status }}</td>
                  <td>{{ $program->stamp }}</td>
                <td>
                  <a href="{{ route('program.view', $program->program_id) }}" class="btn btn-info" target="_blank">👁️ Visit</a>
                </td>
                </tr>
                @empty
                <tr>
                  <td colspan="7" style="text-align:center;">No research data available</td>
                </tr>
                @endforelse
              </tbody>
            </table>
          </div>

          <!-- Security -->
          <div id="security-info" role="tabpanel" aria-labelledby="tab-security">
            <!-- Step 1 -->
            <form id="send-code-form" method="POST" action="{{ route('profile.sendSecurityCode') }}"
                  @if(session('showVerify') || session('showUpdate')) style="display:none;" @endif>
              @csrf
              <button type="submit" class="btn-submit">
                Send Verification Code to {{ $user->email }}
              </button>
            </form>

            <!-- Step 2 -->
            <form id="verify-form" method="POST" action="{{ route('profile.verifySecurityCode') }}"
                  @if(!session('showVerify') || session('showUpdate')) style="display:none;" @endif>
              @csrf
              <input type="text" name="code" placeholder="Enter Code" required>
              <small class="code-expire-note">⚠️ The code will expire in 1 minute.</small>
              <button type="submit" class="btn-submit">Verify Code</button>
              <button type="button" class="btn-back" id="back-to-send">← Back</button>
            </form>

            <!-- Step 3 -->
            <form id="security-form" method="POST" action="{{ route('profile.updateSecurity') }}"
                  @if(!session('showUpdate')) style="display:none;" @endif>
              @csrf
              <input type="text" name="username" placeholder="Current: {{ $user->username }}" value="{{ old('username') }}">
              <input type="password" name="password" placeholder="Enter new password (leave blank to keep)">
              <input type="password" name="password_confirmation" placeholder="Confirm new password">
              <input type="email" name="email" placeholder="Current: {{ $user->email }}" value="{{ old('email') }}">
              <input type="text" name="nidn" placeholder="Current: {{ $user->nidn }}" value="{{ old('nidn') }}">
              <button type="submit" class="btn-submit">Update Security</button>
            </form>
          </div>
        </div>
      </div>
    </main>
  </div>
  <script src="{{ asset('js/Lprofile.js') }}"></script>
</body>
</html>