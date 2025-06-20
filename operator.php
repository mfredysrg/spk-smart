<?php
include "header.php";
$page = isset($_GET['page'])?$_GET['page']:"";
?>
<div class="row cells4">
	<div class="cell colspan2">
		<h3>Operator</h3>
	</div>
<?php
if($page=='form'){
?>
	<div class="cell colspan2 align-right">
		<a href="operator.php" class="button info">Kembali</a>
	</div>
</div>
<tbody>
		<tr>
			<td>-</td>
			<td>Bobot</td>
            <?php
            $stmt2x1 = $db->prepare("select * from smart_kriteria");
            $stmt2x1->execute();
            while($row2x1 = $stmt2x1->fetch()){
            ?>
			<td><?php echo $row2x1['bobot_kriteria'] ?></td>
            <?php
            }
            ?>
            <td>-</td>
            <td>-</td>
		</tr>
		<?php
		$stmtx = $db->prepare("select * from smart_alternatif");
		$noxx = 1;
		$stmtx->execute();
		while($rowx = $stmtx->fetch()){
		?>
		<tr>
			<td><?php echo $noxx++ ?></td>
			<td><?php echo $rowx['nama_alternatif'] ?></td>
            <?php
            $stmt3x = $db->prepare("select * from smart_kriteria");
            $stmt3x->execute();
            while($row3x = $stmt3x->fetch()){
            ?>
			<td>
                <?php
                $stmt4x = $db->prepare("select * from smart_alternatif_kriteria where id_kriteria='".$row3x['id_kriteria']."' and id_alternatif='".$rowx['id_alternatif']."'");
                $stmt4x->execute();
                while($row4x = $stmt4x->fetch()){
                	$ida = $row4x['id_alternatif'];
                	$idk = $row4x['id_kriteria'];
                    echo $kal = $row4x['nilai_alternatif_kriteria']*$row3x['bobot_kriteria'];
                    $stmt2x3 = $db->prepare("update smart_alternatif_kriteria set bobot_alternatif_kriteria=? where id_alternatif=? and id_kriteria=?");
					$stmt2x3->bindParam(1,$kal);
					$stmt2x3->bindParam(2,$ida);
					$stmt2x3->bindParam(3,$idk);
					$stmt2x3->execute();
                }
                ?>
            </td>
            <?php
            }
            ?>
            <td>
            	<?php
            	$stmt3x2 = $db->prepare("select sum(bobot_alternatif_kriteria) as bak from smart_alternatif_kriteria where id_alternatif='".$rowx['id_alternatif']."'");
	            $stmt3x2->execute();
	            $row3x2 = $stmt3x2->fetch();
	            $ideas = $rowx['id_alternatif'];
	            echo $hsl = $row3x2['bak'];
	            if($hsl>=80){
	            	$ket = "Sangat Layak";
	            } else if($hsl>=60){
	            	$ket = "Layak";
	            } else if($hsl>=40){
	            	$ket = "Dipertimbangkan";
	            } else{
	            	$ket = "Tidak Layak";
	            }
            	?>
            </td>
            <td>
            	<?php
            	if($hsl>=80){
	            	$ket2 = "Sangat Layak";
	            } else if($hsl>=55){
	            	$ket2 = "Layak";
	            } else if($hsl>=35){
	            	$ket2 = "Dipertimbangkan";
	            } else{
	            	$ket2 = "Tidak Layak";
	            }
	            echo $ket2;
            	?>
            </td>
		</tr>
	<p></p>
	<?php
	if(isset($_POST['simpan'])){
		$nama = $_POST['nama'];
		$user = $_POST['user'];
		$pass = md5($_POST['pass']);
		$stmt2 = $db->prepare("insert into smart_admin values('',?,?,?)");
		$stmt2->bindParam(1,$nama);
		$stmt2->bindParam(2,$user);
		$stmt2->bindParam(3,$pass);
		if($stmt2->execute()){
			?>
				<script type="text/javascript">location.href='operator.php'</script>
			<?php
		} else{
			?>
				<script type="text/javascript">alert('Gagal menyimpan data')</script>
			<?php
		}
	}
	if(isset($_POST['update'])){
		$id = $_POST['id'];
		$nama = $_POST['nama'];
		$user = $_POST['user'];
		$pass = md5($_POST['pass']);
		$stmt2 = $db->prepare("update smart_admin set nama_admin=?, username=?, password=? where id_admin=?");
		$stmt2->bindParam(1,$nama);
		$stmt2->bindParam(2,$user);
		$stmt2->bindParam(3,$pass);
		$stmt2->bindParam(4,$id);
		if($stmt2->execute()){
			?>
				<script type="text/javascript">location.href='operator.php'</script>
			<?php
		} else{
			?>
				<script type="text/javascript">alert('Gagal mengubah data')</script>
			<?php
		}
	}
	?>
	<form method="post">
		<input type="hidden" name="id" value="<?php echo isset($_GET['id'])? $_GET['id'] : ''; ?>">
		<label>Nama Lengkap</label>
		<div class="input-control text full-size">
		    <input type="text" name="nama" placeholder="Nama Lengkap" value="<?php echo isset($_GET['nama'])? $_GET['nama'] : ''; ?>">
		</div>
		<label>Username</label>
		<div class="input-control text full-size">
		    <input type="text" name="user" placeholder="Nama Pengguna" value="<?php echo isset($_GET['username'])? $_GET['username'] : ''; ?>">
		</div>
		<label>Password</label>
		<div class="input-control text full-size">
		    <input type="password" name="pass" placeholder="Kata Sandi">
		</div>
		<?php
		if (isset($_GET['id'])) {
			?>
			<button type="submit" name="update" class="button warning">Update</button>
			<?php
		} else{
			?>
			<button type="submit" name="simpan" class="button primary">Simpan</button>
			<?php
		}
		?>
	</form>
<?php
} else if($page=='hapus'){
?>
	<div class="cell colspan2 align-right">
	</div>
</div>
<?php
	if(isset($_GET['id'])){
		$stmt = $db->prepare("delete from smart_admin where id_admin='".$_GET['id']."'");
	 	if($stmt->execute()){
	 		?>
	 		<script type="text/javascript">location.href='operator.php'</script>
	 		<?php
	 	}
	}
} else{
?>
	<div class="cell colspan2 align-right">
		<a href="?page=form" class="button primary">Tambah</a>
	</div>
</div>
<table class="table striped hovered cell-hovered border bordered dataTable" data-role="datatable" data-searching="true">
	<thead>
		<tr>
			<th width="50">ID</th>
			<th>Nama</th>
			<th>Username</th>
			<th width="240">Aksi</th>
		</tr>
	</thead>
	<tbody>
		<?php
		$stmt = $db->prepare("select * from smart_admin");
		$stmt->execute();
		while($row = $stmt->fetch()){
		?>
		<tr>
			<td><?php echo $row['id_admin'] ?></td>
			<td><?php echo $row['nama_admin'] ?></td>
			<td><?php echo $row['username'] ?></td>
			<td class="align-center">
				<a href="?page=form&id=<?php echo $row['id_admin'] ?>&nama=<?php echo $row['nama_admin'] ?>&username=<?php echo $row['username'] ?>" class="button warning"><span class="mif-pencil icon"></span> Edit</a>
				<a href="?page=hapus&id=<?php echo $row['id_admin'] ?>" class="button danger"><span class="mif-cancel icon"></span> Hapus</a>
			</td>
		</tr>
		<?php
		}
		?>
	</tbody>
</table>
<p><br/></p>
<?php
}
include "footer.php";
?>
					
					