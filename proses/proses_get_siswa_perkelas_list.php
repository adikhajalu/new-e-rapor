<?php
include '../includes/session.php';
include '../koneksi/koneksi.php';

$v_tahun_pelajaran          = mysqli_real_escape_string($db_connect, $_POST['tahunPelajaran']);
$v_kelas                    = mysqli_real_escape_string($db_connect, $_POST['kelas']);
$v_semester                 = mysqli_real_escape_string($db_connect, $_POST['semester']);
// $v_page                     = (int)$_POST['page'];
// $v_jml_per_page             = (int)$_POST['jmlPerPage'];
$v_status_admin             = $_POST['statusAdmin'];


// $query_count    = "SELECT COUNT(1) CNT_ROW FROM tb_siswa X
//                    WHERE   1=1 AND X.TAHUN_PELAJARAN = '$v_tahun_pelajaran' AND X.KELAS = '$v_kelas' AND UPPER(X.SEMESTER) = UPPER('$v_semester') ";
// $db_ext         = mysqli_query($db_connect,$query_count);
// $data_count     = mysqli_fetch_array($db_ext);
// $v_total_data   = $data_count['CNT_ROW'];
// $v_data_perpage = $v_jml_per_page;   // jumlah data per halaman
// $v_jml_page     = ceil($v_total_data / $v_data_perpage);
// $v_first_num    = ($v_page - 1) * $v_data_perpage + 1;
// $v_last_num     = $v_first_num + $v_data_perpage - 1;
// $v_page_filter  = " AND B.NOMOR BETWEEN $v_first_num AND $v_last_num ";

$query_list     = "SELECT B.*, CASE WHEN (SELECT COUNT(1) FROM tb_jadwal_penilaian XX WHERE CURDATE() BETWEEN TGL_AWAL AND TGL_AKHIR AND XX.TAHUN_PELAJARAN = '$v_tahun_pelajaran' AND UPPER(XX.SEMESTER) = UPPER('$v_semester')) > 0 THEN 'Y' ELSE 'T' END STATUS_BOLEH_UBAH FROM(
                    SELECT ROW_NUMBER() OVER (ORDER BY A.NAMA_SISWA) AS NOMOR, 
                           A.* FROM 
                           (
                                SELECT X.*, (SELECT COUNT(1) FROM tb_penilaian X1 WHERE X1.NIS = X.NIS AND X1.TAHUN_PELAJARAN = '$v_tahun_pelajaran' AND X1.KELAS = '$v_kelas' AND UPPER(X1.SEMESTER) = UPPER('$v_semester')) AS JML_MAPEL,
                                       (SELECT COUNT(1) FROM tb_penilaian_exr X2 WHERE X2.NIS = X.NIS AND X2.TAHUN_PELAJARAN = '$v_tahun_pelajaran' AND X2.KELAS = '$v_kelas' AND UPPER(X2.SEMESTER) = UPPER('$v_semester')) AS JML_ESKUL,
                                       NVL((SELECT (SAKIT+IZIN+TANPA_KETERANGAN) FROM tb_absensi_siswa X3 WHERE X3.NIS = X.NIS AND X3.TAHUN_PELAJARAN = '$v_tahun_pelajaran' AND X3.KELAS = '$v_kelas' AND UPPER(X3.SEMESTER) = UPPER('$v_semester')), 0) AS JML_TIDAK_HADIR
                                FROM tb_siswa X
                                WHERE   1=1 AND X.TAHUN_PELAJARAN = '$v_tahun_pelajaran' AND X.KELAS = '$v_kelas' AND UPPER(X.SEMESTER) = UPPER('$v_semester')
                                ORDER BY X.NAMA_SISWA
                           ) A 
                    ) B";
                   //WHERE 1=1 $v_page_filter ";    
                //    var_dump($query_list);die; 
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
    // $jsondata["jml_page"]       = $v_jml_page;
    // $jsondata["jml_data"]       = $v_total_data;
    $jsondata["msg"]            = "Sukses.";
    echo json_encode($jsondata);

} else {
    $jsondata["isSuccessful"]   = false;
    $jsondata["ret"]            = "-1";
    $jsondata["msg"]            = "Gagal memuat data, silakan coba beberapa saat lagi.";
    echo json_encode($jsondata);
}


?>


