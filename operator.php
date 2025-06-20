<?php
include "header.php"; // Pastikan file header.php ada dan berisi tag pembuka <body> atau struktur HTML awal yang sesuai.
$page = isset($_GET['page'])?$_GET['page']:"";
?>
<div class="row cells4">
	<div class="cell colspan2">
		<h3>Operator</h3>
	</div>
<?php
if($page=='form'){ // Blok untuk menampilkan form tambah/edit operator
?>
	<div class="cell colspan2 align-right">
		<a href="operator.php" class="button info">Kembali</a>
	</div>
</div> <?php
// Bagian ini adalah penanganan form (simpan/update) yang sudah benar tempatnya
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
	$pass = md5($_POST['pass']); // Pastikan password di-hash saat update
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
		if (isset($_GET['id'])) { // Jika ada ID, berarti mode edit
			?>
			<button type="submit" name="update" class="button warning">Update</button>
			<?php
		} else{ // Jika tidak ada ID, berarti mode tambah
			?>
			<button type="submit" name="simpan" class="button primary">Simpan</button>
			<?php
		}
		?>
	</form>
<?php
} else if($page=='hapus'){ // Blok untuk menghapus operator
?>
	<div class="cell colspan2 align-right">
	</div>
</div> <?php
	if(isset($_GET['id'])){
		$stmt = $db->prepare("delete from smart_admin where id_admin='".$_GET['id']."'");
	 	if($stmt->execute()){
	 		?>
	 		<script type="text/javascript">location.href='operator.php'</script>
	 		<?php
	 	}
	}
} else { // Blok default: menampilkan daftar operator (jika $page tidak 'form' atau 'hapus')
?>
	<div class="cell colspan2 align-right">
		<a href="?page=form" class="button primary">Tambah</a>
	</div>
</div> <table class="table striped hovered cell-hovered border bordered dataTable" data-role="datatable" data-searching="true">
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
} // Menutup blok if/else utama untuk $page
include "footer.php"; // Pastikan file footer.php ada dan berisi tag penutup </body> dan </html> atau struktur HTML akhir yang sesuai.
?>