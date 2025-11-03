<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Tambah Program | UBD</title>
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

      <h2>Tambah Program</h2>

<form method="POST" action="{{ route('program.create') }}" enctype="multipart/form-data">
  @csrf

  <!-- Step 1 -->
  <div class="form-step active" id="step-1">
    <select name="jenis" required>
      <option value="">-- Pilih Jenis Program --</option>
      <option value="Penelitian" {{ old('jenis') == 'Penelitian' ? 'selected' : '' }}>Penelitian</option>
      <option value="PKM" {{ old('jenis') == 'PKM' ? 'selected' : '' }}>PKM</option>
    </select>

    <input type="text" name="topik" placeholder="Topik" value="{{ old('topik') }}" required>
    <input type="text" name="bidang" placeholder="Bidang" value="{{ old('bidang') }}" required>
    <input type="text" name="judul" placeholder="Judul" value="{{ old('judul') }}" required>
    <input type="number" name="biaya" placeholder="Biaya" value="{{ old('biaya') }}" required>
    <input type="text" name="sumber_biaya" placeholder="Sumber Biaya" value="{{ old('sumber_biaya') }}" required>

    <div class="form-navigation">
      <button type="button" class="btn-submit" onclick="nextStep()">Next</button>
    </div>
  </div>

  <!-- Step 2 -->
  <div class="form-step" id="step-2">
    <!-- Ketua -->
    <div class="form-group mb-3">
        <label for="ketua_id">Ketua</label>
        <select name="ketua_id" id="ketua_id" class="form-control" required></select>
    </div>

    <!-- Anggota -->
    <div class="form-group mb-3">
        <label for="anggota_ids">Anggota</label>
        <select name="anggota_ids[]" id="anggota_ids" class="form-control" multiple></select>
    </div>

    <select name="pertemuan_id" required>
      <option value="">-- Pilih Pertemuan --</option>
      @foreach($pertemuanList as $pertemuan)
        <option value="{{ $pertemuan->pertemuan_id }}" 
          {{ old('pertemuan_id') == $pertemuan->pertemuan_id ? 'selected' : '' }}>
          {{ $pertemuan->nama }}
        </option>
      @endforeach
    </select>

    <label for="tanggal">Tanggal</label>
    <input type="date" name="tanggal" id="tanggal" value="{{ old('tanggal') }}" required>

    <textarea name="linkweb" placeholder="Web link">{{ old('linkweb') }}</textarea>
    <textarea name="deskripsi" placeholder="Deskripsi">{{ old('deskripsi') }}</textarea>

    <div id="dropZone">
      <span id="fileLabel">Drag & drop up to 5 files (PDF, DOC, ZIP, JPG, PNG) or click to select</span>
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
      <button type="submit" class="btn-submit">Simpan Program</button>
    </div>
  </div>
</form>
</body>
</html>