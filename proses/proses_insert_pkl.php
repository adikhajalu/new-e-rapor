<?php
include '../includes/session.php';
include '../koneksi/koneksi.php';

$v_nis              = mysqli_real_escape_string($db_connect, $_POST['nis']);
$v_nama_siswa       = mysqli_real_escape_string($db_connect, $_POST['namaSiswa']);
$v_kelas            = mysqli_real_escape_string($db_connect, $_POST['kelas']);
$v_tahun_pelajaran  = mysqli_real_escape_string($db_connect, $_POST['tahunAjaran']);
$v_semester         = mysqli_real_escape_string($db_connect, $_POST['semester']);
$v_mitra_du_di      = mysqli_real_escape_string($db_connect, $_POST['mitraDuDi']);
$v_lokasi           = mysqli_real_escape_string($db_connect, $_POST['lokasi']);
$v_filter           = " AND UPPER(NIS) = UPPER('$v_nis') AND UPPER(KELAS) = UPPER('$v_kelas') AND UPPER(TAHUN_PELAJARAN) = UPPER('$v_tahun_pelajaran') AND UPPER(SEMESTER) = UPPER('$v_semester') AND UPPER(MITRA_DU_DI) = UPPER('$v_mitra_du_di')";

$query_count    = "SELECT COUNT(1) CNT_ROW FROM tb_pkl A 
                    WHERE 1=1 $v_filter";
$db_ext         = mysqli_query($db_connect,$query_count);
$data_count     = mysqli_fetch_array($db_ext);
$v_total_exist  = $data_count['CNT_ROW'];

if($v_total_exist > 0){
    $jsondata["isSuccessful"]   = false;
    $jsondata["ret"]            = "-1";
    $jsondata["msg"]            = "Data PKL dengan NIS, Kelas, Tahun Ajaran, Semester dan Mitra DU/DI tersebut sudah ada, silakan cek kembali data yang akan disimpan.";
    echo json_encode($jsondata);
    exit();
}
else 
{
    $query          = "INSERT INTO tb_pkl (NIS, KELAS, TAHUN_PELAJARAN, SEMESTER, MITRA_DU_DI, LOKASI, TGL_REKAM, PETUGAS_REKAM) 
                       VALUES ('$v_nis', UPPER('$v_kelas'), '$v_tahun_pelajaran', '$v_semester', '$v_mitra_du_di', '$v_lokasi', NOW(), '$session_user_id') ";
    $db_ext         = mysqli_query($db_connect,$query);

    if($db_ext == true)
    {
        $jsondata["isSuccessful"]   = true;
        $jsondata["ret"]            = "1";
        $jsondata["msg"]            = "Berhasil simpan data.";
        echo json_encode($jsondata);

    } else {
        $jsondata["isSuccessful"]   = false;
        $jsondata["ret"]            = "-1";
        $jsondata["msg"]            = "Gagal simpan data, silakan coba beberapa saat lagi.";
        echo json_encode($jsondata);
        exit();
    }
}



?>

