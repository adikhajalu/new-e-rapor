<?php
include '../includes/session.php';
include '../koneksi/koneksi.php';

$v_tahun_pelajaran  = $_POST['tahunAjaran'];

$v_filter          = " AND A.TAHUN_PELAJARAN = '$v_tahun_pelajaran'";

$query_detil       = "SELECT A.ID_MAPEL, A.ID_MAPEL_SEMESTER, (SELECT B.NAMA_MAPEL FROM tb_mapel B WHERE B.ID_MAPEL_SEMESTER = A.ID_MAPEL_SEMESTER) NAMA_MAPEL FROM `tb_mapel` A 
                    WHERE 1=1 $v_filter GROUP BY ID_MAPEL"; 

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
    $jsondata["ret"]            = "-1";
    $jsondata["msg"]            = "Mata Pelajaran tidak ditemukan.";
    echo json_encode($jsondata);
}


?>


