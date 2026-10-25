<?php
include '../includes/session.php';
include '../koneksi/koneksi.php';

$v_status_valid     = $_POST['statusValid'];
$v_filter_st_valid  = "";

if(ISSET($v_status_valid) && $v_status_valid == "T")
{
    $v_filter_st_valid = " AND A.STATUS_VALID = 'T' ";
}

$query_list     = "SELECT A.*,
                        (SELECT X.NAMA_SISWA FROM tb_siswa X WHERE X.NIS = A.NIS AND X.KELAS = A.KELAS AND X.TAHUN_PELAJARAN = A.TAHUN_PELAJARAN AND X.SEMESTER = A.SEMESTER) NAMA_SISWA FROM tb_pkl_temp A 
                    WHERE 1=1 AND UPPER(PETUGAS_REKAM) = UPPER('$session_user_id') $v_filter_st_valid
                    ORDER BY A.TAHUN_PELAJARAN DESC, A.SEMESTER, A.NIS, A.TGL_REKAM DESC"; 
                    // var_dump($query_list);die;   
$db_ext         = mysqli_query($db_connect,$query_list);

if(mysqli_num_rows($db_ext) > 0){
    // $data                       = mysqli_fetch_array($db_ext);
    while ($data = mysqli_fetch_array($db_ext)) 
    {
        $jdata[] = $data;
    }

    $jsondata["isSuccessful"]   = true;
    $jsondata["ret"]            = "1";
    $jsondata["data"]           = $jdata;
    $jsondata["msg"]            = "Sukses.";
    echo json_encode($jsondata);

} else {
    $jsondata["isSuccessful"]   = false;
    $jsondata["ret"]            = "-1";
    $jsondata["msg"]            = "Gagal memuat data, silakan coba beberapa saat lagi.";
    echo json_encode($jsondata);
}


?>


