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
                <h4 class="text-left font-medium mb-4"><center>Penilaian Ekstrakurikuler</center></h4><br>
                <div class="grid grid-cols-12 gap-3 mb-3">
                  <div class="col-span-3 flex items-center">
                    <label for="select-tahun-ajaran" class="font-semibold">Tahun Ajaran<span style="color:red;">*</span></label>
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
                    <select name="" id="select-semester" class="form-control" onchange="selectedSemsester()" disabled>
                        <option value="">-- Pilih Semester --</option>
                        <option value="Gasal">Gasal</option>
                        <option value="Genap">Genap</option>
                    </select>
                  </div>
                </div>
                <div class="grid grid-cols-12 gap-3 mb-3">
                  <div class="col-span-3 flex items-center">
                    <label for="select-ekstrakurikuler" class="font-semibold">Ekstrakurikuler<span style="color:red;">*</span></label>
                  </div>
                  <div class="col-span-6 sm:col-span-6">
                    <select name="" id="select-ekstrakurikuler" class="form-control" onchange="selectedEkstrakurikuler()" disabled>
                        <option value="">-- Pilih Ekstrakurikuler --</option>
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
                          <option value="X">X</option>
                          <option value="XI">XI</option>
                          <option value="XII">XII</option>
                          <option value="XIII">XIII</option>
                      </select>
                      <strong style="font-size:30px" >.</strong>
                      <input type="number" id="urut" class="form-control" placeholder="Lokal" onchange="filledKelas()" disabled>
                    </div>
                  </div>
                </div>
                <div class="grid grid-cols-12 gap-3 mb-3">
                  <div class="col-span-3 flex items-center">
                    <label for="select-siswa" class="font-semibold">Siswa<span style="color:red;">*</span></label>
                  </div>
                  <div class="col-span-6 sm:col-span-6">
                    <select name="" id="select-siswa" class="form-control" disabled onchange="selectedSiswa()">
                        <option value="">-- Pilih Siswa --</option>
                    </select>
                  </div>
                </div>
                <div class="grid grid-cols-12 gap-3 mb-3">
                  <div class="col-span-3 flex items-center">
                    <label for="nilai-akhir" class="font-semibold">Nilai Akhir<span style="color:red;">*</span></label>
                  </div>
                  <div class="col-span-6 sm:col-span-6">
                    <select name="" id="nilai-akhir" class="form-control" disabled>
                        <option value="">-- Pilih Nilai --</option>
                        <option value="A (Baik Sekali)">A (Baik Sekali)</option>
                        <option value="B (Baik)">B (Baik)</option>
                        <option value="C (Cukup)">C (Cukup)</option>
                        <option value="D (Kurang)">D (Kurang)</option>
                        <option value="E (Kurang Sekali)">E (Kurang Sekali)</option>
                    </select>
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
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    

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
        generateTahunAjaran2();
        $("#select-semester").prop("style","background-color:#D3D3D3 !important;");
        $("#select-ekstrakurikuler").prop("style","background-color:#D3D3D3 !important;");
        $("#kelas").prop("style","background-color:#D3D3D3 !important;");
        $("#select-siswa").prop("style","background-color:#D3D3D3 !important;");
        $("#nilai-akhir").prop("style","background-color:#D3D3D3 !important;");
        $("#urut").prop("style","background-color:#D3D3D3 !important;");

        $("#btn-simpan").click(function(){
            var nis                 = $("#select-siswa").val() ? $("#select-siswa").val().split('#')[0] : "";
            var namaSiswa           = $("#select-siswa").val() ? $("#select-siswa").val().split('#')[1] : "";
            var kelas               = $("#kelas").val().toUpperCase() + "." + $("#urut").val();
            var tahunAjaran         = $("#select-tahun-ajaran").val();
            var semester            = $("#select-semester").val();
            var idExrSplit          = $("#select-ekstrakurikuler").val();
            var idEkstrakurikuler   = idExrSplit ? idExrSplit.split('#')[0] : "";
            var nilaiAkhir          = $("#nilai-akhir").val();


            if(nis === "" || namaSiswa === "" || kelas === "" || tahunAjaran === "" || semester === "" || idEkstrakurikuler === "" || nilaiAkhir === "")
            {
                alert("Semua kolom data harus diisi.");
                return false;
            }
            else 
            {
                var konfirmasi = confirm("Apakah data yang akan disimpan sudah benar?");
                if(!konfirmasi) 
                {
                    return false;
                }
            
                insertDataPenilaian(nis,namaSiswa,kelas,tahunAjaran,semester,idEkstrakurikuler,nilaiAkhir);
            }
        });

      
        $("#btn-tutup").click(function(){
           window.location.href = "daftar_nilai_ekstrakurikuler";
        });

        $("#select-siswa").select2({
            placeholder: "-- Pilih Siswa --",
            allowClear: true,
            width: '100%'
        });

    });

    function insertDataPenilaian(nis,namaSiswa,kelas,tahunAjaran,semester,idEkstrakurikuler,nilaiAkhir)
    {
      $.ajax({
        url: "../proses/proses_insert_grade.php",
        type: "POST",
            data: {
                nis                 : nis,
                namaSiswa           : namaSiswa,
                kelas               : kelas,
                tahunAjaran         : tahunAjaran,
                semester            : semester,
                idEkstrakurikuler   : idEkstrakurikuler,
                nilaiAkhir          : nilaiAkhir
            },
        success: function(response){
          var jdata = JSON.parse(response);
          if(jdata.isSuccessful == true){
            alert(jdata.msg);
            window.location.href = "daftar_nilai_ekstrakurikuler";
          } else {
            alert(jdata.msg);
          }
        }
      });
    }

    function selectedTahunAjaran() 
    {
        var tahunAjaran = $("#select-tahun-ajaran").val();
        var semester = $("#select-semester").val();

        if(tahunAjaran !== ""){
            $("#select-semester").prop("disabled", false);
            $("#select-semester").prop("style","background-color:white !important;");
        } else {
            $("#select-semester").prop("disabled", true);
            $("#select-semester").val("");
            $("#select-semester").prop("style","background-color:#D3D3D3 !important;");
        }

        if(semester !== ""){
            loadEkstrakurikuler();
            $("#select-ekstrakurikuler").prop("disabled", false);
            $("#select-ekstrakurikuler").prop("style","background-color:white !important;");
        }   
    }

    function selectedSemsester() {
        var semester    = $("#select-semester").val();
        var tahunAjaran = $("#select-tahun-ajaran").val();
        if(semester !== ""){
            loadEkstrakurikuler();
            $("#select-ekstrakurikuler").prop("disabled", false);
            $("#select-ekstrakurikuler").prop("style","background-color:white !important;");
        } else {
            $("#select-ekstrakurikuler").prop("disabled", true);
            $("#select-ekstrakurikuler").val("");
            $("#select-ekstrakurikuler").prop("style","background-color:#D3D3D3 !important;");
            $("#select-kelas").prop("disabled", true);
            $("#select-kelas").val("");
            $("#select-kelas").prop("style","background-color:#D3D3D3 !important;");
            $("#urut").prop("disabled", true);
            $("#urut").val("");
            $("#urut").prop("style","background-color:#D3D3D3 !important;");
            $("#select-siswa").prop("disabled", true);
            $("#select-siswa").val("");
            $("#select-siswa").prop("style","background-color:#D3D3D3 !important;");
            $("#nilai-akhir").prop("disabled", true);
            $("#nilai-akhir").val("");
            $("#nilai-akhir").prop("style","background-color:#D3D3D3 !important;");
        }
    }

    function selectedEkstrakurikuler() {
        if($("#select-ekstrakurikuler").val() !== "")
          {
            $("#kelas").prop("disabled", false);
            $("#kelas").prop("style","background-color:white !important;");
        } 
        else 
        {
            $("#kelas").prop("disabled", true);
            $("#kelas").val("");
            $("#kelas").prop("style","background-color:#D3D3D3 !important;");
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

    function loadEkstrakurikuler() {
        var tahunAjaran = $("#select-tahun-ajaran").val();
        var semester = $("#select-semester").val();
        $.ajax({
            url: '../proses/proses_get_ekstrakurikuler.php',
            type: 'POST',
            data: {
                tahunAjaran  : tahunAjaran,
                semester        : semester
            },
            success: function(response){
                var data = JSON.parse(response);
                if(data.isSuccessful){
                    var options = '<option value="">-- Pilih Ekstrakurikuler --</option>';
                    data.data.forEach(function(row){
                        options += `<option value="${row.ID_EKSTRAKURIKULER}#${row.NAMA_EKSTRAKURIKULER}">${row.NAMA_EKSTRAKURIKULER}</option>`;
                    });
                    $("#select-ekstrakurikuler").html(options);
                } else {
                    alert(data.msg);
                    $("#select-ekstrakurikuler").html('<option value="">-- Pilih Ekstrakurikuler --</option>');
                    $("#select-ekstrakurikuler").prop("disabled", true);
                    $("#select-ekstrakurikuler").prop("style","background-color:#D3D3D3 !important;");
                    $("#select-semester").val("");
                }
            }
        });
    }

    function filledKelas() 
    {
        var kelas = $("#kelas").val();
        var urut = $("#urut").val();
        var tahunAjaran = $("#select-tahun-ajaran").val();
        var semester = $("#select-semester").val();

        if(kelas !== "" && urut !== ""){
            var kelasFull = kelas.toUpperCase() + "." + urut;
            loadSiswa(kelasFull,semester,tahunAjaran);
        } else {
            $("#select-siswa").prop("disabled", true);
            $("#select-siswa").val("");
            $("#select-siswa").prop("style","background-color:#D3D3D3 !important;");
        }
    }

    function selectedSiswa() {
        if($("#select-siswa").val() !== "")
          {
            $("#nilai-akhir").prop("disabled", false);
            $("#nilai-akhir").prop("style","background-color:white !important;");
        } 
        else 
        {
            $("#nilai-akhir").prop("disabled", true);
            $("#nilai-akhir").val("");
            $("#nilai-akhir").prop("style","background-color:#D3D3D3 !important;");
        }
    }

    function loadSiswa(kelas,semester,tahunAjaran) 
    {
        $.ajax({
            url: '../proses/proses_get_siswa_by_kelas.php',
            type: 'POST',
            data: {
                kelas          : kelas,
                semester       : semester,
                tahunAjaran    : tahunAjaran
            },
            success: function(response){
                var data = JSON.parse(response);
                if(data.isSuccessful){
                    var options = '<option value="">-- Pilih Siswa --</option>';
                    data.data.forEach(function(row){
                        options += `<option value="${row.NIS}#${row.NAMA_SISWA}">${row.NIS} - ${row.NAMA_SISWA}</option>`;
                    });
                    $("#select-siswa").html(options).trigger('change');
                    $("#select-siswa").prop("disabled", false);
                    $("#select-siswa").prop("style","background-color:white !important;");
                } else {  
                    alert(data.msg);
                    $("#select-siswa").prop("disabled", true);
                    $("#select-siswa").val("");
                    $("#select-siswa").prop("style","background-color:#D3D3D3 !important;");
                }
            }
        });
    }

    function loadKelas(semester,tahunAjaran) 
    {
        var tahunAjaran = $("#select-tahun-ajaran").val();
        var semester = $("#select-semester").val();
        $.ajax({
            url: '../proses/proses_get_ekstrakurikuler.php',
            type: 'POST',
            data: {
                tahunAjaran  : tahunAjaran,
                semester        : semester
            },
            success: function(response){
                var data = JSON.parse(response);
                if(data.isSuccessful){
                    var options = '<option value="">-- Pilih Ekstrakurikuler --</option>';
                    data.data.forEach(function(row){
                        options += `<option value="${row.ID_EKSTRAKURIKULER}#${row.NAMA_EKSTRAKURIKULER}">${row.NAMA_EKSTRAKURIKULER}</option>`;
                    });
                    $("#select-ekstrakurikuler").html(options);
                } else {
                    alert(data.msg);
                    $("#select-ekstrakurikuler").html('<option value="">-- Pilih Ekstrakurikuler --</option>');
                    $("#select-ekstrakurikuler").prop("disabled", true);
                }
            }
        });
    }

  </script>
</html>
