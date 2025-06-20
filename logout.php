<?php
session_start(); // Memulai sesi
session_destroy(); // Menghancurkan semua data sesi
header('location: laporan.php'); // Mengarahkan pengguna kembali ke halaman laporan.php
?>