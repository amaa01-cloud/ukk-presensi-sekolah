<?php
require_once 'auth.php';
require_once 'config/database.php';
require_once 'models/siswa.php';
require_once 'models/presensi.php';

$db = (new Database())->getConnection();
$siswaModel = new Siswa($db);
$presensiModel = new Presensi($db);

$error = '';
$sukses = '';
$nisn = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nisn = trim($_POST['nisn'] ?? '');

    if (empty($nisn)) {
        $error = "Silakan masukkan atau tempelkan kartu NISN terlebih dahulu.";
    } else {
        $siswa = $siswaModel->getByNisn($nisn);
        if (!$siswa) {
            $error = "Tap ditolak: NISN ($nisn) tidak terdaftar di sistem.";
        } else {
            $presensiModel->setNisn($nisn);
            if ($presensiModel->tapKartu()) {
                $sukses = "Presensi berhasil dicatat untuk: " . $siswa['nama'] . " (NISN: " . $siswa['nisn'] . ")";
                $nisn = ''; // Kosongkan input setelah berhasil tap
            } else {
                $error = "Gagal mencatat presensi.";
            }
        }
    }
}

require_once 'layouts/header.php';
require_once 'layouts/navbar.php';
?>

<h2>Simulasi Tap Kartu RFID</h2>

<?php if ($error): ?>
    <p><strong>Error:</strong> <?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<?php if ($sukses): ?>
    <p><strong>Berhasil:</strong> <?= htmlspecialchars($sukses) ?></p>
<?php endif; ?>

<form method="POST" action="">
    <div>
        <label>Tempelkan Kartu / Masukkan NISN (10 Digit):</label><br>
        <input type="text" name="nisn" value="<?= htmlspecialchars($nisn) ?>" autofocus placeholder="Contoh: 0081234567">
    </div><br>
    <button type="submit">Tap Kartu</button>
</form>

<?php require_once 'layouts/footer.php'; ?>