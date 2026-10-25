<?php
include '../includes/session.php';
include '../koneksi/koneksi.php';

$v_tgl_awal         = mysqli_real_escape_string($db_connect, trim($_POST['tglAwal']));
$v_tgl_akhir        = mysqli_real_escape_string($db_connect, trim($_POST['tglAkhir']));
$v_tahun_ajaran     = mysqli_real_escape_string($db_connect, trim($_POST['tahunAjaran']));
$v_semester         = mysqli_real_escape_string($db_connect, trim($_POST['semester']));

$v_filter          = " AND UPPER(TAHUN_PELAJARAN) = UPPER('$v_tahun_ajaran') AND UPPER(SEMESTER) = UPPER('$v_semester') AND ('$v_tgl_awal' BETWEEN DATE_FORMAT(TGL_AWAL,'%Y-%m-%d') AND DATE_FORMAT(TGL_AKHIR,'%Y-%m-%d') OR '$v_tgl_akhir' BETWEEN DATE_FORMAT(TGL_AWAL,'%Y-%m-%d') AND DATE_FORMAT(TGL_AKHIR,'%Y-%m-%d'))";

$query_count    = "SELECT COUNT(1) CNT_ROW FROM tb_jadwal_penilaian A 
                    WHERE 1=1 $v_filter";
$db_ext         = mysqli_query($db_connect,$query_count);
$data_count     = mysqli_fetch_array($db_ext);
$v_total_exist  = $data_count['CNT_ROW'];

                    // var_dump($query_count);
                    // var_dump($v_total_exist);die;

if($v_total_exist > 0){
    $jsondata["isSuccessful"]   = false;
    $jsondata["ret"]            = "-1";
    $jsondata["msg"]            = "Data Jadwal Penilaian pada periode tersebut sudah pernah direkam sebelumnya, silakan cek ulang kembali.";
    echo json_encode($jsondata);
    exit();
}
else 
{
    $query_max  = "SELECT IFNULL(MAX(NO_URUT),0) AS NO_URUT FROM tb_jadwal_penilaian WHERE TAHUN_PELAJARAN = '$v_tahun_ajaran' AND SEMESTER = '$v_semester'";
    $result_max = mysqli_query($db_connect, $query_max);
    $row_max    = mysqli_fetch_assoc($result_max);
    $v_no_urut  = intval($row_max['NO_URUT']) + 1;

    $query  = "INSERT INTO tb_jadwal_penilaian (NO_URUT, TAHUN_PELAJARAN, SEMESTER, TGL_AWAL, TGL_AKHIR, TGL_REKAM, PETUGAS_REKAM) 
                       VALUES ('$v_no_urut', '$v_tahun_ajaran', '$v_semester', DATE_FORMAT('$v_tgl_awal', '%Y/%m/%d'), DATE_FORMAT('$v_tgl_akhir', '%Y/%m/%d'), NOW(),'$session_user_id') ";
    $db_ext = mysqli_query($db_connect,$query);

    if($db_ext == true)
    {
        $jsondata["isSuccessful"]   = true;
        $jsondata["ret"]            = "1";
        $jsondata["msg"]            = "Berhasil simpan data.";
        echo json_encode($jsondata);

    } else {
        $jsondata["isSuccessful"]   = false;
        $jsondata["ret"]            = "-1";
        $jsondata["msg"]            = "Gagal simpan data, silakan coba beberapa saat lagi.";
        echo json_encode($jsondata);
        exit();
    }
}



?>

