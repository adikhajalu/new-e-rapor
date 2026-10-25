<?php
include '../includes/session.php';
include '../koneksi/koneksi.php';

// helper untuk mengkategorikan error berdasarkan kode
function getErrorCategory($errorCode, $errorMsg) {
    switch($errorCode) {
        case 1062: // duplicate entry
            return "Data sudah ada (duplikat).";
        case 1451:
            return "Data tidak bisa dihapus karena masih digunakan oleh data lain (foreign key).";
        case 1452:
            return "Referensi foreign key tidak valid.";
        case 1054:
            return "Kolom tidak ditemukan di database.";
        default:
            return "Error: " . $errorMsg;
    }
}

$v_id_mapel                 = mysqli_real_escape_string($db_connect, $_POST['idMapel']);
$v_nama_mapel               = mysqli_real_escape_string($db_connect, $_POST['namaMapel']);
$v_tahun_pelajaran          = mysqli_real_escape_string($db_connect, $_POST['tahunAjaran']);
$v_id_kategori_mapel        = mysqli_real_escape_string($db_connect, $_POST['idKategoriMapel']);
$v_id_sub_kategori_mapel    = mysqli_real_escape_string($db_connect, $_POST['idSubKategoriMapel']);
$v_id_sub_kategori_mapel    = empty($v_id_sub_kategori_mapel) ? "NULL" : "'$v_id_sub_kategori_mapel'";
// $v_semester         = mysqli_real_escape_string($db_connect, $_POST['semester']);
$v_keterangan               = mysqli_real_escape_string($db_connect, $_POST['keterangan']);

$v_filter               = " AND UPPER(ID_MAPEL) = UPPER('$v_id_mapel') ";

$query          = "UPDATE tb_mapel SET  NAMA_MAPEL       = TRIM('$v_nama_mapel'),
                                        TAHUN_PELAJARAN  = TRIM('$v_tahun_pelajaran'),
                                        ID_KATEGORI_MAPEL = TRIM('$v_id_kategori_mapel'),
                                        ID_SUB_KATEGORI_MAPEL = $v_id_sub_kategori_mapel,
                                        KETERANGAN       = TRIM('$v_keterangan'),
                                        TGL_UBAH        = NOW(),
                                        PETUGAS_UBAH    = '$session_user_id'
                    WHERE 1=1 $v_filter "; //SEMESTER         = TRIM('$v_semester'),

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

