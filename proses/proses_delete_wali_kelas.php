<?php
include '../includes/session.php';
include '../koneksi/koneksi.php';
    
$v_nip                  = $_POST['nip'];
$v_kelas                = $_POST['kelas'];
$v_tahunAjaran          = $_POST['tahunAjaran'];
$query                  = "DELETE FROM tb_wali_kelas WHERE NIP = '$v_nip' AND KELAS = '$v_kelas' AND TAHUN_PELAJARAN = '$v_tahunAjaran'";
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
