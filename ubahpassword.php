<?php
include "header.php";
?>
<div class="row cells4">
	<div class="cell colspan2">
		<h3>Ubah Password</h3>
	</div>
	<div class="cell colspan2 align-right">
		<a href="operator.php" class="button info">Kembali</a>
	</div>
</div>
	<p></p>
	<?php
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
		<input type="hidden" name="id" value="<?php echo isset($_SESSION['id'])? $_SESSION['id'] : ''; ?>">
		<label>Nama Lengkap</label>
		<div class="input-control text full-size">
		    <input type="text" name="nama" placeholder="Nama Lengkap" value="<?php echo isset($_SESSION['nama'])? $_SESSION['nama'] : ''; ?>">
		</div>
		<label>Username</label>
		<div class="input-control text full-size">
		    <input type="text" name="user" placeholder="Nama Pengguna" value="<?php echo isset($_SESSION['username'])? $_SESSION['username'] : ''; ?>">
		</div>
		<label>Password</label>
		<div class="input-control text full-size">
		    <input type="password" name="pass" placeholder="Kata Sandi">
		</div>
		<button type="submit" name="update" class="button warning">Update</button>
	</form>
<?php
include "footer.php";
?>
					
					