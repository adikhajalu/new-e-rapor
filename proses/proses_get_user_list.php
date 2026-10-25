<?php
include '../includes/session.php';
include '../koneksi/koneksi.php';

$v_search_type  = $_POST['searchType'];
$v_search_val   = mysqli_real_escape_string($db_connect, $_POST['searchVal']);
$v_page         = (int)$_POST['page'];
$v_jml_per_page = (int)$_POST['jmlPerPage'];

if($v_search_type == "" || $v_search_val == ""){
    $v_filter = "";
} else {
    if($v_search_type == "NIP"){
        $v_filter = " AND UPPER(NIP) = UPPER('$v_search_val') ";
    } else if($v_search_type == "NAMA"){
        $v_filter = " AND UPPER(NAMA) like UPPER('%$v_search_val%') ";
    } else if($v_search_type == "POSISI_JABATAN"){
        $v_filter = " AND UPPER(POSISI_JABATAN) like UPPER('%$v_search_val%') ";
    } else if($v_search_type == "STATUS_AKTIF"){
        $v_filter = " AND UPPER(STATUS_AKTIF) = UPPER('$v_search_val') ";
    } else {
        $v_filter = "";
    }
}

$query_count    = "SELECT COUNT(1) CNT_ROW FROM tb_user A 
                    WHERE 1=1 $v_filter";
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
                    SELECT ROW_NUMBER() OVER (ORDER BY A.NAMA, A.TGL_REKAM DESC) AS NOMOR, 
                           A.* FROM tb_user A 
                    WHERE 1=1 $v_filter
                   ORDER BY A.NAMA, A.TGL_REKAM DESC) B 
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


