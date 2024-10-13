/*
SQLyog Professional v12.5.1 (64 bit)
MySQL - 10.4.27-MariaDB : Database - db_magang
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`db_magang` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;

USE `db_magang`;

/*Table structure for table `tbl_absensi` */

DROP TABLE IF EXISTS `tbl_absensi`;

CREATE TABLE `tbl_absensi` (
  `id_absensi` int(15) NOT NULL AUTO_INCREMENT,
  `id_mahasiswa` int(15) DEFAULT NULL,
  `status` int(15) DEFAULT NULL,
  `waktu` time DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  PRIMARY KEY (`id_absensi`),
  KEY `tbl_absensi_ibfk1_1` (`id_mahasiswa`),
  CONSTRAINT `tbl_absensi_ibfk1_1` FOREIGN KEY (`id_mahasiswa`) REFERENCES `tbl_mahasiswa` (`id_mahasiswa`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=94 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `tbl_absensi` */

insert  into `tbl_absensi`(`id_absensi`,`id_mahasiswa`,`status`,`waktu`,`tanggal`) values 
(82,13,1,'08:14:46','2024-05-13'),
(83,10,1,'08:14:46','2024-05-13'),
(84,11,1,'08:15:41','2024-05-13'),
(85,12,1,'08:15:43','2024-05-13'),
(86,15,1,'08:31:46','2024-05-13'),
(87,14,2,'08:32:47','2024-05-13'),
(88,11,1,'08:01:43','2024-05-14'),
(89,12,1,'08:01:57','2024-05-14'),
(90,13,1,'08:02:52','2024-05-14'),
(91,14,1,'08:05:13','2024-05-14'),
(92,10,1,'08:05:21','2024-05-14'),
(93,15,1,'08:16:36','2024-05-14');

/*Table structure for table `tbl_admin` */

DROP TABLE IF EXISTS `tbl_admin`;

CREATE TABLE `tbl_admin` (
  `id_admin` int(15) NOT NULL AUTO_INCREMENT,
  `kode_admin` varchar(4) DEFAULT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `nip` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_admin`),
  KEY `kode_admin` (`kode_admin`),
  CONSTRAINT `tbl_admin_ibfk_1` FOREIGN KEY (`kode_admin`) REFERENCES `tbl_user` (`kode_pengguna`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `tbl_admin` */

insert  into `tbl_admin`(`id_admin`,`kode_admin`,`nama`,`nip`,`email`) values 
(1,'A001','Susi','2022122501','susi123@gmail.com'),
(2,'A002','Rini Hardiyanti Surahman','2022122502','Rinihardiyanti@gmail.com');

/*Table structure for table `tbl_alasan` */

DROP TABLE IF EXISTS `tbl_alasan`;

CREATE TABLE `tbl_alasan` (
  `id_alasan` int(15) NOT NULL AUTO_INCREMENT,
  `id_mahasiswa` int(15) DEFAULT NULL,
  `alasan` varchar(255) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  PRIMARY KEY (`id_alasan`),
  KEY `tbl_alasan_ibfk1_1` (`id_mahasiswa`),
  CONSTRAINT `tbl_alasan_ibfk1_1` FOREIGN KEY (`id_mahasiswa`) REFERENCES `tbl_mahasiswa` (`id_mahasiswa`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `tbl_alasan` */

insert  into `tbl_alasan`(`id_alasan`,`id_mahasiswa`,`alasan`,`tanggal`) values 
(5,14,'urusan keluarga','2024-05-13'),
(6,14,'urusan keluarga','2024-05-13');

/*Table structure for table `tbl_kegiatan` */

DROP TABLE IF EXISTS `tbl_kegiatan`;

CREATE TABLE `tbl_kegiatan` (
  `id_kegiatan` int(15) NOT NULL AUTO_INCREMENT,
  `id_mahasiswa` int(15) DEFAULT NULL,
  `kegiatan` varchar(255) DEFAULT NULL,
  `waktu_awal` time DEFAULT NULL,
  `waktu_akhir` time DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  PRIMARY KEY (`id_kegiatan`),
  KEY `tbl_kegiatan_ibfk1_1` (`id_mahasiswa`),
  CONSTRAINT `tbl_kegiatan_ibfk1_1` FOREIGN KEY (`id_mahasiswa`) REFERENCES `tbl_mahasiswa` (`id_mahasiswa`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=163 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `tbl_kegiatan` */

insert  into `tbl_kegiatan`(`id_kegiatan`,`id_mahasiswa`,`kegiatan`,`waktu_awal`,`waktu_akhir`,`tanggal`) values 
(158,11,'Menyambungkan aplikasi absensi kegiatan mahasiswa ke PC admin','08:15:00','17:00:00','2024-05-13'),
(159,12,'Merevisi Design Aplikasi Absensi Kegiatan Mahasiswa','08:15:00','17:00:00','2024-05-13'),
(160,10,'Menyambungkan aplikasi absensi kegiatan mahasiswa ke PC admin','08:15:00','17:00:00','2024-05-13'),
(161,13,'Merevisi Frontend aplikasi pada Visual Studio Code','08:15:00','17:00:00','2024-05-13'),
(162,15,'Membantu revisi design Aplikasi Absensi Kegiatan Mahasiswa','08:00:00','17:00:00','2024-05-13');

/*Table structure for table `tbl_mahasiswa` */

DROP TABLE IF EXISTS `tbl_mahasiswa`;

CREATE TABLE `tbl_mahasiswa` (
  `id_mahasiswa` int(15) NOT NULL AUTO_INCREMENT,
  `kode_mahasiswa` varchar(4) DEFAULT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `universitas` varchar(255) DEFAULT NULL,
  `jurusan` varchar(255) DEFAULT NULL,
  `nim` varchar(255) DEFAULT NULL,
  `mulai_magang` date DEFAULT NULL,
  `akhir_magang` date DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `status` varchar(10) DEFAULT 'aktif',
  `no_telp` varchar(255) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_mahasiswa`),
  KEY `kode_mahasiswa` (`kode_mahasiswa`),
  CONSTRAINT `tbl_mahasiswa_ibfk_1` FOREIGN KEY (`kode_mahasiswa`) REFERENCES `tbl_user` (`kode_pengguna`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `tbl_mahasiswa` */

insert  into `tbl_mahasiswa`(`id_mahasiswa`,`kode_mahasiswa`,`nama`,`universitas`,`jurusan`,`nim`,`mulai_magang`,`akhir_magang`,`alamat`,`status`,`no_telp`,`foto`) values 
(10,'M010','Ahmad Izzu Azibi','Universitas Prima Indonesia','Teknik Informatika','213303030389','2024-03-13','2024-07-30','Jl.IDI Raya III No.9','aktif','083198106367','42149-medium.jpg'),
(11,'M011','Nadya Khairunisa Lubis','Universitas Prima Indonesia','Teknik Informatika','213303030271','2024-03-13','2024-08-11','Jl. Singgalang No.8/14 Medan','aktif','081260603449','nadya.jpg'),
(12,'M012','Tivanez Ballerina Abellista','Universitas Prima Indonesia','Sistem Informasi','213303040287','2024-03-13','2024-08-11','Jl. Abadi No.2','aktif','085642159616','tivanez.jpg'),
(13,'M013','Emy Priyanka Hutabarat','Universitas Prima Indonesia','Teknik Informatika','213303030319','2024-03-13','2024-07-30','Jl. Kangkung Gg. Buntu No.17','aktif','082286224686','WhatsApp Image 2024-05-13 at 08.45.57_616c0494.jpg'),
(14,'M014','Simon Perez Hutagaol','Universitas Prima Indonesia','Sistem Informasi','213303040292','2024-03-13','2024-08-11','Jl.Sampul No.2','aktif','082163368493','simon.jpg'),
(15,'M015','Juan Kevin Timothi Tarigan','Universitas Prima Indonesia','Teknik Informatika','213303030309','2024-03-13','2024-07-30','Jl. Binjai','aktif','085536373151','juand.png');

/*Table structure for table `tbl_setting_absensi` */

DROP TABLE IF EXISTS `tbl_setting_absensi`;

CREATE TABLE `tbl_setting_absensi` (
  `id_waktu` int(15) DEFAULT NULL,
  `mulai_absen` time DEFAULT NULL,
  `akhir_absen` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `tbl_setting_absensi` */

insert  into `tbl_setting_absensi`(`id_waktu`,`mulai_absen`,`akhir_absen`) values 
(1,'08:00:00','08:15:00');

/*Table structure for table `tbl_site` */

DROP TABLE IF EXISTS `tbl_site`;

CREATE TABLE `tbl_site` (
  `id_site` int(15) DEFAULT NULL,
  `nama_instansi` varchar(255) DEFAULT NULL,
  `pimpinan` varchar(255) DEFAULT NULL,
  `pembimbing` varchar(255) DEFAULT NULL,
  `no_telp` varchar(255) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `tbl_site` */

insert  into `tbl_site`(`id_site`,`nama_instansi`,`pimpinan`,`pembimbing`,`no_telp`,`alamat`,`website`,`logo`) values 
(1,'PT. Perkebunan Nusantara IV Reg. I','Hasanul Arifin Nasution, ST.,QIA','Rini Hardiyanti Surahman S.Kom','(061) 8452244','Jl. Sei Batang Hari No.2 Simpang Tj, Kec. Medan Sunggal, Kota Medan, Sumatera Utara 20122','http://www.ptpn3.co.id/','Logo_PTPN4.png');

/*Table structure for table `tbl_user` */

DROP TABLE IF EXISTS `tbl_user`;

CREATE TABLE `tbl_user` (
  `id_user` int(15) NOT NULL AUTO_INCREMENT,
  `kode_pengguna` varchar(4) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `level` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `kode_pengguna` (`kode_pengguna`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `tbl_user` */

insert  into `tbl_user`(`id_user`,`kode_pengguna`,`username`,`password`,`level`) values 
(1,'A001','Susi','827ccb0eea8a706c4c34a16891f84e7b','Admin'),
(2,'A002','Rini','e10adc3949ba59abbe56e057f20f883e','Admin'),
(3,'M001','062030701635','e10adc3949ba59abbe56e057f20f883e','Mahasiswa'),
(4,'M002','062030701636','e10adc3949ba59abbe56e057f20f883e','Mahasiswa'),
(5,'M003','062030701634','e10adc3949ba59abbe56e057f20f883e','Mahasiswa'),
(6,'M004','tivanez','e10adc3949ba59abbe56e057f20f883e','Mahasiswa'),
(7,'M005','ahmad','e10adc3949ba59abbe56e057f20f883e','Mahasiswa'),
(8,'M006','Emy Priyanka Hutabarat','e10adc3949ba59abbe56e057f20f883e','Mahasiswa'),
(9,'M007',NULL,NULL,NULL),
(10,'M008',NULL,NULL,NULL),
(14,'M009',NULL,NULL,NULL),
(15,'M010','izzu','e10adc3949ba59abbe56e057f20f883e','Mahasiswa'),
(16,'M011','nadya','fcea920f7412b5da7be0cf42b8c93759','Mahasiswa'),
(17,'M012','tivanez17','e10adc3949ba59abbe56e057f20f883e','Mahasiswa'),
(18,'M013','emy','e10adc3949ba59abbe56e057f20f883e','Mahasiswa'),
(19,'M014','simon','827ccb0eea8a706c4c34a16891f84e7b','Mahasiswa'),
(20,'M015','juan','827ccb0eea8a706c4c34a16891f84e7b','Mahasiswa');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
