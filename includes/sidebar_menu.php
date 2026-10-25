<!doctype html>
<html lang="en" data-pc-preset="preset-1" data-pc-sidebar-caption="true" data-pc-direction="ltr" dir="ltr" data-pc-theme="light">
  <!-- [Head] start -->

  <head>
    <title>E-RAPOR</title>
    <!-- [Meta] -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="author" content="CodedThemes" />

    <!-- [Favicon] icon -->
    <link rel="icon" href="../assets/images/e-rapor-background.png" type="image/x-icon" />

     <!-- [Font] Family -->
     <link href="../assets/fonts/css2.css" rel="stylesheet" />
    <!-- [phosphor Icons] https://phosphoricons.com/ -->
    <link rel="stylesheet" href="../assets/fonts/phosphor/duotone/style.css" />
    <!-- [Tabler Icons] https://tablericons.com -->
    <link rel="stylesheet" href="../assets/fonts/tabler-icons.min.css" />
    <!-- [Feather Icons] https://feathericons.com -->
    <link rel="stylesheet" href="../assets/fonts/feather.css" />
    <!-- [Font Awesome Icons] https://fontawesome.com/icons -->
    <link rel="stylesheet" href="../assets/fonts/fontawesome.css" />
    <!-- [Material Icons] https://fonts.google.com/icons -->
    <link rel="stylesheet" href="../assets/fonts/material.css" />
    <!-- [Template CSS Files] -->
    <link rel="stylesheet" href="../assets/css/style.css" id="main-style-link" />

    <style>
      #preview-data .table-responsive {
        max-height: 500px;
        overflow-y: auto;
      }
      
      table {
        margin-left: 10px;
        width: 98% !important;
      }

      table.table td.breakword {
        white-space: normal !important;
        word-break: break-word !important;
        overflow-wrap: anywhere !important;
      }
      

      table tr td {
        border: 1px solid #9696965a !important;
      }

      table thead {
        background-color: #d5ebfe !important;
        color: #000000a4 !important;
      }

      #select-jml-per-page, #select-page{
        background-color: rgba(203, 232, 255, 0.47) !important;
      }
    </style>
  </head>
  <!-- [Head] end -->
  <!-- [Body] Start -->

  <body>
    <!-- [ Pre-loader ] start -->
<div class="loader-bg fixed inset-0 bg-white dark:bg-themedark-cardbg z-[1034]">
  <div class="loader-track h-[5px] w-full inline-block absolute overflow-hidden top-0">
    <div class="loader-fill w-[300px] h-[5px] bg-primary-500 absolute top-0 left-0 animate-[hitZak_0.6s_ease-in-out_infinite_alternate]"></div>
  </div>
</div>
<!-- [ Pre-loader ] End -->
 <!-- [ Sidebar Menu ] start -->
