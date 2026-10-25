<?php
include '../includes/session.php';
include '../koneksi/koneksi.php';

$v_nip              = mysqli_real_escape_string($db_connect, $_POST['nip']);
$v_kelas            = mysqli_real_escape_string($db_connect, $_POST['kelas']);
$v_tahun_pelajaran  = mysqli_real_escape_string($db_connect, $_POST['tahunAjaran']);
$v_semester_ganjil  = mysqli_real_escape_string($db_connect, $_POST['semesterGasal']);
$v_semester_genap   = mysqli_real_escape_string($db_connect, $_POST['semesterGenap']);
$v_id_mapel         = mysqli_real_escape_string($db_connect, $_POST['idMapel']);
$v_val              = "";

if($v_semester_ganjil == "Y")
{
    $v_id_mapel_semester = $v_id_mapel . '.1';
    $v_val              .= "('$v_nip', '$v_id_mapel', '$v_id_mapel_semester', UPPER('$v_kelas'), UPPER('$v_tahun_pelajaran'), NOW(), '$session_user_id') ";
} 

if($v_semester_genap == "Y")
{
    if($v_val !== "")
    {
        $v_val  .= ",";
    }
    
    $v_id_mapel_semester = $v_id_mapel . '.2';
    $v_val              .= "('$v_nip', '$v_id_mapel', '$v_id_mapel_semester', UPPER('$v_kelas'), UPPER('$v_tahun_pelajaran'), NOW(), '$session_user_id') ";
} 


try 
{
    $query          = "INSERT INTO tb_guru_mapel (NIP, ID_MAPEL, ID_MAPEL_SEMESTER, KELAS, TAHUN_PELAJARAN, TGL_REKAM, PETUGAS_REKAM)
                       VALUES $v_val";
    $db_ext         = mysqli_query($db_connect,$query);

    if($db_ext == true)
    {
        $jsondata["isSuccessful"]   = true;
        $jsondata["ret"]            = "1";
        $jsondata["msg"]            = "Berhasil simpan data.";
        echo json_encode($jsondata);
    } 
} 
catch (mysqli_sql_exception $e) 
{
    $jsondata["isSuccessful"]   = false;
    $jsondata["ret"]            = "-1";
    $jsondata["msg"]            = "Gagal menyimpan data, silakan coba beberapa saat lagi.";
    $jsondata["sysErrMsg"]      = $e->getMessage();
    if (mysqli_errno($db_connect) == 1062) 
    {
        $jsondata["msg"] = "Data Guru dengan Kelas, Semester, Tahun Ajaran dan Mata Pelajaran tersebut sudah ada, silakan cek kembali data yang akan disimpan.";
    }
    echo json_encode($jsondata);
    exit();
}



?>

