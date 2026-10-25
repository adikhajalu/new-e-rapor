<?php
include '../includes/session.php';
include '../koneksi/koneksi.php';

$v_nis              = mysqli_real_escape_string($db_connect, trim($_POST['nis']));
$v_nama_siswa       = mysqli_real_escape_string($db_connect, trim($_POST['namaSiswa']));
$v_alamat           = mysqli_real_escape_string($db_connect, trim($_POST['alamat']));
$v_kelas            = mysqli_real_escape_string($db_connect, trim($_POST['kelas']));
$v_tahun_pelajaran  = mysqli_real_escape_string($db_connect, trim($_POST['tahunAjaran']));
$v_keterangan       = mysqli_real_escape_string($db_connect, trim($_POST['keterangan']));

$query_count    = "SELECT COUNT(1) CNT_ROW FROM tb_wali_kelas WHERE 1=1 AND KELAS = UPPER('$v_kelas') AND TAHUN_PELAJARAN = '$v_tahun_pelajaran'";
$db_ext         = mysqli_query($db_connect,$query_count);
$data_count     = mysqli_fetch_array($db_ext);
$v_total_data   = $data_count['CNT_ROW'];

if($v_total_data <= 0){
    $jsondata["isSuccessful"]   = false;
    $jsondata["ret"]            = "-1";
    $jsondata["msg"]            = "Data Wali Kelas untuk Kelas dan Tahun Ajaran tersebut belum ada, pastikan data wali kelas sudah direkam sebelum menambahkan data siswa.";
    echo json_encode($jsondata);
    exit();
}

try 
{
    $query          = "INSERT INTO tb_siswa (NIS, NAMA_SISWA, ALAMAT, KELAS, TAHUN_PELAJARAN, SEMESTER, KETERANGAN, TGL_REKAM, PETUGAS_REKAM) 
                       VALUES ('$v_nis', '$v_nama_siswa', '$v_alamat', UPPER('$v_kelas'), '$v_tahun_pelajaran', 'Gasal', '$v_keterangan', NOW(), '$session_user_id'),
                       ('$v_nis', '$v_nama_siswa', '$v_alamat', UPPER('$v_kelas'), '$v_tahun_pelajaran', 'Genap', '$v_keterangan', NOW(), '$session_user_id') ";
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
        $jsondata["msg"] = "Data siswa dengan NIS, Kelas, dan Tahun Ajaran tersebut sudah ada, silakan cek kembali data yang akan disimpan.";
    }
    echo json_encode($jsondata);
    exit();
}



?>

