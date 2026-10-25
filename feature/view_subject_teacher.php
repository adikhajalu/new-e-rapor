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
                <center><h4>Manajemen Guru Mata Pelajaran</h4></center><br>
                <!-- [ breadcrumb ] start -->
                <div class="page-header" style="margin-top: -20px;">
                  <div class="me-auto pc-mob-drp">
                    <ul class="inline-flex *:min-h-header-height *:inline-flex *:items-center">
                      <li class="dropdown pc-h-item">
                        <a class="pc-head-link dropdown-toggle me-0" data-pc-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                          <i data-feather="plus" style='display: inline-block'></i> Tambah Guru Mapel
                        </a>
                        <div class="dropdown-menu pc-h-dropdown drp-search">
                          <form class="px-2 py-1">
                            <a type="button" href="satuan_guru_mapel" id="btn-insert-subject-teacher" type="button" class="btn btn-secondary">&nbsp;&nbsp;&nbsp;Satuan&nbsp;&nbsp;&nbsp;</a><br><br>
                            <a type="button" href="upload_guru_mapel" id="btn-upload-subject-teacher" type="button" class="btn btn-secondary">&nbsp;&nbsp;&nbsp;&nbsp;Massal&nbsp;&nbsp;&nbsp;&nbsp;</a>
                          </form>
                        </div>
                      </li>
                    </ul>
                  </div>
                </div>
                <!-- [ breadcrumb ] end -->
                <div id="search-area" style="width: 1000px;">
                  <div style="display: flex; align-items: center; gap: 10px;">
                    <span>Cari : </span>
                    <div style="width: 150px;">
                      <select id="search-type" class="form-control" onchange="placeholderSearch(this.value)">
                        <option value="">--Pencari--</option>
                        <option value="NIP">NIP</option>
                        <option value="NAMA_GURU">Nama Guru</option>
                        <option value="KELAS">Kelas</option>
                        <option value="NAMA_MAPEL">Nama Mata Pelajaran</option>
                      </select>
                    </div>
                    <div style="display: flex; gap: 10px;">
                      <input id="search-val" style="width: 160px;" type="text" class="form-control" placeholder="Pilih Pencari..." />
                    </div>
                    <span>Lainnya : </span>
                    <div style="display: flex; gap: 10px;">
                      <input id="search-semester" style="width: 120px;" type="text" class="form-control" placeholder="Semester . . ." />
                    </div>
                    <div style="display: flex; gap: 10px;">
                      <input id="search-tahun-pelajaran" style="width: 165px;" type="text" class="form-control" placeholder="Tahun Ajaran . . ." />
                      <button id="btn-search-guru-mapel" type="button" class="btn btn-primary">Cari</button>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-hover">
                    <thead>
                      <tr style="text-align: center; font-weight: bold;">
                        <td>No</td>
                        <td>Tahun Ajaran</td>
                        <td>Semester</td>
                        <td>Kelas</td>
                        <td class="breakword">Mata Pelajaran</td>
                        <td>NIP</td>
                        <td class="breakword">Nama Guru</td>
                        <td>Action</td>
                      </tr>
                    </thead>
                    <tbody id="tb-guru-mapel"></tbody>
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
      loadDataGuruMapel('','','','',1,10);

      $("#btn-search-guru-mapel").click(function(){
        var searchType            = $("#search-type").val();
        var searchVal             = $("#search-val").val();
        var page                  = $("#select-page").val();
        var jmlPerPage            = $("#select-jml-per-page").val();
        var searchSemester        = $("#search-semester").val();
        var searchTahunAjaran  = $("#search-tahun-pelajaran").val();
        
        if(searchType == "" && searchVal != "")
        {
          alert("Silakan pilih pencari terlebih dahulu.");
          return false;
        }
        
        if((searchType == "" || searchVal == "") && searchSemester == "" && searchTahunAjaran == ""){
          loadDataGuruMapel('','','','',1,10);
        } else {
          loadDataGuruMapel(searchType,searchVal,searchSemester,searchTahunAjaran,page,jmlPerPage);
        }
      });

      $("#select-jml-per-page, #select-page").on('change', function(){
        $("#search-type").val("");
        $("#search-val").val("");
        $("#search-semester").val("");
        $("#search-tahun-pelajaran").val("");
        var searchType            = $("#search-type").val();
        var searchVal             = $("#search-val").val();
        var page                  = $("#select-page").val();
        var jmlPerPage            = $("#select-jml-per-page").val();
        var searchSemester        = $("#search-semester").val();
        var searchTahunAjaran  = $("#search-tahun-pelajaran").val();
        
        loadDataGuruMapel(searchType,searchVal,searchSemester,searchTahunAjaran,page,jmlPerPage);
        
      });

    });

    function loadDataGuruMapel(searchType,searchVal,searchSemester,searchTahunAjaran,page,jmlPerPage){
      if(page == null || page == "" || page == 0)
      {
        page = 1;
      }

      if(jmlPerPage == null || jmlPerPage == "" || jmlPerPage == 0)
        {
        jmlPerPage = 10;
      }

      $.ajax({
        url: "../proses/proses_get_subject_teacher_list.php",
        type: "POST",
            data: {
              searchType            : searchType,
              searchVal             : searchVal,
              page                  : page,
              jmlPerPage            : jmlPerPage,
              searchSemester        : searchSemester,
              searchTahunAjaran     : searchTahunAjaran
            },
        success: function(response){
          var jdata = JSON.parse(response);
          if(jdata.isSuccessful == true){
            var tb_guru_mapel    = "";
            var op_page     = "";
            var v_selected  = "";
            for(var i=0; i < jdata.data.length; i++){
              tb_guru_mapel += "<tr>";
              tb_guru_mapel += "<td>" + jdata.data[i].NOMOR + "</td>";
              tb_guru_mapel += "<td style='text-align: center;'>" + jdata.data[i].TAHUN_PELAJARAN + "</td>";
              tb_guru_mapel += "<td style='text-align: center;'>" + jdata.data[i].SEMESTER + "</td>";
              tb_guru_mapel += "<td style='text-align: center;'>" + jdata.data[i].KELAS + "</td>";
              tb_guru_mapel += "<td class='breakword' width='20%'>" + jdata.data[i].NAMA_MAPEL + "</td>";
              tb_guru_mapel += "<td style='text-align: center;'>" + jdata.data[i].NIP + "</td>";
              tb_guru_mapel += "<td class='breakword' width='20%'>" + jdata.data[i].NAMA_GURU + "</td>";
              tb_guru_mapel += `<td style='text-align: center;'>
                              <a href='ubah_guru_mapel?datasent=` + encodeURIComponent(encryptJSON({
                                  idMapelSemester : jdata.data[i].ID_MAPEL_SEMESTER,
                                  tahunAjaran     : jdata.data[i].TAHUN_PELAJARAN,
                                  semester        : jdata.data[i].SEMESTER,
                                  kelas           : jdata.data[i].KELAS,
                                  namaMapel       : jdata.data[i].NAMA_MAPEL,
                                  nip             : jdata.data[i].NIP
                              }, '<?=$session_key?>')) + `'>
                                  <img style='display: inline-block' src='../assets/images/edit-orange.png' width='30px' title='ubah'>
                              </a>
                              <a id='btn-delete-guru-mapel' href='#' onclick='deleteDataGuruMapel("` + jdata.data[i].NIP + `","` + jdata.data[i].KELAS + `","` + jdata.data[i].ID_MAPEL_SEMESTER + `")'>
                                  <img style='display: inline-block' src='../assets/images/delete-red.png' width='30px' title='hapus'>
                              </a>
                          </td>`;
              tb_guru_mapel += "</tr>";
            }

            $("#tb-guru-mapel").html(tb_guru_mapel);
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
            $("#tb-guru-mapel").html("<tr><td colspan='8'><center>-- Data tidak ditemukan --</center></td></tr>");
          }
        }
      });
    }

    function deleteDataGuruMapel(nip,kelas,idMapelSemester){
      if(nip == ""  || kelas == "" || idMapelSemester == "" ){
        alert("Data Guru Mata Pelajaran tidak ditemukan.");
        return false;
      }
      
      var konfirmasi = confirm("Apakah Anda yakin ingin menghapus data Guru Mata Pelajaran?");
      if(!konfirmasi) {
        return false;
      }

      $.ajax({
        url: "../proses/proses_delete_guru_mapel.php",
        type: "POST",
            data: {
              nip               : nip,
              kelas             : kelas,
              idMapelSemester   : idMapelSemester
            },
        success: function(response){
          var jdata = JSON.parse(response);
          if(jdata.isSuccessful == true){
            alert(jdata.msg);

            $("#search-type").val("");
            $("#search-val").val("");
            $("#search-semester").val("");
            $("#search-tahun-pelajaran").val("");
            var searchType            = $("#search-type").val();
            var searchVal             = $("#search-val").val();
            var page                  = $("#select-page").val();
            var jmlPerPage            = $("#select-jml-per-page").val();
            var searchSemester        = $("#search-semester").val();
            var searchTahunAjaran  = $("#search-tahun-pelajaran").val();
            
            loadDataGuruMapel(searchType,searchVal,searchSemester,searchTahunAjaran,page,jmlPerPage);

          } else {
            alert(jdata.msg);
          }
        }
      });
    }

  </script>
</html>
