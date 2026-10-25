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
                <center><h4>Daftar Kategori Mata Pelajaran</h4></center><br>
                <!-- [ breadcrumb ] start -->
                <div class="page-header" style="margin-top: -20px;">
                  <div class="me-auto pc-mob-drp">
                    <ul class="inline-flex *:min-h-header-height *:inline-flex *:items-center" id="add-data-area">
                      <li class="dropdown pc-h-item">
                        <a class="pc-head-link dropdown-toggle me-0" data-pc-toggle="dropdown" role="button"
                          aria-haspopup="false" aria-expanded="false">
                          <i data-feather="plus" style='display: inline-block'></i> Tambah Kategori
                        </a>
                        <div class="dropdown-menu pc-h-dropdown drp-search">
                          <form class="px-2 py-1">
                            <a type="button" href="#" id="btn-insert-category" type="button" class="btn btn-secondary">&nbsp;&nbsp;&nbsp;Kategory&nbsp;&nbsp;&nbsp;</a><br><br>
                            <a type="button" href="#" id="btn-insert-sub-category" type="button" class="btn btn-secondary">&nbsp;&nbsp;&nbsp;&nbsp;Sub-Kategori&nbsp;&nbsp;&nbsp;&nbsp;</a>
                          </form>
                        </div>
                      </li>
                    </ul>
                  </div>
                </div>
                <!-- [ breadcrumb ] end -->
                <div id="search-area" style="width: 1200px;">
                  <div class="flex flex-wrap gap-2" id="area-insert-category" style="display: none;">
                    <div style="display: flex; gap: 10px;">
                      <span>Tambah Data : </span>
                      <select name="" id="select-tahun-ajaran" class="form-control"><option value="">--Tahun Ajaran--</option></select>
                      <input id="val-category" type="text" class="form-control" placeholder="Nama Kategori" />
                      <button id="btn-save-category" type="button" class="btn btn-primary">Simpan</button>
                    </div>
                  </div>
                  <div class="flex flex-wrap gap-2" id="area-sub-insert-category" style="display: none;">
                    <span>Tambah Data : </span>
                    <div style="width: 150px;">
                      <select name="" id="select-tahun-ajaran2" class="form-control"><option value="">--Tahun Ajaran--</option></select>
                    </div>
                    <div style="width: 300px;">
                      <select id="select-category" class="form-control">
                        <option value="">--Pilih Kategori--</option>
                      </select>
                    </div>
                    <div style="display: flex; gap: 10px;">
                      <input id="val-sub-category" type="text" class="form-control" placeholder="Nama Sub Kategori" />
                      <button id="btn-save-sub-category" type="button" class="btn btn-primary">Simpan</button>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-hover">
                    <thead>
                      <tr style="text-align: left; font-weight: bold;">
                        <td>No</td>
                        <td>Tahun Ajaran</td>
                        <td>Nama Kategory</td>
                        <td>Nama Sub Kategori</td>
                        <td>Aksi</td>
                    </thead>
                    <tbody id="tb-subject"></tbody>
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
      loadDataCategory(1,10);

      // if(window.statusAdmin != "Y"){
      //   $("#add-data-area").hide();
      // }

      $("#btn-insert-category, #btn-insert-sub-category").click(function(){
        // $("#area-insert-category").show();
        // $("#area-sub-insert-category").hide();
        // Tutup dropdown-menu
        $(this).closest('.dropdown').find('.dropdown-menu').removeClass('show').hide();
      });

      $("#btn-search-subject").click(function(){
        var searchType  = $("#search-type").val();
        var searchVal   = $("#search-val").val();
        var page        = $("#select-page").val();
        var jmlPerPage  = $("#select-jml-per-page").val();
        
        if(searchType == "" || searchVal == ""){
          loadDataCategory(1,10);
        } else {
          loadDataCategory(page,jmlPerPage);
        }
      });

      $("#select-jml-per-page").on('change', function(){
        $("#search-type").val("");
        $("#search-val").val("");
        var searchType  = $("#search-type").val();
        var searchVal   = $("#search-val").val();
        var page        = $("#select-page").val("1");
        var jmlPerPage  = $("#select-jml-per-page").val();
        
        loadDataCategory("1",jmlPerPage);
        
      });

      $("#select-category").prop('disabled', true);
      $("#val-sub-category").prop('disabled', true);
      $("#select-tahun-ajaran2").on('change', function(){
        var tahunAjaran = $("#select-tahun-ajaran2").val();
        if(tahunAjaran != "")
        {
          loadCategory();
        }
        else 
        {
          $("#select-category").html("<option value=''>--Pilih Kategori--</option>");
          $("#select-category").prop('disabled', true);
          $("#val-sub-category").prop('disabled', true);
          $("#val-sub-category").val("");
        }
      });

      $("#select-category").on('change', function(){
        var idKategori = $("#select-category").val();
        if(idKategori != "")
        {
          $("#val-sub-category").prop('disabled', false);
        }
        else 
        {
          $("#val-sub-category").prop('disabled', true);
          $("#val-sub-category").val("");
        }
      });

      $("#select-page").on('change', function(){
        $("#search-type").val("");
        $("#search-val").val("");
        var searchType  = $("#search-type").val();
        var searchVal   = $("#search-val").val();
        var page        = $("#select-page").val();
        var jmlPerPage  = $("#select-jml-per-page").val();
        
        loadDataCategory(page,jmlPerPage);
        
      });

      $("#btn-insert-category").click(function(){
        $("#area-insert-category").show();
        $("#area-sub-insert-category").hide();
      });

      $("#btn-insert-sub-category").click(function(){
        $("#area-sub-insert-category").show();
        $("#area-insert-category").hide();
      });

      $("#btn-save-category").click(function(){
        var namaKategori = $("#val-category").val();
        var tahunAjaran = $("#select-tahun-ajaran").val();

        if(namaKategori == "" || tahunAjaran == "")
        {
          alert("Nama Kategori dan Tahun Ajaran harus diisi.");
          return false;
        }
        else 
        {
          var konfirmasi = confirm("Apakah data yang akan disimpan sudah benar?");
          if(!konfirmasi) 
          {
            return false;
          }
        
            insertDataCategory(namaKategori,tahunAjaran);
        }
      });

      $("#btn-save-sub-category").click(function(){
        var idKategori      = $("#select-category").val();
        var namaSubKategori = $("#val-sub-category").val();

        if(idKategori == "" || namaSubKategori == "")
        {
          alert("Kategori dan Nama Sub Kategori harus diisi.");
          return false;
        }
        else 
        {
          var konfirmasi = confirm("Apakah data yang akan disimpan sudah benar?");
          if(!konfirmasi) 
          {
            return false;
          }
        
          insertDataSubCategory(idKategori,namaSubKategori);
        }
      });

    });

    function loadDataCategory(page,jmlPerPage){
      if(page == null || page == "" || page == 0)
      {
        page = 1;
      }

      if(jmlPerPage == null || jmlPerPage == "" || jmlPerPage == 0)
        {
        jmlPerPage = 10;
      }

      $.ajax({
        url: "../proses/proses_get_category_sub_category.php",
        type: "POST",
            data: {
              page        : page,
              jmlPerPage  : jmlPerPage
            },
        success: function(response){
          var jdata = JSON.parse(response);
          if(jdata.isSuccessful == true){
            var tb_subject    = "";
            var op_page     = "";
            var v_selected  = "";
            for(var i=0; i < jdata.data.length; i++){
              tb_subject += "<tr>";
              tb_subject += "<td>" + jdata.data[i].NOMOR + "</td>";
              tb_subject += "<td>" + jdata.data[i].TAHUN_PELAJARAN + "</td>";
              tb_subject += "<td>" + jdata.data[i].NAMA_KATEGORI_MAPEL + "</td>";
              tb_subject += "<td style='text-align: left;'>" + jdata.data[i].NAMA_SUB_KATEGORI_MAPEL + "</td>";
              // if(window.statusAdmin == "Y"){
                tb_subject += `<td style='text-align: left;'>
                                <a id='btn-delete-subject' href='#' onclick='deleteDataSubjectCategory("` + jdata.data[i].ID_KATEGORI_MAPEL + `","` + jdata.data[i].ID_SUB_KATEGORI_MAPEL + `")'>
                                    <img id='icon-act-delete` + i + `' style='display: inline-block' src='../assets/images/delete-red.png' width='30px' title='hapus'>
                                </a>
                            </td>`;
              // } else {
              //   tb_subject += `<td style='text-align: center;'>-</td>`;
              // }
              tb_subject += "</tr>";
            }

            $("#tb-subject").html(tb_subject);
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
            $("#tb-subject").html("<tr><td colspan='7'><center>-- Data tidak ditemukan --</center></td></tr>");
          }
        }
      });
    }

    function deleteDataSubjectCategory(idKategoriMapel, idSubKategoriMapel){
      if(idKategoriMapel == "" ){
        alert("Data Kategori Mata Pelajaran tidak ditemukan.");
        return false;
      }
      
      var konfirmasi = confirm("Apakah Anda yakin ingin menghapus data Kategori Mata Pelajaran?");
      if(!konfirmasi) {
        return false;
      }

      $.ajax({
        url: "../proses/proses_delete_subject_category.php",
        type: "POST",
            data: {
              idKategoriMapel   : idKategoriMapel,
              idSubKategoriMapel: idSubKategoriMapel
            },
        success: function(response){
          var jdata = JSON.parse(response);
          if(jdata.isSuccessful == true){
            alert(jdata.msg);
            
            window.location.reload();

          } else {
            alert(jdata.msg);
          }
        }
      });
    }

    function loadCategory()
    {
      var tahunAjaran2 = $("#select-tahun-ajaran2").val();
      if(tahunAjaran2 == "")
      {
        alert("Tahun Ajaran harus dipilih terlebih dahulu.");
        return false;
      }

      $.ajax({
          url: "../proses/proses_get_category.php",
          type: "POST",
              data: {
                tahunAjaran: tahunAjaran2
              },
          success: function(response){
            var jdata = JSON.parse(response);
            if(jdata.isSuccessful == true){
              var op_kategori = "<option value=''>--Pilih Kategori--</option>";
              for(var i=0; i < jdata.data.length; i++){
                op_kategori += "<option value='" + jdata.data[i].ID_KATEGORI_MAPEL + "'>" + jdata.data[i].NAMA_KATEGORI_MAPEL + "</option>";
              }
  
              $("#select-category").html(op_kategori);
              $("#select-category").prop('disabled', false);
  
            } else {
              alert(jdata.msg);
              $("#select-tahun-ajaran2").val("");
            }
          }
        });
    }

    function insertDataCategory(namaKategori,tahunAjaran)
    {
      $.ajax({
        url: "../proses/proses_insert_category.php",
        type: "POST",
            data: {
                namaKategori: namaKategori,
                tahunAjaran: tahunAjaran
            },
        success: function(response){
          var jdata = JSON.parse(response);
          if(jdata.isSuccessful == true){
            alert(jdata.msg);
            $("#val-category").val("");
            $("#area-insert-category").hide();
            loadDataCategory(1,10);
          } else {
            alert(jdata.msg);
          }
        }
      });
    }

    function insertDataSubCategory(idKategori,namaSubKategori)
    {
      $.ajax({
        url: "../proses/proses_insert_sub_category.php",
        type: "POST",
            data: {
                idKategori: idKategori,
                namaSubKategori: namaSubKategori
            },
        success: function(response){
          var jdata = JSON.parse(response);
          if(jdata.isSuccessful == true){
            alert(jdata.msg);
            $("#select-category").val("");
            $("#val-sub-category").val("");
            $("#area-sub-insert-category").hide();
            loadDataCategory(1,10);
            $("#select-tahun-ajaran2").val("");
          } else {
            alert(jdata.msg);
          }
        }
      });
    }
  </script>
</html>
