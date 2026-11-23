<?php
session_start();
if(!isset($_SESSION['admin'])) { header("Location: login.php"); exit; }
require_once 'Siswa.php';
$siswaObj = new Siswa();
$keyword = isset($_GET['cari']) ? $_GET['cari'] : '';
$data = $keyword ? $siswaObj->cari($keyword) : $siswaObj->getAll();
?>
<!DOCTYPE html>
<html lang="id">
<head><title>Data Siswa</title></head>
<body>
    <h2>Daftar Siswa</h2>
    <a href="siswa_tambah.php">+ Tambah Siswa</a> | <a href="kelas_tampil.php">Kelola Kelas</a> | <a href="rekap_presensi.php">Rekap</a> | <a href="logout.php">Logout</a>
    <hr>
    <form method="GET">
        <input type="text" name="cari" placeholder="Cari Nama / NISN..." value="<?= htmlspecialchars($keyword) ?>">
        <button type="submit">Cari</button>
    </form><br>
    <table border="1" cellpadding="8" cellspacing="0">
        <tr><th>NISN</th><th>Nama</th><th>Kelas</th><th>Alamat</th><th>Aksi</th></tr>
        <?php foreach($data as $row): ?>
        <tr>
            <td><?= htmlspecialchars($row['nisn']) ?></td>
            <td><?= htmlspecialchars($row['nama']) ?></td>
            <td><?= htmlspecialchars($row['nama_kelas']) ?></td>
            <td><?= htmlspecialchars($row['alamat']) ?></td>
            <td>
                <a href="siswa_ubah.php?nisn=<?= $row['nisn'] ?>">Ubah</a> | 
                <a href="siswa_hapus.php?nisn=<?= $row['nisn'] ?>" onclick="return confirm('Yakin hapus?')">Hapus</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>