<?php
    session_start();
    $session_user_id        = $_SESSION['user_id'];
    $session_nama           = $_SESSION['nama'];
    $session_posisi_jabatan = $_SESSION['POSISI_JABATAN'];
    $session_key            = $_SESSION['key_value'];
    $session_st_kepsek      = $_SESSION['STATUS_KEPALA_SEKOLAH'];
    $session_st_admin       = $_SESSION['STATUS_ADMIN'];
    $session_st_guru        = $_SESSION['STATUS_GURU'];
    $session_st_kesiswaan   = $_SESSION['STATUS_KESISWAAN'];
    $session_st_kurikulum   = $_SESSION['STATUS_KURIKULUM'];
    $default_img            = "../assets/images/user/avatar-2.jpg";
    $img_profile            = file_exists("../profileimage/" . $session_user_id . ".jpeg") ? "../profileimage/" . $session_user_id . ".jpeg" : $default_img;

    if(!isset($session_user_id)) 
    {
        header('Location: ../index.php');
        exit;
    }

    ?>