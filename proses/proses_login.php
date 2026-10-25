<?php
include '../koneksi/koneksi.php';
include '../includes/fungsi.php';

$key_value  = 'WlhKaGNHOXlVMjFoYTNCaFEyOXdlWEpwWjJoMFFubEdaV3hzYVc1aFMzVnRZV3ho';
$v_nip      = mysqli_real_escape_string($db_connect, $_POST['nip']);
$v_password = hybridEncrypt(mysqli_real_escape_string($db_connect, $_POST['password']),$key_value);
$query  = "SELECT * FROM tb_user WHERE NIP = '$v_nip' AND PASSWORD = '$v_password' AND STATUS_AKTIF = 'Y'";

$db_ext = mysqli_query($db_connect,$query);

if(mysqli_num_rows($db_ext) > 0){
    $data                       = mysqli_fetch_array($db_ext);
    session_start();
    $_SESSION['user_id']            = $data['NIP'];
    $_SESSION['nama']               = $data['NAMA'];
    $_SESSION['POSISI_JABATAN']     = $data['POSISI_JABATAN'];
    $_SESSION['STATUS_KEPALA_SEKOLAH'] = $data['STATUS_KEPALA_SEKOLAH'];
    $_SESSION['STATUS_ADMIN']       = $data['STATUS_ADMIN'];
    $_SESSION['STATUS_GURU']        = $data['STATUS_GURU'];
    $_SESSION['STATUS_KESISWAAN']   = $data['STATUS_KESISWAAN'];
    $_SESSION['STATUS_KURIKULUM']   = $data['STATUS_KURIKULUM'];
    $_SESSION['key_value']          = $key_value;
    // Simpan password (terenkripsi oleh hybridEncrypt) ke session
    $_SESSION['password']           = $v_password;
    $jdata['userId']                = $data['NIP'];
    $jdata['nama']                  = $data['NAMA'];
    $jdata['posisiJabatan']         = $data['POSISI_JABATAN'];
    $jsondata["isSuccessful"]       = true;
    $jsondata["ret"]                = "1";
    $jsondata["data"]               = $jdata;
    $jsondata["msg"]                = "Sukses login.";
    echo json_encode($jsondata);

} else {
    $jsondata["isSuccessful"]   = false;
    $jsondata["ret"]            = "-1";
    $jsondata["msg"]            = "Komninasi NIP dan password tidak ditemukan, pastikan data yang diinput sudah sesuai.";
    echo json_encode($jsondata);
}


?>
