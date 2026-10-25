<?php
include '../includes/session.php';
include '../koneksi/koneksi.php';
include '../includes/fungsi.php';

$v_nip         = mysqli_real_escape_string($db_connect, $_POST['nip']);
$v_pwd_lama    = hybridEncrypt(mysqli_real_escape_string($db_connect, $_POST['pwdLama']),$session_key);
$v_pwd_baru    = hybridEncrypt(mysqli_real_escape_string($db_connect, $_POST['pwdBaru']),$session_key);
$v_filter      = " AND UPPER(NIP) = UPPER('$v_nip') ";


$query_cek    = "SELECT * FROM tb_user WHERE 1=1 $v_filter AND PASSWORD = '$v_pwd_lama'";
$db_cek       = mysqli_query($db_connect,$query_cek);
if(mysqli_num_rows($db_cek) == 0){
    $jsondata["isSuccessful"]   = false;
    $jsondata["ret"]            = "-1";
    $jsondata["msg"]            = "Password lama tidak sesuai, silakan coba kembali.";
    echo json_encode($jsondata);
    exit();
}


$query          = "UPDATE tb_user SET PASSWORD = '$v_pwd_baru', TGL_UBAH = NOW(), PETUGAS_UBAH = '$session_user_id'
                    WHERE 1=1 $v_filter ";
$db_ext         = mysqli_query($db_connect,$query);

if($db_ext == true)
{
    $jsondata["isSuccessful"]   = true;
    $jsondata["ret"]            = "1";
    $jsondata["msg"]            = "Berhasil ubah password.";
    echo json_encode($jsondata);

} else {
    $jsondata["isSuccessful"]   = false;
    $jsondata["ret"]            = "-1";
    $jsondata["msg"]            = "Gagal ubah password, silakan coba beberapa saat lagi.";
    echo json_encode($jsondata);
    exit();
}




?>

