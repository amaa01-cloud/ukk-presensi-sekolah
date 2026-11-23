<?php
require_once 'Database.php';

abstract class BaseModel {
    protected $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    abstract public function getAll();
}
?>