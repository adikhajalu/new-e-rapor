<?php
include '../includes/session.php';
include '../koneksi/koneksi.php';

$v_nama_mapel       = mysqli_real_escape_string($db_connect, trim($_POST['namaMapel']));
$v_tahun_pelajaran  = mysqli_real_escape_string($db_connect, trim($_POST['tahunAjaran']));
// $v_semester         = mysqli_real_escape_string($db_connect, trim($_POST['semester']));
$v_keterangan       = mysqli_real_escape_string($db_connect, trim($_POST['keterangan']));
$v_id_kategori      = mysqli_real_escape_string($db_connect, trim($_POST['idKategoriMapel']));
$v_id_sub_kategori  = mysqli_real_escape_string($db_connect, trim($_POST['idSubKategoriMapel']));
$v_id_sub_kategori  = empty($v_id_sub_kategori) ? "NULL" : "'$v_id_sub_kategori'";

try 
{
    $query_max  = "SELECT MAX(ID_MAPEL) AS MAX_ID FROM tb_mapel WHERE ID_MAPEL LIKE 'MP%'";
    $result_max = mysqli_query($db_connect, $query_max);
    $row_max    = mysqli_fetch_assoc($result_max);

    if ($row_max['MAX_ID']) {
        $last_num = intval(substr($row_max['MAX_ID'], 2));
        $next_num = $last_num + 1;
    } else {
        $next_num = 1;
    }
    $v_id_mapel             = 'MP' . str_pad($next_num, 7, '0', STR_PAD_LEFT);
    $v_id_mapel_smst_ganjil = $v_id_mapel . '.1';
    $v_id_mapel_smst_genap  = $v_id_mapel . '.2';

    $query  = "INSERT INTO tb_mapel (ID_MAPEL, ID_MAPEL_SEMESTER, NAMA_MAPEL,ID_KATEGORI_MAPEL,ID_SUB_KATEGORI_MAPEL,TAHUN_PELAJARAN,SEMESTER,KETERANGAN,TGL_REKAM,PETUGAS_REKAM) 
                       VALUES ('$v_id_mapel', '$v_id_mapel_smst_ganjil', '$v_nama_mapel','$v_id_kategori',$v_id_sub_kategori,'$v_tahun_pelajaran','Gasal','$v_keterangan',NOW(),'$session_user_id'),
                              ('$v_id_mapel', '$v_id_mapel_smst_genap', '$v_nama_mapel','$v_id_kategori',$v_id_sub_kategori,'$v_tahun_pelajaran','Genap','$v_keterangan',NOW(),'$session_user_id') ";
    $db_ext = mysqli_query($db_connect,$query);

    if($db_ext == true)
    {
        $jsondata["isSuccessful"]   = true;
        $jsondata["ret"]            = "1";
        $jsondata["msg"]            = "Berhasil simpan data.";
        echo json_encode($jsondata);

    }
}
catch (mysqli_sql_exception $e) 
{
    $jsondata["isSuccessful"]   = false;
    $jsondata["ret"]            = "-1";
    $jsondata["msg"]            = "Gagal menyimpan data, silakan coba beberapa saat lagi.";
    $jsondata["sysErrMsg"]      = $e->getMessage();
    if (mysqli_errno($db_connect) == 1062) 
    {
        $jsondata["msg"] = "Data Mata Pelajaran pada Tahun Ajaran tersebut sudah ada, silakan cek kembali data yang akan disimpan.";
    }
    echo json_encode($jsondata);
    exit();
}




?>

