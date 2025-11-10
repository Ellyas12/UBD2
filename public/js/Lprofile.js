// public/js/profile.js
document.addEventListener('DOMContentLoaded', function () {
  const tabs = Array.from(document.querySelectorAll('[role="tab"]'));
  const panels = Array.from(document.querySelectorAll('[role="tabpanel"]'));

  function activateTab(tab) {
    // deactivate all
    tabs.forEach(t => t.setAttribute('aria-selected', 'false'));
    panels.forEach(p => p.hidden = true);

    // activate target
    tab.setAttribute('aria-selected', 'true');
    const targetPanel = document.getElementById(tab.getAttribute('aria-controls'));
    if (targetPanel) targetPanel.hidden = false;
  }

  // clicking a tab -> activate + update URL hash (replaceState to avoid extra history entries)
  tabs.forEach(tab => {
    tab.addEventListener('click', () => {
      activateTab(tab);
      try {
        history.replaceState(null, null, '#' + tab.getAttribute('aria-controls'));
      } catch (e) {
        // fallback if history API blocked
        window.location.hash = tab.getAttribute('aria-controls');
      }
    });
  });

  // Try to activate a tab from the URL hash. Returns true if activation happened.
  function activateTabFromHash() {
    const hash = window.location.hash;
    if (!hash) return false;
    const name = hash.substring(1);
    const tabButton = document.querySelector(`[role="tab"][aria-controls="${name}"]`);
    const tabPanel = document.getElementById(name);
    if (tabButton && tabPanel) {
      activateTab(tabButton);
      return true;
    }
    return false;
  }

  // Utility: is an element visible (handles inline display:none or hidden attr)
  function isVisible(el) {
    if (!el) return false;
    if (el.hasAttribute('hidden')) return false;
    const cs = window.getComputedStyle(el);
    return cs.display !== 'none' && cs.visibility !== 'hidden' && cs.opacity !== '0';
  }

  // On load: prefer hash. If no hash, check server-rendered forms (verify/update) and activate Security tab if needed.
  const activatedByHash = activateTabFromHash();
  if (!activatedByHash) {
    const verifyForm = document.getElementById('verify-form');     // step 2
    const updateForm = document.getElementById('security-form');   // step 3

    if (isVisible(verifyForm) || isVisible(updateForm)) {
      const secTab = document.querySelector('[role="tab"][aria-controls="security-info"]');
      if (secTab) {
        activateTab(secTab);
        // set hash so refresh keeps the same tab
        try { history.replaceState(null, null, '#security-info'); } catch (e) {}
      }
    } else {
      // fallback: activate personal tab (first/default)
      const defaultTab = document.querySelector('[role="tab"][aria-controls="personal-info"]');
      if (defaultTab) activateTab(defaultTab);
    }
  }

  // keep responding to hash changes (back/forward)
  window.addEventListener('hashchange', activateTabFromHash);

  // Back button in verify form: hide verify form and show send-code, keep the Security tab
  const backBtn = document.getElementById('back-to-send');
  if (backBtn) {
    backBtn.addEventListener('click', () => {
      const verify = document.getElementById('verify-form');
      const send = document.getElementById('send-code-form');
      if (verify) verify.style.display = 'none';
      if (send) send.style.display = 'block';
      try { history.replaceState(null, null, '#security-info'); } catch (e) {}
    });
  }
});

document.addEventListener('DOMContentLoaded', function() {
  const addMatkulBtn = document.getElementById('add-mata-kuliah');
  const backBtn = document.getElementById('back-mk');
  const formView = document.getElementById('mata-kuliah-form');
  const tableView = document.getElementById('academic-table-view');
  const selectedBody = document.getElementById('selected-mk-body');
  const availableBody = document.getElementById('available-mk-body');
  const idsInput = document.getElementById('matkul-ids-input');

  let selectedMK = [];

  addMatkulBtn.addEventListener('click', () => {
    tableView.hidden = true;
    formView.hidden = false;
  });

  backBtn.addEventListener('click', () => {
    formView.hidden = true;
    tableView.hidden = false;
  });

  availableBody.addEventListener('click', function(e) {
    if (e.target.classList.contains('add-mk')) {
      const row = e.target.closest('tr');
      const id = row.dataset.id;
      const kode = row.dataset.kode;
      const nama = row.dataset.nama;

      if (!selectedMK.includes(id)) {
        selectedMK.push(id);

        const newRow = document.createElement('tr');
        newRow.dataset.id = id;
        newRow.innerHTML = `
          <td>${kode}</td>
          <td>${nama}</td>
          <td><button type="button" class="btn btn-danger btn-sm remove-mk">Hapus</button></td>
        `;
        selectedBody.appendChild(newRow);
        row.remove();
        updateHiddenInput();
      }
    }
  });

  selectedBody.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-mk')) {
      const row = e.target.closest('tr');
      const id = row.dataset.id;
      const kode = row.querySelector('td:nth-child(1)').textContent;
      const nama = row.querySelector('td:nth-child(2)').textContent;

      selectedMK = selectedMK.filter(m => m !== id);
      row.remove();

      // restore to available
      const restoredRow = document.createElement('tr');
      restoredRow.dataset.id = id;
      restoredRow.dataset.kode = kode;
      restoredRow.dataset.nama = nama;
      restoredRow.innerHTML = `
        <td>${kode}</td>
        <td>${nama}</td>
        <td><button type="button" class="btn btn-success btn-sm add-mk">Tambah</button></td>
      `;
      availableBody.appendChild(restoredRow);

      updateHiddenInput();
    }
  });

  function updateHiddenInput() {
    idsInput.value = selectedMK.join(',');
  }

  document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('submit-selected-form');
  if (!form) return;

  form.addEventListener('submit', () => {
    // Show a quick "processing" feedback
    const submitBtn = form.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.innerText = 'Menyimpan...';
    });
  });
});

document.addEventListener('DOMContentLoaded', function() {
    const addMkBtn = document.getElementById('add-mata-kuliah');
    const addPrestasiBtn = document.getElementById('add-prestasi');
    const backMkBtn = document.getElementById('back-mk');
    const backPrestasiBtn = document.getElementById('back-prestasi');
    const mkForm = document.getElementById('mata-kuliah-form');
    const prestasiForm = document.getElementById('prestasi-form');
    const academicTableView = document.getElementById('academic-table-view');
    const bottomButtons = document.getElementById('academic-buttons');

    // === Mata Kuliah View Switch ===
    addMkBtn?.addEventListener('click', () => {
        academicTableView.hidden = true;
        mkForm.hidden = false;
        prestasiForm.hidden = true;
        bottomButtons.hidden = true;
    });

    backMkBtn?.addEventListener('click', () => {
        mkForm.hidden = true;
        academicTableView.hidden = false;
        bottomButtons.hidden = false;
    });

    // === Prestasi View Switch ===
    addPrestasiBtn?.addEventListener('click', () => {
        academicTableView.hidden = true;
        prestasiForm.hidden = false;
        mkForm.hidden = true;
        bottomButtons.hidden = true;
    });

    backPrestasiBtn?.addEventListener('click', () => {
        prestasiForm.hidden = true;
        academicTableView.hidden = false;
        bottomButtons.hidden = false;
    });
});