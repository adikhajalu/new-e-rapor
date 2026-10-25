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
    foreach ($sheet->getRowIterator(3) as $row) { // Mulai dari baris ke-3 (data)
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
        $v_mitra_du_di      = mysqli_real_escape_string($db_connect, trim($data[4]));
        $v_lokasi           = mysqli_real_escape_string($db_connect, trim($data[5]));

        // Pastikan minimal NIS dan Nama Siswa terisi
        if (!empty($v_nis) && !empty($v_kelas) && !empty($v_tahun_pelajaran) && !empty($v_semester)) {
            $rows[] = [
                "nis"                   => $v_nis,
                "kelas"                 => $v_kelas,
                "tahunpelajaran"        => $v_tahun_pelajaran,
                "semester"              => $v_semester
            ];
        }

        $v_status_valid_ext = "T";
        $v_keterangan_ext   = "";
        $v_status_valid_temp= "T";
        $v_keterangan_temp  = "";
        $v_status_valid_nis= "T";
        $v_keterangan_nis  = "";
        $v_status_valid_jadwal= "T";
        $v_keterangan_jadwal  = "";
        $v_keterangan       = "";

        $query_count        = "SELECT COUNT(1) CNT_ROW FROM tb_pkl A 
                                WHERE 1=1 AND UPPER(MITRA_DU_DI) = UPPER('$v_mitra_du_di') AND UPPER(NIS) = UPPER(trim('$v_nis')) AND UPPER(KELAS) = UPPER('$v_kelas') AND UPPER(TAHUN_PELAJARAN) = UPPER('$v_tahun_pelajaran') AND UPPER(SEMESTER) = UPPER('$v_semester') ";
        $db_ext             = mysqli_query($db_connect,$query_count);
        $data_count         = mysqli_fetch_array($db_ext);
        $v_total_exist      = $data_count['CNT_ROW'];
        $v_status_valid_ext = ($v_total_exist > 0) ? "T" : "Y";
        $v_keterangan_ext   = ($v_total_exist > 0) ? "PKL dengan Siswa, Tahun Ajaran, Semester dan Mitra DU/DI tersebut sudah pernah direkam sebelumnya." : "";

        $query_count_temp   = "SELECT COUNT(1) CNT_ROW FROM tb_pkl_temp A WHERE 1=1 AND UPPER(MITRA_DU_DI) = UPPER('$v_mitra_du_di') AND UPPER(NIS) = UPPER(trim('$v_nis')) AND UPPER(KELAS) = UPPER('$v_kelas') AND UPPER(TAHUN_PELAJARAN) = UPPER('$v_tahun_pelajaran') AND UPPER(SEMESTER) = UPPER('$v_semester') AND UPPER(PETUGAS_REKAM) = UPPER('$session_user_id')";
        $db_ext             = mysqli_query($db_connect,$query_count_temp);
        $data_count         = mysqli_fetch_array($db_ext);
        $v_total_exist_temp = $data_count['CNT_ROW'];
        $v_status_valid_temp= ($v_total_exist_temp > 0) ? "T" : "Y";
        $v_keterangan_temp  = ($v_total_exist_temp > 0) ? "Data duplikat pada template excel." : "";
        
        $query_count_nis    = "SELECT COUNT(1) CNT_ROW FROM tb_siswa A WHERE 1=1 AND UPPER(nis) = UPPER('$v_nis') AND UPPER(kelas) = UPPER('$v_kelas') AND UPPER(kelas) LIKE 'XIII%'AND UPPER(TAHUN_PELAJARAN) = UPPER('$v_tahun_pelajaran') AND UPPER(SEMESTER) = UPPER('$v_semester')";
        // var_dump($query_count_nis);die;
        $db_ext             = mysqli_query($db_connect,$query_count_nis);
        $data_count         = mysqli_fetch_array($db_ext);
        $v_total_cek_nis    = $data_count['CNT_ROW'];
        $v_status_valid_nis = ($v_total_cek_nis < 1) ? "T" : "Y";
        $v_keterangan_nis   = ($v_total_cek_nis < 1) ? "Data Siswa dengan NIS, Kelas dan Tahun Ajaran tersebut tidak ditemukan. Atau pastikan siswa yang direkam adalah siswa kelas XIII." : "";

        if($v_total_exist > 0 && $v_total_exist_temp == 0){
            $v_status_valid = "T";
            $v_keterangan   = $v_keterangan_ext;
        } 
        
        if($v_total_exist_temp > 0){
            $v_status_valid = "T";
            $v_keterangan   = $v_keterangan_temp;
        }  
        
        if($v_total_cek_nis < 1){
            $v_status_valid = "T";
            $v_keterangan   = $v_keterangan_nis;
        } 
        
        if($v_status_valid_ext == 'Y' && $v_status_valid_temp == 'Y' && $v_status_valid_nis == 'Y') {
            $v_status_valid = "Y";
            $v_keterangan   = "";
        }

        if(trim($v_mitra_du_di) == "" || trim($v_nis) == "" || trim($v_kelas) == "" || trim($v_tahun_pelajaran) == "" || trim($v_semester) == "" || trim($v_lokasi) == ""){
            $v_status_valid = "T";
            if($v_keterangan != "") {
                $v_keterangan  .= "\nData tidak lengkap: MITRA_DU_DI, NIS, KELAS, TAHUN_PELAJARAN, SEMESTER, LOKASI harus diisi.";
            } else {
                $v_keterangan   = "Data tidak lengkap: MITRA_DU_DI, NIS, KELAS, TAHUN_PELAJARAN, SEMESTER, LOKASI harus diisi.";
            }
        }
        
        if(trim($v_mitra_du_di) != "" || trim($v_nis) != "" || trim($v_kelas) != "" || trim($v_tahun_pelajaran) != "" || trim($v_semester) != "" || trim($v_lokasi) != ""){
            $query  = "INSERT INTO tb_pkl_temp (MITRA_DU_DI, NIS, KELAS, TAHUN_PELAJARAN, SEMESTER, LOKASI, STATUS_VALID, KETERANGAN, TGL_REKAM, PETUGAS_REKAM)
                        VALUES (trim('$v_mitra_du_di'), trim(UPPER('$v_nis')), trim(UPPER('$v_kelas')), trim(UPPER('$v_tahun_pelajaran')), trim('$v_semester'), trim('$v_lokasi'), '$v_status_valid', '$v_keterangan', NOW(), '$session_user_id') ";
            // var_dump($query);die;
            $db_ext = mysqli_query($db_connect,$query);
        }
        
    }

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