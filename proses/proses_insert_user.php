<?php
include '../includes/session.php';
include '../koneksi/koneksi.php';

$v_nip              = mysqli_real_escape_string($db_connect, $_POST['nip']);
$v_nama             = mysqli_real_escape_string($db_connect, $_POST['nama']);
$v_posisi_jabatan   = mysqli_real_escape_string($db_connect, $_POST['posisiJabatan']);
$v_status_kepsek    = mysqli_real_escape_string($db_connect, $_POST['statusKepSek']);
$v_status_admin     = mysqli_real_escape_string($db_connect, $_POST['statusAdmin']);
$v_status_karyawan  = mysqli_real_escape_string($db_connect, $_POST['statusKaryawan']);
$v_status_guru      = "T";
$v_status_kesiswaan = "T";
$v_status_kurikulum = "T";

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

try {
    $query_insert     = "INSERT INTO tb_user (NIP, NAMA, POSISI_JABATAN, STATUS_KEPALA_SEKOLAH, STATUS_AKTIF, STATUS_ADMIN, TGL_REKAM, PETUGAS_REKAM, STATUS_GURU, STATUS_KESISWAAN, STATUS_KURIKULUM) 
                            VALUES ('$v_nip', '$v_nama', '$v_posisi_jabatan', '$v_status_kepsek', 'Y', '$v_status_admin', NOW(), '$session_user_id', '$v_status_guru', '$v_status_kesiswaan', '$v_status_kurikulum')";
                            
    $db_insert        = mysqli_query($db_connect, $query_insert);

    if($db_insert) {
        $jsondata["isSuccessful"]   = true;
        $jsondata["ret"]            = "1";
        $jsondata["msg"]            = "Data user berhasil ditambahkan dengan User/NIP: $v_nip dan Password: Smakpa123! . Harap segera infokan password tersebut kepada user bersangkutan dan segera menggantinya dengan password terbaru";
        echo json_encode($jsondata);
    } 
} catch (mysqli_sql_exception $e) {
    $jsondata["isSuccessful"]   = false;
    $jsondata["ret"]            = "-1";
    $jsondata["msg"]            = "Gagal menyimpan data, silakan coba beberapa saat lagi.";
    if (mysqli_errno($db_connect) == 1062) {
        $jsondata["msg"] = "Data NIP sudah ada, silakan cek kembali data yang akan direkam.";
    }
    echo json_encode($jsondata);
    exit();
}