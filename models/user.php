<?php
class User extends BaseModel
{
    private ?int $id = null;
    private string $username = '';
    private string $passwordHash = '';

    public function __construct(PDO $db) { parent::__construct($db); }

    public function getId(): ?int { return $this->id; }
    public function getUsername(): string { return $this->username; }
    public function setUsername(string $u): void { $this->username = $u; }
    public function setPassword(string $plain): void
    {
        $this->passwordHash = password_hash($plain, PASSWORD_DEFAULT);
    }

    public function getAll(): array
    {
        return $this->db->query("SELECT id, username, created_at FROM users")->fetchAll();
    }

    public function findByUsername(string $username): bool
    {
        $stmt = $this->db->prepare("SELECT id, username, password FROM users WHERE username = :u LIMIT 1");
        $stmt->execute([':u' => $username]);
        $row = $stmt->fetch();
        if (!$row) return false;
        $this->id = (int)$row['id'];
        $this->username = $row['username'];
        $this->passwordHash = $row['password'];
        return true;
    }

    public function verifyPassword(string $plain): bool
    {
        return $this->passwordHash !== '' && password_verify($plain, $this->passwordHash);
    }

    public function create(): bool
    {
        $stmt = $this->db->prepare("INSERT INTO users (username, password) VALUES (:u, :p)");
        return $stmt->execute([':u' => $this->username, ':p' => $this->passwordHash]);
    }
}