<?php
  include_once '../includes/session.php';
  include_once '../includes/sidebar_menu.php';
  include_once '../includes/header.php';
?>
<style>
/* Latar belakang popup */
.modal {
  display: none; /* awalnya tersembunyi */
  position: fixed;
  z-index: 1000;
  left: 0; top: 0;
  width: 100%; height: 100%;
  background-color: rgba(0,0,0,0.5);
}

/* Konten popup */
.modal-content {
  background: #fff;
  margin: 10% auto;
  padding: 20px;
  border-radius: 10px;
  width: 400px;
  box-shadow: 0 5px 10px rgba(0,0,0,0.3);
}

/* Tombol close (X) */
.close {
  float: right;
  font-size: 18px;
  font-weight: bold;
  cursor: pointer;
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
                <center><h4>Rapor</h4></center><br>
                <!-- [ breadcrumb ] start -->
                <div class="page-header" style="margin-top: -20px;">
                  <div class="me-auto pc-mob-drp">
                    <ul class="inline-flex *:min-h-header-height *:inline-flex *:items-center">
                      <li class="dropdown pc-h-item">
                        <a class="pc-head-link" href="#" id="btn-show-cetak-massal" role="button" aria-haspopup="false" aria-expanded="false">
                          <i data-feather="printer" style='display: inline-block'></i> Cetak Rapor Massal
                        </a>
                      </li>
                    </ul>
                  </div>
                </div>
                <!-- [ breadcrumb ] end -->
                <div style="width: 700px; display: none;" id="insert-area">
                  <div class="flex flex-row gap-2" >
                    <div style="display: flex; gap: 10px;">
                      <select name="" id="select-tahun-penilaian" class="form-control" onchange="selectedTahunPenilaian(this.value)"></select>
                      <select name="" id="select-semester" class="form-control" onchange="selectedSemsester()" disabled>
                          <option value="">-- Pilih Semester --</option>
                          <option value="Gasal">Gasal</option>
                          <option value="Genap">Genap</option>
                      </select>
                      <input type="text" id="kelas" maxlength="10"  class="form-control" placeholder="Kelas" disabled>
                      <button id="btn-cetak-massal" type="button" class="btn btn-primary" onclick="openModal('Massal','')">Cetak</button>
                    </div>
                  </div>
                  <br>
                </div>

                <div id="search-area" style="width: 500px;">
                  <div class="flex flex-wrap gap-2">
                    <div style="width: 150px;">
                      <select id="search-type" class="form-control">
                        <option value="">--Pilih--</option>
                        <option value="NIS">NIS</option>
                        <option value="NAMA_SISWA">Nama</option>
                        <option value="KELAS">Kelas</option>
                        <option value="SEMESTER">Semester</option>
                        <option value="TAHUN_PELAJARAN">Tahun Ajaran</option>
                      </select>
                    </div>
                    <div style="display: flex; gap: 10px;">
                      <input id="search-val" type="text" class="form-control" placeholder="Pencarian . . ." />
                      <button id="btn-search-siswa" type="button" class="btn btn-primary">Cari</button>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-hover">
                    <thead>
                      <tr style="text-align: center; font-weight: bold;">
                        <td width="7%">No</td>
                        <td width="12%">Tahun Ajaran</td>
                        <td width="12%">Semester</td>
                        <td width="7%">Kelas</td>
                        <td width="18%">NIS</td>
                        <td width="30%">Nama Siswa</td>
                        <td width="14%">Action</td>
                      </tr>
                    </thead>
                    <tbody id="tb-siswa"></tbody>
                  </table>

                  <br>
                    
                  <div id="page-area" style="width: 100%; padding-left: 15px; padding-right: 15px; margin-top: 10px;">
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
                  </div>
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
      <div class="modal-content">
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

 
    <!-- Required Js -->
    <script src="../assets/js/plugins/simplebar.min.js"></script>
    <script src="../assets/js/plugins/popper.min.js"></script>
    <script src="../assets/js/icon/custom-icon.js"></script>
    <script src="../assets/js/plugins/feather.min.js"></script>
    <script src="../assets/js/component.js"></script>
    <script src="../assets/js/theme.js"></script>
    <script src="../assets/js/script.js"></script>
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
      document.getElementById("myModal").style.display = "block";
      if(tipe == 'Massal') {
        var tahunPenilaian = $("#select-tahun-penilaian").val();
        var semester       = $("#select-semester").val();
        var kelas          = $("#kelas").val();
        if(!tahunPenilaian || !semester || !kelas) {
          alert("Tahun Ajaran, Semester, dan Kelas wajib diisi.");
          closeModal();
          return;
        }

        $("#tipe-cetak").val(tipe);
        $("#data-tahun-penilaian").val(tahunPenilaian);
        $("#data-semester").val(semester);
        $("#data-kelas").val(kelas);

      } else {
        $("#tipe-cetak").val(tipe);
        $("#datasent").val(val);
      }
    }

    function closeModal() {
      document.getElementById("myModal").style.display = "none";
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
      loadDataSiswa('','',1,10);
      loadTahunPenilaian();

      $("#select-semester").prop("style","background-color:#D3D3D3 !important");
      $("#kelas").prop("style","background-color:#DCDCDC !important");

      $("#btn-search-siswa").click(function(){
        var searchType  = $("#search-type").val();
        var searchVal   = $("#search-val").val();
        var page        = $("#select-page").val();
        var jmlPerPage  = $("#select-jml-per-page").val();
        
        if(searchType == "" || searchVal == ""){
          loadDataSiswa('','',1,10);
        } else {
          loadDataSiswa(searchType,searchVal,page,jmlPerPage);
        }
      });

      $("#select-jml-per-page").on('change', function(){
        $("#search-type").val("");
        $("#search-val").val("");
        var searchType  = $("#search-type").val();
        var searchVal   = $("#search-val").val();
        var page        = $("#select-page").val("1");
        var jmlPerPage  = $("#select-jml-per-page").val();
        
        loadDataSiswa(searchType,searchVal,"1",jmlPerPage);
        
      });

      $("#select-jml-per-page, #select-page").on('change', function(){
        $("#search-type").val("");
        $("#search-val").val("");
        var searchType  = $("#search-type").val();
        var searchVal   = $("#search-val").val();
        var page        = $("#select-page").val();
        var jmlPerPage  = $("#select-jml-per-page").val();
        
        loadDataSiswa(searchType,searchVal,page,jmlPerPage);
        
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

    });

    function loadDataSiswa(searchType,searchVal,page,jmlPerPage){
      if(page == null || page == "" || page == 0)
      {
        page = 1;
      }

      if(jmlPerPage == null || jmlPerPage == "" || jmlPerPage == 0)
        {
        jmlPerPage = 10;
      }

      $.ajax({
        url: "../proses/proses_get_siswa_rapor_list.php",
        type: "POST",
            data: {
              searchType  : searchType,
              searchVal   : searchVal,
              page        : page,
              jmlPerPage  : jmlPerPage,
              searchTahunAjaran  : ''
            },
        success: function(response){
          var jdata = JSON.parse(response);
          if(jdata.isSuccessful == true){
            var tb_siswa    = "";
            var op_page     = "";
            var v_selected  = "";
            for(var i=0; i < jdata.data.length; i++){
              tb_siswa += "<tr>";
              tb_siswa += "<td>" + jdata.data[i].NOMOR + "</td>";
              tb_siswa += "<td style='text-align: center;'>" + jdata.data[i].TAHUN_PELAJARAN + "</td>";
              tb_siswa += "<td style='text-align: center;'>" + jdata.data[i].SEMESTER + "</td>";
              tb_siswa += "<td style='text-align: center;'>" + jdata.data[i].KELAS + "</td>";
              tb_siswa += "<td style='text-align: center;'>" + jdata.data[i].NIS + "</td>";
              tb_siswa += "<td>" + jdata.data[i].NAMA_SISWA + "</td>";
              // tb_siswa += `<td style='text-align: center;'>
              //                 <a href='#' onclick="openModal()">
              //                     <img style='display: inline-block' src='../assets/images/print-gray.png' width='30px' title='cetak rapor'>
              //                 </a>
              //             </td>`;
              tb_siswa += `<td style='text-align: center;'>
                              <a href='#' 
                              onclick="openModal('Satuan','` + encodeURIComponent(encryptJSON({
                                  kelas: jdata.data[i].KELAS,
                                  semester: jdata.data[i].SEMESTER,
                                  tahunAjaran: jdata.data[i].TAHUN_PELAJARAN
                              }, '<?=$session_key?>')) + `')">
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
            $("#tb-siswa").html("<tr><td colspan='7'><center>-- Data tidak ditemukan --</center></td></tr>");
          }
        }
      });
    }

    function deleteDataSiswa(nis,kelas,semester,tahunAjaran){
      console.log('nis');
      if(nis == ""  || kelas == "" || tahunAjaran == ""  || semester == "" ){
        alert("Data siswa tidak ditemukan.");
        return false;
      }
      
      var konfirmasi = confirm("Apakah Anda yakin ingin menghapus data siswa dengan NIS "+nis+"?");
      if(!konfirmasi) {
        return false;
      }

      $.ajax({
        url: "../proses/proses_delete_siswa.php",
        type: "POST",
            data: {
              nis           : nis,
              kelas         : kelas,
              tahunAjaran: tahunAjaran,
              semester      : semester
            },
        success: function(response){
          var jdata = JSON.parse(response);
          if(jdata.isSuccessful == true){
            alert(jdata.msg);

            $("#search-type").val("");
            $("#search-val").val("");
            var searchType  = $("#search-type").val();
            var searchVal   = $("#search-val").val();
            var page        = $("#select-page").val();
            var jmlPerPage  = $("#select-jml-per-page").val();
            
            loadDataSiswa(searchType,searchVal,page,jmlPerPage);

          } else {
            alert("Gagal memuat data siswa.");
          }
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

    function loadTahunPenilaian(){
      $.ajax({
        url: "../proses/proses_get_tahun_penilaian.php",
        success: function(response){
          var jdata               = JSON.parse(response);
          var opt_tahun_penilaian = "<option value=''>-- Pilih Tahun Ajaran --</option>";
          if(jdata.isSuccessful == true){
            for(var i=0; i < jdata.data.length; i++){
              opt_tahun_penilaian += "<option value='" + jdata.data[i].TAHUN_PELAJARAN + "'>" + jdata.data[i].TAHUN_PELAJARAN + "</option>";
            }

            $("#select-tahun-penilaian").html(opt_tahun_penilaian);

          } else {
            $("#select-tahun-penilaian").html(opt_tahun_penilaian);
          }
        }
      });
    }

    function selectedTahunPenilaian(){
      var tahunAjaran = $("#select-tahun-penilaian").val();
      if(tahunAjaran != ""){
        $("#select-semester").prop("disabled", false);
        $("#select-semester").prop("style","background-color:#FFFFFF !important");
      } else {
        $("#select-semester").prop("disabled", true);
        $("#select-semester").prop("style","background-color:#D3D3D3 !important");
        $("#kelas").prop("disabled", true);
        $("#kelas").prop("style","background-color:#DCDCDC !important");
        $("#kelas").val('');
      }
      $("#kelas").prop("disabled", true);
      $("#kelas").prop("style","background-color:#DCDCDC !important");
      $("#kelas").val('');
    }

    function selectedSemsester(){
      var semester = $("#select-semester").val();
      if(semester != ""){
        $("#kelas").prop("disabled", false);
        $("#kelas").prop("style","background-color:#FFFFFF !important");
      } else {
        $("#kelas").prop("disabled", true);
        $("#kelas").prop("style","background-color:#DCDCDC !important");
        $("#kelas").val('');
      }
    }

  </script>
</html>
