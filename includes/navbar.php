<?php $current_page = basename($_SERVER['PHP_SELF']); ?>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">RFID Presensi</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link <?= $current_page == 'index.php' ? 'active fw-bold' : '' ?>" href="index.php">Rekap Presensi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $current_page == 'tap.php' ? 'active fw-bold' : '' ?>" href="tap.php">Simulasi Tap Kartu</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $current_page == 'siswa.php' ? 'active fw-bold' : '' ?>" href="siswa.php">Kelola Siswa</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $current_page == 'kelas.php' ? 'active fw-bold' : '' ?>" href="kelas.php">Kelola Kelas</a>
                </li>
            </ul>
            <div class="d-flex align-items-center text-white">
                <span class="me-3 small">Halo, <strong><?= htmlspecialchars($_SESSION['admin_username']) ?></strong></span>
                <a href="logout.php" class="btn btn-outline-light btn-sm" onclick="return confirm('Yakin ingin keluar?')">Logout</a>
            </div>
        </div>
    </div>
</nav>
<main class="container flex-grow-1"></main>