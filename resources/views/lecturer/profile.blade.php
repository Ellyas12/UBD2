<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Profile | UBD</title>
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

              <select name="fakultas_id" required>
                <option value="">-- Pilih Fakultas --</option>
                @foreach($fakultasList as $fakultas)
                  <option value="{{ $fakultas->fakultas_id }}"
                    {{ old('fakultas_id', $dosen->fakultas_id ?? '') == $fakultas->fakultas_id ? 'selected' : '' }}>
                    {{ $fakultas->nama }}
                  </option>
                @endforeach
              </select>

              <select name="jabatan_id" required>
                <option value="">-- Pilih Jabatan --</option>
                @foreach($jabatanList as $jabatan)
                  <option value="{{ $jabatan->jabatan_id }}"
                    {{ old('jabatan_id', $dosen->jabatan_id ?? '') == $jabatan->jabatan_id ? 'selected' : '' }}>
                    {{ $jabatan->nama }}
                  </option>
                @endforeach
              </select>

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
          <div id="academic-info" role="tabpanel" aria-labelledby="tab-academic" hidden>
  <form id="academic-form">
    
    <!-- ==================== MATA KULIAH ==================== -->
    <h3>Mata Kuliah</h3>
    <input type="text" id="mata-kuliah-search" placeholder="Cari mata kuliah...">
    <select id="mata-kuliah-options">
      <option value="Algoritma">Algoritma</option>
      <option value="Struktur Data">Struktur Data</option>
      <option value="Jaringan Komputer">Jaringan Komputer</option>
    </select>
    <button type="button" id="add-mata-kuliah">Tambah</button>

    <table id="mata-kuliah-list" border="1" style="margin-top:10px; width:100%;">
      <thead>
        <tr>
          <th>Mata Kuliah</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>

    <!-- ==================== PRESTASI ==================== -->
    <h3>Prestasi Akademik</h3>
    <table id="prestasi-list" border="1" style="margin-top:10px; width:100%;">
      <thead>
        <tr>
          <th>Prestasi</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>
      <button type="button" id="add-prestasi">Tambah</button>
  </form>
</div>

          <!-- Research -->
          <div id="research-info" role="tabpanel" aria-labelledby="tab-research" hidden>
            <h2>Research & Publications</h2>
            <table>
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Judul</th>
                  <th>Date</th>
                  <th>Tipe</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>1</td>
                  <td>Contoh Judul</td>
                  <td>2023-10-01</td>
                  <td>Research</td>
                  <td><span class="badge-success">Completed</span></td>
                  <td>
                    <button>✏️</button>
                    <button class="btn-danger">🗑️</button>
                  </td>
                </tr>
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