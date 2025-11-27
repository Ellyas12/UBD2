<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit User | UBD</title>
    <link rel="stylesheet" href="{{ asset('css/Lprogram.css') }}">
    
  <link rel="stylesheet" href="{{ asset('css/Programedit.css') }}">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>

@if ($errors->any())
    <div style="color:red;">
        <strong>Validation Errors:</strong>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="dashboard-container">
    @include('admin.navbar')
    <main class="main-content">
        <div class="program-detail">
        <form action="{{ route('admin.users.update', $userData->user_id) }}" method="POST" style="margin-top:20px;">
            @csrf
            @method('PUT')
            <h2>User Role Edit</h2>

            <label>Username</label><br>
            <input type="text" name="username" value="{{ $userData->username }}" readonly><br><br>

            <label>Posisi</label><br>
            <select name="posisi" required>
                <option value="Dekan" {{ $userData->posisi == 'Dekan' ? 'selected' : '' }}>Dekan</option>
                <option value="Kaprodi" {{ $userData->posisi == 'Kaprodi' ? 'selected' : '' }}>Kaprodi</option>
                <option value="Guru" {{ $userData->posisi == 'Guru' ? 'selected' : '' }}>Guru</option>
            </select>
            <br><br>

            <label>Role</label><br>
            <select name="role" required>
                <option value="Lecturer" {{ $userData->role == 'Lecturer' ? 'selected' : '' }}>Lecturer</option>
                <option value="Admin" {{ $userData->role == 'Admin' ? 'selected' : '' }}>Admin</option>
            </select>
            <br><br>

            <label>Jabatan</label><br>
            <select name="jabatan_id">
                <option value="">-- Pilih Jabatan --</option>
                @foreach($jabatanList as $j)
                    <option value="{{ $j->jabatan_id }}"
                        {{ optional($userData->dosen)->jabatan_id == $j->jabatan_id ? 'selected' : '' }}>
                        {{ $j->nama }}
                    </option>
                @endforeach
            </select>
            <br><br>

            <label>Fakultas</label><br>
            <select name="fakultas_id">
                <option value="">-- Pilih Fakultas --</option>
                @foreach($fakultasList as $f)
                    <option value="{{ $f->fakultas_id }}"
                        {{ optional($userData->dosen)->fakultas_id == $f->fakultas_id ? 'selected' : '' }}>
                        {{ $f->nama }}
                    </option>
                @endforeach
            </select>
            <br><br>

            <button type="submit">Update</button>
            <a href="#" class="btn-cancel" onclick="window.close(); return false;">Cancel</a>
        </form>
</div>
    </main>
</div>
</body>
</html>
