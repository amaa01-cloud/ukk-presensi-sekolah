<?php
session_start();
require_once 'Database.php';

$pesan_error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // 1. Validasi jika form kosong
    if (empty($username) || empty($password)) {
        $pesan_error = "Username dan password tidak boleh kosong!";
    } else {
        $database = new Database();
        $db = $database->getConnection();

        // 2. Ambil data user dari database berdasarkan username
        $query = "SELECT * FROM users WHERE username = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        // 3. Verifikasi password menggunakan password_verify
        if ($user && password_verify($password, $user['password'])) {
            // Set session login sukses
            $_SESSION['admin'] = true;
            $_SESSION['username'] = $user['username'];

            // Redirect langsung ke halaman rekap presensi
            header("Location: rekap_presensi.php");
            exit;
        } else {
            // Pesan error jika gagal
            $pesan_error = "Username atau password salah!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Administrator - Presensi Siswa</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f9; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .card { background: white; padding: 30px; border-radius: 8px; box-shadow: 0px 4px 10px rgba(0,0,0,0.1); width: 320px; }
        h2 { text-align: center; color: #333; margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; color: #555; }
        input[type="text"], input[type="password"] { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background-color: #007bff; border: none; border-radius: 4px; color: white; font-weight: bold; cursor: pointer; }
        button:hover { background-color: #0056b3; }
        .error { color: #d9534f; background: #f2dede; padding: 10px; border-radius: 4px; margin-bottom: 15px; font-size: 14px; text-align: center; }
    </style>
</head>
<body>
    <div class="card">
        <h2>Login Admin</h2>
        
        <?php if (!empty($pesan_error)): ?>
            <div class="error"><?= htmlspecialchars($pesan_error) ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label>Username:</label>
                <input type="text" name="username" value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>" required>
            </div>
            <div class="form-group">
                <label>Password:</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>