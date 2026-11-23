<?php
require_once 'includes/auth.php';
require_once 'config/Database.php';
require_once 'models/Kelas.php';

$db = (new Database())->getConnection();
$kelasModel = new Kelas($db);

$errors = [];
$success = '';
$editData = null;

// Handle Delete
if (isset($_GET['hapus'])) {
    $id_hapus = (int)$_GET['hapus'];
    if ($kelasModel->hasSiswa($id_hapus)) {
        $errors['general'] = "Kelas tidak dapat dihapus karena masih memiliki data siswa terdaftar.";
    } else {
        if ($kelasModel->delete($id_hapus)) {
            $success = "Data kelas berhasil dihapus.";
        } else {
            $errors['general'] = "Gagal menghapus data kelas.";
        }
    }
}

// Handle Edit Fetch
if (isset($_GET['edit'])) {
    $editData = $kelasModel->getById((int)$_GET['edit']);
}

// Handle Create / Update
$nama_kelas = $editData['nama_kelas'] ?? '';
$jurusan = $editData['jurusan'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_kelas = $_POST['id_kelas'] ?? '';
    $nama_kelas = trim($_POST['nama_kelas'] ?? '');
    $jurusan = trim($_POST['jurusan'] ?? '');

    if ($nama_kelas === '') {
        $errors['nama_kelas'] = "Nama kelas wajib diisi.";
    } elseif ($kelasModel->isNamaExists($nama_kelas, $id_kelas ?: null)) {
        $errors['nama_kelas'] = "Nama kelas sudah digunakan, tidak boleh kembar.";
    }

    if ($jurusan === '') {
        $errors['jurusan'] = "Jurusan wajib diisi.";
    }

    if (empty($errors)) {
        $kelasModel->setNamaKelas($nama_kelas);
        $kelasModel->setJurusan($jurusan);

        if ($id_kelas) {
            $kelasModel->setIdKelas($id_kelas);
            if ($kelasModel->update()) {
                $success = "Data kelas berhasil diperbarui.";
                $editData = null;
                $nama_kelas = '';
                $jurusan = '';
            }
        } else {
            if ($kelasModel->create()) {
                $success = "Data kelas berhasil ditambahkan.";
                $nama_kelas = '';
                $jurusan = '';
            }
        }
    }
}

$daftarKelas = $kelasModel->getAll();

include 'includes/header.php';
include 'includes/navbar.php';
?>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white fw-bold">
                <?= $editData ? 'Ubah Data Kelas' : 'Tambah Kelas Baru' ?>
            </div>
            <div class="card-body">
                <form method="POST" action="kelas.php">
                    <?php if ($editData): ?>
                        <input type="hidden" name="id_kelas" value="<?= htmlspecialchars($editData['id_kelas']) ?>">
                    <?php endif; ?>

                    <div class="mb-3">
                        <label class="form-label">Nama Kelas <span class="text-danger">*</span></label>
                        <input type="text" name="nama_kelas" class="form-control <?= isset($errors['nama_kelas']) ? 'is-invalid' : '' ?>" 
                               placeholder="Contoh: X PPLG 1" value="<?= htmlspecialchars($nama_kelas) ?>">
                        <?php if (isset($errors['nama_kelas'])): ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($errors['nama_kelas']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jurusan <span class="text-danger">*</span></label>
                        <input type="text" name="jurusan" class="form-control <?= isset($errors['jurusan']) ? 'is-invalid' : '' ?>" 
                               placeholder="Contoh: PPLG" value="<?= htmlspecialchars($jurusan) ?>">
                        <?php if (isset($errors['jurusan'])): ?>
                            <div class="invalid-feedback"><?= htmlspecialchars($errors['jurusan']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary"><?= $editData ? 'Simpan Perubahan' : 'Tambah' ?></button>
                        <?php if ($editData): ?>
                            <a href="kelas.php" class="btn btn-secondary">Batal</a>
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
            <div class="card-header bg-white fw-bold">Daftar Kelas</div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Kelas</th>
                            <th>Jurusan</th>
                            <th width="150">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($daftarKelas)): ?>
                            <tr><td colspan="4" class="text-center text-muted py-3">Belum ada data kelas.</td></tr>
                        <?php else: ?>
                            <?php foreach ($daftarKelas as $i => $k): ?>
                                <tr>
                                    <td><?= $i + 1 ?></td>
                                    <td class="fw-semibold"><?= htmlspecialchars($k['nama_kelas']) ?></td>
                                    <td><?= htmlspecialchars($k['jurusan']) ?></td>
                                    <td>
                                        <a href="kelas.php?edit=<?= $k['id_kelas'] ?>" class="btn btn-warning btn-sm">Ubah</a>
                                        <a href="kelas.php?hapus=<?= $k['id_kelas'] ?>" class="btn btn-danger btn-sm" 
                                           onclick="return confirm('Yakin hapus kelas <?= htmlspecialchars($k['nama_kelas']) ?>?')">Hapus</a>
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