<?php
session_start();
if (isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit;
}

require_once 'config/Database.php';
require_once 'models/User.php';

$error = '';
$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $db = (new Database())->getConnection();
    $userModel = new User($db);

    if ($userModel->login($username, $password)) {
        $_SESSION['admin_id'] = $userModel->getId();
        $_SESSION['admin_username'] = $userModel->getUsername();
        header("Location: index.php");
        exit;
    } else {
        $error = "Username atau password salah.";
    }
}

include 'includes/header.php';
?>
<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card shadow-sm" style="width: 100%; max-width: 400px;">
        <div class="card-body p-4">
            <h4 class="card-title text-center mb-1 fw-bold text-primary">Login Administrator</h4>
            <p class="text-center text-muted small mb-4">Aplikasi Presensi Siswa SMK TI Airlangga</p>

            <?php if ($error): ?>
                <div class="alert alert-danger py-2"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($username) ?>" required autofocus>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Masuk</button>
            </form>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>