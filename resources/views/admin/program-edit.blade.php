<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Program | UBD</title>
  <link rel="stylesheet" href="{{ asset('css/login.css') }}">
<script>
  function handleStampChange() {
    const stampSelect = document.querySelector('select[name="stamp"]');
    const statusSelect = document.querySelector('select[name="status"]');
    const dosenSelect = document.querySelector('select[name="stamped_dosen"]');
    const stampValue = stampSelect.value;

    // Disable invalid statuses if Not yet
    [...statusSelect.options].forEach(opt => {
      opt.disabled = (stampValue === 'Not yet' && !['Pending', 'Revisi'].includes(opt.value));
    });

    // Disable dosen dropdown if Not yet
    dosenSelect.disabled = (stampValue === 'Not yet');
  }
  document.addEventListener('DOMContentLoaded', handleStampChange);
</script>
  
</head>
<body>
  <div class="dashboard-container">
    @include('admin.navbar')

    <div class="program-detail">
      <h2>Edit Program Status & Stamp</h2>

      <form method="POST" action="{{ route('admin.programs.update', $program->program_id) }}">
        @csrf

        <div>
          <label>Judul Program:</label>
          <p>{{ $program->judul }}</p>
        </div>

        <div>
          <label>Status:</label>
          <select name="status" required>
            <option value="Pending" {{ $program->status === 'Pending' ? 'selected' : '' }}>Pending</option>
            <option value="Accepted" {{ $program->status === 'Accepted' ? 'selected' : '' }}>Accepted</option>
            <option value="Denied" {{ $program->status === 'Denied' ? 'selected' : '' }}>Denied</option>
            <option value="Revisi" {{ $program->status === 'Revisi' ? 'selected' : '' }}>Revisi</option>
          </select>
        </div>

        <div>
          <label>Stamp:</label>
          <select name="stamp" onchange="handleStampChange()" required>
            <option value="Not yet" {{ $program->stamp === 'Not yet' ? 'selected' : '' }}>Not yet</option>
            <option value="Done" {{ $program->stamp === 'Done' ? 'selected' : '' }}>Done</option>
          </select>
        </div>

        <div>
        <label>Dosen (Kaprodi who stamped):</label>
        <select name="stamped_dosen">
            <option value="">-- Select Kaprodi --</option>
            @foreach ($dosenList as $d)
            <option value="{{ $d->dosen_id }}"
                @if(optional($program->stampRecord)->dosen_id === $d->dosen_id) selected @endif>
                {{ $d->nama }}
            </option>
            @endforeach
        </select>
        </div>

        <p style="color: gray; font-size: 13px; margin-top: 10px;">
          *If "Stamp" is set to "Not yet", only "Pending" or "Revisi" status is allowed.  
          Any existing stamp record will be deleted.
        </p>

        <div style="margin-top: 15px;">
          <button type="submit" style="padding: 8px 16px;">Save Changes</button>
          <a href="{{ route('admin.programs') }}" style="margin-left: 10px;">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</body>
</html>
