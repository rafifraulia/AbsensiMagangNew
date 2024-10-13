<?php
include '../../config/database.php';

$id_mahasiswa = $_POST['id_mahasiswa'];
$tanggal = $_POST['tanggal'];

// Query untuk mendapatkan alasan izin berdasarkan id_mahasiswa dan tanggal
$query = "SELECT alasan FROM tbl_alasan WHERE id_mahasiswa = '$id_mahasiswa' AND tanggal = '$tanggal'";
$result = mysqli_query($kon, $query);
$data = mysqli_fetch_assoc($result);

// Tampilkan alasan izin jika ditemukan
if ($data) {
    echo '<p>Alasan Izin: ' . $data['alasan'] . '</p>';
} else {
    echo '<p>Tidak ada alasan izin untuk tanggal ini.</p>';
}
?>
