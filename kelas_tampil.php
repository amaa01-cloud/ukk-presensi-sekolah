<?php
session_start();
if(!isset($_SESSION['admin'])) { header("Location: login.php"); exit; }
require_once 'Kelas.php';

$kelasObj = new Kelas();
$data_kelas = $kelasObj->getAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Kelas - Presensi Siswa</title>
</head>
<body>
    <h2>Daftar Kelas</h2>
    <a href="siswa_tampil.php">&laquo; Menu Siswa</a> | 
    <a href="kelas_tambah.php">+ Tambah Kelas Baru</a> | 
    <a href="rekap_presensi.php">Rekap Presensi</a> | 
    <a href="logout.php">Logout</a>
    <hr>
    <table border="1" cellpadding="10" cellspacing="0">
        <tr>
            <th>ID Kelas</th>
            <th>Nama Kelas</th>
            <th>Jurusan</th>
            <th>Aksi</th>
        </tr>
        <?php foreach($data_kelas as $row): ?>
        <tr>
            <td><?= $row['id_kelas'] ?></td>
            <td><?= htmlspecialchars($row['nama_kelas']) ?></td>
            <td><?= htmlspecialchars($row['jurusan']) ?></td>
            <td>
                <a href="kelas_hapus.php?id=<?= $row['id_kelas'] ?>" onclick="return confirm('Yakin ingin menghapus kelas ini?')">Hapus</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>