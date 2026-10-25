<?php
include '../includes/session.php';
include '../koneksi/koneksi.php';

$v_search_type              = $_POST['searchType'];
$v_search_val               = mysqli_real_escape_string($db_connect, $_POST['searchVal']);
$v_search_tahun_pelajaran   = $_POST['searchTahunAjaran'];
$v_page                     = (int)$_POST['page'];
$v_jml_per_page             = (int)$_POST['jmlPerPage'];
$v_filter_tahun_pelajaran   = "";

if($v_search_type == "" || $v_search_val == ""){
    $v_filter = "";
} else {
    if($v_search_type == "NIS"){
        $v_filter = " AND UPPER(NIS) = UPPER('$v_search_val') ";
    } else if($v_search_type == "NAMA_SISWA"){
        $v_filter = " AND UPPER(NAMA_SISWA) like UPPER('%$v_search_val%') ";
    } else if($v_search_type == "KELAS"){
        $v_filter = " AND UPPER(KELAS) = UPPER('$v_search_val') ";
    // } else if($v_search_type == "SEMESTER"){
    //     $v_filter = " AND UPPER(SEMESTER) = UPPER('$v_search_val') ";
    } else {
        $v_filter = "";
    }
}


if($v_search_tahun_pelajaran != ""){
    $v_filter_tahun_pelajaran = " AND UPPER(TAHUN_PELAJARAN) = UPPER('$v_search_tahun_pelajaran') ";
}


$query_count    = "SELECT COUNT(1) CNT_ROW FROM tb_siswa A 
                    WHERE 1=1 $v_filter $v_filter_tahun_pelajaran";
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
                    SELECT ROW_NUMBER() OVER (ORDER BY  A.TAHUN_PELAJARAN DESC, A.KELAS, A.NIS, A.TGL_REKAM DESC) AS NOMOR, 
                           A.* FROM 
                           (
                                SELECT X.NIS, X.NAMA_SISWA, X.KELAS, X.TAHUN_PELAJARAN, X.TGL_REKAM, X.SEMESTER
                                FROM tb_siswa X WHERE 1=1 $v_filter $v_filter_tahun_pelajaran
                                ORDER BY X.TAHUN_PELAJARAN DESC, X.KELAS, X.NIS, X.TGL_REKAM DESC
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


