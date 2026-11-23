<?php
session_start();
if(!isset($_SESSION['admin'])) { header("Location: login.php"); exit; }
require_once 'Database.php';

$database = new Database();
$db = $database->getConnection();
$tanggal = isset($_GET['tanggal']) ? $_GET['tanggal'] : date('Y-m-d');

$query = "SELECT p.id, p.tanggal_waktu, s.nisn, s.nama, k.nama_kelas, k.jurusan 
          FROM presensi p
          INNER JOIN siswa s ON p.nisn = s.nisn
          INNER JOIN kelas k ON s.id_kelas = k.id_kelas
          WHERE DATE(p.tanggal_waktu) = ?
          ORDER BY p.tanggal_waktu DESC";
$stmt = $db->prepare($query);
$stmt->execute([$tanggal]);
$data_presensi = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head><title>Rekap Presensi</title></head>
<body>
    <h2>Rekap Kehadiran Siswa</h2>
    <a href="siswa_tampil.php">&laquo; Menu Utama</a> | <a href="logout.php" style="color:red;">Logout</a>
    <hr>
    <form method="GET">
        <label>Filter Tanggal:</label>
        <input type="date" name="tanggal" value="<?= htmlspecialchars($tanggal) ?>">
        <button type="submit">Tampilkan</button>
    </form>
    <br>
    <table border="1" cellpadding="10" cellspacing="0">
        <tr>
            <th>No</th><th>Waktu Tap</th><th>NISN</th><th>Nama Siswa</th><th>Kelas</th><th>Jurusan</th>
        </tr>
        <?php if(count($data_presensi) > 0): ?>
            <?php $no=1; foreach($data_presensi as $row): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= $row['tanggal_waktu'] ?></td>
                <td><?= htmlspecialchars($row['nisn']) ?></td>
                <td><?= htmlspecialchars($row['nama']) ?></td>
                <td><?= htmlspecialchars($row['nama_kelas']) ?></td>
                <td><?= htmlspecialchars($row['jurusan']) ?></td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="6" align="center">Tidak ada data presensi pada tanggal ini.</td></tr>
        <?php endif; ?>
    </table>
</body>
</html>