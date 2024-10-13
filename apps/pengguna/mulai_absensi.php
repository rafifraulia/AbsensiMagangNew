<?php
    session_start();
    if (isset($_POST['submit'])) {
        include '../../config/database.php';
        function input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        $id_mahasiswa = $_SESSION["id_mahasiswa"];
        $status = $_POST["status"];
        date_default_timezone_set("Asia/Jakarta");
        $tanggal = date("Y-m-d");
        $waktu = date("H:i:s");
        $alasan = isset($_POST["alasan"]) ? $_POST["alasan"] : null;
        $selfie = isset($_POST['selfie']) ? $_POST['selfie'] : null;

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $cek_waktu = "SELECT CONCAT(CURDATE(), ' ', mulai_absen) as mulai_absen, CONCAT(CURDATE(), ' ', akhir_absen) as akhir_absen, NOW() as waktu_sekarang FROM tbl_setting_absensi LIMIT 1;";
            $query = mysqli_query($kon, $cek_waktu);
            $setting = mysqli_fetch_array($query);
            $mulai_absen = $setting["mulai_absen"];
            $akhir_absen = $setting["akhir_absen"];
            $waktu_sekarang = $setting["waktu_sekarang"];

            if ($waktu_sekarang >= $mulai_absen && $waktu_sekarang <= $akhir_absen) {
                if ($status == "1" && $selfie) {
                    $selfie = str_replace('data:image/png;base64,', '', $selfie);
                    $selfie = str_replace(' ', '+', $selfie);
                    $selfie_data = base64_decode($selfie);

                    $file_name = 'selfie_' . $id_mahasiswa . '_' . $tanggal . '.png';
                    $file_path = '../../uploads/selfies/' . $file_name;

                    // Simpan file gambar
                    if (file_put_contents($file_path, $selfie_data)) {
                        $sql = "INSERT INTO tbl_absensi (id_mahasiswa, status, waktu, tanggal, selfie) VALUES ('$id_mahasiswa', '$status', '$waktu', '$tanggal', '$file_name')";
                    } else {
                        echo "Gagal menyimpan gambar.";
                    }
                } else {
                    $sql = "INSERT INTO tbl_absensi (id_mahasiswa, status, waktu, tanggal) VALUES ('$id_mahasiswa', '$status', '$waktu', '$tanggal')";
                }
                
                $simpan_absensi = mysqli_query($kon, $sql);
            }

            if ($status == "2") {
                $sql = "INSERT INTO tbl_alasan (id_mahasiswa, alasan, tanggal) VALUES ('$id_mahasiswa', '$alasan', '$tanggal')";
                $simpan_izin = mysqli_query($kon, $sql);
            }

            if ($simpan_absensi && $simpan_izin) {
                mysqli_query($kon, "COMMIT");
                header("Location:../../index.php?page=absen&mulai=berhasil");
            } else {
                mysqli_query($kon, "ROLLBACK");
                header("Location:../../index.php?page=absen&mulai=gagal");
            }
        }
    }
?>

<?php
    $id_mahasiswa = $_SESSION["id_mahasiswa"];
    $nama_mahasiswa = $_SESSION["nama_mahasiswa"];
    $tanggal = date("Y-m-d");
    include '../../config/database.php';
    $query = mysqli_query($kon, "SELECT mulai_magang, akhir_magang FROM tbl_mahasiswa WHERE id_mahasiswa=$id_mahasiswa;");
    $periode = mysqli_fetch_array($query);
    $tanggal_masuk = $periode["mulai_magang"];
    $tanggal_keluar = $periode["akhir_magang"];
?>

<?php
    $tanggal_sekarang = date("Y-m-d");
    $query = "SELECT COUNT(*) FROM tbl_absensi WHERE tanggal = '$tanggal_sekarang' AND id_mahasiswa = '$id_mahasiswa'";
    $result = mysqli_query($kon, $query);
    $data = mysqli_fetch_assoc($result);
    if ($data['COUNT(*)'] > 0) {
        $absensi_sudah = "";
    } else {
        $absensi_sudah = "";
    }
?>

<form action="apps/pengguna/mulai_absensi.php" method="post" enctype="multipart/form-data">
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label>Status :</label>
                <select class="form-control" id="status" name="status" required>
                    <option value="">Pilih</option>
                    <option value="1">Hadir</option>
                    <option value="2">Izin</option>
                    <option value="3">Tidak Hadir</option>
                </select>
            </div>
        </div>

        <div class="col-sm-6" id="text_alasan" style="display:none;">
            <div class="form-group">
                <label>Alasan :</label>
                <input type="text" name="alasan" id="alasan" class="form-control" value="" placeholder="Masukkan Alasan Kenapa Izin?">
            </div>
        </div>
    </div>

    <!-- Status Lokasi -->
    <div id="lokasi-status-container" style="display:none;">
        <label>Status Lokasi:</label>
        <p id="lokasi-status"></p>
    </div>

    <!-- Kamera dan Hasil Selfie -->
    <div id="video-container" style="display:none;">
    <label>Ambil Selfie:</label>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    
                    <video id="video" width="320" height="240" autoplay></video>
                    <br>
                    <button type="button" id="ambilGambar" class="btn btn-primary">Ambil Gambar</button>
                </div>
            </div>
            <div class="col-sm-6">
                <img id="selfieImage" style="display:none;" width="320" height="240">
            </div>
        </div>
    </div>
    <input type="hidden" name="selfie" id="selfieInput">
    <div class="row">
        <div class="col-sm-4">
            <div class="form-group">
                <br>
                <button type="submit" name="submit" id="tombol_hari" class="simpan_absensi btn btn-primary" disabled><i class="fa fa-clock-o"></i> Absensi</button>
            </div>
        </div>
    </div>
