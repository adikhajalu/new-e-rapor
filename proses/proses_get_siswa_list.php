<?php
include '../includes/session.php';
include '../koneksi/koneksi.php';

$v_search_type              = $_POST['searchType'];
$v_search_val               = mysqli_real_escape_string($db_connect,$_POST['searchVal']);
$v_search_tahun_pelajaran   = mysqli_real_escape_string($db_connect,$_POST['searchTahunAjaran']);
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
    } else if($v_search_type == "NAMA_WALI_KELAS"){
        $v_filter = " AND UPPER(NAMA_WALI_KELAS) = UPPER('$v_search_val') ";
    } else if($v_search_type == "NIP_WALI_KELAS"){
        $v_filter = " AND UPPER(NIP_WALI_KELAS) = UPPER('$v_search_val') ";
    } else {
        $v_filter = "";
    }
}


if($v_search_tahun_pelajaran != ""){
    $v_filter_tahun_pelajaran = " AND UPPER(TAHUN_PELAJARAN) = UPPER('$v_search_tahun_pelajaran') ";
}


$query_count    = "SELECT COUNT(1) CNT_ROW FROM(
                        SELECT
                            A.*, 
                            (SELECT (SELECT WW.NAMA FROM tb_user WW WHERE WW.NIP = W.NIP) FROM tb_wali_kelas W WHERE W.KELAS = A.KELAS AND W.TAHUN_PELAJARAN = A.TAHUN_PELAJARAN LIMIT 1) NAMA_WALI_KELAS, 
                            (SELECT W.NIP FROM tb_wali_kelas W WHERE W.KELAS = A.KELAS AND W.TAHUN_PELAJARAN = A.TAHUN_PELAJARAN LIMIT 1) NIP_WALI_KELAS
                            FROM 
                            (
                                    SELECT DISTINCT X.NIS, X.NAMA_SISWA, X.KELAS, X.TAHUN_PELAJARAN, X.TGL_REKAM 
                                    FROM tb_siswa X WHERE 1=1
                                    ORDER BY X.TAHUN_PELAJARAN DESC, X.KELAS, X.NIS, X.TGL_REKAM DESC
                            ) A 
                    ) B WHERE 1=1 $v_filter $v_filter_tahun_pelajaran";
$db_ext         = mysqli_query($db_connect,$query_count);
$data_count     = mysqli_fetch_array($db_ext);
$v_total_data   = $data_count['CNT_ROW'];
$v_data_perpage = $v_jml_per_page;   // jumlah data per halaman
$v_jml_page     = ceil($v_total_data / $v_data_perpage);
$v_first_num    = ($v_page - 1) * $v_data_perpage + 1;
$v_last_num     = $v_first_num + $v_data_perpage - 1;
$v_page_filter  = " AND X.NOMOR BETWEEN $v_first_num AND $v_last_num ";

if($v_filter != ""){
    $v_page_filter = "";
}

$query_list     = "SELECT X.* FROM(
                        SELECT ROW_NUMBER() OVER (ORDER BY  B.TAHUN_PELAJARAN DESC, B.KELAS, B.NAMA_SISWA, B.TGL_REKAM DESC) AS NOMOR, 
                                B.* FROM(
                                SELECT
                                    A.*, 
                                    (SELECT (SELECT WW.NAMA FROM tb_user WW WHERE WW.NIP = W.NIP) FROM tb_wali_kelas W WHERE W.KELAS = A.KELAS AND W.TAHUN_PELAJARAN = A.TAHUN_PELAJARAN LIMIT 1) NAMA_WALI_KELAS, 
                                    (SELECT W.NIP FROM tb_wali_kelas W WHERE W.KELAS = A.KELAS AND W.TAHUN_PELAJARAN = A.TAHUN_PELAJARAN LIMIT 1) NIP_WALI_KELAS
                                    FROM 
                                    (
                                            SELECT DISTINCT X.NIS, X.NAMA_SISWA, X.KELAS, X.TAHUN_PELAJARAN, X.TGL_REKAM 
                                            FROM tb_siswa X WHERE 1=1
                                            ORDER BY X.TAHUN_PELAJARAN DESC, X.KELAS, X.NAMA_SISWA, X.TGL_REKAM DESC
                                    ) A 
                            ) B WHERE 1=1 $v_filter $v_filter_tahun_pelajaran
                        ) X
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


