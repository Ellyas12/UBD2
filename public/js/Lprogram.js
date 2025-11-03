document.addEventListener('DOMContentLoaded', function() {

  // ==============================
  // Step navigation logic
  // ==============================
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

  // Expose globally so onclick in HTML still works
  window.nextStep = nextStep;
  window.prevStep = prevStep;


  // ==============================
  // Drag & Drop + Click upload logic
  // ==============================
  const dropZone = document.getElementById('dropZone');
  const fileInput = document.getElementById('fileInput');
  const fileList = document.getElementById('fileList');
  let selectedFiles = [];

  // 🧩 File type icons
  const icons = {
    pdf: "https://img.icons8.com/color/48/000000/pdf.png",
    doc: "https://img.icons8.com/color/48/000000/ms-word.png",
    docx: "https://img.icons8.com/color/48/000000/ms-word.png",
    zip: "https://img.icons8.com/color/48/000000/zip.png",
    jpg: "https://img.icons8.com/color/48/000000/jpg.png",
    jpeg: "https://img.icons8.com/color/48/000000/jpg.png",
    png: "https://img.icons8.com/color/48/000000/png.png",
    default: "https://img.icons8.com/ios/50/000000/file.png"
  };

  function renderFileList() {
    if (!fileList) return;
    fileList.innerHTML = '';

    selectedFiles.forEach((file, index) => {
      const li = document.createElement('li');
      li.style.display = 'flex';
      li.style.alignItems = 'center';
      li.style.justifyContent = 'space-between';
      li.style.gap = '10px';
      li.style.margin = '8px auto';
      li.style.border = '1px solid #ddd';
      li.style.padding = '6px 10px';
      li.style.borderRadius = '6px';
      li.style.background = '#f9f9f9';

      const ext = file.name.split('.').pop().toLowerCase();
      const iconUrl = icons[ext] || icons.default;
      const img = document.createElement('img');
      img.src = iconUrl;
      img.style.width = '32px';
      img.style.height = '32px';

      const info = document.createElement('span');
      info.textContent = `${file.name} (${(file.size / 1024).toFixed(1)} KB)`;
      info.style.flexGrow = '1';
      info.style.textAlign = 'left';

      const removeBtn = document.createElement('button');
      removeBtn.textContent = '❌ Remove';
      removeBtn.style.background = '#e74c3c';
      removeBtn.style.color = '#fff';
      removeBtn.style.border = 'none';
      removeBtn.style.padding = '5px 10px';
      removeBtn.style.borderRadius = '4px';
      removeBtn.style.cursor = 'pointer';
      removeBtn.onclick = () => removeFile(index);

      li.appendChild(img);
      li.appendChild(info);
      li.appendChild(removeBtn);
      fileList.appendChild(li);
    });
  }

  function addFiles(files) {
    if (!files) return;
    let totalSize = selectedFiles.reduce((sum, f) => sum + f.size, 0);
    for (let file of files) {
      if (selectedFiles.length >= 5) {
        alert("Maksimal 5 file!");
        break;
      }
      if (totalSize + file.size > 10 * 1024 * 1024) {
        alert("Total ukuran file tidak boleh lebih dari 10MB!");
        break;
      }
      if (!selectedFiles.find(f => f.name === file.name && f.size === file.size)) {
        selectedFiles.push(file);
        totalSize += file.size;
      }
    }
    updateFileInput();
    renderFileList();
  }

  function updateFileInput() {
    if (!fileInput) return;
    const dataTransfer = new DataTransfer();
    selectedFiles.forEach(f => dataTransfer.items.add(f));
    fileInput.files = dataTransfer.files;
  }

  function removeFile(index) {
    selectedFiles.splice(index, 1);
    updateFileInput();
    renderFileList();
  }

  if (dropZone && fileInput) {
    dropZone.addEventListener('click', () => fileInput.click());
    fileInput.addEventListener('change', e => addFiles(e.target.files));

    dropZone.addEventListener('dragover', e => {
      e.preventDefault();
      dropZone.style.borderColor = '#007bff';
    });

    dropZone.addEventListener('dragleave', e => {
      e.preventDefault();
      dropZone.style.borderColor = '#ccc';
    });

    dropZone.addEventListener('drop', e => {
      e.preventDefault();
      dropZone.style.borderColor = '#ccc';
      addFiles(e.dataTransfer.files);
    });
  }


  // ==============================
  // Select2 AJAX Search (Ketua + Anggota)
  // ==============================
  if (window.jQuery && $('#ketua_id').length && $('#anggota_ids').length) {

    function highlightMatchedText(data) {
      if (!data.id) return data.text;
      const search = $('.select2-search__field').val();
      if (!search) return data.text;
      const regex = new RegExp('(' + search + ')', 'ig');
      const highlighted = data.text.replace(regex, '<b style="color:#007bff;">$1</b>');
      return $('<span>' + highlighted + '</span>');
    }

    // Ketua
    const ketuaSelect = $('#ketua_id').select2({
      theme: 'bootstrap-5',
      placeholder: "Cari dan pilih ketua...",
      allowClear: true,
      ajax: {
        url: '/program/search-dosen',
        dataType: 'json',
        delay: 250,
        data: params => ({
          q: params.term,
          exclude: $('#anggota_ids').val() || [] // exclude anggota if selected
        }),
        processResults: data => ({
          results: data.map(item => ({
            id: item.dosen_id,
            text: `${item.nama} – ${item.nidn ?? ''}`
          }))
        }),
        cache: true
      },
      width: '100%',
      minimumInputLength: 1,
      templateResult: highlightMatchedText
    });

    // Anggota
    const anggotaSelect = $('#anggota_ids').select2({
      theme: 'bootstrap-5',
      placeholder: "Cari dan tambahkan anggota...",
      allowClear: true,
      multiple: true,
      ajax: {
        url: '/program/search-dosen',
        dataType: 'json',
        delay: 250,
        data: params => {
          const ketuaId = $('#ketua_id').val();
          return {
            q: params.term,
            exclude: ketuaId ? [ketuaId] : [] // ✅ only send if ketua selected
          };
        },
        processResults: data => ({
          results: data.map(item => ({
            id: item.dosen_id,
            text: `${item.nama} – ${item.nidn ?? ''}`
          }))
        }),
        cache: true
      },
      width: '100%',
      minimumInputLength: 1,
      templateResult: highlightMatchedText
    });

    // Optional: prevent same dosen selection
    ketuaSelect.on('change', function() {
      const ketuaId = $(this).val();
      const anggotaIds = $('#anggota_ids').val() || [];

      if (anggotaIds.includes(ketuaId)) {
        $('#anggota_ids').val(null).trigger('change');
      }
    });

    anggotaSelect.on('change', function() {
      const ketuaId = $('#ketua_id').val();
      const anggotaIds = $(this).val() || [];

      if (anggotaIds.includes(ketuaId)) {
        $('#ketua_id').val(null).trigger('change');
      }
    });
  }
  
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
});
