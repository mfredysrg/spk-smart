<?php
include "header.php";
?>
<script src="js/Chart.js"></script>
<canvas id="myChart" style="width:100%;height:400px;"></canvas>
<script>
var ctx = document.getElementById("myChart").getContext("2d");
var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: [
            <?php
            $stmt2x = $db->prepare("select * from smart_alternatif");
            $stmt2x->execute();
            while($row2x = $stmt2x->fetch()){
            ?>
            "<?php echo $row2x['nama_alternatif'] ?>",
            <?php
            }
            ?>
        ],
        datasets: [{
            label: '# of Votes',
            data: [
            <?php
            $stmt2y = $db->prepare("select * from smart_alternatif");
            $stmt2y->execute();
            while($row2y = $stmt2y->fetch()){
                echo $row2y['hasil_alternatif'].',';
            }
            ?>
            ],
            backgroundColor: [
                'rgba(255, 99, 132, 0.8)',
                'rgba(54, 162, 235, 0.8)',
                'rgba(255, 206, 86, 0.8)',
                'rgba(75, 192, 192, 0.8)',
                'rgba(153, 102, 255, 0.8)',
                'rgba(245, 159, 64, 0.8)',
                'rgba(223, 99, 132, 0.8)',
                'rgba(45, 162, 235, 0.8)',
                'rgba(211, 206, 86, 0.8)',
                'rgba(89, 192, 192, 0.8)',
                'rgba(233, 99, 132, 0.8)',
                'rgba(67, 167, 235, 0.8)',
                'rgba(20, 26, 86, 0.8)',
                'rgba(67, 42, 12, 0.8)'
            ],
            borderColor: [
                'rgba(255,99,132,1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)',
                'rgba(255,99,132,1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)',
                'rgba(255,99,132,1)',
                'rgba(54, 162, 235, 1)',
            ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero:true
                }
            }]
        },
        title: {
            display: true,
            text: 'Hasil Akhir Perangkingan'
        }
    }
});
</script>
<?php
include "footer.php";
?>
					
					