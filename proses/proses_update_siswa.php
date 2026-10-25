<?php
include '../includes/session.php';
include '../koneksi/koneksi.php';

$v_nis                  = mysqli_real_escape_string($db_connect, $_POST['nis']);
$v_nama_siswa           = mysqli_real_escape_string($db_connect, $_POST['namaSiswa']);
$v_alamat               = mysqli_real_escape_string($db_connect, $_POST['alamat']);
$v_kelas                = mysqli_real_escape_string($db_connect, $_POST['kelas']);
$v_tahun_pelajaran      = mysqli_real_escape_string($db_connect, $_POST['tahunAjaran']);
// $v_semester             = mysqli_real_escape_string($db_connect, $_POST['semester']);
$v_keterangan           = mysqli_real_escape_string($db_connect, $_POST['keterangan']);
$v_nis_old              = mysqli_real_escape_string($db_connect, $_POST['nisOld']);
$v_kelas_old            = mysqli_real_escape_string($db_connect, $_POST['kelasOld']);
$v_tahun_pelajaran_old  = mysqli_real_escape_string($db_connect, $_POST['tahunAjaranOld']);
// $v_semester_old         = mysqli_real_escape_string($db_connect, $_POST['semesterOld']);
$v_filter_old           = " AND UPPER(NIS) = UPPER('$v_nis_old') AND UPPER(KELAS) = UPPER('$v_kelas_old') AND UPPER(TAHUN_PELAJARAN) = UPPER('$v_tahun_pelajaran_old') "; // AND UPPER(SEMESTER) = UPPER('$v_semester_old')

if($v_kelas_old <> $v_kelas || $v_tahun_pelajaran_old <> $v_tahun_pelajaran) {
    $query_cek  = "SELECT * FROM tb_penilaian WHERE 1=1 $v_filter_old ";
    $row        = mysqli_num_rows(mysqli_query($db_connect, $query_cek));

    if($row > 0){
        $jsondata["isSuccessful"]   = false;
        $jsondata["ret"]            = "-1";
        $jsondata["msg"]            = "Sudah ada penilaian pada data siswa tersebut sehingga data kelas dan tahun ajaran tidak dapat diubah.";
        echo json_encode($jsondata);
        exit();
    }

    $query_count_wali   = "SELECT COUNT(1) CNT_ROW FROM tb_wali_kelas A WHERE 1=1 AND UPPER(kelas) = UPPER('$v_kelas') AND tahun_pelajaran = '$v_tahun_pelajaran'";
    $row                = mysqli_num_rows(mysqli_query($db_connect, $query_count_wali));
    if($row > 0){
        $jsondata["isSuccessful"]   = false;
        $jsondata["ret"]            = "-1";
        $jsondata["msg"]            = "Tidak ada Wali Kelas pada kelas dan tahun pelajaran yang diisi.";
        echo json_encode($jsondata);
        exit();
    }
}

$query          = "UPDATE tb_siswa SET NAMA_SISWA = TRIM('$v_nama_siswa'), KELAS = UPPER('$v_kelas'), TAHUN_PELAJARAN = '$v_tahun_pelajaran', KETERANGAN = '$v_keterangan', TGL_UBAH = NOW(), PETUGAS_UBAH = '$session_user_id', ALAMAT = TRIM('$v_alamat') 
                    WHERE 1=1 $v_filter_old ";
try{
    $db_ext         = mysqli_query($db_connect,$query);

    if($db_ext == true)
    {
        $jsondata["isSuccessful"]   = true;
        $jsondata["ret"]            = "1";
        $jsondata["msg"]            = "Berhasil ubah data.";
        echo json_encode($jsondata);

    } else {
        $jsondata["isSuccessful"]   = false;
        $jsondata["ret"]            = "-1";
        $jsondata["msg"]            = "Gagal ubah data, silakan coba beberapa saat lagi.";
        echo json_encode($jsondata);
        exit();
    }
} catch (mysqli_sql_exception $e) {
    $jsondata["isSuccessful"]   = false;
    $jsondata["ret"]            = "-1";
    if ($e->getCode() == 1062) {
        $jsondata["msg"] = "Sudah terdapat data siswa dengan data yang sama, silakan cek kembali.";
    } else {
        $jsondata["msg"]        = "Gagal ubah data, silakan coba beberapa saat lagi.";
        $jsondata["sysErrMsg"]  = $e->getMessage();
    }
    echo json_encode($jsondata);
    exit();
}



?>

