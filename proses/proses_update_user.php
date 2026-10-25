<?php
include '../includes/session.php';
include '../koneksi/koneksi.php';

$v_nip                = mysqli_real_escape_string($db_connect, $_POST['nip']);
$v_nama               = mysqli_real_escape_string($db_connect, $_POST['nama']);
$v_posisi_jabatan     = mysqli_real_escape_string($db_connect, $_POST['posisiJabatan']);
$v_status_aktif       = mysqli_real_escape_string($db_connect, $_POST['statusAktif']);
$v_status_kepsek      = mysqli_real_escape_string($db_connect, $_POST['statusKepsek']);
$v_status_admin       = mysqli_real_escape_string($db_connect, $_POST['statusAdmin']);
$v_nip_old            = mysqli_real_escape_string($db_connect, $_POST['nipOld']);
$v_status_karyawan    = mysqli_real_escape_string($db_connect, $_POST['statusKaryawan']);
$v_filter_old         = " AND UPPER(NIP) = UPPER('$v_nip_old') ";
$v_status_guru        = "T";
$v_status_kesiswaan   = "T";    
$v_status_kurikulum   = "T";

if($v_status_karyawan == "STATUS_GURU")
{
    $v_status_guru      = "Y";
} 
else if($v_status_karyawan == "STATUS_KESISWAAN")
{
    $v_status_kesiswaan = "Y";
} 
else if($v_status_karyawan == "STATUS_KURIKULUM")
{
    $v_status_kurikulum = "Y";
}

$query          = "UPDATE tb_user SET NAMA = TRIM('$v_nama'), POSISI_JABATAN = TRIM('$v_posisi_jabatan'), STATUS_AKTIF = '$v_status_aktif', STATUS_KEPALA_SEKOLAH = '$v_status_kepsek', STATUS_ADMIN = '$v_status_admin', STATUS_GURU = '$v_status_guru', STATUS_KESISWAAN = '$v_status_kesiswaan', STATUS_KURIKULUM = '$v_status_kurikulum', TGL_UBAH = NOW(), PETUGAS_UBAH = '$session_user_id'
                    WHERE 1=1 $v_filter_old ";
$db_ext         = mysqli_query($db_connect,$query);

if($db_ext == true)
{
    $jsondata["isSuccessful"]   = true;
    $jsondata["ret"]            = "1";
    $jsondata["msg"]            = "Berhasil ubah data.";
    echo json_encode($jsondata);

} else {
    $jsondata["isSuccessful"]   = false;
    $jsondata["ret"]            = "-1";
    $jsondata["msg"]            = "Gagal ubah data, silakan coba beberapa saat lagi.";
    echo json_encode($jsondata);
    exit();
}




?>

