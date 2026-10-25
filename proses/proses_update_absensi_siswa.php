<?php
include '../includes/session.php';
include '../koneksi/koneksi.php';

$v_nama_siswa           = mysqli_real_escape_string($db_connect, $_POST['namaSiswa']);
$v_nis                  = mysqli_real_escape_string($db_connect, $_POST['nis']);
$v_kelas                = mysqli_real_escape_string($db_connect, $_POST['kelas']);
$v_tahun_pelajaran      = mysqli_real_escape_string($db_connect, $_POST['tahunAjaran']);
$v_semester             = mysqli_real_escape_string($db_connect, $_POST['semester']);
$v_kehadiran            = mysqli_real_escape_string($db_connect, $_POST['kehadiran']);
$v_sakit                = mysqli_real_escape_string($db_connect, $_POST['sakit']);
$v_izin                 = mysqli_real_escape_string($db_connect, $_POST['izin']);
$v_tanpa_keterangan     = mysqli_real_escape_string($db_connect, $_POST['tanpaKeterangan']);
$v_filter               = " AND NIS = '$v_nis' AND UPPER(KELAS) = UPPER('$v_kelas') AND UPPER(TAHUN_PELAJARAN) = UPPER('$v_tahun_pelajaran') AND UPPER(SEMESTER) = UPPER('$v_semester') ";

$query              = "UPDATE tb_absensi_siswa SET KEHADIRAN = $v_kehadiran, SAKIT = $v_sakit, IZIN = $v_izin, TANPA_KETERANGAN = $v_tanpa_keterangan, TGL_UBAH = NOW(), PETUGAS_UBAH = '$session_user_id' 
                        WHERE 1=1 $v_filter ";
                        // var_dump($query); die;
$db_ext             = mysqli_query($db_connect,$query);

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

