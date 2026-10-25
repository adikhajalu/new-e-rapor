<?php
include '../includes/session.php';
include '../koneksi/koneksi.php';

$v_nis              = mysqli_real_escape_string($db_connect, $_POST['nis']);
$v_kelas            = mysqli_real_escape_string($db_connect, $_POST['kelas']);
$v_tahun_pelajaran  = mysqli_real_escape_string($db_connect, $_POST['tahunAjaran']);
$v_semester         = mysqli_real_escape_string($db_connect, $_POST['semester']);
$v_mitra_du_di_old  = mysqli_real_escape_string($db_connect, $_POST['mitraDuDiOld']);
$v_mitra_du_di_new  = mysqli_real_escape_string($db_connect, $_POST['mitraDuDiNew']);
$v_lokasi           = mysqli_real_escape_string($db_connect, $_POST['lokasi']);
$v_filter           = " AND UPPER(NIS) = UPPER('$v_nis') AND UPPER(KELAS) = UPPER('$v_kelas') AND UPPER(TAHUN_PELAJARAN) = UPPER('$v_tahun_pelajaran') AND UPPER(SEMESTER) = UPPER('$v_semester') AND UPPER(MITRA_DU_DI) = UPPER('$v_mitra_du_di_old') ";

try 
{
    $query              = "UPDATE tb_pkl SET MITRA_DU_DI = '$v_mitra_du_di_new', LOKASI = '$v_lokasi', TGL_UBAH = NOW(), PETUGAS_UBAH = '$session_user_id' 
                            WHERE 1=1 $v_filter ";
    $db_ext             = mysqli_query($db_connect,$query);

    $jsondata["isSuccessful"]   = true;
    $jsondata["ret"]            = "1";
    $jsondata["msg"]            = "Berhasil ubah data.";
    echo json_encode($jsondata);

}
catch (mysqli_sql_exception $e) 
{
    $jsondata["isSuccessful"]   = false;
    $jsondata["ret"]            = "-1";
    $jsondata["msg"]            = "Gagal menyimpan data, silakan coba beberapa saat lagi.";
    $jsondata["sysErrMsg"]      = $e->getMessage();
    if (mysqli_errno($db_connect) == 1062) 
    {
        $jsondata["msg"] = "Data PKL dengan NIS, Kelas, Semester, Tahun Ajaran dan Mitra DU/DI tersebut sudah ada, silakan cek kembali data yang akan disimpan.";
    }
    echo json_encode($jsondata);
    exit();
}


?>

