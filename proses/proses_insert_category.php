<?php
include '../includes/session.php';
include '../koneksi/koneksi.php';

$v_nama_kategori_mapel  = mysqli_real_escape_string($db_connect, trim($_POST['namaKategori']));
$v_tahun_ajaran         = mysqli_real_escape_string($db_connect, trim($_POST['tahunAjaran']));

$v_filter          = " AND UPPER(NAMA_KATEGORI_MAPEL) = UPPER('$v_nama_kategori_mapel') AND UPPER(TAHUN_PELAJARAN) = UPPER('$v_tahun_ajaran') ";

$query_count    = "SELECT COUNT(1) CNT_ROW FROM tb_kategori_mapel A 
                    WHERE 1=1 $v_filter";
$db_ext         = mysqli_query($db_connect,$query_count);
$data_count     = mysqli_fetch_array($db_ext);
$v_total_exist  = $data_count['CNT_ROW'];

                    // var_dump($query_count);
                    // var_dump($v_total_exist);die;

if($v_total_exist > 0){
    $jsondata["isSuccessful"]   = false;
    $jsondata["ret"]            = "-1";
    $jsondata["msg"]            = "Data Kategori Mata Pelajaran sudah pernah direkam sebelumnya, silakan cek ulang kembali.";
    echo json_encode($jsondata);
    exit();
}
else 
{
    $query_max  = "SELECT MAX(ID_KATEGORI_MAPEL) AS MAX_ID FROM tb_kategori_mapel WHERE ID_KATEGORI_MAPEL LIKE 'KMP%'";
    $result_max = mysqli_query($db_connect, $query_max);
    $row_max    = mysqli_fetch_assoc($result_max);

    if ($row_max['MAX_ID']) {
        $last_num = intval(substr($row_max['MAX_ID'], 3));
        $next_num = $last_num + 1;
    } else {
        $next_num = 1;
    }
    $v_id_kategori_mapel = 'KMP' . str_pad($next_num, 7, '0', STR_PAD_LEFT);

    $query  = "INSERT INTO tb_kategori_mapel (ID_KATEGORI_MAPEL, TAHUN_PELAJARAN, NAMA_KATEGORI_MAPEL,TGL_REKAM,PETUGAS_REKAM) 
                       VALUES ('$v_id_kategori_mapel', '$v_tahun_ajaran', TRIM('$v_nama_kategori_mapel'),NOW(),'$session_user_id') ";
    $db_ext = mysqli_query($db_connect,$query);

    if($db_ext == true)
    {
        $jsondata["isSuccessful"]   = true;
        $jsondata["ret"]            = "1";
        $jsondata["msg"]            = "Berhasil simpan data.";
        echo json_encode($jsondata);

    } else {
        $jsondata["isSuccessful"]   = false;
        $jsondata["ret"]            = "-1";
        $jsondata["msg"]            = "Gagal simpan data, silakan coba beberapa saat lagi.";
        echo json_encode($jsondata);
        exit();
    }
}



?>

