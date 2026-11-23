<?php
session_start();
if(!isset($_SESSION['admin'])) { header("Location: login.php"); exit; }
require_once 'Siswa.php';

$siswaObj = new Siswa();
$keyword = isset($_GET['cari']) ? $_GET['cari'] : '';
$data_siswa = $keyword ? $siswaObj->cari($keyword) : $siswaObj->getAll();
?>
<!DOCTYPE html>
<html lang="id">
<head><title>Data Siswa</title></head>
<body>
    <h2>Daftar Siswa</h2>
    <a href="kelas_tampil.php">Kelola Kelas</a> | 
    <a href="siswa_tambah.php">+ Tambah Siswa</a> | 
    <a href="tap_kartu.php">Simulasi Tap Kartu</a> | 
    <a href="rekap_presensi.php">Rekap Presensi</a> | 
    <a href="logout.php" style="color:red;">Logout</a>
    <hr>
    <form method="GET">
        <input type="text" name="cari" placeholder="Cari Nama / NISN..." value="<?= htmlspecialchars($keyword) ?>">
        <button type="submit">Cari</button>
    </form>
    <br>
    <table border="1" cellpadding="10" cellspacing="0">
        <tr>
            <th>NISN</th><th>Nama Siswa</th><th>Kelas</th><th>Alamat</th>
        </tr>
        <?php foreach($data_siswa as $row): ?>
        <tr>
            <td><?= htmlspecialchars($row['nisn']) ?></td>
            <td><?= htmlspecialchars($row['nama']) ?></td>
            <td><?= htmlspecialchars($row['nama_kelas']) ?></td>
            <td><?= htmlspecialchars($row['alamat']) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>