<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Restore Program | UBD</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/Lprogram.css') }}">
  <script src="{{ asset('js/Block.js') }}"></script>
</head>
<body>
  <div class="dashboard-container">
    @include('lecturer.navbar')

    <main class="main-content">
      <div class="program-list">
        <h2>🧩 Restore Program</h2>

        {{-- Flash messages --}}
        @if(session('success'))
          <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
          <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="table-responsive">
          <table class="table align-middle">
            <thead>
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

              @for ($i = $deletedPrograms->count(); $i < 5; $i++)
                <tr>
                  <td colspan="5" style="height: 48px; border: none;">&nbsp;</td>
                </tr>
              @endfor

            </tbody>
          </table>

          <div class="mt-3">
            {{ $deletedPrograms->links('pagination::bootstrap-5') }}
          </div>
        </div>
        <div class="program-actions">
          <a href="{{ route('program') }}" class="btn-submit">⬅️ Kembali</a>
        </div>
      </div>
    </main>
  </div>
</body>
</html>
