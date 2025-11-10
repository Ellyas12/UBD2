document.addEventListener('DOMContentLoaded', () => {
  const preventDoubleClick = (selector) => {
    document.querySelectorAll(selector).forEach(btn => {
      btn.addEventListener('click', function(e) {
        if (this.dataset.clicked === "true") {
          e.preventDefault();
          return;
        }

        this.dataset.clicked = "true";
        this.style.opacity = "0.6";

        if (this.classList.contains('logout')) {
          this.innerHTML = '<span class="material-icons">logout</span> Logging out...';
          setTimeout(() => { this.disabled = true; }, 100);
        } else {
          this.innerText = 'Processing...';
          setTimeout(() => { this.disabled = true; }, 100);
        }
      });
    });
  };

  preventDoubleClick('button');
});
