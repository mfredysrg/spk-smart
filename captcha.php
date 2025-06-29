<?php
session_start();

// Jangan cache captcha agar bisa direfresh
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header('Content-Type: image/png');

// Karakter captcha (hindari karakter ambigu)
$characters = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
$captcha_text = '';
for ($i = 0; $i < 6; $i++) {
    $captcha_text .= $characters[rand(0, strlen($characters) - 1)];
}

// Simpan ke sesi
$_SESSION['captcha'] = $captcha_text;

// Ukuran gambar
$width = 180;
$height = 50;
$image = imagecreatetruecolor($width, $height);

// Warna
$bg_color = imagecolorallocate($image, 255, 255, 255);
$text_color = imagecolorallocate($image, 0, 0, 0);
$noise_color = imagecolorallocate($image, 180, 180, 180);

// Isi latar belakang
imagefilledrectangle($image, 0, 0, $width, $height, $bg_color);

// Tambahkan noise titik
for ($i = 0; $i < 300; $i++) {
    imagefilledellipse($image, rand(0, $width), rand(0, $height), 1, 1, $noise_color);
}

// Tambahkan garis acak
for ($i = 0; $i < 10; $i++) {
    imageline($image, rand(0, $width), rand(0, $height),
              rand(0, $width), rand(0, $height), $noise_color);
}

// Gambar teks captcha (tanpa TTF)
imagestring($image, 5, 40, 15, $captcha_text, $text_color);

// Output gambar
imagepng($image);
imagedestroy($image);
