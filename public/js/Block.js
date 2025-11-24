document.addEventListener('DOMContentLoaded', () => {
  const buttonBehaviors = {
    'sidebar-logout-btn': 'Logging out...',
    'btn-submit' : 'Submitting...',
    'btn-red' : 'Deleting...'
  };

  document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', function (e) {

      const submitBtn = this.querySelector('button[type="submit"], input[type="submit"]');
      if (!submitBtn) return;

      if (submitBtn.disabled) {
        e.preventDefault();
        return;
      }

      const originalText = submitBtn.innerText || submitBtn.value;
      submitBtn.dataset.originalText = originalText;
      submitBtn.disabled = true;

      let applied = false;
      Object.keys(buttonBehaviors).forEach(className => {
        if (submitBtn.classList.contains(className)) {
          const msg = buttonBehaviors[className];
          if (submitBtn.tagName === 'BUTTON') {
            submitBtn.innerText = msg;
          } else {
            submitBtn.value = msg;
          }
          applied = true;
        }
      });

      if (!applied) {
        if (submitBtn.tagName === 'BUTTON') {
          submitBtn.innerText = 'Processing...';
        } else {
          submitBtn.value = 'Processing...';
        }
      }

      setTimeout(() => {
        if (document.visibilityState === 'visible') {
          submitBtn.disabled = false;
          const orig = submitBtn.dataset.originalText;
          if (submitBtn.tagName === 'BUTTON') {
            submitBtn.innerText = orig;
          } else {
            submitBtn.value = orig;
          }
        }
      }, 5000);
    });
  });
});