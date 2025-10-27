<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Kaprodi Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/Lprogram.css') }}">
</head>
<body>
<div class="dashboard-container">
    @include('lecturer.navbar')

    <div class="program-section">
        <h2>🟡 Program Belum di-Stamp</h2>
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
        @endif
    </div>

    <hr>

    <div class="program-section">
        <h2>🟢 Program Sudah di-Stamp</h2>
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
        @endif
    </div>
</div>
</body>
</html>