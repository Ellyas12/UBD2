<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Program | UBD</title>
  <link rel="stylesheet" href="{{ asset('css/Lprogram.css') }}">
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
  <script src="{{ asset('js/Lprogram.js') }}"></script>
  <script src="{{ asset('js/Block.js') }}"></script>
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
<!-- Ketua -->
<div class="form-group mb-3">
    <label for="ketua_id">Ketua</label>
    <select name="ketua_id" id="ketua_id" class="form-control" required>
        <option value="">-- Pilih Ketua --</option>
        @foreach ($dosenList as $d)
            <option value="{{ $d->dosen_id }}" 
                {{ ($program->ketua && $program->ketua->dosen_id == $d->dosen_id) ? 'selected' : '' }}>
                {{ $d->nama }}
            </option>
        @endforeach
    </select>
</div>

<!-- Anggota -->
<div class="form-group mb-3">
    <label for="anggota_ids">Anggota</label>
    <select name="anggota_ids[]" id="anggota_ids" class="form-control" multiple>
        @foreach ($dosenList as $d)
            <option value="{{ $d->dosen_id }}"
                {{ $program->anggota->pluck('dosen_id')->contains($d->dosen_id) ? 'selected' : '' }}>
                {{ $d->nama }}
            </option>
        @endforeach
    </select>
</div>

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
</body>
</html>
