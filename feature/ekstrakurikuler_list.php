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
          <div class="col-span-1 xl:col-span-8 md:col-span-6">
            <div class="card table-card">
              <div class="card-header d-flex justify-content-between align-items-center">
                <center><h4>Daftar Ekstrakurikuler</h4></center><br>
                <!-- [ breadcrumb ] start -->
                 <div class="page-header" style="margin-top: -20px;">
                  <div class="me-auto pc-mob-drp">
                    <ul class="inline-flex *:min-h-header-height *:inline-flex *:items-center" id="add-button-area">
                      <li class="dropdown pc-h-item">
                        <a class="pc-head-link me-0" role="button" id="btn-extrakurikuler">
                          <i data-feather="plus" style='display: inline-block'></i> Tambah Ekstrakurikuler
                        </a>
                      </li>
                    </ul>
                  </div>
                </div>
                <!-- [ breadcrumb ] end -->
                <div style="width: 700px; display: none;" id="insert-area">
                  <div class="flex flex-row gap-2" >
                    <div style="display: flex; gap: 10px;">
                      <span>Tambah Data :</span>
                      <input id="nama-extrakurikuler" type="text" class="form-control" placeholder="Nama Extrakurikuler" />
                      <input id="id-extrakurikuler" type="hidden" class="form-control"/>
                      <select id="select-tahun-ajaran" class="form-control">
                        <option value="">--Pilih Tahun Ajaran--</option>
                      </select>
                      <!-- <select id="select-semester" class="form-control">
                        <option value="">--Pilih Semester--</option>
                        <option value="Gasal">Gasal</option>
                        <option value="Genap">Genap</option>
                      </select> -->
                      <button id="btn-insert-extrakurikuler" type="button" class="btn btn-primary">Simpan</button>
                    </div>
                  </div><br>
                </div>
                
                <!-- [ breadcrumb ] end -->
                <div id="search-area" style="width: 500px;">
                  <div class="flex flex-wrap gap-2">
                    <div style="width: 150px;">
                      <select id="search-type" class="form-control">
                        <option value="">--Pencari--</option>
                        <option value="NAMA_EKSTRAKURIKULER">Nama Ekstrakurikuler</option>
                        <!-- <option value="SEMESTER">Semester</option> -->
                        <option value="TAHUN_PELAJARAN">Tahun Ajaran</option>
                      </select>
                    </div>
                    <div style="display: flex; gap: 10px;">
                      <input id="search-val" type="text" class="form-control" placeholder="Pilih Pencari..." />
                      <button id="btn-search-extrakurikuler" type="button" class="btn btn-primary">Cari</button>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-hover">
                    <thead>
                      <tr style="text-align: left; font-weight: bold;">
                        <td width="7%">No</td>
                        <td width="12%">Tahun Ajaran</td>
                        <td width="22%">Nama Ekstrakurikuler</td>
                        <!-- <td width="12%">Semester</td> -->
                        <td width="10%">Action</td>
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
      generateTahunAjaran();
      loadDataExtrakurikuler('','',1,10);

      // if(window.statusAdmin != "Y"){
      //   $("#add-button-area").hide();
      // }

      $("#btn-insert-extrakurikuler").click(function(){
        var idExtrakurikuler    = $("#id-extrakurikuler").val();
        var namaExtrakurikuler  = $("#nama-extrakurikuler").val();
        var tahunAjaran      = $("#select-tahun-ajaran").val();
        // var semester            = $("#select-semester").val();

        if(namaExtrakurikuler == ""){
          alert("Nama ekstrakurikuler tidak boleh kosong.");
          $("#nama-extrakurikuler").focus();
          return false;
        }

        if(tahunAjaran == ""){
          alert("Tahun pelajaran tidak boleh kosong.");
          $("#select-tahun-ajaran").focus();
          return false;
        }

        // if(semester == ""){
        //   alert("Semester tidak boleh kosong.");
        //   $("#select-semester").focus();
        //   return false;
        // }

        $.ajax({
          url: "../proses/proses_insert_edit_extrakurikuler.php",
          type: "POST",
              data: {
                idExtrakurikuler    : idExtrakurikuler,
                namaExtrakurikuler  : namaExtrakurikuler,
                tahunAjaran      : tahunAjaran
                // semester            : semester
              },
          success: function(response){
            var jdata = JSON.parse(response);
            if(jdata.isSuccessful == true){
              alert(jdata.msg);
              $("#id-extrakurikuler").val('');
              $("#nama-extrakurikuler").val('');
              $("#select-tahun-ajaran").val('');
              // $("#select-semester").val('');
              $('#insert-area').hide();
              loadDataExtrakurikuler('','',1,10);
            } else {
              alert(jdata.msg);
            }
          }
        });

      });

      $("#btn-extrakurikuler").click(function(){
        $("#id-extrakurikuler").val('');
        $("#nama-extrakurikuler").val('');
        $("#select-tahun-ajaran").val('');
        // $("#select-semester").val('');
        
        if($('#insert-area').is(':visible')){
          $('#insert-area').hide();
        } else {
          $('#insert-area').show();
        }
      });
      
      $("#btn-search-extrakurikuler").click(function(){
        var searchType  = $("#search-type").val();
        var searchVal   = $("#search-val").val();
        var page        = $("#select-page").val();
        var jmlPerPage  = $("#select-jml-per-page").val();
        
        if(searchType == "" || searchVal == ""){
          loadDataExtrakurikuler('','',1,10);
        } else {
          loadDataExtrakurikuler(searchType,searchVal,page,jmlPerPage);
        }
      });

      $("#select-jml-per-page").on('change', function(){
        $("#search-type").val("");
        $("#search-val").val("");
        var searchType  = $("#search-type").val();
        var searchVal   = $("#search-val").val();
        var page        = $("#select-page").val("1");
        var jmlPerPage  = $("#select-jml-per-page").val();
        
        loadDataExtrakurikuler(searchType,searchVal,"1",jmlPerPage);
        
      });

      $("#select-jml-per-page, #select-page").on('change', function(){
        $("#search-type").val("");
        $("#search-val").val("");
        var searchType  = $("#search-type").val();
        var searchVal   = $("#search-val").val();
        var page        = $("#select-page").val();
        var jmlPerPage  = $("#select-jml-per-page").val();
        
        loadDataExtrakurikuler(searchType,searchVal,page,jmlPerPage);
        
      });

    });

    function loadDataExtrakurikuler(searchType,searchVal,page,jmlPerPage){
      if(page == null || page == "" || page == 0)
      {
        page = 1;
      }

      if(jmlPerPage == null || jmlPerPage == "" || jmlPerPage == 0)
        {
        jmlPerPage = 10;
      }

      $.ajax({
        url: "../proses/proses_get_ekstrakurikuler_list.php",
        type: "POST",
            data: {
              searchType  : searchType,
              searchVal   : searchVal,
              page        : page,
              jmlPerPage  : jmlPerPage
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
              tb_siswa += "<td>" + jdata.data[i].TAHUN_PELAJARAN + "</td>";
              tb_siswa += "<td>" + jdata.data[i].NAMA_EKSTRAKURIKULER + "</td>";
              // tb_siswa += "<td>" + jdata.data[i].SEMESTER + "</td>";
              // if(window.statusAdmin == "Y"){
                tb_siswa += `<td>
                                <a href='#' onclick="editExtrakurikuler('` + encodeURIComponent(encryptJSON(JSON.stringify({
                                                                                        idExtrakurikuler: jdata.data[i].ID_EKSTRAKURIKULER,
                                                                                        namaExtrakurikuler: jdata.data[i].NAMA_EKSTRAKURIKULER,
                                                                                        semester: jdata.data[i].SEMESTER,
                                                                                        tahunAjaran: jdata.data[i].TAHUN_PELAJARAN
                                                                                    }), '<?=$session_key?>')) + `')">
                                    <img style='display: inline-block' src='../assets/images/edit-orange.png' width='30px' title='ubah'>
                                </a>
                                <a id='btn-delete-siswa' href='#' onclick='deleteDataSiswa("` + jdata.data[i].ID_EKSTRAKURIKULER + `","` + jdata.data[i].TAHUN_PELAJARAN + `")'>
                                    <img style='display: inline-block' src='../assets/images/delete-red.png' width='30px' title='hapus'>
                                </a>
                            </td>`;
              // } else {
              //   tb_siswa += `<td style='text-align: center;'>-</td>`;
              // }
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

    function deleteDataSiswa(idEkstrakurikuler,tahunAjaran){
      console.log('nis');
      if(idEkstrakurikuler == "" || tahunAjaran == ""){
        alert("Data ekstrakurikuler gagal dihapus.");
        return false;
      }
      
      var konfirmasi = confirm("Apakah Anda yakin ingin menghapus data ekstrakurikuler ini?");
      if(!konfirmasi) {
        return false;
      }

      $.ajax({
        url: "../proses/proses_delete_ekstrakurikuler.php",
        type: "POST",
            data: {
              idEkstrakurikuler : idEkstrakurikuler,
              tahunAjaran    : tahunAjaran
              // semester          : semester
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
            
            loadDataExtrakurikuler(searchType,searchVal,page,jmlPerPage);

          } else {
            alert(jdata.msg);
          }
        }
      });
    }

    function editExtrakurikuler(param){
      generateTahunAjaran();
      var data = JSON.parse(decryptJSON(decodeURIComponent(param), '<?=$session_key?>'));
      if(data.idExtrakurikuler == ""  || data.namaExtrakurikuler == "" || data.tahunAjaran == ""  || data.semester == "" ){
        alert("Data siswa tidak ditemukan.");
        return false;
      }

      $("#insert-area").show();
      $("#id-extrakurikuler").val(data.idExtrakurikuler);
      $("#nama-extrakurikuler").val(data.namaExtrakurikuler);
      $("#select-tahun-ajaran").val(data.tahunAjaran);
      // $("#select-semester").val(data.semester);
      
      $('html, body').animate({
          scrollTop: $("#insert-area").offset().top
      }, 1000);
    }
  </script>
</html>
