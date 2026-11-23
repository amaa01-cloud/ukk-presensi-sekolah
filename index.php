<?php
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Aplikasi Presensi Siswa - UKK</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; margin-top: 50px; background-color: #f4f4f9; }
        .card { background: white; padding: 30px; border-radius: 8px; display: inline-block; box-shadow: 0px 0px 10px rgba(0,0,0,0.1); }
        .success { color: green; font-weight: bold; }
        .error { color: red; font-weight: bold; }
    </style>
</head>
<body>
    <div class="card">
        <h2>Uji Unit Kompetensi - Presensi Siswa</h2>
        <hr>
        <p>Status Koneksi Database:</p>
        <?php
        if ($db) {
            echo '<p class="success">Koneksi ke Database Berhasil! (Database siap digunakan)</p>';
        } else {
            echo '<p class="error">Koneksi Gagal! Periksa nama database atau XAMPP.</p>';
        }
        ?>
    </div>
</body>
</html>