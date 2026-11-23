<?php
require_once 'includes/auth.php';
require_once 'config/Database.php';
require_once 'models/Siswa.php';
require_once 'models/Kelas.php';

$db = (new Database())->getConnection();
$siswaModel = new Siswa($db);
$kelasModel = new Kelas($db);

$daftarKelas = $kelasModel->getAll();
$errors = [];
$success = '';
$editData = null;

// Handle Delete
if (isset($_GET['hapus'])) {
    $nisn_hapus = $_GET['hapus'];
    if ($siswaModel->delete($nisn_hapus)) {
        $success = "Data siswa berhasil dihapus.";
    } else {
        $errors['general'] = "Gagal menghapus data siswa.";
    }
}

// Handle Edit Fetch
if (isset($_GET['edit'])) {
    $editData = $siswaModel->getByNisn($_GET['edit']);
}

// Form Values
$old_nisn = $editData['nisn'] ?? '';
$nisn = $editData['nisn'] ?? '';
$nama = $editData['nama'] ?? '';
$alamat = $editData['alamat'] ?? '';
$id_kelas = $editData['id_kelas'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old_nisn = $_POST['old_nisn'] ?? '';
    $nisn = trim($_POST['nisn'] ?? '');
    $nama = trim($_POST['nama'] ?? '');
    $alamat = trim($_POST['alamat'] ?? '');
    $id_kelas = $_POST['id_kelas'] ?? '';

    // Validasi NISN: Wajib 10 digit angka
    if ($nisn === '') {
        $errors['nisn'] = "NISN wajib diisi.";
    } elseif (!preg_match('/^\d{10}$/', $nisn)) {
        $errors['nisn'] = "NISN wajib tepat 10 digit angka.";
    } else {
        $existing = $siswaModel->getByNisn($nisn);
        if ($existing && ($old_nisn === '' || $existing['nisn'] !== $old_nisn)) {
            $errors['nisn'] = "NISN sudah terdaftar, tidak boleh kembar.";
        }
    }

    // Validasi Nama
    if ($nama === '') {
        $errors['nama'] = "Nama siswa wajib diisi.";
    }

    // Validasi Kelas
    if ($id_kelas === '' || !$kelasModel->getById($id_kelas)) {
        $errors['id_kelas'] = "Kelas wajib dipilih dari daftar yang tersedia.";
    }

    if (empty($errors)) {
        $siswaModel->setNisn($nisn);
        $siswaModel->setNama($nama);
        $siswaModel->setAlamat($alamat ?: null);
        $siswaModel->setIdKelas($id_kelas);

        if ($old_nisn !== '') {
            if ($siswaModel->update($old_nisn)) {
                $success = "Data siswa berhasil diperbarui.";
                $editData = null;
                $old_nisn = $nisn = $nama = $alamat = $id_kelas = '';
            }
        } else {
            if ($siswaModel->create()) {
                $success = "Data siswa berhasil ditambahkan.";
                $nisn = $nama = $alamat = $id_kelas = '';
            }
        }
    }
}

$keyword = trim($_GET['cari'] ?? '');
$daftarSiswa = $siswaModel->getAll($keyword);

include 'includes/header.php';
include 'includes/navbar.php';
?>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white fw-bold">
                <?= ($editData || $old_nisn !== '') ? 'Ubah Data Siswa' : 'Tambah Siswa Baru' ?>
            </div>
            <div class="card-body">
                <form method="POST" action="siswa.php">
                    <input type="hidden" name="old_nisn" value="<?= htmlspecialchars($old_nisn) ?>">

                    <div class="mb-3">
                        <label class="form-label">NISN (10 Digit) <span class="text-danger">*</span></label>
                        <input type="text" name="nisn" maxlength="10" class="form-control <?= isset($errors['nisn']) ? 'is-invalid' : '' ?>" 
                               placeholder="Contoh: 0012345678" value="<?= htmlspecialchars($nisn) ?>">
                        <?php if (isset($errors['nisn'])): ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($errors['nisn']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control <?= isset($errors['nama']) ? 'is-invalid' : '' ?>" 
                               value="<?= htmlspecialchars($nama) ?>">
                        <?php if (isset($errors['nama'])): ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($errors['nama']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kelas <span class="text-danger">*</span></label>
                        <select name="id_kelas" class="form-select <?= isset($errors['id_kelas']) ? 'is-invalid' : '' ?>">
                            <option value="">-- Pilih Kelas --</option>
                            <?php foreach ($daftarKelas as $k): ?>
                                <option value="<?= $k['id_kelas'] ?>" <?= ($id_kelas == $k['id_kelas']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($k['nama_kelas']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (isset($errors['id_kelas'])): ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($errors['id_kelas']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Alamat (Opsional)</label>
                        <textarea name="alamat" rows="2" class="form-control"><?= htmlspecialchars($alamat) ?></textarea>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary"><?= ($editData || $old_nisn !== '') ? 'Simpan Perubahan' : 'Tambah' ?></button>
                        <?php if ($editData || $old_nisn !== ''): ?>
                            <a href="siswa.php" class="btn btn-secondary">Batal</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <?php if ($success): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
        <?php if (isset($errors['general'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($errors['general']) ?></div>
        <?php endif; ?>

        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex flex-wrap justify-content-between align-items-center gap-2">
                <span class="fw-bold">Daftar Siswa</span>
                <form method="GET" action="siswa.php" class="d-flex gap-2">
                    <input type="text" name="cari" class="form-control form-control-sm" placeholder="Cari Nama / NISN..." value="<?= htmlspecialchars($keyword) ?>">
                    <button type="submit" class="btn btn-primary btn-sm">Cari</button>
                    <?php if ($keyword !== ''): ?>
                        <a href="siswa.php" class="btn btn-outline-secondary btn-sm">Reset</a>
                    <?php endif; ?>
                </form>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>NISN</th>
                            <th>Nama Siswa</th>
                            <th>Kelas</th>
                            <th>Alamat</th>
                            <th width="140">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($daftarSiswa)): ?>
                            <tr><td colspan="5" class="text-center text-muted py-3">Data siswa tidak ditemukan.</td></tr>
                        <?php else: ?>
                            <?php foreach ($daftarSiswa as $s): ?>
                                <tr>
                                    <td><code><?= htmlspecialchars($s['nisn']) ?></code></td>
                                    <td class="fw-semibold"><?= htmlspecialchars($s['nama']) ?></td>
                                    <td><span class="badge bg-info text-dark"><?= htmlspecialchars($s['nama_kelas']) ?></span></td>
                                    <td class="small text-muted"><?= htmlspecialchars($s['alamat'] ?? '-') ?></td>
                                    <td>
                                        <a href="siswa.php?edit=<?= urlencode($s['nisn']) ?>" class="btn btn-warning btn-sm">Ubah</a>
                                        <a href="siswa.php?hapus=<?= urlencode($s['nisn']) ?>" class="btn btn-danger btn-sm" 
                                           onclick="return confirm('Hapus siswa <?= htmlspecialchars($s['nama']) ?>?')">Hapus</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>