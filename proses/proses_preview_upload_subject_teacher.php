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
        
        if (!empty($data[0]) && !empty($data[1])) 
        { 
            $rows[] = [
                "tahun_pelajaran"   => $data[0],
                "semester"          => $data[1],
                "kelas"             => $data[2],
                "id_mapel_semester" => $data[3],
                "nip_guru_mapel"    => $data[4]
            ];
        }
        
        $v_tahun_pelajaran  = trim(mysqli_real_escape_string($db_connect, $data[0]));
        $v_semester         = trim(mysqli_real_escape_string($db_connect, $data[1]));
        $v_kelas            = trim(mysqli_real_escape_string($db_connect, $data[2]));
        $v_id_mapel_semester= trim(mysqli_real_escape_string($db_connect, $data[3]));
        $v_id_mapel         = "";
        $v_nip_guru_mapel   = trim(mysqli_real_escape_string($db_connect, $data[4]));
        
        if($v_id_mapel_semester == "" || $v_id_mapel_semester == "--Pilih Mapel--")
        {
            $v_id_mapel_semester = "";
        }
        else
        {   
            $v_id_mapel_semester_split = explode("#", $v_id_mapel_semester, 2);
            $v_id_mapel_semester       = $v_id_mapel_semester_split[0];
            $v_id_mapel_split          = explode(".", $v_id_mapel_semester, 2);
            $v_id_mapel                = $v_id_mapel_split[0];
            $v_id_semester             = $v_id_mapel_split[1];
        }

        $v_status_valid     = "Y";
        $v_keterangan       = "";

        $query_count        = "SELECT COUNT(1) CNT_ROW FROM tb_mapel A 
                                WHERE 1=1 AND ID_MAPEL_SEMESTER = UPPER('$v_id_mapel_semester') ";
        $db_ext             = mysqli_query($db_connect,$query_count);
        $data_count         = mysqli_fetch_array($db_ext);
        $v_total_cek_mapel  = $data_count['CNT_ROW'];
        $v_status_mapel     = ($v_total_cek_mapel <= 0) ? "T" : "Y";
        $v_keterangan_mapel = ($v_total_cek_mapel <= 0) ? "Mata pelajaran tidak ditemukan. Pastikan mata pelajaran sudah dipilih dengan benar dan sesuai pada template yang tersedia" : "";

        $query_count_temp   = "SELECT COUNT(1) CNT_ROW FROM tb_user A WHERE 1=1 AND A.STATUS_AKTIF = 'Y' AND A.STATUS_GURU = 'Y' AND A.NIP = UPPER('$v_nip_guru_mapel')";
        $db_ext             = mysqli_query($db_connect,$query_count_temp);
        $data_count         = mysqli_fetch_array($db_ext);
        $v_total_cek_guru   = $data_count['CNT_ROW'];
        $v_status_cek_guru  = ($v_total_cek_guru <= 0) ? "T" : "Y";
        $v_keterangan_guru  = ($v_total_cek_guru <= 0) ? "Data guru tidak ditemukan atau sudah tidak aktif. Pastikan NIP guru sudah benar dan status guru aktif." : "";

        $query_count_temp   = "SELECT COUNT(1) CNT_ROW FROM tb_guru_mapel A WHERE 1=1 AND A.ID_MAPEL_SEMESTER = '$v_id_mapel_semester' AND UPPER(KELAS) = UPPER('$v_kelas')";
        $db_ext             = mysqli_query($db_connect,$query_count_temp);
        $data_count         = mysqli_fetch_array($db_ext);
        $v_total_guru_mapel = $data_count['CNT_ROW'];
        $v_status_guru_mapel= ($v_total_guru_mapel > 0) ? "T" : "Y";
        $v_ket_guru_mapel   = ($v_total_guru_mapel > 0) ? "Data guru dengan mata pelajaran dan kelas tersebut sudah ada. Silakan periksa kembali template data anda." : "";

        $query_count_temp   = "SELECT COUNT(1) CNT_ROW FROM tb_guru_mapel_temp A WHERE 1=1 AND A.ID_MAPEL_SEMESTER = '$v_id_mapel_semester' AND UPPER(KELAS) = UPPER('$v_kelas') AND UPPER(PETUGAS_REKAM) = UPPER('$session_user_id')";
        $db_ext             = mysqli_query($db_connect,$query_count_temp);
        $data_count         = mysqli_fetch_array($db_ext);
        $v_total_duplicate  = $data_count['CNT_ROW'];
        $v_status_duplicate = ($v_total_duplicate > 0) ? "T" : "Y";
        $v_ket_duplicate    = ($v_total_duplicate > 0) ? "Data duplikat pada template excel. Silakan periksa kembali template data anda." : "";

        if($v_total_cek_mapel <= 0)
        {
            $v_status_valid = "T";
            $v_keterangan   = $v_keterangan_mapel;
        } 
         
        if($v_total_cek_guru <= 0)
        {
            $v_status_valid = "T";
            $v_keterangan   = $v_keterangan_guru;
        }
        
        if($v_total_guru_mapel > 0)
        {
            $v_status_valid = "T";
            $v_keterangan   = $v_ket_guru_mapel;
        } 

        if($v_total_duplicate > 0)
        {
            $v_status_valid = "T";
            $v_keterangan   = $v_ket_duplicate;
        } 

        if((strtoupper($v_semester) == 'GASAL' && $v_id_semester != '1') || (strtoupper($v_semester) == 'GENAP' && $v_id_semester != '2'))
        {
            $v_status_valid = "T";
            $v_keterangan   = "Kode mata pelajaran tidak sesuai dengan sesuai dengan Semesternya.";
        }

        if($v_tahun_pelajaran == "" || $v_kelas == "" || $v_id_mapel_semester == "" || $v_nip_guru_mapel == ""){ 
            $v_status_valid = "T";
            $v_keterangan   = "Data tidak lengkap. Tahun Ajaran, Semester, Kelas, Mata Pelajaran dan NIP Guru Mata Pelajaran harus diisi."; 
        }

        
        if($v_tahun_pelajaran != "" && $v_semester != ""){
            $query  = "INSERT INTO tb_guru_mapel_temp (ID_MAPEL, ID_MAPEL_SEMESTER, KELAS, TAHUN_PELAJARAN, NIP, STATUS_VALID, KETERANGAN, TGL_REKAM, PETUGAS_REKAM) 
                        VALUES ('$v_id_mapel', '$v_id_mapel_semester', '$v_kelas', '$v_tahun_pelajaran', '$v_nip_guru_mapel', '$v_status_valid', '$v_keterangan', NOW(), '$session_user_id') "; 
            $db_ext = mysqli_query($db_connect,$query);
        }
        
    }

    $query_list     = "SELECT   A.*, 
                            (SELECT B.NAMA FROM tb_user B WHERE B.NIP = A.NIP) NAMA_GURU, 
                            (SELECT B.NAMA_MAPEL FROM tb_mapel B WHERE B.ID_MAPEL_SEMESTER = A.ID_MAPEL_SEMESTER) NAMA_MAPEL, 
                            (SELECT B.SEMESTER FROM tb_mapel B WHERE B.ID_MAPEL_SEMESTER = A.ID_MAPEL_SEMESTER) SEMESTER 
                    FROM tb_guru_mapel_temp A 
                    WHERE 1=1 AND UPPER(PETUGAS_REKAM) = UPPER('$session_user_id')
                    ORDER BY A.NIP, A.TAHUN_PELAJARAN DESC, A.ID_MAPEL_SEMESTER";
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