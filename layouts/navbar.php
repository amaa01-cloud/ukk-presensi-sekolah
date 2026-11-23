<nav>
    <a href="index.php">Rekap Presensi</a> |
    <a href="tap.php">Simulasi Tap Kartu</a> |
    <a href="kelas.php">Kelola Kelas</a> |
    <a href="siswa.php">Kelola Siswa</a> |
    <span>Admin: <strong><?= htmlspecialchars($_SESSION['username']) ?></strong></span> |
    <a href="logout.php" onclick="return confirm('Yakin ingin logout?')">Logout</a>
</nav>
<hr>