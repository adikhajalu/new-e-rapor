<?php
include '../includes/session.php';
include '../koneksi/koneksi.php';

// Function untuk kategorikan error message
function getErrorCategory($errorCode, $errorMsg) {
    switch($errorCode) {
        case 1451:
            return "Data tidak bisa dihapus karena sudah memiliki sub kategori atau sudah digunakan oleh pada perekaman mata pelajaran.";
        case 1452:
            return "Operasi gagal karena referensi foreign key tidak valid.";
        case 1054:
            return "Database error: Field tidak ditemukan.";
        case 1305:
            return "Database error: Procedure tidak ditemukan.";
        default:
            return "Error: " . $errorMsg;
    }
}

$v_id_kategori_mapel        = $_POST['idKategoriMapel'];
$v_id_sub_kategori_mapel    = $_POST['idSubKategoriMapel'];

if($v_id_sub_kategori_mapel != "" && $v_id_sub_kategori_mapel != "-"){
    $query      = "DELETE FROM tb_sub_kategori_mapel WHERE ID_SUB_KATEGORI_MAPEL = '$v_id_sub_kategori_mapel' ";
    $jsondata   = [];

    try {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        $db_ext = mysqli_query($db_connect, $query);

        $jsondata["isSuccessful"] = true;
        $jsondata["ret"] = "1";
        $jsondata["msg"] = "Berhasil hapus data.";
        echo json_encode($jsondata);

    } catch (mysqli_sql_exception $e) {
        $jsondata["isSuccessful"] = false;
        $jsondata["ret"] = "-1";
        $jsondata["msg"] = getErrorCategory($e->getCode(), $e->getMessage());
        echo json_encode($jsondata);
    }
    exit();
}
else
{
    $query      = "DELETE FROM tb_kategori_mapel WHERE ID_KATEGORI_MAPEL = '$v_id_kategori_mapel' ";
    $jsondata   = [];

    try {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        $db_ext = mysqli_query($db_connect, $query);

        $jsondata["isSuccessful"] = true;
        $jsondata["ret"] = "1";
        $jsondata["msg"] = "Berhasil hapus data.";
        echo json_encode($jsondata);

    } catch (mysqli_sql_exception $e) {
        $jsondata["isSuccessful"] = false;
        $jsondata["ret"] = "-1";
        $jsondata["msg"] = getErrorCategory($e->getCode(), $e->getMessage());
        echo json_encode($jsondata);
    }
}


?>


