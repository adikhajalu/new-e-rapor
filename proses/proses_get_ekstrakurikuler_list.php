<?php
include '../includes/session.php';
include '../koneksi/koneksi.php';

$v_search_type  = $_POST['searchType'];
$v_search_val   = mysqli_real_escape_string($db_connect,$_POST['searchVal']);
$v_page         = (int)$_POST['page'];
$v_jml_per_page = (int)$_POST['jmlPerPage'];

if($v_search_type == "" || $v_search_val == ""){
    $v_filter = "";
} else {
    if($v_search_type == "NAMA_EKSTRAKURIKULER"){
        $v_filter = " AND UPPER(NAMA_EKSTRAKURIKULER) = TRIM(UPPER('$v_search_val')) ";
    } else if($v_search_type == "TAHUN_PELAJARAN"){
        $v_filter = " AND UPPER(TAHUN_PELAJARAN) = TRIM(UPPER('$v_search_val')) ";
    // } else if($v_search_type == "SEMESTER"){
    //     $v_filter = " AND UPPER(SEMESTER) = UPPER('$v_search_val') ";
    } else {
        $v_filter = "";
    }
}

$query_count    = "SELECT COUNT(1) CNT_ROW FROM (
                                SELECT DISTINCT X.ID_EKSTRAKURIKULER, X.TAHUN_PELAJARAN
                                FROM tb_ekstrakurikuler X WHERE 1=1 $v_filter
                           ) A";
$db_ext         = mysqli_query($db_connect,$query_count);
$data_count     = mysqli_fetch_array($db_ext);
$v_total_data   = $data_count['CNT_ROW'];
$v_data_perpage = $v_jml_per_page;   // jumlah data per halaman
$v_jml_page     = ceil($v_total_data / $v_data_perpage);
$v_first_num    = ($v_page - 1) * $v_data_perpage + 1;
$v_last_num     = $v_first_num + $v_data_perpage - 1;
$v_page_filter  = " AND B.NOMOR BETWEEN $v_first_num AND $v_last_num ";

if($v_filter != ""){
    $v_page_filter = "";
}

$query_list     = "SELECT B.* FROM(
                    SELECT ROW_NUMBER() OVER (ORDER BY A.TAHUN_PELAJARAN DESC, A.NAMA_EKSTRAKURIKULER ASC) AS NOMOR, 
                           A.* 
                           FROM ( SELECT DISTINCT X.NAMA_EKSTRAKURIKULER, X.TAHUN_PELAJARAN, X.ID_EKSTRAKURIKULER FROM tb_ekstrakurikuler X  
                                    WHERE 1=1 $v_filter
                                    ORDER BY X.TAHUN_PELAJARAN DESC, X.NAMA_EKSTRAKURIKULER DESC
                                ) A 
                    ) B 
                   WHERE 1=1 $v_page_filter ";    
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
    $jsondata["jml_page"]       = $v_jml_page;
    $jsondata["jml_data"]       = $v_total_data;
    $jsondata["msg"]            = "Sukses.";
    echo json_encode($jsondata);

} else {
    $jsondata["isSuccessful"]   = false;
    $jsondata["ret"]            = "-1";
    $jsondata["msg"]            = "Gagal memuat data, silakan coba beberapa saat lagi.";
    echo json_encode($jsondata);
}


?>


