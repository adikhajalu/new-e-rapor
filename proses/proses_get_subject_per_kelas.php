<?php
include '../includes/session.php';
include '../koneksi/koneksi.php';

$v_tahun_pelajaran  = $_POST['tahunAjaran'];
$v_semester         = $_POST['semester'];
$v_kelas            = $_POST['kelas'];

if($session_st_admin == "Y")
{
    $v_filter           = " AND A.TAHUN_PELAJARAN = '$v_tahun_pelajaran' AND UPPER(B.SEMESTER) = UPPER('$v_semester') AND A.KELAS = '$v_kelas' ";
}
else
{
    $v_filter           = " AND A.NIP = '$session_user_id' AND A.TAHUN_PELAJARAN = '$v_tahun_pelajaran' AND UPPER(B.SEMESTER) = UPPER('$v_semester') AND A.KELAS = '$v_kelas' ";
}

$query_detil        = "SELECT B.ID_MAPEL, B.NAMA_MAPEL, A.NIP, (SELECT X.NAMA FROM tb_user X WHERE X.NIP = A.NIP) AS NAMA_GURU FROM tb_guru_mapel A, tb_mapel B WHERE A.ID_MAPEL_SEMESTER = B.ID_MAPEL_SEMESTER $v_filter"; 

try {
    $db_ext             = mysqli_query($db_connect,$query_detil);

    if(mysqli_num_rows($db_ext) > 0){
        while ($data = mysqli_fetch_array($db_ext)) 
        {
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
        $jsondata["msg"]            = "Mata pelajaran yang anda ajar tidak ditemukan untuk kelas $v_kelas semester $v_semester tahun ajaran $v_tahun_pelajaran.";
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


