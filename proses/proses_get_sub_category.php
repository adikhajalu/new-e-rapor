<?php
include '../includes/session.php';
include '../koneksi/koneksi.php';

$v_id_kategori_mapel = $_POST['idKategoriMapel'];

$query_list = "SELECT * FROM tb_sub_kategori_mapel WHERE ID_KATEGORI_MAPEL = '$v_id_kategori_mapel' ORDER BY ID_SUB_KATEGORI_MAPEL ASC";    
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
    $jsondata["msg"]            = "Gagal memuat data, silakan coba beberapa saat lagi.";
    echo json_encode($jsondata);
}

