<?php
include '../includes/session.php';
include '../koneksi/koneksi.php';

$v_filter          = " AND STATUS_AKTIF = 'Y' AND STATUS_GURU = 'Y' ";

$query_detil       = "SELECT B.* FROM tb_user B WHERE 1=1 $v_filter"; 

$db_ext             = mysqli_query($db_connect,$query_detil);

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
    $jsondata["msg"]            = "Mata Pelajaran tidak ditemukan.";
    echo json_encode($jsondata);
}


?>


