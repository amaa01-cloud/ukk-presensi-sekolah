<?php
require_once __DIR__ . '/basemodel.php';

class Kelas extends BaseModel {
    private $id_kelas;
    private $nama_kelas;
    private $jurusan;

    public function getIdKelas() { return $this->id_kelas; }
    public function setIdKelas($id) { $this->id_kelas = $id; }

    public function getNamaKelas() { return $this->nama_kelas; }
    public function setNamaKelas($nama) { $this->nama_kelas = $nama; }

    public function getJurusan() { return $this->jurusan; }
    public function setJurusan($jurusan) { $this->jurusan = $jurusan; }

    public function getAll() {
        $stmt = $this->db->prepare("SELECT * FROM kelas ORDER BY nama_kelas ASC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM kelas WHERE id_kelas = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function isNamaExists($nama_kelas, $exclude_id = null) {
        if ($exclude_id) {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM kelas WHERE nama_kelas = :nama AND id_kelas != :id");
            $stmt->execute([':nama' => $nama_kelas, ':id' => $exclude_id]);
        } else {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM kelas WHERE nama_kelas = :nama");
            $stmt->execute([':nama' => $nama_kelas]);
        }
        return $stmt->fetchColumn() > 0;
    }

    public function create() {
        $stmt = $this->db->prepare("INSERT INTO kelas (nama_kelas, jurusan) VALUES (:nama, :jurusan)");
        return $stmt->execute([
            ':nama' => $this->nama_kelas,
            ':jurusan' => $this->jurusan
        ]);
    }

    public function update() {
        $stmt = $this->db->prepare("UPDATE kelas SET nama_kelas = :nama, jurusan = :jurusan WHERE id_kelas = :id");
        return $stmt->execute([
            ':nama' => $this->nama_kelas,
            ':jurusan' => $this->jurusan,
            ':id' => $this->id_kelas
        ]);
    }

    public function delete($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM kelas WHERE id_kelas = :id");
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            if ($e->getCode() == '23000') {
                throw new Exception("Kelas tidak dapat dihapus karena masih memiliki data siswa terdaftar.");
            }
            throw new Exception("Terjadi kesalahan saat menghapus data kelas.");
        }
    }
}