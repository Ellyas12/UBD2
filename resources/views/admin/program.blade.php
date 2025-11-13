<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Program Management | UBD</title>
  <link rel="stylesheet" href="{{ asset('css/login.css') }}">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>
  <div class="dashboard-container">
    @include('admin.navbar')

    <div class="program-detail">
      <h2>Program Management</h2>

      <form method="GET" action="{{ route('admin.programs') }}">
        <input type="text" name="search" placeholder="Search program..." value="{{ $search }}" style="padding: 8px;">
        <button type="submit" style="padding: 8px 12px;">Search</button>
      </form>

      <div class="table-container">
        <table class="table-user">
          <thead>
            <tr>
              <th>No</th>
              <th>Judul</th>
              <th>Jenis</th>
              <th>Bidang</th>
              <th>Tanggal</th>
              <th>Dosen</th>
              <th>Status</th>
              <th>Stamp</th>
              <th>Files</th>
              <th>Action</th>
            </tr>
          </thead>

          <tbody>
            @foreach ($programs as $i => $p)
              <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $p->judul }}</td>
                <td>{{ $p->jenis }}</td>
                <td>{{ $p->bidang }}</td>
                <td>{{ $p->tanggal }}</td>
                <td>{{ $p->dosen->nama ?? '-' }}</td>
                <td>{{ $p->status }}</td>
                <td>{{ $p->stamp }}</td>
                <td>
                  @if($p->files->count())
                    <ul>
                      @foreach($p->files as $f)
                        <li><a href="{{ Storage::url($f->file) }}" target="_blank">{{ $f->nama }}</a></li>
                      @endforeach
                    </ul>
                  @else
                    -
                  @endif
                </td>
                <td>
                  <a href="{{ route('admin.programs.edit', $p->program_id) }}">Edit</a>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</body>
</html>
