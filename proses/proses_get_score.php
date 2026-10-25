<?php
include '../includes/session.php';
include '../koneksi/koneksi.php';

$v_search_type              = $_POST['searchType'];
$v_search_val               = mysqli_real_escape_string($db_connect, $_POST['searchVal']);
$v_page                     = (int)$_POST['page'];
$v_jml_per_page             = (int)$_POST['jmlPerPage'];
$v_filter                   = "";
$v_filter_mapel             = "";
$v_search_kelas             = mysqli_real_escape_string($db_connect, $_POST['searchKelas']);
$v_search_semester          = mysqli_real_escape_string($db_connect, $_POST['searchSemester']);
$v_search_tahun_pelajaran   = mysqli_real_escape_string($db_connect, $_POST['searchTahunAjaran']);
$v_filter_kelas             = "";
$v_filter_semester          = "";
$v_filter_tahun_pelajaran   = "";
$v_status_admin             = $_POST['statusAdmin'];

if($v_search_type == "" || $v_search_val == ""){
    $v_filter       = "";
    $v_filter_mapel = "";
} else {
    if($v_search_type == "NIS"){
        $v_filter = " AND UPPER(A.NIS) = UPPER('$v_search_val') ";
    } else {
        $v_filter = "";
        if($v_search_type == "NAMA_MAPEL"){
            $v_filter_mapel = " AND UPPER(B.NAMA_MAPEL) like UPPER('%$v_search_val%') ";
        } else if($v_search_type == "NAMA_GURU"){
            $v_filter_mapel = " AND UPPER(B.NAMA_GURU) like UPPER('%$v_search_val%') ";
        } else if($v_search_type == "NAMA_SISWA"){
            $v_filter_mapel = " AND UPPER(B.NAMA_SISWA) like UPPER('%$v_search_val%') ";
        } else {
            $v_filter_mapel = "";
        }
    }
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
                         SELECT B.*, ROW_NUMBER() OVER (ORDER BY B.TAHUN_PELAJARAN DESC, B.SEMESTER DESC, B.KELAS, B.NIS) AS NOMOR FROM(
                            SELECT A.*, (SELECT X.NAMA_MAPEL FROM tb_mapel X WHERE X.ID_MAPEL = A.ID_MAPEL LIMIT 1) NAMA_MAPEL,
                                        (SELECT B.NAMA FROM tb_user B WHERE B.NIP = A.NIP_GURU) NAMA_GURU,
                                        (SELECT B.NAMA_SISWA FROM tb_siswa B WHERE B.NIS = A.NIS AND B.KELAS = A.KELAS AND B.TAHUN_PELAJARAN = A.TAHUN_PELAJARAN LIMIT 1) NAMA_SISWA
                                FROM tb_penilaian A 
                            WHERE 1=1 $v_filter $v_filter_kelas $v_filter_semester $v_filter_tahun_pelajaran
                                    AND (
                                            EXISTS (
                                                        SELECT NULL
                                                        FROM tb_guru_mapel B
                                                        WHERE B.NIP = '$session_user_id'
                                                        AND B.ID_MAPEL_SEMESTER = CONCAT(A.ID_MAPEL,CASE WHEN UPPER(A.SEMESTER) = 'GENAP' THEN '.2' ELSE '.1' END)
                                                        AND B.KELAS = A.KELAS
                                                        AND B.TAHUN_PELAJARAN = A.TAHUN_PELAJARAN
                                                   )
                                            OR
                                            '$v_status_admin' = 'Y'
                                        )
                            ORDER BY A.TAHUN_PELAJARAN DESC, A.SEMESTER DESC, A.KELAS, A.NIS) B 
                        WHERE 1=1 $v_filter_mapel) C ";
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

$query_list     = "SELECT C.*, CASE WHEN (SELECT COUNT(1) FROM tb_jadwal_penilaian XX WHERE CURDATE() BETWEEN TGL_AWAL AND TGL_AKHIR AND XX.TAHUN_PELAJARAN = C.TAHUN_PELAJARAN AND XX.SEMESTER = C.SEMESTER) > 0 THEN 'Y' ELSE 'T' END STATUS_BOLEH_UBAH FROM (
                         SELECT B.*, ROW_NUMBER() OVER (ORDER BY B.TAHUN_PELAJARAN DESC, B.SEMESTER DESC, B.KELAS ASC, B.NAMA_MAPEL ASC, B.NAMA_SISWA ASC) AS NOMOR FROM(
                            SELECT A.*, (SELECT X.NAMA_MAPEL FROM tb_mapel X WHERE X.ID_MAPEL = A.ID_MAPEL LIMIT 1) NAMA_MAPEL,
                                        (SELECT B.NAMA FROM tb_user B WHERE B.NIP = A.NIP_GURU) NAMA_GURU,
                                        (SELECT B.NAMA_SISWA FROM tb_siswa B WHERE B.NIS = A.NIS AND B.KELAS = A.KELAS AND B.TAHUN_PELAJARAN = A.TAHUN_PELAJARAN LIMIT 1) NAMA_SISWA
                                FROM tb_penilaian A 
                            WHERE 1=1 $v_filter $v_filter_kelas $v_filter_semester $v_filter_tahun_pelajaran
                                    AND (
                                            EXISTS (
                                                        SELECT NULL
                                                        FROM tb_guru_mapel B
                                                        WHERE B.NIP = '$session_user_id'
                                                        AND B.ID_MAPEL_SEMESTER = CONCAT(A.ID_MAPEL,CASE WHEN UPPER(A.SEMESTER) = 'GENAP' THEN '.2' ELSE '.1' END)
                                                        AND B.KELAS = A.KELAS
                                                        AND B.TAHUN_PELAJARAN = A.TAHUN_PELAJARAN
                                                   )
                                            OR
                                            EXISTS (
                                                        SELECT NULL
                                                        FROM tb_wali_kelas B
                                                        WHERE B.NIP = '$session_user_id'
                                                        AND B.KELAS = A.KELAS
                                                        AND B.TAHUN_PELAJARAN = A.TAHUN_PELAJARAN
                                                   )
                                            OR
                                            '$v_status_admin' = 'Y'
                                        )
                            ) B 
                        WHERE 1=1 $v_filter_mapel ORDER BY B.TAHUN_PELAJARAN DESC, B.SEMESTER DESC, B.KELAS ASC, B.NAMA_MAPEL ASC, B.NAMA_SISWA ASC) C
                    WHERE 1=1 $v_page_filter";    
// var_dump($query_list);die;
try {
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
        $jsondata["ret"]            = "-2";
        $jsondata["msg"]            = "Data penilaian tidak ditemukan.";
        echo json_encode($jsondata);
    }
} catch (mysqli_sql_exception $e) {
    $jsondata["isSuccessful"]   = false;
    $jsondata["ret"]            = "-1";
    $jsondata["msg"]            = "Gagal memuat data, silakan coba beberapa saat lagi.";
    $jsondata["sysErrMsg"]      = $e->getMessage();
    echo json_encode($jsondata);
}

?>


