<?php
require_once 'BaseModel.php';

class Presensi extends BaseModel {
    private $id;
    private $nisn;
    private $tanggal_waktu;

    public function setId($id) { $this->id = $id; }
    public function getId() { return $this->id; }

    public function setNisn($nisn) { $this->nisn = $nisn; }
    public function getNisn() { return $this->nisn; }

    public function setTanggalWaktu($tanggal_waktu) { $this->tanggal_waktu = $tanggal_waktu; }
    public function getTanggalWaktu() { return $this->tanggal_waktu; }

    public function getAll($tanggal = '', $id_kelas = '', $keyword = '') {
        $sql = "SELECT presensi.id, presensi.nisn, presensi.tanggal_waktu, siswa.nama, kelas.nama_kelas 
                FROM presensi 
                INNER JOIN siswa ON presensi.nisn = siswa.nisn 
                INNER JOIN kelas ON siswa.id_kelas = kelas.id_kelas 
                WHERE 1=1";
        $params = [];

        if (!empty($tanggal)) {
            $sql .= " AND DATE(presensi.tanggal_waktu) = :tanggal";
            $params[':tanggal'] = $tanggal;
        }

        if (!empty($id_kelas)) {
            $sql .= " AND kelas.id_kelas = :id_kelas";
            $params[':id_kelas'] = $id_kelas;
        }

        if (!empty($keyword)) {
            $sql .= " AND (siswa.nama LIKE :kw OR siswa.nisn LIKE :kw)";
            $params[':kw'] = "%$keyword%";
        }

        $sql .= " ORDER BY presensi.tanggal_waktu DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function tapKartu() {
        $stmt = $this->db->prepare("INSERT INTO presensi (nisn) VALUES (:nisn)");
        return $stmt->execute([':nisn' => $this->nisn]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM presensi WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}