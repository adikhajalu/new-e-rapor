<?php
include '../includes/session.php';
include '../koneksi/koneksi.php';

$query_count    = "SELECT COUNT(1) CNT_ROW FROM tb_penilaian_exr_temp A 
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

    
    $query_count    = "SELECT COUNT(1) CNT_ROW FROM `tb_penilaian_exr_temp` A WHERE 1=1 AND PETUGAS_REKAM = '$session_user_id'
                        AND EXISTS(SELECT NULL FROM `tb_penilaian_exr` B WHERE B.ID_EKSTRAKURIKULER = A.ID_EKSTRAKURIKULER AND B.NIS = A.NIS AND UPPER(B.KELAS) = UPPER(A.KELAS) AND B.TAHUN_PELAJARAN = A.TAHUN_PELAJARAN AND UPPER(B.SEMESTER) = UPPER(A.SEMESTER))";
    $db_ext         = mysqli_query($db_connect,$query_count);
    $data_count     = mysqli_fetch_array($db_ext);
    $v_total_invalid= $data_count['CNT_ROW'];

    if($v_total_invalid > 0){
        $query  = "SELECT * FROM `tb_penilaian_exr` A WHERE 1=1
                            AND EXISTS(SELECT NULL FROM `tb_penilaian_exr_temp` B WHERE B.ID_EKSTRAKURIKULER = A.ID_EKSTRAKURIKULER AND B.NIS = A.NIS AND UPPER(B.KELAS) = UPPER(A.KELAS) AND B.TAHUN_PELAJARAN = A.TAHUN_PELAJARAN AND UPPER(B.SEMESTER) = UPPER(A.SEMESTER))";
        $db_ext = mysqli_query($db_connect,$query);

        if(mysqli_num_rows($db_ext) > 0){
            while ($data = mysqli_fetch_array($db_ext)) 
            {
                $query_update   = "UPDATE tb_penilaian_exr_temp SET STATUS_VALID = 'T', KETERANGAN = 'Data siswa sudah pernah direkam sebelumnya.' 
                                                        WHERE 1=1 AND ID_EKSTRAKURIKULER = '".$data['ID_EKSTRAKURIKULER']."' AND UPPER(NIS) = UPPER('".$data['NIS']."') AND UPPER(KELAS) = UPPER('".$data['KELAS']."') AND UPPER(TAHUN_PELAJARAN) = UPPER('".$data['TAHUN_PELAJARAN']."') AND UPPER(SEMESTER) = UPPER('".$data['SEMESTER']."') AND UPPER(PETUGAS_REKAM) = UPPER('$session_user_id')";
                $db_upd         = mysqli_query($db_connect,$query_update);
            }
        }

        $jsondata["isSuccessful"]   = false;
        $jsondata["ret"]            = "-1";
        $jsondata["msg"]            = "Terdapat Perekaman data oleh user lain yang membuat data upload menjadi tidak valid. Silakan lakukan review ulang.";
        echo json_encode($jsondata);
        exit();
    }

    $query          = "INSERT INTO tb_penilaian_exr (ID_EKSTRAKURIKULER, NIS, KELAS, TAHUN_PELAJARAN, SEMESTER, NILAI_AKHIR, TGL_REKAM, PETUGAS_REKAM) 
                       SELECT ID_EKSTRAKURIKULER, NIS, KELAS, TAHUN_PELAJARAN, SEMESTER, NILAI_AKHIR, NOW(), PETUGAS_REKAM FROM tb_penilaian_exr_temp A 
                       WHERE 1=1 AND UPPER(PETUGAS_REKAM) = UPPER('$session_user_id') ";
    $db_ext         = mysqli_query($db_connect,$query);

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

