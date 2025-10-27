<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Tambah Program | UBD</title>
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
    <input type="text" name="ketua" placeholder="Ketua" value="{{ old('ketua') }}" required>
    <textarea name="anggota" placeholder="Anggota (pisahkan dengan koma)">{{ old('anggota') }}</textarea>

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
  <script src="{{ asset('js/Lprogram.js') }}"></script>
</body>
</html>