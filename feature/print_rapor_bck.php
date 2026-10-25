<?php
include_once '../includes/session.php';
$datasent   = isset($_GET['datasent']) ? $_GET['datasent'] : '-';
$tempat     = isset($_GET['tempatDiberikan']) ? $_GET['tempatDiberikan'] : '-';
$tanggal    = isset($_GET['tglDiberikan']) ? $_GET['tglDiberikan'] : '-';
$urut_siswa = isset($_GET['urutSiswa']) ? $_GET['urutSiswa'] : '-';
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
        /*background: #ccc;*/ /* area luar kertas abu-abu */
        /* background: #fff; */
        margin: 0;
        padding: 0;
        }
        .page<?=$urut_siswa;?> {
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
        table.identitas<?=$urut_siswa;?>, table.nilai<?=$urut_siswa;?>, table.ekstrakurikuler<?=$urut_siswa;?>, table.ttd<?=$urut_siswa;?> {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 12px;
        }
        table.absensi<?=$urut_siswa;?> {
        width: 50%;
        border-collapse: collapse;
        margin-bottom: 12px;
        }
        table.footnote<?=$urut_siswa;?> {
        width: 40%;
        border-collapse: collapse;
        margin-bottom: 12px;
        }
        table.ttd<?=$urut_siswa;?> {
            text-align: center;
        }
        table.nilai<?=$urut_siswa;?> th, table.nilai<?=$urut_siswa;?> td, table.ekstrakurikuler<?=$urut_siswa;?> th, table.ekstrakurikuler<?=$urut_siswa;?> td, table.absensi<?=$urut_siswa;?> th, table.absensi<?=$urut_siswa;?> td {
        border: 2px solid #444;
        padding: 5px;
        font-size: 8pt;
        }
        table.nilai<?=$urut_siswa;?> th, table.ekstrakurikuler<?=$urut_siswa;?> th, table.absensi<?=$urut_siswa;?> th {
        background: #fabf8f;
        }
        table.nilai<?=$urut_siswa;?> {
            counter-reset: abjad; /* mulai counter */
        }
        td.urutan<?=$urut_siswa;?>::before {
            counter-increment: abjad;
            content: counter(abjad, upper-alpha); /* upper-alpha = A B C ... */
            font-weight: bold;
        }
        table.keputusan<?=$urut_siswa;?> {
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

<div class="page<?=$urut_siswa;?>">
    <header>
        <img src="../assets/images/logo-kemenprin.png" alt="Logo" width="160px">
        <div class="kop<?=$urut_siswa;?>">
        <h2>SEKOLAH MENENGAH KEJURUAN - SMAK PADANG</h2>
        <p>Jl. Alai Pauh V Kel. Kapalo Koto Kec. Pauh - Telp. (0751) 777703, Fax. (0751) 777702</p>
        <p><strong><h2>P A D A N G</h2></strong></p>
        </div>
    </header>
    <center><h3>LAPORAN HASIL BELAJAR</h3></center>
    <table class="identitas<?=$urut_siswa;?>">
        <tr>
            <td width="20%">Nama Peserta Didik</td>
            <td width="2%">:</td>
            <td><strong><span id="nama-siswa<?=$urut_siswa;?>"></span></strong></td>
            <td width="10%">Kelas</td>
            <td width="2%">:</td>
            <td width="20%"><strong><span id="kelas<?=$urut_siswa;?>"></span></strong></td>
        </tr>
        <tr>
            <td>NIS / NISN</td>
            <td>:</td>
            <td><strong><span id="nis<?=$urut_siswa;?>"></span></strong></td>
            <td>Fase</td>
            <td>:</td>
            <td><strong><span id="fase<?=$urut_siswa;?>"></span></strong></td>
        </tr>
        <tr>
            <td>Nama Sekolah</td>
            <td>:</td>
            <td><strong>SMK SMAK Padang</strong></td>
            <td>Semester</td>
            <td>:</td>
            <td><strong><span id="semester<?=$urut_siswa;?>"></span></strong></td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td>:</td>
            <td><strong><span id="alamat<?=$urut_siswa;?>"></span></strong></td>
            <td>Tahun Ajaran</td>
            <td>:</td>
            <td><strong><span id="tahun-pelajaran<?=$urut_siswa;?>"></span></strong></td>
        </tr>
    </table>

    <table class="nilai<?=$urut_siswa;?>">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Mata Pelajaran</th>
                <th>Nilai Akhir</th>
                <th width="60%">Capaian Kompetensi</th>
            </tr>
        </thead>
        <tbody id="tb_mapel<?=$urut_siswa;?>">
        </tbody>
        <!-- Tambahkan baris lain sesuai kebutuhan -->
    </table>

    
    <table class="ekstrakurikuler<?=$urut_siswa;?>">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Kegiatan Ekstrakurikuler</th>
                <th width="60%">Keterangan</th>
            </tr>
        </thead>
        <tbody id="tb_ekstrakurikuler<?=$urut_siswa;?>"></tbody>
    </table>

    <table class="absensi<?=$urut_siswa;?>">
        <thead>
            <tr>
                <th colspan="3">KETIDAKHADIRAN</th>
        </thead>
        <tbody id="tb_absensi_siswa<?=$urut_siswa;?>"></tbody>
    </table>

    <table class="footnote<?=$urut_siswa;?>">
        <tr>
            <td><strong>Diberikan di</strong></td>
            <td><strong>: <span id="tempat-diberikan<?=$urut_siswa;?>"></span></strong></td>
        </tr>
        <tr>
            <td><strong>Tanggal</strong></td>
            <td><strong>: <span id="tgl-diberikan<?=$urut_siswa;?>"></span></strong></td>
        </tr>
    </table>

    <table class="keputusan<?=$urut_siswa;?>" style="display:none">
        <tr>
            <td colspan="2"><strong>Keputusan :</strong></td>
        </tr>
        <tr>
            <td colspan="2">Berdasarkan hasil yang dicapai pada semester 1 dan 2, peserta didik ditetapkan</td>
        </tr>
        <tr>
            <td width="22%"><strong>Naik ke Kelas</strong></td>
            <td><strong>: <span id="kelas-selanjutnya<?=$urut_siswa;?>"></span></strong></td>
        </tr>
        <tr>
            <td><strong>Tinggal di Kelas</strong></td>
            <td><strong>: <span id="kelas-saat-ini<?=$urut_siswa;?>"></span></strong></td>
        </tr>
    </table>

    <br><br>
    <div class="ttd-wrapper<?=$urut_siswa;?>">
        <table class="ttd<?=$urut_siswa;?>">
            <tr>
                <td>Mengetahui</td>
                <td></td>
                <td>Padang, <span id="tgl-cetak<?=$urut_siswa;?>"></span></td>
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
                <td><u><span id="nama-wali-kelas<?=$urut_siswa;?>"></span></u></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td><span id="nip-wali-kelas<?=$urut_siswa;?>"></span></td>
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
                <td><u><span id="nama-kepsek<?=$urut_siswa;?>"></span></u></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td><span id="nip-kepsek<?=$urut_siswa;?>"></span></td>
                <td></td>
            </tr>
        </table>
    </div>
</div>

</body>

<script src="../assets/js/jquery-1.11.3.min.js"></script>
<script src="../assets/js/secure-encrypt.js"></script>
<script>
    $(document).ready(function(){
        var dataDecrypt = decryptJSON(decodeURIComponent('<?php echo $datasent; ?>'), '<?=$session_key?>');
        
        loadDataPrint(dataDecrypt.nis, dataDecrypt.semester, dataDecrypt.tahunAjaran, dataDecrypt.kelas);

        const bulanIndo = [
        "Januari", "Februari", "Maret", "April", "Mei", "Juni",
        "Juli", "Agustus", "September", "Oktober", "November", "Desember"
        ];
        
        const today = new Date();
        const tanggal = today.getDate();
        const bulan = bulanIndo[today.getMonth()];
        const tahun = today.getFullYear();
        const hasilTgl = `${tanggal} ${bulan} ${tahun}`;

        $("#tgl-cetak<?=$urut_siswa;?>").html(hasilTgl);
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

        $("#tempat-diberikan<?=$urut_siswa;?>").html(tempat);
        $("#tgl-diberikan<?=$urut_siswa;?>").html(tanggalDisplay);
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
            for(var i=0; i < jdata.data.siswa.length; i++){

                if (kelas.startsWith("X.")) {
                    fase = "E";
                } else {
                    fase = "F";
                }
                
                $("#nama-siswa<?=$urut_siswa;?>").html(data.siswa[0].NAMA_SISWA);
                $("#kelas<?=$urut_siswa;?>").html(data.siswa[0].KELAS);
                $("#nis<?=$urut_siswa;?>").html(data.siswa[0].NIS + ' / ' + data.siswa[0].NISN);
                $("#fase<?=$urut_siswa;?>").html(fase);
                $("#semester<?=$urut_siswa;?>").html(data.siswa[0].SEMESTER);
                $("#alamat<?=$urut_siswa;?>").html(data.siswa[0].ALAMAT);
                $("#tahun-pelajaran<?=$urut_siswa;?>").html(data.siswa[0].TAHUN_PELAJARAN);
                $("#nama-wali-kelas<?=$urut_siswa;?>").html(data.siswa[0].NAMA_WALI_KELAS);
                $("#nip-wali-kelas<?=$urut_siswa;?>").html("NIP " + data.siswa[0].NIP_WALI_KELAS);

                if(semester == "Genap") {
                    $(".keputusan<?=$urut_siswa;?>").show();
                    if(kelas.startsWith("X.")) {
                        $("#kelas-selanjutnya<?=$urut_siswa;?>").html("XI (SEBELAS)");
                        $("#kelas-saat-ini<?=$urut_siswa;?>").html("X (SEPULUH)");
                    } else if(kelas.startsWith("XI.")) {
                        $("#kelas-selanjutnya<?=$urut_siswa;?>").html("XII (DUA SEBELAS)");
                        $("#kelas-saat-ini<?=$urut_siswa;?>").html("XI (SEBELAS)");
                    } else if(kelas.startsWith("XII.")) {
                        $("#kelas-selanjutnya<?=$urut_siswa;?>").html("XIII (TIGA SEBELAS)");
                        $("#kelas-saat-ini<?=$urut_siswa;?>").html("XII (DUA SEBELAS)");
                    }

                    if(data.siswa[0].STATUS_NAIK_KELAS == 'Y') {
                        $("#kelas-saat-ini<?=$urut_siswa;?>").prop("style","text-decoration-line: line-through;");
                    } else {
                        $("#kelas-selanjutnya<?=$urut_siswa;?>").prop("style","text-decoration-line: line-through;");
                    }
                }
            
            }

            if(jdata.data.siswa.length > 0) {
                $("#nama-kepsek<?=$urut_siswa;?>").html(data.kepsek[0].NAMA);
                $("#nip-kepsek<?=$urut_siswa;?>").html("NIP " + data.kepsek[0].NIP);
            }

            for(var i=0; i < jdata.data.exr.length; i++){
                
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

            $("#tb_ekstrakurikuler<?=$urut_siswa;?>").html(tbExr);
            $("#tb_absensi_siswa<?=$urut_siswa;?>").html(tbAbsSis);

            var nomor = 0;

            for(var i=0; i < jdata.data.listKategori.length; i++){
                // console.log(jdata.data.listKategori[i].ID_KATEGORI_MAPEL);
                // console.log(jdata.data.listKategori[i].ID_SUB_KATEGORI_MAPEL);

                var resetNomorByKategori = "";


                if(data.listKategori[i].ID_KATEGORI_MAPEL != "" && data.listKategori[i].ID_SUB_KATEGORI_MAPEL == "") {

                    resetNomorByKategori = data.listKategori[i].ID_KATEGORI_MAPEL;
                    listPenilaian += "<tr>";
                    listPenilaian += "<td class='urutan' colspan='4'><strong>. "+data.listKategori[i].NAMA_KATEGORI_MAPEL+"</strong></td>"; 
                    listPenilaian += "</tr>";
                    

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
                
                if(data.listKategori[i].ID_KATEGORI_MAPEL != "" && data.listKategori[i].ID_SUB_KATEGORI_MAPEL != "") {

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

            $("#tb_mapel<?=$urut_siswa;?>").html(listPenilaian);

            
            // for(var i=0; i < jdata.data.mapel.length; i++){
            //     if(data.mapel[i].ID_SUB_KATEGORI_MAPEL == ""){

            //         if(kategori)
            //         mapelNKategori += 
            //     }

            //     $("#nama-siswa").html(data.siswa[0].NAMA_SISWA);
            //     $("#kelas").html(data.siswa[0].KELAS);
            //     $("#nis").html(data.siswa[0].NIS + ' / ' + data.siswa[0].NISN);
            //     $("#fase").html(fase);
            //     $("#semester").html(data.siswa[0].SEMESTER);
            //     $("#alamat").html(data.siswa[0].ALAMAT);
            //     $("#tahun-plajaran").html(data.siswa[0].TAHUN_PELAJARAN);
            
            // }
            
            // <tr>
            //     <td colspan="4"><strong>A. Kelompok Mata Pelajaran Umum</strong></td>
            // </tr>
            // <tr>
            //     <td rowspan="2">1</td>
            //     <td rowspan="2">Matematika</td>
            //     <td rowspan="2">81</td>
            //     <td>Menunjukkan pemahaman yang baik dalam materi SPLDV dan Program Linear</td>
            // </tr>
            // <tr>
            //     <td>Menunjukkan pemahaman yang baik dalam materi SPLDV dan Program Linear</td>
            // </tr>
        //     var tb_siswa    = "";
        //     var op_page     = "";
        //     var v_selected  = "";
        //     for(var i=0; i < jdata.data.length; i++){
        //       tb_siswa += "<tr>";
        //       tb_siswa += "<td>" + jdata.data[i].NOMOR + "</td>";
        //       tb_siswa += "<td style='text-align: center;'>" + jdata.data[i].NIS + "</td>";
        //       tb_siswa += "<td>" + jdata.data[i].NAMA_SISWA + "</td>";
        //       tb_siswa += "<td>" + jdata.data[i].NAMA_MAPEL + "</td>";
        //       tb_siswa += "<td>" + jdata.data[i].KELAS + "</td>";
        //       tb_siswa += "<td style='text-align: center;'>" + jdata.data[i].SEMESTER + "</td>";
        //       tb_siswa += "<td style='text-align: center;'>" + jdata.data[i].TAHUN_PELAJARAN + "</td>";
        //       tb_siswa += "<td style='text-align: center;'>" + jdata.data[i].NILAI_AKHIR + "</td>";
        //       tb_siswa += `<td style='text-align: center;'>
        //                       <a href='nilai_mata_pelajaran?datasent=` + encodeURIComponent(encryptJSON({
        //                           idMapel: jdata.data[i].ID_MAPEL,
        //                           nis: jdata.data[i].NIS,
        //                           kelas: jdata.data[i].KELAS,
        //                           semester: jdata.data[i].SEMESTER,
        //                           tahunAjaran: jdata.data[i].TAHUN_PELAJARAN
        //                       }, '<?=$session_key?>')) + `'>
        //                           <img style='display: inline-block' src='../assets/images/view-blue.png' width='30px' title='lihat'>
        //                       </a>&nbsp;&nbsp;
        //                       <a href='ubah_nilai_mapel?datasent=` + encodeURIComponent(encryptJSON({
        //                           idMapel: jdata.data[i].ID_MAPEL,
        //                           nis: jdata.data[i].NIS,
        //                           kelas: jdata.data[i].KELAS,
        //                           semester: jdata.data[i].SEMESTER,
        //                           tahunAjaran: jdata.data[i].TAHUN_PELAJARAN
        //                       }, '<?=$session_key?>')) + `'>
        //                           <img style='display: inline-block' src='../assets/images/edit-orange.png' width='30px' title='ubah'>
        //                       </a>
        //                       <a id='btn-delete-siswa' href='#' onclick='deleteDataPenilaian("` + jdata.data[i].ID_MAPEL + `","` + jdata.data[i].NIS + `","` + jdata.data[i].KELAS + `","` + jdata.data[i].SEMESTER + `","` + jdata.data[i].TAHUN_PELAJARAN + `")'>
        //                           <img style='display: inline-block' src='../assets/images/delete-red.png' width='30px' title='hapus'>
        //                       </a>
        //                   </td>`;
        //       tb_siswa += "</tr>";
        //     }

        //     $("#tb-siswa").html(tb_siswa);
        //     $("#jml-page").html(jdata.jml_page);
        //     $("#jml-data").html(jdata.jml_data);
        //     $("#select-jml-per-page [value='"+jmlPerPage+"']").prop('selected', true);

        //     for(var i=1; i <= jdata.jml_page; i++){
        //       if(i == page) 
        //       {
        //         op_page += "<option value='" + i + "' selected >" + i + "</option>";
        //       } 
        //       else 
        //       {
        //         op_page += "<option value='" + i + "'>" + i + "</option>";
        //       }
        //     }

        //     $("#select-page").html(op_page);

          } else {
            $("#tb-siswa<?=$urut_siswa;?>").html("<tr><td colspan='7'><center>-- Data tidak ditemukan --</center></td></tr>");
          }
        }
      });
    }
</script>

</html>
