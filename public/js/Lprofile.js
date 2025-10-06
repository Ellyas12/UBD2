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