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
    <h2>Penelitian dan PKM Management</h2>
      <form method="GET" class="mb-3">
          <input type="text" name="search_pending" placeholder="Cari judul..." value="{{ request('search_pending') }}" style="padding: 8px;">
          <button type="submit" style="padding: 8px 12px;">Search</button>
      </form>
      <div class="table-responsive">
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
                  <a href="{{ route('admin.programs.edit', $p->program_id) }}" 
                    class="btn btn-warning btn-sm" 
                    target="_blank">✏️ Edit</a>
                </td>
              </tr>
            @endforeach
          </tbody>

        </table>
        <div class="mt-3">
          {{ $programs->links('pagination::bootstrap-5') }}
        </div>

      </div>
    </div>
  </main>
</body>
</html>
