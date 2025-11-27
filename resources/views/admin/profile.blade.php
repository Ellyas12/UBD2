<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Profile | UBD</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <link rel="stylesheet" href="{{ asset('css/Lprofile.css') }}">
</head>
<body>
  <div class="dashboard-container">
    @include('admin.navbar')
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
            <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data">
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
       
              <label for="profile_picture">Upload Profile Picture</label>
              <input type="file" name="profile_picture" id="profile_picture" accept=".jpg,.jpeg,.png">

              @error('profile_picture')
                  <div class="text-danger">{{ $message }}</div>
              @enderror

              <button type="submit" class="btn-submit">SUBMIT</button>
            </form>
            
            @if($dosen && $dosen->profile_picture)
              <form method="POST" action="{{ route('admin.profile.remove-picture') }}" class="remove-picture-form">
                @csrf
                <button type="submit" class="btn-danger"
                        onclick="return confirm('Are you sure you want to remove your profile picture?');">
                  Remove Profile Picture
                </button>
              </form>
            @endif
          </div>

          <div id="security-info" role="tabpanel" aria-labelledby="tab-security">
            <form id="send-code-form" method="POST" action="{{ route('admin.profile.sendSecurityCode') }}"
                  @if(session('showVerify') || session('showUpdate')) style="display:none;" @endif>
              @csrf
              <button type="submit" class="btn-submit">
                Send Verification Code to {{ $user->email }}
              </button>
            </form>

            <form id="verify-form" method="POST" action="{{ route('admin.profile.verifySecurityCode') }}"
                  @if(!session('showVerify') || session('showUpdate')) style="display:none;" @endif>
              @csrf
              <input type="text" name="code" placeholder="Enter Code" required>
              <small class="code-expire-note">⚠️ The code will expire in 1 minute.</small>
              <button type="submit" class="btn-submit">Verify Code</button>
              <button type="button" class="btn-back" id="back-to-send">← Back</button>
            </form>

            <form id="security-form" method="POST" action="{{ route('admin.profile.updateSecurity') }}"
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