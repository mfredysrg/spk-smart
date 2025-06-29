<?php
include "config.php";
session_start();

// Inisialisasi percobaan login
if (!isset($_SESSION['login_attempt'])) {
    $_SESSION['login_attempt'] = 0;
    $_SESSION['lock_time'] = 0;
}

// Atur batas percobaan login dan waktu penguncian
$max_attempt = 3;
$lock_duration = 60; // dalam detik

$locked = false;
if ($_SESSION['login_attempt'] >= $max_attempt) {
    $remaining = time() - $_SESSION['lock_time'];
    if ($remaining < $lock_duration) {
        $locked = true;
    } else {
        $_SESSION['login_attempt'] = 0;
        $_SESSION['lock_time'] = 0;
        $locked = false;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8"/>
	<title>SPK-KEJAKSAAN NEGERI LHOKSEUMAWE</title>
    <link href="css/metro.css" rel="stylesheet">
    <link href="css/metro-icons.css" rel="stylesheet">
    <link href="css/metro-schemes.css" rel="stylesheet">
    <link href="css/metro-responsive.css" rel="stylesheet">
    <script src="js/jquery.js"></script>
    <script src="js/metro.js"></script>
    <style>
        body {
            background-image: url('assets/background.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
        .login-box {
            margin: 15px auto;
            width: 340px;
            background: rgba(255,255,255,0.9);
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 8px;
        }
        .login-image {
            display: block;
            margin: 20px auto 10px auto;
            width: 350px;
            height: auto;
        }
        .welcome-text {
            text-align: center;
            font-size: 22px;
            font-weight: bold;
            margin: 30px 0 10px 0;
            color: #000;
        }
        .button.primary {
            transition: background-color 0.3s ease, transform 0.2s ease;
        }
        .button.primary:hover {
            background-color: #0078d7;
            transform: scale(1.05);
        }
        .show-password {
            margin-top: 5px;
            font-size: 14px;
        }
        .captcha-box {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 10px;
        }
        .captcha-image img {
            height: 50px;
            border: 1px solid #ccc;
        }
    </style>
</head>
<body onload="runPB1()">
    <div class="app-bar">
		<a class="app-bar-element place-right" href="tentang.php">Tentang Aplikasi</a>
	</div>

    <div class="welcome-text">
        Selamat Datang, Silahkan Login
    </div>

    <img src="assets/kejaksaan.png" alt="Login Icon" class="login-image"/>

	<div class="login-box">
		<?php
		if (!$locked && isset($_POST['username']) && isset($_POST['password']) && isset($_POST['captcha'])) {
			$user = $_POST['username'];
			$pass = md5($_POST['password']);
			$captcha = $_POST['captcha'];

			if (strcasecmp($captcha, $_SESSION['captcha']) !== 0) {
				echo "<script>$.Notify({caption: 'Captcha Salah', content: 'Kode captcha tidak cocok.', type: 'alert'});</script>";
			} else {
				$stmt = $db->prepare("SELECT * FROM smart_admin WHERE username = ? AND password = ? LIMIT 1");
				$stmt->execute([$user, $pass]);
				$row = $stmt->fetch(PDO::FETCH_ASSOC);

				if ($row) {
					$_SESSION['id'] = $row['id_admin'];
					$_SESSION['nama'] = $row['nama_admin'];
					$_SESSION['username'] = $row['username'];
					$_SESSION['login_attempt'] = 0;
					$_SESSION['lock_time'] = 0;
					?>
					<div class="progress ani large" id="pb1" data-animate="500" data-color="ribbed-amber" data-role="progress"></div>
					<script>
						var interval1;
						function runPB1(){
							clearInterval(interval1);
							var pb = $("#pb1").data('progress');
							var val = 0;
							interval1 = setInterval(function(){
								val += 10;
								pb.set(val);
								if (val >= 100) {
									location.href='index.php';
									val = 0;
									clearInterval(interval1);
								}
							}, 100);
						}
					</script>
					<?php
				} else {
					$_SESSION['login_attempt']++;
					if ($_SESSION['login_attempt'] >= $max_attempt) {
						$_SESSION['lock_time'] = time();
					}
					echo "<script>$.Notify({caption: 'Maaf', content: 'Username atau password salah.', type: 'alert'});</script>";
				}
			}
		} elseif ($locked) {
			echo "<script>$.Notify({caption: 'Dikunci', content: 'Terlalu banyak percobaan login. Coba lagi nanti.', type: 'alert'});</script>";
		}
		?>

		<form method="post" onsubmit="return validateForm()">
			<div class="input-control text full-size">
				<label>Username</label>
				<span class="mif-user prepend-icon"></span>
				<input type="text" name="username" id="username" placeholder="Masukkan username" required>
			</div>
			<p></p>
			<div class="input-control password full-size">
				<label>Password</label>
				<span class="mif-key prepend-icon"></span>
				<input type="password" name="password" id="password" placeholder="Masukkan password" required>
			</div>
			<div class="show-password">
				<label><input type="checkbox" onclick="togglePassword()"> Tampilkan Password</label>
			</div>
			<div class="captcha-box">
				<label>Kode Captcha:</label>
				<div class="captcha-image">
					<img src="captcha.php" alt="Captcha">
				</div>
			</div>
			<div class="input-control text full-size">
				<input type="text" name="captcha" placeholder="Masukkan kode captcha" required>
			</div>
			<div style="text-align:center; margin-top: 20px;">
				<button type="submit" class="button primary">Masuk</button>
			</div>
		</form>
	</div>

	<script>
		function togglePassword() {
			var x = document.getElementById("password");
			x.type = x.type === "password" ? "text" : "password";
		}

		function validateForm() {
			var user = document.getElementById("username").value.trim();
			var pass = document.getElementById("password").value.trim();
			if (user === "" || pass === "") {
				$.Notify({
					caption: 'Validasi Gagal',
					content: 'Silakan isi username dan password!',
					type: 'alert'
				});
				return false;
			}
			return true;
		}
	</script>
</body>
</html>
