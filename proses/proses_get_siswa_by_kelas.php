<?php
include '../includes/session.php';
include '../koneksi/koneksi.php';

$v_kelas            = $_POST['kelas'];
$v_tahun_pelajaran  = $_POST['tahunAjaran'];
$v_semester         = $_POST['semester'];

$v_filter          = " AND  UPPER(KELAS) = UPPER('$v_kelas') AND UPPER(TAHUN_PELAJARAN) = UPPER('$v_tahun_pelajaran') AND UPPER(SEMESTER) = UPPER('$v_semester') ";

$query_detil       = "SELECT B.* FROM tb_siswa B WHERE 1=1 $v_filter"; 

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
        echo json_encode($jsondata);

    } else {
        $jsondata["isSuccessful"]   = false;
        $jsondata["ret"]            = "-2";
        $jsondata["msg"]            = "Tidak terdapat siswa untuk kelas $v_kelas pada semester $v_semester tahun ajaran $v_tahun_pelajaran.";
        echo json_encode($jsondata);
    }

} catch (mysqli_sql_exception $e) {
    $jsondata["isSuccessful"]   = false;
    $jsondata["ret"]            = "-1";
    $jsondata["msg"]            = "Gagal memuat data, silakan coba beberapa saat lagi.";
    $jsondata["sysErrMsg"]      = $e->getMessage();
    echo json_encode($jsondata);
}


?>


