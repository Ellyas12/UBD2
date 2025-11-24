<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dekan Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/Lprogram.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>
<div class="dashboard-container">
    @include('lecturer.navbar')
    <main class="main-content">
    <div class="program-section">
        <h2>🟡 Program Menunggu Review</h2>

        <form method="GET" class="mb-3">
            <input type="text" name="search_pending" placeholder="Cari judul..." value="{{ request('search_pending') }}">
            <button type="submit">Search</button>
        </form>

        @if($pending->isEmpty())
            <p>Tidak ada program yang menunggu review.</p>
        @else
        <table class="program-table">
            <thead>
                <tr>
                    <th class="left">Judul</th>
                    <th class="left">Ketua</th>
                    <th class="left">Tanggal</th>
                    <th class="center">Status</th>
                    <th class="center">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pending as $program)
                    <tr>
                        <td class="left">{{ $program->judul }}</td>
                        <td class="left">{{ $program->dosen->nama ?? '-' }}</td>
                        <td class="left">{{ $program->tanggal }}</td>
                        <td class="center">{{ $program->status ?? 'Pending' }}</td>
                        <td class="center">
                            <a href="{{ route('dekan.review', $program->program_id) }}" class="btn btn-primary">
                                ✏️ Review
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-3">
            {{ $pending->appends(request()->except('pending_page'))->links('pagination::bootstrap-5') }}
        </div>

        @endif
    </div>
    <hr>

    {{-- Revision Programs --}}
    <div class="program-section">
        <h2>🟠 Program Perlu Revisi</h2>

        <form method="GET" class="mb-3">
            <input type="text" name="search_revision" placeholder="Cari judul..." value="{{ request('search_revision') }}">
            <button type="submit">Search</button>
        </form>

        @if($revision->isEmpty())
            <p>Tidak ada program yang perlu revisi.</p>
        @else
        <table class="program-table">
            <thead>
                <tr>
                    <th class="left">Judul</th>
                    <th class="left">Ketua</th>
                    <th class="left">Tanggal</th>
                    <th class="center">Status</th>
                    <th class="center">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($revision as $program)
                    <tr>
                        <td class="left">{{ $program->judul }}</td>
                        <td class="left">{{ $program->dosen->nama ?? '-' }}</td>
                        <td class="left">{{ $program->tanggal }}</td>
                        <td class="center"><span class="badge bg-warning text-dark">Revisi</span></td>
                        <td class="center">
                            <a href="{{ route('dekan.review', $program->program_id) }}" class="btn btn-primary">
                                🔄 Update Review
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-3">
            {{ $revision->appends(request()->except('revision_page'))->links('pagination::bootstrap-5') }}
        </div>

        @endif
    </div>
    <hr>

    {{-- Reviewed Programs --}}
    <div class="program-section">
        <h2>🟢 Program Telah Direview</h2>

        <form method="GET" class="mb-3">
            <input type="text" name="search_processed" placeholder="Cari judul..." value="{{ request('search_processed') }}">
            <button type="submit">Search</button>
        </form>

        @if($processed->isEmpty())
            <p>Belum ada program yang direview.</p>
        @else
        <table class="program-table">
            <thead>
                <tr>
                    <th class="left">Judul</th>
                    <th class="left">Ketua</th>
                    <th class="left">Tanggal</th>
                    <th class="center">Status</th>
                    <th class="center">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($processed as $program)
                    <tr>
                        <td class="left">{{ $program->judul }}</td>
                        <td class="left">{{ $program->dosen->nama ?? '-' }}</td>
                        <td class="left">{{ $program->tanggal }}</td>
                        <td class="center">
                            @if($program->status == 'Accepted')
                                <span class="badge bg-success">Accepted</span>
                            @elseif($program->status == 'Denied')
                                <span class="badge bg-danger">Denied</span>
                            @else
                                <span class="badge bg-secondary">{{ $program->status }}</span>
                            @endif
                        </td>
                        <td class="center">
                            <a href="{{ route('dekan.review', $program->program_id) }}" class="btn btn-secondary btn-sm ms-2">👁️ View</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-3">
            {{ $processed->appends(request()->except('processed_page'))->links('pagination::bootstrap-5') }}
        </div>

        @endif
    </div>

</main>
</div>
</body>
</html>
