<?php 
    //memulai session
    session_start();
    //Jika terdetesi ada variabel id_pengguna dalam session maka langsung arahkan ke halaman dashboard
    if  (isset($_SESSION["id_pengguna"])){
        session_unset();
        session_destroy();
    }
    //Variable pesan untuk menampilkan validasi login
    $pesan="";
    //Fungsi untuk mencegah inputan karakter yang tidak sesuai
    function input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
    }
    //Cek apakah ada kiriman form dari method post
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        //Menghubungkan database
        include "config/database.php";
        //Mengambil input username dan password dari form login
        $username = input($_POST["username"]);
        $password = input(md5($_POST["password"])); //hash yang dipakai md5
        //Query untuk cek tbl_user yang dijoinkan dengan table tbl_admin
        $tabel_admin= "SELECT * FROM tbl_user p
        INNER JOIN tbl_admin k ON k.kode_admin=p.kode_pengguna
        WHERE username='".$username."' and password='".$password."' LIMIT 1";
        $cek_tabel_admin = mysqli_query ($kon,$tabel_admin);
        $admin = mysqli_num_rows($cek_tabel_admin);
        //Query untuk cek pada tbl_user yang dijoinkan dengan table tbl_mahasiswa
        $tabel_mahasiswa= "SELECT * FROM tbl_user p
        INNER JOIN tbl_mahasiswa m ON m.kode_mahasiswa=p.kode_pengguna
        WHERE username='".$username."' and password='".$password."' LIMIT 1";
        $cek_tabel_mahasiswa = mysqli_query ($kon,$tabel_mahasiswa);
        $mahasiswa = mysqli_num_rows($cek_tabel_mahasiswa);
        // Kondisi jika pengguna merupakan admin
        if ($admin>0){
            $row = mysqli_fetch_assoc($cek_tabel_admin);
            $_SESSION["id_pengguna"]=$row["id_user"];
            $_SESSION["kode_pengguna"]=$row["kode_pengguna"];
            $_SESSION["nama_admin"]=$row["nama"];
            $_SESSION["username"]=$row["username"];
            $_SESSION["level"]=$row["level"];
            $_SESSION["nip"]=$row["nip"];
            //mengalihkan halaman ke page beranda
            header("Location:index.php?page=beranda");
        } else if ($mahasiswa>0){
            $row = mysqli_fetch_assoc($cek_tabel_mahasiswa);
            $_SESSION["id_pengguna"]=$row["id_user"];
            $_SESSION["kode_pengguna"]=$row["kode_pengguna"];
            $_SESSION["id_mahasiswa"]=$row["id_mahasiswa"];
            $_SESSION["nama_mahasiswa"]=$row["nama"];
            $_SESSION["username"]=$row["username"];
            $_SESSION["universitas"]=$row["universitas"];
            $_SESSION["level"]=$row["level"];
            $_SESSION["foto"]=$row["foto"];
            $_SESSION["nim"]=$row["nim"];
            //mengalihkan halaman ke page beranda
            header("Location:index.php?page=beranda");
        } else {
            //variable di buat terlebih dahulu
            $pesan="<div class='alert alert-danger'><strong>Error!</strong> Password Anda Salah.</div>";
        }
	}
?>

<!-- Mengambil Profil Aplikasi -->
<?php
    //Menghubungkan database
    include 'config/database.php';
    //Melakukan query untuk menampilkan table tbl_site
    $query = mysqli_query($kon, "select * from tbl_site limit 1");
    //Menyimpan hasil query    
    $row = mysqli_fetch_array($query);
    //Menyimpan nama instansi dari tbl_site
    $nama_instansi=$row['nama_instansi'];
    //Menyimpan nama logo dari tbl_site
    $logo=$row['logo'];
?>
<!-- Mengambil Profil Aplikasi -->


