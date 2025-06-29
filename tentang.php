<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Tentang Aplikasi</title>
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
            font-family: sans-serif;
        }
        .content-box {
            margin: 40px auto;
            width: 80%;
            max-width: 800px;
            background: rgba(255,255,255,0.95);
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0,0,0,0.3);
        }
        .content-box h2 {
            text-align: center;
            margin-bottom: 25px;
        }
        .content-box p {
            text-align: justify;
            line-height: 1.7;
        }
    </style>
</head>
<body>
    <div class="app-bar">
        <a class="app-bar-element place-right" href="login.php">Login</a>
    </div>

    <div class="content-box">
        <h2>Tentang Aplikasi</h2>

        <h3>Apa itu Metode SMART?</h3>
        <p>
            SMART (Simple Multi-Attribute Rating Technique) adalah salah satu metode dalam Sistem Pendukung Keputusan (SPK)
            yang digunakan untuk membantu pengambilan keputusan berdasarkan kriteria dan bobot tertentu. SMART menggunakan pendekatan linear
            yang sederhana, di mana setiap alternatif akan dievaluasi berdasarkan nilai kriteria yang telah diberi bobot sesuai tingkat kepentingannya.
        </p>

        <p>
            Dalam metode SMART, langkah utama meliputi: menentukan alternatif, menetapkan kriteria, memberikan bobot pada tiap kriteria,
            melakukan normalisasi, menghitung skor akhir, dan menentukan alternatif terbaik. Metode ini populer karena kemudahannya dalam
            implementasi dan transparansi perhitungan.
        </p>

        <h3>Tentang Aplikasi Ini</h3>
        <p>
            Aplikasi ini merupakan sistem pendukung keputusan yang dirancang untuk membantu Kejaksaan Negeri Lhokseumawe dalam melakukan
            penilaian terhadap kinerja pegawai honorer secara lebih objektif dan sistematis. Dengan mengimplementasikan metode SMART, aplikasi ini
            memungkinkan penilai untuk memasukkan nilai berdasarkan beberapa kriteria penting seperti kedisiplinan, tanggung jawab,
            kerjasama tim, dan lain-lain.
        </p>

        <p>
            Hasil penilaian akan dihitung secara otomatis berdasarkan bobot yang telah ditentukan, sehingga memberikan rekomendasi yang adil
            dan berbasis data untuk mendukung proses pengambilan keputusan dalam evaluasi pegawai. Aplikasi ini dibangun untuk memberikan
            efisiensi, akurasi, dan kemudahan bagi pihak manajemen dalam melakukan penilaian kinerja secara menyeluruh.
        </p>
    </div>
</body>
</html>
