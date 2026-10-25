<?php
include '../includes/session.php';
include '../koneksi/koneksi.php';

$v_nis              = mysqli_real_escape_string($db_connect, $_POST['nis']);
$v_nama_siswa       = mysqli_real_escape_string($db_connect, $_POST['namaSiswa']);
$v_kelas            = mysqli_real_escape_string($db_connect, $_POST['kelas']);
$v_tahun_pelajaran  = mysqli_real_escape_string($db_connect, $_POST['tahunAjaran']);
$v_semester         = mysqli_real_escape_string($db_connect, $_POST['semester']);
$v_kehadiran        = mysqli_real_escape_string($db_connect, $_POST['kehadiran']);
$v_sakit            = mysqli_real_escape_string($db_connect, $_POST['sakit']);
$v_izin             = mysqli_real_escape_string($db_connect, $_POST['izin']);
$v_tanpa_keterangan = mysqli_real_escape_string($db_connect, $_POST['tanpaKeterangan']);
$v_filter          = " AND UPPER(NIS) = UPPER('$v_nis') AND UPPER(KELAS) = UPPER('$v_kelas') AND UPPER(TAHUN_PELAJARAN) = UPPER('$v_tahun_pelajaran') AND UPPER(SEMESTER) = UPPER('$v_semester') ";

$query_count    = "SELECT COUNT(1) CNT_ROW FROM tb_absensi_siswa A 
                    WHERE 1=1 $v_filter";
$db_ext         = mysqli_query($db_connect,$query_count);
$data_count     = mysqli_fetch_array($db_ext);
$v_total_exist  = $data_count['CNT_ROW'];

if($v_total_exist > 0){
    $jsondata["isSuccessful"]   = false;
    $jsondata["ret"]            = "-1";
    $jsondata["msg"]            = "Data siswa dengan NIS, Kelas, Tahun Ajaran, dan Semester tersebut sudah ada, silakan cek kembali data yang akan disimpan.";
    echo json_encode($jsondata);
    exit();
}
else 
{
    $query          = "INSERT INTO tb_absensi_siswa (NIS, NAMA_SISWA, KELAS, TAHUN_PELAJARAN, SEMESTER, KEHADIRAN, SAKIT, IZIN, TANPA_KETERANGAN, TGL_REKAM, PETUGAS_REKAM) 
                       VALUES ('$v_nis', '$v_nama_siswa', UPPER('$v_kelas'), '$v_tahun_pelajaran', '$v_semester', '$v_kehadiran', '$v_sakit', '$v_izin', '$v_tanpa_keterangan', NOW(), '$session_user_id') ";
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

