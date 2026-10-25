<?php
include '../includes/session.php';
include '../koneksi/koneksi.php';

$v_nis              = $_POST['nis'];
$v_kelas            = $_POST['kelas'];
$v_tahun_pelajaran  = $_POST['tahunAjaran'];
$v_semester         = $_POST['semester'];

$v_filter          = " AND UPPER(NIS) = UPPER('$v_nis') AND UPPER(KELAS) = UPPER('$v_kelas') AND UPPER(TAHUN_PELAJARAN) = UPPER('$v_tahun_pelajaran') AND UPPER(SEMESTER) = UPPER('$v_semester') ";

$query_detil       = "SELECT DISTINCT ID_KATEGORI_MAPEL, ID_SUB_KATEGORI_MAPEL, 
                                        (SELECT Z.NAMA_KATEGORI_MAPEL FROM tb_kategori_mapel Z WHERE Z.ID_KATEGORI_MAPEL = X.ID_KATEGORI_MAPEL) NAMA_KATEGORI_MAPEL,
                                        (SELECT Z.NAMA_SUB_KATEGORI_MAPEL FROM tb_sub_kategori_mapel Z WHERE Z.ID_SUB_KATEGORI_MAPEL = X.ID_SUB_KATEGORI_MAPEL) NAMA_SUB_KATEGORI_MAPEL
                      FROM (
                        SELECT B.*, 
                            (SELECT A.ID_KATEGORI_MAPEL FROM tb_mapel A WHERE A.ID_MAPEL = B.ID_MAPEL AND A.SEMESTER = B.SEMESTER) ID_KATEGORI_MAPEL,
                            (SELECT A.ID_SUB_KATEGORI_MAPEL FROM tb_mapel A WHERE A.ID_MAPEL = B.ID_MAPEL AND A.SEMESTER = B.SEMESTER) ID_SUB_KATEGORI_MAPEL
                        FROM tb_penilaian B WHERE 1=1 $v_filter) X ORDER BY X.ID_KATEGORI_MAPEL ASC, X.ID_SUB_KATEGORI_MAPEL ASC"; 
                        // var_dump($query_detil);die;

$db_ext             = mysqli_query($db_connect,$query_detil);

