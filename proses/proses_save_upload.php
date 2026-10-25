<?php
include '../includes/session.php';
include '../koneksi/koneksi.php';

$query_count    = "SELECT COUNT(1) CNT_ROW FROM tb_siswa_temp A 
                    WHERE 1=1 AND UPPER(PETUGAS_REKAM) = UPPER('$session_user_id') AND STATUS_VALID = 'T' ";
$db_ext         = mysqli_query($db_connect,$query_count);
$data_count     = mysqli_fetch_array($db_ext);
$v_total_invalid= $data_count['CNT_ROW'];

if($v_total_invalid > 0){
    $jsondata["isSuccessful"]   = false;
    $jsondata["ret"]            = "-1";
    $jsondata["msg"]            = "Pastikan semua Status Valid pada data siswa sudah tercentang.";
    echo json_encode($jsondata);
    exit();
}
else 
{

    
    $query_count    = "SELECT COUNT(1) CNT_ROW FROM `tb_siswa_temp` A WHERE 1=1 AND PETUGAS_REKAM = '$session_user_id'
                        AND EXISTS(SELECT NULL FROM `tb_siswa` B WHERE B.NIS=A.NIS AND B.KELAS = A.KELAS AND B.TAHUN_PELAJARAN = A.TAHUN_PELAJARAN)"; // AND B.SEMESTER = A.SEMESTER
    $db_ext         = mysqli_query($db_connect,$query_count);
    $data_count     = mysqli_fetch_array($db_ext);
    $v_total_invalid= $data_count['CNT_ROW'];
    
    if($v_total_invalid > 0){
        $query  = "SELECT * FROM `tb_siswa` A WHERE 1=1
                            AND EXISTS(SELECT NULL FROM `tb_siswa_temp` B WHERE B.NIS=A.NIS AND B.KELAS = A.KELAS AND B.TAHUN_PELAJARAN = A.TAHUN_PELAJARAN)"; // AND B.SEMESTER = A.SEMESTER
        $db_ext = mysqli_query($db_connect,$query);

        if(mysqli_num_rows($db_ext) > 0){
            while ($data = mysqli_fetch_array($db_ext)) 
            {
                $query_update   = "UPDATE tb_siswa_temp SET STATUS_VALID = 'T', KETERANGAN = 'Data siswa sudah pernah direkam sebelumnya.' 
                                                        WHERE 1=1 AND UPPER(NIS) = UPPER('".$data['NIS']."') AND UPPER(KELAS) = UPPER('".$data['KELAS']."') AND UPPER(TAHUN_PELAJARAN) = UPPER('".$data['TAHUN_PELAJARAN']."') AND UPPER(PETUGAS_REKAM) = UPPER('$session_user_id')"; // UPPER(SEMESTER) = UPPER('".$data['SEMESTER']."') AND
                $db_upd         = mysqli_query($db_connect,$query_update);
            }
        }

        $jsondata["isSuccessful"]   = false;
        $jsondata["ret"]            = "-1";
        $jsondata["msg"]            = "Terdapat Perekaman data oleh user lain yang membuat data upload menjadi tidak valid. Silakan lakukan review ulang.";
        echo json_encode($jsondata);
        exit();
    }
    
    try 
    {
        $query          = "INSERT INTO tb_siswa (NIS, NAMA_SISWA, ALAMAT, KELAS, TAHUN_PELAJARAN, SEMESTER, TGL_REKAM, PETUGAS_REKAM) 
                        SELECT NIS, NAMA_SISWA, ALAMAT, KELAS, TAHUN_PELAJARAN, 'Gasal' SEMESTER, TGL_REKAM, PETUGAS_REKAM FROM tb_siswa_temp A 
                        WHERE 1=1 AND UPPER(PETUGAS_REKAM) = UPPER('$session_user_id')
                        UNION ALL
                        SELECT NIS, NAMA_SISWA, ALAMAT, KELAS, TAHUN_PELAJARAN, 'Genap' SEMESTER, TGL_REKAM, PETUGAS_REKAM FROM tb_siswa_temp A 
                        WHERE 1=1 AND UPPER(PETUGAS_REKAM) = UPPER('$session_user_id') ";
    // var_dump($query);die;
        $db_ext         = mysqli_query($db_connect,$query);

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
        echo json_encode($jsondata);
        exit();
    }
}



?>

