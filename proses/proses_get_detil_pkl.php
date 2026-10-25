<?php
include '../includes/session.php';
include '../koneksi/koneksi.php';

$v_mitra_du_di          = $_POST['mitraDuDi'];
$v_nis                  = $_POST['nis'];
$v_kelas                = $_POST['kelas'];
$v_tahun_pelajaran      = $_POST['tahunAjaran'];
$v_semester             = $_POST['semester'];

$v_filter          = " AND UPPER(MITRA_DU_DI) = UPPER('$v_mitra_du_di') AND UPPER(NIS) = UPPER('$v_nis') AND UPPER(KELAS) = UPPER('$v_kelas') AND UPPER(TAHUN_PELAJARAN) = UPPER('$v_tahun_pelajaran') AND UPPER(SEMESTER) = UPPER('$v_semester')";

$query_detil       = "SELECT B.*,
                        (SELECT X.NAMA_SISWA FROM tb_siswa X WHERE X.NIS = B.NIS AND X.KELAS = B.KELAS AND X.TAHUN_PELAJARAN = B.TAHUN_PELAJARAN AND X.SEMESTER = B.SEMESTER) NAMA_SISWA
                    FROM tb_pkl B WHERE 1=1 $v_filter "; 

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