if(mysqli_num_rows($db_ext) > 0){
    $jdata_mapel_ketegori_non_sub   = [];
    $jdata_mapel_ketegori_sub       = [];
    while ($data = mysqli_fetch_array($db_ext)) 
    {
        $jdata_list_kategori[]  = $data;
        $v_kategori             = $data['ID_KATEGORI_MAPEL'];
        $v_sub_kategori         = $data['ID_SUB_KATEGORI_MAPEL'];

        if(isset($v_kategori) && !isset($v_sub_kategori)){
            $q_ketegori_non_sub = "SELECT 
                                            (SELECT Z.NAMA_KATEGORI_MAPEL FROM tb_kategori_mapel Z WHERE Z.ID_KATEGORI_MAPEL = X.ID_KATEGORI_MAPEL) NAMA_KATEGORI_MAPEL,
                                            (SELECT Z.NAMA_SUB_KATEGORI_MAPEL FROM tb_sub_kategori_mapel Z WHERE Z.ID_SUB_KATEGORI_MAPEL = X.ID_SUB_KATEGORI_MAPEL) NAMA_SUB_KATEGORI_MAPEL,
                                            X.*
                                    FROM (
                                        SELECT B.*, 
                                            (SELECT A.NAMA_MAPEL FROM tb_mapel A WHERE A.ID_MAPEL = B.ID_MAPEL AND A.SEMESTER = B.SEMESTER) NAMA_MAPEL,
                                            (SELECT A.ID_KATEGORI_MAPEL FROM tb_mapel A WHERE A.ID_MAPEL = B.ID_MAPEL AND A.SEMESTER = B.SEMESTER) ID_KATEGORI_MAPEL,
                                            (SELECT A.ID_SUB_KATEGORI_MAPEL FROM tb_mapel A WHERE A.ID_MAPEL = B.ID_MAPEL AND A.SEMESTER = B.SEMESTER) ID_SUB_KATEGORI_MAPEL
                                        FROM tb_penilaian B WHERE 1=1 $v_filter) X WHERE X.ID_KATEGORI_MAPEL = '$v_kategori' AND X.ID_SUB_KATEGORI_MAPEL IS NULL  ORDER BY X.ID_KATEGORI_MAPEL ASC";
                                        // var_dump($q_ketegori_non_sub);die;
            $db_ext_ketegori_non_sub             = mysqli_query($db_connect,$q_ketegori_non_sub);       
            while ($data_ketegori_non_sub = mysqli_fetch_array($db_ext_ketegori_non_sub)) {
                $jdata_mapel_ketegori_non_sub[] = $data_ketegori_non_sub;
            }
        }

        if(isset($v_kategori) && isset($v_sub_kategori)){
            $q_ketegori_sub = "SELECT 
                                        (SELECT Z.NAMA_KATEGORI_MAPEL FROM tb_kategori_mapel Z WHERE Z.ID_KATEGORI_MAPEL = X.ID_KATEGORI_MAPEL) NAMA_KATEGORI_MAPEL,
                                        (SELECT Z.NAMA_SUB_KATEGORI_MAPEL FROM tb_sub_kategori_mapel Z WHERE Z.ID_SUB_KATEGORI_MAPEL = X.ID_SUB_KATEGORI_MAPEL) NAMA_SUB_KATEGORI_MAPEL,
                                        X.*
                                FROM (
                                    SELECT B.*, 
                                        (SELECT A.NAMA_MAPEL FROM tb_mapel A WHERE A.ID_MAPEL = B.ID_MAPEL AND A.SEMESTER = B.SEMESTER) NAMA_MAPEL,
                                        (SELECT A.ID_KATEGORI_MAPEL FROM tb_mapel A WHERE A.ID_MAPEL = B.ID_MAPEL AND A.SEMESTER = B.SEMESTER) ID_KATEGORI_MAPEL,
                                        (SELECT A.ID_SUB_KATEGORI_MAPEL FROM tb_mapel A WHERE A.ID_MAPEL = B.ID_MAPEL AND A.SEMESTER = B.SEMESTER) ID_SUB_KATEGORI_MAPEL
                                    FROM tb_penilaian B WHERE 1=1 $v_filter) X WHERE X.ID_KATEGORI_MAPEL = '$v_kategori' AND X.ID_SUB_KATEGORI_MAPEL = '$v_sub_kategori' ORDER BY X.ID_KATEGORI_MAPEL ASC, X.ID_SUB_KATEGORI_MAPEL ASC";
            $db_ext_ketegori_sub             = mysqli_query($db_connect,$q_ketegori_sub);     
            while ($data_ketegori_sub = mysqli_fetch_array($db_ext_ketegori_sub)) {
                $jdata_mapel_ketegori_sub[] = $data_ketegori_sub;
            }
        }
    }
    
    $jsondata["data"]["listKategori"]       = $jdata_list_kategori;
    $jsondata["data"]["mapelKategori"]      = $jdata_mapel_ketegori_non_sub;
    $jsondata["data"]["mapelSubKategori"]   = $jdata_mapel_ketegori_sub;
} 
else 
{
    $jsondata["data"]["listKategori"]       = [];
    $jsondata["data"]["mapelKategori"]      = [];
    $jsondata["data"]["mapelSubKategori"]   = [];
}

$query_detil       = "SELECT B.*, (SELECT A.NAMA_EKSTRAKURIKULER FROM tb_ekstrakurikuler A WHERE A.ID_EKSTRAKURIKULER = B.ID_EKSTRAKURIKULER AND A.SEMESTER = B.SEMESTER) NAMA_EKSTRAKURIKULER FROM tb_penilaian_exr B WHERE 1=1 $v_filter"; 

$db_ext             = mysqli_query($db_connect,$query_detil);

if(mysqli_num_rows($db_ext) > 0){
    while ($data = mysqli_fetch_array($db_ext)) 
    {
        $jdata_exr[] = $data;
    }
    
    $jsondata["data"]["exr"]  = $jdata_exr;
} else {
    $jsondata["data"]["exr"]  = [];
}

