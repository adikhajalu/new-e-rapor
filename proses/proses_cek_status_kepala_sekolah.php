<?php
include '../includes/session.php';
include '../koneksi/koneksi.php';

$v_status_kepala_sekolah = $_POST['statusKepalaSekolah'];

$query_list = "SELECT NAMA, NIP FROM tb_user WHERE status_kepala_sekolah = '$v_status_kepala_sekolah'";    
$db_ext     = mysqli_query($db_connect,$query_list);

if(mysqli_num_rows($db_ext) > 0){
    while ($data = mysqli_fetch_array($db_ext)) 
    {
        $jdata[] = $data;
        $jdata['EXIST_KEPALA_SEKOLAH'] = "Y";
    }

    $jsondata["isSuccessful"]   = true;
    $jsondata["ret"]            = "1";
    $jsondata["data"]           = $jdata;
    echo json_encode($jsondata);

} else {
    $jsondata["isSuccessful"]   = false;
    $jsondata["ret"]            = "-1";
    $jsondata["msg"]            = "Gagal memuat data, silakan coba beberapa saat lagi.";
    echo json_encode($jsondata);
}


?>


