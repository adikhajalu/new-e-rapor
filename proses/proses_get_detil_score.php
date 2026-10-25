<?php
include '../includes/session.php';
include '../koneksi/koneksi.php';

$v_id_mapel         = $_POST['idMapel'];
$v_nis              = $_POST['nis'];
$v_kelas            = $_POST['kelas'];
$v_tahun_pelajaran  = $_POST['tahunAjaran'];
$v_semester         = $_POST['semester'];

$v_filter          = " AND UPPER(ID_MAPEL) = UPPER('$v_id_mapel') AND UPPER(NIS) = UPPER('$v_nis') AND UPPER(KELAS) = UPPER('$v_kelas') AND UPPER(TAHUN_PELAJARAN) = UPPER('$v_tahun_pelajaran') AND UPPER(SEMESTER) = UPPER('$v_semester') ";

$query_detil       = "SELECT B.*, (SELECT A.NAMA_MAPEL FROM tb_mapel A WHERE A.ID_MAPEL = B.ID_MAPEL LIMIT 1) NAMA_MAPEL,
                        (SELECT A.NAMA FROM tb_user A WHERE A.NIP = B.NIP_GURU) NAMA_GURU,
                        (SELECT A.NAMA_SISWA FROM tb_siswa A WHERE A.NIS = B.NIS AND B.KELAS = A.KELAS AND B.TAHUN_PELAJARAN = A.TAHUN_PELAJARAN LIMIT 1) NAMA_SISWA
                    FROM tb_penilaian B WHERE 1=1 $v_filter"; 

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
    $jsondata["msg"]            = "Gagal memuat data, silakan coba beberapa saat lagi.";
    echo json_encode($jsondata);
}


?>


