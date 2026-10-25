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

        $requiredCols = 11;
        for ($i = 0; $i < $requiredCols; $i++) {
            if (!isset($data[$i])) {
                $data[$i] = '';
            }
        }

        // if($data[7] == null || $data[7] == ""){
        //     $data[7] = 0;
        // }
        
        $v_id_mapel_semester= mysqli_real_escape_string($db_connect, trim($data[0]));
        $v_id_mapel         = substr($v_id_mapel_semester, 0, -2);
        $v_nis              = mysqli_real_escape_string($db_connect, trim($data[2]));
        $v_nama_siswa       = mysqli_real_escape_string($db_connect, trim($data[3]));
        $v_kelas            = mysqli_real_escape_string($db_connect, trim($data[4]));
        $v_tahun_pelajaran  = mysqli_real_escape_string($db_connect, trim($data[5]));
        $v_semester         = mysqli_real_escape_string($db_connect, trim($data[6]));
        $v_cp_plus          = mysqli_real_escape_string($db_connect, trim($data[8]));
        $v_cp_min           = mysqli_real_escape_string($db_connect, trim($data[9]));

        // Pastikan minimal NIS dan Nama Siswa terisi
        if (!empty($v_id_mapel) && !empty($data[1]) && !empty($v_nis) && !empty($v_nama_siswa) && !empty($v_kelas) && !empty($v_tahun_pelajaran) && !empty($v_semester)) {
            $rows[] = [
                "idMapel"           => $v_id_mapel,
                "namaMapel"         => $data[1],
                "nis"               => $v_nis,
                "namaSiswa"         => $v_nama_siswa,
                "kelas"             => $v_kelas,
                "tahunpelajaran"    => $v_tahun_pelajaran,
                "semester"          => $v_semester,
                "na"                => $data[7],
                "cpPlus"            => $v_cp_plus,
                "cpMin"             => $v_cp_min
            ];
        }

        $v_status_valid_ext = "T";
        $v_keterangan_ext   = "";
        $v_status_valid_temp= "T";
        $v_keterangan_temp  = "";
        $v_status_valid_nip = "T";
        $v_keterangan_nip   = "";
        $v_status_valid_jadwal = "T";
        $v_keterangan_jadwal   = "";

        $query_count        = "SELECT COUNT(1) CNT_ROW FROM tb_penilaian A 
                                WHERE 1=1 AND UPPER(ID_MAPEL_SEMESTER) = UPPER('$v_id_mapel_semester') AND UPPER(NIS) = UPPER(trim('$v_nis')) AND UPPER(KELAS) = UPPER('$v_kelas') AND UPPER(TAHUN_PELAJARAN) = UPPER('$v_tahun_pelajaran') ";
        $db_ext             = mysqli_query($db_connect,$query_count);
        $data_count         = mysqli_fetch_array($db_ext);
        $v_total_exist      = $data_count['CNT_ROW'];
        $v_status_valid_ext = ($v_total_exist > 0) ? "T" : "Y";
        $v_keterangan_ext   = ($v_total_exist > 0) ? "Siswa sudah pernah dinilai sebelumnya." : "";

        $query_count_temp   = "SELECT COUNT(1) CNT_ROW FROM tb_penilaian_temp A WHERE 1=1 AND UPPER(ID_MAPEL) = UPPER('$v_id_mapel') AND UPPER(NIS) = UPPER(trim('$v_nis')) AND UPPER(KELAS) = UPPER('$v_kelas') AND UPPER(TAHUN_PELAJARAN) = UPPER('$v_tahun_pelajaran') AND UPPER(SEMESTER) = UPPER('$v_semester') AND UPPER(PETUGAS_REKAM) = UPPER('$session_user_id')";
        $db_ext             = mysqli_query($db_connect,$query_count_temp);
        $data_count         = mysqli_fetch_array($db_ext);
        $v_total_exist_temp = $data_count['CNT_ROW'];
        $v_status_valid_temp= ($v_total_exist_temp > 0) ? "T" : "Y";
        $v_keterangan_temp  = ($v_total_exist_temp > 0) ? "Data duplikat pada template excel." : "";

        if($session_st_admin == "Y")
        {
            $query_nip_guru     = "SELECT NIP FROM tb_guru_mapel A WHERE 1=1 AND ID_MAPEL_SEMESTER = '$v_id_mapel_semester' ";
            $db_ext             = mysqli_query($db_connect,$query_nip_guru);
            $data_nip_guru      = mysqli_fetch_array($db_ext);
            $v_nip              = $data_nip_guru['NIP'];
            if($v_nip == '' || $v_nip == null)
            {
                $v_status_valid_nip = "T";
                $v_total_cek_nip    = 0;
                $v_keterangan_nip   = "Data user perekam tidak ditemukan sebagai guru dari mapel dengan kelas, semester dan tahun pelajaran tersebut.";
            }
            else
            {
                $v_status_valid_nip = "Y";
                $v_total_cek_nip    = 1;
                $v_keterangan_nip   = "";
            }
        }
        else
        {
            $query_count_nip    = "SELECT COUNT(1) CNT_ROW FROM tb_guru_mapel A WHERE 1=1 AND UPPER(nip) = UPPER('$session_user_id') AND ID_MAPEL_SEMESTER = '$v_id_mapel_semester' ";
            $db_ext             = mysqli_query($db_connect,$query_count_nip);
            $data_count         = mysqli_fetch_array($db_ext);
            $v_total_cek_nip    = $data_count['CNT_ROW'];
            $v_status_valid_nip = ($v_total_cek_nip < 1) ? "T" : "Y";
            $v_nip              = ($v_total_cek_nip > 0) ? $session_user_id : "";
            $v_keterangan_nip   = ($v_total_cek_nip < 1) ? "Data user perekam tidak ditemukan sebagai guru dari mapel dengan kelas, semester dan tahun pelajaran tersebut." : "";
        }
        
        $query_count_siswa  = "SELECT COUNT(1) CNT_ROW FROM tb_siswa WHERE 1=1 AND UPPER(SEMESTER) = UPPER('$v_semester') AND UPPER(NIS) = UPPER(trim('$v_nis')) AND UPPER(KELAS) = UPPER('$v_kelas') AND UPPER(TAHUN_PELAJARAN) = UPPER('$v_tahun_pelajaran') ";
        $db_ext             = mysqli_query($db_connect,$query_count_siswa);
        $data_count         = mysqli_fetch_array($db_ext);
        $v_total_cek_siswa  = $data_count['CNT_ROW'];
        $v_status_valid_siswa = ($v_total_cek_siswa < 1) ? "T" : "Y";
        $v_keterangan_siswa   = ($v_total_cek_siswa < 1) ? "Data siswa dengan NIS, Kelas, Semester dan Tahun Ajaran tidak ditemukan." : "";

        $query_count_jadwal = "SELECT COUNT(1) CNT_ROW FROM tb_jadwal_penilaian WHERE CURDATE() BETWEEN TGL_AWAL AND TGL_AKHIR AND UPPER(TAHUN_PELAJARAN) = UPPER('$v_tahun_pelajaran') AND UPPER(SEMESTER) = UPPER('$v_semester')";
        $db_ext             = mysqli_query($db_connect,$query_count_jadwal);
        $data_count         = mysqli_fetch_array($db_ext);
        $v_total_cek_jadwal = $data_count['CNT_ROW'];
        $v_status_valid_jadwal = ($v_total_cek_jadwal < 1) ? "T" : "Y";
        $v_keterangan_jadwal   = ($v_total_cek_jadwal < 1) ? "Jadwal Penilaian dengan Tahun Ajaran $v_tahun_pelajaran dan Semester $v_semester belum dimulai atau sudah ditutup." : "";

        if($v_status_valid_ext == "T"){
            $v_status_valid = "T";
            $v_keterangan   = $v_keterangan_ext;
        } 
        
        if($v_status_valid_temp == "T"){
            $v_status_valid = "T";
            $v_keterangan   = $v_keterangan_temp;
        }
        
        if($v_status_valid_nip == "T"){
            $v_status_valid = "T";
            $v_keterangan   = $v_keterangan_nip;
        }
        
        if($v_status_valid_siswa == "T"){
            $v_status_valid = "T";
            $v_keterangan   = $v_keterangan_siswa;
        } 
        
        if($v_status_valid_jadwal == "T"){
            $v_status_valid = "T";
            $v_keterangan   = $v_keterangan_jadwal;
        } 
        
        if($v_status_valid_ext == 'Y' && $v_status_valid_temp == 'Y' && $v_status_valid_nip == 'Y' && $v_status_valid_jadwal == 'Y' && $v_status_valid_siswa == 'Y') {
            $v_status_valid = "Y";
            $v_keterangan   = "";
        }

        if($v_total_cek_nip < 1){
            $v_status_valid = "T";
            $v_keterangan   = $v_keterangan_nip;
        }

        if(trim($v_id_mapel) == "" || trim($data[1]) == "" || trim($v_nis) == "" || trim($v_nama_siswa) == "" || trim($v_kelas) == "" || trim($v_tahun_pelajaran) == "" || trim($v_semester) == "" || trim($data[7]) == "" || trim($v_cp_plus) == "" || trim($v_cp_min) == ""){
            $v_status_valid = "T";
            $v_keterangan   = "Data tidak lengkap. ID MAPEL, NAMA MAPEL, NIS, NAMA SISWA, KELAS,TAHUN PELAJARAN, SEMESTER, NILAI_AKHIR, CP+, CP- harus diisi.";
        }
        
        if(trim($v_id_mapel) != "" || trim($data[1]) != "" || trim($v_nis) != "" || trim($v_nama_siswa) != "" || trim($v_kelas) != "" || trim($v_tahun_pelajaran) != "" || trim($v_semester) != "" || trim($data[7]) != "" || trim($v_cp_plus) != "" || trim($v_cp_min) != ""){
            $query  = "INSERT INTO tb_penilaian_temp (ID_MAPEL, ID_MAPEL_SEMESTER, NIS, NAMA_SISWA, KELAS, TAHUN_PELAJARAN, SEMESTER, NILAI_AKHIR, CP_PLUS, CP_MIN, NIP_GURU, STATUS_VALID, KETERANGAN, TGL_REKAM, PETUGAS_REKAM)
                        VALUES ('$v_id_mapel', '$v_id_mapel_semester', '$v_nis', '$v_nama_siswa', UPPER('$v_kelas'), '$v_tahun_pelajaran', '$v_semester', trim('$data[7]'), '$v_cp_plus', '$v_cp_min', '$v_nip', '$v_status_valid', '$v_keterangan', NOW(), '$session_user_id') ";
            // var_dump($query);die;'
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