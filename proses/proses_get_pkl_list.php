<?php
include '../includes/session.php';
include '../koneksi/koneksi.php';

$v_search_type              = $_POST['searchType'];
$v_search_val               = mysqli_real_escape_string($db_connect, $_POST['searchVal']);
$v_search_lokasi            = mysqli_real_escape_string($db_connect, $_POST['searchLokasi']);
$v_search_kelas             = mysqli_real_escape_string($db_connect, $_POST['searchKelas']);
$v_search_semester          = mysqli_real_escape_string($db_connect, $_POST['searchSemester']);
$v_search_tahun_pelajaran   = mysqli_real_escape_string($db_connect, $_POST['searchTahunAjaran']);
$v_page                     = (int)$_POST['page'];
$v_jml_per_page             = (int)$_POST['jmlPerPage'];
$v_filter                   = "";
$v_filter_mapel             = "";
$v_filter_kelas             = "";
$v_filter_semester          = "";
$v_filter_tahun_pelajaran   = "";
$v_filter_nama_siswa        = "";
$v_filter_lokasi            = '';

if($v_search_type == "" || $v_search_val == ""){
    $v_filter       = "";
    $v_filter_mapel = "";
} else {
    if($v_search_type == "NIS"){
        $v_filter = " AND UPPER(A.NIS) = UPPER('$v_search_val') ";
    } else if($v_search_type == "NAMA_SISWA"){
        $v_filter_nama_siswa = " AND UPPER(B.NAMA_SISWA) like UPPER('%$v_search_val%') ";
    } else if($v_search_type == "MITRA_DU_DI"){
        $v_filter = " AND UPPER(A.MITRA_DU_DI) like UPPER('%$v_search_val%') ";
    }
}

if($v_search_lokasi != ""){
    $v_filter_lokasi = " AND UPPER(A.LOKASI) = UPPER('$v_search_lokasi') ";
}

if($v_search_kelas != ""){
    $v_filter_kelas = " AND UPPER(A.KELAS) = UPPER('$v_search_kelas') ";
}

if($v_search_semester != ""){
    $v_filter_semester = " AND UPPER(A.SEMESTER) = UPPER('$v_search_semester') ";
}

if($v_search_tahun_pelajaran != ""){
    $v_filter_tahun_pelajaran = " AND UPPER(A.TAHUN_PELAJARAN) = UPPER('$v_search_tahun_pelajaran') ";
}
    

$query_count    = "SELECT COUNT(*) AS CNT_ROW FROM (
                        SELECT A.*,
                        (SELECT X.NAMA_SISWA FROM tb_siswa X WHERE X.NIS = A.NIS AND X.KELAS = A.KELAS AND X.TAHUN_PELAJARAN = A.TAHUN_PELAJARAN AND X.SEMESTER = A.SEMESTER) NAMA_SISWA
                      FROM tb_pkl A 
                        WHERE   1=1 $v_filter $v_filter_kelas $v_filter_semester $v_filter_tahun_pelajaran $v_filter_lokasi) B
                    WHERE 1=1 $v_filter_nama_siswa";
$db_ext         = mysqli_query($db_connect,$query_count);
$data_count     = mysqli_fetch_array($db_ext);
$v_total_data   = $data_count['CNT_ROW'];
$v_data_perpage = $v_jml_per_page;   // jumlah data per halaman
$v_jml_page     = ceil($v_total_data / $v_data_perpage);
$v_first_num    = ($v_page - 1) * $v_data_perpage + 1;
$v_last_num     = $v_first_num + $v_data_perpage - 1;
$v_page_filter  = " AND c.NOMOR BETWEEN $v_first_num AND $v_last_num ";

if($v_filter != ""){
    $v_page_filter = "";
}

$query_list     = "SELECT C.*
                    FROM (
                         SELECT B.*, ROW_NUMBER() OVER (ORDER BY B.TAHUN_PELAJARAN DESC, B.SEMESTER DESC, B.KELAS, B.NAMA_SISWA, B.TGL_REKAM DESC) AS NOMOR FROM(
                        SELECT A.*,
                        (SELECT X.NAMA_SISWA FROM tb_siswa X WHERE X.NIS = A.NIS AND X.KELAS = A.KELAS AND X.TAHUN_PELAJARAN = A.TAHUN_PELAJARAN AND X.SEMESTER = A.SEMESTER) NAMA_SISWA
                      FROM tb_pkl A 
                        WHERE   1=1 $v_filter $v_filter_kelas $v_filter_semester $v_filter_tahun_pelajaran $v_filter_lokasi) B
                        WHERE 1=1 $v_filter_mapel $v_filter_nama_siswa ORDER BY B.TAHUN_PELAJARAN DESC, B.SEMESTER DESC, B.KELAS, B.NAMA_SISWA, B.TGL_REKAM DESC ) C
                    WHERE 1=1 $v_page_filter";    
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