<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* POPPINS FONT */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');

        * {  
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: url("source/img/bg-login.jpg");
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            overflow: hidden;
        }

        .wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 110vh;
            background: rgba(39, 39, 39, 0.3);
        }

        .form-box {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 500px;
            height: 420px;
            overflow: hidden;
            z-index: 1;
        }

        .register-container, .login-container {
            position: absolute;
            top : 50px; 
            width: 70%;
            transition: .5s ease-in-out;
        }

        .register-container {
            left: 0;
            opacity: 0;
            z-index: 0;
        }

        .login-container {
            bottom: 20;
            opacity: 1;
            z-index: 1;
            padding: 40px;
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(15px);
            border-radius: 20px;
            width: 400px;
            display: flex;
            flex-direction: column;   
        }

        .top span {
            color: #fff;
            font-size: small;
        }

        .top header {
            color: #fff;
            font-size: 25px;
            font-weight: 600;
            text-align: center;
            margin-bottom: 20px;
        }

        .input-box {
            margin-bottom: 20px;
            position: relative;
        }

        .input-field {
            width: 100%;
            padding: 12px 15px;
            font-size: 16px;
            color: #fff;
            background: rgba(255, 255, 255, 0.3);
            border: none;
            border-radius: 20px;
            outline: none;
            transition: .2s ease;
        }

        .input-field:hover, .input-field:focus {
            background: rgba(255, 255, 255, 0.25);
        }

        ::-webkit-input-placeholder {
            color: #fff;
        }

        .input-box i {
            position: relative;
            top: -35px;
            left: 17px;
            color: #fff;
        }

        .submit {
            font-size: 15px;
            font-weight: 500;
            color: black;
            height: 45px;
            width: 100%;
            border: none;
            border-radius: 30px;
            outline: none;
            background: rgba(255, 255, 255, 0.5);
            cursor: pointer;
            transition: .3s ease-in-out;
        }

        .submit:hover {
            background: rgba(255, 255, 255, 0.5);
            box-shadow: 1px 5px 7px 1px rgba(0, 0, 0, 0.2);
        }

        .two-col {
            display: flex;
            justify-content: space-between;
            color: #fff;
            font-size: small;
            margin-top: 10px;
        }

        .two-col .one {
            display: flex;
            gap: 5px;
        }

        .two-col label a {
            text-decoration: none;
            color: #fff;
        }

        .two-col label a:hover {
            text-decoration: underline;
        }

        @media only screen and (max-width: 786px) {
            .nav-button {
                display: none;
            }

            .nav-menu.responsive {
                top: 100px;
            }

            .nav-menu {
                position: absolute;
                top: -800px;
                display: flex;
                justify-content: center;
                background: rgba(255, 255, 255, 0.2);
                width: 100%;
                height: 90vh;
                backdrop-filter: blur(20px);
                transition: .3s;
            }

            .nav-menu ul {
                flex-direction: column;
                text-align: center;
            }

            .nav-menu-btn {
                display: block;
            }

            .nav-menu-btn i {
                font-size: 25px;
                color: #fff;
                padding: 10px;
                background: rgba(255, 255, 255, 0.2);
                border-radius: 50%;
                cursor: pointer;
                transition: .3s;
            }

            .nav-menu-btn i:hover {
                background: rgba(255, 255, 255, 0.15);
            }
        }

        @media only screen and (max-width: 540px) {
            .wrapper {
                min-height: 100vh;
            }

            .form-box {
                width: 100%;
                height: 500px;
            }

            .register-container, .login-container {
                width: 100%;
                height: 55%;
                padding: 0 20px;
            }

            .register-container .two-forms {
                flex-direction: column;
                gap: 0;
            }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="form-box">
            <div class="login-container" id="login">
                <div class="top">
                    <header>ABSENSI DAN KEGIATAN</header>
                </div>
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                    <div class="input-box">
                        <input type="text" class="input-field" name="username" id="username" placeholder="Enter email" required>
                    </div>
                    <div class="input-box">
                        <input type="password" class="input-field" name="password" id="password" placeholder="Enter password" required>
                    </div>
                    <button type="submit" class="submit">Login</button>
                    <div class="two-col">
                      <!--  <label><a href="#">Forgot password?</a></label>
                        <label><a href="#">Create Account</a></label>-->
                    </div>
                    <?php
                    // Display error message if any
                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                        echo $pesan;
                    }
                    ?>
                </form>
            </div>
        </div>
    </div>
</body>
</html>