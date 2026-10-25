<?php
  include_once '../includes/session.php';
  include_once '../includes/sidebar_menu.php';
  include_once '../includes/header.php';
  $datasent      = isset($_GET['datasent']) ? $_GET['datasent'] : '-';
  $semester_sent = isset($_GET['semester']) ? $_GET['semester'] : '-';
  // $page          = isset($_GET['page']) ? $_GET['page'] : 1;
  // $jml_per_page  = isset($_GET['jml_per_page']) ? $_GET['jml_per_page'] : 10;
?>
<style>
/* Latar belakang popup */
.modal {
  display: none; /* awalnya tersembunyi */
  position: fixed;
  z-index: 2147483647;
  left: 0; top: 0;
  width: 100%; height: 100%;
  background-color: rgba(0,0,0,0.5);
  align-items: center;
  justify-content: center;
  padding: 20px;
  box-sizing: border-box;
}

/* Konten popup */
.modal-content {
  position: relative;
  z-index: 2147483647;
  background: #fff;
  margin: 0;
  padding: 20px;
  border-radius: 10px;
  width: min(1000px, 100%);
  /* min-height: min(800px, calc(100vh - 40px)); */
  max-height: calc(100vh - 40px);
  overflow-y: auto;
  box-shadow: 0 5px 10px rgba(0,0,0,0.3);
}

/* Tombol close (X) */
.close {
  float: right;
  font-size: 18px;
  font-weight: bold;
  cursor: pointer;
}

.metric-clickable {
  cursor: pointer;
  color: #206bc4;
  text-decoration: underline;
}

