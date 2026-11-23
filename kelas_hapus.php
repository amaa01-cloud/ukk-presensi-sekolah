<?php
session_start();
if(!isset($_SESSION['admin'])) { header("Location: login.php"); exit; }
require_once 'Kelas.php';

if(isset($_GET['id'])) {
    $kelasObj = new Kelas();
    $result = $kelasObj->hapus($_GET['id']);
    
    if($result !== true) {
        // Tampilkan pesan error jika kelas masih memiliki siswa
        echo "<script>alert('$result'); window.location='kelas_tampil.php';</script>";
        exit;
    }
}
header("Location: kelas_tampil.php");
exit;
?>