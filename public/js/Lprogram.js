
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

  // Drag & Drop + Click upload logic
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

  // 🖼️ Display file list with icons and remove buttons
  function renderFileList() {
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

      // Icon
      const ext = file.name.split('.').pop().toLowerCase();
      const iconUrl = icons[ext] || icons.default;
      const img = document.createElement('img');
      img.src = iconUrl;
      img.style.width = '32px';
      img.style.height = '32px';

      // File name and size
      const info = document.createElement('span');
      info.textContent = `${file.name} (${(file.size / 1024).toFixed(1)} KB)`;
      info.style.flexGrow = '1';
      info.style.textAlign = 'left';

      // Remove button
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

  // ✅ Update the hidden input (so Laravel receives correct files)
  function updateFileInput() {
    const dataTransfer = new DataTransfer();
    selectedFiles.forEach(f => dataTransfer.items.add(f));
    fileInput.files = dataTransfer.files;
  }

  // ❌ Remove a single file
  function removeFile(index) {
    selectedFiles.splice(index, 1);
    updateFileInput();
    renderFileList();
  }

  // Click to open file dialog
  dropZone.addEventListener('click', () => fileInput.click());

  // Manual select
  fileInput.addEventListener('change', (e) => addFiles(e.target.files));

  // Drag events
  dropZone.addEventListener('dragover', (e) => {
    e.preventDefault();
    dropZone.style.borderColor = '#007bff';
  });

  dropZone.addEventListener('dragleave', (e) => {
    e.preventDefault();
    dropZone.style.borderColor = '#ccc';
  });

  dropZone.addEventListener('drop', (e) => {
    e.preventDefault();
    dropZone.style.borderColor = '#ccc';
    addFiles(e.dataTransfer.files);
  });