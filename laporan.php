<?php
include "config.php";
session_start();
if(!isset($_SESSION['username'])){
    ?>
    <script>window.location.assign("login.php")</script>
    <?php
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>SPK Metode SMART</title>
    <link href="css/metro.css" rel="stylesheet">
    <link href="css/metro-icons.css" rel="stylesheet">
    <link href="css/metro-schemes.css" rel="stylesheet">
    <link href="css/metro-responsive.css" rel="stylesheet">
</head>
<body>

    <div class="container">
        <h2 style="text-align:center;">LAPORAN PENILAIAN PEGAWAI KEJAKSAAN NEGERI LHOKSEUMAWE</h2>
    <p><strong>Nilai Dasar</strong></p>
    <table class="table striped hovered cell-hovered border bordered">
    <thead>
        <tr>
            <th width="50">No</th>
            <th>Alternatif</th>
            <?php
            // Fetch all kriteria once for efficiency
            $stmt3_header = $db->prepare("select * from smart_kriteria");
            $stmt3_header->execute();
            $kriteria_all = $stmt3_header->fetchAll(PDO::FETCH_ASSOC);
            foreach($kriteria_all as $row3_header){
            ?>
            <th><?php echo $row3_header['nama_kriteria'] ?></th>
            <?php
            }
            ?>
        </tr>
    </thead>
    <tbody>
        <?php
        // Fetch all alternatif once for efficiency
        $stmt = $db->prepare("select * from smart_alternatif");
        $nox = 1;
        $stmt->execute();
        $alternatif_all = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach($alternatif_all as $row){
        ?>
        <tr>
            <td><?php echo $nox++ ?></td>
            <td><?php echo $row['nama_alternatif'] ?></td>
            <?php
            foreach($kriteria_all as $row3){
            ?>
            <td>
                <?php
                $stmt4 = $db->prepare("select * from smart_alternatif_kriteria where id_kriteria='".$row3['id_kriteria']."' and id_alternatif='".$row['id_alternatif']."'");
                $stmt4->execute();
                while($row4 = $stmt4->fetch()){
                    echo $row4['nilai_alternatif_kriteria'];
                }
                ?>
            </td>
            <?php
            }
            ?>
        </tr>
        <?php
        }
        ?>
    </tbody>
    </table>
    <br/>

    <p><strong>Normalisasi Nilai Kriteria</strong></p>
    <table class="table striped hovered cell-hovered border bordered">
    <thead>
        <tr>
            <th width="50">No</th>
            <th>Alternatif</th>
            <?php
            foreach($kriteria_all as $row_kriteria_norm_header){
            ?>
            <th><?php echo $row_kriteria_norm_header['nama_kriteria'] ?></th>
            <?php
            }
            ?>
        </tr>
    </thead>
    <tbody>
        <?php
        // Fetch maximum values for each criterion for normalization
        $max_values = [];
        foreach($kriteria_all as $row_kriteria_norm){
            $stmt_max_val = $db->prepare("SELECT MAX(nilai_alternatif_kriteria) AS max_val FROM smart_alternatif_kriteria WHERE id_kriteria = '".$row_kriteria_norm['id_kriteria']."'");
            $stmt_max_val->execute();
            $max_row = $stmt_max_val->fetch();
            $max_values[$row_kriteria_norm['id_kriteria']] = $max_row['max_val'];
        }

        $normalized_criterion_values = []; // Store normalized values for later use in ranking calculation
        $nox_norm = 1;
        foreach($alternatif_all as $row_alternatif_norm){
            echo "<tr>";
            echo "<td>".$nox_norm++."</td>";
            echo "<td>".$row_alternatif_norm['nama_alternatif']."</td>";
            foreach($kriteria_all as $row_kriteria_norm){
                echo "<td>";
                $stmt_nilai_ak = $db->prepare("select * from smart_alternatif_kriteria where id_kriteria='".$row_kriteria_norm['id_kriteria']."' and id_alternatif='".$row_alternatif_norm['id_alternatif']."'");
                $stmt_nilai_ak->execute();
                while($row_nilai_ak = $stmt_nilai_ak->fetch()){
                    $nilai_dasar = $row_nilai_ak['nilai_alternatif_kriteria'];
                    $idk_norm = $row_kriteria_norm['id_kriteria'];
                    $normalized_value = ($max_values[$idk_norm] != 0) ? $nilai_dasar / $max_values[$idk_norm] : 0;
                    echo number_format($normalized_value, 4); // Round to 4 decimal places for display
                    $normalized_criterion_values[$row_alternatif_norm['id_alternatif']][$idk_norm] = $normalized_value;
                }
                echo "</td>";
            }
            echo "</tr>";
        }
        ?>
    </tbody>
    </table>
    <br/>

    <p><strong>Normalisasi Bobot Kriteria</strong></p>
    <table class="table striped hovered cell-hovered border bordered">
        <thead>
            <tr>
                <th width="50">No</th>
                <th>Kriteria</th>
                <th>Bobot Asli</th>
                <th>Bobot Ternormalisasi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sum_bobot = 0;
            foreach($kriteria_all as $kriteria_item){
                $sum_bobot += $kriteria_item['bobot_kriteria'];
            }

            $normalized_bobot_kriteria = []; // Store normalized weights for later use
            $nox_bobot = 1;
            foreach($kriteria_all as $kriteria_item){
                $normalized_bobot = ($sum_bobot != 0) ? $kriteria_item['bobot_kriteria'] / $sum_bobot : 0;
                $normalized_bobot_kriteria[$kriteria_item['id_kriteria']] = $normalized_bobot;
                ?>
                <tr>
                    <td><?php echo $nox_bobot++ ?></td>
                    <td><?php echo $kriteria_item['nama_kriteria'] ?></td>
                    <td><?php echo $kriteria_item['bobot_kriteria'] ?></td>
                    <td><?php echo number_format($normalized_bobot, 4) ?></td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
    <br/>

    <p><strong>Perhitungan Akhir (Nilai Perangkingan)</strong></p>
    <table class="table striped hovered cell-hovered border bordered">
    <thead>
        <tr>
            <th width="50">No</th>
            <th>Alternatif</th>
            <?php
            foreach($kriteria_all as $row2x_header){
            ?>
            <th><?php echo $row2x_header['nama_kriteria'] ?> (N x W)</th> <?php
            }
            ?>
            <th>Hasil Akhir</th>
            <th>Keterangan</th>
            </tr>
    </thead>
    <tbody>
        <tr>
            <td>-</td>
            <td>Bobot Ternormalisasi</td>
            <?php
            foreach($kriteria_all as $row2x1){
            ?>
            <td><?php echo number_format($normalized_bobot_kriteria[$row2x1['id_kriteria']], 4) ?></td>
            <?php
            }
            ?>
            <td>-</td>
            <td>-</td>
        </tr>
        <?php
        $noxx = 1;
        foreach($alternatif_all as $rowx){
        ?>
        <tr>
            <td><?php echo $noxx++ ?></td>
            <td><?php echo $rowx['nama_alternatif'] ?></td>
            <?php
            $total_score_alternatif = 0;
            foreach($kriteria_all as $row3x){
            ?>
            <td>
                <?php
                $id_alternatif_curr = $rowx['id_alternatif'];
                $id_kriteria_curr = $row3x['id_kriteria'];

                $normalized_val_for_calc = $normalized_criterion_values[$id_alternatif_curr][$id_kriteria_curr];
                $normalized_weight_for_calc = $normalized_bobot_kriteria[$id_kriteria_curr];

                $kal = $normalized_val_for_calc * $normalized_weight_for_calc;
                echo number_format($kal, 4); // Display the product of normalized value and normalized weight

                // Update the database with the new calculated value (if needed for persistence, otherwise just for display)
                $stmt2x3 = $db->prepare("update smart_alternatif_kriteria set bobot_alternatif_kriteria=? where id_alternatif=? and id_kriteria=?");
                $stmt2x3->bindParam(1,$kal);
                $stmt2x3->bindParam(2,$id_alternatif_curr);
                $stmt2x3->bindParam(3,$id_kriteria_curr);
                $stmt2x3->execute();

                $total_score_alternatif += $kal;
                ?>
            </td>
            <?php
            }
            ?>
            <td>
                <?php
                echo $hsl = number_format($total_score_alternatif, 4); // Display the final sum for the alternative

                // Keterangan logic with adjusted thresholds for 0-1 scale
                if($hsl>=0.80){ // Was 80
                    $ket = "Sangat Layak";
                } else if($hsl>=0.60){ // Was 60
                    $ket = "Layak";
                } else if($hsl>=0.40){ // Was 40
                    $ket = "Dipertimbangkan";
                } else{
                    $ket = "Tidak Layak";
                }
                ?>
            </td>
            <td>
                <?php
                // Keterangan with potentially different thresholds based on original code, also adjusted
                if($hsl>=0.80){ // Was 80
                    $ket2 = "Sangat Layak";
                } else if($hsl>=0.55){ // Was 55
                    $ket2 = "Layak";
                } else if($hsl>=0.35){ // Was 35
                    $ket2 = "Dipertimbangkan";
                } else{
                    $ket2 = "Tidak Layak";
                }
                echo $ket2;
                ?>
            </td>
        </tr>
        <?php
        }
        ?>
    </tbody>
    </table>
    <p><br/></p>

    <div style="text-align: center; margin-top: 20px;">
        <a href="index.php" class="button primary">Kembali ke Beranda</a>
    </div>

    </div>
    <script src="js/jquery.js"></script>
    <script src="js/metro.js"></script>
</body>
</html>