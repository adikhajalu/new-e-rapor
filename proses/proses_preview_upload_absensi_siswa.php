<?php
include '../includes/session.php';
include '../koneksi/koneksi.php';
require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

$response = [
    "isSuccessful" => false,
    "msg" => "",
    "data" => []
];

if (!isset($_FILES['file_excel'])) {
    $response['msg'] = "File tidak ditemukan.";
    echo json_encode($response);
    exit();
}

$file = $_FILES['file_excel']['tmp_name'];
$fileType = $_FILES['file_excel']['type'];
$fileSize = $_FILES['file_excel']['size'];

if ($fileSize > 5 * 1024 * 1024) {
    $response['msg'] = "Ukuran file melebihi 5MB.";
    echo json_encode($response);
    exit();
}

try {
    $spreadsheet = IOFactory::load($file);
    $sheet = $spreadsheet->getActiveSheet();
    $rows = [];
    foreach ($sheet->getRowIterator(3) as $row) { // Mulai dari baris ke-2 (data)
        $cellIterator = $row->getCellIterator();
        $cellIterator->setIterateOnlyExistingCells(false);

        $data = [];
        foreach ($cellIterator as $cell) {
            $cellValue = $cell->getValue();
            if ($cellValue === null) {
                $cellValue = '';
            }
            // cast ke string untuk menghindari warning pada trim/empty
            $data[] = trim((string)$cellValue);
        }

        $requiredCols = 9;
        for ($i = 0; $i < $requiredCols; $i++) {
            if (!isset($data[$i])) {
                $data[$i] = '';
            }
        }

        $v_tahun_pelajaran  = mysqli_real_escape_string($db_connect, trim($data[0]));
        $v_semester         = mysqli_real_escape_string($db_connect, trim($data[1]));
        $v_kelas            = mysqli_real_escape_string($db_connect, trim($data[2]));
        $v_nis              = mysqli_real_escape_string($db_connect, trim($data[3]));
        $v_nama_siswa       = mysqli_real_escape_string($db_connect, trim($data[4]));
        $v_kehadiran        = mysqli_real_escape_string($db_connect, trim($data[5]));
        $v_sakit            = mysqli_real_escape_string($db_connect, trim($data[6]));
        $v_izin             = mysqli_real_escape_string($db_connect, trim($data[7]));
        $v_tanpa_keterangan = mysqli_real_escape_string($db_connect, trim($data[8]));
        // Pastikan minimal NIS dan Nama Siswa terisi
        if (!empty($v_nis) && !empty($v_nama_siswa) && !empty($v_kelas) && !empty($v_tahun_pelajaran) && !empty($v_semester)) {
            $rows[] = [
                "nis"                   => $v_nis,
                "namaSiswa"             => $v_nama_siswa,
                "kelas"                 => $v_kelas,
                "tahunpelajaran"        => $v_tahun_pelajaran,
                "semester"              => $v_semester,
                "kehadiran"             => $v_kehadiran,
                "sakit"                 => $v_sakit,
                "izin"                  => $v_izin,
                "tanpaKeterangan"       => $v_tanpa_keterangan
            ];
        }
        
        $v_status_valid_ext = "T";
        $v_keterangan_ext   = "";
        $v_status_valid_temp= "T";
        $v_keterangan_temp  = "";
        $v_status_valid_siswa= "T";
        $v_keterangan_siswa  = "";

        $query_count        = "SELECT COUNT(1) CNT_ROW FROM tb_absensi_siswa A 
                                WHERE 1=1 AND UPPER(NIS) = UPPER(trim('$v_nis')) AND UPPER(KELAS) = UPPER('$v_kelas') AND UPPER(TAHUN_PELAJARAN) = UPPER('$v_tahun_pelajaran') AND UPPER(SEMESTER) = UPPER('$v_semester') ";
        $db_ext             = mysqli_query($db_connect,$query_count);
        $data_count         = mysqli_fetch_array($db_ext);
        $v_total_exist      = $data_count['CNT_ROW'];
        $v_status_valid_ext = ($v_total_exist > 0) ? "T" : "Y";
        $v_keterangan_ext   = ($v_total_exist > 0) ? "Siswa sudah pernah direkam sebelumnya." : "";

        $query_count_temp   = "SELECT COUNT(1) CNT_ROW FROM tb_absensi_siswa_temp A WHERE 1=1 AND UPPER(NIS) = UPPER(trim('$v_nis')) AND UPPER(KELAS) = UPPER('$v_kelas') AND UPPER(TAHUN_PELAJARAN) = UPPER('$v_tahun_pelajaran') AND UPPER(SEMESTER) = UPPER('$v_semester') AND UPPER(PETUGAS_REKAM) = UPPER('$session_user_id')";
        $db_ext             = mysqli_query($db_connect,$query_count_temp);
        $data_count         = mysqli_fetch_array($db_ext);
        $v_total_exist_temp = $data_count['CNT_ROW'];
        $v_status_valid_temp= ($v_total_exist_temp > 0) ? "T" : "Y";
        $v_keterangan_temp  = ($v_total_exist_temp > 0) ? "Data duplikat pada template excel." : "";
        
        $query_count_siswa  = "SELECT COUNT(1) CNT_ROW FROM tb_siswa A WHERE 1=1 AND UPPER(NIS) = UPPER(trim('$v_nis')) AND UPPER(KELAS) = UPPER('$v_kelas') AND UPPER(TAHUN_PELAJARAN) = UPPER('$v_tahun_pelajaran') AND UPPER(SEMESTER) = UPPER('$v_semester')";
        $db_ext             = mysqli_query($db_connect,$query_count_siswa);
        $data_count_siswa   = mysqli_fetch_array($db_ext);
        $v_total_exist_siswa = $data_count_siswa['CNT_ROW'];
        $v_status_valid_siswa= ($v_total_exist_siswa < 1) ? "T" : "Y";
        $v_keterangan_siswa  = ($v_total_exist_siswa < 1) ? "Siswa tidak ditemukan dengan NIS, KELAS, TAHUN PELAJARAN, dan SEMESTER tersebut." : "";

        if($v_total_exist > 0){
            $v_status_valid = "T";
            $v_keterangan   = $v_keterangan_ext;
        }

        if($v_total_exist_temp > 0){
            $v_status_valid = "T";
            $v_keterangan   = $v_keterangan_temp;
        }

        if($v_total_exist_siswa < 1){
            $v_status_valid = "T";
            $v_keterangan   = $v_keterangan_siswa;
        }
        
        if($v_status_valid_ext == 'Y' && $v_status_valid_temp == 'Y' && $v_status_valid_siswa == 'Y'){ 
            $v_status_valid = "Y";
            $v_keterangan   = "";
        }

        if(trim($v_nis) == "" || trim($v_nama_siswa) == "" || trim($v_kelas) == "" || trim($v_tahun_pelajaran) == "" || trim($v_semester) == "" || trim($v_sakit) == "" || trim($v_izin) == "" || trim($v_tanpa_keterangan) == "" || trim($v_kehadiran) == ""){
            $v_status_valid = "T";
            $v_keterangan   = "Data tidak lengkap. NIS, NAMA SISWA, KELAS,TAHUN PELAJARAN, SEMESTER, HADIR, SAKIT, IZIN, TANPA KETERANGAN harus diisi.";
        }
        
        if(trim($v_nis) != "" || trim($v_nama_siswa) != "" || trim($v_kelas) != "" || trim($v_tahun_pelajaran) != "" || trim($v_semester) != "" || trim($v_sakit) != "" || trim($v_izin) != "" || trim($v_tanpa_keterangan) != ""){
            $query  = "INSERT INTO tb_absensi_siswa_temp (NIS, NAMA_SISWA, KELAS, TAHUN_PELAJARAN, SEMESTER, KEHADIRAN, SAKIT, IZIN, TANPA_KETERANGAN, STATUS_VALID, KETERANGAN, TGL_REKAM, PETUGAS_REKAM)
                        VALUES (trim('$v_nis'), trim('$v_nama_siswa'), trim(UPPER('$v_kelas')), trim('$v_tahun_pelajaran'), trim('$v_semester'), trim('$v_kehadiran'), trim('$v_sakit'), trim('$v_izin'), trim('$v_tanpa_keterangan'), '$v_status_valid', '$v_keterangan', NOW(), '$session_user_id') ";
            // var_dump($query);die;
            $db_ext = mysqli_query($db_connect,$query);
        }
        
    }

    // $query_list     = "SELECT A.NIS, A.NAMA_SISWA, A.KELAS, A.TAHUN_PELAJARAN, A.SEMESTER, A.STATUS_VALID, A.KETERANGAN FROM tb_siswa_temp A 
    //                         WHERE 1=1 AND UPPER(PETUGAS_REKAM) = UPPER('$session_user_id')
    //                         ORDER BY A.NIS, A.TAHUN_PELAJARAN DESC, A.SEMESTER, A.TGL_REKAM DESC"; 
    // $db_ext         = mysqli_query($db_connect,$query_list);

    // if(mysqli_num_rows($db_ext) > 0){
    //     // $data                       = mysqli_fetch_array($db_ext);
    //     while ($data = mysqli_fetch_array($db_ext)) 
    //     {
    //         $jdata[] = $data;
    //     }
    // }


    if (count($rows) == 0) {
        $response['msg'] = "Data tidak ditemukan di file.";
    } else {
        $response['isSuccessful'] = true;
        $response['msg'] = "Sukses membaca data.";
        $response['ret'] = "1";
        // $response['data'] = $jdata;
    }
} catch (Exception $e) {
    $response['msg'] = "Gagal membaca file, pastikan data di excel sudah terisi dengan benar: " . $e->getMessage();
}

echo json_encode($response);
?>