<?php
include '../includes/session.php';
include '../koneksi/koneksi.php';

$v_tanggal  = $_POST['tanggal'];

$query_detil        = "SELECT DISTINCT TAHUN_PELAJARAN FROM tb_jadwal_penilaian WHERE STR_TO_DATE('$v_tanggal', '%Y/%m/%d') BETWEEN TGL_AWAL AND TGL_AKHIR ORDER BY TAHUN_PELAJARAN DESC"; 

try {
    $db_ext = mysqli_query($db_connect, $query_detil);
    if (mysqli_num_rows($db_ext) > 0) {
        while ($data = mysqli_fetch_array($db_ext)) {
            $jdata[] = $data;
        }

        $jsondata["isSuccessful"]   = true;
        $jsondata["data"]           = $jdata;
        $jsondata["ret"]            = "1";
        $jsondata["msg"]            = "Sukses.";
    } else {
        $jsondata["isSuccessful"]   = true;
        $jsondata["ret"]            = "-2";
        $jsondata["data"]           = [];
        $jsondata["msg"]            = "Tidak ada data yang ditemukan.";
    }
    
    echo json_encode($jsondata);

} catch (mysqli_sql_exception $e) {
    $jsondata["isSuccessful"]   = false;
    $jsondata["ret"]            = "-1";
    $jsondata["msg"]            = "Gagal memuat data, silakan coba beberapa saat lagi.";
    $jsondata["sysErrMsg"]      = $e->getMessage();
    echo json_encode($jsondata);
}

?>


