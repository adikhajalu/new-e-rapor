<?php
include '../includes/session.php';
include '../koneksi/koneksi.php';

header('Content-Type: application/json');

$tahunAjaran = mysqli_real_escape_string($db_connect, $_POST['tahunAjaran'] ?? '');
$kelas       = mysqli_real_escape_string($db_connect, $_POST['kelas'] ?? '');
$semester    = mysqli_real_escape_string($db_connect, $_POST['semester'] ?? '');
$dataSiswa   = json_decode($_POST['dataSiswa'] ?? '[]', true);

if($tahunAjaran === '' || $kelas === '' || $semester === '' || !is_array($dataSiswa) || count($dataSiswa) === 0) {
    echo json_encode([
        'isSuccessful' => false,
        'ret' => '-1',
        'msg' => 'Data untuk disimpan belum lengkap.'
    ]);
    exit();
}

try {
    mysqli_begin_transaction($db_connect);

    foreach($dataSiswa as $siswa) {
        $nis = mysqli_real_escape_string($db_connect, $siswa['nis'] ?? '');
        $catatanWaliKelas = mysqli_real_escape_string($db_connect, $siswa['catatanWaliKelas'] ?? '');

        if($nis === '') {
            throw new Exception('NIS siswa tidak ditemukan.');
        }

        $setStatusLsp = '';
        if(array_key_exists('statusKompetensiLsp', $siswa)) {
            $statusKompetensiLsp = mysqli_real_escape_string($db_connect, $siswa['statusKompetensiLsp'] ?? 'T');
            $setStatusLsp = "STATUS_KOMPETENSI_LSP = '$statusKompetensiLsp',";
        }

        if(array_key_exists('kokurikuler', $siswa)) {
            $kokurikuler = mysqli_real_escape_string($db_connect, $siswa['kokurikuler'] ?? '');
            $query = "UPDATE tb_siswa
                      SET KOKURIKULER = '$kokurikuler',
                          CATATAN_WALI_KELAS = '$catatanWaliKelas',
                          $setStatusLsp
                          TGL_UBAH = NOW(),
                          PETUGAS_UBAH = '$session_user_id'
                      WHERE NIS = '$nis'
                        AND KELAS = '$kelas'
                        AND TAHUN_PELAJARAN = '$tahunAjaran'
                        AND SEMESTER = '$semester'";
        } else {
            $query = "UPDATE tb_siswa
                      SET CATATAN_WALI_KELAS = '$catatanWaliKelas',
                          $setStatusLsp
                          TGL_UBAH = NOW(),
                          PETUGAS_UBAH = '$session_user_id'
                      WHERE NIS = '$nis'
                        AND KELAS = '$kelas'
                        AND TAHUN_PELAJARAN = '$tahunAjaran'
                        AND SEMESTER = '$semester'";
        }

        if(!mysqli_query($db_connect, $query)) {
            throw new Exception(mysqli_error($db_connect));
        }
    }

    mysqli_commit($db_connect);

    echo json_encode([
        'isSuccessful' => true,
        'ret' => '1',
        'msg' => 'Data kokurikuler dan catatan berhasil disimpan.'
    ]);
} catch(Throwable $e) {
    mysqli_rollback($db_connect);

    echo json_encode([
        'isSuccessful' => false,
        'ret' => '-1',
        'msg' => 'Gagal menyimpan data, silakan coba lagi.'
    ]);
}
