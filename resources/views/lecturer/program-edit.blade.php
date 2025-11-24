<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Program | UBD</title>
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

        <h2>Edit Program</h2>

        <!-- Progress Bar -->
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

        <form method="POST" action="{{ route('program.update', $program->program_id) }}" enctype="multipart/form-data">
          @csrf
          @method('PUT')

          <!-- STEP 1 -->
          <div class="form-step active" id="step-1">
            <div id="step1Errors" class="error-box" style="display:none; color:red; margin-bottom:10px;"></div>
            <label>Jenis</label>
            <select name="jenis" id="jenis" required>
              <option value="">-- Pilih Jenis Program --</option>
              <option value="Penelitian" {{ $program->jenis == 'Penelitian' ? 'selected' : '' }}>Penelitian</option>
              <option value="PKM" {{ $program->jenis == 'PKM' ? 'selected' : '' }}>PKM</option>
            </select>

            <label>Judul</label>
            <input type="text" name="judul" id="judul" value="{{ $program->judul }}" required>

            <label>Bidang</label>
            <input type="text" name="bidang" id="bidang" value="{{ $program->bidang }}" required>

            <label>Topik</label>
            <input type="text" name="topik" id="topik" value="{{ $program->topik }}" required>

            <label>Deskripsi</label>
            <textarea name="deskripsi" id="deskripsi">{{ $program->deskripsi }}</textarea>

            <div class="form-navigation">
              <button type="button" class="btn-submit-secondary" onclick="window.location='{{ route('program') }}'">← Back</button>
              <button type="button" class="btn-submit-secondary" data-step="next">Next</button>
            </div>
          </div>

          <!-- STEP 2 -->
          <div class="form-step" id="step-2">
            <div id="step2Errors" class="error-box" style="display:none; color:red; margin-bottom:10px;"></div>

            <label for="tanggal">Tanggal</label>
            <input type="date" name="tanggal" id="tanggal" value="{{ $program->tanggal }}" required>

            <label>Pertemuan</label>
            <select name="pertemuan_id" id="pertemuan" required>
              <option value="">-- Pilih Pertemuan --</option>
              @foreach($pertemuanList as $p)
                <option value="{{ $p->pertemuan_id }}" {{ $program->pertemuan_id == $p->pertemuan_id ? 'selected' : '' }}>
                  {{ $p->nama }}
                </option>
              @endforeach
            </select>

            <label>Biaya</label>
            <div class="currency-wrapper">
              <span class="currency-prefix">Rp</span>
              <input type="text" name="biaya" id="biaya" value="{{ $program->biaya }}" required>
            </div>

            <label>Sumber Biaya</label>
            <input type="text" name="sumber_biaya" id="sumber_biaya" value="{{ $program->sumber_biaya }}" required>

            <div class="form-navigation">
              <button type="button" class="btn-submit-secondary" data-step="prev">Back</button>
              <button type="button" class="btn-submit-secondary" data-step="next">Next</button>
            </div>
          </div>

          <!-- STEP 3 -->
          <div class="form-step" id="step-3">
            <div id="step3Errors" class="error-box" style="display:none; color:red; margin-bottom:10px;"></div>

            <label for="ketua_id">Ketua</label>
            <select name="ketua_id" id="ketua_id" required>
              <option value="">-- Pilih Ketua --</option>
              @foreach ($dosenList as $d)
                <option value="{{ $d->dosen_id }}"
                  {{ ($program->ketua && $program->ketua->dosen_id == $d->dosen_id) ? 'selected' : '' }}>
                  {{ $d->nama }}
                </option>
              @endforeach
            </select>

            <label for="anggota_ids">Anggota</label>
            <select name="anggota_ids[]" id="anggota_ids" multiple>
              @foreach ($dosenList as $d)
                <option value="{{ $d->dosen_id }}"
                  {{ $program->anggota->pluck('dosen_id')->contains($d->dosen_id) ? 'selected' : '' }}>
                  {{ $d->nama }}
                </option>
              @endforeach
            </select>

            <label>Link Website</label>
            <textarea name="linkweb" id="linkweb">{{ $program->linkweb }}</textarea>

            <h3>Existing Files</h3>
            <ul id="existingFiles">
              @foreach($program->files as $file)
                <li data-id="{{ $file->file_id }}">
                  <a href="{{ asset('storage/' . $file->file) }}" target="_blank">{{ $file->nama }}</a>
                  <button type="button" class="markDeleteBtn">❌ Remove</button>
                </li>
              @endforeach
            </ul>

            <input type="hidden" name="deleted_files" id="deleted_files">

            <label>Upload File Baru</label>
            <div id="dropZone">
              <span id="fileLabel">Drag & drop up to 5 files or click to select</span>
              <input type="file" id="fileInput" name="linkpdf[]" multiple accept=".pdf,.doc,.docx,.zip,.jpg,.jpeg,.png" style="display:none;">
              <ul id="fileList"></ul>
            </div>

            <div class="form-navigation">
              <button type="button" class="btn-submit-secondary" data-step="prev">Back</button>
              <button type="submit" class="btn-submit">💾 Simpan Perubahan</button>
            </div>
          </div>

        </form>

      </div>
    </div>
  </main>
</div>
</body>
</html>
