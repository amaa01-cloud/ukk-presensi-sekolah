<?php
session_start();
if(!isset($_SESSION['admin'])) { header("Location: login.php"); exit; }
require_once 'Database.php';

$database = new Database();
$db = $database->getConnection();
$pesan = ""; $tipe = "";

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nisn = trim($_POST['nisn']);
    $stmt = $db->prepare("SELECT * FROM siswa WHERE nisn = ?");
    $stmt->execute([$nisn]);
    $siswa = $stmt->fetch();

    if($siswa) {
        $insert = $db->prepare("INSERT INTO presensi (nisn, tanggal_waktu) VALUES (?, NOW())");
        $insert->execute([$nisn]);
        $pesan = "Presensi Berhasil: " . $siswa['nama'] . " (" . $siswa['nisn'] . ") tercatat hadir!";
        $tipe = "success";
    } else {
        $pesan = "Ditolak: NISN tidak terdaftar dalam database!";
        $tipe = "error";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><title>Simulasi Tap Kartu</title>
    <style>body{font-family:Arial;background:#f4f4f9;display:flex;justify-content:center;align-items:center;height:100vh;margin:0;}
    .card{background:#fff;padding:30px;border-radius:8px;box-shadow:0 4px 10px rgba(0,0,0,0.1);width:350px;text-align:center;}
    input{width:100%;padding:10px;margin:10px 0;box-sizing:border-box;}button{width:100%;padding:10px;background:#28a745;color:#fff;border:none;border-radius:4px;font-weight:bold;cursor:pointer;}
    .success{color:green;background:#d4edda;padding:10px;margin-top:10px;}.error{color:red;background:#f8d7da;padding:10px;margin-top:10px;}</style>
</head>
<body>
    <div class="card">
        <h2>Simulasi Tap Kartu RFID</h2>
        <form method="POST">
            <input type="text" name="nisn" placeholder="Masukkan NISN Siswa..." autofocus required>
            <button type="submit">Tap Kartu</button>
        </form>
        <!--  if($pesan):--><div class="??= $tipe ??"><!--htmlspecialchars($pesan)--></div><!--  endif;-->
        <br><a href="siswa_tampil.php">Kembali ke Menu Utama</a>
    </div>
</body>
</html>