$query_detil       = "SELECT B.* FROM tb_absensi_siswa B WHERE 1=1 $v_filter"; 

$db_ext             = mysqli_query($db_connect,$query_detil);

if(mysqli_num_rows($db_ext) > 0){
    while ($data = mysqli_fetch_array($db_ext)) 
    {
        $jdata_abs_sis[] = $data;
    }
    
    $jsondata["data"]["absSis"]  = $jdata_abs_sis;
} else {
    $jsondata["data"]["absSis"]  = [];
}

$query_detil       = "SELECT B.*, 
                             (SELECT A1.NIP FROM tb_wali_kelas A1 WHERE A1.TAHUN_PELAJARAN = UPPER('$v_tahun_pelajaran') AND UPPER(A1.KELAS) = UPPER('$v_kelas')) NIP_WALI_KELAS,
                             (SELECT (SELECT A1.NAMA FROM tb_user A1 WHERE A1.NIP = A.NIP) FROM tb_wali_kelas A WHERE A.KELAS = B.KELAS AND A.TAHUN_PELAJARAN = B.TAHUN_PELAJARAN) NAMA_WALI_KELAS,
                              (
                                SELECT COUNT(1) CNT 
                                FROM (
                                SELECT NILAI.ID_MAPEL, SUM(NILAI.NILAI_AKHIR) JUMLAH_NILAI, COUNT(1) JUMLAH_MAPEL_SEMESTER
                                FROM tb_penilaian NILAI
                                WHERE UPPER(NILAI.NIS) = UPPER('$v_nis')
                                        AND UPPER(NILAI.KELAS) = UPPER('$v_kelas')
                                        AND UPPER(NILAI.TAHUN_PELAJARAN) = UPPER('$v_tahun_pelajaran')
                                        AND EXISTS (
                                        SELECT 1 FROM tb_mapel MPL
                                        WHERE MPL.ID_MAPEL = NILAI.ID_MAPEL AND MPL.ID_KATEGORI_MAPEL = (SELECT X.ID_KATEGORI_MAPEL FROM tb_kategori_mapel X WHERE X.TAHUN_PELAJARAN = '$v_tahun_pelajaran' AND UPPER(X.NAMA_KATEGORI_MAPEL) NOT LIKE '%KEJURUAN%')
                                        ) 
                                        GROUP BY NILAI.ID_MAPEL
                                ) Z
                                WHERE (Z.JUMLAH_NILAI/Z.JUMLAH_MAPEL_SEMESTER) < 80
                              ) AS JML_NILAI_UMUM_TIDAK_LULUS,
                              (
                                SELECT COUNT(1) CNT 
                                FROM (
                                SELECT NILAI.ID_MAPEL, SUM(NILAI.NILAI_AKHIR) JUMLAH_NILAI, COUNT(1) JUMLAH_MAPEL_SEMESTER
                                FROM tb_penilaian NILAI
                                WHERE UPPER(NILAI.NIS) = UPPER('$v_nis')
                                        AND UPPER(NILAI.KELAS) = UPPER('$v_kelas')
                                        AND UPPER(NILAI.TAHUN_PELAJARAN) = UPPER('$v_tahun_pelajaran')
                                        AND EXISTS (
                                        SELECT 1 FROM tb_mapel MPL
                                        WHERE MPL.ID_MAPEL = NILAI.ID_MAPEL AND MPL.ID_KATEGORI_MAPEL = (SELECT X.ID_KATEGORI_MAPEL FROM tb_kategori_mapel X WHERE X.TAHUN_PELAJARAN = '$v_tahun_pelajaran' AND UPPER(X.NAMA_KATEGORI_MAPEL)  LIKE '%KEJURUAN%')
                                        ) 
                                        GROUP BY NILAI.ID_MAPEL
                                ) Z
                                WHERE (Z.JUMLAH_NILAI/Z.JUMLAH_MAPEL_SEMESTER) < 80
                              ) AS JML_NILAI_KEJURUAN_TIDAK_LULUS,
                              (
                                SELECT COUNT(1) CNT
                                FROM (
                                    SELECT ((X.KEHADIRAN)/(X.KEHADIRAN+X.IZIN+X.TANPA_KETERANGAN+X.SAKIT)*100) RATA2_KEHADIRAN 
                                    FROM tb_absensi_siswa X
                                    WHERE 1=1
                                    AND X.NIS = UPPER('$v_nis')
                                    AND X.TAHUN_PELAJARAN = UPPER('$v_tahun_pelajaran')
                                    AND X.KELAS = UPPER('$v_kelas')
                                ) Z 
                                WHERE 1=1
                                AND Z.RATA2_KEHADIRAN < 80
                              ) AS JML_ABSESNSI_TIDAK_LULUS,
                              (
                                SELECT COUNT(1) CNT
                                FROM (
                                    SELECT LEFT(X.NILAI_AKHIR, 1) NILAI_AKHIR FROM tb_penilaian_exr X
                                    WHERE 1=1
                                    AND X.NIS = UPPER('$v_nis')
                                    AND X.TAHUN_PELAJARAN = UPPER('$v_tahun_pelajaran')
                                    AND UPPER(X.KELAS) = UPPER('$v_kelas')
                                ) Z
                                WHERE UPPER(Z.NILAI_AKHIR) NOT IN ('A','B')
                              ) AS JML_ESKUL_TIDAK_LULUS
                        FROM tb_siswa B WHERE 1=1 $v_filter"; 
                        // var_dump($query_detil);die;
