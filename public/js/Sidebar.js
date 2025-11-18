document.addEventListener("DOMContentLoaded", function () {
    const sidebar = document.getElementById("sidebar");
    const toggleBtn = document.getElementById("sidebarToggle");

    const savedState = localStorage.getItem("sidebar-collapsed");
    if (savedState === "true") {
        sidebar.classList.add("collapsed");
    }

    toggleBtn.addEventListener("click", function () {
        sidebar.classList.toggle("collapsed");

        localStorage.setItem(
            "sidebar-collapsed",
            sidebar.classList.contains("collapsed")
        );
    });
});