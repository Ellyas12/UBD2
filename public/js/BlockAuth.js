document.addEventListener('DOMContentLoaded', () => {
  const preventDoubleClick = (selector) => {
    document.querySelectorAll(selector).forEach(btn => {
      btn.addEventListener('click', function(e) {
        if (this.dataset.clicked === "true") {
          e.preventDefault();
          return;
        }

        this.dataset.clicked = "true";

        // For logout: wait a bit so the POST actually fires
        if (this.classList.contains('logout')) {
          setTimeout(() => {
            this.disabled = true;
            this.innerHTML = '<span class="material-icons">logout</span> Logging out...';
            this.style.opacity = "0.6";
          }, 150);
        } else {
          this.disabled = true;
          this.innerText = 'Processing...';
          this.style.opacity = "0.6";
        }
      });
    });
  };

  preventDoubleClick('.logout');
});