$db_ext             = mysqli_query($db_connect,$query_detil);

if(mysqli_num_rows($db_ext) > 0){
    while ($data = mysqli_fetch_array($db_ext)) 
    {
        if(str_starts_with($data['KELAS'], 'XIII'))
        {
            if($data['JML_NILAI_UMUM_TIDAK_LULUS'] > 2 || $data['JML_NILAI_KEJURUAN_TIDAK_LULUS'] > 0 || $data['JML_ABSESNSI_TIDAK_LULUS'] > 0 || $data['STATUS_KOMPETENSI_LSP'] == 'T')
            {
                $data["STATUS_NAIK_KELAS"] = "T";
            } 
            else 
            {
                $data["STATUS_NAIK_KELAS"] = "Y";
            }
        }
        else
        {
            if($data['JML_NILAI_UMUM_TIDAK_LULUS'] > 2 || $data['JML_NILAI_KEJURUAN_TIDAK_LULUS'] > 0 || $data['JML_ABSESNSI_TIDAK_LULUS'] > 0 || $data['JML_ESKUL_TIDAK_LULUS'] > 0)
            {
                $data["STATUS_NAIK_KELAS"] = "T";
            } 
            else 
            {
                $data["STATUS_NAIK_KELAS"] = "Y";
            }
        }

        $jdata_sis[] = $data;
    }
    
    $jsondata["data"]["siswa"]  = $jdata_sis;
} else {
    $jsondata["data"]["siswa"]  = [];
}

$query_detil       = "SELECT B.* FROM tb_user B WHERE 1=1 AND B.STATUS_KEPALA_SEKOLAH = 'Y'"; 

$db_ext             = mysqli_query($db_connect,$query_detil);

if(mysqli_num_rows($db_ext) > 0){
    while ($data = mysqli_fetch_array($db_ext)) 
    {
        $jdata_kepsek[] = $data;
    }
    
    $jsondata["data"]["kepsek"]  = $jdata_kepsek;
} else {
    $jsondata["data"]["kepsek"]  = [];
}

$query_pkl       = "SELECT B.* FROM tb_pkl B WHERE 1=1 $v_filter"; 

$db_pkl          = mysqli_query($db_connect,$query_pkl);

if(mysqli_num_rows($db_pkl) > 0){
    while ($data = mysqli_fetch_array($db_pkl)) 
    {
        $jdata_pkl[] = $data;
    }
    
    $jsondata["data"]["pkl"]  = $jdata_pkl;
} else {
    $jsondata["data"]["pkl"]  = [];
}

$jsondata["isSuccessful"]   = true;
$jsondata["ret"]            = "1";
$jsondata["msg"]            = "Sukses.";
echo json_encode($jsondata);














?>


