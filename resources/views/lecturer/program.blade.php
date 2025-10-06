<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Tambah Program | UBD</title>
  <link rel="stylesheet" href="{{ asset('css/login.css') }}">
  <style>
    .form-step { display: none; }
    .form-step.active { display: block; }
    .form-navigation {
      margin-top: 15px;
      display: flex;
      justify-content: space-between;
    }
  </style>
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

          <input type="text" name="topik" placeholder="Topik"
                 value="{{ old('topik') }}" required>

          <input type="text" name="bidang" placeholder="Bidang"
                 value="{{ old('bidang') }}" required>

          <input type="text" name="judul" placeholder="Judul"
                 value="{{ old('judul') }}" required>

          <input type="number" name="biaya" placeholder="Biaya"
                 value="{{ old('biaya') }}" required>

          <input type="text" name="sumber_biaya" placeholder="Sumber Biaya"
                 value="{{ old('sumber_biaya') }}" required>

          <div class="form-navigation">
            <button type="button" class="btn-submit" onclick="nextStep()">Next</button>
          </div>
        </div>

        <!-- Step 2 -->
        <div class="form-step" id="step-2">
          <input type="text" name="ketua" placeholder="Ketua"
                 value="{{ old('ketua') }}" required>

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
          <input type="date" name="tanggal" id="tanggal" 
                 value="{{ old('tanggal') }}" required>

          <textarea name="deskripsi" placeholder="Deskripsi">{{ old('deskripsi') }}</textarea>

    <div id="drop-area" 
         style="border:2px dashed #ccc; padding:20px; text-align:center; cursor:pointer; position:relative;">
        <span id="fileLabel">Drag & drop PDF/DOC here or click to select</span>
        <input type="file" name="linkpdf" id="fileInput" 
               accept=".pdf,.doc,.docx" style="display:none;">

        <div id="filePreview" style="margin-top:10px; display:none; text-align:center;">
            <img id="fileIcon" src="" alt="file icon" style="width:40px; height:40px; display:block; margin:auto;">
            <span id="fileName"></span>
            <button type="button" id="removeFile" 
                    style="margin-top:5px; background:#e74c3c; color:#fff; border:none; padding:5px 10px; border-radius:4px; cursor:pointer;">
                ❌ Remove
            </button>
        </div>
    </div>

          <div class="form-navigation">
            <button type="button" class="btn-submit" onclick="prevStep()">Back</button>
            <button type="submit" class="btn-submit">Simpan Program</button>
          </div>
        </div>

      </form>
    </div>
  </div>

  <script>
    let currentStep = 1;

    function nextStep() {
      document.getElementById(`step-${currentStep}`).classList.remove('active');
      currentStep++;
      document.getElementById(`step-${currentStep}`).classList.add('active');
    }

    function prevStep() {
      document.getElementById(`step-${currentStep}`).classList.remove('active');
      currentStep--;
      document.getElementById(`step-${currentStep}`).classList.add('active');
    }
  </script>

 <script>
const dropArea = document.getElementById("drop-area");
const fileInput = document.getElementById("fileInput");
const fileLabel = document.getElementById("fileLabel");
const filePreview = document.getElementById("filePreview");
const fileIcon = document.getElementById("fileIcon");
const fileName = document.getElementById("fileName");
const removeFile = document.getElementById("removeFile");

// Logos (you can replace with real image URLs in /public/icons/ )
const icons = {
    pdf: "https://img.icons8.com/color/48/000000/pdf.png",
    doc: "https://img.icons8.com/color/48/000000/ms-word.png"
};

// File chosen manually
fileInput.addEventListener("change", () => handleFile(fileInput.files[0]));

// Click area → file picker
dropArea.addEventListener("click", () => fileInput.click());

// Drag highlight
dropArea.addEventListener("dragover", (e) => {
    e.preventDefault();
    dropArea.style.borderColor = "green";
});

dropArea.addEventListener("dragleave", () => {
    dropArea.style.borderColor = "#ccc";
});

// Drop file
dropArea.addEventListener("drop", (e) => {
    e.preventDefault();
    dropArea.style.borderColor = "#ccc";

    if (e.dataTransfer.files.length > 0) {
        fileInput.files = e.dataTransfer.files; // assign dropped file
        handleFile(e.dataTransfer.files[0]);
    }
});

// Handle file display
function handleFile(file) {
    if (!file) return;

    fileLabel.style.display = "none";
    filePreview.style.display = "block";
    fileName.textContent = file.name;

    // Choose correct icon
    const ext = file.name.split('.').pop().toLowerCase();
    if (ext === "pdf") {
        fileIcon.src = icons.pdf;
    } else if (ext === "doc" || ext === "docx") {
        fileIcon.src = icons.doc;
    } else {
        fileIcon.src = "https://img.icons8.com/ios/50/000000/file.png"; // fallback
    }
}

// Remove/reset
removeFile.addEventListener("click", () => {
    fileInput.value = ""; // clear input
    filePreview.style.display = "none";
    fileLabel.style.display = "inline";
});
</script>
</body>
</html>