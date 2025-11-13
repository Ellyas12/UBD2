<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Profile | UBD</title>
  <link rel="stylesheet" href="{{ asset('css/Aprofile.css') }}">
</head>
<body>
  <div class="dashboard-container">
    @include('admin.navbar')

    <main class="main-content">
      <div class="profile-container">
        <div class="profile-sidebar">
          <div class="profile-avatar">
            <img src="{{ $dosen && $dosen->profile_picture ? asset('storage/'.$dosen->profile_picture) : asset('images/default.jpg') }}" alt="Profile Photo" class="avatar-img">
          </div>
          <nav class="profile-menu">
            <ul role="tablist">
              <li><button id="tab-personal" class="active" data-target="personal-info">Personal Info</button></li>
              <li><button id="tab-security" data-target="security-info">Security & Accessibility</button></li>
            </ul>
          </nav>
        </div>

        <div class="profile-form">
          @if(session('success'))
            <div class="alert success">{{ session('success') }}</div>
          @endif
          @if(session('error'))
            <div class="alert error">{{ session('error') }}</div>
          @endif

          <!-- Personal Info -->
          <div id="personal-info" class="tab-panel active">
            <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data">
              @csrf
              <input type="text" name="nama" placeholder="Nama Lengkap" value="{{ old('nama', $dosen->nama ?? $user->nama) }}" required>
              <input type="email" name="email" value="{{ $user->email }}" readonly>
              <select name="fakultas_id">
                <option value="">-- Fakultas --</option>
                @foreach($fakultasList as $f)
                  <option value="{{ $f->fakultas_id }}" {{ ($dosen->fakultas_id ?? '') == $f->fakultas_id ? 'selected' : '' }}>{{ $f->nama }}</option>
                @endforeach
              </select>
              <select name="jabatan_id">
                <option value="">-- Jabatan --</option>
                @foreach($jabatanList as $j)
                  <option value="{{ $j->jabatan_id }}" {{ ($dosen->jabatan_id ?? '') == $j->jabatan_id ? 'selected' : '' }}>{{ $j->nama }}</option>
                @endforeach
              </select>
              <input type="text" name="telepon" placeholder="Nomor Telepon" value="{{ old('telepon', $dosen->telp ?? '') }}">
              <label for="profile_picture">Upload Profile Picture</label>
              <input type="file" name="profile_picture" id="profile_picture" accept=".jpg,.jpeg,.png">
              <button type="submit" class="btn-submit">Update Profile</button>
            </form>

            @if($dosen && $dosen->profile_picture)
              <form method="POST" action="{{ route('admin.profile.remove-picture') }}">
                @csrf
                <button type="submit" class="btn-danger">Remove Profile Picture</button>
              </form>
            @endif
          </div>

          <!-- Security -->
          <div id="security-info" class="tab-panel" style="display:none;">
            <!-- Step 1 -->
            <form id="send-code-form" method="POST" action="{{ route('admin.profile.sendSecurityCode') }}"
                  @if(session('showVerify') || session('showUpdate')) style="display:none;" @endif>
              @csrf
              <button type="submit" class="btn-submit">Send Verification Code to {{ $user->email }}</button>
            </form>

            <!-- Step 2 -->
            <form id="verify-form" method="POST" action="{{ route('admin.profile.verifySecurityCode') }}"
                  @if(!session('showVerify') || session('showUpdate')) style="display:none;" @endif>
              @csrf
              <input type="text" name="code" placeholder="Enter Code" required>
              <small class="code-expire-note">⚠️ The code will expire in 1 minute.</small>
              <button type="submit" class="btn-submit">Verify Code</button>
              <button type="button" class="btn-back" id="back-to-send">← Back</button>
            </form>

            <!-- Step 3 -->
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

  <script>
    // 🔄 Tabs
    document.querySelectorAll('.profile-menu button').forEach(btn => {
      btn.addEventListener('click', () => {
        document.querySelectorAll('.tab-panel').forEach(tab => tab.style.display = 'none');
        document.querySelectorAll('.profile-menu button').forEach(b => b.classList.remove('active'));
        document.getElementById(btn.dataset.target).style.display = 'block';
        btn.classList.add('active');
      });
    });

    // 🔙 Back button in verification
    document.getElementById('back-to-send')?.addEventListener('click', () => {
      document.getElementById('verify-form').style.display = 'none';
      document.getElementById('send-code-form').style.display = 'block';
    });
  </script>
</body>
</html>
