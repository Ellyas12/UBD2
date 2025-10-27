<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Daftar Program | UBD</title>
  <link rel="stylesheet" href="{{ asset('css/Lprogram.css') }}">
</head>
<body>
  <div class="dashboard-container">
    @include('lecturer.navbar')

    <div class="program-list">
      <h2>Daftar Program</h2>

      {{-- Flash messages --}}
      @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif
      @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
      @endif

      <table>
        <thead>
          <tr>
            <th>Judul</th>
            <th>Tanggal</th>
            <th>Jenis</th>
            <th>Ketua</th>
            <th>Status</th>
            <th>Stample</th>
            <th>Action</th>
          </tr>
        <tbody>
          @forelse($myPrograms as $program)
          <tr>
            <td>{{ $program->judul }}</td>
            <td>{{ $program->tanggal }}</td>
            <td>{{ $program->jenis }}</td>
            <td>{{ $program->ketua }}</td>
            <td>{{ $program->status }}</td>
            <td>{{ $program->stamp }}</td>
            <td>
              <a href="{{ route('program.view', $program->program_id) }}" class="btn btn-info" target="_blank">👁️ Visit</a>
               
              @if($program->status != 'Accepted' && $program->stamp != 'Done')
              <a href="{{ route('program.edit', $program->program_id) }}" class="btn btn-warning" target="_blank">✏️ Edit</a>
              <a href="{{ route('program.confirmDelete', $program->program_id) }}" class="btn btn-danger">🗑️ Delete</a>
              @endif
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="7" style="text-align:center;">No research data available</td>
          </tr>
          @endforelse
        </tbody>
      </table>

      <a href="{{ route('program.createProgram') }}" class="btn-submit">+ Tambah Program</a>
    </div>
  </div>
</body>
</html>