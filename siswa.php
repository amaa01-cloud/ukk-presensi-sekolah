<?php
class Siswa extends BaseModel
{
    private string $nisn = '';
    private string $nama = '';
    private ?string $alamat = null;
    private int $idKelas = 0;

    public function __construct(PDO $db) { parent::__construct($db); }

    public function getNisn(): string { return $this->nisn; }
    public function setNisn(string $v): void { $this->nisn = trim($v); }
    public function getNama(): string { return $this->nama; }
    public function setNama(string $v): void { $this->nama = trim($v); }
    public function getAlamat(): ?string { return $this->alamat; }
    public function setAlamat(?string $v): void
    {
        $v = trim((string)$v);
        $this->alamat = $v === '' ? null : $v;
    }
    public function getIdKelas(): int { return $this->idKelas; }
    public function setIdKelas(int $v): void { $this->idKelas = $v; }

    // READ semua + pencarian nama/NISN, nama kelas lewat INNER JOIN
    public function getAll(string $q = ''): array
    {
        $sql = "SELECT s.nisn, s.nama, s.alamat, k.nama_kelas
                FROM siswa s
                INNER JOIN kelas k ON s.id_kelas = k.id_kelas";
        $params = [];
        if ($q !== '') {
            $sql .= " WHERE s.nama LIKE :q1 OR s.nisn LIKE :q2";
            $params = [':q1' => "%$q%", ':q2' => "%$q%"];
        }
        $sql .= " ORDER BY k.nama_kelas, s.nama";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    // READ satu
    public function find(string $nisn): ?array
    {
        $stmt = $this->db->prepare("SELECT s.nisn, s.nama, s.alamat, s.id_kelas, k.nama_kelas
                                    FROM siswa s INNER JOIN kelas k ON s.id_kelas = k.id_kelas
                                    WHERE s.nisn = :n");
        $stmt->execute([':n' => $nisn]);
        return $stmt->fetch() ?: null;
    }

    public function exists(string $nisn): bool
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM siswa WHERE nisn = :n");
        $stmt->execute([':n' => $nisn]);
        return (int)$stmt->fetchColumn() > 0;
    }

    // Validasi sisi server, pesan per kolom
    public function validate(bool $isNew): array
    {
        $errors = [];
        if ($this->nisn === '') {
            $errors['nisn'] = 'NISN tidak boleh kosong.';
        } elseif (!preg_match('/^[0-9]{10}$/', $this->nisn)) {
            $errors['nisn'] = 'NISN harus terdiri dari 10 digit angka.';
        } elseif ($isNew && $this->exists($this->nisn)) {
            $errors['nisn'] = 'NISN sudah terdaftar.';
        }
        if ($this->nama === '') {
            $errors['nama'] = 'Nama wajib diisi.';
        }
        if ($this->idKelas <= 0 || (new Kelas($this->db))->find($this->idKelas) === null) {
            $errors['id_kelas'] = 'Kelas wajib dipilih dari daftar kelas yang ada.';
        }
        return $errors;
    }

    // CREATE
    public function create(): bool
    {
        $stmt = $this->db->prepare("INSERT INTO siswa (nisn, nama, alamat, id_kelas) VALUES (:n, :nm, :a, :k)");
        return $stmt->execute([':n' => $this->nisn, ':nm' => $this->nama, ':a' => $this->alamat, ':k' => $this->idKelas]);
    }

    // UPDATE
    public function update(): bool
    {
        $stmt = $this->db->prepare("UPDATE siswa SET nama = :nm, alamat = :a, id_kelas = :k WHERE nisn = :n");
        return $stmt->execute([':n' => $this->nisn, ':nm' => $this->nama, ':a' => $this->alamat, ':k' => $this->idKelas]);
    }

    // DELETE
    public function delete(string $nisn): bool
    {
        $stmt = $this->db->prepare("DELETE FROM siswa WHERE nisn = :n");
        return $stmt->execute([':n' => $nisn]);
    }
}