.back-btn-inline {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: #fff;
  color: #4673b2;
  border: 1px solid #649add;
  border-radius: 8px;
  padding: 8px 12px;
  font-weight: 600;
  margin-right: 12px;
  cursor: pointer;
  box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

.back-btn-inline:hover {
  background: #f3f4f6;
}
</style>

    <!-- [ Main Content ] start -->
    <div class="pc-container">
      <div class="pc-content">

        <!-- [ Main Content ] start -->
        <div class="grid grid-cols-1 gap-x-6">
          <div class="col-span-1 xl:col-span-8 md:col-span-6">
            <div class="card table-card">
              <div class="card-header d-flex justify-content-between align-items-center">
                <div style="display: flex; align-items: center; width: 100%; position: relative;">
                  <a type="button" class="back-btn-inline" type="button" href="kelas_perwali" style="position: absolute; left: 0;">
                    <i data-feather="arrow-left"></i>
                    <span>Kembali</span>
</a>
                  <div style="flex: 1; text-align: center;">
                    <h4 style="margin: 0;">Daftar Siswa <span id="kelas-title"></span></h4>
                  </div>
                </div>
                <br>
                <br>

                <div class="page-header" style="margin-top: -20px;">
                  <div class="me-auto pc-mob-drp">
                    <ul class="inline-flex *:min-h-header-height *:inline-flex *:items-center">
                      <li class="dropdown pc-h-item">
                        <a class="pc-head-link" href="#" id="btn-show-cetak-massal" onclick="openModal('Massal')" role="button" aria-haspopup="false" aria-expanded="false">
                          <i data-feather="printer" style='display: inline-block'></i> Cetak Rapor Massal
                        </a>
                      </li>
                    </ul>
                  </div>
                </div>

                <div id="search-area" style="flex: 1; width: auto; min-width: 0;">
                  <div style="display: flex; align-items: center; gap: 10px; width: 100%;">
                    <span>Semester : </span>
                    <div style="width: 150px;">
                      <select id="search-semester" class="form-control" onchange="loadDataSiswa()">
                        <option value="Gasal">Gasal</option>
                        <option value="Genap">Genap</option>
                      </select>
                      <input type="hidden" id="tahun-pelajaran" class="form-control" value="">
                      <input type="hidden" id="kelas" class="form-control" value="">
                    </div>
                    <button id='btn-save' type='button' class='btn btn-primary' style='margin-left: auto;'>Simpan Kokurikuler dan Catatan</button>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-hover">
                    <thead>
                      <tr style="text-align: center; font-weight: bold;">
                        <td rowspan="2">No</td>
                        <td rowspan="2">NIS</td>
                        <td rowspan="2">Nama Siswa</td>
                        <td colspan="3" style="text-align: center; font-weight: bold;">Total Rekap</td>
                        <td rowspan="2" id="cl-kokurikuler">Kokurikuler</td>
                        <td rowspan="2">Catatan Wali Kelas</td>
                        <td rowspan="2" id="cl-lks" style="display:none;">Status Kompeten LKS</td>
                        <td rowspan="2">Action</td>
                      </tr>
                      <tr style="text-align: center; font-weight: bold;">
                        <td>Mapel</td>
                        <td>Eskul</td>
                        <td>Tidak Hadir</td>
                      </tr>
                    </thead>
                    <tbody id="tb-siswa"></tbody>
                  </table>

                  <br>
                    
                  <!-- <div id="page-area" style="width: 100%; padding-left: 15px; padding-right: 15px; margin-top: 10px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap;">
                      <div class="flex flex-wrap gap-2" style="align-items: center;">
                        <div style="width: 80px;">
                          <select id="select-jml-per-page" class="form-control">
                            <option value="10">10</option>
                            <option value="20">20</option>
                            <option value="30">30</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                          </select>
                        </div>
                        <span>baris data per halaman </span>
                      </div>

                      <div class="flex flex-wrap gap-2" style="align-items: center; margin-left: auto;">
                        <span>Halaman </span>
                        <div style="width: 80px;">
                          <select id="select-page" class="form-control"></select>
                        </div>
                        <div style="display: flex; gap: 10px;">
                          <nav>dari <span id="jml-page"></span> halaman dan total <span id="jml-data"></span> data</nav>
                        </div>
                      </div>
                    </div>
                  </div> -->
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- [ Main Content ] end -->
      </div>
    </div>
    <!-- [ Main Content ] end -->

    <!-- Tombol buka modal -->

    <!-- Modal -->
    <div id="myModal" class="modal">
      <div class="modal-content" style="width: 300px !important; height: 300px !important;">
        <span class="close" onclick="closeModal()">&times;</span>
        <h5>Data Penyerahan</h5><br>
        <div class="grid grid-cols-12 gap-6 mb-6">
          <div class="col-span-12 sm:col-span-12">
            <input type="text" id="tempat-diberikan" class="form-control" placeholder="Diberikan di . . .">
            <input type="hidden" id="tipe-cetak" class="form-control">
            <input type="hidden" id="datasent" class="form-control">
            <input type="hidden" id="data-tahun-penilaian" class="form-control">
            <input type="hidden" id="data-semester" class="form-control">
            <input type="hidden" id="data-kelas" class="form-control">
          </div>
        </div>
        <div class="grid grid-cols-12 gap-6 mb-6">
          <div class="col-span-12 sm:col-span-12">
            <input type="date" id="tgl-diberikan" maxlength="150" class="form-control">
          </div>
        </div>
        
        <button id="btn-print" type="button" onclick="cetak()" class="btn btn-primary">Cetak</button>

      </div>
    </div>

    <!-- Modal Detil Absensi -->
    <div id="modalAbsensi" class="modal">
      <div class="modal-content">
        <span class="close" onclick="closeMetricModal('ABSENSI')">&times;</span>
        <h4>Daftar Absensi Siswa</h4>
        <br>
        <table style="all: revert; border: none !important; border-collapse: collapse;">
          <tr>
            <td style="all: revert; border: none !important; border-collapse: collapse; width: 150px;">NIS</td>
            <td style="all: revert; border: none !important; border-collapse: collapse; width: 10px;">:</td>
            <td style="all: revert; border: none !important; border-collapse: collapse;"><span id="modal-absensi-nis"></span></td>
          </tr>
          <tr>
            <td style="all: revert; border: none !important; border-collapse: collapse; width: 150px;">Nama Siswa</td>
            <td style="all: revert; border: none !important; border-collapse: collapse; width: 10px;">:</td>
            <td style="all: revert; border: none !important; border-collapse: collapse;"><span id="modal-absensi-nama-siswa"></span></td>
          </tr>
          <tr>
            <td style="all: revert; border: none !important; border-collapse: collapse; width: 150px;">Tahun Ajaran</td>
            <td style="all: revert; border: none !important; border-collapse: collapse; width: 10px;">:</td>
            <td style="all: revert; border: none !important; border-collapse: collapse;"><span id="modal-absensi-tahun-ajaran"></span></td>
          </tr>
          <tr>
            <td style="all: revert; border: none !important; border-collapse: collapse; width: 150px;">Semester</td>
            <td style="all: revert; border: none !important; border-collapse: collapse; width: 10px;">:</td>
            <td style="all: revert; border: none !important; border-collapse: collapse;"><span id="modal-absensi-semester"></span></td>
          </tr>
          <tr>
            <td style="all: revert; border: none !important; border-collapse: collapse; width: 150px;">Kelas</td>
            <td style="all: revert; border: none !important; border-collapse: collapse; width: 10px;">:</td>
            <td style="all: revert; border: none !important; border-collapse: collapse;"><span id="modal-absensi-kelas"></span></td>
          </tr>
        </table>
        <br>
        
        <div class="table-responsive">
          <table class="table table-hover">
            <thead>
              <tr style="text-align: center; font-weight: bold;">
                <td>Hadir</td>
                <td>Sakit</td>
                <td>Izin Hadir</td>
                <td>Tanpa Keterangan</td>
              </tr>
            </thead>
            <tbody id="tb-detil-absensi"></tbody>
          </table>
        </div>

      </div>
    </div>

    <!-- Modal Detil Mapel -->
    <div id="modalMapel" class="modal">
      <div class="modal-content">
        <span class="close" onclick="closeMetricModal('MAPEL')">&times;</span>
        <h4>Daftar Penilaian Mata Pelajaran</h4>
        <br>
        <table style="all: revert; border: none !important; border-collapse: collapse;">
          <tr>
            <td style="all: revert; border: none !important; border-collapse: collapse; width: 150px;">NIS</td>
            <td style="all: revert; border: none !important; border-collapse: collapse; width: 10px;">:</td>
            <td style="all: revert; border: none !important; border-collapse: collapse;"><span id="modal-mapel-nis"></span></td>
          </tr>
          <tr>
            <td style="all: revert; border: none !important; border-collapse: collapse; width: 150px;">Nama Siswa</td>
            <td style="all: revert; border: none !important; border-collapse: collapse; width: 10px;">:</td>
            <td style="all: revert; border: none !important; border-collapse: collapse;"><span id="modal-mapel-nama-siswa"></span></td>
          </tr>
          <tr>
            <td style="all: revert; border: none !important; border-collapse: collapse; width: 150px;">Tahun Ajaran</td>
            <td style="all: revert; border: none !important; border-collapse: collapse; width: 10px;">:</td>
            <td style="all: revert; border: none !important; border-collapse: collapse;"><span id="modal-mapel-tahun-ajaran"></span></td>
          </tr>
          <tr>
            <td style="all: revert; border: none !important; border-collapse: collapse; width: 150px;">Semester</td>
            <td style="all: revert; border: none !important; border-collapse: collapse; width: 10px;">:</td>
            <td style="all: revert; border: none !important; border-collapse: collapse;"><span id="modal-mapel-semester"></span></td>
          </tr>
          <tr>
            <td style="all: revert; border: none !important; border-collapse: collapse; width: 150px;">Kelas</td>
            <td style="all: revert; border: none !important; border-collapse: collapse; width: 10px;">:</td>
            <td style="all: revert; border: none !important; border-collapse: collapse;"><span id="modal-mapel-kelas"></span></td>
          </tr>
        </table>
        <br>

        <div class="table-responsive">
          <table class="table table-hover">
            <thead>
              <tr style="text-align: center; font-weight: bold;">
                <td>No</td>
                <td>Mata Pelajaran</td>
                <td>Nama Guru</td>
                <td>Nilai</td>
                <td>CP+</td>
                <td>CP-</td>
              </tr>
            </thead>
            <tbody id="tb-siswa-mapel"></tbody>
          </table>

          <br>
        </div>

      </div>
    </div>

    <!-- Modal Detil Eskul -->
    <div id="modalEskul" class="modal">
      <div class="modal-content">
        <span class="close" onclick="closeMetricModal('ESKUL')">&times;</span>
        <h4>Daftar Penilaian Ekstrakurikuler</h4>
        <br>
        <table style="all: revert; border: none !important; border-collapse: collapse;">
          <tr>
            <td style="all: revert; border: none !important; border-collapse: collapse; width: 150px;">NIS</td>
            <td style="all: revert; border: none !important; border-collapse: collapse; width: 10px;">:</td>
            <td style="all: revert; border: none !important; border-collapse: collapse;"><span id="modal-eskul-nis"></span></td>
          </tr>
          <tr>
            <td style="all: revert; border: none !important; border-collapse: collapse; width: 150px;">Nama Siswa</td>
            <td style="all: revert; border: none !important; border-collapse: collapse; width: 10px;">:</td>
            <td style="all: revert; border: none !important; border-collapse: collapse;"><span id="modal-eskul-nama-siswa"></span></td>
          </tr>
          <tr>
            <td style="all: revert; border: none !important; border-collapse: collapse; width: 150px;">Tahun Ajaran</td>
            <td style="all: revert; border: none !important; border-collapse: collapse; width: 10px;">:</td>
            <td style="all: revert; border: none !important; border-collapse: collapse;"><span id="modal-eskul-tahun-ajaran"></span></td>
          </tr>
          <tr>
            <td style="all: revert; border: none !important; border-collapse: collapse; width: 150px;">Semester</td>
            <td style="all: revert; border: none !important; border-collapse: collapse; width: 10px;">:</td>
            <td style="all: revert; border: none !important; border-collapse: collapse;"><span id="modal-eskul-semester"></span></td>
          </tr>
          <tr>
            <td style="all: revert; border: none !important; border-collapse: collapse; width: 150px;">Kelas</td>
            <td style="all: revert; border: none !important; border-collapse: collapse; width: 10px;">:</td>
            <td style="all: revert; border: none !important; border-collapse: collapse;"><span id="modal-eskul-kelas"></span></td>
          </tr>
        </table>
        <br>

        <div class="table-responsive">
          <table class="table table-hover">
            <thead>
              <tr style="text-align: center; font-weight: bold;">
                <td>No</td>
                <td>Ekstrakurikuler</td>
                <td>Nilai</td>
              </tr>
            </thead>
            <tbody id="tb-siswa-eskul"></tbody>
          </table>

          <br>
        </div>

      </div>
    </div>

 
    <!-- Required Js -->
    <script src="../assets/js/plugins/simplebar.min.js"></script>
    <script src="../assets/js/plugins/popper.min.js"></script>
    <script src="../assets/js/icon/custom-icon.js"></script>
    <script src="../assets/js/plugins/feather.min.js"></script>
    <script src="../assets/js/component.js"></script>
    <script src="../assets/js/theme.js"></script>
    <script src="../assets/js/script.js"></script>
    <script src="../includes/include_script.js"></script>
    <script src="../assets/js/secure-encrypt.js"></script>

    <div class="floting-button fixed bottom-[50px] right-[30px] z-[1030]">
    </div>

    
    <script>
      layout_change('false');
    </script>
     
    
    <script>
      layout_theme_sidebar_change('dark');
    </script>
    
     
    <script>
      change_box_container('false');
    </script>
     
    <script>
      layout_caption_change('true');
    </script>
     
    <script>
      layout_rtl_change('false');
    </script>
     
    <script>
      preset_change('preset-1');
    </script>
     
    <script>
      main_layout_change('vertical');
    </script>
    

  </body>
  <!-- [Body] end -->
  <script>
    function openModal(tipe,val) {
      document.getElementById("myModal").style.display = "flex";
        $("#tipe-cetak").val(tipe);
        $("#datasent").val(val);
    }

    function closeModal(val) {
        document.getElementById("myModal").style.display = "none";
    }

    function openMetricModal(kategori, nis, namaSiswa, tahunPelajaran, semester, kelas) {
      if(kategori == 'ABSENSI') {
        document.getElementById("modalAbsensi").style.display = "flex";
        // alert("NIS: " + nis + "\nTahun Pelajaran: " + tahunPelajaran + "\nSemester: " + semester + "\nKelas: " + kelas);
        loadDataAbsensi(nis, namaSiswa, tahunPelajaran, semester, kelas);
      }

      if(kategori == 'MAPEL') {
        document.getElementById("modalMapel").style.display = "flex";
        loadDataScore(nis, namaSiswa, tahunPelajaran, semester, kelas);
      }

      if(kategori == 'ESKUL') {
        document.getElementById("modalEskul").style.display = "flex";
        loadDataEskul(nis, namaSiswa, tahunPelajaran, semester, kelas);
      }
    }

    function closeMetricModal(val) {
      if(val == 'ABSENSI') 
      {
        document.getElementById("modalAbsensi").style.display = "none";
      }

      if(val == 'MAPEL') 
      {
        document.getElementById("modalMapel").style.display = "none";
      }

      if(val == 'ESKUL') 
      {
        document.getElementById("modalEskul").style.display = "none";
      }
    }

    function submitForm() {
      let data = {
        tanggal: document.getElementById("tgl").value,
        wali: document.getElementById("wali").value,
        nipWali: document.getElementById("nipWali").value,
        kepsek: document.getElementById("kepsek").value,
        nipKepsek: document.getElementById("nipKepsek").value
      };

      if (!data.tanggal || !data.wali || !data.nipWali || !data.kepsek || !data.nipKepsek) {
        alert("Semua field wajib diisi!");
        return;
      }

      closeModal();
    }
  </script>

  <script type="text/javascript">
    $(document).ready(function(){
      var dataDecrypt = decryptJSON(decodeURIComponent('<?php echo $datasent; ?>'), '<?=$session_key?>');
      var selectSemester = $("#search-semester").val();
      $('#data-tahun-penilaian').val(dataDecrypt.tahunAjaran);
      $('#data-semester').val(selectSemester);
      $('#data-kelas').val(dataDecrypt.kelas);

      $("#kelas-title").text(" Kelas " + dataDecrypt.kelas + " Tahun Ajaran " + dataDecrypt.tahunAjaran);
      $("#tahun-pelajaran").val(dataDecrypt.tahunAjaran);
      $("#kelas").val(dataDecrypt.kelas);
      if('<?=$semester_sent;?>' != '-')
      {
        $("#search-semester").val('<?=$semester_sent;?>');
        loadDataSiswa();
      }
      else
      {
        loadDataSiswa();
      }

      $("#select-semester").prop("style","background-color:#D3D3D3 !important");
      $("#kelas").prop("style","background-color:#DCDCDC !important");


      $("#select-jml-per-page").on('change', function(){
        $("#search-type").val("");
        $("#search-val").val("");
        var page        = $("#select-page").val("1");
        var jmlPerPage  = $("#select-jml-per-page").val();
        
        loadDataSiswa();
        
      });

      $("#select-jml-per-page, #select-page").on('change', function(){
        $("#search-type").val("");
        $("#search-val").val("");
        var page        = $("#select-page").val();
        var jmlPerPage  = $("#select-jml-per-page").val();
        
        loadDataSiswa();
        
      });

      
      $("#btn-show-cetak-massal").click(function(){
        $("#id-extrakurikuler").val('');
        $("#nama-extrakurikuler").val('');
        $("#select-tahun-ajaran").val('');
        $("#select-semester").val('');
        
        if($('#insert-area').is(':visible')){
          $('#insert-area').hide();
        } else {
          $('#insert-area').show();
        }
      });

      $("#btn-save").click(function(){
         updateKokurikulerCatatan();
        
      });

    });

    function loadDataSiswa(){

      var selectSemester = $("#search-semester").val();
      var cekKelas       = $("#kelas").val();
      var cekTahunAjaran = $("#tahun-pelajaran").val();

      $('#data-semester').val(selectSemester);
      
      // if(page == null || page == "" || page == 0)
      // {
      //   page = 1;
      // }

      // if(jmlPerPage == null || jmlPerPage == "" || jmlPerPage == 0)
      //   {
      //   jmlPerPage = 10;
      // }

      $.ajax({
        url: "../proses/proses_get_siswa_perkelas_list.php",
        type: "POST",
            data: {
              tahunPelajaran    : cekTahunAjaran,
              kelas             : cekKelas,
              semester          : selectSemester,
              // page              : page,
              // jmlPerPage        : jmlPerPage,
              statusAdmin       : window.statusAdmin
            },
        success: function(response){
          var jdata = JSON.parse(response);
          var valDataSent = '';
          if(jdata.isSuccessful == true){
            var tb_siswa    = "";
            var op_page     = "";
            var v_selected  = "";
            for(var i=0; i < jdata.data.length; i++){
              tb_siswa += "<tr>";
              tb_siswa += "<td>" + jdata.data[i].NOMOR + "</td>";
              tb_siswa += "<td style='text-align: center;'>" + jdata.data[i].NIS + "</td>";
              tb_siswa += "<td style='text-align: left;'>" + jdata.data[i].NAMA_SISWA + "</td>";
              tb_siswa += "<td style='text-align: center; font-weight: bold;'><span class='metric-clickable' onclick='openMetricModal(\"MAPEL\",\"" + jdata.data[i].NIS + "\",\"" + escapeHtmlAttr(jdata.data[i].NAMA_SISWA) + "\",\"" + jdata.data[i].TAHUN_PELAJARAN + "\",\"" + jdata.data[i].KELAS + "\",\"" + jdata.data[i].SEMESTER + "\")'>" + jdata.data[i].JML_MAPEL + "</span></td>";
              tb_siswa += "<td style='text-align: center; font-weight: bold;'><span class='metric-clickable' onclick='openMetricModal(\"ESKUL\",\"" + jdata.data[i].NIS + "\",\"" + escapeHtmlAttr(jdata.data[i].NAMA_SISWA) + "\",\"" + jdata.data[i].TAHUN_PELAJARAN + "\",\"" + jdata.data[i].KELAS + "\",\"" + jdata.data[i].SEMESTER + "\")'>" + jdata.data[i].JML_ESKUL + "</span></td>";
              tb_siswa += "<td style='text-align: center; font-weight: bold;'><span class='metric-clickable' onclick='openMetricModal(\"ABSENSI\",\"" + jdata.data[i].NIS + "\",\"" + escapeHtmlAttr(jdata.data[i].NAMA_SISWA) + "\",\"" + jdata.data[i].TAHUN_PELAJARAN + "\",\"" + jdata.data[i].KELAS + "\",\"" + jdata.data[i].SEMESTER + "\")'>" + jdata.data[i].JML_TIDAK_HADIR + "</span></td>";

              if(!jdata.data[i].KELAS.startsWith('XIII')){
                tb_siswa += "<td><div style='display: flex; align-items: center; gap: 10px;'>";
                if(jdata.data[i].STATUS_BOLEH_UBAH == "Y")
                {
                  tb_siswa += "<textarea id='kokurikuler-" + jdata.data[i].NIS + "' cols='20' rows='2' style='border: 1px solid #999;'>" + formatNull(jdata.data[i].KOKURIKULER) + "</textarea>";
                  // tb_siswa += "<button id='btn-kokurikuler' onclick='updateCatatan(\"KOKURIKULER\",\"" + jdata.data[i].NIS + "\",\"" + jdata.data[i].TAHUN_PELAJARAN + "\",\"" + jdata.data[i].KELAS + "\",\"" + jdata.data[i].SEMESTER + "\")' type='button' class='btn btn-primary' style='padding: 2px 5px; font-size: 10px;'>Simpan</button>";
                }
                else
                {
                  tb_siswa += "<textarea disabled id='kokurikuler-" + jdata.data[i].NIS + "' cols='20' rows='2' style='border: 1px solid #999;'>" + formatNull(jdata.data[i].KOKURIKULER) + "</textarea>"; 
                }
                tb_siswa += "</td>";
              }
              else
              {
                $("#cl-kokurikuler").hide();
              }
              

              tb_siswa += "<td><div style='display: flex; align-items: center; gap: 10px;'>";
              if(jdata.data[i].STATUS_BOLEH_UBAH == "Y")
              {
                tb_siswa += "<textarea id='catatan-wali-kelas-" + jdata.data[i].NIS + "' cols='20' rows='2' style='border: 1px solid #999;'>" + formatNull(jdata.data[i].CATATAN_WALI_KELAS) + "</textarea>";
                // tb_siswa += "<button id='btn-update-catatan' onclick='updateCatatan(\"CATATAN_WALI_KELAS\",\"" + jdata.data[i].NIS + "\",\"" + jdata.data[i].TAHUN_PELAJARAN + "\",\"" + jdata.data[i].KELAS + "\",\"" + jdata.data[i].SEMESTER + "\")' type='button' class='btn btn-primary' style='padding: 2px 5px; font-size: 10px;'>Simpan</button>";
              }
              else
              {
                tb_siswa += "<textarea disabled id='catatan-wali-kelas-" + jdata.data[i].NIS + "' cols='20' rows='2' style='border: 1px solid #999;'>" + formatNull(jdata.data[i].CATATAN_WALI_KELAS) + "</textarea>"; 
              }
              
              tb_siswa += "</td>";
              
              if(jdata.data[i].KELAS.startsWith('XIII') && jdata.data[i].SEMESTER == 'Genap')
              {
                var stCekLsp    = '';
                var stLulusLsp  = '<i>Tidak Lulus</i>';
                
                $("#cl-lks").show();
                tb_siswa += "<td><div style='text-align: center;'>";

                if(jdata.data[i].STATUS_KOMPETENSI_LSP == 'Y')
                {
                  stCekLsp    = 'checked';
                  stLulusLsp  = '<i>Lulus</i>';
                }

                if(jdata.data[i].STATUS_BOLEH_UBAH == "Y")
                {
                  tb_siswa += "<input data-nis='" + jdata.data[i].NIS + "' id='lsp_" + jdata.data[i].NIS + "_" + cekTahunAjaran.replace("/", "_") + "_" + cekKelas.replace(".", "_") + "_" + selectSemester + "' type='checkbox' " + stCekLsp + " onchange='redaksiLulus(\"LSP\",\"" + jdata.data[i].NIS + "\",\"" + jdata.data[i].TAHUN_PELAJARAN + "\",\"" + jdata.data[i].KELAS + "\",\"" + jdata.data[i].SEMESTER + "\")'/> <span id='ket-lulus-lsp_" + jdata.data[i].NIS + "_" + cekTahunAjaran.replace("/", "_") + "_" + cekKelas.replace(".", "_") + "_" + selectSemester + "'> " + stLulusLsp + "</span>";
                }
                else
                {
                  tb_siswa += "<input data-nis='" + jdata.data[i].NIS + "' id='lsp_" + jdata.data[i].NIS + "_" + jdata.data[i].TAHUN_PELAJARAN + "_" + jdata.data[i].KELAS + "_" + jdata.data[i].SEMESTER + "' type='checkbox' " + stCekLsp + " disabled/> <span id='ket-lulus-lsp'> " + stLulusLsp + "</span>";
                }

                tb_siswa += "</td>";
              }
              else
              {              
                $("#cl-lks").hide();
              }
              
              tb_siswa += `<td style='text-align: center;'>
                              <a href='#' onclick="openModal(`+ "'SATUAN','" +encodeURIComponent(encryptJSON({nis: jdata.data[i].NIS,kelas: jdata.data[i].KELAS,semester: jdata.data[i].SEMESTER,tahunAjaran: jdata.data[i].TAHUN_PELAJARAN}, '<?=$session_key?>'))+`')">
                                  <img style='display: inline-block' src='../assets/images/print-gray.png' width='30px' title='cetak rapor'>
                              </a>
                          </td>`;
              tb_siswa += "</tr>";
            }

            $("#tb-siswa").html(tb_siswa);
            $("#jml-page").html(jdata.jml_page);
            $("#jml-data").html(jdata.jml_data);
            $("#select-jml-per-page [value='"+jmlPerPage+"']").prop('selected', true);

            for(var i=1; i <= jdata.jml_page; i++){
              if(i == page) 
              {
                op_page += "<option value='" + i + "' selected >" + i + "</option>";
              } 
              else 
              {
                op_page += "<option value='" + i + "'>" + i + "</option>";
              }
            }

            $("#select-page").html(op_page);

          } else {
            $("#tb-siswa").html("<tr><td colspan='9'><center>-- Data tidak ditemukan --</center></td></tr>");
          }
        }
      });
    }

    function updateKokurikulerCatatan(){
      var tahunAjaran = $("#tahun-pelajaran").val();
      var kelas       = $("#kelas").val();
      var semester    = $("#search-semester").val();
      var dataSiswa   = {};

      $("textarea[id^='kokurikuler-']").each(function(){
        var nis = this.id.replace("kokurikuler-", "");

        if(!dataSiswa[nis]) {
          dataSiswa[nis] = {nis: nis};
        }

        dataSiswa[nis].kokurikuler = $(this).val();
      });

      $("textarea[id^='catatan-wali-kelas-']").each(function(){
        var nis = this.id.replace("catatan-wali-kelas-", "");

        if(!dataSiswa[nis]) {
          dataSiswa[nis] = {nis: nis};
        }

        dataSiswa[nis].catatanWaliKelas = $(this).val();
      });
      
      $("input[id^='lsp_']:not(:disabled)").each(function(){
        var nis = $(this).data("nis").toString();

        if(!dataSiswa[nis]) {
          dataSiswa[nis] = {nis: nis};
        }

        dataSiswa[nis].statusKompetensiLsp = $(this).is(":checked") ? "Y" : "T";
      });

      var daftarSiswa = Object.keys(dataSiswa).map(function(nis){
        return dataSiswa[nis];
      });

      if(daftarSiswa.length === 0) {
        alert("Data siswa tidak ditemukan.");
        return;
      }

      $("#btn-save").prop("disabled", true);

      $.ajax({
        url: "../proses/proses_update_kokurikuler_catatan.php",
        type: "POST",
        dataType: "json",
        data: {
          tahunAjaran: tahunAjaran,
          kelas: kelas,
          semester: semester,
          dataSiswa: JSON.stringify(daftarSiswa)
        },
        success: function(response){
          alert(response.msg);

          if(response.isSuccessful === true) {
            window.location.href = 'siswa_kelas_perwali?semester='+ semester +'&datasent=' + encodeURIComponent(encryptJSON({kelas: kelas,tahunAjaran: tahunAjaran}, '<?=$session_key?>'));
          }
        },
        error: function(){
          alert("Gagal menyimpan data, silakan coba lagi.");
        },
        complete: function(){
          $("#btn-save").prop("disabled", false);
        }
      });
    }

    function cetak() {
        var tempatDiberikan = $("#tempat-diberikan").val();
        var tglDiberikan    = $("#tgl-diberikan").val();
        var datasent        = $("#datasent").val();
        var tipe            = $("#tipe-cetak").val();
        var tahunPenilaian  = $("#data-tahun-penilaian").val();
        var semester        = $("#data-semester").val();
        var kelas           = $("#data-kelas").val();
        var url             = "";
        
        if(tipe == 'Massal') {
          url = 'cetak_rapor_massal?tahunAjaran='+tahunPenilaian+'&semester='+semester+'&kelas='+kelas+'&tglDiberikan='+tglDiberikan+'&tempatDiberikan='+tempatDiberikan;
        } else {
          url = 'cetak_rapor?datasent='+datasent+'&tglDiberikan='+tglDiberikan+'&tempatDiberikan='+tempatDiberikan;
        }

        if(tempatDiberikan == "" || tglDiberikan == ""){
          alert("isi data terlebih dahulu.");
          return;
        }
        
        window.open(url,'_blank');
    }

    function loadDataAbsensi(nis, namaSiswa, tahunPelajaran, kelas, semester){
      
      $.ajax({
        url: "../proses/proses_get_absensi_by_siswa.php",
        type: "POST",
            data: {
              nis               : nis,
              namaSiswa         : namaSiswa,
              tahunPelajaran    : tahunPelajaran,
              kelas             : kelas,
              semester          : semester
            },
        success: function(response){
          var jdata = JSON.parse(response);
          if(jdata.isSuccessful == true){
            var tb_siswa    = "";
            var op_page     = "";
            var v_selected  = "";
            
            $("#modal-absensi-nis").text(nis);
            $("#modal-absensi-nama-siswa").text(namaSiswa);
            $("#modal-absensi-tahun-ajaran").text(tahunPelajaran);
            $("#modal-absensi-semester").text(semester);
            $("#modal-absensi-kelas").text(kelas);

            for(var i=0; i < jdata.data.length; i++){
              tb_siswa += "<tr>";
              tb_siswa += "<td style='text-align: center;'>" + jdata.data[i].KEHADIRAN + "</td>";
              tb_siswa += "<td style='text-align: center;'>" + jdata.data[i].SAKIT + "</td>";
              tb_siswa += "<td style='text-align: center;'>" + jdata.data[i].IZIN + "</td>";
              tb_siswa += "<td style='text-align: center;'>" + jdata.data[i].TANPA_KETERANGAN + "</td>";
              tb_siswa += "</tr>";
            }

            $("#tb-detil-absensi").html(tb_siswa);

          } else {
            $("#modal-absensi-nis").text(nis);
            $("#modal-absensi-nama-siswa").text(namaSiswa);
            $("#modal-absensi-tahun-ajaran").text(tahunPelajaran);
            $("#modal-absensi-semester").text(semester);
            $("#modal-absensi-kelas").text(kelas);
            $("#tb-detil-absensi").html("<tr><td colspan='6'><center>-- Data tidak ditemukan --</center></td></tr>");
          }
        }
      });
    }

    function loadDataScore(nis, namaSiswa, tahunAjaran, kelas, semester){

      $.ajax({
        url: "../proses/proses_get_score.php",
        type: "POST",
            data: {
              searchType            : 'NIS',
              searchVal             : nis,
              page                  : 1,
              jmlPerPage            : 100,
              searchTahunAjaran     : tahunAjaran,
              searchKelas           : kelas,
              searchSemester        : semester,
              statusAdmin           : window.statusAdmin
            },
        success: function(response){
          var jdata = JSON.parse(response);
          if(jdata.isSuccessful == true){
            var tb_siswa    = "";
            var op_page     = "";
            var v_selected  = "";
            // $("#mapel-title").text(" Kelas " + kelas + " Tahun Ajaran " + tahunAjaran + " Semester " + semester);
            $("#modal-mapel-nis").text(nis);
            $("#modal-mapel-nama-siswa").text(namaSiswa);
            $("#modal-mapel-tahun-ajaran").text(tahunAjaran);
            $("#modal-mapel-semester").text(semester);
            $("#modal-mapel-kelas").text(kelas);
            for(var i=0; i < jdata.data.length; i++){
              tb_siswa += "<tr style=''>";
              tb_siswa += "<td>" + jdata.data[i].NOMOR + "</td>";
              tb_siswa += "<td>" + jdata.data[i].NAMA_MAPEL + "</td>";
              tb_siswa += "<td>" + jdata.data[i].NAMA_GURU + "</td>";
              tb_siswa += "<td style='text-align: center;'>" + jdata.data[i].NILAI_AKHIR + "</td>";
              tb_siswa += "<td><textarea cols='20' rows='1' readonly>" + jdata.data[i].CP_PLUS + "</textarea></td>";
              tb_siswa += "<td><textarea cols='20' rows='1' readonly>" + jdata.data[i].CP_MIN + "</textarea></td>";
              tb_siswa += "</tr>";
            }

            $("#tb-siswa-mapel").html(tb_siswa);

          } else {
            $("#modal-mapel-nis").text(nis);
            $("#modal-mapel-nama-siswa").text(namaSiswa);
            $("#modal-mapel-tahun-ajaran").text(tahunAjaran);
            $("#modal-mapel-semester").text(semester);
            $("#modal-mapel-kelas").text(kelas);
            $("#tb-siswa-mapel").html("<tr><td colspan='9'><center>-- Data tidak ditemukan --</center></td></tr>");
          }
        }
      });
    }
    
    function loadDataEskul(nis, namaSiswa, tahunAjaran, kelas, semester){

      $.ajax({
        url: "../proses/proses_get_ekstrakurikuler_grade_list.php",
        type: "POST",
            data: {
              searchType            : 'NIS',
              searchVal             : nis,
              page                  : 1,
              jmlPerPage            : 100,
              searchTahunAjaran     : tahunAjaran,
              searchKelas           : kelas,
              searchSemester        : semester,
              statusAdmin           : window.statusAdmin
            },
        success: function(response){
          var jdata = JSON.parse(response);
          if(jdata.isSuccessful == true){
            var tb_siswa    = "";
            var op_page     = "";
            var v_selected  = "";
            // $("#mapel-title").text(" Kelas " + kelas + " Tahun Ajaran " + tahunAjaran + " Semester " + semester);
            $("#modal-eskul-nis").text(nis);
            $("#modal-eskul-nama-siswa").text(namaSiswa);
            $("#modal-eskul-tahun-ajaran").text(tahunAjaran);
            $("#modal-eskul-semester").text(semester);
            $("#modal-eskul-kelas").text(kelas);
            for(var i=0; i < jdata.data.length; i++){
              tb_siswa += "<tr style=''>";
              tb_siswa += "<td>" + jdata.data[i].NOMOR + "</td>";
              tb_siswa += "<td>" + jdata.data[i].NAMA_EKSTRAKURIKULER + "</td>";
              tb_siswa += "<td style='text-align: center;'>" + jdata.data[i].NILAI_AKHIR + "</td>";
              tb_siswa += "</tr>";
            }

            $("#tb-siswa-eskul").html(tb_siswa);

          } else {
            $("#modal-eskul-nis").text(nis);
            $("#modal-eskul-nama-siswa").text(namaSiswa);
            $("#modal-eskul-tahun-ajaran").text(tahunAjaran);
            $("#modal-eskul-semester").text(semester);
            $("#modal-eskul-kelas").text(kelas);
            $("#tb-siswa-eskul").html("<tr><td colspan='3'><center>-- Data tidak ditemukan --</center></td></tr>");
          }
        }
      });
    }

    
    function redaksiLulus(jenis,nis,tahunAjaran,kelas,semester){
      var catatan = '';
      
      // if(jenis == 'KOKURIKULER') 
      // {
      //   catatan = $("#kokurikuler-" + nis).val();
      // } 
      // else if(jenis == 'CATATAN_WALI_KELAS') 
      // {
      //   catatan = $("#catatan-wali-kelas-" + nis).val();
      // } 
      // else if(jenis == 'LSP')
      // {
      //   if($("#lsp_" + nis + "_" + tahunAjaran.replace("/", "_") + "_" + kelas.replace(".", "_") + "_" + semester).is(":checked"))
      //   {
      //     catatan = "Y";
      //   } 
      //   else 
      //   {
      //     catatan = "T";
      //   }
      // }

      // if(catatan == null || catatan == "") {
      //   alert("Catatan wali kelas tidak boleh kosong.");
      //   return;
      // }

      // $.ajax({
      //   url: "../proses/proses_update_catatan.php",
      //   type: "POST",
      //       data: {
      //         jenis           : jenis,
      //         nis             : nis,
      //         tahunAjaran     : tahunAjaran,
      //         kelas           : kelas,
      //         semester        : semester,
      //         catatan         : catatan,
      //         statusAdmin     : window.statusAdmin
      //       },
      //   success: function(response){
      //     var jdata = JSON.parse(response);
      //     if(jdata.isSuccessful == true){
              
      //         var page        = $("#select-page").val();
      //         var jmlPerPage  = $("#select-jml-per-page").val();

      //         alert(jdata.msg);
      //         // window.location.href = 'siswa_kelas_perwali?page='+page+'&jml_per_page='+jmlPerPage+'&semester='+ semester +'&datasent=' + encodeURIComponent(encryptJSON({kelas: kelas,tahunAjaran: tahunAjaran}, '<?=$session_key?>'));
      //         window.location.href = 'siswa_kelas_perwali?semester='+ semester +'&datasent=' + encodeURIComponent(encryptJSON({kelas: kelas,tahunAjaran: tahunAjaran}, '<?=$session_key?>'));
      //     } else {
      //       alert(jdata.msg);
      //     }
      //   }
      // });

        if($("#lsp_" + nis + "_" + tahunAjaran.replace("/", "_") + "_" + kelas.replace(".", "_") + "_" + semester).is(":checked"))
        {
          $("#ket-lulus-lsp_" + nis + "_" + tahunAjaran.replace("/", "_") + "_" + kelas.replace(".", "_") + "_" + semester).text("Lulus");
        } 
        else 
        {
          $("#ket-lulus-lsp_" + nis + "_" + tahunAjaran.replace("/", "_") + "_" + kelas.replace(".", "_") + "_" + semester).text("Tidak Lulus");
        }
    }
    

  </script>
</html>
