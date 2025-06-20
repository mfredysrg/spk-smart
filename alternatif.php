<?php
include "header.php";
$page = isset($_GET['page'])?$_GET['page']:"";
?>
<div class="row cells4">
	<div class="cell colspan2">
		<h3>Alternatif</h3>
	</div>
<?php
if($page=='form'){
?>
	<div class="cell colspan2 align-right">
		<a href="alternatif.php" class="button info">Kembali</a>
	</div>
</div>
	<p></p>
	<?php
	if(isset($_POST['simpan'])){
		$nama = $_POST['nama'];
		$stmt2 = $db->prepare("insert into smart_alternatif(id_alternatif,nama_alternatif) values('',?)");
		$stmt2->bindParam(1,$nama);
		if($stmt2->execute()){
			?>
				<script type="text/javascript">location.href='alternatif.php'</script>
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
		$stmt2 = $db->prepare("update smart_alternatif set nama_alternatif=? where id_alternatif=?");
		$stmt2->bindParam(1,$nama);
		$stmt2->bindParam(2,$id);
		if($stmt2->execute()){
			?>
				<script type="text/javascript">location.href='alternatif.php'</script>
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
		<label>Alternatif</label>
		<div class="input-control text full-size">
		    <input type="text" name="nama" placeholder="Nama Alternatif" value="<?php echo isset($_GET['nama'])? $_GET['nama'] : ''; ?>">
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
		$stmt = $db->prepare("delete from smart_alternatif where id_alternatif='".$_GET['id']."'");
	 	if($stmt->execute()){
	 		?>
	 		<script type="text/javascript">location.href='alternatif.php'</script>
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
			<th>Alternatif</th>
			<th width="240">Aksi</th>
		</tr>
	</thead>
	<tbody>
		<?php
		$stmt = $db->prepare("select * from smart_alternatif");
		$stmt->execute();
        $no = 1;
		while($row = $stmt->fetch()){
		?>
		<tr>
			<td><?php echo $no++ ?></td>
			<td><?php echo $row['nama_alternatif'] ?></td>
			<td class="align-center">
				<a href="?page=form&id=<?php echo $row['id_alternatif'] ?>&nama=<?php echo $row['nama_alternatif'] ?>" class="button warning"><span class="mif-pencil icon"></span> Edit</a>
				<a href="?page=hapus&id=<?php echo $row['id_alternatif'] ?>" class="button danger"><span class="mif-cancel icon"></span> Hapus</a>
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
					
					