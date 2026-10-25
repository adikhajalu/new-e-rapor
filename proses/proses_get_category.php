<?php
include '../includes/session.php';
include '../koneksi/koneksi.php';

$v_tahun_ajaran = isset($_POST['tahunAjaran']) ? $_POST['tahunAjaran'] : '';

$query_list = "SELECT * FROM tb_kategori_mapel WHERE TAHUN_PELAJARAN = '$v_tahun_ajaran' ORDER BY ID_KATEGORI_MAPEL ASC";    
$db_ext     = mysqli_query($db_connect,$query_list);

if(mysqli_num_rows($db_ext) > 0){
    // $data                       = mysqli_fetch_array($db_ext);
    while ($data = mysqli_fetch_array($db_ext)) 
    {
        $jdata[] = $data;
    }

    $jsondata["isSuccessful"]   = true;
    $jsondata["ret"]            = "1";
    $jsondata["data"]           = $jdata;
    echo json_encode($jsondata);

} else {
    $jsondata["isSuccessful"]   = false;
    $jsondata["ret"]            = "-1";
    $jsondata["msg"]            = "Tidak ada kategori pelajaran pada tahun ajaran yang dipilih.";
    echo json_encode($jsondata);
}


?>


