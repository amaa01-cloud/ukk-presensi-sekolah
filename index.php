<?php
require_once 'includes/auth.php';
require_once 'config/Database.php';
require_once 'models/Presensi.php';
require_once 'models/Kelas.php';

$db = (new Database())->getConnection();
$presensiModel = new Presensi($db);
$kelasModel = new Kelas($db);

$success = '';
$error = '';

// Handle Hapus Log
if (isset($_GET['hapus'])) {
    $id_hapus = (int)$_GET['hapus'];
    if ($presensiModel->delete($id_hapus)) {
        $success = "Log presensi berhasil dihapus.";
    } else {
        $error = "Gagal menghapus log presensi.";
    }
}

// Default filter tanggal hari ini
$tanggal = $_GET['tanggal'] ?? date('Y-m-d');
$id_kelas = $_GET['id_kelas'] ?? '';
$keyword = trim($_GET['cari'] ?? '');

$daftarKelas = $kelasModel->getAll();
$daftarPresensi = $presensiModel->getAll($tanggal, $id_kelas, $keyword);

include 'includes/header.php';
include 'includes/navbar.php';
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-bold mb-0">Rekap Presensi Siswa</h4>
    <a href="tap.php" class="btn btn-success btn-sm">+ Simulasi Tap Kartu</a>
</div>

<?php if ($success): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>
<?php if ($error): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="index.php" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Tanggal</label>
                <input type="date" name="tanggal" class="form-control" value="<?= htmlspecialchars($tanggal) ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Filter Kelas</label>
                <select name="id_kelas" class="form-select">
                    <option value="">-- Semua Kelas --</option>
                    <?php foreach ($daftarKelas as $k): ?>
                        <option value="<?= $k['id_kelas'] ?>" <?= ($id_kelas == $k['id_kelas']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($k['nama_kelas']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-semibold">Cari Siswa (Nama / NISN)</label>
                <input type="text" name="cari" class="form-control" placeholder="Ketik nama atau NISN..." value="<?= htmlspecialchars($keyword) ?>">
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
                <a href="index.php" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>NISN</th>
                    <th>Nama Siswa</th>
                    <th>Kelas</th>
                    <th>Waktu Tap</th>
                    <th width="100">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($daftarPresensi)): ?>
                    <tr><td colspan="6" class="text-center text-muted py-4">Tidak ada data presensi pada filter ini.</td></tr>
                <?php else: ?>
                    <?php foreach ($daftarPresensi as $i => $p): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><code><?= htmlspecialchars($p['nisn']) ?></code></td>
                            <td class="fw-semibold"><?= htmlspecialchars($p['nama']) ?></td>
                            <td><span class="badge bg-primary"><?= htmlspecialchars($p['nama_kelas']) ?></span></td>
                            <td><?= date('d-m-Y H:i:s', strtotime($p['tanggal_waktu'])) ?></td>
                            <td>
                                <a href="index.php?hapus=<?= $p['id'] ?>&tanggal=<?= urlencode($tanggal) ?>&id_kelas=<?= urlencode($id_kelas) ?>&cari=<?= urlencode($keyword) ?>" 
                                   class="btn btn-danger btn-sm" 
                                   onclick="return confirm('Hapus log presensi <?= htmlspecialchars($p['nama']) ?>?')">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/footer.php'; ?>