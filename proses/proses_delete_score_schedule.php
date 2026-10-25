<?php
include '../includes/session.php';
include '../koneksi/koneksi.php';

// Function untuk kategorikan error message
function getErrorCategory($errorCode, $errorMsg) 
{
    switch($errorCode) 
    {
        case 1054:
            return "Database error: Field tidak ditemukan.";
        case 1305:
            return "Database error: Procedure tidak ditemukan.";
        default:
            return "Error: " . $errorMsg;
    }
}

$v_tahun_pelajaran  = $_POST['tahunPelajaran'];
$v_semester         = $_POST['semester'];
$v_no_urut          = $_POST['noUrut'];

$query      = "DELETE FROM tb_jadwal_penilaian WHERE TAHUN_PELAJARAN = '$v_tahun_pelajaran' AND SEMESTER = '$v_semester' AND NO_URUT = '$v_no_urut' ";
$jsondata   = [];

try 
{
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $db_ext = mysqli_query($db_connect, $query);

    $jsondata["isSuccessful"] = true;
    $jsondata["ret"] = "1";
    $jsondata["msg"] = "Berhasil hapus data.";
    echo json_encode($jsondata);

} 
catch (mysqli_sql_exception $e) 
{
    $jsondata["isSuccessful"] = false;
    $jsondata["ret"] = "-1";
    $jsondata["msg"] = getErrorCategory($e->getCode(), $e->getMessage());
    echo json_encode($jsondata);
}

exit();

?>


