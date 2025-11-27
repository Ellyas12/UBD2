<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Home | UBD</title>
  <link rel="stylesheet" href="{{ asset('css/Lprogram.css') }}">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>
  <div class="dashboard-container">
  @include('admin.navbar')
  <main class="main-content">
    <div class="program-detail">
    <h2>User Management</h2>

    <form method="GET" class="mb-3">
        <input type="text" name="search_pending" placeholder="Cari judul..." value="{{ request('search_pending') }}" style="padding: 8px;">
        <button type="submit" style="padding: 8px 12px;">Search</button>
    </form>

    <div class="table-responsive">
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
                <a href="{{ route('admin.users.edit', $u->user_id) }}" 
                  class="btn btn-warning btn-sm" 
                  target="_blank">✏️ Edit</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-3">
    {{ $users->links('pagination::bootstrap-5') }}
    </div>
</div>
</div>
</main>
</body>
</html>