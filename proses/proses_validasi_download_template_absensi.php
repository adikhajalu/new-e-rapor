<?php
include '../includes/session.php';
include '../koneksi/koneksi.php';

$v_kelas                = $_POST['kelas'];
$v_tahun_pelajaran      = $_POST['tahunAjaran'];
$v_semester             = $_POST['semester'];
$v_filter               = " AND UPPER(A.KELAS) = UPPER('$v_kelas') AND UPPER(A.TAHUN_PELAJARAN) = UPPER('$v_tahun_pelajaran') AND UPPER(A.SEMESTER) = UPPER('$v_semester') ";

$query_cek_siswa= "SELECT COUNT(1) CNT_ROW FROM tb_siswa A 
                    WHERE 1=1 $v_filter";
$db_ext         = mysqli_query($db_connect,$query_cek_siswa);
$data_count     = mysqli_fetch_array($db_ext);
$v_siswa_exist  = $data_count['CNT_ROW'];

if($v_siswa_exist == 0){
    $jsondata["isSuccessful"]   = false;
    $jsondata["ret"]            = "-2";
    $jsondata["msg"]            = "Tidak ada siswa pada Kelas, Semester dan Tahun Ajaran tersebut.";
    echo json_encode($jsondata);
    exit();
}

if($v_siswa_exist > 0){
    $jsondata["isSuccessful"]   = true;
    $jsondata["ret"]            = "1";
    $jsondata["msg"]            = "Berhasil.";
    echo json_encode($jsondata);
    exit();
}



?>

