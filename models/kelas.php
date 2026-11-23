<?php
require_once 'BaseModel.php';

class Kelas extends BaseModel {
    private $id_kelas;
    private $nama_kelas;
    private $jurusan;

    public function setIdKelas($id_kelas) { $this->id_kelas = $id_kelas; }
    public function getIdKelas() { return $this->id_kelas; }

    public function setNamaKelas($nama_kelas) { $this->nama_kelas = $nama_kelas; }
    public function getNamaKelas() { return $this->nama_kelas; }

    public function setJurusan($jurusan) { $this->jurusan = $jurusan; }
    public function getJurusan() { return $this->jurusan; }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM kelas ORDER BY nama_kelas ASC");
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
        $stmt = $this->db->prepare("INSERT INTO kelas (nama_kelas, jurusan) VALUES (:nama_kelas, :jurusan)");
        return $stmt->execute([
            ':nama_kelas' => $this->nama_kelas,
            ':jurusan' => $this->jurusan
        ]);
    }

    public function update() {
        $stmt = $this->db->prepare("UPDATE kelas SET nama_kelas = :nama_kelas, jurusan = :jurusan WHERE id_kelas = :id_kelas");
        return $stmt->execute([
            ':nama_kelas' => $this->nama_kelas,
            ':jurusan' => $this->jurusan,
            ':id_kelas' => $this->id_kelas
        ]);
    }

    public function hasSiswa($id_kelas) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM siswa WHERE id_kelas = :id");
        $stmt->execute([':id' => $id_kelas]);
        return $stmt->fetchColumn() > 0;
    }

    public function delete($id_kelas) {
        if ($this->hasSiswa($id_kelas)) {
            return false;
        }
        $stmt = $this->db->prepare("DELETE FROM kelas WHERE id_kelas = :id");
        return $stmt->execute([':id' => $id_kelas]);
    }
}