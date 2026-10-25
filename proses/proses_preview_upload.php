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
        $v_nis              = "";
        $v_nama_siswa       = "";
        $v_alamat           = "";
        $v_kelas            = "";
        $v_tahun_pelajaran  = "";

        $data = [];
        foreach ($cellIterator as $cell) {
            $cellValue = $cell->getValue();
            if ($cellValue === null) {
                $cellValue = '';
            }
            // cast ke string untuk menghindari warning pada trim/empty
            $data[] = trim((string)$cellValue);
        }
        $requiredCols = 5;
        for ($i = 0; $i < $requiredCols; $i++) {
            if (!isset($data[$i])) {
                $data[$i] = '';
            }
        }
        // Pastikan minimal NIS, Nama Siswa, Alamat, Kelas, dan Tahun Ajaran terisi
        if (!empty($data[0]) && !empty($data[1]) && !empty($data[2]) && !empty($data[3]) && !empty($data[4])) {
            $rows[] = [
                "nis" => $data[0],
                "nama_siswa" => $data[1],
                "alamat" => $data[2],
                "kelas" => $data[3],
                "tahun_pelajaran" => $data[4]
            ];
        
        }
        
        $v_nis              = mysqli_real_escape_string($db_connect, trim($data[0]));
        $v_nama_siswa       = mysqli_real_escape_string($db_connect, trim($data[1]));
        $v_alamat           = mysqli_real_escape_string($db_connect, trim($data[2]));
        $v_kelas            = mysqli_real_escape_string($db_connect, trim($data[3]));
        $v_tahun_pelajaran  = mysqli_real_escape_string($db_connect, trim($data[4]));
        // $v_nip_wali_kelas = mysqli_real_escape_string($db_connect, trim($data[6]));


        $v_status_valid_ext = "T";
        $v_keterangan_ext   = "";
        $v_status_valid_temp= "T";
        $v_keterangan_temp  = "";

        $query_count        = "SELECT COUNT(1) CNT_ROW FROM tb_siswa A 
                                WHERE 1=1 AND UPPER(NIS) = UPPER('$v_nis') AND UPPER(KELAS) = UPPER('$v_kelas') AND UPPER(TAHUN_PELAJARAN) = UPPER('$v_tahun_pelajaran')"; // AND UPPER(SEMESTER) = UPPER(trim('$data[6]'))
        $db_ext             = mysqli_query($db_connect,$query_count);
        $data_count         = mysqli_fetch_array($db_ext);
        $v_total_exist      = $data_count['CNT_ROW'];
        $v_status_valid_ext = ($v_total_exist > 0) ? "T" : "Y";
        $v_keterangan_ext   = ($v_total_exist > 0) ? "Data siswa sudah pernah direkam sebelumnya." : "";

        $query_count_temp   = "SELECT COUNT(1) CNT_ROW FROM tb_siswa_temp A WHERE 1=1 AND UPPER(NIS) = UPPER('$v_nis') AND UPPER(KELAS) = UPPER('$v_kelas') AND UPPER(TAHUN_PELAJARAN) = UPPER('$v_tahun_pelajaran') AND UPPER(PETUGAS_REKAM) = UPPER('$session_user_id')"; //AND UPPER(SEMESTER) = UPPER(trim('$data[6]')) 
        $db_ext             = mysqli_query($db_connect,$query_count_temp);
        $data_count         = mysqli_fetch_array($db_ext);
        $v_total_exist_temp = $data_count['CNT_ROW'];
        $v_status_valid_temp= ($v_total_exist_temp > 0) ? "T" : "Y";
        $v_keterangan_temp  = ($v_total_exist_temp > 0) ? "Data duplikat." : "";

        
        $query_count_wali   = "SELECT COUNT(1) CNT_ROW FROM tb_wali_kelas A WHERE 1=1 AND UPPER(kelas) = UPPER('$v_kelas') AND tahun_pelajaran = '$v_tahun_pelajaran'";
        $db_ext             = mysqli_query($db_connect,$query_count_wali);
        $data_count         = mysqli_fetch_array($db_ext);
        $v_total_cek_wali    = $data_count['CNT_ROW'];
        $v_status_valid_wali = ($v_total_cek_wali < 1) ? "T" : "Y";
        $v_keterangan_wali   = ($v_total_cek_wali < 1) ? "Tidak ada Wali Kelas pada kelas dan tahun pelajaran yang diisi." : "";

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

        if($v_total_cek_wali < 1){
            $v_status_valid = "T";
            $v_keterangan   = $v_keterangan_wali;
        }

        if($v_nis == "" || $v_nama_siswa == "" || $v_alamat == "" || $v_kelas == "" || $v_tahun_pelajaran == ""){
            $v_status_valid = "T";
            $v_keterangan   = "Data tidak lengkap. NIS, NISN, Nama Siswa, Alamat, Kelas, Tahun Ajaran, NIP Wali Kelas harus diisi."; //Semester,
        }
        
        if($v_nis != "" || $v_nama_siswa != "" || $v_alamat != "" || $v_kelas != "" || $v_tahun_pelajaran != ""){
            $query  = "INSERT INTO tb_siswa_temp (NIS, NAMA_SISWA, ALAMAT, KELAS, TAHUN_PELAJARAN, STATUS_VALID, KETERANGAN, TGL_REKAM, PETUGAS_REKAM) 
                        VALUES ('$v_nis', '$v_nama_siswa', '$v_alamat', UPPER('$v_kelas'), '$v_tahun_pelajaran', '$v_status_valid', '$v_keterangan', NOW(), '$session_user_id')";
            $db_ext = mysqli_query($db_connect,$query);
        }
        
    }

    $query_list     = "SELECT DISTINCT A.NIS, A.NAMA_SISWA, A.ALAMAT, A.KELAS, A.TAHUN_PELAJARAN, A.STATUS_VALID, A.KETERANGAN FROM tb_siswa_temp A 
                            WHERE 1=1 AND UPPER(PETUGAS_REKAM) = UPPER('$session_user_id')
                            ORDER BY A.NIS, A.TAHUN_PELAJARAN DESC, A.TGL_REKAM DESC"; //A.SEMESTER,
    $db_ext         = mysqli_query($db_connect,$query_list);

    if(mysqli_num_rows($db_ext) > 0){
        // $data                       = mysqli_fetch_array($db_ext);
        while ($data = mysqli_fetch_array($db_ext)) 
        {
            $jdata[] = $data;
        }
        if (count($rows) == 0) {
            $response['msg'] = "Pastikan semua kolom data sudah terisi.";
        } else {
            $response['msg'] = "Berhasil upload " . count($rows) . " data.";
        }
        $response['isSuccessful']   = true;
        $response['data']           = $jdata;
    }

} catch (Exception $e) {
    $response['msg'] = "Gagal membaca file, pastikan data di excel sudah terisi dengan benar: " . $e->getMessage();
}

echo json_encode($response);
?>