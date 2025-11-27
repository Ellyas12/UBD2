document.addEventListener("DOMContentLoaded", () => {

    // Read JSON
    const programData = JSON.parse(
        document.getElementById("program-data").textContent
    );

    const btnAnalytics = document.getElementById("btn-analytics");
    const btnBack = document.getElementById("btn-back");
    const tableView = document.getElementById("table-view");
    const analyticsView = document.getElementById("analytics-view");

    // Show analytics
    btnAnalytics.addEventListener("click", () => {
        tableView.style.display = "none";
        analyticsView.style.display = "block";

        drawChart(programData);
    });

    // Back to table
    btnBack.addEventListener("click", () => {
        analyticsView.style.display = "none";
        tableView.style.display = "block";
    });
});

function drawChart(programData) {
    const filtered = programData.filter(
        p => p.status === "Accepted" && p.stamp === "Done"
    );

    const grouped = {};
    filtered.forEach(p => {
        const year = new Date(p.tanggal).getFullYear();
        if (!grouped[year]) grouped[year] = { PKM: 0, Penelitian: 0 };

        if (p.jenis.toLowerCase() === "pkm") grouped[year].PKM++;
        if (p.jenis.toLowerCase() === "penelitian") grouped[year].Penelitian++;
    });

    const years = Object.keys(grouped).sort();
    const pkmCounts = years.map(y => grouped[y].PKM);
    const penelitianCounts = years.map(y => grouped[y].Penelitian);

    const ctx = document.getElementById("programChart").getContext("2d");

    if (window.programChartInstance) {
        window.programChartInstance.destroy();
    }

    window.programChartInstance = new Chart(ctx, {
        type: "line",
        data: {
            labels: years,
            datasets: [
                {
                    label: "PKM",
                    data: pkmCounts,
                    borderColor: "red",
                    borderWidth: 2,
                    fill: false
                },
                {
                    label: "Penelitian",
                    data: penelitianCounts,
                    borderColor: "blue",
                    borderWidth: 2,
                    fill: false
                }
            ]
        }
    });
}
