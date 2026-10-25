<?php
include '../includes/session.php';
include '../koneksi/koneksi.php';

$v_status_valid     = $_POST['statusValid'];
$v_filter_st_valid  = "";

if(ISSET($v_status_valid) && $v_status_valid == "T")
{
    $v_filter_st_valid = " AND A.STATUS_VALID = 'T' ";
}

$query_list     = " SELECT X.* FROM (
                        SELECT A.TAHUN_PELAJARAN, A.SEMESTER, A.KELAS, A.NIS, A.NILAI_AKHIR, A.CP_PLUS, A.CP_MIN, A.STATUS_VALID, A.KETERANGAN,
                        (SELECT B.NAMA_MAPEL FROM tb_mapel B WHERE B.ID_MAPEL_SEMESTER = A.ID_MAPEL_SEMESTER) NAMA_MAPEL,
                        (SELECT B.NAMA_SISWA FROM tb_siswa B WHERE B.NIS = A.NIS AND B.TAHUN_PELAJARAN =  A.TAHUN_PELAJARAN AND B.KELAS = A.KELAS LIMIT 1) NAMA_SISWA
                        FROM tb_penilaian_temp A WHERE 1=1 AND UPPER(PETUGAS_REKAM) = UPPER('$session_user_id') $v_filter_st_valid
                    ) X 
                    ORDER BY X.TAHUN_PELAJARAN DESC, X.SEMESTER DESC, X.KELAS, X.NAMA_MAPEL, X.NAMA_SISWA
                    "; 
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


