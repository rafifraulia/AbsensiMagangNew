<?php
    session_start();
    if (!$_SESSION["kode_pengguna"]) {
        header("Location:login.php");
    } else {
        include 'config/database.php';
        $kode_pengguna = $_SESSION["kode_pengguna"];
        $username = $_SESSION["username"];
        $hasil = mysqli_query($kon,"select username from tbl_user where kode_pengguna='$kode_pengguna'");
        $data = mysqli_fetch_array($hasil);
        $username_db = $data['username'];
        if ($username != $username_db) {
            session_unset();
            session_destroy();
            header("Location:login.php");
        }
    }
?>

<?php
    include 'config/database.php';
    $query = mysqli_query($kon, "SELECT * FROM tbl_site LIMIT 1");    
    $row = mysqli_fetch_array($query);
    $nama_instansi = $row['nama_instansi'];
    $logo = $row['logo'];
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="apps/pengaturan/logo/<?php echo $logo; ?>">
    <title><?php echo $nama_instansi; ?></title>
    <link href="template/css/bootstrap.min.css" rel="stylesheet">
    <link href="template/css/font-awesome.min.css" rel="stylesheet">
    <link href="template/css/datepicker3.css" rel="stylesheet">
    <link href="template/css/styles.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/jquery-ui.css">
    <script src="template/js/jquery-2.2.3.min.js"></script>
    <script src="template/js/jquery-1.11.1.min.js"></script>
    <link href="src/font/font.css" rel="stylesheet" type="text/css">
    <style>
        /* Efek kaca pada sidebar */
        .sidebar {
            background: #325434; /* Gradien hijau */
            backdrop-filter: blur(10px);
            color: #fff;
            height: 100vh;
            overflow-y: auto;
            transition: all 0.3s;
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            display: flex;
            flex-direction: column;
        }

        .sidebar.closed {
            width: 80px;
        }

        /* Navigasi di sidebar */
        .sidebar .nav > li > a {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            color: #fff;
            text-decoration: none;
        }
        .sidebar .nav > li > a i {
            margin-right: 10px;
        }
        .sidebar .nav > li > a .label-text {
            display: inline;
        }
        .sidebar.closed .nav > li > a .label-text {
            display: none;
        }

        /* Tombol toggle di sidebar */
        .toggle-btn {
            font-size: 25px;
            color: #fff;
            background: transparent;
            border: none;
            cursor: pointer;
    
            position: absolute;
            
            left: 20px;
            z-index: 1000;
            transition: background 0.3s;
        }
        .toggle-btn:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        /* Profil di sidebar */
        .profile-sidebar {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 80px 0 20px 0;
            text-align: center;
            position: relative;
        }
        .profile-pic {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 10px;
            border: 2px solid #fff;
        }
        .profile-sidebar span {
            color: #fff;
            font-size: 16px;
            font-weight: bold;
        }

        /* Sesuaikan ukuran foto profil saat sidebar diminimized */
        .sidebar.closed .profile-pic {
            width: 30px;
            height: 30px;
        }

        /* Sembunyikan nama dan level di profil saat sidebar diminimized */
        .sidebar.closed .profile-sidebar span,
        .sidebar.closed .profile-usertitle-name {
            display: none;
        }

        /* Konten utama */
        .main-content {
            margin-left: 250px;
            margin-top: 0px; /* Tambahkan margin top untuk menyesuaikan dengan bar */
            transition: margin-left 0.3s ease;
        }
        .sidebar.closed ~ .main-content {
            margin-left: 80px;
        }
        /* Styling untuk menu navigasi */
        .nav.menu {
            list-style-type: none; /* Menghilangkan bullet */
            padding: 0; /* Menghilangkan padding default */
            margin: 0; /* Menghilangkan margin default */
            background: #325434; /* Gradien hijau */
            border-radius: 5px; /* Sudut membulat */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2); /* Bayangan untuk efek kedalaman */
        }

        /* Styling untuk setiap item menu */
        .nav.menu li {
            margin: 10px 0; /* Memberikan jarak atas dan bawah sebesar 10px */
            transition: background-color 0.3s ease; /* Transisi latar belakang */
        }

        /* Styling untuk setiap link di dalam menu */
        .nav.menu li a {
            display: flex; /* Flexbox untuk menyejajarkan ikon dan teks */
            align-items: center; /* Vertikal center */
            padding: 15px 20px; /* Padding untuk ruang dalam, memberikan jarak di dalam setiap item menu */
            color: #fff; /* Warna teks */
            text-decoration: none; /* Menghilangkan garis bawah */
            font-size: 16px; /* Ukuran font */
        }
        .sidebar ul.nav li a {
            color: #ffffff; 
        }
        /* Hover effect untuk item menu */
        .nav.menu li:hover {
            background-color: #6e896a; /* Warna latar belakang saat hover */
            transition: background-color 0.3s ease; /* Transisi smooth saat hover */
        }
        .sidebar ul.nav li a:hover {
            background-color: #6e896a;
        }

        /* Mengatur ikon */
        .nav.menu li a i {
            margin-right: 10px; /* Jarak antara ikon dan teks */
            margin-left: 7px;
            font-size: 20px; /* Ukuran ikon */
        }

        /* Styling untuk teks label */
        .label-text {
            flex-grow: 1; /* Memperluas ruang teks */
        }

        /* Mengatur warna link yang aktif */
        .nav.menu li a.active {
            background-color: rgba(255, 255, 255, 0.2); /* Warna latar belakang untuk link aktif */
            font-weight: bold; /* Mengatur teks menjadi tebal */
        }

        /* Media Query untuk responsif */
        
        .page-title-bar {
            display: flex;
            align-items: center;
            justify-content: space-between; /* Membuat toggle di kiri dan profil di kanan */
            padding: 15px 20px; /* Tambahkan padding agar tidak terlalu ke tepi */
            background-color: #fff;
            color: #fff;
            position: fixed;
            width: calc(100% - 250px); /* Menghindari sidebar overlap, hitung lebar tanpa sidebar */
            top: 0;
            left: 250px; /* Menjaga posisi agar tidak tertutup sidebar */
            z-index: 999;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2); /* Memberikan efek bayangan */
            transition: left 0.3s ease; /* Smooth transition saat sidebar di-minimize */
        }

        .page-title-bar .toggle-btn {
            font-size: 25px;
            color: #0f0f0d;
            background: transparent;
            border: none;
            cursor: pointer;
            margin-right: 20px; /* Jarak antara tombol dan judul */
            transition: background 0.3s;
        }

        #page-title {
            font-size: 25px;
            font-weight: bold;
            margin-left: 60px;
            margin-bottom: 23px;
        }
        .page-title-bar .toggle-btn:hover {
            background: rgba(255, 255, 255, 0.3);
        }
        .sidebar.closed ~ #page-title-bar {
            left: 80px; /* Sesuaikan dengan lebar sidebar yang diperkecil */
            width: calc(100% - 80px);
        }
        .profile-topbar {
            display: flex;
            align-items: center; /* Vertikal center antara foto dan nama */
            position: relative; /* Gunakan relative untuk fleksibilitas dalam flexbox */
        }

        .profile-pic-topbar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 10px; /* Jarak antara foto dan info */
        }

        /* Info nama dan level profil */
        .profile-info {
            display: flex;
            flex-direction: column; /* Nama dan level vertikal */
        }

        .profile-name {
            font-size: 16px;
            font-weight: bold;
            color: #0f0f0d;
        }

        .profile-level {
            font-size: 14px;
            color: #0f0f0d;
        }
        .sidebar-header {
            display: flex;
            align-items: center;
            padding: 15px 13px; /* Jarak padding untuk memberikan ruang */
            background-color: #325434;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2); /               /* Menyesuaikan tinggi otomatis */
        }

        /* Logo dalam sidebar */
        .sidebar-logo {
            width: 50px;
            height: 50px;
            object-fit: cover; /* Menjaga aspek rasio logo */
            margin-right: 40px; /* Jarak antara logo dan teks */
        }

        /* Teks "PTPN IV" */
        .sidebar-text {
            font-size: 25px;
            font-weight: bold;
            color: #fff;
            white-space: nowrap; /* Mencegah teks menjadi terpotong */
            transition: opacity 0.3s ease; /* Transisi smooth untuk menghilangkan teks */
            margin-top : 10px;
        }

        /* Saat sidebar di-closed, sembunyikan teks "PTPN IV" */
        .sidebar.closed .sidebar-text {
            opacity: 0; /* Menghilangkan teks secara visual */
            transition: opacity 0.3s ease;
        }

        /* Atur lebar sidebar yang minim */
        .sidebar.closed .sidebar-logo {
            margin-right: 0; /* Menghilangkan jarak logo saat sidebar kecil */
        }
        .profile-sidebar {
            padding-top: 20px; /* Memberikan ruang setelah header logo */
        }
        @media (max-width: 768px) {
            .page-title-bar {
                padding: 8px 10px; /* Padding lebih kecil untuk mobile */
                width: 100%; /* Lebar penuh pada mobile */
                left: 250px; /* Mengatur posisi left untuk mobile */
                z-index: 999; /* Pastikan berada di atas */
            }
            
            .sidebar {
                position: fixed; /* Tetap di posisi fixed */
                left: 0; /* Menjaga sidebar tetap di kiri */
                top: 0; /* Menjaga sidebar tetap di atas */
                height: 100vh; /* Tinggi penuh */
                width: 250px; /* Pastikan sidebar memiliki lebar yang jelas */
                z-index: 9999; /* Z-index lebih tinggi agar sidebar selalu di atas */
                transition: transform 0.3s ease; /* Transisi saat membuka/tutup */
            }

            .sidebar.closed {
                transform: translateX(-100%); /* Menyembunyikan sidebar dengan gerakan ke kiri */
            }

            .main-content {
                margin-left: 0; /* Menghilangkan margin pada konten utama */
                margin-top: 0px; /* Jaga jarak dengan page title bar */
                padding: 10px; /* Tambahkan padding pada konten utama untuk ruang */
                z-index: 1; /* Z-index lebih rendah daripada sidebar */
            }

            .sidebar.closed ~ .main-content {
                margin-left: 0px; /* Kembali ke margin untuk main content saat sidebar terbuka */
            }
            .sidebar.closed ~ #page-title-bar {
                left: 0px;!important;
                width: 100%;!important;
            }
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div id="sidebar" class="sidebar">
       <!-- Logo Website -->
           <div class="sidebar-header">
        <img src="Vector_PTPN4.png" alt="Logo" class="sidebar-logo"> <!-- Ganti path ke logo -->
        <span class="sidebar-text">PTPN IV</span>
    </div>
        <!-- Menu navigasi -->
        <ul class="nav menu">
            <li><a href='index.php?page=beranda'><i class='fa fa-home'></i> <span class="label-text">Beranda</span></a></li>
            <?php if ($_SESSION["level"] == "Admin" or $_SESSION['level'] == 'admin'): ?>
                <li><a href="index.php?page=mahasiswa" id="mahasiswa"><i class="fa fa-users"></i> <span class="label-text">Data Mahasiswa</span></a></li>
                <li><a href="index.php?page=data_absensi" id="data_absensi"><i class="fa fa-calendar"></i> <span class="label-text">Data Absensi</span></a></li>
                <li><a href="index.php?page=data_kegiatan" id="kegiatan"><i class="fa fa-book"></i> <span class="label-text">Data Kegiatan</span></a></li>
                <li><a href="index.php?page=admin" id="admin"><i class="fa fa-user"></i> <span class="label-text">Administrator</span></a></li>
                <li><a href="index.php?page=pengaturan" id="pengaturan"><i class="fa fa-gear"></i> <span class="label-text">Pengaturan</span></a></li>
            <?php endif; ?>
            <?php if ($_SESSION["level"] == "Mahasiswa" or $_SESSION["level"] == "mahasiswa"): ?>
                <li><a href="index.php?page=absen"><i class="fa fa-calendar-check-o"></i> <span class="label-text">Absensi</span></a></li>
                <li><a href="index.php?page=riwayat"><i class="fa fa-history"></i> <span class="label-text">Riwayat Absensi</span></a></li>
                <li><a href="index.php?page=kegiatan"><i class="fa fa-book"></i> <span class="label-text">Kegiatan Harian</span></a></li>
                <li><a href="index.php?page=profil"><i class="fa fa-user-circle-o"></i> <span class="label-text">Profil</span></a></li>
            <?php endif; ?>
            <li><a href="logout.php" id="keluar"><i class="fa fa-sign-out"></i> <span class="label-text">Keluar</span></a></li>
        </ul>
    </div>

    <!-- Bar Judul Page -->
    <div id="page-title-bar" class="page-title-bar">
        <button class="toggle-btn" onclick="toggleSidebar()">
            <i class="fa fa-bars"></i>
        </button> <!-- Ikon burger -->

        <h3 id="page-title">Beranda</h3> <!-- Default judul page -->

        <!-- Profil pengguna di topbar -->
        <div class="profile-topbar">
            <?php if ($_SESSION['level'] == 'Admin' or $_SESSION['level'] == 'admin'): ?>
                <img src="source/img/profile.png" class="profile-pic-topbar" alt="Profile Admin"> <!-- Foto profil admin -->
                <div class="profile-info">
                    <span class="profile-name"><?php echo substr($_SESSION['nama_admin'], 0, 20); ?></span> <!-- Nama admin -->
                    <span class="profile-level">Administrator</span> <!-- Level admin -->
                </div>
            <?php elseif ($_SESSION['level'] == 'Mahasiswa' or $_SESSION['level'] == 'mahasiswa'): ?>
                <img src="apps/mahasiswa/foto/<?php echo $_SESSION['foto']; ?>" class="profile-pic-topbar" alt="Profile Mahasiswa"> <!-- Foto profil mahasiswa -->
                <div class="profile-info">
                    <span class="profile-name"><?php echo substr($_SESSION['nama_mahasiswa'], 0, 30); ?></span> <!-- Nama mahasiswa -->
                    <span class="profile-level">Mahasiswa</span> <!-- Level mahasiswa -->
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Konten Utama -->
    <div class="main-content">
        <?php 
            if (isset($_GET['page'])) {
                $page = $_GET['page'];
                switch ($page) {
                    case 'beranda':
                        include "apps/beranda/index.php";
                        break;
                    case 'admin':
                        include "apps/admin/index.php";
                        break;
                    case 'mahasiswa':
                        include "apps/mahasiswa/index.php";
                        break;
                    case 'data_absensi':
                        include "apps/data_absensi/index.php";
                        break;
                    case 'data_kegiatan':
                        include "apps/data_kegiatan/index.php";
                        break;
                    case 'pengaturan':
                        include "apps/pengaturan/index.php";
                        break;
                    case 'absen':
                        include "apps/pengguna/absen.php";
                        break;
                    case 'riwayat':
                        include "apps/data_absensi/riwayat.php";
                        break;
                    case 'kegiatan':
                        include "apps/data_kegiatan/kegiatan.php";
                        break;
                    case 'profil':
                        include "apps/pengguna/profil.php";
                        break;
                    default:
                        echo "<center><h3>Maaf. Halaman tidak di temukan !</h3></center>";
                        break;
                }
            } else {
                include "apps/beranda/index.php";
            }
        ?>
    </div>
