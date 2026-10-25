<?php
include '../includes/session.php';
include '../koneksi/koneksi.php';

$v_page         = (int)$_POST['page'];
$v_jml_per_page = (int)$_POST['jmlPerPage'];

$query_count    = "SELECT COUNT(1) CNT_ROW FROM ( 
                        SELECT XY.* FROM (
                            SELECT X.ID_KATEGORI_MAPEL, X.NAMA_KATEGORI_MAPEL, '-' ID_SUB_KATEGORI_MAPEL, '-' NAMA_SUB_KATEGORI_MAPEL FROM `tb_kategori_mapel` X
                            UNION
                            SELECT Y.ID_KATEGORI_MAPEL, (SELECT B.NAMA_KATEGORI_MAPEL FROM `tb_kategori_mapel` B WHERE B.ID_KATEGORI_MAPEL=Y.ID_KATEGORI_MAPEL) NAMA_KATEGORI_MAPEL, Y.ID_SUB_KATEGORI_MAPEL, Y.NAMA_SUB_KATEGORI_MAPEL FROM `tb_sub_kategori_mapel` Y) XY
                        ORDER BY XY.ID_KATEGORI_MAPEL ASC, XY.ID_SUB_KATEGORI_MAPEL ASC) Z";
$db_ext         = mysqli_query($db_connect,$query_count);
$data_count     = mysqli_fetch_array($db_ext);
$v_total_data   = $data_count['CNT_ROW'];
$v_data_perpage = $v_jml_per_page;   // jumlah data per halaman
$v_jml_page     = ceil($v_total_data / $v_data_perpage);
$v_first_num    = ($v_page - 1) * $v_data_perpage + 1;
$v_last_num     = $v_first_num + $v_data_perpage - 1;
$v_page_filter  = " AND Z.NOMOR BETWEEN $v_first_num AND $v_last_num ";

$query_list     = "SELECT Z.* FROM ( 
                        SELECT ROW_NUMBER() OVER (ORDER BY XY.TAHUN_PELAJARAN DESC,XY.ID_KATEGORI_MAPEL ASC, XY.ID_SUB_KATEGORI_MAPEL ASC) AS NOMOR, XY.* FROM (
                            SELECT X.ID_KATEGORI_MAPEL, X.TAHUN_PELAJARAN, X.NAMA_KATEGORI_MAPEL, '-' ID_SUB_KATEGORI_MAPEL, '-' NAMA_SUB_KATEGORI_MAPEL FROM `tb_kategori_mapel` X
                            UNION
                            SELECT Y.ID_KATEGORI_MAPEL, (SELECT B.TAHUN_PELAJARAN FROM `tb_kategori_mapel` B WHERE B.ID_KATEGORI_MAPEL=Y.ID_KATEGORI_MAPEL) TAHUN_PELAJARAN, (SELECT B.NAMA_KATEGORI_MAPEL FROM `tb_kategori_mapel` B WHERE B.ID_KATEGORI_MAPEL=Y.ID_KATEGORI_MAPEL) NAMA_KATEGORI_MAPEL, Y.ID_SUB_KATEGORI_MAPEL, Y.NAMA_SUB_KATEGORI_MAPEL FROM `tb_sub_kategori_mapel` Y) XY
                        ORDER BY XY.TAHUN_PELAJARAN DESC, XY.ID_KATEGORI_MAPEL ASC, XY.ID_SUB_KATEGORI_MAPEL ASC) Z
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
    $jsondata["msg"]            = "Tidak ada kategori pelajaran.";
    echo json_encode($jsondata);
}


?>


