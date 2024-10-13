<?php
session_start();
if (isset($_POST['submit'])) {
    // Include file koneksi, untuk menghubungkan ke database
    include '../../config/database.php';

    // Fungsi untuk membersihkan input
    function input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    // Mengambil data dari form absensi
    $id_mahasiswa = $_SESSION["id_mahasiswa"];
    $status = input($_POST["status"]);
    date_default_timezone_set("Asia/Jakarta");
    $tanggal = date("Y-m-d");
    $waktu = date("H:i:s");
    $alasan = isset($_POST["alasan"]) ? input($_POST["alasan"]) : '';

    // Cek apakah ada kiriman form dari method POST
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Cek rentang waktu absensi
        $cek_waktu = "SELECT CONCAT(CURDATE(), ' ', mulai_absen) as mulai_absen, 
                             CONCAT(CURDATE(), ' ', akhir_absen) as akhir_absen, 
                             NOW() as waktu_sekarang 
                      FROM tbl_setting_absensi LIMIT 1;";
        $query = mysqli_query($kon, $cek_waktu);
        $setting = mysqli_fetch_array($query);
        $mulai_absen = $setting["mulai_absen"];
        $akhir_absen = $setting["akhir_absen"];
        $waktu_sekarang = $setting["waktu_sekarang"];

        // Validasi apakah waktu sekarang sesuai dengan rentang waktu absensi
        if ($waktu_sekarang >= $mulai_absen && $waktu_sekarang <= $akhir_absen) {
            // Menambahkan data ke tabel absensi
            $sql_absen = "INSERT INTO tbl_absensi (id_mahasiswa, status, waktu, tanggal) 
                          VALUES ('$id_mahasiswa', '$status', '$waktu', '$tanggal')";
            $simpan_absensi = mysqli_query($kon, $sql_absen);

            // Jika status izin, simpan juga ke tabel alasan
            if ($status == "2") {
                $sql_izin = "INSERT INTO tbl_alasan (id_mahasiswa, alasan, tanggal) 
                             VALUES ('$id_mahasiswa', '$alasan', '$tanggal')";
                $simpan_izin = mysqli_query($kon, $sql_izin);
            } else {
                $simpan_izin = true; // Jika bukan izin, set true agar tidak error
            }

            // Commit atau rollback sesuai hasil penyimpanan
            if ($simpan_absensi && $simpan_izin) {
                mysqli_query($kon, "COMMIT");
                header("Location:../../index.php?page=absen&mulai=berhasil");
                exit;
            } else {
                mysqli_query($kon, "ROLLBACK");
                header("Location:../../index.php?page=absen&mulai=gagal");
                exit;
            }
        } else {
            // Jika di luar rentang waktu absensi, tampilkan pesan gagal
            header("Location:../../index.php?page=absen&mulai=gagal_waktu");
            exit;
        }
    }
}
?>

