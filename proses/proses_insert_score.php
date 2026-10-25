<?php
include '../includes/session.php';
include '../koneksi/koneksi.php';

$v_nis              = mysqli_real_escape_string($db_connect, $_POST['nis']);
$v_kelas            = mysqli_real_escape_string($db_connect, $_POST['kelas']);
$v_tahun_pelajaran  = mysqli_real_escape_string($db_connect, $_POST['tahunAjaran']);
$v_semester         = mysqli_real_escape_string($db_connect, $_POST['semester']);
$v_id_semester      = strtoupper($v_semester) == 'GENAP' ? '.2' : '.1';
$v_id_mapel         = mysqli_real_escape_string($db_connect, $_POST['idMapel']);
$v_id_mapel_semester= $v_id_mapel.$v_id_semester;
$v_nip              = mysqli_real_escape_string($db_connect, $_POST['nip']);
$v_nilai_akhir      = mysqli_real_escape_string($db_connect, $_POST['nilaiAkhir']);
$v_cp_plus          = mysqli_real_escape_string($db_connect, $_POST['cpPlus']);
$v_cp_min           = mysqli_real_escape_string($db_connect, $_POST['cpMin']);

$query_count_jadwal = "SELECT COUNT(1) CNT_ROW FROM tb_jadwal_penilaian WHERE CURDATE() BETWEEN TGL_AWAL AND TGL_AKHIR AND UPPER(TAHUN_PELAJARAN) = UPPER('$v_tahun_pelajaran') AND UPPER(SEMESTER) = UPPER('$v_semester')";
$db_ext             = mysqli_query($db_connect,$query_count_jadwal);
$data_count         = mysqli_fetch_array($db_ext);
$v_total_cek_jadwal = $data_count['CNT_ROW'];

if($v_total_cek_jadwal < 1){
    $jsondata["isSuccessful"]   = false;
    $jsondata["ret"]            = "-1";
    $jsondata["msg"]            = "Jadwal Penilaian dengan Tahun Ajaran $v_tahun_pelajaran dan Semester $v_semester belum dimulai atau sudah ditutup.";
    echo json_encode($jsondata);
    exit();
}

try 
{
    $query_insert     = "INSERT INTO tb_penilaian (NIS, KELAS, TAHUN_PELAJARAN, SEMESTER, ID_MAPEL, ID_MAPEL_SEMESTER, NIP_GURU, NILAI_AKHIR, CP_PLUS, CP_MIN, TGL_REKAM, PETUGAS_REKAM) 
                            VALUES ('$v_nis', '$v_kelas', '$v_tahun_pelajaran', '$v_semester', '$v_id_mapel', '$v_id_mapel_semester', '$v_nip', '$v_nilai_akhir', '$v_cp_plus', '$v_cp_min', NOW(), '$session_user_id')";
    $db_insert        = mysqli_query($db_connect, $query_insert);
 
    if($db_insert) {
        $jsondata["isSuccessful"]   = true;
        $jsondata["ret"]            = "1";
        $jsondata["msg"]            = "Data penilaian berhasil disimpan.";
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
        $jsondata["msg"] = "Data penilaian siswa dengan NIS, Kelas, Mata Pelajaran dan Tahun Ajaran tersebut sudah ada, silakan cek kembali data yang akan disimpan.";
    }
    echo json_encode($jsondata);
    exit();
}