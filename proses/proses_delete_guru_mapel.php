<?php
include '../includes/session.php';
include '../koneksi/koneksi.php';

$v_nip                  = $_POST['nip'];
$v_kelas                = $_POST['kelas'];
$v_id_mapel_semester    = $_POST['idMapelSemester'];
$v_id_mapel             = explode(".", $v_id_mapel_semester)[0];

$query_cek              = "SELECT COUNT(1) CNT_ROW FROM tb_penilaian WHERE NIP_GURU = '$v_nip' AND KELAS = '$v_kelas' AND ID_MAPEL = '$v_id_mapel' ";
$db_ext                 = mysqli_query($db_connect,$query_cek);
$data_count             = mysqli_fetch_array($db_ext);
$v_total_data           = $data_count['CNT_ROW'];

if($v_total_data > 0){
    $jsondata["isSuccessful"]   = false;
    $jsondata["ret"]            = "-1";
    $jsondata["msg"]            = "Gagal hapus, data tidak dapat dihapus karena guru mata pelajaran ini sudah memiliki data penilaian.";
    echo json_encode($jsondata);
    exit();
}

$query                  = "DELETE FROM tb_guru_mapel WHERE NIP = '$v_nip' AND KELAS = '$v_kelas' AND ID_MAPEL_SEMESTER = '$v_id_mapel_semester' ";

$db_ext                 = mysqli_query($db_connect,$query);

if($db_ext == true)
{
    $jsondata["isSuccessful"]   = true;
    $jsondata["ret"]            = "1";
    $jsondata["msg"]            = "Berhasil hapus data.";
    echo json_encode($jsondata);

} else {
    $jsondata["isSuccessful"]   = false;
    $jsondata["ret"]            = "-1";
    $jsondata["msg"]            = "Gagal hapus data, silakan coba beberapa saat lagi.";
    echo json_encode($jsondata);
}


?>
