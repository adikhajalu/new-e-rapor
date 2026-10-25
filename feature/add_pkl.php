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
          <div class="col-span-1 xl:col-span-2 md:col-span-6">
            <div class="card table-card">
              <!--  -->
              <div class="card-body !p-10">
                <h4 class="text-left font-medium mb-4"><center>Tambah Absensi Siswa</center></h4><br>
                <!-- ...existing code... -->
                <div class="grid grid-cols-12 gap-3 mb-3">
                  <div class="col-span-3 flex items-center">
                    <label for="select-tahun-ajaran" class="font-semibold">Tahun Ajaran</label>
                  </div>
                  <div class="col-span-6 sm:col-span-6">
                    <select name="" id="select-tahun-ajaran" class="form-control" onchange="selectedTahunAjaran()"></select>
                  </div>
                </div>
                <div class="grid grid-cols-12 gap-3 mb-3">
                  <div class="col-span-3 flex items-center">
                    <label for="select-semester" class="font-semibold">Semester<span style="color:red;">*</span></label>
                  </div>
                  <div class="col-span-6 sm:col-span-6">
                    <select name="" id="select-semester" class="form-control" disabled onchange="selectedSemsester()">
                        <option value=""> -- Pilih Semester -- </option>
                        <option value="Gasal">Gasal</option>
                        <option value="Genap">Genap</option>
                    </select>
                  </div>
                </div>
                <div class="grid grid-cols-12 gap-3 mb-3">
                  <div class="col-span-3 flex items-center">
                    <label for="kelas" class="font-semibold">Kelas<span style="color:red;">*</span></label>
                  </div>
                  <div class="col-span-3 sm:col-span-3">
                    <div style="display: flex; gap: 10px; height: 40px; width:300px">
                      <select name="" id="kelas" class="form-control" onchange="selectedKelas()" disabled>
                          <option value="">-- Kelas --</option>
                          <!-- <option value="X">X</option>
                          <option value="XI">XI</option>
                          <option value="XII">XII</option> -->
                          <option value="XIII">XIII</option>
                      </select>
                      <strong style="font-size:30px" >.</strong>
                      <input type="number" id="urut" class="form-control" placeholder="Lokal" onchange="filledKelas()" disabled>
                    </div>
                  </div>
                </div>
                <div class="grid grid-cols-12 gap-3 mb-3">
                  <div class="col-span-3 flex items-center">
                    <label for="select-siswa" class="font-semibold">Nama Siswa<span style="color:red;">*</span></label>
                  </div>
                  <div class="col-span-6 sm:col-span-6">
                    <select name="" id="select-siswa" class="form-control" disabled>
                        <option value=""> -- Pilih Siswa -- </option></select>
                  </div>
                </div>
                <div class="grid grid-cols-12 gap-3 mb-3">
                  <div class="col-span-3 flex items-center">
                    <label for="mitra-du-di" class="font-semibold">Mitra DU/DI<span style="color:red;">*</span></label>
                  </div>
                  <div class="col-span-6 sm:col-span-6">
                    <input type="text" id="mitra-du-di" maxlength="150" class="form-control" placeholder="Mitra DU/DI">
                  </div>
                </div>
                <div class="grid grid-cols-12 gap-3 mb-3">
                  <div class="col-span-3 flex items-center">
                    <label for="lokasi" class="font-semibold">Lokasi<span style="color:red;">*</span></label>
                  </div>
                  <div class="col-span-6 sm:col-span-6">
                    <input type="text" id="lokasi" maxlength="150" class="form-control" placeholder="Lokasi">
                  </div>
                </div>
                <div class="mt-4 text-left">
                  <button id="btn-simpan" type="button" class="btn btn-primary mx-auto shadow-2xl">Simpan</button>
                  <button id="btn-tutup" type="button" class="btn btn-warning mx-auto shadow-2xl">Tutup</button>
                </div>
              </div>
              <!--  -->
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
      
        $("#select-semester").prop("style","background-color:#D3D3D3 !important;");
        $("#select-siswa").prop("style","background-color:#D3D3D3 !important;");
        $("#kelas").prop("style","background-color:#DCDCDC !important;");

        generateTahunAjaran2();

        $("#btn-simpan").click(function(){
            var siswaSplit      = $("#select-siswa").val();
            var nis             = siswaSplit ? siswaSplit.split('#')[0] : "";
            var namaSiswa       = siswaSplit ? siswaSplit.split('#')[1] : "";
            var kelas           = $("#kelas").val() + "." + $("#urut").val();
            var tahunAjaran     = $("#select-tahun-ajaran").val();
            var semester        = $("#select-semester").val();
            var mitraDuDi       = $("#mitra-du-di").val();
            var lokasi          = $("#lokasi").val();

            if(nis == "" || namaSiswa == "" || kelas == "" || tahunAjaran == "" || semester == "" || mitraDuDi == "" || lokasi == "")
            {
                alert("Nama Siswa, Kelas, Tahun Ajaran, Semester, Mitra DU/DI, Lokasi harus diisi.");
                return false;
            }
            else 
            {
                var konfirmasi = confirm("Apakah data yang akan disimpan sudah benar?");
                if(!konfirmasi) 
                {
                    return false;
                }
            
                insertDataPkl(nis,namaSiswa,kelas,tahunAjaran,semester,mitraDuDi,lokasi);
            }
        });

      
        $("#btn-tutup").click(function(){
           window.location.href = "praktik_kerja_lapangan";
        });

    });

    function insertDataPkl(nis,namaSiswa,kelas,tahunAjaran,semester,mitraDuDi,lokasi)
    {
      $.ajax({
        url: "../proses/proses_insert_pkl.php",
        type: "POST",
            data: {
                nis             : nis,
                namaSiswa       : namaSiswa,
                kelas           : kelas,
                tahunAjaran     : tahunAjaran,
                semester        : semester,
                mitraDuDi       : mitraDuDi,
                lokasi          : lokasi
            },
        success: function(response){
          var jdata = JSON.parse(response);
          if(jdata.isSuccessful == true){
            alert(jdata.msg);
            window.location.href = "praktik_kerja_lapangan";
          } else {
            alert(jdata.msg);
          }
        }
      });
    }
    
    function cekExistSiswa() 
    {
        var idMapelSplit   = $("#select-ekstrakurikuler").val();
        var idMapel        = idMapelSplit ? idMapelSplit.split('#')[0] : "";
        var namaMapel      = idMapelSplit ? idMapelSplit.split('#')[1] : "";
        var tahunAjaran = $("#select-tahun-ajaran").val();
        var semester       = $("#select-semester").val();
        var kelas          = $("#kelas").val();

        $.ajax({
            url: '../proses/proses_validasi_download_template_score.php',
            type: 'POST',
            data: {
                idMapel        : idMapel,
                namaMapel      : namaMapel,
                tahunAjaran : tahunAjaran,
                semester       : semester,
                kelas          : kelas
            },
            success: function(response){
                var data = JSON.parse(response);
                if(data.isSuccessful == true)
                {
                  loadSiswa(kelas,semester,tahunAjaran);
                }
                else
                {
                    alert(data.msg);
                }
            }
        });
    }

    function loadSiswa(kelas,semester,tahunAjaran) 
    {
        $.ajax({
            url: '../proses/proses_get_siswa_by_kelas.php',
            type: 'POST',
            data: {
                kelas          : kelas,
                semester       : semester,
                tahunAjaran : tahunAjaran
            },
            success: function(response){
                var data = JSON.parse(response);
                if(data.isSuccessful){
                    var options = '<option value="">-- Pilih Siswa --</option>';
                    data.data.forEach(function(row){
                        options += `<option value="${row.NIS}#${row.NAMA_SISWA}">${row.NIS} - ${row.NAMA_SISWA}</option>`;
                    });
                    $("#select-siswa").html(options).trigger('change');
                    $("#select-siswa").prop("style","background-color:white !important;");
                    $("#select-siswa").prop("disabled", false);
                } else {
                    alert(data.msg);
                    $("#select-siswa").prop("disabled", true);
                    $("#select-siswa").val("");
                    $("#select-siswa").prop("style","background-color:#D3D3D3 !important;");
                }
            }
        });
    }

    
    function selectedTahunAjaran() 
    {
        var tahunAjaran  = $("#select-tahun-ajaran").val();
        var semester        = $("#select-semester").val();
        var options         = '<option value="">-- Pilih Siswa --</option>';

        if(tahunAjaran !== ""){
            $("#select-semester").prop("disabled", false);
            $("#select-semester").prop("style","background-color:white !important;");
            $("#kelas").val('');
            $("#kelas").prop("style","background-color:#DCDCDC !important;");
            $("#select-siswa").html(options);
            $("#select-siswa").prop("disabled", true);
            $("#select-siswa").prop("style","background-color:#D3D3D3 !important;");
        } else {
            $("#select-semester").prop("disabled", true);
            $("#select-semester").val("");
            $("#select-semester").prop("style","background-color:#D3D3D3 !important;");
            $("#kelas").val('');
            $("#kelas").prop("style","background-color:#DCDCDC !important;");
            $("#urut").prop("disabled", true);
            $("#urut").val("");
            $("#urut").prop("style","background-color:#D3D3D3 !important;");
            $("#select-siswa").html(options);
            $("#select-siswa").prop("disabled", true);
            $("#select-siswa").prop("style","background-color:#D3D3D3 !important;");
        }

        if(semester !== ""){
            loadEkstrakurikuler();
            $("#select-ekstrakurikuler").prop("disabled", false);
            $("#select-ekstrakurikuler").prop("style","background-color:white !important;");
        }   
    }

    function selectedSemsester() {
        var semester  = $("#select-semester").val();
        var options   = '<option value="">-- Pilih Siswa --</option>';
        $("#kelas").val('');
        $("#kelas").prop("style","background-color:#DCDCDC !important;");
        $("#select-siswa").html(options);
        $("#select-siswa").prop("disabled", true);
        $("#select-siswa").prop("style","background-color:#D3D3D3 !important;");
        if(semester !== ""){
            $("#kelas").prop("disabled", false);
            $("#kelas").prop("style","background-color:white !important;");
        } else {
            $("#kelas").prop("disabled", true);
            $("#kelas").val("");
            $("#kelas").prop("style","background-color:#DCDCDC !important;");
            $("#urut").prop("disabled", true);
            $("#urut").val("");
            $("#urut").prop("style","background-color:#D3D3D3 !important;");
        }
    }

    function selectedKelas() {
        var kelas = $("#kelas").val();
        if(kelas !== ""){
            $("#urut").prop("disabled", false);
            $("#urut").prop("style","background-color:white !important;");

            if($("#urut").val() !== ""){
                var kelasFull = kelas.toUpperCase() + "." + $("#urut").val();
                var semester = $("#select-semester").val();
                var tahunAjaran = $("#select-tahun-ajaran").val();
                loadSiswa(kelasFull,semester,tahunAjaran);
            }
        } else {
            $("#urut").prop("disabled", true);
            $("#urut").val("");
            $("#urut").prop("style","background-color:#D3D3D3 !important;");
        }
    }

    function filledKelas()
    {
        var kelas       = $("#kelas").val();
        var urut        = $("#urut").val();
        var tahunAjaran = $("#select-tahun-ajaran").val();
        var semester    = $("#select-semester").val();

        if(kelas !== "" && urut !== ""){
            var kelasFull = kelas.toUpperCase() + "." + urut;
            loadSiswa(kelasFull,semester,tahunAjaran);
        } else {
            $("#select-siswa").prop("disabled", true);
            $("#select-siswa").val("");
            $("#select-siswa").prop("style","background-color:#D3D3D3 !important;");
        }
    }

  </script>
</html>
