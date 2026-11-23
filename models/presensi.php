<?php
class Presensi extends BaseModel
{
    private ?int $id = null;
    private string $nisn = '';

    public function __construct(PDO $db) { parent::__construct($db); }

    public function getId(): ?int { return $this->id; }
    public function getNisn(): string { return $this->nisn; }
    public function setNisn(string $v): void { $this->nisn = trim($v); }

    // READ: INNER JOIN 3 tabel + filter tanggal, kelas, nama/NISN
    public function getAll(array $filter = []): array
    {
        $sql = "SELECT p.id, s.nisn, s.nama, k.nama_kelas, p.tanggal_waktu
                FROM presensi p
                INNER JOIN siswa s ON p.nisn = s.nisn
                INNER JOIN kelas k ON s.id_kelas = k.id_kelas
                WHERE 1=1";
        $params = [];
        if (!empty($filter['tanggal'])) {
            $sql .= " AND DATE(p.tanggal_waktu) = :tgl";
            $params[':tgl'] = $filter['tanggal'];
        }
        if (!empty($filter['id_kelas'])) {
            $sql .= " AND k.id_kelas = :kls";
            $params[':kls'] = (int)$filter['id_kelas'];
        }
        if (!empty($filter['q'])) {
            $sql .= " AND (s.nama LIKE :q1 OR s.nisn LIKE :q2)";
            $params[':q1'] = '%' . $filter['q'] . '%';
            $params[':q2'] = '%' . $filter['q'] . '%';
        }
        $sql .= " ORDER BY p.tanggal_waktu DESC, p.id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function validate(): array
    {
        $errors = [];
        if ($this->nisn === '') {
            $errors['nisn'] = 'NISN tidak boleh kosong.';
        } elseif (!(new Siswa($this->db))->exists($this->nisn)) {
            $errors['nisn'] = 'NISN tidak terdaftar. Kartu tidak dikenali.';
        }
        return $errors;
    }

    // CREATE: hanya NISN dikirim; id dan tanggal_waktu diisi otomatis oleh database
    public function tap(): ?array
    {
        $stmt = $this->db->prepare("INSERT INTO presensi (nisn) VALUES (:n)");
        $stmt->execute([':n' => $this->nisn]);
        $this->id = (int)$this->db->lastInsertId();

        $q = $this->db->prepare("SELECT p.id, s.nisn, s.nama, k.nama_kelas, p.tanggal_waktu
                                 FROM presensi p
                                 INNER JOIN siswa s ON p.nisn = s.nisn
                                 INNER JOIN kelas k ON s.id_kelas = k.id_kelas
                                 WHERE p.id = :id");
        $q->execute([':id' => $this->id]);
        return $q->fetch() ?: null;
    }

    // DELETE: log yang salah catat dapat dihapus (log tidak dapat diubah, jadi tidak ada update)
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM presensi WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}