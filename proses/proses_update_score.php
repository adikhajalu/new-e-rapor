<?php
include '../includes/session.php';
include '../koneksi/koneksi.php';

$v_id_mapel         = mysqli_real_escape_string($db_connect, $_POST['idMapel']);
$v_nis              = mysqli_real_escape_string($db_connect, $_POST['nis']);
$v_kelas            = mysqli_real_escape_string($db_connect, $_POST['kelas']);
$v_tahun_pelajaran  = mysqli_real_escape_string($db_connect, $_POST['tahunAjaran']);
$v_semester         = mysqli_real_escape_string($db_connect, $_POST['semester']);
$v_nilai_akhir      = mysqli_real_escape_string($db_connect, $_POST['nilaiAkhir']);
$v_cp_plus          = mysqli_real_escape_string($db_connect, $_POST['cpPlus']);
$v_cp_min           = mysqli_real_escape_string($db_connect, $_POST['cpMin']);
$v_filter           = " AND ID_MAPEL = '$v_id_mapel' AND UPPER(NIS) = UPPER('$v_nis') AND UPPER(KELAS) = UPPER('$v_kelas') AND UPPER(TAHUN_PELAJARAN) = UPPER('$v_tahun_pelajaran') AND UPPER(SEMESTER) = UPPER('$v_semester') ";

$query              = "UPDATE tb_penilaian SET NILAI_AKHIR = '$v_nilai_akhir', CP_PLUS = '$v_cp_plus', CP_MIN = '$v_cp_min', TGL_UBAH = NOW(), PETUGAS_UBAH = '$session_user_id' 
                        WHERE 1=1 $v_filter ";
$db_ext             = mysqli_query($db_connect,$query);

if($db_ext == true)
{
    $jsondata["isSuccessful"]   = true;
    $jsondata["ret"]            = "1";
    $jsondata["msg"]            = "Berhasil ubah data.";
    echo json_encode($jsondata);

} else {
    $jsondata["isSuccessful"]   = false;
    $jsondata["ret"]            = "-1";
    $jsondata["msg"]            = "Gagal ubah data, silakan coba beberapa saat lagi.";
    echo json_encode($jsondata);
    exit();
}




?>

