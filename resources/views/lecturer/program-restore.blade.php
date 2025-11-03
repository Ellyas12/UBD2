<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Restore Program | UBD</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background-color: #f7f8fa; font-family: 'Poppins', sans-serif; }
    .container { margin-top: 3rem; background: #fff; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.08); }
    h2 { color: #b5121b; border-bottom: 2px solid #b5121b; padding-bottom: .5rem; }
  </style>
</head>
<body>
    <div class="dashboard-container">
    @include('lecturer.navbar')

    <div class="container">
    <h2>🧩 Restore Program</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <table class="table table-bordered mt-3">
        <thead class="table-danger">
        <tr>
            <th>Judul</th>
            <th>Jenis</th>
            <th>Tanggal</th>
            <th>Backup Terakhir</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @forelse($deletedPrograms as $program)
            <tr>
            <td>{{ $program->judul }}</td>
            <td>{{ $program->jenis }}</td>
            <td>{{ $program->tanggal }}</td>
            <td>
                {{ \App\Models\ProgramBackup::where('program_id', $program->program_id)->latest()->first()->created_at ?? '-' }}
            </td>
            <td>
                <form action="{{ route('program.restore', $program->program_id) }}" method="POST" onsubmit="return confirm('Yakin ingin merestore program ini?')">
                @csrf
                <button type="submit" class="btn btn-success btn-sm">♻️ Restore</button>
                </form>
            </td>
            </tr>
        @empty
            <tr>
            <td colspan="5" class="text-center text-muted">Tidak ada program yang dihapus.</td>
            </tr>
        @endforelse
        </tbody>
    </table>

    <a href="{{ route('program') }}" class="btn btn-secondary mt-3">⬅️ Kembali</a>
    </div>
    </div>
</body>
</html>
