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
                <center><h4>Jadwal Penilaian</h4></center><br>
                <!-- [ breadcrumb ] start -->
                <div class="page-header" style="margin-top: -20px;">
                  <div class="me-auto pc-mob-drp">
                    <ul class="inline-flex *:min-h-header-height *:inline-flex *:items-center" id="add-data-area">
                      <li class="dropdown pc-h-item">
                        <a role="button" id="btn-insert-schedule">
                          <i data-feather="plus" style='display: inline-block'></i> Tambah Jadwal
                        </a>
                      </li>
                    </ul>
                  </div>
                </div>
                <br>
                <!-- [ breadcrumb ] end -->
                <div id="search-area" style="width: 1200px;">
                  <div class="flex flex-wrap gap-2" id="area-insert-schedule" style="display: none;">
                    <div style="display: flex; gap: 10px;">
                      <label for="select-tahun-ajaran">Tahun Ajaran</label>
                      : <select name="" id="select-tahun-ajaran" class="form-control"><option value="">--Tahun Ajaran--</option></select>
                      <label for="select-semester">Semester</label>
                      : <select name="" id="select-semester" class="form-control"><option value="">--Semester--</option><option value="Gasal">Gasal</option><option value="Genap">Genap</option></select>
                      <label for="tgl-awal">Tanggal Awal</label>
                      : <input type="date" id="tgl-awal" class="form-control" placeholder="Tanggal Awal">
                      <label for="tgl-akhir">Tanggal Akhir</label>
                      : <input type="date" id="tgl-akhir" class="form-control" placeholder="Tanggal Akhir">
                      <button id="btn-save-schedule" type="button" class="btn btn-primary">Simpan</button>
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
                        <td>Tanggal Awal</td>
                        <td>Tanggal Akhir</td>
                        <td>Status Jadwal</td>
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
      loadScoreSchedule(1,10);

      $("#select-jml-per-page").on('change', function(){
        var page        = $("#select-page").val("1");
        var jmlPerPage  = $("#select-jml-per-page").val();
        
        loadScoreSchedule("1",jmlPerPage);
        
      });

      $("#select-page").on('change', function(){
        var page        = $("#select-page").val();
        var jmlPerPage  = $("#select-jml-per-page").val();
        
        loadScoreSchedule(page,jmlPerPage);
        
      });

      $("#btn-insert-schedule").click(function(){
        $("#area-insert-schedule").show();
      });

      $("#btn-save-schedule").click(function(){
        var tglAwal     = $("#tgl-awal").val();
        var tglAkhir    = $("#tgl-akhir").val();
        var tahunAjaran = $("#select-tahun-ajaran").val();
        var semester    = $("#select-semester").val();

        if(tglAwal == "" || tglAkhir == "" || tahunAjaran == "" || semester == "")
        {
          alert("Tahun Ajaran, Semester dan Tanggal Penilaian harus diisi.");
          return false;
        }
        else 
        {
          if(tglAwal > tglAkhir)
          {
            alert("Tanggal Awal harus lebih kecil dari Tanggal Akhir.");
            return false;
          }
          
          var konfirmasi = confirm("Apakah data yang akan disimpan sudah benar?");
          if(konfirmasi === true) 
          {
            insertDataScoreSchedule(tglAwal,tglAkhir,tahunAjaran,semester);
          }
        }
      });

    });

    function loadScoreSchedule(page,jmlPerPage){
      if(page == null || page == "" || page == 0)
      {
        page = 1;
      }

      if(jmlPerPage == null || jmlPerPage == "" || jmlPerPage == 0)
        {
        jmlPerPage = 10;
      }

      $.ajax({
        url: "../proses/proses_get_score_schedule.php",
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
              tb_subject += "<td style='text-align: left;'>" + jdata.data[i].NOMOR + "</td>";
              tb_subject += "<td style='text-align: center;'>" + jdata.data[i].TAHUN_PELAJARAN + "</td>";
              tb_subject += "<td style='text-align: center;'>" + jdata.data[i].SEMESTER + "</td>";
              tb_subject += "<td style='text-align: center;'>" + jdata.data[i].TGL_AWAL_VW + "</td>";
              tb_subject += "<td style='text-align: center;'>" + jdata.data[i].TGL_AKHIR_VW + "</td>";
              tb_subject += "<td style='text-align: center;'>" + jdata.data[i].STATUS_JADWAL + "</td>";
              tb_subject += `<td style='text-align: center;'>
                              <a id='btn-delete-subject' href='#' onclick='deleteDataScoreSchedule("` + jdata.data[i].TAHUN_PELAJARAN + `","` + jdata.data[i].SEMESTER + `","` + jdata.data[i].NO_URUT + `")'>
                                  <img id='icon-act-delete` + i + `' style='display: inline-block' src='../assets/images/delete-red.png' width='30px' title='hapus'>
                              </a>
                          </td>`;
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

    function deleteDataScoreSchedule(tahunPelajaran, semester, noUrut){
      var konfirmasi = confirm("Apakah Anda yakin ingin menghapus data Jadwal Penilaian?");
      if(!konfirmasi) 
      {
        return false;
      }

      $.ajax({
        url: "../proses/proses_delete_score_schedule.php",
        type: "POST",
            data: {
              tahunPelajaran  : tahunPelajaran,
              semester        : semester,
              noUrut          : noUrut
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

    function insertDataScoreSchedule(tglAwal,tglAkhir,tahunAjaran,semester)
    {
      $.ajax({
        url: "../proses/proses_insert_score_schedule.php",
        type: "POST",
            data: {
                tglAwal     : tglAwal,
                tglAkhir    : tglAkhir,
                tahunAjaran : tahunAjaran,
                semester    : semester
            },
        success: function(response){
          var jdata = JSON.parse(response);
          if(jdata.isSuccessful == true){
            alert(jdata.msg);
            loadScoreSchedule(1,10);
          } else {
            alert(jdata.msg);
          }
        }
      });
    }

  </script>
</html>
