<?php
include_once '../includes/session.php';
$datasent   = isset($_GET['datasent']) ? $_GET['datasent'] : '-';
$tempat     = isset($_GET['tempatDiberikan']) ? $_GET['tempatDiberikan'] : '-';
$tanggal    = isset($_GET['tglDiberikan']) ? $_GET['tglDiberikan'] : '-';
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Rapor SMK</title>
    <style>
        /* @page { size: A4; margin: 15mm; } */  /* A4 */
        /*@page { size: 216mm 356mm; margin: 15mm; } */ /* Legal */
        @page { size: 210mm 330mm; margin: 15mm; } /* Folio */
        body {
        background: #ccc; /* area luar kertas abu-abu */
        background: #fff;
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
        width: 40%;
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
        width: 85%;
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

<div class="page">
    <header>
        <img src="../assets/images/logo-kemenprin.png" alt="Logo" width="160px">
        <div class="kop">
        <h2>SEKOLAH MENENGAH KEJURUAN - SMAK PADANG</h2>
        <p>Jl. Alai Pauh V Kel. Kapalo Koto Kec. Pauh - Telp. (0751) 777703, Fax. (0751) 777702</p>
        <p><strong><h2>P A D A N G</h2></strong></p>
        </div>
    </header>
    <center><h3>LAPORAN HASIL BELAJAR</h3></center>
    <table class="identitas">
        <tr>
            <td width="20%">Nama Peserta Didik</td>
            <td width="2%">:</td>
            <td><strong><span id="nama-siswa"></span></strong></td>
            <td width="10%">Kelas</td>
            <td width="2%">:</td>
            <td width="20%"><strong><span id="kelas"></span></strong></td>
        </tr>
        <tr>
            <td>NIS / NISN</td>
            <td>:</td>
            <td><strong><span id="nis"></span></strong></td>
            <td>Fase</td>
            <td>:</td>
            <td><strong><span id="fase"></span></strong></td>
        </tr>
        <tr>
            <td>Nama Sekolah</td>
            <td>:</td>
            <td><strong>SMK SMAK Padang</strong></td>
            <td>Semester</td>
            <td>:</td>
            <td><strong><span id="semester"></span></strong></td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td>:</td>
            <td><strong><span id="alamat"></span></strong></td>
            <td>Tahun Ajaran</td>
            <td>:</td>
            <td><strong><span id="tahun-pelajaran"></span></strong></td>
        </tr>
    </table>

    <table class="nilai">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Mata Pelajaran</th>
                <th>Nilai Akhir</th>
                <th width="60%">Capaian Kompetensi</th>
            </tr>
        </thead>
        <tbody id="tb_mapel">
        </tbody>
    </table>
    
    <table class="kokurikuler" style="display:none">
        <thead>
            <tr>
                <th>Kokurikuler</th>
            </tr>
        </thead>
        <tbody id="tb_kokurikuler">
        </tbody>
    </table>
    
    <table class="ekstrakurikuler" style="display:none">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Kegiatan Ekstrakurikuler</th>
                <th width="60%">Keterangan</th>
            </tr>
        </thead>
        <tbody id="tb_ekstrakurikuler"></tbody>
    </table>
    
    <label><strong>C. Praktik Kerja Lapangan</strong></label>
    <table class="pkl" style="display:none">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Mitra DU/DI</th>
                <th>Lokasi</th>
            </tr>
        </thead>
        <tbody id="tb_pkl"></tbody>
    </table>

    <div style="display: flex; gap: 12px; align-items: flex-start;">
        <table class="absensi" style="flex: 1;">
            <thead>
                <tr>
                    <th colspan="3">KETIDAKHADIRAN</th>
                </tr>
            </thead>
            <tbody id="tb_absensi_siswa"></tbody>
        </table>

        <table class="catatan_wali_kelas" style="flex: 1;">
            <thead>
                <tr>
                    <th>Catatan Wali Kelas</th>
                </tr>
            </thead>
            <tbody id="tb_catatan_wali_kelas"></tbody>
        </table>
    </div>

    <table class="keputusan_naik_kelas" style="display:none">
        <tr>
            <td style="text-align:center;"><br><strong>Keterangan Naik Kelas : <span id="ket_naik_kelas"></span><span id="ket_tidak_naik_kelas"></span></strong><br><br></td>
        </tr>
    </table>

    <table class="tanggapan_ortu">
        <thead>
            <tr>
                <th>Tanggapan Orang Tua/Wali Murid</th>
            </tr>
        </thead>
        <tbody id="tb_tanggapan_ortu">
            <tr>
                <td><br><br><br></td>
            </tr>
        </tbody>
    </table>
    										
    <!-- <table class="footnote">
        <tr>
            <td><strong>Diberikan di</strong></td>
            <td><strong>: <span id="tempat-diberikan"></span></strong></td>
        </tr>
        <tr>
            <td><strong>Tanggal</strong></td>
            <td><strong>: <span id="tgl-diberikan"></span></strong></td>
        </tr>
    </table> -->

    <!-- <table class="keputusan" style="display:none">
        <tr>
            <td colspan="2"><strong>Keputusan :</strong></td>
        </tr>
        <tr>
            <td colspan="2">Berdasarkan hasil yang dicapai pada semester 1 dan 2, peserta didik ditetapkan</td>
        </tr>
        <tr>
            <td width="22%"><strong>Naik ke Kelas</strong></td>
            <td><strong>: <span id="kelas-selanjutnya"></span></strong></td>
        </tr>
        <tr>
            <td><strong>Tinggal di Kelas</strong></td>
            <td><strong>: <span id="kelas-saat-ini"></span></strong></td>
        </tr>
    </table> -->
    
    <table class="keputusan" style="display:none">
        <tr>
            <td style="width:20%"><strong>Diberikan di</strong></td>
            <td><strong>: <span id="tempat-diberikan"></span></strong></td>
            <td></td>
        </tr>
        <tr>
            <td><strong>Tanggal</strong></td>
            <td><strong>: <span id="tgl-diberikan"></span></strong></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="1"><strong>Keputusan </strong></td>
            <td colspan="2">:</td>
        </tr>
        <tr>
            <td colspan="3"><strong>Berdasarkan rapat kelulusan yang dilaksanakan dewan guru dan pertimbangan nilai semester 1 sampai 8, peserta didik dinyatakan : </strong><span id="hasil-kelulusan"></span></td>
        </tr>
    </table>

    <br><br>
    <div class="ttd-wrapper">
        <table class="ttd">
            <tr>
                <td>Mengetahui</td>
                <td></td>
                <td>Padang, <span id="tgl-cetak"></span></td>
            </tr>
            <tr>
                <td>Orang Tua/Wali</td>
                <td></td>
                <td>Wali Kelas,</td>
            </tr>
            <tr height="80">
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>.........................................</td>
                <td></td>
                <td><u><span id="nama-wali-kelas"></span></u></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td><span id="nip-wali-kelas"></span></td>
            </tr>
            <tr>
                <td></td>
                <td>Mengetahui,</td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td>Kepala Sekolah</td>
                <td></td>
            </tr>
            <tr height="80">
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td><u><span id="nama-kepsek"></span></u></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td><span id="nip-kepsek"></span></td>
                <td></td>
            </tr>
        </table>
    </div>
</div>

</body>

<script src="../assets/js/jquery-1.11.3.min.js"></script>
<script src="../assets/js/secure-encrypt.js"></script>
<script src="../includes/include_script.js"></script>
<script>
    $(document).ready(function(){
        var dataDecrypt = decryptJSON(decodeURIComponent('<?php echo $datasent; ?>'), '<?=$session_key?>');
        
        loadDataPrint(dataDecrypt.nis, dataDecrypt.semester, dataDecrypt.tahunAjaran, dataDecrypt.kelas);

        // const bulanIndo = [
        // "Januari", "Februari", "Maret", "April", "Mei", "Juni",
        // "Juli", "Agustus", "September", "Oktober", "November", "Desember"
        // ];
        
        // const today = new Date();
        // const tanggal = today.getDate();
        // const bulan = bulanIndo[today.getMonth()];
        // const tahun = today.getFullYear();
        // const hasilTgl = `${tanggal} ${bulan} ${tahun}`;

        // $("#tgl-cetak").html(hasilTgl);
    });

    function loadDataPrint(nis, semester, tahunAjaran, kelas)
    {
        var tempat      = '<?=$tempat;?>';
        var tanggalGet  = '<?=$tanggal;?>';
        const bulanIndo = [
            "Januari", "Februari", "Maret", "April", "Mei", "Juni",
            "Juli", "Agustus", "September", "Oktober", "November", "Desember"
            ];
        let dateObj = new Date(tanggalGet);
        
        let tanggal = dateObj.getDate();
        let bulan = bulanIndo[dateObj.getMonth()];
        let tahun = dateObj.getFullYear();

        tanggalDisplay = tanggal+' '+bulan+' '+tahun;

        $("#tempat-diberikan").html(tempat);
        $("#tgl-diberikan").html(tanggalDisplay);
        $("#tgl-cetak").html(tanggalDisplay);
        if(nis == "" || semester == "" || tahunAjaran == "" || kelas == "")
        {
            alert("Data Gagal Dimuat");
            return false;
        }

      $.ajax({
        url: "../proses/proses_get_detil_rapor.php",
        type: "POST",
            data: {
                nis             : nis,
                semester        : semester,
                tahunAjaran  : tahunAjaran,
                kelas           : kelas
            },
        success: function(response){
          var jdata = JSON.parse(response);
          if(jdata.isSuccessful == true){
            var data                = jdata.data;
            var kategori            = "";
            var subKategori         = "";
            var listPenilaian       = "";
            var tbExr               = "";
            var tbAbsSis            = "";
            var fase                = "";
            var tbKokurikuler       = "";
            var tbCatatan           = "";
            var tbPkl               = "";
            for(var i=0; i < jdata.data.siswa.length; i++){

                if (kelas.startsWith("X.")) {
                    fase = "E";
                } else {
                    fase = "F";
                }
                
                $("#nama-siswa").html(data.siswa[0].NAMA_SISWA);
                $("#kelas").html(data.siswa[0].KELAS);
                $("#nis").html(data.siswa[0].NIS);
                $("#fase").html(fase);
                $("#semester").html(data.siswa[0].SEMESTER);
                $("#alamat").html(data.siswa[0].ALAMAT);
                $("#tahun-pelajaran").html(data.siswa[0].TAHUN_PELAJARAN);
                $("#nama-wali-kelas").html(data.siswa[0].NAMA_WALI_KELAS);
                $("#nip-wali-kelas").html("NIP " + data.siswa[0].NIP_WALI_KELAS);

                if(semester == "Genap") {
                    // $(".keputusan").show();
                    // if(kelas.startsWith("X.")) {
                    //     $("#kelas-selanjutnya").html("XI (SEBELAS)");
                    //     $("#kelas-saat-ini").html("X (SEPULUH)");
                    // } else if(kelas.startsWith("XI.")) {
                    //     $("#kelas-selanjutnya").html("XII (DUA SEBELAS)");
                    //     $("#kelas-saat-ini").html("XI (SEBELAS)");
                    // } else if(kelas.startsWith("XII.")) {
                    //     $("#kelas-selanjutnya").html("XIII (TIGA SEBELAS)");
                    //     $("#kelas-saat-ini").html("XII (DUA SEBELAS)");
                    // }

                    // if(data.siswa[0].STATUS_NAIK_KELAS == 'Y') {
                    //     $("#kelas-saat-ini").prop("style","text-decoration-line: line-through;");
                    // } else {
                    //     $("#kelas-selanjutnya").prop("style","text-decoration-line: line-through;");
                    // }
                    if (!kelas.startsWith("XIII.")) {
                        $(".keputusan_naik_kelas").show();
                        if(data.siswa[0].STATUS_NAIK_KELAS == 'Y') 
                        {
                            if(kelas.startsWith("X.")) {
                                $("#ket_naik_kelas").html("Naik ke Kelas XI (SEBELAS)");
                            } else if(kelas.startsWith("XI.")) {
                                $("#ket_naik_kelas").html("Naik ke Kelas XII (DUA SEBELAS)");
                            } else if(kelas.startsWith("XII.")) {
                                $("#ket_naik_kelas").html("Naik ke Kelas XIII (TIGA SEBELAS)");
                            }
                        }
                        else
                        {
                            if(kelas.startsWith("X.")) {
                                $("#ket_tidak_naik_kelas").html("Tinggal di Kelas X (SEPULUH)");
                            } else if(kelas.startsWith("XI.")) {
                                $("#ket_tidak_naik_kelas").html("Tinggal di Kelas XI (SEBELAS)");
                            } else if(kelas.startsWith("XII.")) {
                                $("#ket_tidak_naik_kelas").html("Tinggal di Kelas XII (DUA SEBELAS)");
                            }
                        }
                    }
                    else
                    {
                        $(".keputusan").show();
                        if(data.siswa[0].STATUS_NAIK_KELAS == 'Y') 
                        {
                            $("#hasil-kelulusan").html("LULUS / <label style='text-decoration-line: line-through;'>TIDAK LULUS</label>");
                        }
                        else
                        {
                            $("#hasil-kelulusan").html("<label style='text-decoration-line: line-through;'>LULUS</label> / TIDAK LULUS");
                        }
                    }
                    
                }
            
            }

            if(jdata.data.siswa.length > 0) {
                $("#nama-kepsek").html(data.kepsek[0].NAMA);
                $("#nip-kepsek").html("NIP " + data.kepsek[0].NIP);
            }

            for(var i=0; i < jdata.data.exr.length; i++){
                $('.ekstrakurikuler').show();
                tbExr += "<tr>";
                tbExr += "<td>"+parseInt(i+1)+"</td>";
                tbExr += "<td>"+data.exr[i].NAMA_EKSTRAKURIKULER+"</td>";
                tbExr += "<td>"+data.exr[i].NILAI_AKHIR+"</td>";
                tbExr += "</tr>";
            
            }

            for(var i=0; i < jdata.data.absSis.length; i++){
                
                tbAbsSis += "<tr>";
                tbAbsSis += "<td width='50%'>Sakit</td>";
                tbAbsSis += "<td width='1%'>:</td>";
                tbAbsSis += "<td>"+data.absSis[i].SAKIT+" hari</td>";
                tbAbsSis += "</tr>";
                tbAbsSis += "<tr>";
                tbAbsSis += "<td>Izin</td>";
                tbAbsSis += "<td>:</td>";
                tbAbsSis += "<td>"+data.absSis[i].IZIN+" hari</td>";
                tbAbsSis += "</tr>";
                tbAbsSis += "<tr>";
                tbAbsSis += "<td>Tanpa Keterangan</td></td>";
                tbAbsSis += "<td>:</td></td>";
                tbAbsSis += "<td>"+data.absSis[i].TANPA_KETERANGAN+" hari</td>";
                tbAbsSis += "</tr>";
            }

            if(!kelas.startsWith("XIII."))
            {
                $(".kokurikuler").show();
                tbKokurikuler += "<tr>";
                tbKokurikuler += "<td style='text-align: center;'><br>"+formatNull(data.siswa[0].KOKURIKULER)+"<br><br></td>";
                tbKokurikuler += "</tr>";
            }

            if(kelas.startsWith("XIII."))
            {
                $(".pkl").show();
                for(var i=0; i < jdata.data.pkl.length; i++){
                    tbPkl += "<tr>";
                    tbPkl += "<td>"+parseInt(i+1)+"</td>";
                    tbPkl += "<td>"+data.pkl[i].MITRA_DU_DI+"</td>";
                    tbPkl += "<td>"+data.pkl[i].LOKASI+"</td>";
                    tbPkl += "</tr>";
                }
            }

            tbCatatan += "<tr>";
            tbCatatan += "<td style='text-align: center; height: 63px;' >"+data.siswa[0].CATATAN_WALI_KELAS+"</td>";
            tbCatatan += "</tr>";

            $("#tb_pkl").html(tbPkl);
            $("#tb_ekstrakurikuler").html(tbExr);
            $("#tb_absensi_siswa").html(tbAbsSis);
            $("#tb_kokurikuler").html(tbKokurikuler);
            $("#tb_catatan_wali_kelas").html(tbCatatan);

            var nomor = 0;

            for(var i=0; i < jdata.data.listKategori.length; i++){
                // console.log(jdata.data.listKategori[i].ID_KATEGORI_MAPEL);
                // console.log(jdata.data.listKategori[i].ID_SUB_KATEGORI_MAPEL);

                var resetNomorByKategori = "";

                if(data.listKategori[i].ID_KATEGORI_MAPEL != "" && data.listKategori[i].ID_SUB_KATEGORI_MAPEL == null) {

                    resetNomorByKategori = data.listKategori[i].ID_KATEGORI_MAPEL;
                    listPenilaian += "<tr>";
                    listPenilaian += "<td class='urutan' colspan='4'><strong>. "+data.listKategori[i].NAMA_KATEGORI_MAPEL+"</strong></td>"; 
                    listPenilaian += "</tr>";
                    
                    // console.log('data.mapelKategori.length');
                    // console.log(data.mapelKategori.length);return;
                    for(var x=0; x < data.mapelKategori.length; x++) {

                        nomor = parseInt(nomor+1);

                        if(resetNomorByKategori != data.mapelKategori[x].ID_KATEGORI_MAPEL) {
                            nomor = parseInt(0+1);
                            resetNomorByKategori = data.mapelKategori[x].ID_KATEGORI_MAPEL;
                            console.log('resetNomorByKategori '+resetNomorByKategori);
                        }

                        if(data.mapelKategori[x].ID_KATEGORI_MAPEL == data.listKategori[i].ID_KATEGORI_MAPEL) {
                            
                            listPenilaian += "<tr>";
                            listPenilaian += "<td rowspan='2'>"+nomor+"</td>";
                            listPenilaian += "<td rowspan='2'>"+data.mapelKategori[x].NAMA_MAPEL+"</td>";
                            listPenilaian += "<td rowspan='2'>"+data.mapelKategori[x].NILAI_AKHIR+"</td>";
                            listPenilaian += "<td>"+data.mapelKategori[x].CP_PLUS+"</td>";
                            listPenilaian += "</tr>";
                            listPenilaian += "<tr>";
                            listPenilaian += "<td>"+data.mapelKategori[x].CP_MIN+"</td>";
                            listPenilaian += "</tr>";
                        }
                    }
                }
                
                if(data.listKategori[i].ID_KATEGORI_MAPEL != null && data.listKategori[i].ID_SUB_KATEGORI_MAPEL != null) {

                    resetNomorBySubKategori = data.listKategori[i].ID_SUB_KATEGORI_MAPEL;
                    nomor = parseInt(nomor+1);
                    listPenilaian += "<tr>";
                    listPenilaian += "<td>"+nomor+"</td>";
                    listPenilaian += "<td colspan='3'>"+data.listKategori[i].NAMA_SUB_KATEGORI_MAPEL+"</td>"; 
                    listPenilaian += "</tr>";
                    
                    var counter     = -1;
                    var cekNomor    = nomor;
                    for(var x=0; x < data.mapelSubKategori.length; x++) {
                        counter = counter + 1;
                        // nomor = parseInt(nomor+1);

                        if(resetNomorBySubKategori != data.mapelSubKategori[x].ID_SUB_KATEGORI_MAPEL) {
                            counter = 0;
                            resetNomorBySubKategori = data.mapelSubKategori[x].ID_SUB_KATEGORI_MAPEL
                        }
                        var huruf = String.fromCharCode(97 + counter); // 97 = 'a'
                        if(data.mapelSubKategori[x].ID_KATEGORI_MAPEL == data.listKategori[i].ID_KATEGORI_MAPEL && (data.mapelSubKategori[x].ID_SUB_KATEGORI_MAPEL == data.listKategori[i].ID_SUB_KATEGORI_MAPEL)) {
                            
                            listPenilaian += "<tr>";
                            listPenilaian += "<td rowspan='2'></td>";
                            listPenilaian += "<td rowspan='2'>"+huruf+". "+data.mapelSubKategori[x].NAMA_MAPEL+"</td>";
                            listPenilaian += "<td rowspan='2'>"+data.mapelSubKategori[x].NILAI_AKHIR+"</td>";
                            listPenilaian += "<td>"+data.mapelSubKategori[x].CP_PLUS+"</td>";
                            listPenilaian += "</tr>";
                            listPenilaian += "<tr>";
                            listPenilaian += "<td>"+data.mapelSubKategori[x].CP_MIN+"</td>";
                            listPenilaian += "</tr>";
                        }
                    }
                }
            }

            $("#tb_mapel").html(listPenilaian);

          } else {
            $("#tb-siswa").html("<tr><td colspan='7'><center>-- Data tidak ditemukan --</center></td></tr>");
          }

          window.print();
        }
      });
    }
</script>

</html>
