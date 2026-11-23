<?php // File: database.php (PDO Constructor & Exception Handling)
class Database {
    protected $pdo;
    public function __construct() {
        try {
            $this->pdo = new PDO("mysql:host=localhost;dbname=db_presensi", "root", "", [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        } catch (PDOException $e) { die("Koneksi Gagal: " . $e->getMessage()); }
    }
}
?>