<?php
  include_once '../includes/session.php';
  include_once '../includes/sidebar_menu.php';
  include_once '../includes/header.php';
?>

    <!-- [ Main Content ] start -->
    <div class="pc-container">
      <div class="pc-content">

        <!-- [ Main Content ] start -->
        <div class="grid grid-cols-1 gap-x-6">
          <div class="col-span-1 xl:col-span-8 md:col-span-6"  style="width: 75%;">
            <div class="card table-card">
              <div class="card-header d-flex justify-content-between align-items-center">
                <center><h4>Daftar Kelas</h4></center><br><br><br>
                <div id="search-area" style="width: 500px;">
                  <div style="display: flex; align-items: center; gap: 10px;">
                    <span>Cari : </span>
                    <div style="width: 150px;">
                      <select id="search-type" class="form-control" onchange="placeholderSearch(this.value)">
                        <option value="">--Pencari--</option>
                        <option value="KELAS">Kelas</option>
                        <option value="TAHUN_PELAJARAN">Tahun Ajaran</option>
                      </select>
                    </div>
                    <div style="display: flex; gap: 10px;">
                      <input id="search-val" type="text" class="form-control" placeholder="Pilih Pencari..." />
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
                        <td width="8%">No</td>
                        <td width="12%">Tahun Ajaran</td>
                        <td width="10%">Kelas</td>
                        <td width="10%">Jumlah Siswa</td>
                        <td>NIP</td>
                        <td>Nama Wali Kelas</td>
                        <td width="9%">Action</td>
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
        url: "../proses/proses_get_kelas_perwali_list.php",
        type: "POST",
            data: {
              searchType        : searchType,
              searchVal         : searchVal,
              page              : page,
              jmlPerPage        : jmlPerPage,
              searchTahunAjaran : '',
              statusAdmin       : window.statusAdmin
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
              tb_siswa += "<td style='text-align: center;'>" + jdata.data[i].KELAS + "</td>";
              tb_siswa += "<td style='text-align: center;'>" + jdata.data[i].JML_SISWA + "</td>";
              tb_siswa += "<td style='text-align: center;'>" + jdata.data[i].NIP + "</td>";
              tb_siswa += "<td style='text-align: left;'>" + jdata.data[i].NAMA_GURU + "</td>";
              tb_siswa += `<td style='text-align: center;'>
                              <a href='siswa_kelas_perwali?datasent=` + encodeURIComponent(encryptJSON({
                                  kelas: jdata.data[i].KELAS,
                                  tahunAjaran: jdata.data[i].TAHUN_PELAJARAN
                              }, '<?=$session_key?>')) + `'>
                                  <img style='display: inline-block' src='../assets/images/view-blue.png' width='30px' title='lihat'>
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
