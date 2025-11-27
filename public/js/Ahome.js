document.addEventListener("DOMContentLoaded", () => {

    const searchBtn = document.getElementById("search-btn");
    const searchInput = document.getElementById("search-input");
    const searchResults = document.getElementById("search-results");
    const resultsList = document.getElementById("search-results-list");
    const noResults = document.getElementById("no-results");

    const programs = Array.from(document.querySelectorAll(".program-card"));

    // Highlight function - safe for text only
    function highlight(text, query) {
        if (!query) return text;
        const regex = new RegExp(`(${query})`, "gi");
        return text.replace(regex, `<span class="highlight">$1</span>`);
    }

    // Apply highlight only to specific fields, not the entire card
    function applyHighlight(clone, query) {
        const fields = clone.querySelectorAll("h3, p, .submitted-by, strong");

        fields.forEach(field => {
            field.innerHTML = highlight(field.innerHTML, query);
        });

        return clone;
    }

    function performSearch() {
        const query = searchInput.value.trim().toLowerCase();

        resultsList.innerHTML = "";
        noResults.classList.add("hidden");

        if (!query) {
            searchResults.classList.add("hidden");
            return;
        }

        // Filter only accepted programs
        const matches = programs.filter(card => {
            const status = card.querySelector(".program-status")?.innerText.trim().toLowerCase();
            const text = card.innerText.toLowerCase();

            if (status !== "accepted") return false;
            return text.includes(query);
        });

        searchResults.classList.remove("hidden");

        if (matches.length === 0) {
            noResults.classList.remove("hidden");
            return;
        }

        matches.forEach(card => {
            const clone = card.cloneNode(true);
            applyHighlight(clone, query);
            resultsList.appendChild(clone);
        });
    }

    searchBtn.addEventListener("click", performSearch);
    searchInput.addEventListener("keydown", e => {
        if (e.key === "Enter") performSearch();
    });

});

document.addEventListener("DOMContentLoaded", () => {
  const modal = document.getElementById("lecturer-modal");
  const modalContent = document.querySelector(".profile-modal-content");

  const modalPhoto = document.getElementById("modal-photo");
  const modalName = document.getElementById("modal-name");
  const modalFakultas = document.getElementById("modal-fakultas");
  const modalEmail = document.getElementById("modal-email");
  const modalBio = document.getElementById("modal-bio");
  const modalProfileLink = document.getElementById("modal-profile-link");
  const modalClose = document.getElementById("modal-close");

  // Open modal when clicking avatar
  document.querySelectorAll(".profile-click").forEach(img => {
    img.addEventListener("click", () => {
      modalPhoto.src = img.dataset.picture;
      modalName.textContent = img.dataset.name;
      modalFakultas.textContent = img.dataset.fakultas;
      modalEmail.textContent = img.dataset.email;
      modalBio.textContent = img.dataset.bio;
      modalProfileLink.href = img.dataset.profileUrl;

      modal.classList.remove("hidden");

      // Allow CSS transition to activate
      requestAnimationFrame(() => {
        modal.classList.add("visible");
      });
    });
  });

  // Close when pressing X
  modalClose.addEventListener("click", () => closeModal());

  // Close when clicking outside the content
  modal.addEventListener("click", e => {
    if (!modalContent.contains(e.target)) closeModal();
  });

  function closeModal() {
    modal.classList.remove("visible");
    setTimeout(() => modal.classList.add("hidden"), 300); // match CSS transition time
  }
});

document.addEventListener("DOMContentLoaded", () => {
    const modal = document.getElementById("lecturer-modal");

    const modalPhoto = document.getElementById("modal-photo");
    const modalName = document.getElementById("modal-name");
    const modalFakultas = document.getElementById("modal-fakultas");

    const modalEmail = document.getElementById("modal-email");
    const modalNIDN = document.getElementById("modal-nidn");
    const modalPosisi = document.getElementById("modal-posisi");

    const modalJabatan = document.getElementById("modal-jabatan");
    const modalBidang = document.getElementById("modal-bidang");
    const modalTelp = document.getElementById("modal-telp");

    const modalProfileLink = document.getElementById("modal-profile-link");
    const modalClose = document.getElementById("modal-close");

    // Delegated click handler
    document.addEventListener("click", e => {
        const img = e.target.closest(".profile-click");
        if (!img) return;

        modalPhoto.src = img.dataset.picture;
        modalName.textContent = img.dataset.name;
        modalFakultas.textContent = img.dataset.fakultas;

        modalEmail.textContent = img.dataset.email;
        modalNIDN.textContent = img.dataset.nidn;
        modalPosisi.textContent = img.dataset.posisi;

        modalJabatan.textContent = img.dataset.jabatan;
        modalBidang.textContent = img.dataset.bidang;
        modalTelp.textContent = img.dataset.telp;

        modalProfileLink.href = img.dataset.profileUrl;

        modal.classList.add("visible");
    });

    modalClose.addEventListener("click", () => modal.classList.remove("visible"));

    modal.addEventListener("click", e => {
        if (e.target === modal) modal.classList.remove("visible");
    });
});



