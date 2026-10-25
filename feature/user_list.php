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
                <center><h4>Daftar Pengguna</h4></center><br>
                <!-- [ breadcrumb ] start -->
                <div class="page-header" style="margin-top: -20px;">
                  <div class="me-auto pc-mob-drp">
                    <ul class="inline-flex *:min-h-header-height *:inline-flex *:items-center">
                      <li class="dropdown pc-h-item">
                        <a class="pc-head-link me-0" id="btn-insert-user" href="#" role="button"
                          aria-haspopup="false" aria-expanded="false">
                          <i data-feather="plus" style='display: inline-block'></i> Tambah Data Pengguna
                        </a>
                      </li>
                    </ul>
                  </div>
                </div>
                <div style="width: 1500px; display: none;" id="insert-area">
                  <div class="flex flex-row gap-2" >
                    <div style="display: flex; gap: 10px; height: 40px;">
                      <span>Tambah Data:</span>
                      <input id="nip" type="text" class="form-control" style="width: 180px;" placeholder="NIP" />
                      <input id="nama" type="text" class="form-control" style="width: 200px;" placeholder="Nama" />
                      <input id="posisi-jabatan" type="text" style="width: 200px;" class="form-control" placeholder="Posisi / Jabatan" />
                      <select id="status-karyawan" class="form-control" style="width: 130px;">
                        <option value="">-- Status --</option>
                        <option value="STATUS_GURU">Guru</option>
                        <option value="STATUS_KESISWAAN">Kesiswaan</option>
                        <option value="STATUS_KURIKULUM">Kurikulum</option>
                      </select>
                      <input id="status-kepala-sekolah" type="checkbox"/> <span style="width: 120px;">Status Kepala Sekolah</span> 
                      <!-- <input id="status-admin" type="checkbox"/> <span style="width: 80px;">Status Admin</span>  -->
                      <button id="btn-simpan" type="button" class="btn btn-primary">Simpan</button>
                    </div>
                  </div><br>
                </div>
                <!-- [ breadcrumb ] end -->
                <div id="search-area" style="width: 500px;">
                  <div style="display: flex; align-items: center; gap: 10px;">
                    <span>Cari : </span>
                    <div style="width: 150px;">
                      <select id="search-type" class="form-control" onchange="placeholderSearch(this.value)">
                        <option value="">--Pencari--</option>
                        <option value="NIP">NIP</option>
                        <option value="NAMA">Nama</option>
                        <option value="POSISI_JABATAN">Posisi/Jabatan</option>
                      </select>
                    </div>
                    <div style="display: flex; gap: 10px;">
                      <input id="search-val" type="text" class="form-control" placeholder="Pilih Pencari..." />
                      <button id="btn-search-user" type="button" class="btn btn-primary">Cari</button>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-hover">
                    <thead>
                      <tr style="text-align: center; font-weight: bold;">
                        <td width="5%">No</td>
                        <td width="22%">NIP</td>
                        <td width="32%">Nama</td>
                        <td width="7%">Posisi</td>
                        <td width="12%">Status Aktif</td>
                        <td width="12%">Status Karyawan</td>
                        <td width="12%">Status Kepsek</td>
                        <td width="12%">Status Admin</td>
                        <td width="10%">Action</td>
                      </tr>
                    </thead>
                    <tbody id="tb-user"></tbody>
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
      loadDataUser('','',1,10);

      // if(window.statusAdmin == 'T'){
      //   $("#btn-insert-user").hide();
      // }

      $("#btn-search-user").click(function(){
        var searchType  = $("#search-type").val();
        var searchVal   = $("#search-val").val();
        var page        = $("#select-page").val();
        var jmlPerPage  = $("#select-jml-per-page").val();
        
        if(searchType == "" && searchVal != "")
        {
          alert("Silakan pilih pencari terlebih dahulu.");
          return false;
        }
        
        if(searchType == "" || searchVal == ""){
          loadDataUser('','',1,10);
        } else {
          loadDataUser(searchType,searchVal,page,jmlPerPage);
        }
      });

      $("#btn-insert-user").click(function(){
        $("#nip").val("");
        $("#nama").val("");
        $("#posisi-jabatan").val("");
        $("#status-kepala-sekolah").prop("checked", false);
        $("#insert-area").show();
      });

      $("#btn-simpan").click(function(){
        var nip            = $("#nip").val();
        var nama           = $("#nama").val();
        var posisiJabatan  = $("#posisi-jabatan").val();
        var statusKepSek   = "T";
        var statusAdmin    = "T";
        var statusKaryawan = $("#status-karyawan").val();

        if($("#status-kepala-sekolah").is(":checked")){
          statusKepSek = "Y";
        } else {
          statusKepSek = "T";
        }
        
        if($("#status-admin").is(":checked")){
          statusAdmin = "Y";
        } else {
          statusAdmin = "T";
        }

        if(nip == "" || nama == "" || posisiJabatan == "" || statusKaryawan == ""){
          alert("NIP, Nama, Posisi/Jabatan dan Status wajib diisi.");
          return false;
        }

        insertDataUser(nip,nama,posisiJabatan,statusKepSek,statusAdmin,statusKaryawan);
      });

      $("#status-kepala-sekolah").change(function() {
        if(this.checked) {
          cekStatusKepalaSekolah();
        }
      });

      $("#status-admin").change(function() {
        if(this.checked) {
          alert("Status Admin akan memberikan hak akses penuh terhadap sistem untuk melakukan manajemen Guru, Siswa, Mata Pelajaran, Reset Password dll. Pastikan anda sudah yakin memberikan status ini.");
        }
      });

      $("#select-jml-per-page").on('change', function(){
        $("#search-type").val("");
        $("#search-val").val("");
        var searchType  = $("#search-type").val();
        var searchVal   = $("#search-val").val();
        var page        = $("#select-page").val("1");
        var jmlPerPage  = $("#select-jml-per-page").val();
        
        loadDataUser(searchType,searchVal,"1",jmlPerPage);
        
      });

      $("#select-jml-per-page, #select-page").on('change', function(){
        $("#search-type").val("");
        $("#search-val").val("");
        var searchType  = $("#search-type").val();
        var searchVal   = $("#search-val").val();
        var page        = $("#select-page").val();
        var jmlPerPage  = $("#select-jml-per-page").val();
        
        loadDataUser(searchType,searchVal,page,jmlPerPage);
        
      });

    });

    function loadDataUser(searchType,searchVal,page,jmlPerPage){
      if(page == null || page == "" || page == 0)
      {
        page = 1;
      }

      if(jmlPerPage == null || jmlPerPage == "" || jmlPerPage == 0)
        {
        jmlPerPage = 10;
      }

      $.ajax({
        url: "../proses/proses_get_user_list.php",
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
            var tb_user    = "";
            var op_page       = "";
            var v_selected    = "";
            var statusAktif   = "";
            var statusKepsek  = "";
            var statusAdmin   = "";
            var statusKaryawan= "";
            for(var i=0; i < jdata.data.length; i++)
            {
              statusKaryawan= "-";

              if(jdata.data[i].STATUS_AKTIF == "Y"){
                statusAktif = "<center><img src='../assets/images/check.png' width='30px' title='Aktif'></center>";
              } else {
                statusAktif = "<center><img src='../assets/images/cross.png' width='30px' title='Non Aktif'></center>";
              }
              if(jdata.data[i].STATUS_KEPALA_SEKOLAH == "Y"){
                statusKepsek = "<center><img src='../assets/images/check.png' width='30px' title='Kepala Sekolah'></center>";
              } else {
                statusKepsek = "<center><img src='../assets/images/cross.png' width='30px' title='Non Kepala Sekolah'></center>";
              }
              if(jdata.data[i].STATUS_ADMIN == "Y"){
                statusAdmin = "<center><img src='../assets/images/check.png' width='30px' title='Admin'></center>";
              } else {
                statusAdmin = "<center><img src='../assets/images/cross.png' width='30px' title='Non Admin'></center>";
              }
              if(jdata.data[i].STATUS_GURU == "Y")
              {
                statusKaryawan = "Guru";
              }
              else if(jdata.data[i].STATUS_KESISWAAN == "Y")
              {
                statusKaryawan = "Kesiswaan";
              }
              else if(jdata.data[i].STATUS_KURIKULUM == "Y")
              {
                statusKaryawan = "Kurikulum";
              }
              tb_user += "<tr>";
              tb_user += "<td>" + jdata.data[i].NOMOR + "</td>";
              tb_user += "<td style='text-align: center;'>" + jdata.data[i].NIP + "</td>";
              tb_user += "<td>" + jdata.data[i].NAMA + "</td>";
              tb_user += "<td style='text-align: left;'>" + jdata.data[i].POSISI_JABATAN + "</td>";
              tb_user += "<td>" + statusAktif + "</td>";
              tb_user += "<td style='text-align: center;'>" + statusKaryawan + "</td>";
              tb_user += "<td>" + statusKepsek + "</td>";
              tb_user += "<td>" + statusAdmin + "</td>";
              
              // if(window.statusAdmin == 'Y'){
                tb_user += `<td style='text-align: center;'>
                                <a href='ubah_pengguna?datasent=` + encodeURIComponent(encryptJSON({
                                    nip: jdata.data[i].NIP
                                }, '<?=$session_key?>')) + `'>
                                    <img style='display: inline-block' src='../assets/images/edit-orange.png' width='30px' title='ubah'>
                                </a>
                                <a id='btn-delete-user' href='#' onclick='deleteDataUser("` + jdata.data[i].NIP + `")'>
                                    <img style='display: inline-block' src='../assets/images/delete-red.png' width='30px' title='hapus'>
                                </a>
                            </td>`;
              // } else {
              //   tb_user += `<td style='text-align: center;'> - </td>`;
              // }

              tb_user += "</tr>";
            }

            $("#tb-user").html(tb_user);
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
            $("#tb-user").html("<tr><td colspan='7'><center>-- Data tidak ditemukan --</center></td></tr>");
          }
        }
      });
    }

    function deleteDataUser(nip){
      if(nip == ""){
        alert("Data tidak ditemukan.");
        return false;
      }
      
      var konfirmasi = confirm("Apakah Anda yakin ingin menghapus data berikut?");
      if(!konfirmasi) {
        return false;
      }

      $.ajax({
        url: "../proses/proses_delete_user.php",
        type: "POST",
            data: {
              nip           : nip
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
            
            loadDataUser(searchType,searchVal,page,jmlPerPage);

          } else {
            alert(jdata.msg);
          }
        }
      });
    }

    function cekStatusKepalaSekolah(){
      $.ajax({
        url: "../proses/proses_cek_status_kepala_sekolah.php",
        type: "POST",
            data: {
              statusKepalaSekolah : "Y"
            },
        success: function(response){
          var jdata = JSON.parse(response);
          if(jdata.isSuccessful == true){
            if(jdata.data.EXIST_KEPALA_SEKOLAH == "Y"){
              $("#status-kepala-sekolah").prop("checked", false);
              alert("Status Kepala Sekolah melekat pada " + jdata.data[0].NAMA + " ("+jdata.data[0].NIP+"). Pastikan hanya ada satu Kepala Sekolah. Jika ingin mengganti Kepala Sekolah, silakan hapus status Kepala Sekolah pada pengguna yang bersangkutan terlebih dahulu kemudian tambahkan kepada user terbaru.");
            } 
          } 
        }
      });
    }


    
    function insertDataUser(nip,nama,posisiJabatan,statusKepSek,statusAdmin,statusKaryawan){
      
      var konfirmasi = confirm("Apakah Anda yakin ingin merambahkan data berikut?");
      if(!konfirmasi) {
        return false;
      }

      $.ajax({
        url: "../proses/proses_insert_user.php",
        type: "POST",
            data: {
              nip           : nip,
              posisiJabatan : posisiJabatan,
              nama          : nama,
              statusKepSek  : statusKepSek,
              statusAdmin   : statusAdmin,
              statusKaryawan: statusKaryawan
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


  </script>
</html>
