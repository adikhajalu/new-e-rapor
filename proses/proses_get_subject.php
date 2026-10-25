<?php
include '../includes/session.php';
include '../koneksi/koneksi.php';

$v_tahun_pelajaran  = $_POST['tahunAjaran'];
$v_semester         = $_POST['semester'];
$v_kelas            = $_POST['kelas'];

if($session_st_admin == "Y")
{
    $v_filter          = " AND A.TAHUN_PELAJARAN = '$v_tahun_pelajaran' AND A.ID_MAPEL_SEMESTER = CONCAT(A.ID_MAPEL, CASE WHEN UPPER('$v_semester') = 'GENAP' THEN '.2' ELSE '.1' END) AND A.KELAS = '$v_kelas' ";
}
else
{
    $v_filter          = " AND A.TAHUN_PELAJARAN = '$v_tahun_pelajaran' AND A.NIP = '$session_user_id' AND A.ID_MAPEL_SEMESTER = CONCAT(A.ID_MAPEL, CASE WHEN UPPER('$v_semester') = 'GENAP' THEN '.2' ELSE '.1' END) AND A.KELAS = '$v_kelas' ";
}

$query_detil       = "SELECT A.ID_MAPEL_SEMESTER, (SELECT B.NAMA_MAPEL FROM tb_mapel B WHERE B.ID_MAPEL_SEMESTER = A.ID_MAPEL_SEMESTER) NAMA_MAPEL FROM `tb_guru_mapel` A 
                    WHERE 1=1 $v_filter"; 

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


