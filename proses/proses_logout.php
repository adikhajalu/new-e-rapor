<?php
session_start();
session_destroy();
$jsondata["isSuccessful"]   = true;
$jsondata["ret"]            = "1";
$jsondata["msg"]            = "Logout berhasil.";
echo json_encode($jsondata);
?>