<nav class="pc-sidebar">
  <div class="navbar-wrapper">
    <div class="m-header flex items-center py-4 px-6 h-header-height">
      <a href="../dashboard/" class="b-brand flex items-center gap-3">
        <!-- ========   Change your logo from here   ============ -->
        <img src="../assets/images/e-rapor-white.png" width="60%" alt="logo" />
      </a>
    </div>
    <div class="navbar-content h-[calc(100vh_-_74px)] py-2.5">
      <ul class="pc-navbar">
        <li class="pc-item pc-caption">
          <label>Navigation</label>
        </li>
        <li class="pc-item">
        <li class="pc-item">
          <a href="../dashboard/" class="pc-link">
            <span class="pc-micon">
              <i data-feather="home"></i>
            </span>
            <span class="pc-mtext">Home</span>
          </a>
        </li>
        <!-- <li class="pc-item pc-hasmenu">
          <a href="../feature/daftar_nilai_mata_pelajaran" class="pc-link">
            <span class="pc-micon"> <i data-feather="activity"></i></span>
            <span class="pc-mtext">Penilaian Siswa</span>
          </a>
        </li> -->
        <li class="pc-item pc-hasmenu" id="kat-menu-penilaian-siswa">
          <a href="#!" class="pc-link"><span class="pc-micon"> <i data-feather="activity"></i> </span><span
              class="pc-mtext">Penilaian & Rapor</span><span class="pc-arrow"><i class="ti ti-chevron-right"></i></span></a>
          <ul class="pc-submenu">
            <li class="pc-item"><a class="pc-link" id="menu-nilai-mata-pelajaran" href="../feature/daftar_nilai_mata_pelajaran">Penilaian Mata Pelajaran</a></li>
            <li class="pc-item"><a class="pc-link" id="menu-nilai-ekstrakurikuler" href="../feature/daftar_nilai_ekstrakurikuler">Penilaian Ekstrakurikuler</a></li>
            <li class="pc-item"><a class="pc-link" id="menu-pkl" href="../feature/praktik_kerja_lapangan">Praktik Kerja Lapangan</a></li>
            <li class="pc-item"><a class="pc-link" id="menu-absensi" href="../feature/daftar_absensi">Absensi Siswa</a></li>
            <li class="pc-item"><a class="pc-link" id="menu-rapor" href="../feature/kelas_perwali">Monitoring & Rapor</a></li>
          </ul>
        </li>
        <!-- <li class="pc-item pc-hasmenu">
          <a href="../feature/daftar_mata_pelajaran" class="pc-link">
            <span class="pc-micon"> <i data-feather="book"></i></span>
            <span class="pc-mtext">Daftar Mata Pelajaran</span>
          </a>
        </li> -->
        <li class="pc-item pc-hasmenu" id="kat-menu-manajemen-pelajaran">
          <a href="#!" class="pc-link"><span class="pc-micon"> <i data-feather="book"></i> </span><span
              class="pc-mtext">Manajemen Akademik</span><span class="pc-arrow"><i class="ti ti-chevron-right"></i></span></a>
          <ul class="pc-submenu">
            <li class="pc-item"><a class="pc-link" id="menu-kategori-mapel" href="../feature/daftar_kategori_mapel">Kategori Mata Pelajaran</a></li>
            <li class="pc-item"><a class="pc-link" id="menu-mata-pelajaran" href="../feature/daftar_mata_pelajaran">Daftar Mata Pelajaran</a></li>
            <li class="pc-item"><a class="pc-link" id="menu-ekstrakurikuler" href="../feature/daftar_ekstrakurikuler">Daftar Ekstrakurikuler</a></li>
            <li class="pc-item"><a class="pc-link" id="menu-jadwal-pengisian-nilai" href="../feature/jadwal_pengisian_nilai">Jadwal Pengisian Nilai</a></li>
          </ul>
        </li>
        <!-- <li class="pc-item pc-hasmenu">
          <a href="../feature/daftar_siswa" class="pc-link">
            <span class="pc-micon"> <i data-feather="users"></i></span>
            <span class="pc-mtext">Daftar Siswa</span>
          </a>
        </li> -->
        <li class="pc-item pc-hasmenu" id="kat-menu-warga-sekolah">
          <a href="#!" class="pc-link"><span class="pc-micon"> <i data-feather="users"></i> </span><span
              class="pc-mtext">Warga Sekolah</span><span class="pc-arrow"><i class="ti ti-chevron-right"></i></span></a>
          <ul class="pc-submenu">
            <li class="pc-item"><a class="pc-link" id="menu-pengguna" href="../feature/daftar_pengguna">Manajemen Pengguna</a></li>
            <li class="pc-item"><a class="pc-link" id="menu-wali-kelas" href="../feature/manajemen_wali_kelas">Manajemen Wali Kelas</a></li>
            <li class="pc-item"><a class="pc-link" id="menu-guru-mapel" href="../feature/manajemen_guru_mapel">Manajemen Guru Mapel</a></li>
            <li class="pc-item"><a class="pc-link" id="menu-siswa" href="../feature/daftar_siswa">Manajemen Siswa</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>
