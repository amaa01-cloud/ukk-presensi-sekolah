<?php
require_once 'includes/auth.php';
require_once 'config/Database.php';
require_once 'models/Siswa.php';
require_once 'models/Presensi.php';

$db = (new Database())->getConnection();
$siswaModel = new Siswa($db);
$presensiModel = new Presensi($db);

$error = '';
$success = '';
$nisn_input = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nisn_input = trim($_POST['nisn'] ?? '');

    if ($nisn_input === '') {
        $error = "Silakan tempelkan kartu atau masukkan NISN.";
    } else {
        $siswa = $siswaModel->getByNisn($nisn_input);
        if (!$siswa) {
            $error = "Tap ditolak: Kartu dengan NISN '" . htmlspecialchars($nisn_input) . "' tidak terdaftar di sistem.";
            $nisn_input = '';
        } else {
            $presensiModel->setNisn($siswa['nisn']);
            if ($presensiModel->tapKartu()) {
                $success = "Presensi berhasil dicatat untuk: <strong>" . htmlspecialchars($siswa['nama']) . "</strong> (" . htmlspecialchars($siswa['nisn']) . ")";
                $nisn_input = '';
            } else {
                $error = "Terjadi kesalahan saat menyimpan presensi.";
            }
        }
    }
}

include 'includes/header.php';
include 'includes/navbar.php';
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm text-center">
            <div class="card-header bg-white fw-bold">Simulasi Mesin Pembaca Kartu RFID</div>
            <div class="card-body p-4">
                <p class="text-muted small mb-4">Tempelkan kartu RFID atau ketik 10 digit NISN siswa lalu tekan Enter.</p>

                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>

                <?php if ($success): ?>
                    <div class="alert alert-success"><?= $success ?></div>
                <?php endif; ?>

                <form method="POST" action="tap.php">
                    <div class="mb-3">
                        <input type="text" name="nisn" class="form-control form-control-lg text-center fw-bold" 
                               placeholder="Scan / Ketik NISN..." maxlength="10" value="<?= htmlspecialchars($nisn_input) ?>" autofocus autocomplete="off">
                    </div>
                    <button type="submit" class="btn btn-success btn-lg w-100">Tap Kartu</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>