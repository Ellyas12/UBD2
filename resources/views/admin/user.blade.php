<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Home | UBD</title>
  <link rel="stylesheet" href="{{ asset('css/login.css') }}">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>
  <div class="dashboard-container">
  @include('admin.navbar')
    <div class="program-detail">
    <h2>User Management</h2>

    <form method="GET" action="{{ route('admin.users') }}">
        <input type="text" name="search" placeholder="Search user..." value="{{ $search }}" style="padding: 8px;">
        <button type="submit" style="padding: 8px 12px;">Search</button>
    </form>

    <div class="table-container">
    <table class="table-user">
        <thead>
            <tr>
                <th>No</th>
                <th>Username</th>
                <th>Email</th>
                <th>NIDN</th>
                <th>Role</th>
                <th>Nama Dosen</th>
                <th>Telp</th>
                <th>Pendidikan</th>
                <th>Bidang</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($users as $i => $u)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $u->username }}</td>
                <td>{{ $u->email }}</td>
                <td>{{ $u->nidn }}</td>
                <td>{{ ucfirst($u->role) }}</td>

                {{-- Dosen Fields --}}
                <td>{{ $u->dosen->nama ?? '-' }}</td>
                <td>{{ $u->dosen->telp ?? '-' }}</td>
                <td>{{ $u->dosen->pendidikan ?? '-' }}</td>
                <td>{{ $u->dosen->bidang ?? '-' }}</td>
                <td>
                    <a href="{{ route('admin.users.edit', $u->user_id) }}">
                        Edit
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
</div>