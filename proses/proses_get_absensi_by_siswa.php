<?php
include '../includes/session.php';
include '../koneksi/koneksi.php';
// var_dump($_POST);die;
$v_nis               = mysqli_real_escape_string($db_connect, $_POST['nis']);
$v_tahun_pelajaran   = mysqli_real_escape_string($db_connect, $_POST['tahunPelajaran']);
$v_kelas             = mysqli_real_escape_string($db_connect, $_POST['kelas']);
$v_semester          = mysqli_real_escape_string($db_connect, $_POST['semester']);

$query_list     = "SELECT * FROM `tb_absensi_siswa` WHERE NIS = '$v_nis' AND TAHUN_PELAJARAN = '$v_tahun_pelajaran' AND UPPER(SEMESTER) = UPPER('$v_semester') AND KELAS = '$v_kelas'";    
                //    var_dump($query_list);die; 
$db_ext         = mysqli_query($db_connect,$query_list);

if(mysqli_num_rows($db_ext) > 0){
    // $data                       = mysqli_fetch_array($db_ext);
    while ($data = mysqli_fetch_array($db_ext)) 
    {
        $jdata[] = $data;
    }

    $jsondata["isSuccessful"]   = true;
    $jsondata["ret"]            = "1";
    $jsondata["data"]           = $jdata;
    $jsondata["msg"]            = "Sukses.";
    echo json_encode($jsondata);

} else {
    $jsondata["isSuccessful"]   = false;
    $jsondata["ret"]            = "-1";
    $jsondata["msg"]            = "Gagal memuat data, silakan coba beberapa saat lagi.";
    echo json_encode($jsondata);
}


?>


