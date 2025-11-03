document.addEventListener('DOMContentLoaded', () => {
  const protectedClasses = [
    'btn-danger',
    'btn',
    'btn-success',
    'btn-secondary',
    'btn-success mt-3'
  ];

  // Build selector for all protected classes
  const selector = protectedClasses
    .map(cls => '.' + cls.replace(/\s+/g, '.')) // handle multi-class like "btn btn-success"
    .join(',');

  // ===== Protect all matching buttons =====
  document.querySelectorAll(selector).forEach(button => {
    button.addEventListener('click', function (e) {
      // If this is inside a form, let the form listener handle it
      if (this.closest('form')) return;

      if (this.disabled) {
        e.preventDefault();
        return;
      }

      const originalText = this.innerText;
      this.disabled = true;

      // Change text for feedback
      if (this.classList.contains('btn-danger')) {
        this.innerText = 'Processing...';
      } else if (this.classList.contains('btn-logout')) {
        this.innerText = 'Logging out...';
      } else {
        this.innerText = 'Please wait...';
      }

      // Re-enable after 5 seconds if no redirect happened
      setTimeout(() => {
        if (document.visibilityState === 'visible') {
          this.disabled = false;
          this.innerText = originalText;
        }
      }, 5000);
    });
  });

  // ===== Protect all form submissions =====
  document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', function (e) {
      const submitBtn = this.querySelector(
        'button[type="submit"], input[type="submit"]'
      );
      if (!submitBtn) return;

      if (submitBtn.disabled) {
        e.preventDefault();
        return;
      }

      const originalText = submitBtn.innerText || submitBtn.value;
      submitBtn.disabled = true;

      if (submitBtn.tagName === 'BUTTON') {
        submitBtn.innerText = 'Processing...';
      } else {
        submitBtn.value = 'Processing...';
      }

      // Fallback: re-enable if validation fails and no redirect happens
      setTimeout(() => {
        if (document.visibilityState === 'visible') {
          submitBtn.disabled = false;
          if (submitBtn.tagName === 'BUTTON') {
            submitBtn.innerText = originalText;
          } else {
            submitBtn.value = originalText;
          }
        }
      }, 5000);
    });
  });
});
