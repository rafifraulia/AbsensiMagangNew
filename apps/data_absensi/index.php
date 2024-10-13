<?php 
    if ($_SESSION["level"] != 'Admin' and $_SESSION["level"] != 'admin') {
        echo "<br><div class='alert alert-danger'>Tidak Memiliki Hak Akses</div>";
        exit;
    }
?>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
            </div>
            <div class="panel-body">
                <div class="row">
                    <form action="#" method="GET">
                        <input type="hidden" name="page" value="data_absensi"/>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Nama Mahasiswa :</label>
                                <input type="text" name="nama" id="nama" class="form-control" value="" placeholder="Cari Mahasiswa" required>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Tanggal Awal :</label>
                                <input type="date" name="tanggal_awal" id="tanggal_awal" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Tanggal Akhir :</label>
                                <input type="date" name="tanggal_akhir" id="tanggal_akhir" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <br>
                                <button type="submit" class="btn btn-info"><i class="fa fa-search"></i> Cari</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div><!--/.row-->

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-body">

                <div class="form-group">
                    <button type="button" class="btn btn-success" id="tambah_absensi"><i class="tambah_absensi fa fa-plus"></i> Absensi</button>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Universitas</th>
                                <th>Status</th>
                                <th>Waktu</th>
                                <th>Hari</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                        <?php
                            include 'config/database.php';
                            include 'config/function.php';
                            if (isset($_GET['nama']) AND $_GET['nama'] != "") {
                                $nama = trim($_GET["nama"]);
                                $tanggal_awal = $_GET["tanggal_awal"];
                                $tanggal_akhir = $_GET["tanggal_akhir"];
                                $sql = PencarianAbsensi($nama, $tanggal_awal, $tanggal_akhir);
                            } else {
                                $sql = AbsensiOtomatis('');
                            }                            
                            $hasil = mysqli_query($kon, $sql);
                            $no = 0;
                            while ($data = mysqli_fetch_array($hasil)):
                                $no++;
                        ?>
                        <tr>
                            <td><?php echo $no; ?></td>
                            <td><?php echo $data['nama']; ?></td>
                            <td><?php echo $data['universitas']; ?></td>
                            <td>
                                <?php 
                                    if (empty($data['status'])) {
                                        echo $data['status'];
                                    } else if ($data['status'] == 'Hadir') { 
                                        echo 'Hadir <button class="btn btn-info btn-circle btn-sm view_selfie" 
                                                     data-id_mahasiswa="' . $data['id_mahasiswa'] . '" 
                                                     data-tanggal="' . $data['tanggal'] . '">
                                                     <i class="fa fa-search"></i>
                                              </button>';
                                    } else if ($data['status'] == 'Izin') { 
                                        echo 'Izin <button class="btn btn-info btn-circle btn-sm view_izin" 
                                                     data-id_mahasiswa="' . $data['id_mahasiswa'] . '" 
                                                     data-tanggal="' . $data['tanggal'] . '">
                                                     <i class="fa fa-search"></i>
                                              </button>';
                                    }
                                ?>
                            </td>
                            <td><?php echo $data['waktu']; ?></td>
                            <td>
                                <?php
                                    $hari = $data["hari"];
                                    echo MendapatkanHari($hari);
                                ?>
                            </td>
                            <td>
                                <?php
                                    $tgl = date("d", strtotime($data['tanggal']));
                                    $bulan = date("m", strtotime($data['tanggal']));
                                    $tahun = date("Y", strtotime($data['tanggal']));
                                    echo $tgl . ' ' . MendapatkanBulan($bulan) . ' ' . $tahun;
                                ?>
                            </td>
                            <td>
                                <button id_mahasiswa="<?php echo $data['id_mahasiswa']; ?>" id_absensi="<?php echo $data['id_absensi']; ?>" class="absensi btn btn-success btn-circle" ><i class="fa fa-clock-o"></i> Absensi</button>
                                <button id_mahasiswa="<?php echo $data['id_mahasiswa']; ?>" class="cetak btn btn-primary btn-circle" ><i class="fa fa-print"></i> Cetak</button>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div><!--/.row-->

<!-- Modal -->
<div class="modal fade" id="modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

        <div class="modal-header">
            <h4 class="modal-title" id="judul"></h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <div class="modal-body">
            <div id="tampil_data">                   
            </div>  
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
        </div>

        </div>
    </div>
</div>

<script>
    // Menambahkan absensi oleh admin
    $('#tambah_absensi').on('click',function(){
    $.ajax({
        url: 'apps/data_absensi/tambah.php',
        method: 'post',
        success:function(data){
            $('#tampil_data').html(data);  
            document.getElementById("judul").innerHTML='Tambah Absensi';
        }
    });
    // Membuka modal
    $('#modal').modal('show');
});
$('.absensi').on('click',function(){
    var id_mahasiswa = $(this).attr("id_mahasiswa");
    var id_absensi = $(this).attr("id_absensi");
    $.ajax({
        url: 'apps/data_absensi/absensi.php',
        method: 'POST',
        data: {id_mahasiswa: id_mahasiswa, id_absensi: id_absensi},
        success:function(data){
            $('#tampil_data').html(data);  
            document.getElementById("judul").innerHTML='Mulai Absensi';
        }
    });
    // Membuka modal
    $('#modal').modal('show');
});

//Cetak Absensi
$('.cetak').on('click',function(){
    var id_mahasiswa = $(this).attr("id_mahasiswa");
    $.ajax({
        url: 'apps/data_absensi/cetak.php',
        method: 'POST',
        data: {id_mahasiswa: id_mahasiswa},
        success:function(data){
            $('#tampil_data').html(data);  
            document.getElementById("judul").innerHTML='Cetak Absensi';
        }
    });
    // Membuka modal
    $('#modal').modal('show');
});

    // Menampilkan gambar selfie untuk status Hadir
    $('.view_selfie').on('click', function() {
        var id_mahasiswa = $(this).data('id_mahasiswa');
        var tanggal = $(this).data('tanggal');
        
        $.ajax({
            url: 'apps/data_absensi/view_selfie.php',
            method: 'POST',
            data: {id_mahasiswa: id_mahasiswa, tanggal: tanggal},
            success: function(data) {
                $('#tampil_data').html(data);  
                document.getElementById("judul").innerHTML = 'Gambar Selfie';
            }
        });
        $('#modal').modal('show');
    });

    // Menampilkan alasan izin untuk status Izin
    $('.view_izin').on('click', function() {
        var id_mahasiswa = $(this).data('id_mahasiswa');
        var tanggal = $(this).data('tanggal');
        
        $.ajax({
            url: 'apps/data_absensi/view_izin.php',
            method: 'POST',
            data: {id_mahasiswa: id_mahasiswa, tanggal: tanggal},
            success: function(data) {
                $('#tampil_data').html(data);  
                document.getElementById("judul").innerHTML = 'Alasan Izin';
            }
        });
        $('#modal').modal('show');
    });
</script>
