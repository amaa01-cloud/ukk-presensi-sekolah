<?php
session_start();
if(!isset($_SESSION['admin'])) { header("Location: login.php"); exit; }
require_once 'Siswa.php';
require_once 'Kelas.php';
$data_kelas = (new Kelas())->getAll();
$pesan = "";
$nisn = ""; $nama = ""; $alamat = "";

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nisn = trim($_POST['nisn']);
    $nama = trim($_POST['nama']);
    $alamat = trim($_POST['alamat']);
    $id_kelas = $_POST['id_kelas'];

    $result = (new Siswa())->tambah($nisn, $nama, $alamat, $id_kelas);
    if($result === true) { header("Location: siswa_tampil.php"); exit; }
    else { $pesan = $result; }
}
?>
<!DOCTYPE html>
<html lang="id">
<head><title>Tambah Siswa</title></head>
<body>
    <h2>Tambah Siswa Baru</h2>
    <?php if($pesan): ?><p style="color:red;"><?= $pesan ?></p><?php endif; ?>
    <form method="POST">
        <label>NISN (10 Digit):</label><br><input type="text" name="nisn" maxlength="10" value="<?= htmlspecialchars($nisn) ?>" required><br><br>
        <label>Nama Siswa:</label><br><input type="text" name="nama" value="<?= htmlspecialchars($nama) ?>" required><br><br>
        <label>Kelas:</label><br>
        <select name="id_kelas" required>
            <option value="">-- Pilih Kelas --</option>
            <?php foreach($data_kelas as $k): ?>
                <option value="<?= $k['id_kelas'] ?>"><?= $k['nama_kelas'] ?></option>
            <?php endforeach; ?>
        </select><br><br>
        <label>Alamat:</label><br><textarea name="alamat"><?= htmlspecialchars($alamat) ?></textarea><br><br>
        <button type="submit">Simpan</button> <a href="siswa_tampil.php">Kembali</a>
    </form>
</body>
</html>