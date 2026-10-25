<?php
include '../includes/session.php';
include '../koneksi/koneksi.php';
include '../includes/fungsi.php';

$v_nip      = mysqli_real_escape_string($db_connect, $_POST['nip']);
$v_filter   = " AND UPPER(NIP) = UPPER('$v_nip') ";
$password   = hybridEncrypt('Smakpa123!', $session_key);

$query          = "UPDATE tb_user SET PASSWORD = '$password', TGL_UBAH = NOW(), PETUGAS_UBAH = '$session_user_id'
                    WHERE 1=1 $v_filter ";
$db_ext         = mysqli_query($db_connect,$query);

if($db_ext == true)
{
    $jsondata["isSuccessful"]   = true;
    $jsondata["ret"]            = "1";
    $jsondata["msg"]            = "<span style='color: black;'>Password berhasil direset menjadi <label style='color: red; font-weight: bold;'>Smakpa123!</label> <br>Harap segera infokan password tersebut kepada user bersangkutan dan segera menggantinya dengan password terbaru.</span>";
    echo json_encode($jsondata);

} else {
    $jsondata["isSuccessful"]   = false;
    $jsondata["ret"]            = "-1";
    $jsondata["msg"]            = "Gagal reset password, silakan coba beberapa saat lagi.";
    echo json_encode($jsondata);
    exit();
}




?>

