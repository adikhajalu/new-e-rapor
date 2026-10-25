<?php
include '../includes/session.php';
include '../koneksi/koneksi.php';

$v_search_type              = $_POST['searchType'];
$v_search_val               = mysqli_real_escape_string($db_connect, $_POST['searchVal']);
$v_semester                 = mysqli_real_escape_string($db_connect, $_POST['searchSemester']);
$v_tahun_pelajaran          = mysqli_real_escape_string($db_connect, $_POST['searchTahunAjaran']);
$v_page                     = (int)$_POST['page'];
$v_jml_per_page             = (int)$_POST['jmlPerPage'];
$v_filter_semester          = "";
$v_filter_tahun_pelajaran   = "";

if($v_search_type == "" || $v_search_val == ""){
    $v_filter = "";
} else {
    if($v_search_type == "NIP"){
        $v_filter = " AND UPPER(NIP) = UPPER('$v_search_val') ";
    } else if($v_search_type == "NAMA_GURU"){
        $v_filter = " AND UPPER(NAMA_GURU) LIKE UPPER('%$v_search_val%') ";
    } else if($v_search_type == "KELAS"){
        $v_filter = " AND UPPER(KELAS) LIKE UPPER('%$v_search_val%') ";
    } else if($v_search_type == "NAMA_MAPEL"){
        $v_filter = " AND UPPER(NAMA_MAPEL) LIKE UPPER('%$v_search_val%') ";
    } else {
        $v_filter = "";
    }
}

if($v_semester != ""){
     $v_filter_semester = " AND UPPER(SEMESTER) = UPPER('$v_semester') ";
}

if($v_tahun_pelajaran != ""){
    $v_filter_tahun_pelajaran = " AND UPPER(TAHUN_PELAJARAN) = UPPER('$v_tahun_pelajaran') ";
}

$query_count    = "SELECT COUNT(1) CNT_ROW FROM (
                        SELECT  Y.NIP, 
                                (SELECT X.NAMA FROM tb_user X WHERE X.NIP = Y.NIP) NAMA_GURU, 
                                (SELECT X.NAMA_MAPEL FROM tb_mapel X WHERE X.ID_MAPEL_SEMESTER = Y.ID_MAPEL_SEMESTER) NAMA_MAPEL,
                                Y.KELAS,
                                (SELECT X.SEMESTER FROM tb_mapel X WHERE X.ID_MAPEL_SEMESTER = Y.ID_MAPEL_SEMESTER) SEMESTER,
                                (SELECT X.TAHUN_PELAJARAN FROM tb_mapel X WHERE X.ID_MAPEL_SEMESTER = Y.ID_MAPEL_SEMESTER) TAHUN_PELAJARAN
                        FROM tb_guru_mapel Y) A
                        WHERE 1=1 $v_filter $v_filter_semester $v_filter_tahun_pelajaran ";
$db_ext         = mysqli_query($db_connect,$query_count);
$data_count     = mysqli_fetch_array($db_ext);
$v_total_data   = $data_count['CNT_ROW'];
$v_data_perpage = $v_jml_per_page;   // jumlah data per halaman
$v_jml_page     = ceil($v_total_data / $v_data_perpage);
$v_first_num    = ($v_page - 1) * $v_data_perpage + 1;
$v_last_num     = $v_first_num + $v_data_perpage - 1;
$v_page_filter  = " AND Z.NOMOR BETWEEN $v_first_num AND $v_last_num ";

if($v_filter != ""){
    $v_page_filter = "";
}

$query_list     = "SELECT Z.* FROM(
                    SELECT 	ROW_NUMBER() OVER (ORDER BY A.TAHUN_PELAJARAN DESC, A.SEMESTER, A.KELAS, A.ID_MAPEL_SEMESTER) AS NOMOR, 
                            A.* FROM (
                        SELECT  Y.NIP, 
                                (SELECT X.NAMA FROM tb_user X WHERE X.NIP = Y.NIP) NAMA_GURU, 
                                (SELECT X.NAMA_MAPEL FROM tb_mapel X WHERE X.ID_MAPEL_SEMESTER = Y.ID_MAPEL_SEMESTER) NAMA_MAPEL,
                                Y.KELAS,
                                (SELECT X.SEMESTER FROM tb_mapel X WHERE X.ID_MAPEL_SEMESTER = Y.ID_MAPEL_SEMESTER) SEMESTER,
                                Y.TAHUN_PELAJARAN,
                                Y.ID_MAPEL_SEMESTER
                        FROM tb_guru_mapel Y) A
                        WHERE 1=1 $v_filter $v_filter_semester $v_filter_tahun_pelajaran
                    ORDER BY A.TAHUN_PELAJARAN DESC, A.SEMESTER, A.KELAS, A.ID_MAPEL_SEMESTER) Z
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


