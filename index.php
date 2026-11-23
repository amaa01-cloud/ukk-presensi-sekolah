<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

require_once 'auth.php';
require_once 'config/database.php';
require_once 'models/presensi.php';
require_once 'models/kelas.php';

$db = (new Database())->getConnection();
$presensiModel = new Presensi($db);
$kelasModel = new Kelas($db);

$pesan = '';

// Hapus log presensi
if (isset($_GET['hapus'])) {
    $id_hapus = (int)$_GET['hapus'];
    if ($presensiModel->delete($id_hapus)) {
        $pesan = "Log presensi berhasil dihapus.";
    }
}

// Default tanggal hari ini, kecuali user mengosongkan/memilih tanggal lain
$tanggal = isset($_GET['tanggal']) ? $_GET['tanggal'] : date('Y-m-d');
$id_kelas = $_GET['id_kelas'] ?? '';
$keyword = trim($_GET['keyword'] ?? '');

$daftar_kelas = $kelasModel->getAll();
$data_presensi = $presensiModel->getAll($tanggal, $id_kelas, $keyword);

require_once 'layouts/header.php';
require_once 'layouts/navbar.php';
?>

<h2>Rekap Presensi Siswa</h2>

<?php if ($pesan): ?>
    <p><strong>Info:</strong> <?= htmlspecialchars($pesan) ?></p>
<?php endif; ?>

<form method="GET" action="">
    <label>Tanggal:</label>
    <input type="date" name="tanggal" value="<?= htmlspecialchars($tanggal) ?>">

    <label>Kelas:</label>
    <select name="id_kelas">
        <option value="">-- Semua Kelas --</option>
        <?php foreach ($daftar_kelas as $k): ?>
            <option value="<?= $k['id_kelas'] ?>" <?= ($id_kelas == $k['id_kelas']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($k['nama_kelas']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label>Cari (Nama/NISN):</label>
    <input type="text" name="keyword" value="<?= htmlspecialchars($keyword) ?>" placeholder="Ketik nama atau NISN...">

    <button type="submit">Filter / Cari</button>
    <a href="index.php">Reset Hari Ini</a>
</form>
<br>

<table border="1" cellpadding="6" cellspacing="0">
    <thead>
        <tr>
            <th>No</th>
            <th>NISN</th>
            <th>Nama Siswa</th>
            <th>Kelas</th>
            <th>Waktu Presensi</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php if (count($data_presensi) > 0): ?>
            <?php $no = 1; foreach ($data_presensi as $row): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($row['nisn']) ?></td>
                    <td><?= htmlspecialchars($row['nama']) ?></td>
                    <td><?= htmlspecialchars($row['nama_kelas']) ?></td>
                    <td><?= htmlspecialchars($row['tanggal_waktu']) ?></td>
                    <td>
                        <a href="index.php?hapus=<?= $row['id'] ?>&tanggal=<?= htmlspecialchars($tanggal) ?>&id_kelas=<?= htmlspecialchars($id_kelas) ?>&keyword=<?= htmlspecialchars($keyword) ?>" 
                           onclick="return confirm('Hapus log presensi ini?')">Hapus</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="6">Tidak ada data presensi ditemukan.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php require_once 'layouts/footer.php'; ?>