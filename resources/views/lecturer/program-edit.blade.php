<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Program | UBD</title>
  <link rel="stylesheet" href="{{ asset('css/Lprogram.css') }}">
</head>
<body>
  <div class="dashboard-container">
    @include('lecturer.navbar')

    <div class="profile-form">
      
      {{-- Flash Message --}}
      @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif
      @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
      @endif

      <h2>Edit Program</h2>

      <form method="POST" action="{{ route('program.update', $program->program_id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Step 1 -->
        <div class="form-step active" id="step-1">
          <select name="jenis" required>
            <option value="">-- Pilih Jenis Program --</option>
            <option value="Penelitian" {{ $program->jenis == 'Penelitian' ? 'selected' : '' }}>Penelitian</option>
            <option value="PKM" {{ $program->jenis == 'PKM' ? 'selected' : '' }}>PKM</option>
          </select>

          <input type="text" name="topik" placeholder="Topik" value="{{ $program->topik }}" required>
          <input type="text" name="bidang" placeholder="Bidang" value="{{ $program->bidang }}" required>
          <input type="text" name="judul" placeholder="Judul" value="{{ $program->judul }}" required>
          <input type="number" name="biaya" placeholder="Biaya" value="{{ $program->biaya }}" required>
          <input type="text" name="sumber_biaya" placeholder="Sumber Biaya" value="{{ $program->sumber_biaya }}" required>

          <div class="form-navigation">
            <button type="button" class="btn-submit" onclick="nextStep()">Next</button>
          </div>
        </div>

        <!-- Step 2 -->
        <div class="form-step" id="step-2">
          <input type="text" name="ketua" placeholder="Ketua" value="{{ $program->ketua }}" required>
          <textarea name="anggota" placeholder="Anggota (pisahkan dengan koma)">{{ $program->anggota }}</textarea>

          <select name="pertemuan_id" required>
            <option value="">-- Pilih Pertemuan --</option>
            @foreach($pertemuanList as $pertemuan)
              <option value="{{ $pertemuan->pertemuan_id }}" 
                {{ $program->pertemuan_id == $pertemuan->pertemuan_id ? 'selected' : '' }}>
                {{ $pertemuan->nama }}
              </option>
            @endforeach
          </select>

          <label for="tanggal">Tanggal</label>
          <input type="date" name="tanggal" id="tanggal" value="{{ $program->tanggal }}" required>
          <textarea name="linkweb" placeholder="Web link">{{ $program->linkweb }}</textarea>
          <textarea name="deskripsi" placeholder="Deskripsi">{{ $program->deskripsi }}</textarea>



<h3>Existing Files</h3>
<ul id="existingFiles">
  @foreach($program->files as $file)
    <li data-id="{{ $file->file_id }}">
      <a href="{{ asset('storage/' . $file->file) }}" target="_blank">{{ $file->nama }}</a>
      <button type="button" class="markDeleteBtn">❌ Remove</button>
    </li>
  @endforeach
</ul>

<input type="hidden" name="deleted_files" id="deleted_files" value="">

<div id="dropZone">
  <span id="fileLabel">Upload file baru (opsional, maksimal 5 file)</span>
  <input type="file"
        id="fileInput"
        name="linkpdf[]"
        multiple
        accept=".pdf,.doc,.docx,.zip,.jpg,.jpeg,.png"
        style="display:none;">
  <ul id="fileList"></ul>
</div>

          <div class="form-navigation">
            <button type="button" class="btn-submit" onclick="prevStep()">Back</button>
            <button type="submit" class="btn-submit">💾 Simpan Perubahan</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <script src="{{ asset('js/Lprogram.js') }}"></script>
  <script>
const deletedFilesInput = document.getElementById('deleted_files');
const existingFilesList = document.getElementById('existingFiles');
let deletedFiles = [];

existingFilesList?.addEventListener('click', function (e) {
  if (e.target.classList.contains('markDeleteBtn')) {
    const li = e.target.closest('li');
    const fileId = li.dataset.id;

    // Remove visually
    li.remove();

    // Mark for deletion
    deletedFiles.push(fileId);
    deletedFilesInput.value = deletedFiles.join(',');
  }
});
</script>
</body>
</html>
