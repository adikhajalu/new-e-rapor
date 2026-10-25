<?php
include '../includes/session.php';
include '../koneksi/koneksi.php';

$v_kelas                = $_POST['kelas'];
$v_tahun_pelajaran      = $_POST['tahunAjaran'];
$v_semester             = $_POST['semester'];
$v_id_mapel_semester    = $_POST['idMapelSemester'];
$v_filter               = " AND UPPER(A.KELAS) = UPPER('$v_kelas') AND UPPER(A.TAHUN_PELAJARAN) = UPPER('$v_tahun_pelajaran') AND UPPER(A.SEMESTER) = UPPER('$v_semester') ";

if($session_st_admin == "Y")
{
    
    $query_cek_mapel= "SELECT COUNT(1) CNT_ROW FROM tb_guru_mapel A 
                    WHERE A.ID_MAPEL_SEMESTER = '$v_id_mapel_semester'";
}
else
{
    $query_cek_mapel= "SELECT COUNT(1) CNT_ROW FROM tb_guru_mapel A 
                    WHERE A.ID_MAPEL_SEMESTER = '$v_id_mapel_semester' AND A.NIP = '$session_user_id' ";
}

$db_ext         = mysqli_query($db_connect,$query_cek_mapel);
$data_count     = mysqli_fetch_array($db_ext);
$v_mapel_exist  = $data_count['CNT_ROW'];

if($v_mapel_exist == 0){
    $jsondata["isSuccessful"]   = false;
    $jsondata["ret"]            = "-2";
    $jsondata["msg"]            = "Anda tidak memiliki mata pelajaran pada Kelas, Semester dan Tahun Ajaran tersebut.";
    echo json_encode($jsondata);
    exit();
}

$query_count    = "SELECT COUNT(1) CNT_ROW FROM tb_siswa A 
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
    $jsondata["msg"]            = "Tidak terdapat siswa pada Kelas, Semester dan Tahun Ajaran tersebut.";
    echo json_encode($jsondata);
    exit();
}



?>

