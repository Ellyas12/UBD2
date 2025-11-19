document.addEventListener("DOMContentLoaded", function () {
    let currentStep = 1;
    const totalSteps = 3;
    const progressFill = document.getElementById("progressFill");
    const dots = [
        document.getElementById("stepDot1"),
        document.getElementById("stepDot2"),
        document.getElementById("stepDot3")
    ];
    const stepPercents = [0, 50, 100];
    
    function updateProgress() {
        progressFill.style.width = stepPercents[currentStep - 1] + "%";

        dots.forEach((d, i) => {
            d.classList.toggle("active", i + 1 === currentStep);
        });
    }

    function showErrors(id, errors) {
        const box = document.getElementById(id);
        if (errors.length === 0) {
            box.style.display = "none";
            box.innerHTML = "";
            return;
        }

        box.style.display = "block";
        box.innerHTML = errors.map(e => `<div>• ${e}</div>`).join("");
    }

    function validate(step) {
        let errors = [];

        if (step === 1) {
            if (!jenis.value) errors.push("Jenis program harus dipilih");
            if (!judul.value.trim()) errors.push("Judul wajib diisi");
            if (!bidang.value.trim()) errors.push("Bidang wajib diisi");
            if (!topik.value.trim()) errors.push("Topik wajib diisi");
        }

        if (step === 2) {
            if (!tanggal.value) errors.push("Tanggal wajib diisi");
            if (!pertemuan.value) errors.push("Pertemuan wajib dipilih");
            if (!biaya.value) errors.push("Biaya wajib diisi");
            if (!sumber_biaya.value.trim()) errors.push("Sumber biaya wajib diisi");
        }

        if (step === 3) {
            if (!ketua_id.value) errors.push("Ketua wajib dipilih");
        }

        return errors;
    }

    function goTo(step) {
        document.querySelector(`#step-${currentStep}`).classList.remove("active");
        currentStep = step;
        document.querySelector(`#step-${currentStep}`).classList.add("active");
        updateProgress();
    }

    document.querySelectorAll(".btn-submit-secondary[data-step]").forEach(btn => {
        btn.addEventListener("click", () => {
            const dir = btn.dataset.step;

            if (dir === "next") {
                const errs = validate(currentStep);
                showErrors(`step${currentStep}Errors`, errs);
                if (errs.length === 0 && currentStep < totalSteps) goTo(currentStep + 1);
            }

            if (dir === "prev") {
                if (currentStep > 1) goTo(currentStep - 1);
            }

        });
    });

    document.querySelector("form").addEventListener("submit", e => {
        const errs = validate(3);
        if (errs.length) {
            e.preventDefault();
            showErrors("step3Errors", errs);
        }
    });

    updateProgress();

    const inputsToProtect = ['judul','bidang','topik','deskripsi','linkweb'];
    inputsToProtect.forEach(id => {
        const el = document.getElementById(id);
        if (!el) return;
        el.setAttribute('autocomplete','new-' + Math.random().toString(36).slice(2));
    });

    if (window.jQuery && $('#ketua_id').length && $('#anggota_ids').length) {
        function highlightMatchedText(data) {
            if (!data.id) return data.text;
            const search = $('.select2-search__field').val();
            if (!search) return data.text;
            const regex = new RegExp('(' + search + ')', 'ig');
            const highlighted = data.text.replace(regex, '<b style="color:#007bff;">$1</b>');
            return $('<span>' + highlighted + '</span>');
        }

        $('#ketua_id').select2({
            theme: 'bootstrap-5',
            placeholder: "Cari dan pilih ketua...",
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
                })
            },
            width: '100%',
            minimumInputLength: 1,
            templateResult: highlightMatchedText
        });

        $('#anggota_ids').select2({
            theme: 'bootstrap-5',
            placeholder: "Cari dan tambahkan anggota...",
            multiple: true,
            ajax: {
                url: '/program/search-dosen',
                dataType: 'json',
                delay: 250,
                data: params => ({
                    q: params.term,
                    exclude: $('#ketua_id').val() ? [$('#ketua_id').val()] : []
                }),
                processResults: data => ({
                    results: data.map(item => ({
                        id: item.dosen_id,
                        text: `${item.nama} – ${item.nidn ?? ''}`
                    }))
                })
            },
            width: '100%',
            minimumInputLength: 1,
            templateResult: highlightMatchedText
        });
    }

    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('fileInput');
    const fileList = document.getElementById('fileList');
    let selectedFiles = [];

    const icons = {
        pdf: "https://img.icons8.com/color/48/pdf.png",
        doc: "https://img.icons8.com/color/48/ms-word.png",
        docx: "https://img.icons8.com/color/48/ms-word.png",
        zip: "https://img.icons8.com/color/48/zip.png",
        jpg: "https://img.icons8.com/color/48/jpg.png",
        jpeg: "https://img.icons8.com/color/48/jpg.png",
        png: "https://img.icons8.com/color/48/png.png",
        default: "https://img.icons8.com/ios/50/file.png"
    };

    function renderFileList() {
        if (!fileList) return;
        fileList.innerHTML = '';

        selectedFiles.forEach((file, index) => {
            const li = document.createElement('li');
            li.style.display = 'flex';
            li.style.alignItems = 'center';
            li.style.justifyContent = 'space-between';
            li.style.padding = '6px 10px';

            const ext = file.name.split('.').pop().toLowerCase();
            const img = document.createElement('img');
            img.src = icons[ext] || icons.default;
            img.style.width = '32px';

            const info = document.createElement('span');
            info.textContent = `${file.name} (${(file.size / 1024).toFixed(1)} KB)`;
            info.style.flexGrow = '1';

            const rm = document.createElement('button');
            rm.textContent = "❌ Remove";
            rm.onclick = () => removeFile(index);

            li.appendChild(img);
            li.appendChild(info);
            li.appendChild(rm);
            fileList.appendChild(li);
        });
    }

    function addFiles(files) {
        if (!files) return;
        for (let file of files) {
            if (selectedFiles.length >= 5) {
                alert("Maksimal 5 file!");
                break;
            }
            selectedFiles.push(file);
        }
        updateFileInput();
        renderFileList();
    }

    function updateFileInput() {
        const dt = new DataTransfer();
        selectedFiles.forEach(f => dt.items.add(f));
        fileInput.files = dt.files;
    }

    function removeFile(index) {
        selectedFiles.splice(index, 1);
        updateFileInput();
        renderFileList();
    }

    if (dropZone && fileInput) {
        dropZone.addEventListener('click', () => fileInput.click());
        fileInput.addEventListener('change', e => addFiles(e.target.files));
    }

    (function () {
    const input = document.getElementById("biaya");
    const form  = document.querySelector("form");

    if (!input) return;

    function formatRupiah(numStr) {
        return numStr.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    input.addEventListener("input", function () {
        let digits = this.value.replace(/\D/g, "");

        if (digits === "") {
        this.value = "";
        return;
        }

        this.value = formatRupiah(digits);

        // always keep cursor at the end (most user-friendly)
        this.setSelectionRange(this.value.length, this.value.length);
    });

    if (form) {
        form.addEventListener("submit", function () {
        input.value = input.value.replace(/\D/g, "");
        });
    }
    });
});