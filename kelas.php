<?php
require_once 'BaseModel.php';

class Kelas extends BaseModel {
    private $table = "kelas";

    public function getAll() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY nama_kelas ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function tambah($nama_kelas, $jurusan) {
        $check = $this->db->prepare("SELECT * FROM kelas WHERE nama_kelas = ?");
        $check->execute([$nama_kelas]);
        if($check->rowCount() > 0) {
            return "Error: Nama kelas sudah terdaftar!";
        }

        $query = "INSERT INTO " . $this->table . " (nama_kelas, jurusan) VALUES (?, ?)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$nama_kelas, $jurusan]);
    }

    public function hapus($id_kelas) {
        // Cek apakah kelas masih memiliki siswa (Sesuai ketentuan soal: Kelas yang masih memiliki siswa tidak boleh dihapus)
        $check = $this->db->prepare("SELECT * FROM siswa WHERE id_kelas = ?");
        $check->execute([$id_kelas]);
        if($check->rowCount() > 0) {
            return "Error: Kelas masih memiliki siswa dan tidak dapat dihapus!";
        }

        $query = "DELETE FROM " . $this->table . " WHERE id_kelas = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$id_kelas]);
    }
}
?>