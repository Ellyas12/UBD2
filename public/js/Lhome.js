document.addEventListener("DOMContentLoaded", () => {
  const searchBtn = document.getElementById("search-btn");
  const searchInput = document.getElementById("search-input");
  const searchResults = document.getElementById("search-results");
  const resultsList = document.getElementById("search-results-list");
  const noResults = document.getElementById("no-results");

  // Grab all program cards
  const programs = Array.from(document.querySelectorAll(".program-card"));

  // Highlight matched text
  function highlightText(element, query) {
    if (!query) return element.innerHTML;
    const regex = new RegExp(`(${query})`, "gi");
    return element.innerHTML.replace(regex, `<span class="highlight">$1</span>`);
  }

  // Search function
  function performSearch() {
    const query = searchInput.value.trim().toLowerCase();
    resultsList.innerHTML = "";
    noResults.classList.add("hidden");

    if (!query) {
      searchResults.classList.add("hidden");
      return;
    }

    const matches = programs.filter(card => {
      const text = card.innerText.toLowerCase();
      return text.includes(query);
    });

    searchResults.classList.remove("hidden");

    if (matches.length === 0) {
      noResults.classList.remove("hidden");
      return;
    }

    matches.forEach(card => {
      const clone = card.cloneNode(true);
      clone.innerHTML = highlightText(clone, query);
      resultsList.appendChild(clone);
    });
  }

  // Trigger search on button click or Enter key
  searchBtn.addEventListener("click", performSearch);
  searchInput.addEventListener("keydown", e => {
    if (e.key === "Enter") performSearch();
  });
});

document.addEventListener("DOMContentLoaded", () => {
  const fakultasSelect = document.getElementById("filter-fakultas");
  const jenisSelect = document.getElementById("filter-jenis");
  const sortSelect = document.getElementById("sort-option");
  const applyBtn = document.getElementById("apply-sort");

  const programsContainer = document.getElementById("program-list");
  const paginationControls = document.getElementById("pagination-controls");
  const viewMoreBtn = document.getElementById("view-more");
  const pageButtonsContainer = document.getElementById("page-buttons");

  const allPrograms = Array.from(programsContainer.querySelectorAll(".program-card"));

  let currentPage = 1;
  let programsPerPage = 6; // initial
  const expandPageLimit = 10; // expanded after View More

  function getProgramData(card) {
    const text = card.innerText.toLowerCase();
    return {
      fakultasId: (card.dataset.fakultasId || "").trim(),
      jenis: (text.match(/jenis:\s*(.+)/i) || [])[1]?.trim() || "",
      tanggal: (text.match(/tanggal:\s*(.+)/i) || [])[1]?.trim() || ""
    };
  }

  function applyFiltersAndSort() {
    const fakultasVal = fakultasSelect.value.trim();
    const jenisVal = jenisSelect.value.toLowerCase();
    const sortVal = sortSelect.value;

    // Filter
    let filtered = allPrograms.filter(card => {
      const data = getProgramData(card);
      const fakultasMatch =
        !fakultasVal || String(data.fakultasId) === String(fakultasVal);
      const jenisMatch = !jenisVal || data.jenis.includes(jenisVal);
      return fakultasMatch && jenisMatch;
    });

    // Sort
    filtered.sort((a, b) => {
      const dateA = new Date(getProgramData(a).tanggal);
      const dateB = new Date(getProgramData(b).tanggal);
      return sortVal === "newest" ? dateB - dateA : dateA - dateB;
    });

    renderPrograms(filtered);
  }

  function renderPrograms(programList) {
    programsContainer.innerHTML = "";

    const totalPrograms = programList.length;
    const totalPages = Math.ceil(totalPrograms / programsPerPage);

    if (totalPrograms === 0) {
      paginationControls.classList.add("hidden");
      programsContainer.innerHTML = "<p>Tidak ada program yang ditemukan.</p>";
      return;
    }

    paginationControls.classList.remove("hidden");

    // Slice programs for current page
    const start = (currentPage - 1) * programsPerPage;
    const end = start + programsPerPage;
    const currentPrograms = programList.slice(start, end);

    currentPrograms.forEach(card => programsContainer.appendChild(card));

    renderPaginationButtons(totalPages, programList);
  }

  function renderPaginationButtons(totalPages, programList) {
    pageButtonsContainer.innerHTML = "";

    for (let i = 1; i <= totalPages; i++) {
      const btn = document.createElement("button");
      btn.textContent = i;
      btn.classList.add("page-btn");
      if (i === currentPage) btn.classList.add("active");

      btn.addEventListener("click", () => {
        currentPage = i;
        renderPrograms(programList);
      });

      pageButtonsContainer.appendChild(btn);
    }
  }

  // View More button
  viewMoreBtn.addEventListener("click", () => {
    programsPerPage = expandPageLimit;
    currentPage = 1;
    applyFiltersAndSort();
  });

  // Sort/filter apply
  applyBtn.addEventListener("click", () => {
    programsPerPage = 6; // reset to initial
    currentPage = 1;
    applyFiltersAndSort();
  });

  // Initial load (newest by default)
  applyFiltersAndSort();
});


document.addEventListener("DOMContentLoaded", () => {
  const modal = document.getElementById("lecturer-modal");
  const modalPhoto = document.getElementById("modal-photo");
  const modalName = document.getElementById("modal-name");
  const modalFakultas = document.getElementById("modal-fakultas");
  const modalEmail = document.getElementById("modal-email");
  const modalBio = document.getElementById("modal-bio");
  const modalProfileLink = document.getElementById("modal-profile-link");
  const modalClose = document.getElementById("modal-close");

  // Open modal
  document.querySelectorAll(".profile-click").forEach(img => {
    img.addEventListener("click", () => {
      modalPhoto.src = img.dataset.picture;
      modalName.textContent = img.dataset.name;
      modalFakultas.textContent = img.dataset.fakultas;
      modalEmail.textContent = img.dataset.email;
      modalBio.textContent = img.dataset.bio;
      modalProfileLink.href = img.dataset.profileUrl;

      modal.classList.remove("hidden");
      setTimeout(() => modal.classList.add("visible"), 10);
    });
  });

  // Close modal
  modalClose.addEventListener("click", closeModal);
  modal.addEventListener("click", e => {
    if (e.target === modal) closeModal();
  });

  function closeModal() {
    modal.classList.remove("visible");
    setTimeout(() => modal.classList.add("hidden"), 300);
  }
});