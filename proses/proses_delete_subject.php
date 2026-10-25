<?php
include '../includes/session.php';
include '../koneksi/koneksi.php';

$v_id_mapel = $_POST['idMapel'];
$query      = "DELETE FROM tb_mapel WHERE ID_MAPEL = '$v_id_mapel' ";
$db_ext     = mysqli_query($db_connect,$query);

if($db_ext == true)
{
    $jsondata["isSuccessful"]   = true;
    $jsondata["ret"]            = "1";
    $jsondata["msg"]            = "Berhasil hapus data.";
    echo json_encode($jsondata);

} else {
    $jsondata["isSuccessful"]   = false;
    $jsondata["ret"]            = "-1";
    $jsondata["msg"]            = "Gagal hapus data, silakan coba beberapa saat lagi.";
    echo json_encode($jsondata);
}


?>
