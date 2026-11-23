<?php
require_once 'config.php';

class Database {
    public $conn;
    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo "Koneksi Error: " . $e->getMessage();
        }
        return $this->conn;
    }
}

abstract class BaseModel {
    protected $db;
    public function __construct() {
        $db_obj = new Database();
        $this->db = $db_obj->getConnection();
    }
    abstract public function getAll();
}
?>