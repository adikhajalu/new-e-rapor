<?php
include '../includes/session.php';
include '../koneksi/koneksi.php';

$query      = "DELETE FROM tb_guru_mapel_temp WHERE UPPER(PETUGAS_REKAM) = UPPER('$session_user_id')";
$db_ext     = mysqli_query($db_connect,$query);

if($db_ext == true)
{
    $jsondata["isSuccessful"]   = true;
    $jsondata["ret"]            = "1";
    $jsondata["msg"]            = "Data berhasil dihapus.";
    echo json_encode($jsondata);

} 
else 
{
    $jsondata["isSuccessful"]   = false;
    $jsondata["ret"]            = "-1";
    $jsondata["msg"]            = "Gagal menghapus data, coba beberapa saat lagi.";
    echo json_encode($jsondata);
}


?>
