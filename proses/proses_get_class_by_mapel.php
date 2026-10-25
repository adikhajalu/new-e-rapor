<?php
include '../includes/session.php';
include '../koneksi/koneksi.php';

$v_tahun_pelajaran  = $_POST['tahunAjaran'];
$v_semester         = $_POST['semester'];
$v_val_semester     = "";

if(strtoupper($v_semester) == 'GENAP')
{
    $v_val_semester = ".2";
}
else
{
    $v_val_semester = ".1";
}

if($session_st_admin == "Y")
{
    $v_filter          = " AND TAHUN_PELAJARAN = '$v_tahun_pelajaran' AND RIGHT(ID_MAPEL_SEMESTER, 2) = '$v_val_semester' ";
}
else
{
    $v_filter          = " AND TAHUN_PELAJARAN = '$v_tahun_pelajaran' AND NIP = '$session_user_id' AND RIGHT(ID_MAPEL_SEMESTER, 2) = '$v_val_semester' ";
}

$query_kelas       = "SELECT DISTINCT KELAS FROM tb_guru_mapel WHERE 1=1 $v_filter ORDER BY KELAS"; 
$db_ext            = mysqli_query($db_connect,$query_kelas);

if(mysqli_num_rows($db_ext) > 0){
    while ($data = mysqli_fetch_array($db_ext)) 
    {
        $jdata[] = $data;
    }

    $jsondata["isSuccessful"]   = true;
    $jsondata["data"]           = $jdata;
    $jsondata["ret"]            = "1";
    $jsondata["msg"]            = "Sukses.";
    echo json_encode($jsondata);

} else {
    $jsondata["isSuccessful"]   = false;
    $jsondata["ret"]            = "-1";
    $jsondata["msg"]            = "Kelas tidak ditemukan.";
    echo json_encode($jsondata);
}


?>


