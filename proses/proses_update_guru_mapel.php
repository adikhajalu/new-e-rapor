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

$v_id_mapel_semester     = mysqli_real_escape_string($db_connect, $_POST['idMapelSemester']);
$v_kelas                 = mysqli_real_escape_string($db_connect, $_POST['kelas']);
$v_nip                   = mysqli_real_escape_string($db_connect, $_POST['nip']);
$v_filter_old            = "AND UPPER(ID_MAPEL_SEMESTER) = UPPER('$v_id_mapel_semester') AND UPPER(KELAS) = UPPER('$v_kelas')";
$query                   = "UPDATE tb_guru_mapel SET NIP = TRIM('$v_nip'), TGL_UBAH = NOW(), PETUGAS_UBAH = '$session_user_id' WHERE 1=1 $v_filter_old";

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

