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

        $requiredCols = 4;
        for ($i = 0; $i < $requiredCols; $i++) {
            if (!isset($data[$i])) {
                $data[$i] = '';
            }
        }
        
        if (!empty($data[0]) && !empty($data[1]) && !empty($data[2])) {
            $rows[] = [
                "tahun_pelajaran"   => $data[0],
                "kelas"             => $data[1],
                "nip"               => $data[2]
            ];
        }
        
        $v_tahun_pelajaran  = mysqli_real_escape_string($db_connect, trim($data[0]));
        $v_kelas            = mysqli_real_escape_string($db_connect, trim($data[1]));
        $v_nip              = mysqli_real_escape_string($db_connect, trim($data[2]));

        $v_status_kelas = "Y";
        $v_status_valid = "Y";
        $v_keterangan   = "";

        $query_count        = "SELECT COUNT(1) CNT_ROW FROM tb_wali_kelas A 
                                WHERE 1=1 AND NIP = UPPER('$v_nip') AND KELAS = UPPER('$v_kelas') AND TAHUN_PELAJARAN = '$v_tahun_pelajaran'";
        $db_ext             = mysqli_query($db_connect,$query_count);
        $data_count         = mysqli_fetch_array($db_ext);
        $v_total_cek_wali   = $data_count['CNT_ROW'];
        $v_status_wali      = ($v_total_cek_wali > 0) ? "T" : "Y";
        $v_keterangan_wali  = ($v_total_cek_wali > 0) ? "Guru wali kelas sudah pernah direkam.<br> Silakan periksa kembali template data anda." : "";

        $query_count_temp   = "SELECT COUNT(1) CNT_ROW FROM tb_user A WHERE 1=1 AND A.STATUS_AKTIF = 'Y' AND A.STATUS_GURU = 'Y' AND A.NIP = UPPER('$v_nip')";
        $db_ext             = mysqli_query($db_connect,$query_count_temp);
        $data_count         = mysqli_fetch_array($db_ext);
        $v_total_cek_guru   = $data_count['CNT_ROW'];
        $v_status_cek_guru  = ($v_total_cek_guru <= 0) ? "T" : "Y";
        $v_keterangan_guru  = ($v_total_cek_guru <= 0) ? "Data guru tidak ditemukan atau tidak aktif.<br> Pastikan NIP guru sudah benar dan status guru aktif." : "";

        $query_count_temp   = "SELECT COUNT(1) CNT_ROW FROM tb_wali_kelas_temp A WHERE 1=1 AND A.NIP = UPPER('$v_nip') AND A.KELAS = UPPER('$v_kelas') AND A.TAHUN_PELAJARAN = '$v_tahun_pelajaran' AND UPPER(PETUGAS_REKAM) = UPPER('$session_user_id')";
        $db_ext             = mysqli_query($db_connect,$query_count_temp);
        $data_count         = mysqli_fetch_array($db_ext);
        $v_total_guru_temp  = $data_count['CNT_ROW'];
        $v_status_guru_temp = ($v_total_guru_temp > 0) ? "T" : "Y";
        $v_ket_guru_temp    = ($v_total_guru_temp > 0) ? "Data duplikat pada template excel.<br> Silakan periksa kembali template data anda." : "";

       if($v_status_wali == 'T') {
            $v_status_valid = "T";
            $v_keterangan   = $v_keterangan_wali;
        }
        
        if($v_status_cek_guru == 'T') {
            $v_status_valid = "T";
            $v_keterangan   = $v_keterangan_guru;
        }

        if($v_status_guru_temp == 'T') {
            $v_status_valid = "T";
            $v_keterangan   = $v_ket_guru_temp;
        }

        if(trim($data[0]) == "" || trim($data[1]) == "" || trim($data[2]) == ""){
            $v_status_valid = "T";
            $v_keterangan   = "Data tidak lengkap. Tahun Ajaran, Kelas, dan NIP harus diisi.";
        }

         // Pengecekan format kelas
        if ($v_kelas != "" && !preg_match('/^(X|XI|XII|XIII)\.([1-9]|1[0-9]|20)$/', $v_kelas)) {
            $v_status_valid = "T";
            $v_keterangan   = "Format kelas tidak valid. (Contoh: X.1 / XI.2 / XII.3 / XIII.4 dll).";
        }

        if(trim($data[0]) != "" && trim($data[1]) != "" && trim($data[2]) != ""){
            $query  = "INSERT INTO tb_wali_kelas_temp (TAHUN_PELAJARAN, KELAS, NIP, STATUS_VALID, KETERANGAN, TGL_REKAM, PETUGAS_REKAM) 
                        VALUES ('$v_tahun_pelajaran', UPPER('$v_kelas'), UPPER('$v_nip'), '$v_status_valid', '$v_keterangan', NOW(), '$session_user_id') ";
            
            $db_ext = mysqli_query($db_connect,$query);
        }
        
    }

    $query_list     = "SELECT A.*, NVL((SELECT B.NAMA FROM tb_user B WHERE B.NIP = A.NIP LIMIT 1), '-') NAMA_GURU 
                             FROM tb_wali_kelas_temp A 
                            WHERE 1=1 AND UPPER(PETUGAS_REKAM) = UPPER('$session_user_id')
                            ORDER BY A.TAHUN_PELAJARAN DESC, A.KELAS ASC, A.NIP ASC, A.TGL_REKAM DESC";
    $db_ext         = mysqli_query($db_connect,$query_list);

    if(mysqli_num_rows($db_ext) > 0){
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