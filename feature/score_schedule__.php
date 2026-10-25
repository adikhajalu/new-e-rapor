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
                <center><h4>Jadwal Pengisian Nilai</h4></center><br><br><br>
                <!-- [ breadcrumb ] end -->
                <div id="input-area" style="width: 1000px;">
                    <div class="grid grid-cols-12 gap-3 mb-3">
                        <div class="col-span-3 flex items-center">
                            <label for="kelas" class="font-semibold">Jadwal Pengisian Nilai <span style="color:red;">*</span></label>
                        </div>
                        <div class="col-span-3 sm:col-span-3">
                            <div style="display: flex; gap: 10px; height: 40px; width:300px">
                            <input type="date" id="tgl-awal" class="form-control" placeholder="Lokal">
                            <strong style="font-size:25px">-</strong>
                            <input type="date" id="tgl-akhir" class="form-control" placeholder="Lokal">
                            <button id="btn-ubah-tgl" type="button" class="btn btn-primary">Ubah</button>
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
    //   loadDataAbsensi('','','','',1,10);

      $("#btn-search-siswa").click(function(){
        var searchType            = $("#search-type").val();
        var searchVal             = $("#search-val").val();
        var page                  = $("#select-page").val();
        var jmlPerPage            = $("#select-jml-per-page").val();
        var searchSemester        = $("#search-semester").val();
        var searchTahunAjaran  = $("#search-tahun-pelajaran").val();
        
        if((searchType == "" || searchVal == "") && searchSemester == "" && searchTahunAjaran == ""){
          loadDataAbsensi('','','','',1,10);
        } else {
          loadDataAbsensi(searchType,searchVal,searchSemester,searchTahunAjaran,page,jmlPerPage);
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
        
        loadDataAbsensi(searchType,searchVal,searchSemester,searchTahunAjaran,page,jmlPerPage);
        
      });

    });

    function loadDataAbsensi(searchType,searchVal,searchSemester,searchTahunAjaran,page,jmlPerPage){
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
              searchTahunAjaran  : searchTahunAjaran
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
              tb_siswa += "<td style='text-align: center;'>" + jdata.data[i].NIP + "</td>";
              tb_siswa += "<td>" + jdata.data[i].NAMA_GURU + "</td>";
              tb_siswa += "<td style='text-align: center;'>" + jdata.data[i].KELAS + "</td>";
              tb_siswa += "<td style='text-align: center;'>" + jdata.data[i].TAHUN_PELAJARAN + "</td>";
              tb_siswa += `<td style='text-align: center;'>
                              <a href='ubah_absensi?datasent=` + encodeURIComponent(encryptJSON({
                                  nis: jdata.data[i].ID_MAPEL_SEMESTER
                              }, '<?=$session_key?>')) + `'>
                                  <img style='display: inline-block' src='../assets/images/edit-orange.png' width='30px' title='ubah'>
                              </a>
                              <a id='btn-delete-siswa' href='#' onclick='deleteDataAbsensiSiswa("` + jdata.data[i].ID_MAPEL_SEMESTER + `")'>
                                  <img style='display: inline-block' src='../assets/images/delete-red.png' width='30px' title='hapus'>
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
            $("#tb-siswa").html("<tr><td colspan='8'><center>-- Data tidak ditemukan --</center></td></tr>");
          }
        }
      });
    }

    function deleteDataAbsensiSiswa(nis,kelas,semester,tahunAjaran){
      if(nis == ""  || kelas == "" || tahunAjaran == ""  || semester == "" ){
        alert("Data Penilaian tidak ditemukan.");
        return false;
      }
      
      var konfirmasi = confirm("Apakah Anda yakin ingin menghapus data Penilaian?");
      if(!konfirmasi) {
        return false;
      }

      $.ajax({
        url: "../proses/proses_delete_absensi_siswa.php",
        type: "POST",
            data: {
              nis               : nis,
              kelas             : kelas,
              tahunAjaran    : tahunAjaran,
              semester          : semester
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
            
            loadDataAbsensi(searchType,searchVal,searchSemester,searchTahunAjaran,page,jmlPerPage);

          } else {
            alert("Gagal memuat data penilaian.");
          }
        }
      });
    }

  </script>
</html>
