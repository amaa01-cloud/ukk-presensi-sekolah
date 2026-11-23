<?php
require_once 'BaseModel.php';

class User extends BaseModel {
    private $id;
    private $username;
    private $password;

    public function setId($id) { $this->id = $id; }
    public function getId() { return $this->id; }

    public function setUsername($username) { $this->username = $username; }
    public function getUsername() { return $this->username; }

    public function setPassword($password) { $this->password = $password; }
    public function getPassword() { return $this->password; }

    public function getAll() {
        $stmt = $this->db->query("SELECT id, username FROM users");
        return $stmt->fetchAll();
    }

    public function login($username, $password) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :username LIMIT 1");
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $this->setId($user['id']);
            $this->setUsername($user['username']);
            return true;
        }
        return false;
    }
}