<?php
session_start();
if(!isset($_SESSION['admin'])) { header("Location: login.php"); exit; }
require_once 'Database.php';
if(isset($_GET['nisn'])) {
    $db = (new Database())->getConnection();
    $stmt = $db->prepare("DELETE FROM siswa WHERE nisn = ?");
    $stmt->execute([$_GET['nisn']]);
}
header("Location: siswa_tampil.php");
exit;