<!-- [ Sidebar Menu ] end -->


<div id="loader-overlay" style="position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(255,255,255,0.7); z-index:9999; display:none; justify-content:center; align-items:center;">
    <img src="../assets/gif/loading.gif" alt="Loading..." style="width:80px; height:80px;">
</div>

<script src="../assets/js/jquery-1.11.3.min.js"></script>
<script>
  $(document).ready(function(){
    window.statusAdmin      = '<?=$session_st_admin?>';
    window.statusGuru       = '<?=$session_st_guru?>';
    window.statusKesiswaan  = '<?=$session_st_kesiswaan?>';
    window.statusKurikulum  = '<?=$session_st_kurikulum?>';

    $("#kat-menu-penilaian-siswa").hide();
    $("#menu-nilai-mata-pelajaran").hide();
    $("#menu-nilai-ekstrakurikuler").hide();
    $("#menu-pkl").hide();
    $("#menu-absensi").hide();
    $("#menu-rapor").hide();

    $("#kat-menu-manajemen-pelajaran").hide();
    $("#menu-kategori-mapel").hide();
    $("#menu-mata-pelajaran").hide();
    $("#menu-ekstrakurikuler").hide();
    $("#menu-jadwal-pengisian-nilai").hide();

    $("#kat-menu-warga-sekolah").hide();
    $("#menu-pengguna").hide();
    $("#menu-wali-kelas").hide();
    $("#menu-guru-mapel").hide();
    $("#menu-siswa").hide();

    if(window.statusAdmin == 'Y'){
      $("#kat-menu-penilaian-siswa").show();
      $("#kat-menu-manajemen-pelajaran").show();
      $("#kat-menu-warga-sekolah").show();

      $("#menu-nilai-mata-pelajaran").show();
      $("#menu-nilai-ekstrakurikuler").show();
      $("#menu-pkl").show();
      $("#menu-absensi").show();
      $("#menu-rapor").show();
      $("#menu-kategori-mapel").show();
      $("#menu-mata-pelajaran").show();
      $("#menu-ekstrakurikuler").show();
      $("#menu-jadwal-pengisian-nilai").show();
      $("#menu-pengguna").show();
      $("#menu-wali-kelas").show();
      $("#menu-guru-mapel").show();
      $("#menu-siswa").show();
    } else {
      if(window.statusGuru == 'Y'){
        $("#kat-menu-penilaian-siswa").show();

        $("#menu-nilai-mata-pelajaran").show();
        $("#menu-rapor").show();
      }  
      
      if(window.statusKesiswaan == 'Y'){
        $("#kat-menu-penilaian-siswa").show();

        $("#menu-nilai-ekstrakurikuler").show();
        $("#menu-absensi").show();
      } 
      
      if(window.statusKurikulum == 'Y'){
        $("#kat-menu-penilaian-siswa").show();
        $("#kat-menu-manajemen-pelajaran").show();
        $("#kat-menu-warga-sekolah").show();

        $("#menu-pkl").show();
        $("#menu-kategori-mapel").show();
        $("#menu-mata-pelajaran").show();
        $("#menu-ekstrakurikuler").show();
        $("#menu-jadwal-pengisian-nilai").show();
        $("#menu-pengguna").show();
        $("#menu-wali-kelas").show();
        $("#menu-guru-mapel").show();
        $("#menu-siswa").show();
      }

    }

  });
  // Cara 1: Blokir semua klik kanan
  document.addEventListener("contextmenu", function(e) {
    // e.preventDefault();
  });

  // // Cara 2: Opsional, blokir shortcut tertentu (misalnya Ctrl+U, Ctrl+S, F12)
  document.addEventListener("keydown", function(e) {
    if (e.ctrlKey && ["u", "s"].includes(e.key.toLowerCase())) {
      e.preventDefault();
      // alert("Shortcut ini dinonaktifkan!");
    }
    if (e.key === "F12") {
      e.preventDefault();
    }
  });
   
</script>
<script src="../includes/include_script.js"></script>

