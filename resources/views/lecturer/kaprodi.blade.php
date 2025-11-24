<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Kaprodi Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/Lprogram.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>
    <div class="dashboard-container">
        @include('lecturer.navbar')
        <main class="main-content">
        <div class="program-section">
            <h2>🟡 Program Belum di-Stamp</h2>

            <form method="GET" class="mb-3">
                <input type="text" name="search_unstamped" placeholder="Cari judul..." value="{{ request('search_unstamped') }}">
                <button type="submit">Search</button>
            </form>

            @if($unstamped->isEmpty())
                <p>Tidak ada program yang menunggu stamp.</p>
            @else
            <table class="program-table">
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Ketua</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($unstamped as $program)
                        <tr>
                            <td>{{ $program->judul }}</td>
                            <td>{{ $program->dosen->nama ?? '-' }}</td>
                            <td>{{ $program->tanggal }}</td>
                            <td>{{ $program->stamp ?? 'Belum' }}</td>
                            <td>
                                <a href="{{ route('kaprodi.stamp.show', $program->program_id) }}" class="btn btn-success">
                                    📜 Stamp
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-3">
                {{ $unstamped->appends(request()->except('unstamped_page'))->links('pagination::bootstrap-5') }}
            </div>

            @endif
        </div>
        <hr>

        <div class="program-section">
            <h2>🟢 Program Sudah di-Stamp</h2>

            <form method="GET" class="mb-3">
                <input type="text" name="search_stamped" placeholder="Cari judul..." value="{{ request('search_stamped') }}">
                <button type="submit">Search</button>
            </form>

            @if($stamped->isEmpty())
                <p>Belum ada program yang di-stamp.</p>
            @else
            <table class="program-table">
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Ketua</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($stamped as $program)
                        <tr>
                            <td>{{ $program->judul }}</td>
                            <td>{{ $program->dosen->nama ?? '-' }}</td>
                            <td>{{ $program->tanggal }}</td>
                            <td><span class="badge bg-success">Done</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-3">
                {{ $stamped->appends(request()->except('stamped_page'))->links('pagination::bootstrap-5') }}
            </div>

            @endif
        </div>
    </main>
    </div>
</body>
</html>