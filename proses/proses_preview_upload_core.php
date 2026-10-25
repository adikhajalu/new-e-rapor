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
            $data[] = trim($cell->getValue());
        }
        // Pastikan minimal NIS dan Nama Siswa terisi
        if (!empty($data[0]) && !empty($data[1]) && !empty($data[2]) && !empty($data[3]) && !empty($data[4])) {
            $rows[] = [
                "nis" => $data[0],
                "nama_siswa" => $data[1],
                "kelas" => $data[2],
                "tahun_pelajaran" => $data[3],
                "semester" => $data[4]
            ];
        }

        $v_status_valid_ext = "T";
        $v_keterangan_ext   = "";
        $v_status_valid_temp= "T";
        $v_keterangan_temp  = "";

        $query_count        = "SELECT COUNT(1) CNT_ROW FROM tb_siswa A 
                                WHERE 1=1 AND UPPER(NIS) = UPPER(trim('$data[0]')) AND UPPER(KELAS) = UPPER(trim('$data[2]')) AND UPPER(TAHUN_PELAJARAN) = UPPER(trim('$data[3]')) AND UPPER(SEMESTER) = UPPER(trim('$data[4]'))";
        $db_ext             = mysqli_query($db_connect,$query_count);
        $data_count         = mysqli_fetch_array($db_ext);
        $v_total_exist      = $data_count['CNT_ROW'];
        $v_status_valid_ext = ($v_total_exist > 0) ? "T" : "Y";
        $v_keterangan_ext   = ($v_total_exist > 0) ? "Data siswa sudah pernah direkam sebelumnya." : "";

        $query_count_temp   = "SELECT COUNT(1) CNT_ROW FROM tb_siswa_temp A WHERE 1=1 AND UPPER(NIS) = UPPER(trim('$data[0]')) AND UPPER(KELAS) = UPPER(trim('$data[2]')) AND UPPER(TAHUN_PELAJARAN) = UPPER(trim('$data[3]')) AND UPPER(SEMESTER) = UPPER(trim('$data[4]')) AND UPPER(PETUGAS_REKAM) = UPPER('$session_user_id')";
        $db_ext             = mysqli_query($db_connect,$query_count_temp);
        $data_count         = mysqli_fetch_array($db_ext);
        $v_total_exist_temp = $data_count['CNT_ROW'];
        $v_status_valid_temp= ($v_total_exist_temp > 0) ? "T" : "Y";
        $v_keterangan_temp  = ($v_total_exist_temp > 0) ? "Data duplikat pada template excel." : "";

        if($v_total_exist > 0 && $v_total_exist_temp == 0){
            $v_status_valid = "T";
            $v_keterangan   = $v_keterangan_ext;
        } else if($v_total_exist == 0 && $v_total_exist_temp > 0){
            $v_status_valid = "T";
            $v_keterangan   = $v_keterangan_temp;
        } else if($v_total_exist > 0 && $v_total_exist_temp > 0){
            $v_status_valid = "T";
            $v_keterangan   = $v_keterangan_ext;
        } 
        
        if($v_status_valid_ext == 'Y' && $v_status_valid_temp == 'Y') {
            $v_status_valid = "Y";
            $v_keterangan   = "";
        }

        if(trim($data[0]) == "" || trim($data[1]) == "" || trim($data[2]) == "" || trim($data[3]) == "" || trim($data[4]) == ""){
            $v_status_valid = "T";
            $v_keterangan   = "Data tidak lengkap. NIS, Nama Siswa, Kelas, Tahun Ajaran, Semester harus diisi.";
        }

        if(trim($data[0]) != "" || trim($data[1]) != "" || trim($data[2]) != "" || trim($data[3]) != "" || trim($data[4]) != ""){
            $query  = "INSERT INTO tb_siswa_temp (NIS, NAMA_SISWA, KELAS, TAHUN_PELAJARAN, SEMESTER, STATUS_VALID, KETERANGAN, TGL_REKAM, PETUGAS_REKAM) 
                        VALUES (trim('$data[0]'), trim('$data[1]'), trim(UPPER('$data[2]')), trim('$data[3]'), trim(UPPER('$data[4]')), '$v_status_valid', '$v_keterangan', NOW(), '$session_user_id') ";
            $db_ext = mysqli_query($db_connect,$query);
        }
        
    }

    $query_list     = "SELECT A.NIS, A.NAMA_SISWA, A.KELAS, A.TAHUN_PELAJARAN, A.SEMESTER, A.STATUS_VALID, A.KETERANGAN FROM tb_siswa_temp A 
                            WHERE 1=1 AND UPPER(PETUGAS_REKAM) = UPPER('$session_user_id')
                            ORDER BY A.NIS, A.TAHUN_PELAJARAN DESC, A.SEMESTER, A.TGL_REKAM DESC"; 
    $db_ext         = mysqli_query($db_connect,$query_list);

    if(mysqli_num_rows($db_ext) > 0){
        // $data                       = mysqli_fetch_array($db_ext);
        while ($data = mysqli_fetch_array($db_ext)) 
        {
            $jdata[] = $data;
        }
    }


    if (count($rows) == 0) {
        $response['msg'] = "Data tidak ditemukan di file.";
    } else {
        $response['isSuccessful'] = true;
        $response['data'] = $jdata;
    }
} catch (Exception $e) {
    $response['msg'] = "Gagal membaca file, pastikan data di excel sudah terisi dengan benar: " . $e->getMessage();
}

echo json_encode($response);
?>