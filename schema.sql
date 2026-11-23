CREATE DATABASE IF NOT EXISTS presensi_siswa
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE presensi_siswa;

DROP TABLE IF EXISTS presensi;
DROP TABLE IF EXISTS siswa;
DROP TABLE IF EXISTS kelas;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
  id         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  username   VARCHAR(50)  NOT NULL,
  password   VARCHAR(255) NOT NULL,
  created_at TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_users_username (username)
) ENGINE=InnoDB;

CREATE TABLE kelas (
  id_kelas   INT UNSIGNED NOT NULL AUTO_INCREMENT,
  nama_kelas VARCHAR(50)  NOT NULL,
  jurusan    VARCHAR(50)  NULL,
  PRIMARY KEY (id_kelas),
  UNIQUE KEY uq_kelas_nama (nama_kelas)
) ENGINE=InnoDB;

CREATE TABLE siswa (
  nisn     CHAR(10)     NOT NULL,
  nama     VARCHAR(100) NOT NULL,
  alamat   VARCHAR(255) NULL,
  id_kelas INT UNSIGNED NOT NULL,
  PRIMARY KEY (nisn),
  CONSTRAINT fk_siswa_kelas FOREIGN KEY (id_kelas)
    REFERENCES kelas (id_kelas) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE presensi (
  id            INT UNSIGNED NOT NULL AUTO_INCREMENT,
  nisn          CHAR(10)     NOT NULL,
  tanggal_waktu DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  CONSTRAINT fk_presensi_siswa FOREIGN KEY (nisn)
    REFERENCES siswa (nisn) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;