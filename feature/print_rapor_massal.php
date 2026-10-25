<?php
    include_once '../includes/session.php';
    include_once '../koneksi/koneksi.php';

    $v_tahun_pelajaran  = isset($_GET['tahunAjaran']) ? $_GET['tahunAjaran'] : '';
    $v_semester         = isset($_GET['semester']) ? $_GET['semester'] : '';
    $v_kelas            = isset($_GET['kelas']) ? $_GET['kelas'] : '';
    $v_tempat           = isset($_GET['tempatDiberikan']) ? $_GET['tempatDiberikan'] : '-';
    $v_tanggal          = isset($_GET['tglDiberikan']) ? $_GET['tglDiberikan'] : '-';
?>

<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Rapor-<?=$v_kelas.'-'.$v_tahun_pelajaran.'-'.$v_semester.'-'.date("Y-m-d-H:i:s");?></title>
    <style>
        /* @page { size: A4; margin: 15mm; } */  /* A4 */
        /*@page { size: 216mm 356mm; margin: 15mm; } */ /* Legal */
        @page { size: 210mm 330mm; margin: 15mm; } /* Folio */
        body {
        background: #ccc; /* area luar kertas abu-abu */
        /* background: #fff; */
        margin: 0;
        padding: 0;
        }
        .page {
        background: #fff;
        width: 210mm;
        min-height: 297mm;
        margin: 10px auto;
        padding: 15mm;
        box-sizing: border-box;
        /* box-shadow: 0 0 5mm rgba(0,0,0,0.3); */
        page-break-after: always;
        font-family: Tahoma, sans-serif;
        font-size: 8pt;
        color: #111;
        }
        header {
        display: flex;
        align-items: center;
        border-bottom: 2px solid #000;
        padding-bottom: 10px;
        margin-bottom: 15px;
        }
        header img {
        height: 60px;
        margin-right: 15px;
        }
        header .kop {
        flex: 1;
        text-align: center;
        }
        header .kop h1 {
        margin: 0;
        font-size: 14pt;
        }
        header .kop h2 {
        margin: 0;
        font-size: 12pt;
        }
        header .kop p {
        margin: 2px 0;
        font-size: 10pt;
        }
        table.identitas, table.nilai, table.ekstrakurikuler, table.kokurikuler,table.ttd, table.keputusan_naik_kelas, table.tanggapan_ortu, table.pkl {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 12px;
        }
        table.absensi {
        width: 49%;
        border-collapse: collapse;
        margin-bottom: 12px;
        }
        table.catatan_wali_kelas {
        width: 49%;
        border-collapse: collapse;
        margin-bottom: 12px;
        }
        table.footnote {
        width: 85%;
        border-collapse: collapse;
        margin-bottom: 12px;
        }
        table.ttd {
            text-align: center;
        }
        table.nilai th, table.nilai td, table.ekstrakurikuler th, table.ekstrakurikuler td, table.kokurikuler th, table.kokurikuler td, table.absensi th, table.absensi td, table.catatan_wali_kelas th, table.catatan_wali_kelas td, table.keputusan_naik_kelas td, table.tanggapan_ortu th, table.tanggapan_ortu td, table.pkl th, table.pkl td{
        border: 2px solid #444;
        padding: 5px;
        font-size: 8pt;
        }
        table.nilai th, table.ekstrakurikuler th, table.kokurikuler th, table.absensi th, table.catatan_wali_kelas th, table.tanggapan_ortu th, table.pkl th{
        background: #fabf8f;
        }
        table.nilai {
            counter-reset: abjad; /* mulai counter */
        }
        td.urutan::before {
            counter-increment: abjad;
            content: counter(abjad, upper-alpha); /* upper-alpha = A B C ... */
            font-weight: bold;
        }
        table.keputusan {
        width: 70%;
        border-collapse: collapse;
        margin-bottom: 12px;
        }

        @media print {
            .ttd-wrapper {
                page-break-inside: avoid;
                break-inside: avoid;
            }
        }
    </style>
</head>
<body>

<div id="loader-overlay" style="position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(255,255,255,0.7); z-index:9999; display:flex; justify-content:center; align-items:center;">
    <img src="../assets/gif/loading.gif" alt="Loading..." style="width:80px; height:80px;">
</div>


<?php
    
    $v_filter          = " AND UPPER(KELAS) = UPPER('$v_kelas') AND UPPER(TAHUN_PELAJARAN) = UPPER('$v_tahun_pelajaran') AND UPPER(SEMESTER) = UPPER('$v_semester') ";
    $query_detil       = "SELECT B.*, 
                           (SELECT (SELECT A1.NAMA FROM tb_user A1 WHERE A1.NIP = A.NIP) FROM tb_wali_kelas A WHERE A.KELAS = B.KELAS AND A.TAHUN_PELAJARAN = B.TAHUN_PELAJARAN) NAMA_WALI_KELAS,
                            CASE WHEN   
                                (SELECT COUNT(1) FROM tb_penilaian A WHERE A.NIS = B.NIS AND A.KELAS = B.KELAS AND A.TAHUN_PELAJARAN = B.TAHUN_PELAJARAN AND A.SEMESTER IN ('Gasal','Genap') AND NILAI_AKHIR < 80) > 0
                            THEN 'T'
                            ELSE 'Y'
                            END STATUS_NAIK_KELAS
                        FROM tb_siswa B WHERE 1=1 $v_filter"; 

                        // var_dump($query_detil); exit();

    $db_ext             = mysqli_query($db_connect,$query_detil);
    if(mysqli_num_rows($db_ext) > 0){
        while ($data = mysqli_fetch_array($db_ext)) 
        {
            $v_nis              = $data['NIS'];
            $v_nama             = $data['NAMA_SISWA'];
            $v_kelas            = $data['KELAS'];
            $v_tahun_pelajaran  = $data['TAHUN_PELAJARAN'];
            $v_semester         = $data['SEMESTER'];
            include "print_rapor2.php";
        }
    }

    echo "<script>document.getElementById('loader-overlay').style.display = 'none';window.print();</script>";
?>


</body>
<!-- <script>document.getElementById('loader-overlay').style.display = 'none';window.print();</script> -->
</html>
