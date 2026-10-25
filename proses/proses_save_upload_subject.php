<?php
include '../includes/session.php';
include '../koneksi/koneksi.php';

$query_count    = "SELECT COUNT(1) CNT_ROW FROM tb_mapel_temp A 
                    WHERE 1=1 AND UPPER(PETUGAS_REKAM) = UPPER('$session_user_id') AND STATUS_VALID = 'T' ";
$db_ext         = mysqli_query($db_connect,$query_count);
$data_count     = mysqli_fetch_array($db_ext);
$v_total_invalid= $data_count['CNT_ROW'];

if($v_total_invalid > 0){
    $jsondata["isSuccessful"]   = false;
    $jsondata["ret"]            = "-1";
    $jsondata["msg"]            = "Pastikan semua Status Valid pada data Mata Pelajaran sudah tercentang.";
    echo json_encode($jsondata);
    exit();
}
else 
{

    
    $query_count    = "SELECT COUNT(1) CNT_ROW FROM `tb_mapel_temp` A WHERE 1=1 AND PETUGAS_REKAM = '$session_user_id'
                        AND EXISTS(SELECT NULL FROM `tb_mapel` B WHERE B.NAMA_MAPEL=A.NAMA_MAPEL AND B.TAHUN_PELAJARAN = A.TAHUN_PELAJARAN)"; // AND B.SEMESTER = A.SEMESTER
    $db_ext         = mysqli_query($db_connect,$query_count);
    $data_count     = mysqli_fetch_array($db_ext);
    $v_total_invalid= $data_count['CNT_ROW'];

    if($v_total_invalid > 0){
        $query  = "SELECT * FROM `tb_mapel` A WHERE 1=1
                            AND EXISTS(SELECT NULL FROM `tb_mapel_temp` B WHERE B.NAMA_MAPEL=A.NAMA_MAPEL AND B.TAHUN_PELAJARAN = A.TAHUN_PELAJARAN)"; // AND B.SEMESTER = A.SEMESTER
        $db_ext = mysqli_query($db_connect,$query);

        if(mysqli_num_rows($db_ext) > 0){
            while ($data = mysqli_fetch_array($db_ext)) 
            {
                $query_update   = "UPDATE tb_mapel_temp A SET STATUS_VALID = 'T', KETERANGAN = 'Data Mata Pelajaran sudah pernah direkam sebelumnya.' 
                                                        WHERE 1=1 AND UPPER(NAMA_MAPEL) = UPPER('".$data['NAMA_MAPEL']."') AND UPPER(TAHUN_PELAJARAN) = UPPER('".$data['TAHUN_PELAJARAN']."') AND UPPER(PETUGAS_REKAM) = UPPER('$session_user_id')";
                $db_upd         = mysqli_query($db_connect,$query_update);
            }
        }

        $jsondata["isSuccessful"]   = false;
        $jsondata["ret"]            = "-1";
        $jsondata["msg"]            = "Terdapat Perekaman data oleh user lain yang membuat data upload menjadi tidak valid. Silakan lakukan review ulang.";
        echo json_encode($jsondata);
        exit();
    }

    $query_temp = "SELECT NAMA_MAPEL, TAHUN_PELAJARAN, SEMESTER, TGL_REKAM, PETUGAS_REKAM, ID_KATEGORI_MAPEL, ID_SUB_KATEGORI_MAPEL
                    FROM tb_mapel_temp 
                    WHERE UPPER(PETUGAS_REKAM) = UPPER('$session_user_id')";
    $db_temp = mysqli_query($db_connect, $query_temp);

    $success = 0;
    $failed = 0;

    $query_max  = "SELECT MAX(ID_MAPEL) AS MAX_ID FROM tb_mapel WHERE ID_MAPEL LIKE 'MP%'";
    $result_max = mysqli_query($db_connect, $query_max);
    $row_max    = mysqli_fetch_assoc($result_max);
    if ($row_max['MAX_ID']) {
        $last_num = intval(substr($row_max['MAX_ID'], 2));
    } else {
        $last_num = 0;
    }

    while ($row = mysqli_fetch_assoc($db_temp)) {
        $last_num++;
        $v_id_mapel             = 'MP' . str_pad($last_num, 7, '0', STR_PAD_LEFT);
        $v_id_mapel_smst_ganjil = $v_id_mapel . '.1';
        $v_id_mapel_smst_genap  = $v_id_mapel . '.2';

        $v_nama_mapel           = mysqli_real_escape_string($db_connect, $row['NAMA_MAPEL']);
        $v_tahun_pelajaran      = mysqli_real_escape_string($db_connect, $row['TAHUN_PELAJARAN']);
        // $v_semester             = mysqli_real_escape_string($db_connect, $row['SEMESTER']);
        $v_id_kategori_mapel    = mysqli_real_escape_string($db_connect, $row['ID_KATEGORI_MAPEL']);
        $v_id_sub_kategori_mapel= mysqli_real_escape_string($db_connect, $row['ID_SUB_KATEGORI_MAPEL']);
        $v_id_sub_kategori_mapel= empty($v_id_sub_kategori_mapel) ? "NULL" : "'$v_id_sub_kategori_mapel'";

        $query_insert = "INSERT INTO tb_mapel (ID_MAPEL, ID_MAPEL_SEMESTER , NAMA_MAPEL, TAHUN_PELAJARAN, SEMESTER, TGL_REKAM, PETUGAS_REKAM, ID_KATEGORI_MAPEL, ID_SUB_KATEGORI_MAPEL)
                        VALUES ('$v_id_mapel', '$v_id_mapel_smst_ganjil', '$v_nama_mapel', '$v_tahun_pelajaran', 'Gasal', NOW(), '$session_user_id', '$v_id_kategori_mapel', $v_id_sub_kategori_mapel),
                               ('$v_id_mapel', '$v_id_mapel_smst_genap', '$v_nama_mapel', '$v_tahun_pelajaran', 'Genap', NOW(), '$session_user_id', '$v_id_kategori_mapel', $v_id_sub_kategori_mapel)";
        $db_ext = mysqli_query($db_connect, $query_insert);

        if($db_ext) $success++; else $failed++;
    }
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

