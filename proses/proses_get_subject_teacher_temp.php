<?php
include '../includes/session.php';
include '../koneksi/koneksi.php';

$v_status_valid     = $_POST['statusValid'];
$v_filter_st_valid  = "";

if(ISSET($v_status_valid) && $v_status_valid == "T")
{
    $v_filter_st_valid = " AND A.STATUS_VALID = 'T' ";
}

$query_list     = "SELECT   A.*, 
                            (SELECT B.NAMA FROM tb_user B WHERE B.NIP = A.NIP) NAMA_GURU, 
                            (SELECT B.NAMA_MAPEL FROM tb_mapel B WHERE B.ID_MAPEL_SEMESTER = A.ID_MAPEL_SEMESTER) NAMA_MAPEL, 
                            (SELECT B.SEMESTER FROM tb_mapel B WHERE B.ID_MAPEL_SEMESTER = A.ID_MAPEL_SEMESTER) SEMESTER 
                    FROM tb_guru_mapel_temp A 
                    WHERE 1=1 AND UPPER(PETUGAS_REKAM) = UPPER('$session_user_id') $v_filter_st_valid
                    ORDER BY A.NIP, A.TAHUN_PELAJARAN DESC, A.ID_MAPEL_SEMESTER"; 
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


