<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit User | UBD</title>
</head>
<body>

@include('admin.navbar')

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
    <h2>Edit User</h2>

    <form action="{{ route('admin.users.update', $userData->user_id) }}" method="POST" style="margin-top:20px;">
        @csrf
        @method('PUT')


        <h3>User Info</h3>

        <label>Username</label><br>
        <input type="text" name="username" value="{{ $userData->username }}" required><br><br>

        <label>Email</label><br>
        <input type="email" name="email" value="{{ $userData->email }}" required><br><br>

        <label>NIDN</label><br>
        <input type="text" name="nidn" value="{{ $userData->nidn }}"><br><br>

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

        <h3>Dosen Info</h3>

        <label>Nama</label><br>
        <input type="text" name="nama" value="{{ optional($userData->dosen)->nama }}"><br><br>

        <label>No Telp</label><br>
        <input type="text" name="telp" value="{{ optional($userData->dosen)->telp }}"><br><br>

        <label>Pendidikan</label><br>
        <input type="text" name="pendidikan" value="{{ optional($userData->dosen)->pendidikan }}"><br><br>

        <label>Bidang</label><br>
        <input type="text" name="bidang" value="{{ optional($userData->dosen)->bidang }}"><br><br>

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
        <a href="{{ route('admin.users') }}">Cancel</a>
    </form>
</div>
</body>
</html>
