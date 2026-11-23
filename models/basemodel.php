<?php
abstract class BaseModel {
    protected $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    abstract public function getAll();
}