<?php
include '../includes/session.php';
include '../koneksi/koneksi.php';

$v_page         = (int)$_POST['page'];
$v_jml_per_page = (int)$_POST['jmlPerPage'];

$query_count    = "SELECT COUNT(1) CNT_ROW FROM ( 
                        SELECT XY.* FROM tb_jadwal_penilaian XY
                        ORDER BY XY.TAHUN_PELAJARAN DESC, XY.TGL_REKAM DESC) Z";
$db_ext         = mysqli_query($db_connect,$query_count);
$data_count     = mysqli_fetch_array($db_ext);
$v_total_data   = $data_count['CNT_ROW'];
$v_data_perpage = $v_jml_per_page;   // jumlah data per halaman
$v_jml_page     = ceil($v_total_data / $v_data_perpage);
$v_first_num    = ($v_page - 1) * $v_data_perpage + 1;
$v_last_num     = $v_first_num + $v_data_perpage - 1;
$v_page_filter  = " AND Z.NOMOR BETWEEN $v_first_num AND $v_last_num ";

$query_list     = "SELECT Z.* FROM ( 
                        SELECT ROW_NUMBER() OVER (ORDER BY XY.TAHUN_PELAJARAN ASC, XY.TGL_REKAM DESC) AS NOMOR,
                                DATE_FORMAT(XY.TGL_AWAL, '%d-%m-%Y') TGL_AWAL_VW, 
                                DATE_FORMAT(XY.TGL_AKHIR, '%d-%m-%Y') TGL_AKHIR_VW, 
                                CASE WHEN NOW() BETWEEN TGL_AWAL AND TGL_AKHIR THEN 'Sedang Berlangsung' 
                                     WHEN TGL_AWAL > NOW() THEN 'Belum Dimulai'
                                     ELSE 'Selesai'
                                END STATUS_JADWAL,
                                XY.* 
                        FROM tb_jadwal_penilaian XY
                        ORDER BY XY.TAHUN_PELAJARAN DESC, XY.TGL_REKAM DESC) Z
                   WHERE 1=1 $v_page_filter ";    
$db_ext         = mysqli_query($db_connect,$query_list);

if(mysqli_num_rows($db_ext) > 0){
    while ($data = mysqli_fetch_array($db_ext)) 
    {
        $jdata[] = $data;
    }

    $jsondata["isSuccessful"]   = true;
    $jsondata["ret"]            = "1";
    $jsondata["data"]           = $jdata;
    $jsondata["jml_page"]       = $v_jml_page;
    $jsondata["jml_data"]       = $v_total_data;
    $jsondata["msg"]            = "Sukses.";
    echo json_encode($jsondata);

} else {
    $jsondata["isSuccessful"]   = false;
    $jsondata["ret"]            = "-1";
    $jsondata["msg"]            = "Tidak ada kategori pelajaran.";
    echo json_encode($jsondata);
}


?>


