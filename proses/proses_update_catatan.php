<?php
include '../includes/session.php';
include '../koneksi/koneksi.php';

$v_jenis                = mysqli_real_escape_string($db_connect, $_POST['jenis']);
$v_nis                  = mysqli_real_escape_string($db_connect, $_POST['nis']);
$v_kelas                = mysqli_real_escape_string($db_connect, $_POST['kelas']);
$v_tahun_pelajaran      = mysqli_real_escape_string($db_connect, $_POST['tahunAjaran']);
$v_semester             = mysqli_real_escape_string($db_connect, $_POST['semester']);
$v_catatan              = mysqli_real_escape_string($db_connect, $_POST['catatan']);

if($v_jenis == "KOKURIKULER") 
{
    $query          = "UPDATE tb_siswa SET KOKURIKULER = '$v_catatan', TGL_UBAH = NOW(), PETUGAS_UBAH = '$session_user_id' 
                        WHERE 1=1 AND NIS = '$v_nis' AND KELAS = '$v_kelas' AND TAHUN_PELAJARAN = '$v_tahun_pelajaran' AND SEMESTER = '$v_semester' ";
}
else if($v_jenis == "CATATAN_WALI_KELAS") 
{
    $query          = "UPDATE tb_siswa SET CATATAN_WALI_KELAS = '$v_catatan', TGL_UBAH = NOW(), PETUGAS_UBAH = '$session_user_id' 
                        WHERE 1=1 AND NIS = '$v_nis' AND KELAS = '$v_kelas' AND TAHUN_PELAJARAN = '$v_tahun_pelajaran' AND SEMESTER = '$v_semester' ";
}
else if($v_jenis == "LSP") 
{
    $query          = "UPDATE tb_siswa SET STATUS_KOMPETENSI_LSP = '$v_catatan', TGL_UBAH = NOW(), PETUGAS_UBAH = '$session_user_id' 
                        WHERE 1=1 AND NIS = '$v_nis' AND KELAS = '$v_kelas' AND TAHUN_PELAJARAN = '$v_tahun_pelajaran' AND SEMESTER = '$v_semester' ";
}

try{
    $db_ext         = mysqli_query($db_connect,$query);

    if($db_ext == true)
    {
        $jsondata["isSuccessful"]   = true;
        $jsondata["ret"]            = "1";
        $jsondata["msg"]            = "Berhasil ubah data.";
        echo json_encode($jsondata);

    } 
} catch (mysqli_sql_exception $e) {
    $jsondata["isSuccessful"]   = false;
    $jsondata["ret"]            = "-1";
    $jsondata["msg"]        = "Gagal ubah data, silakan coba beberapa saat lagi.";
    $jsondata["sysErrMsg"]  = $e->getMessage();
    echo json_encode($jsondata);
    exit();
}



?>

