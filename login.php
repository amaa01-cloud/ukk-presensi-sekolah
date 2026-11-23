<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

require_once 'config/database.php';
require_once 'models/user.php';

$error = '';
$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = "Username dan password wajib diisi.";
    } else {
        $db = (new Database())->getConnection();
        $userModel = new User($db);

        if ($userModel->login($username, $password)) {
            $_SESSION['user_id'] = $userModel->getId();
            $_SESSION['username'] = $userModel->getUsername();
            header("Location: index.php");
            exit;
        } else {
            $error = "Username atau password salah.";
        }
    }
}

require_once 'layouts/header.php';
?>

<h2>Login Administrator</h2>

<?php if ($error): ?>
    <p><strong>Error:</strong> <?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form method="POST" action="">
    <div>
        <label>Username:</label><br>
        <input type="text" name="username" value="<?= htmlspecialchars($username) ?>">
    </div><br>
    <div>
        <label>Password:</label><br>
        <input type="password" name="password">
    </div><br>
    <button type="submit">Login</button>
</form>

<?php require_once 'layouts/footer.php'; ?>