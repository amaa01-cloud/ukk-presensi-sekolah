<?php
session_start();
if(!isset($_SESSION['admin'])) { header("Location: login.php"); exit; }
require_once 'Database.php';
require_once 'Kelas.php';
$db = (new Database())->getConnection();
$nisn = $_GET['nisn'];

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $stmt = $db->prepare("UPDATE siswa SET nama = ?, alamat = ?, id_kelas = ? WHERE nisn = ?");
    $stmt->execute([trim($_POST['nama']), trim($_POST['alamat']), $_POST['id_kelas'], $nisn]);
    header("Location: siswa_tampil.php"); exit;
}
$stmt = $db->prepare("SELECT * FROM siswa WHERE nisn = ?");
$stmt->execute([$nisn]);
$data = $stmt->fetch();
$data_kelas = (new Kelas())->getAll();
?>
<!DOCTYPE html>
<html lang="id">
<head><title>Ubah Siswa</title></head>
<body>
    <h2>Ubah Data Siswa</h2>
    <form method="POST">
        <label>NISN:</label><br><input type="text" value="<?= htmlspecialchars($data['nisn']) ?>" disabled><br><br>
        <label>Nama:</label><br><input type="text" name="nama" value="<?= htmlspecialchars($data['nama']) ?>" required><br><br>
        <label>Kelas:</label><br>
        <select name="id_kelas" required>
            <?php foreach($data_kelas as $k): ?>
                <option value="<?= $k['id_kelas'] ?>" <?= $k['id_kelas'] == $data['id_kelas'] ? 'selected' : '' ?>><?= $k['nama_kelas'] ?></option>
            <?php endforeach; ?>
        </select><br><br>
        <label>Alamat:</label><br><textarea name="alamat"><?= htmlspecialchars($data['alamat']) ?></textarea><br><br>
        <button type="submit">Update</button> <a href="siswa_tampil.php">Kembali</a>
    </form>
</body>
</html>