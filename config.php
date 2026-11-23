<?php
// Aktifkan error reporting untuk mendeteksi error dengan jelas
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Panggil file database.php
require_once 'database.php';

// Inisialisasi koneksi
$database = new Database();
$db = $database->getConnection();
?>