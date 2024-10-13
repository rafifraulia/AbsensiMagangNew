<?php
include '../../config/database.php';

$id_mahasiswa = $_POST['id_mahasiswa'];
$tanggal = $_POST['tanggal'];

// Tentukan lokasi gambar selfie berdasarkan id_mahasiswa dan tanggal
// Gunakan URL penuh, bukan path relatif
$path_selfie = 'http://localhost/Aplikasi_Absensi_dan_Kegiatan_Harian_Mahasiswa_Magang-main/uploads/selfies/selfie_' . $id_mahasiswa . '_' . $tanggal . '.png';

// Periksa apakah file selfie ada
if (file_exists('../../uploads/selfies/selfie_' . $id_mahasiswa . '_' . $tanggal . '.png')) {
    //echo '<p>Path selfie: ' . $path_selfie . '</p>';
    echo '<img src="' . $path_selfie . '" alt="Selfie Mahasiswa" class="img-fluid">';
} else {
    echo '<p>Gambar selfie tidak ditemukan untuk tanggal ini.</p>';
    echo '<p>Path yang dicari: ' . $path_selfie . '</p>';
}
?>

