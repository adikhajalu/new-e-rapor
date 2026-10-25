<?php
include '../includes/session.php';
include '../koneksi/koneksi.php';

$v_id_ekstrakurikuler   = $_POST['idEkstrakurikuler'];
$v_tahun_pelajaran      = $_POST['tahunAjaran'];
$v_semester             = $_POST['semester'];
$v_filter               = " AND ID_EKSTRAKURIKULER = '$v_id_ekstrakurikuler' AND UPPER(TAHUN_PELAJARAN) = UPPER('$v_tahun_pelajaran') AND UPPER(SEMESTER) = UPPER('$v_semester') ";

$query_count    = "SELECT COUNT(1) CNT_ROW FROM tb_ekstrakurikuler A 
                    WHERE 1=1 $v_filter";
$db_ext         = mysqli_query($db_connect,$query_count);
$data_count     = mysqli_fetch_array($db_ext);
$v_total_exist  = $data_count['CNT_ROW'];

if($v_total_exist > 0){
    $jsondata["isSuccessful"]   = true;
    $jsondata["ret"]            = "1";
    $jsondata["msg"]            = "Berhasil.";
    echo json_encode($jsondata);
    exit();
}
else 
{
    $jsondata["isSuccessful"]   = false;
    $jsondata["ret"]            = "-1";
    $jsondata["msg"]            = "Tidak terdapat ekstrakurikuler pada Semester dan Tahun Ajaran tersebut.";
    echo json_encode($jsondata);
    exit();
}



?>
