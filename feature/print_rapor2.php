
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
    <?php
        $bulan = [
                    1 => "Januari",
                    "Februari",
                    "Maret",
                    "April",
                    "Mei",
                    "Juni",
                    "Juli",
                    "Agustus",
                    "September",
                    "Oktober",
                    "November",
                    "Desember"
                ];

        $v_filter_siswa     = " AND UPPER(NIS) = UPPER('$v_nis') AND UPPER(KELAS) = UPPER('$v_kelas') AND UPPER(TAHUN_PELAJARAN) = UPPER('$v_tahun_pelajaran') AND UPPER(SEMESTER) = UPPER('$v_semester') ";
        $query_detil_siswa  = "SELECT B.*, 
                             (SELECT A1.NIP FROM tb_wali_kelas A1 WHERE A1.TAHUN_PELAJARAN = UPPER('$v_tahun_pelajaran') AND UPPER(A1.KELAS) = UPPER('$v_kelas')) NIP_WALI_KELAS,
                             (SELECT (SELECT A1.NAMA FROM tb_user A1 WHERE A1.NIP = A.NIP) FROM tb_wali_kelas A WHERE A.KELAS = B.KELAS AND A.TAHUN_PELAJARAN = B.TAHUN_PELAJARAN) NAMA_WALI_KELAS,
                              (
                                SELECT COUNT(1) CNT 
                                FROM (
                                SELECT NILAI.ID_MAPEL, SUM(NILAI.NILAI_AKHIR) JUMLAH_NILAI, COUNT(1) JUMLAH_MAPEL_SEMESTER
                                FROM tb_penilaian NILAI
                                WHERE UPPER(NILAI.NIS) = UPPER('$v_nis')
                                        AND UPPER(NILAI.KELAS) = UPPER('$v_kelas')
                                        AND UPPER(NILAI.TAHUN_PELAJARAN) = UPPER('$v_tahun_pelajaran')
                                        AND EXISTS (
                                        SELECT 1 FROM tb_mapel MPL
                                        WHERE MPL.ID_MAPEL = NILAI.ID_MAPEL AND MPL.ID_KATEGORI_MAPEL = (SELECT X.ID_KATEGORI_MAPEL FROM tb_kategori_mapel X WHERE X.TAHUN_PELAJARAN = '$v_tahun_pelajaran' AND UPPER(X.NAMA_KATEGORI_MAPEL) NOT LIKE '%KEJURUAN%')
                                        ) 
                                        GROUP BY NILAI.ID_MAPEL
                                ) Z
                                WHERE (Z.JUMLAH_NILAI/Z.JUMLAH_MAPEL_SEMESTER) < 80
                              ) AS JML_NILAI_UMUM_TIDAK_LULUS,
                              (
                                SELECT COUNT(1) CNT 
                                FROM (
                                SELECT NILAI.ID_MAPEL, SUM(NILAI.NILAI_AKHIR) JUMLAH_NILAI, COUNT(1) JUMLAH_MAPEL_SEMESTER
                                FROM tb_penilaian NILAI
                                WHERE UPPER(NILAI.NIS) = UPPER('$v_nis')
                                        AND UPPER(NILAI.KELAS) = UPPER('$v_kelas')
                                        AND UPPER(NILAI.TAHUN_PELAJARAN) = UPPER('$v_tahun_pelajaran')
                                        AND EXISTS (
                                        SELECT 1 FROM tb_mapel MPL
                                        WHERE MPL.ID_MAPEL = NILAI.ID_MAPEL AND MPL.ID_KATEGORI_MAPEL = (SELECT X.ID_KATEGORI_MAPEL FROM tb_kategori_mapel X WHERE X.TAHUN_PELAJARAN = '$v_tahun_pelajaran' AND UPPER(X.NAMA_KATEGORI_MAPEL)  LIKE '%KEJURUAN%')
                                        ) 
                                        GROUP BY NILAI.ID_MAPEL
                                ) Z
                                WHERE (Z.JUMLAH_NILAI/Z.JUMLAH_MAPEL_SEMESTER) < 80
                              ) AS JML_NILAI_KEJURUAN_TIDAK_LULUS,
                              (
                                SELECT COUNT(1) CNT
                                FROM (
                                    SELECT ((X.KEHADIRAN)/(X.KEHADIRAN+X.IZIN+X.TANPA_KETERANGAN+X.SAKIT)*100) RATA2_KEHADIRAN 
                                    FROM tb_absensi_siswa X
                                    WHERE 1=1
                                    AND X.NIS = UPPER('$v_nis')
                                    AND X.TAHUN_PELAJARAN = UPPER('$v_tahun_pelajaran')
                                    AND X.KELAS = UPPER('$v_kelas')
                                ) Z 
                                WHERE 1=1
                                AND Z.RATA2_KEHADIRAN < 80
                              ) AS JML_ABSESNSI_TIDAK_LULUS,
                              (
                                SELECT COUNT(1) CNT
                                FROM (
                                    SELECT LEFT(X.NILAI_AKHIR, 1) NILAI_AKHIR FROM tb_penilaian_exr X
                                    WHERE 1=1
                                    AND X.NIS = UPPER('$v_nis')
                                    AND X.TAHUN_PELAJARAN = UPPER('$v_tahun_pelajaran')
                                    AND UPPER(X.KELAS) = UPPER('$v_kelas')
                                ) Z
                                WHERE UPPER(Z.NILAI_AKHIR) NOT IN ('A','B')
                              ) AS JML_ESKUL_TIDAK_LULUS
                        FROM tb_siswa B WHERE 1=1 $v_filter"; 

        $db_ext_siswa           = mysqli_query($db_connect,$query_detil_siswa);
        $data_siswa             = mysqli_fetch_array($db_ext_siswa);
        $status_naik_kelas      = 'T';
        $css_tidak_naik_kelas   = 'style="text-decoration-line: line-through;"';
        $kelas_saat_ini         = '';
        $kelas_selanjutnya      = '';

        if (strpos($data_siswa['KELAS'], "X.") === 0) {
            $fase = "E";
        } else {
            $fase = "F";
        }

        if(str_starts_with($data_siswa['KELAS'], 'XIII'))
        {
            if($data_siswa['JML_NILAI_UMUM_TIDAK_LULUS'] > 2 || $data_siswa['JML_NILAI_KEJURUAN_TIDAK_LULUS'] > 0 || $data_siswa['JML_ABSESNSI_TIDAK_LULUS'] > 0 || $data_siswa['STATUS_KOMPETENSI_LSP'] == 'T')
            {
                $status_naik_kelas = 'T';
                $hasil_lulus       = "<label style='text-decoration-line: line-through;'>LULUS</label> / TIDAK LULUS";
            } 
            else 
            {
                $status_naik_kelas = 'Y';
                $hasil_lulus       = "LULUS / <label style='text-decoration-line: line-through;'>TIDAK LULUS</label>";
            }
        }
        else
        {
            if($data_siswa['JML_NILAI_UMUM_TIDAK_LULUS'] > 2 || $data_siswa['JML_NILAI_KEJURUAN_TIDAK_LULUS'] > 0 || $data_siswa['JML_ABSESNSI_TIDAK_LULUS'] > 0 || $data_siswa['JML_ESKUL_TIDAK_LULUS'] > 0)
            {
               $status_naik_kelas = 'T';
            } 
            else 
            {
                $status_naik_kelas = 'Y';
            }
        }

        if (strpos($data_siswa['KELAS'], "X.") === 0) {
            if($status_naik_kelas == 'Y') {
                $kelas_saat_ini     = "<span id='kelas-saat-ini' ".$css_tidak_naik_kelas.">X (SEPULUH)</span>";
                $kelas_selanjutnya  = "<span id='kelas-selanjutnya'>XI (SEBELAS)</span>";
            } else {
                $kelas_saat_ini     = "<span id='kelas-saat-ini'>X (SEPULUH)</span>";
                $kelas_selanjutnya  = "<span id='kelas-selanjutnya' ".$css_tidak_naik_kelas.">XI (SEBELAS)</span>";
            }
        } else if (strpos($data_siswa['KELAS'], "XI.") === 0) {
            if($status_naik_kelas == 'Y') {
                $kelas_saat_ini     = "<span id='kelas-saat-ini' ".$css_tidak_naik_kelas.">XI (SEBELAS)</span>";
                $kelas_selanjutnya  = "<span id='kelas-selanjutnya'>XII (DUA BELAS)</span>";
            } else {
                $kelas_saat_ini     = "<span id='kelas-saat-ini'>XI (SEBELAS)</span>";
                $kelas_selanjutnya  = "<span id='kelas-selanjutnya' ".$css_tidak_naik_kelas.">XII (DUA BELAS)</span>";
            }
        } else if (strpos($data_siswa['KELAS'], "XII.") === 0) {
            if($status_naik_kelas == 'Y') {
                $kelas_saat_ini     = "<span id='kelas-saat-ini' ".$css_tidak_naik_kelas.">XII (DUA BELAS)</span>";
                $kelas_selanjutnya  = "<span id='kelas-selanjutnya'>XIII (TIGA BELAS)</span>";
            } else {
                $kelas_saat_ini     = "<span id='kelas-saat-ini'>XII (DUA BELAS)</span>";
                $kelas_selanjutnya  = "<span id='kelas-selanjutnya' ".$css_tidak_naik_kelas.">XIII (TIGA BELAS)</span>";
            }
        } 
    ?>
    <table class="identitas">
        <tr>
            <td width="20%">Nama Peserta Didik</td>
            <td width="2%">:</td>
            <td><strong><span id="nama-siswa"><?=$data['NAMA_SISWA'];?></span></strong></td>
            <td width="10%">Kelas</td>
            <td width="2%">:</td>
            <td width="20%"><strong><span id="kelas"><?=$data['KELAS'];?></span></strong></td>
        </tr>
        <tr>
            <td>NIS / NISN</td>
            <td>:</td>
            <td><strong><span id="nis"><?=$data['NIS'];?></span></strong></td>
            <td>Fase</td>
            <td>:</td>
            <td><strong><span id="fase"><?=$fase;?></span></strong></td>
        </tr>
        <tr>
            <td>Nama Sekolah</td>
            <td>:</td>
            <td><strong>SMK SMAK Padang</strong></td>
            <td>Semester</td>
            <td>:</td>
            <td><strong><span id="semester"><?=$data['SEMESTER'];?></span></strong></td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td>:</td>
            <td><strong><span id="alamat"><?=$data['ALAMAT'];?></span></strong></td>
            <td>Tahun Ajaran</td>
            <td>:</td>
            <td><strong><span id="tahun-pelajaran"><?=$data['TAHUN_PELAJARAN'];?></span></strong></td>
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
            <?php
                $query_list_ktg         = "SELECT DISTINCT ID_KATEGORI_MAPEL, ID_SUB_KATEGORI_MAPEL, 
                                                                (SELECT Z.NAMA_KATEGORI_MAPEL FROM tb_kategori_mapel Z WHERE Z.ID_KATEGORI_MAPEL = X.ID_KATEGORI_MAPEL) NAMA_KATEGORI_MAPEL,
                                                                (SELECT Z.NAMA_SUB_KATEGORI_MAPEL FROM tb_sub_kategori_mapel Z WHERE Z.ID_SUB_KATEGORI_MAPEL = X.ID_SUB_KATEGORI_MAPEL) NAMA_SUB_KATEGORI_MAPEL
                                                FROM (
                                                    SELECT B.*, 
                                                        (SELECT A.ID_KATEGORI_MAPEL FROM tb_mapel A WHERE A.ID_MAPEL = B.ID_MAPEL AND A.SEMESTER = B.SEMESTER) ID_KATEGORI_MAPEL,
                                                        (SELECT A.ID_SUB_KATEGORI_MAPEL FROM tb_mapel A WHERE A.ID_MAPEL = B.ID_MAPEL AND A.SEMESTER = B.SEMESTER) ID_SUB_KATEGORI_MAPEL
                                                    FROM tb_penilaian B WHERE 1=1 $v_filter_siswa) X ORDER BY X.ID_KATEGORI_MAPEL ASC, X.ID_SUB_KATEGORI_MAPEL ASC"; 
                                            // var_dump($query_list_ktg);die;

                $db_list_ktg            = mysqli_query($db_connect,$query_list_ktg);
                $nomor_urut             = 0;
                $reset_no_by_ktg        = '';
                $reset_no_by_sub_ktg    = '';
                if(mysqli_num_rows($db_list_ktg) > 0){
                    while ($data_list_ktg = mysqli_fetch_array($db_list_ktg)) 
                    {

                        if($data_list_ktg['ID_KATEGORI_MAPEL'] != "" && $data_list_ktg['ID_SUB_KATEGORI_MAPEL'] == null) {
                            

                            echo "<tr>
                                    <td class='urutan' colspan='4'><strong>. ".$data_list_ktg['NAMA_KATEGORI_MAPEL']."</strong></td>
                                  </tr>";

                            $v_kategori         = $data_list_ktg['ID_KATEGORI_MAPEL'];
                            $v_sub_kategori     = $data_list_ktg['ID_SUB_KATEGORI_MAPEL'];

                            $q_ketegori_non_sub = "SELECT 
                                                            (SELECT Z.NAMA_KATEGORI_MAPEL FROM tb_kategori_mapel Z WHERE Z.ID_KATEGORI_MAPEL = X.ID_KATEGORI_MAPEL) NAMA_KATEGORI_MAPEL,
                                                            (SELECT Z.NAMA_SUB_KATEGORI_MAPEL FROM tb_sub_kategori_mapel Z WHERE Z.ID_SUB_KATEGORI_MAPEL = X.ID_SUB_KATEGORI_MAPEL) NAMA_SUB_KATEGORI_MAPEL,
                                                            X.*
                                                    FROM (
                                                        SELECT B.*, 
                                                            (SELECT A.NAMA_MAPEL FROM tb_mapel A WHERE A.ID_MAPEL = B.ID_MAPEL AND A.SEMESTER = B.SEMESTER) NAMA_MAPEL,
                                                            (SELECT A.ID_KATEGORI_MAPEL FROM tb_mapel A WHERE A.ID_MAPEL = B.ID_MAPEL AND A.SEMESTER = B.SEMESTER) ID_KATEGORI_MAPEL,
                                                            (SELECT A.ID_SUB_KATEGORI_MAPEL FROM tb_mapel A WHERE A.ID_MAPEL = B.ID_MAPEL AND A.SEMESTER = B.SEMESTER) ID_SUB_KATEGORI_MAPEL
                                                        FROM tb_penilaian B WHERE 1=1 $v_filter_siswa) X WHERE X.ID_KATEGORI_MAPEL = '$v_kategori' AND X.ID_SUB_KATEGORI_MAPEL IS NULL ORDER BY X.ID_KATEGORI_MAPEL ASC";
                                                        // var_dump($q_ketegori_non_sub);die;
                            $db_ext_ketegori_non_sub             = mysqli_query($db_connect,$q_ketegori_non_sub);  
                                 
                            while ($data_ketegori_non_sub = mysqli_fetch_array($db_ext_ketegori_non_sub)) {
                                $nomor_urut = $nomor_urut + 1;

                                if($reset_no_by_ktg != $data_ketegori_non_sub['ID_KATEGORI_MAPEL']) {
                                    $nomor_urut = 0+1;
                                    $reset_no_by_ktg = $data_ketegori_non_sub['ID_KATEGORI_MAPEL'];
                                }
                                
                                if($data_list_ktg['ID_KATEGORI_MAPEL'] == $data_ketegori_non_sub['ID_KATEGORI_MAPEL']) {
                            
                                    echo "<tr>
                                            <td rowspan='2'>".$nomor_urut."</td>
                                            <td rowspan='2'>".$data_ketegori_non_sub['NAMA_MAPEL']."</td>
                                            <td rowspan='2'>".$data_ketegori_non_sub['NILAI_AKHIR']."</td>
                                            <td>".$data_ketegori_non_sub['CP_PLUS']."</td>
                                         </tr>
                                         <tr>
                                            <td>".$data_ketegori_non_sub['CP_MIN']."</td>
                                         </tr>";
                                }
                            }
                        }

                        if($data_list_ktg['ID_KATEGORI_MAPEL'] != "" && $data_list_ktg['ID_SUB_KATEGORI_MAPEL'] != "") {

                            $v_kategori         = $data_list_ktg['ID_KATEGORI_MAPEL'];
                            $v_sub_kategori     = $data_list_ktg['ID_SUB_KATEGORI_MAPEL'];

                            $nomor_urut = $nomor_urut + 1;
                            echo "<tr>
                                    <td>".$nomor_urut."</td>
                                    <td colspan='3'>".$data_list_ktg['NAMA_SUB_KATEGORI_MAPEL']."</td>
                                 </tr>";

                            
                            $q_ketegori_sub = "SELECT 
                                                        (SELECT Z.NAMA_KATEGORI_MAPEL FROM tb_kategori_mapel Z WHERE Z.ID_KATEGORI_MAPEL = X.ID_KATEGORI_MAPEL) NAMA_KATEGORI_MAPEL,
                                                        (SELECT Z.NAMA_SUB_KATEGORI_MAPEL FROM tb_sub_kategori_mapel Z WHERE Z.ID_SUB_KATEGORI_MAPEL = X.ID_SUB_KATEGORI_MAPEL) NAMA_SUB_KATEGORI_MAPEL,
                                                        X.*
                                                FROM (
                                                    SELECT B.*, 
                                                        (SELECT A.NAMA_MAPEL FROM tb_mapel A WHERE A.ID_MAPEL = B.ID_MAPEL AND A.SEMESTER = B.SEMESTER) NAMA_MAPEL,
                                                        (SELECT A.ID_KATEGORI_MAPEL FROM tb_mapel A WHERE A.ID_MAPEL = B.ID_MAPEL AND A.SEMESTER = B.SEMESTER) ID_KATEGORI_MAPEL,
                                                        (SELECT A.ID_SUB_KATEGORI_MAPEL FROM tb_mapel A WHERE A.ID_MAPEL = B.ID_MAPEL AND A.SEMESTER = B.SEMESTER) ID_SUB_KATEGORI_MAPEL
                                                    FROM tb_penilaian B WHERE 1=1 $v_filter_siswa) X WHERE X.ID_KATEGORI_MAPEL = '$v_kategori' AND X.ID_SUB_KATEGORI_MAPEL = '$v_sub_kategori' ORDER BY X.ID_KATEGORI_MAPEL ASC, X.ID_SUB_KATEGORI_MAPEL ASC";
                                                    // var_dump($q_ketegori_sub); die;
                            $db_ext_ketegori_sub             = mysqli_query($db_connect,$q_ketegori_sub); 
                            $urut_abjad = 0;    
                            while ($data_ketegori_sub = mysqli_fetch_array($db_ext_ketegori_sub)) {
                                $urut_abjad = $urut_abjad + 1;
                                if($reset_no_by_sub_ktg != $data_ketegori_sub['ID_SUB_KATEGORI_MAPEL']) {
                                    $nomor_urut = 0+1;
                                    $reset_no_by_sub_ktg = $data_ketegori_sub['ID_SUB_KATEGORI_MAPEL'];
                                }

                                echo "
                                        <tr>
                                            <td rowspan='2'></td>
                                            <td rowspan='2'><ol type='a' start='".$urut_abjad."' style='margin-left:-30px'><li>".$data_ketegori_sub['NAMA_MAPEL']."</li></ol></td>
                                            <td rowspan='2'>".$data_ketegori_sub['NILAI_AKHIR']."</td>
                                            <td>".$data_ketegori_sub['CP_PLUS']."</td>
                                        </tr>
                                        <tr>
                                            <td>".$data_ketegori_sub['CP_MIN']."</td>
                                        </tr>
                                        ";

                            }
                            
                        }
                            
                    }
                }
            ?>
        </tbody>
        <!-- Tambahkan baris lain sesuai kebutuhan -->
    </table>

    <?php
        if (!str_starts_with($v_kelas, "XIII.")) 
        {
    ?>
    <table class="kokurikuler">
        <thead>
            <tr>
                <th>Kokurikuler</th>
            </tr>
        </thead>
        <tbody id="tb_kokurikuler">
            <tr>
                <td style='text-align:center;'><br><?=$data['KOKURIKULER'];?><br><br></td>
            </tr>
        </tbody>
    </table>
    <?php 
    }
    else
    {
    ?>
    
    <label><strong>C. Praktik Kerja Lapangan</strong></label>
    <table class="pkl">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Mitra DU/DI</th>
                <th>Lokasi</th>
            </tr>
        </thead>
        <tbody id="tb_pkl">
            <?php

                $query_exr       = "SELECT 
                                            B.* FROM tb_pkl B WHERE 1=1 $v_filter_siswa"; 

                $db_exr             = mysqli_query($db_connect,$query_exr);

                if(mysqli_num_rows($db_exr) > 0){
                    $v_nomor = 1;
                    while ($data_exr = mysqli_fetch_array($db_exr)) 
                    {
                        echo "<tr>
                                <td>".$v_nomor."</td>
                                <td>".$data_exr['MITRA_DU_DI']."</td>
                                <td>".$data_exr['LOKASI']."</td>
                            </tr>";
                        
                        $v_nomor++;
                    }
                } 
            ?>
        </tbody>
    </table>

    <?php
    }

        $query_exr       = "SELECT ROW_NUMBER() OVER (ORDER BY B.ID_EKSTRAKURIKULER DESC, B.ID_EKSTRAKURIKULER DESC) AS NOMOR, 
                                    B.*, (SELECT A.NAMA_EKSTRAKURIKULER FROM tb_ekstrakurikuler A WHERE A.ID_EKSTRAKURIKULER = B.ID_EKSTRAKURIKULER AND A.SEMESTER = B.SEMESTER) NAMA_EKSTRAKURIKULER FROM tb_penilaian_exr B WHERE 1=1 $v_filter_siswa"; 

        $db_exr             = mysqli_query($db_connect,$query_exr);

        if(mysqli_num_rows($db_exr) > 0){
    ?>
    <table class="ekstrakurikuler">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Kegiatan Ekstrakurikuler</th>
                <th width="60%">Keterangan</th>
            </tr>
        </thead>
        <tbody id="tb_ekstrakurikuler">
            <?php
                while ($data_exr = mysqli_fetch_array($db_exr)) 
                {
                    echo "<tr>
                            <td>".$data_exr['NOMOR']."</td>
                            <td>".$data_exr['NAMA_EKSTRAKURIKULER']."</td>
                            <td>".$data_exr['NILAI_AKHIR']."</td>
                        </tr>";
                }
            ?>
        </tbody>
    </table>\
    <?php } ?>

    <div style="display: flex; gap: 12px; align-items: flex-start;">
        <table class="absensi" style="flex: 1;">
            <thead>
                <tr>
                    <th colspan="3">KETIDAKHADIRAN</th>
            </thead>
            <tbody id="tb_absensi_siswa">
                <?php
                    $query_abs_sis  = "SELECT B.* FROM tb_absensi_siswa B WHERE 1=1 $v_filter_siswa"; 

                    $db_abs_sis     = mysqli_query($db_connect,$query_abs_sis);
                    $data_abs_sis   = mysqli_fetch_array($db_abs_sis);
                ?>
                <tr>
                    <td width='50%'>Sakit</td>
                    <td width='1%'>:</td>
                    <td><?=isset($data_abs_sis['SAKIT']) ? $data_abs_sis['SAKIT'] : '-';?> hari</td>
                </tr>
                <tr>
                    <td>Izin</td>
                    <td>:</td>
                    <td><?=isset($data_abs_sis['IZIN']) ? $data_abs_sis['IZIN'] : '-';?> hari</td>
                </tr>
                <tr>
                    <td>Tanpa Keterangan</td></td>
                    <td>:</td></td>
                    <td><?=isset($data_abs_sis['TANPA_KETERANGAN']) ? $data_abs_sis['TANPA_KETERANGAN'] : '-';?> hari</td>
                </tr>
            </tbody>
        </table>
        <table class="catatan_wali_kelas" style="flex: 1;">
            <thead>
                <tr>
                    <th>Catatan Wali Kelas</th>
                </tr>
            </thead>
            <tbody id="tb_catatan_wali_kelas">
                <tr>
                    <td style='text-align: center; height: 63px;' ><?=$data['CATATAN_WALI_KELAS'];?></td>
                </tr>
            </tbody>
        </table>
    </div>

    <?php
        if($data_siswa['SEMESTER'] == "Genap" && !str_starts_with($data['KELAS'], 'XIII')) {
    ?>
    <table class="keputusan_naik_kelas">
        <tr>
            <td style="text-align:center;"><br><strong>Keterangan Naik Kelas : <?php if($status_naik_kelas == 'Y'){echo 'Naik ke Kelas '.$kelas_selanjutnya;} else {echo 'Tinggal di Kelas '.$kelas_saat_ini;} ?></strong><br><br></td>
        </tr>
    </table>
    <?php
        }
    ?>

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

    <?php
        if($data_siswa['SEMESTER'] == "Genap" && str_starts_with($data['KELAS'], 'XIII')) {
    ?>
    <table class="footnote">
        <tr>
            <td style="width:20%;"><strong>Diberikan di</strong></td>
            <td colspan="2"><strong>: <span id="tempat-diberikan"><?=$v_tempat;?></span></strong></td>
        </tr>
        <tr>
            <td><strong>Tanggal</strong></td>
            <?php
                $time = strtotime($v_tanggal);
                $hasil = date("j", $time) . " " . $bulan[date("n", $time)] . " " . date("Y", $time);

            ?>
            <td colspan="2"><strong>: <span id="tgl-diberikan"><?=$hasil;?></span></strong></td>
        </tr>
        <tr>
            <td><strong>Keputusan</strong></td>
            <td colspan="2"><strong>:</strong></td>
        </tr>
        <tr>
            <td colspan="3"><strong>Berdasarkan rapat kelulusan yang dilaksanakan dewan guru dan pertimbangan nilai semester 1 sampai 8, peserta didik dinyatakan :</strong> <?=$hasil_lulus;?></td>
        </tr>
    </table>

    <?php
        }
    ?>

    <br><br>
    <div class="ttd-wrapper">
        <table class="ttd">
            <tr>
                <td>Mengetahui</td>
                <td></td>
                <td><span id="tgl-cetak"><?php echo $v_tempat.", ".date("j") . " " . $bulan[date("n")] . " " . date("Y"); ?></span></td>
            </tr>
            <tr>
                <td>Orang Tua/Wali</td>
                <td></td>
                <td>Wali Kelas</td>
            </tr>
            <tr height="80">
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>.........................................</td>
                <td></td>
                <td><u><span id="nama-wali-kelas"><?=$data_siswa['NAMA_WALI_KELAS'];?></span></u></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td><span id="nip-wali-kelas"><?=$data_siswa['NIP_WALI_KELAS'];?></span></td>
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
            <?php
                $query_kepsek   = "SELECT B.* FROM tb_user B WHERE 1=1 AND B.STATUS_KEPALA_SEKOLAH = 'Y'"; 
                $db_kepsek      = mysqli_query($db_connect,$query_kepsek);
                $data_kepsek    = mysqli_fetch_array($db_kepsek);
            ?>
            <tr>
                <td></td>
                <td><u><span id="nama-kepsek"><?=$data_kepsek['NAMA'];?></span></u></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td><span id="nip-kepsek"><?=$data_kepsek['NIP'];?></span></td>
                <td></td>
            </tr>
        </table>
    </div>
</div>
