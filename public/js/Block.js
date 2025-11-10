document.addEventListener('DOMContentLoaded', () => {
  const protectedClasses = [
    'btn-danger',
    'btn',
    'btn-success',
    'btn-secondary',
    'btn-success mt-3'
  ];

  const selector = protectedClasses
    .map(cls => '.' + cls.replace(/\s+/g, '.'))
    .join(',');

  document.querySelectorAll(selector).forEach(button => {
    button.addEventListener('click', function (e) {
      if (this.closest('form')) return;

      if (this.disabled) {
        e.preventDefault();
        return;
      }

      const originalText = this.innerText;
      this.disabled = true;

      if (this.classList.contains('btn-danger')) {
        this.innerText = 'Processing...';
      } else if (this.classList.contains('btn-logout')) {
        this.innerText = 'Logging out...';
      } else {
        this.innerText = 'Please wait...';
      }

      setTimeout(() => {
        if (document.visibilityState === 'visible') {
          this.disabled = false;
          this.innerText = originalText;
        }
      }, 5000);
    });
  });

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
