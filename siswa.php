<?php
require_once 'BaseModel.php';

class Siswa extends BaseModel {
    private $table = "siswa";

    public function getAll() {
        $query = "SELECT s.*, k.nama_kelas FROM " . $this->table . " s 
                  INNER JOIN kelas k ON s.id_kelas = k.id_kelas 
                  ORDER BY s.nama ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function tambah($nisn, $nama, $alamat, $id_kelas) {
        if(!preg_match('/^[0-9]{10}$/', $nisn)) {
            return "Error: NISN wajib 10 digit angka!";
        }

        $check = $this->db->prepare("SELECT * FROM siswa WHERE nisn = ?");
        $check->execute([$nisn]);
        if($check->rowCount() > 0) {
            return "Error: NISN sudah terdaftar!";
        }

        $query = "INSERT INTO " . $this->table . " (nisn, nama, alamat, id_kelas) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$nisn, $nama, $alamat, $id_kelas]);
    }

    public function cari($keyword) {
        $query = "SELECT s.*, k.nama_kelas FROM " . $this->table . " s 
                  INNER JOIN kelas k ON s.id_kelas = k.id_kelas 
                  WHERE s.nama LIKE ? OR s.nisn LIKE ? 
                  ORDER BY s.nama ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute(["%$keyword%", "%$keyword%"]);
        return $stmt->fetchAll();
    }
}
?>