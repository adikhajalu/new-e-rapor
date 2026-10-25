<?php
include '../includes/session.php';
include '../koneksi/koneksi.php';

// Function untuk kategorikan error message
function getErrorCategory($errorCode, $errorMsg) {
    switch($errorCode) {
        case 1062:
            return "Data sudah ada (duplikat), silakan pastikan data yang dimasukkan belum pernah disimpan sebelumnya.";
        case 1054:
            return "Database error: Field tidak ditemukan.";
        case 1305:
            return "Database error: Procedure tidak ditemukan.";
        default:
            return "Error: " . $errorMsg;
    }
}

$v_nip                  = mysqli_real_escape_string($db_connect, $_POST['nip']);
$v_kelas                = mysqli_real_escape_string($db_connect, $_POST['kelas']);
$v_tahun_ajaran         = mysqli_real_escape_string($db_connect, $_POST['tahunAjaran']);
$v_keterangan           = mysqli_real_escape_string($db_connect, $_POST['keterangan']);
$v_old_kelas            = mysqli_real_escape_string($db_connect, $_POST['oldKelas']);
$v_old_tahun_ajaran     = mysqli_real_escape_string($db_connect, $_POST['oldTahunAjaran']);
$v_filter_old           = " AND UPPER(NIP) = UPPER('$v_nip') AND UPPER(KELAS) = UPPER('$v_old_kelas') AND UPPER(TAHUN_PELAJARAN) = UPPER('$v_old_tahun_ajaran') ";


$query          = "UPDATE tb_wali_kelas SET KELAS = TRIM('$v_kelas'), TAHUN_PELAJARAN = TRIM('$v_tahun_ajaran'), KETERANGAN = TRIM('$v_keterangan'), TGL_UBAH = NOW(), PETUGAS_UBAH = '$session_user_id'
                    WHERE 1=1 $v_filter_old ";

try {
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $db_ext = mysqli_query($db_connect, $query);

    $jsondata["isSuccessful"]   = true;
    $jsondata["ret"]            = "1";
    $jsondata["msg"]            = "Berhasil ubah data.";
    echo json_encode($jsondata);

} catch (mysqli_sql_exception $e) {
    $jsondata["isSuccessful"]   = false;
    $jsondata["ret"]            = "-1";
    $jsondata["msg"]            = getErrorCategory($e->getCode(), $e->getMessage());
    echo json_encode($jsondata);
    exit();
}




?>

