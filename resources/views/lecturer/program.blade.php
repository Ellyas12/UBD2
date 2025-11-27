<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Daftar Penelitian dan PKM | UBD</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/Lprogram.css') }}">
</head>
<body>
  <div class="dashboard-container">
    @include('lecturer.navbar')
    <main class="main-content">
      <div class="program-wrapper">
        <div class="program-list">
          <h2>📘 Daftar Penelitian dan PKM anda</h2>

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
                  <th class="left">Judul</th>
                  <th class="left">Jenis</th>
                  <th class="left">Tanggal</th>
                  <th class="center">Status</th>
                  <th class="center">Stample</th>
                  <th class="center">Action</th>
                </tr>
              </thead>
              <tbody>
                @forelse($myPrograms as $program)
                <tr>
                  <td class="left">{{ $program->judul }}</td>
                  <td class="left">{{ $program->jenis }}</td>
                  <td class="left">{{ $program->tanggal }}</td>
                  <td class="center">
                    <span class="badge 
                      @if($program->status == 'Pending') bg-warning text-dark
                      @elseif($program->status == 'Accepted') bg-success
                      @elseif($program->status == 'Revisi') bg-primary
                      @elseif($program->status == 'Denied') bg-danger
                      @else bg-secondary @endif">
                      {{ $program->status }}
                    </span>
                  </td>
                  <td class="center">
                    <span class="badge 
                      @if($program->stamp == 'Not yet') bg-warning text-dark
                      @elseif($program->stamp == 'Done') bg-success
                      @else bg-secondary @endif">
                      {{ $program->stamp }}
                    </span>
                  </td>
                  <td class="center">
                      <a href="{{ route('program.view', $program->program_id) }}" 
                        class="btn btn-info btn-sm" 
                        target="_blank">👁️ Visit</a>

                      @if($program->status === 'Done' && $program->stamp === 'Done')
                          {{-- nothing else shown --}}
                      
                      @elseif($program->status === 'Denied' && $program->stamp === 'Done')
                          <a href="{{ route('program.confirmDelete', $program->program_id) }}" 
                            class="btn btn-danger btn-sm">🗑️ Delete</a>

                      @elseif($program->status !== 'Accepted' && $program->stamp !== 'Done')
                          <a href="{{ route('program.edit', $program->program_id) }}" 
                            class="btn btn-warning btn-sm" 
                            target="_blank">✏️ Edit</a>

                          <a href="{{ route('program.confirmDelete', $program->program_id) }}" 
                            class="btn btn-danger btn-sm">🗑️ Delete</a>
                      @endif
                  </td>
                </tr>
                @empty
                <tr>
                  <td colspan="6" class="text-center text-muted">No research data available</td>
                </tr>
                @endforelse

                @php
                    $myrecentPrograms = $myPrograms->take(5);
                    $empty = 5 - $myrecentPrograms->count();
                @endphp

                @for ($i = 0; $i < $empty; $i++)
                <tr>
                    <td colspan="8" style="height:48px;border:none;">&nbsp;</td>
                </tr>
                @endfor

              </tbody>
            </table>
          <div class="mt-3">
              {{ $programList->links('pagination::bootstrap-5') }}
          </div>
          </div>

          <div class="program-actions">
              <a href="{{ route('program.createProgram') }}" class="btn-submit">+ Tambah Program</a>
              <a href="{{ route('program.restoreProgram') }}" class="btn-submit">♻️ Restore Program</a>
          </div>
        </div>
      </div>  
    </main>  
  </div>
</body>
</html>
