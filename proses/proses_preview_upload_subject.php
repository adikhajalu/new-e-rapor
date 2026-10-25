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
        
        if (!empty($data[0]) && !empty($data[1]) && !empty($data[3])) { // && !empty($data[4])
            $rows[] = [
                "nama_mapel" => $data[0],
                "kategori_mapel" => $data[1],
                "sub_kategori_mapel" => $data[2],
                "tahun_pelajaran" => $data[3]
                // "semester" => $data[4]
            ];
        }
        
        $v_nama_mapel       = mysqli_real_escape_string($db_connect, trim($data[0]));
        $v_tahun_pelajaran  = mysqli_real_escape_string($db_connect, trim($data[3]));

        $v_status_valid_ext = "T";
        $v_keterangan_ext   = "";
        $v_status_valid_temp= "T";
        $v_keterangan_temp  = "";

        $query_count        = "SELECT COUNT(1) CNT_ROW FROM tb_mapel A 
                                WHERE 1=1 AND UPPER(NAMA_MAPEL) = UPPER('$v_nama_mapel') AND UPPER(TAHUN_PELAJARAN) = UPPER('$v_tahun_pelajaran') "; // AND UPPER(SEMESTER) = UPPER(trim('$data[4]'))
        $db_ext             = mysqli_query($db_connect,$query_count);
        $data_count         = mysqli_fetch_array($db_ext);
        $v_total_exist      = $data_count['CNT_ROW'];
        $v_status_valid_ext = ($v_total_exist > 0) ? "T" : "Y";
        $v_keterangan_ext   = ($v_total_exist > 0) ? "Data Mata Pelajaran sudah pernah direkam sebelumnya." : "";

        $query_count_temp   = "SELECT COUNT(1) CNT_ROW FROM tb_mapel_temp A WHERE 1=1 AND UPPER(NAMA_MAPEL) = UPPER('$v_nama_mapel') AND UPPER(TAHUN_PELAJARAN) = UPPER('$v_tahun_pelajaran') AND UPPER(PETUGAS_REKAM) = UPPER('$session_user_id')"; // AND UPPER(SEMESTER) = UPPER(trim('$data[4]')) 
        $db_ext             = mysqli_query($db_connect,$query_count_temp);
        $data_count         = mysqli_fetch_array($db_ext);
        $v_total_exist_temp = $data_count['CNT_ROW'];
        $v_status_valid_temp= ($v_total_exist_temp > 0) ? "T" : "Y";
        $v_keterangan_temp  = ($v_total_exist_temp > 0) ? "Data duplikat." : "";

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

        if(trim($data[0]) == "" || trim($data[1]) == "" || trim($data[1]) == "--Pilih Kategori--" || trim($data[3]) == ""){ // || trim($data[4]) == ""
            $v_status_valid = "T";
            $v_keterangan   = "Data tidak lengkap. Mata Pelajaran, Kategori Mata Pelajaran, Tahun Pelajaran harus diisi."; //, Semester
        }

        $id_kategori = "";
        $id_sub_kategori = "";

        if($data[1] == "--Pilih Kategori--" || $data[1] == ""){
            $id_kategori = "";
        }
        else
        {   
            $id_kategori_split  = explode("#", $data[1], 2);
            $id_kategori        = $id_kategori_split[0];
        }

        if($data[2] == "--Pilih Sub Kategori--" || $data[2] == ""){
            $id_sub_kategori_split  = "";
        }
        else
        {
            $id_sub_kategori_split  = explode("#", $data[2], 2);
            $id_sub_kategori        = $id_sub_kategori_split[0];
        }
        
        $id_kategori        = mysqli_real_escape_string($db_connect, trim($id_kategori));
        $id_sub_kategori    = mysqli_real_escape_string($db_connect, trim($id_sub_kategori));

        if(($id_kategori != "" && $id_kategori != "--Pilih Kategori--") && ($id_sub_kategori != "" && $id_sub_kategori != "--Pilih Sub Kategori--")){
            $query_count        = "SELECT COUNT(1) CNT_ROW FROM tb_sub_kategori_mapel A 
                                    WHERE 1=1 AND A.ID_SUB_KATEGORI_MAPEL = '$id_sub_kategori' AND A.id_kategori_mapel = '$id_kategori' "; 
            $db_ext             = mysqli_query($db_connect,$query_count);
            $data_count         = mysqli_fetch_array($db_ext);
            $v_total_exist      = $data_count['CNT_ROW'];
            $v_status_valid_ctg = ($v_total_exist > 0) ? "Y" : "T";
            $v_keterangan_ctg   = ($v_total_exist > 0) ? "" : "Data Kombinasi Ketegori dan Sub Kategori Mata Pelajaran tidak ditemukan.";
            
            if($v_status_valid_ctg == 'T') {
                $v_status_valid = "T";
                $v_keterangan   = $v_keterangan_ctg;
            }
        }

        if(trim($data[0]) != "" || trim($data[1]) != "" || trim($data[2]) != "" || trim($data[3]) != ""){
            $query  = "INSERT INTO tb_mapel_temp (NAMA_MAPEL, ID_KATEGORI_MAPEL, ID_SUB_KATEGORI_MAPEL, TAHUN_PELAJARAN, STATUS_VALID, KETERANGAN, TGL_REKAM, PETUGAS_REKAM) 
                        VALUES ('$v_nama_mapel', UPPER('$id_kategori'), UPPER('$id_sub_kategori'), '$v_tahun_pelajaran','$v_status_valid', '$v_keterangan', NOW(), '$session_user_id') "; //, 'trim('$data[4]')'
            
            $db_ext = mysqli_query($db_connect,$query);
        }
        
    }

    $query_list     = "SELECT A.NAMA_MAPEL, A.TAHUN_PELAJARAN, A.STATUS_VALID, A.KETERANGAN,
                            (SELECT B.NAMA_KATEGORI_MAPEL FROM tb_kategori_mapel B WHERE B.ID_KATEGORI_MAPEL = A.ID_KATEGORI_MAPEL) NAMA_KATEGORI,
                            NVL((SELECT B.NAMA_SUB_KATEGORI_MAPEL FROM tb_sub_kategori_mapel B WHERE B.ID_SUB_KATEGORI_MAPEL = A.ID_SUB_KATEGORI_MAPEL),'-') NAMA_SUB_KATEGORI 
                             FROM tb_mapel_temp A 
                            WHERE 1=1 AND UPPER(PETUGAS_REKAM) = UPPER('$session_user_id')
                            ORDER BY A.ID_KATEGORI_MAPEL, A.NAMA_MAPEL, A.TAHUN_PELAJARAN DESC, A.TGL_REKAM DESC"; // A.SEMESTER,
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