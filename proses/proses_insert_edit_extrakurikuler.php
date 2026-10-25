<?php
include '../includes/session.php';
include '../koneksi/koneksi.php';

$v_id_extrakurikuler    = mysqli_real_escape_string($db_connect, trim($_POST['idExtrakurikuler']));
$v_nama_extrakurikuler  = mysqli_real_escape_string($db_connect, trim($_POST['namaExtrakurikuler']));
$v_tahun_pelajaran      = mysqli_real_escape_string($db_connect, trim($_POST['tahunAjaran']));
// $v_semester             = mysqli_real_escape_string($db_connect, trim($_POST['semester']));

$v_filter       = " AND UPPER(NAMA_EKSTRAKURIKULER) = UPPER('$v_id_extrakurikuler') AND UPPER(TAHUN_PELAJARAN) = UPPER('$v_tahun_pelajaran') "; // AND UPPER(SEMESTER) = UPPER('$v_semester')
$query_count    = "SELECT COUNT(1) CNT_ROW FROM tb_ekstrakurikuler A 
                    WHERE 1=1 $v_filter";
$db_ext         = mysqli_query($db_connect,$query_count);
$data_count     = mysqli_fetch_array($db_ext);
$v_total_exist  = $data_count['CNT_ROW'];

if($v_total_exist > 0){
    $jsondata["isSuccessful"]   = false;
    $jsondata["ret"]            = "-1";
    $jsondata["msg"]            = "Data Extrakurikuler dengan Tahun Ajaran tersebut sudah ada, silakan cek kembali data yang akan disimpan.";
    echo json_encode($jsondata);
    exit();
}
else 
{
    try 
    {
        if($v_id_extrakurikuler != ""){
            $query  = "UPDATE tb_ekstrakurikuler SET NAMA_EKSTRAKURIKULER = '$v_nama_extrakurikuler', TAHUN_PELAJARAN = '$v_tahun_pelajaran', TGL_UBAH = NOW(), PETUGAS_UBAH = '$session_user_id' 
                    WHERE ID_EKSTRAKURIKULER = '$v_id_extrakurikuler' ";
            $db_ext = mysqli_query($db_connect,$query);
        } else {
            $query_max  = "SELECT MAX(ID_EKSTRAKURIKULER) AS MAX_ID FROM tb_ekstrakurikuler WHERE ID_EKSTRAKURIKULER LIKE 'EXR%'";
            $result_max = mysqli_query($db_connect, $query_max);
            $row_max    = mysqli_fetch_assoc($result_max);

            if ($row_max['MAX_ID']) {
                $last_num = intval(substr($row_max['MAX_ID'], 3));
                $next_num = $last_num + 1;
            } else {
                $next_num = 1;
            }
            $v_id_extrakurikuler        = 'EXR' . str_pad($next_num, 7, '0', STR_PAD_LEFT);
            $v_id_extrakurikuler_ganjil = $v_id_extrakurikuler . '.1';
            $v_id_extrakurikuler_genap  = $v_id_extrakurikuler . '.2';

            $query  = "INSERT INTO tb_ekstrakurikuler (ID_EKSTRAKURIKULER, ID_EKSTRAKURIKULER_SEMESTER, NAMA_EKSTRAKURIKULER, TAHUN_PELAJARAN, SEMESTER, TGL_REKAM, PETUGAS_REKAM) 
                            VALUES ('$v_id_extrakurikuler', '$v_id_extrakurikuler_ganjil', '$v_nama_extrakurikuler','$v_tahun_pelajaran','Gasal',NOW(),'$session_user_id'),
                                    ('$v_id_extrakurikuler', '$v_id_extrakurikuler_genap', '$v_nama_extrakurikuler','$v_tahun_pelajaran','Genap',NOW(),'$session_user_id') ";
            $db_ext = mysqli_query($db_connect,$query);
        }

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
            $jsondata["msg"] = "Data Ekstrakurikuler dengan Tahun Ajaran tersebut sudah ada, silakan cek kembali data yang akan disimpan.";
        }
        echo json_encode($jsondata);
        exit();
    }

}



?>