<!-- Java Script -->
<script src="template/js/bootstrap.min.js"></script>
<script src="template/js/chart.min.js"></script>
<script src="template/js/chart-data.js"></script>
<script src="template/js/easypiechart.js"></script>
<script src="template/js/easypiechart-data.js"></script>
<script src="template/js/bootstrap-datepicker.js"></script>
<script src="template/js/custom.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.2/css/buttons.dataTables.min.css">
<script src="/assets/chart/chart.js"></script>
<!-- Java Script -->
    <!-- Script untuk toggle sidebar -->
    <script>
        // Fungsi untuk mengubah judul page sesuai halaman yang dibuka
        function updatePageTitle(title) {
            document.getElementById('page-title').textContent = title;
        }

        // Update title berdasarkan page yang dibuka
        document.addEventListener("DOMContentLoaded", function() {
            var page = new URLSearchParams(window.location.search).get('page');
            var titleMap = {
                'beranda': 'Beranda',
                'admin': 'Administrator',
                'mahasiswa': 'Data Mahasiswa',
                'data_absensi': 'Data Absensi',
                'data_kegiatan': 'Data Kegiatan',
                'pengaturan': 'Pengaturan',
                'absen': 'Absensi',
                'riwayat': 'Riwayat Absensi',
                'kegiatan': 'Kegiatan Harian',
                'profil': 'Profil Pengguna'
            };
            
            var title = titleMap[page] || 'Beranda';
            updatePageTitle(title);
        });

        // Script untuk toggle sidebar
        function toggleSidebar() {
            document.getElementById("sidebar").classList.toggle("closed");
            document.getElementById("page-title-bar").classList.toggle("closed");
        }

    </script>
</body>
</html>