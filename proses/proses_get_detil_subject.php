<?php
include '../includes/session.php';
include '../koneksi/koneksi.php';

$v_id_mapel     = $_POST['idMapel'];
$v_filter       = " AND UPPER(ID_MAPEL) = UPPER('$v_id_mapel') ";
$query_detil    = "SELECT B.* FROM tb_mapel B WHERE 1=1 $v_filter"; 
$db_ext         = mysqli_query($db_connect,$query_detil);

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
    $jsondata["msg"]            = "Gagal memuat data, silakan coba beberapa saat lagi.";
    echo json_encode($jsondata);
}


?>


