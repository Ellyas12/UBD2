<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Tambah Program | UBD</title>

  <link rel="stylesheet" href="{{ asset('css/Lprogramcreate.css') }}">
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script defer src="{{ asset('js/Lprogram.js') }}"></script>
</head>
<body>
<div class="dashboard-container">
  @include('lecturer.navbar')
  <main class="main-content">
    <div class="program-wrapper">
      <div class="profile-form">

        {{-- Flash Messages --}}
        @if(session('success'))
          <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
          <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <h2>Tambah Program</h2>
        <div class="steps-wrapper">
          <div class="progress-track">
            <div class="progress-fill" id="progressFill"></div>
          </div>
          <div class="steps-labels">
            <div>
              <div class="step-dot" id="stepDot1">1</div>
              <div class="step-label">Informasi Program</div>
            </div>
            <div>
              <div class="step-dot" id="stepDot2">2</div>
              <div class="step-label">Detail & Biaya</div>
            </div>
            <div>
              <div class="step-dot" id="stepDot3">3</div>
              <div class="step-label">Anggota & File</div>
            </div>
          </div>
        </div>

        <form method="POST" action="{{ route('program.create') }}" enctype="multipart/form-data" autocomplete="off">
          @csrf
          <div class="form-step active" id="step-1">
            <div id="step1Errors" class="error-box" style="display:none;"></div>
            <label>Jenis</label>
            <select name="jenis" id="jenis" required>
              <option value="">-- Pilih Jenis Program --</option>
              <option value="Penelitian">Penelitian</option>
              <option value="PKM">PKM</option>
            </select>
            <label>Judul</label>
            <input type="text" name="judul" id="judul" placeholder="Judul" required>
            <label>Bidang</label>
            <input type="text" name="bidang" id="bidang" placeholder="Bidang" required>
            <label>Topik</label>
            <input type="text" name="topik" id="topik" placeholder="Topik" required>
            <label>Deskripsi</label>
            <textarea name="deskripsi" id="deskripsi" placeholder="Deskripsi (Optional)"></textarea>
            <div class="form-navigation">
              <button type="button" class="btn-submit-secondary" onclick="window.location='{{ route('program') }}'">
                  ← Back to Program List
              </button>
              <button type="button" class="btn-submit-secondary" data-step="next">Next</button>
            </div>
          </div>

          <div class="form-step" id="step-2">
            <div id="step2Errors" class="error-box" style="display:none;"></div>
            <label for="tanggal">Tanggal</label>
            <input type="date" name="tanggal" id="tanggal" required>
            <label>Pertemuan</label>
            <select name="pertemuan_id" id="pertemuan" required>
              <option value="">-- Pilih Pertemuan --</option>
              @foreach($pertemuanList as $p)
                <option value="{{ $p->pertemuan_id }}">{{ $p->nama }}</option>
              @endforeach
            </select>
            <label>Biaya</label>
            <div class="currency-wrapper">
              <span class="currency-prefix">Rp</span>
              <input type="text" name="biaya" id="biaya" placeholder="Biaya" required autocomplete="off">
            </div>
            <label>Sumber Biaya</label>
            <input type="text" name="sumber_biaya" id="sumber_biaya" placeholder="Sumber Biaya" required>
            <div class="form-navigation">
              <button type="button" class="btn-submit-secondary" data-step="prev">Back</button>
              <button type="button" class="btn-submit-secondary" data-step="next">Next</button>
            </div>
          </div>


          <div class="form-step" id="step-3">
            <div id="step3Errors" class="error-box" style="display:none;"></div>
            <label for="ketua_id">Ketua</label>
            <select name="ketua_id" id="ketua_id" required></select>
            <label for="anggota_ids">Anggota</label>
            <select name="anggota_ids[]" id="anggota_ids" multiple></select>
            <label>Link Website(Optional)</label>
            <textarea name="linkweb" id="linkweb" placeholder="Web link (Optional)"></textarea>
            <label>File Upload</label>
            <div id="dropZone">
              <span id="fileLabel">Drag & drop up to 5 files or click to select</span>
              <input type="file" id="fileInput" name="linkpdf[]" multiple accept=".pdf,.doc,.docx,.zip,.jpg,.jpeg,.png" style="display:none;">
              <ul id="fileList"></ul>
            </div>
            <div class="form-navigation">
              <button type="button" class="btn-submit-secondary" data-step="prev">Back</button>
              <button type="submit" class="btn-submit">Simpan Program</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </main>
</div>
</body>
</html>
