<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit User | UBD</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>

@include('admin.navbar')

<div class="dashboard-container">
    <h2>Edit User</h2>

    <form action="{{ route('admin.users.edit', $userData->user_id) }}" method="POST" style="margin-top:20px;">
        @csrf
        @method('PUT')

        <label>Username</label><br>
        <input type="text" name="username" value="{{ $userData->username }}" required><br><br>

        <label>Email</label><br>
        <input type="email" name="email" value="{{ $userData->email }}" required><br><br>

        <label>NIDN</label><br>
        <input type="text" name="nidn" value="{{ $userData->nidn }}"><br><br>

        <label>Role</label><br>
        <select name="role">
            <option value="admin" {{ $userData->role == 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="dosen" {{ $userData->role == 'dosen' ? 'selected' : '' }}>Dosen</option>
        </select><br><br>

        @if($userData->dosen)
            <h3>Dosen Info</h3>

            <label>Nama</label><br>
            <input type="text" name="nama" value="{{ $userData->dosen->nama }}"><br><br>

            <label>No Telp</label><br>
            <input type="text" name="telp" value="{{ $userData->dosen->telp }}"><br><br>

            <label>Pendidikan</label><br>
            <input type="text" name="pendidikan" value="{{ $userData->dosen->pendidikan }}"><br><br>

            <label>Bidang</label><br>
            <input type="text" name="bidang" value="{{ $userData->dosen->bidang }}"><br><br>
        @endif

        <button type="submit" style="padding:8px 12px;">Update</button>
        <a href="{{ route('admin.users') }}" style="margin-left:10px;">Cancel</a>
    </form>
</div>

</body>
</html>
