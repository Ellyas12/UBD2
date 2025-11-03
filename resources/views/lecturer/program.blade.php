<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Daftar Program | UBD</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/Lprogram.css') }}">
  <script src="{{ asset('js/Block.js') }}"></script>
  <style>
    body {
      background-color: #f7f8fa;
      font-family: 'Poppins', sans-serif;
    }

    .program-list {
      margin: 2rem auto;
      background: #fff;
      border-radius: 15px;
      padding: 2rem;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
      max-width: 95%;
    }

    h2 {
      font-weight: 600;
      color: #b5121b;
      border-bottom: 3px solid #b5121b;
      padding-bottom: .5rem;
      margin-bottom: 1.5rem;
    }

    .alert {
      border-radius: 10px;
      font-size: 0.95rem;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      border-radius: 10px;
      overflow: hidden;
    }

    thead {
      background-color: #b5121b;
      color: #fff;
    }

    th, td {
      padding: 12px 15px;
      vertical-align: middle;
      text-align: center;
    }

    tbody tr:nth-child(even) {
      background-color: #f9f9f9;
    }

    tbody tr:hover {
      background-color: #ffecec;
      transition: 0.2s;
    }

    .btn {
      border-radius: 8px;
      font-weight: 500;
      padding: 5px 12px;
    }

    .btn-info {
      background-color: #007bff;
      color: #fff;
    }

    .btn-warning {
      background-color: #ffc107;
      color: #000;
    }

    .btn-danger {
      background-color: #dc3545;
      color: #fff;
    }

    .btn-submit {
      background-color: #b5121b;
      color: #fff;
      border: none;
      padding: 10px 20px;
      border-radius: 10px;
      font-weight: 600;
      margin-top: 1.5rem;
      display: inline-block;
      transition: 0.3s;
    }

    .btn-submit:hover {
      background-color: #8f0f15;
    }

    @media (max-width: 768px) {
      table {
        font-size: 0.85rem;
      }
      .program-list {
        padding: 1rem;
      }
    }
  </style>
</head>
<body>
  <div class="dashboard-container">
    @include('lecturer.navbar')

    <div class="program-list">
      <h2>📘 Daftar Program</h2>

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
              <th>Ketua</th>
              <th>Anggota</th>
              <th>Status</th>
              <th>Stample</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @forelse($myPrograms as $program)
            <tr>
              <td>{{ $program->judul }}</td>
              <td>{{ $program->jenis }}</td>
              <td>{{ $program->tanggal }}</td>
              <td>{{ $program->ketua->dosen->nama ?? '-' }}</td>
              <td>
                @if ($program->anggota->isNotEmpty())
                    @foreach ($program->anggota as $index => $anggota)
                        {{ $index + 1 }}. {{ $anggota->dosen->nama ?? '-' }}<br>
                    @endforeach
                @else
                    -
                @endif
              </td>
              <td>
                <span class="badge 
                  @if($program->status == 'Pending') bg-warning text-dark
                  @elseif($program->status == 'Accepted') bg-success
                  @else bg-secondary @endif">
                  {{ $program->status }}
                </span>
              </td>
              <td>{{ $program->stamp }}</td>
              <td>
                <a href="{{ route('program.view', $program->program_id) }}" class="btn btn-info btn-sm" target="_blank">👁️ Visit</a>
                @if($program->status != 'Accepted' && $program->stamp != 'Done')
                  <a href="{{ route('program.edit', $program->program_id) }}" class="btn btn-warning btn-sm" target="_blank">✏️ Edit</a>
                  <a href="{{ route('program.confirmDelete', $program->program_id) }}" class="btn btn-danger btn-sm">🗑️ Delete</a>
                @endif
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="8" class="text-center text-muted">No research data available</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <a href="{{ route('program.createProgram') }}" class="btn-submit">+ Tambah Program</a>
      <a href="{{ route('program.restoreProgram') }}" class="btn-submit">♻️ Restore Program</a>
    </div>
  </div>
</body>
</html>
