<?php
class Kelas extends BaseModel
{
    private ?int $id = null;
    private string $namaKelas = '';
    private ?string $jurusan = null;

    public function __construct(PDO $db) { parent::__construct($db); }

    public function getId(): ?int { return $this->id; }
    public function setId(?int $id): void { $this->id = $id; }
    public function getNamaKelas(): string { return $this->namaKelas; }
    public function setNamaKelas(string $n): void { $this->namaKelas = trim($n); }
    public function getJurusan(): ?string { return $this->jurusan; }
    public function setJurusan(?string $j): void
    {
        $j = trim((string)$j);
        $this->jurusan = $j === '' ? null : $j;
    }

    // READ semua
    public function getAll(): array
    {
        $sql = "SELECT k.id_kelas, k.nama_kelas, k.jurusan,
                       (SELECT COUNT(*) FROM siswa s WHERE s.id_kelas = k.id_kelas) AS jumlah_siswa
                FROM kelas k ORDER BY k.nama_kelas";
        return $this->db->query($sql)->fetchAll();
    }

    // READ satu
    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT id_kelas, nama_kelas, jurusan FROM kelas WHERE id_kelas = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function namaExists(): bool
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM kelas WHERE nama_kelas = :n AND id_kelas <> :id");
        $stmt->execute([':n' => $this->namaKelas, ':id' => $this->id ?? 0]);
        return (int)$stmt->fetchColumn() > 0;
    }

    // Validasi: nama kelas tidak boleh kosong
    public function validate(): array
    {
        $errors = [];
        if ($this->namaKelas === '') {
            $errors['nama_kelas'] = 'Nama kelas tidak boleh kosong.';
        } elseif ($this->namaExists()) {
            $errors['nama_kelas'] = 'Nama kelas sudah digunakan.';
        }
        return $errors;
    }

    // CREATE
    public function create(): bool
    {
        $stmt = $this->db->prepare("INSERT INTO kelas (nama_kelas, jurusan) VALUES (:n, :j)");
        return $stmt->execute([':n' => $this->namaKelas, ':j' => $this->jurusan]);
    }

    // UPDATE
    public function update(): bool
    {
        $stmt = $this->db->prepare("UPDATE kelas SET nama_kelas = :n, jurusan = :j WHERE id_kelas = :id");
        return $stmt->execute([':n' => $this->namaKelas, ':j' => $this->jurusan, ':id' => $this->id]);
    }

    public function countSiswa(int $id): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM siswa WHERE id_kelas = :id");
        $stmt->execute([':id' => $id]);
        return (int)$stmt->fetchColumn();
    }

    // DELETE (halaman wajib cek countSiswa dulu agar pesannya mudah dipahami)
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM kelas WHERE id_kelas = :id");
        return $stmt->execute([':id' => $id]);
    }
}