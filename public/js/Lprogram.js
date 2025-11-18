 document.addEventListener('DOMContentLoaded', function() {
    let currentStep = 1;
    const totalSteps = 3;

    const stepFillPercents = [0, 50, 100];
    const progressFill = document.getElementById('progressFill');
    const dots = [document.getElementById('stepDot1'), document.getElementById('stepDot2'), document.getElementById('stepDot3')];

    function updateProgressUI() {
      const percent = stepFillPercents[currentStep - 1];
      progressFill.style.width = percent + '%';
      dots.forEach((d, idx) => {
        if (idx + 1 === currentStep) d.classList.add('active'); else d.classList.remove('active');
      });
    }
    updateProgressUI();

    function showErrors(containerId, messages) {
      const el = document.getElementById(containerId);
      if (!el) return;
      if (!messages || messages.length === 0) {
        el.style.display = 'none';
        el.innerHTML = '';
        return;
      }
      el.style.display = 'block';
      el.innerHTML = messages.map(m => `<div>• ${m}</div>`).join('');
      window.scrollTo({ top: el.getBoundingClientRect().top + window.scrollY - 100, behavior: 'smooth' });
    }

    function validateStep(step) {
      const errors = [];
      if (step === 1) {
        const jenis = document.getElementById('jenis');
        const judul = document.getElementById('judul');
        const bidang = document.getElementById('bidang');
        const topik = document.getElementById('topik');
        if (!jenis || !jenis.value) errors.push('Jenis program harus dipilih.');
        if (!judul || !judul.value.trim()) errors.push('Judul harus diisi.');
        if (!bidang || !bidang.value.trim()) errors.push('Bidang harus diisi.');
        if (!topik || !topik.value.trim()) errors.push('Topik harus diisi.');
      } else if (step === 2) {
        const tanggal = document.getElementById('tanggal');
        const pertemuan = document.getElementById('pertemuan_id');
        const biaya = document.getElementById('biaya');
        const sumber = document.getElementById('sumber_biaya');
        if (!tanggal || !tanggal.value) errors.push('Tanggal harus dipilih.');
        if (!pertemuan || !pertemuan.value) errors.push('Pertemuan harus dipilih.');
        if (!biaya || !biaya.value) errors.push('Biaya harus diisi.');
        if (!sumber || !sumber.value.trim()) errors.push('Sumber biaya harus diisi.');
      } else if (step === 3) {
        const ketua = document.getElementById('ketua_id');
        if (!ketua || !ketua.value) errors.push('Ketua harus dipilih.');
      }
      return errors;
    }

    function goToStep(newStep) {
      document.getElementById(`step-${currentStep}`).classList.remove('active');
      currentStep = newStep;
      document.getElementById(`step-${currentStep}`).classList.add('active');
      updateProgressUI();
      showErrors(`step${currentStep}Errors`, []);
    }

    // Button handlers (C2)
    document.getElementById('nextBtn1').addEventListener('click', function() {
      const errs = validateStep(1);
      if (errs.length) {
        showErrors('step1Errors', errs);
        return;
      }
      showErrors('step1Errors', []);
      goToStep(2);
    });

    document.getElementById('backBtn2').addEventListener('click', function() {
      goToStep(1);
    });

    document.getElementById('nextBtn2').addEventListener('click', function() {
      const errs = validateStep(2);
      if (errs.length) {
        showErrors('step2Errors', errs);
        return;
      }
      showErrors('step2Errors', []);
      goToStep(3);
    });

    document.getElementById('backBtn3').addEventListener('click', function() {
      goToStep(2);
    });

    // When user submits the form, validate final step as well (server-side will still validate)
    document.getElementById('programForm').addEventListener('submit', function(e) {
      const errs = validateStep(3);
      if (errs.length) {
        e.preventDefault();
        showErrors('step3Errors', errs);
        return false;
      }
      showErrors('step3Errors', []);
      return true; // allow submit
    });

    // ---------- Autofill mitigation: clear values from the old session when focusing ----------
    // Remove value of fields on focus if they're not server-populated (but preserve server-old via old() if you want)
    // To avoid losing legitimate server-old values, do NOT auto-clear values that are non-empty and server set.
    // We'll clear browser-suggested 'saved' values by resetting autocomplete attributes and using dummy fields already added.
    const inputsToProtect = ['judul','bidang','topik','deskripsi','linkweb'];
    inputsToProtect.forEach(id => {
      const el = document.getElementById(id);
      if (!el) return;
      // Try to remove "previously filled suggestions" by briefly setting autocomplete to "off" and blurring
      el.setAttribute('autocomplete','new-' + Math.random().toString(36).slice(2));
      // prevent browser remembering on click by clearing datalist-like suggestions (best-effort)
      el.addEventListener('focus', function() {
        // no destructive clearing if value was output by server (old value) — keep it
        // If you want to fully clear on focus, uncomment next line:
        // if (!el.dataset.serverValue) el.value = '';
      });
    });

    // ---------- Select2 initialization for step 3 (ketua & anggota) ----------
    if (window.jQuery && $('#ketua_id').length && $('#anggota_ids').length) {
      function highlightMatchedText(data) {
        if (!data.id) return data.text;
        const search = $('.select2-search__field').val();
        if (!search) return data.text;
        const regex = new RegExp('(' + search + ')', 'ig');
        const highlighted = data.text.replace(regex, '<b style="color:#007bff;">$1</b>');
        return $('<span>' + highlighted + '</span>');
      }

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
            exclude: $('#anggota_ids').val() || []
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
              exclude: ketuaId ? [ketuaId] : []
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

    // ---------- File uploader (drag & drop) ----------
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('fileInput');
    const fileList = document.getElementById('fileList');
    let selectedFiles = [];

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
        removeBtn.type = 'button';
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

    // ---------- Existing files: deletion logic (keeps your logic) ----------
    const deletedFilesInput = document.getElementById('deleted_files');
    const existingFilesList = document.getElementById('existingFiles');
    let deletedFiles = [];

    existingFilesList?.addEventListener('click', function (e) {
      if (e.target.classList.contains('markDeleteBtn')) {
        const li = e.target.closest('li');
        const fileId = li.dataset.id;

        li.remove();

        deletedFiles.push(fileId);
        deletedFilesInput.value = deletedFiles.join(',');
      }
    });

    setTimeout(() => {
      try {
        document.getElementById('prevent_autofill_username').value = '';
        document.getElementById('prevent_autofill_password').value = '';
      } catch (e) {}
    }, 500);

  });