</form>

<script>
$(document).ready(function() {
    var video = document.getElementById('video');
    var selfieImage = document.getElementById('selfieImage');
    var canvas = document.createElement('canvas');
    var context = canvas.getContext('2d');
    var gambar_diambil = false;
    var lokasi_sesuai = false;
    var lokasi_diperiksa = true;
    var latitudeKantor = 3.5869784468828456;  // Koordinat latitude kantor
    var longitudeKantor = 98.64408850533962;  // Koordinat longitude kantor
    var radiusToleransi = 0.01;  // Radius toleransi dalam derajat (kurang lebih 1 km)

    // Fungsi untuk menghitung jarak antara dua koordinat
    function hitungJarak(lat1, lon1, lat2, lon2) {
        var R = 6371; // Radius bumi dalam kilometer
        var dLat = (lat2 - lat1) * Math.PI / 180;
        var dLon = (lon2 - lon1) * Math.PI / 180;
        var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                Math.sin(dLon / 2) * Math.sin(dLon / 2);
        var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        var jarak = R * c; // Jarak dalam kilometer
        return jarak;
    }

    // Dapatkan lokasi pengguna
    function cekLokasi() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var latitudePengguna = position.coords.latitude;
                var longitudePengguna = position.coords.longitude;
                
                // Hitung jarak dari kantor
                var jarak = hitungJarak(latitudeKantor, longitudeKantor, latitudePengguna, longitudePengguna);

                if (jarak <= radiusToleransi) {
                    lokasi_sesuai = true;
                    $('#lokasi-status').text("Lokasi sesuai").css('color', 'green');
                } else {
                    lokasi_sesuai = false;
                    $('#lokasi-status').text("Lokasi tidak sesuai").css('color', 'red');
                }
                cekSyarat();  // Cek syarat setelah pengecekan lokasi
            });
        } else {
            $('#lokasi-status').text("Geolocation tidak didukung oleh browser ini.").css('color', 'red');
            lokasi_sesuai = false;
        }
    }

    // Fungsi untuk mengecek apakah syarat terpenuhi
    function cekSyarat() {
        var status = $('#status').val();
        if (status == '1' && gambar_diambil && (!lokasi_diperiksa || lokasi_sesuai)) {  // "1" adalah nilai untuk "Hadir"
            $('#tombol_hari').prop('disabled', false);
        } else if (status == '2' || status == '3') {  // "2" adalah Izin, "3" adalah Tidak Hadir
            $('#tombol_hari').prop('disabled', false);  // Aktifkan tombol absensi
        } else {
            $('#tombol_hari').prop('disabled', true);
        }
    }

    // Ketika pengguna memilih status
    $('#status').change(function() {
        var status = $(this).val();
        if (status == '1') {  // Status Hadir
            $('#video-container').show();
            $('#lokasi-status-container').show();  // Tampilkan status lokasi
            $('#text_alasan').hide();  // Sembunyikan alasan
            cekLokasi(); // Cek lokasi hanya jika pengguna memilih "Hadir"
            
            navigator.mediaDevices.getUserMedia({ video: true })
            .then(function(stream) {
                video.srcObject = stream;
            })
            .catch(function(err) {
                console.log("Error: " + err);
            });
        } else if (status == '2') {  // Status Izin
            $('#text_alasan').show();  // Tampilkan input alasan
            $('#video-container').hide();
            $('#lokasi-status-container').hide();
            gambar_diambil = false;
            lokasi_sesuai = false;
            cekSyarat();
        } else {
            $('#video-container').hide();
            $('#lokasi-status-container').hide();  // Sembunyikan status lokasi
            $('#text_alasan').hide();  // Sembunyikan alasan jika bukan Izin
            gambar_diambil = false;
            lokasi_sesuai = false;
            cekSyarat();  // Nonaktifkan tombol absensi hanya jika syarat tidak terpenuhi
        }
    });

    // Ketika pengguna mengambil gambar selfie
    $('#ambilGambar').click(function() {
        // Sesuaikan ukuran canvas dengan video
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;

        // Ambil gambar dari video dan tampilkan di canvas
        context.drawImage(video, 0, 0, canvas.width, canvas.height);
        var imageData = canvas.toDataURL('image/png');
        
        // Tampilkan hasil gambar di elemen img
        selfieImage.src = imageData;
        selfieImage.style.display = 'block';  // Tampilkan gambar hasil selfie

        $('#selfieInput').val(imageData);
        gambar_diambil = true;
        cekSyarat();
    });

    // Sembunyikan kamera, status lokasi, dan hasil selfie secara default
    $('#video-container').hide();
    $('#lokasi-status-container').hide();
});
</script>





