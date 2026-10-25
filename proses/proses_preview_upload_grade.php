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
        $v_id_eskul         = mysqli_real_escape_string($db_connect, trim($data[2]));
        $v_nama_eskul       = mysqli_real_escape_string($db_connect, trim($data[3]));
        $v_kelas            = mysqli_real_escape_string($db_connect, trim($data[4]));
        $v_nis              = mysqli_real_escape_string($db_connect, trim($data[5]));
        $v_na               = mysqli_real_escape_string($db_connect, trim($data[6]));

        // Pastikan minimal NIS dan Nama Siswa terisi
        if (!empty($v_id_eskul) && !empty($v_nama_eskul) && !empty($v_nis) && !empty($v_kelas) && !empty($v_tahun_pelajaran) && !empty($v_semester) && !empty($v_na)) {
            $rows[] = [
                "idEkstrakurikuler"     => $v_id_eskul,
                "namaEkstrakurikuler"   => $v_nama_eskul,
                "nis"                   => $v_nis,
                "kelas"                 => $v_kelas,
                "tahunpelajaran"        => $v_tahun_pelajaran,
                "semester"              => $v_semester,
                "na"                    => $v_na
            ];
        }

        if(!empty($v_na)){
            if(trim($v_na) == "A" || trim($v_na) == "A (Baik Sekali)"){
                $v_na = "A (Baik Sekali)";
            } else if(trim($v_na) == "B" || trim($v_na) == "B (Baik)"){
                $v_na = "B (Baik)";
            } else if(trim($v_na) == "C" || trim($v_na) == "C (Cukup)"){
                $v_na = "C (Cukup)";
            } else if(trim($v_na) == "D" || trim($v_na) == "D (Kurang)"){
                $v_na = "D (Kurang)";
            } else if(trim($v_na) == "E" || trim($v_na) == "E (Kurang Sekali)"){
                $v_na = "E (Kurang Sekali)";
            } else {
                $v_na = "";
            }
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

        $query_count        = "SELECT COUNT(1) CNT_ROW FROM tb_penilaian_exr A 
                                WHERE 1=1 AND UPPER(ID_EKSTRAKURIKULER) = UPPER('$v_id_eskul') AND UPPER(NIS) = UPPER(trim('$v_nis')) AND UPPER(KELAS) = UPPER('$v_kelas') AND UPPER(TAHUN_PELAJARAN) = UPPER('$v_tahun_pelajaran') AND UPPER(SEMESTER) = UPPER('$v_semester') ";
        $db_ext             = mysqli_query($db_connect,$query_count);
        $data_count         = mysqli_fetch_array($db_ext);
        $v_total_exist      = $data_count['CNT_ROW'];
        $v_status_valid_ext = ($v_total_exist > 0) ? "T" : "Y";
        $v_keterangan_ext   = ($v_total_exist > 0) ? "Siswa sudah pernah dinilai sebelumnya." : "";

        $query_count_temp   = "SELECT COUNT(1) CNT_ROW FROM tb_penilaian_exr_temp A WHERE 1=1 AND UPPER(ID_EKSTRAKURIKULER) = UPPER('$v_id_eskul') AND UPPER(NIS) = UPPER(trim('$v_nis')) AND UPPER(KELAS) = UPPER('$v_kelas') AND UPPER(TAHUN_PELAJARAN) = UPPER('$v_tahun_pelajaran') AND UPPER(SEMESTER) = UPPER('$v_semester') AND UPPER(PETUGAS_REKAM) = UPPER('$session_user_id')";
        $db_ext             = mysqli_query($db_connect,$query_count_temp);
        $data_count         = mysqli_fetch_array($db_ext);
        $v_total_exist_temp = $data_count['CNT_ROW'];
        $v_status_valid_temp= ($v_total_exist_temp > 0) ? "T" : "Y";
        $v_keterangan_temp  = ($v_total_exist_temp > 0) ? "Data duplikat pada template excel." : "";
        
        $query_count_nis    = "SELECT COUNT(1) CNT_ROW FROM tb_siswa A WHERE 1=1 AND UPPER(nis) = UPPER('$v_nis') AND UPPER(kelas) = UPPER('$v_kelas') AND UPPER(TAHUN_PELAJARAN) = UPPER('$v_tahun_pelajaran') AND UPPER(SEMESTER) = UPPER('$v_semester')";
        // var_dump($query_count_nis);die;
        $db_ext             = mysqli_query($db_connect,$query_count_nis);
        $data_count         = mysqli_fetch_array($db_ext);
        $v_total_cek_nis    = $data_count['CNT_ROW'];
        $v_status_valid_nis = ($v_total_cek_nis < 1) ? "T" : "Y";
        $v_keterangan_nis   = ($v_total_cek_nis < 1) ? "Data Siswa dengan NIS, Kelas dan Tahun Ajaran tersebut tidak ditemukan." : "";

        $query_count_jadwal = "SELECT COUNT(1) CNT_ROW FROM tb_jadwal_penilaian WHERE CURDATE() BETWEEN TGL_AWAL AND TGL_AKHIR AND UPPER(TAHUN_PELAJARAN) = UPPER('$v_tahun_pelajaran') AND UPPER(SEMESTER) = UPPER('$v_semester')";
        $db_ext             = mysqli_query($db_connect,$query_count_jadwal);
        $data_count         = mysqli_fetch_array($db_ext);
        $v_total_cek_jadwal = $data_count['CNT_ROW'];
        $v_status_valid_jadwal = ($v_total_cek_jadwal < 1) ? "T" : "Y";
        $v_keterangan_jadwal   = ($v_total_cek_jadwal < 1) ? "Jadwal Penilaian dengan Tahun Ajaran $v_tahun_pelajaran dan Semester $v_semester belum dimulai atau sudah ditutup." : "";


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

        if($v_total_cek_jadwal < 1){
            $v_status_valid = "T";
            $v_keterangan   = $v_keterangan_jadwal;
        } 
        
        if($v_status_valid_ext == 'Y' && $v_status_valid_temp == 'Y' && $v_status_valid_nis == 'Y' && $v_status_valid_jadwal == 'Y') {
            $v_status_valid = "Y";
            $v_keterangan   = "";
        }

        if(trim($v_id_eskul) == "" || trim($v_nama_eskul) == "" || trim($v_nis) == "" || trim($v_kelas) == "" || trim($v_tahun_pelajaran) == "" || trim($v_semester) == "" || trim($v_na) == ""){
            $v_status_valid = "T";
            if($v_keterangan != "") {
                $v_keterangan  .= "\nData tidak lengkap: ID EKSTRAKURIKULER, NAMA EKSTRAKURIKULER, NIS, KELAS,TAHUN PELAJARAN, SEMESTER, NILAI_AKHIR harus diisi.";
            } else {
                $v_keterangan   = "Data tidak lengkap: ID EKSTRAKURIKULER, NAMA EKSTRAKURIKULER, NIS, KELAS,TAHUN PELAJARAN, SEMESTER, NILAI_AKHIR harus diisi.";
            }
        }
        
        if(trim($v_id_eskul) != "" || trim($v_nama_eskul) != "" || trim($v_nis) != "" || trim($v_kelas) != "" || trim($v_tahun_pelajaran) != "" || trim($v_semester) != "" || trim($v_na) != ""){
            $query  = "INSERT INTO tb_penilaian_exr_temp (ID_EKSTRAKURIKULER, NIS, KELAS, TAHUN_PELAJARAN, SEMESTER, NILAI_AKHIR, STATUS_VALID, KETERANGAN, TGL_REKAM, PETUGAS_REKAM)
                        VALUES (trim('$v_id_eskul'), trim(UPPER('$v_nis')), trim(UPPER('$v_kelas')), trim(UPPER('$v_tahun_pelajaran')), trim('$v_semester'), trim('$v_na'), '$v_status_valid', '$v_keterangan', NOW(), '$session_user_id') ";
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