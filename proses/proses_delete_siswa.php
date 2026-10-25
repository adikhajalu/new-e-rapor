<?php
include '../includes/session.php';
include '../koneksi/koneksi.php';

$v_nis              = $_POST['nis'];
$v_kelas            = $_POST['kelas'];
$v_tahunAjaran   = $_POST['tahunAjaran'];
// $v_semester         = $_POST['semester'];
$query              = "DELETE FROM tb_siswa WHERE NIS = '$v_nis' AND KELAS = '$v_kelas' AND TAHUN_PELAJARAN = '$v_tahunAjaran' "; //AND SEMESTER = '$v_semester'
try{
    $db_ext             = mysqli_query($db_connect,$query);
    $jsondata["isSuccessful"]   = true;
    $jsondata["ret"]            = "1";
    $jsondata["msg"]            = "Berhasil hapus data.";
    echo json_encode($jsondata);
    exit();
} catch (mysqli_sql_exception $e) {
    $jsondata["isSuccessful"]   = false;
    $jsondata["ret"]            = "-1";
    if ($e->getCode() == 1451) {
        $jsondata["msg"] = "Gagal hapus, data tidak dapat dihapus karena siswa ini sudah memiliki data penilaian.";
    } else {
        $jsondata["msg"]        = "Gagal hapus data, silakan coba beberapa saat lagi.";
        $jsondata["sysErrMsg"]  = $e->getMessage();
    }
    echo json_encode($jsondata);
    exit();
}

?>
