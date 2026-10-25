<?php
include '../includes/session.php';
include '../koneksi/koneksi.php';

$v_nip              = mysqli_real_escape_string($db_connect, $_POST['nip']);
$v_kelas            = mysqli_real_escape_string($db_connect, $_POST['kelas']);
$v_tahun_pelajaran  = mysqli_real_escape_string($db_connect, $_POST['tahunAjaran']);
$v_keterangan       = mysqli_real_escape_string($db_connect, $_POST['keterangan']);

try 
{
    $query          = "INSERT INTO tb_wali_kelas (NIP, KELAS, TAHUN_PELAJARAN, KETERANGAN, TGL_REKAM, PETUGAS_REKAM) 
                       VALUES ('$v_nip', '$v_kelas', '$v_tahun_pelajaran', '$v_keterangan', NOW(), '$session_user_id')";
    $db_ext         = mysqli_query($db_connect,$query);

    if($db_ext == true)
    {
        $jsondata["isSuccessful"]   = true;
        $jsondata["ret"]            = "1";
        $jsondata["msg"]            = "Berhasil simpan data.";
        echo json_encode($jsondata);
    } 
} 
catch (mysqli_sql_exception $e) 
{
    $jsondata["isSuccessful"]   = false;
    $jsondata["ret"]            = "-1";
    $jsondata["msg"]            = "Gagal menyimpan data, silakan coba beberapa saat lagi.";
    $jsondata["sysErrMsg"]      = $e->getMessage();
    if (mysqli_errno($db_connect) == 1062) 
    {
        $jsondata["msg"] = "Data Wali Kelas pada Kelas dan Tahun Ajaran tersebut sudah ada, silakan cek kembali data yang akan disimpan.";
    }
    echo json_encode($jsondata);
    exit();
}



?>

