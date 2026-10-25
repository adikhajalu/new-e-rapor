<?php
include '../includes/session.php';
include '../koneksi/koneksi.php';

$v_id_ekstrakurikuler   = $_POST['idEkstrakurikuler'];
$v_tahun_pelajaran      = $_POST['tahunAjaran'];
// $v_semester             = $_POST['semester'];
$query                  = "DELETE FROM tb_ekstrakurikuler WHERE ID_EKSTRAKURIKULER = '$v_id_ekstrakurikuler' AND TAHUN_PELAJARAN = '$v_tahun_pelajaran' ";// AND SEMESTER = '$v_semester'
$db_ext                 = mysqli_query($db_connect,$query);

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
