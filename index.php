<?php
include "header.php";
?>
<script src="js/Chart.js"></script>

<!-- Tombol untuk ubah jenis grafik -->
<button onclick="toggleChartType()" style="margin: 10px 0; padding: 8px 16px;">Ubah Grafik Bar/Garis</button>

<canvas id="myChart" style="width:100%; height:400px;"></canvas>

<script>
var ctx = document.getElementById("myChart").getContext("2d");

// Ambil labels dari PHP
var labels = [
    <?php
    $stmt2x = $db->prepare("SELECT * FROM smart_alternatif");
    $stmt2x->execute();
    while($row2x = $stmt2x->fetch()){
        echo '"' . $row2x['nama_alternatif'] . '",';
    }
    ?>
];

// Ambil data hasil dari PHP
var dataHasil = [
    <?php
    $stmt2y = $db->prepare("SELECT * FROM smart_alternatif");
    $stmt2y->execute();
    while($row2y = $stmt2y->fetch()){
        echo $row2y['hasil_alternatif'] . ',';
    }
    ?>
];

// Konfigurasi awal chart
var chartType = 'bar';
var myChart = new Chart(ctx, {
    type: chartType,
    data: {
        labels: labels,
        datasets: [{
            label: 'Hasil Alternatif',
            data: dataHasil,
            backgroundColor: 'rgba(54, 162, 235, 0.6)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 2,
            fill: false
        }]
    },
    options: {
        responsive: true,
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }]
        },
        title: {
            display: true,
            text: 'Hasil Akhir Perangkingan'
        }
    }
});

// Fungsi untuk toggle chart
function toggleChartType() {
    // Hancurkan chart lama
    myChart.destroy();

    // Ganti tipe chart
    chartType = (chartType === 'bar') ? 'line' : 'bar';

    // Buat chart baru
    myChart = new Chart(ctx, {
        type: chartType,
        data: {
            labels: labels,
            datasets: [{
                label: 'Hasil Alternatif',
                data: dataHasil,
                backgroundColor: (chartType === 'bar') ? 'rgba(54, 162, 235, 0.6)' : 'rgba(0,0,0,0)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 2,
                fill: false
            }]
        },
        options: {
            responsive: true,
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            },
            title: {
                display: true,
                text: 'Hasil Akhir Perangkingan'
            }
        }
    });
}
</script>

<?php
include "footer.php";
?>
