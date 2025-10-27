<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dekan Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/Lprogram.css') }}">
</head>
<body>
<div class="dashboard-container">
    @include('lecturer.navbar')

    {{-- Pending Programs --}}
    <div class="program-section">
        <h2>🟡 Program Menunggu Review</h2>
        @if($pending->isEmpty())
            <p>Tidak ada program yang menunggu review.</p>
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
                @foreach($pending as $program)
                    <tr>
                        <td>{{ $program->judul }}</td>
                        <td>{{ $program->dosen->nama ?? '-' }}</td>
                        <td>{{ $program->tanggal }}</td>
                        <td>{{ $program->status ?? 'Pending' }}</td>
                        <td>
                            <a href="{{ route('dekan.review', $program->program_id) }}" class="btn btn-primary">
                                ✏️ Review
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>

    <hr>

    {{-- Reviewed Programs --}}
    <div class="program-section">
        <h2>🟢 Program Telah Direview</h2>
        @if($processed->isEmpty())
            <p>Belum ada program yang direview.</p>
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
                @foreach($processed as $program)
                    <tr>
                        <td>{{ $program->judul }}</td>
                        <td>{{ $program->dosen->nama ?? '-' }}</td>
                        <td>{{ $program->tanggal }}</td>
                        <td>
                          @if($program->status == 'Accepted')
                              <span class="badge bg-success">Accepted</span>
                          @elseif($program->status == 'Denied')
                              <span class="badge bg-danger">Denied</span>
                          @elseif($program->status == 'Revisi') {{-- ✅ updated --}}
                              <span class="badge bg-warning text-dark">Revisi</span>
                          @else
                              <span class="badge bg-secondary">{{ $program->status }}</span>
                          @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</div>
</body>
</html>
