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
                <h4 class="text-left font-medium mb-4"><center>Penilaian Siswa</center></h4><br>
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
                    <label for="select-kelas" class="font-semibold">Kelas<span style="color:red;">*</span></label>
                  </div>
                  <div class="col-span-6 sm:col-span-6">
                    <select name="" id="select-kelas" class="form-control" onchange="selectedKelas()" disabled>
                        <option value="">-- Pilih Kelas --</option>
                    </select>
                  </div>
                </div>
                <div class="grid grid-cols-12 gap-3 mb-3">
                  <div class="col-span-3 flex items-center">
                    <label for="select-mata-pelajaran" class="font-semibold">Mata Pelajaran<span style="color:red;">*</span></label>
                  </div>
                  <div class="col-span-6 sm:col-span-6">
                    <select name="" id="select-mata-pelajaran" class="form-control" onchange="selectedMataPelajaran()" disabled>
                        <option value="">-- Pilih Mata Pelajaran --</option>
                    </select>
                  </div>
                </div>
                <div class="grid grid-cols-12 gap-3 mb-3">
                  <div class="col-span-3 flex items-center">
                    <label for="select-siswa" class="font-semibold">Siswa<span style="color:red;">*</span></label>
                  </div>
                  <div class="col-span-6 sm:col-span-6">
                    <select name="" id="select-siswa" class="form-control" disabled>
                        <option value="">-- Pilih Siswa --</option>
                    </select>
                  </div>
                </div>
                <div class="grid grid-cols-12 gap-3 mb-3">
                  <div class="col-span-3 flex items-center">
                    <label for="nip" class="font-semibold">Guru Mapel<span style="color:red;">*</span></label>
                  </div>
                  <div class="col-span-6 sm:col-span-6">
                    <input type="text" id="nip" class="form-control" disabled>
                  </div>
                </div>
                <div class="grid grid-cols-12 gap-3 mb-3">
                  <div class="col-span-3 flex items-center">
                    <label for="nilai-akhir" class="font-semibold">Nilai Akhir<span style="color:red;">*</span></label>
                  </div>
                  <div class="col-span-6 sm:col-span-6">
                    <input type="number" id="nilai-akhir"  class="form-control" placeholder="Nilai Akhir">
                  </div>
                </div>
                <div class="grid grid-cols-12 gap-3 mb-3">
                  <div class="col-span-3 flex items-center">
                    <label for="cp-plus" class="font-semibold">CP+<span style="color:red;">*</span></label>
                  </div>
                  <div class="col-span-6 sm:col-span-6">
                    <textarea id="cp-plus" class="form-control" maxlength="4000" placeholder="CP+"></textarea>
                  </div>
                </div>
                <div class="grid grid-cols-12 gap-3 mb-3">
                  <div class="col-span-3 flex items-center">
                    <label for="cp-min" class="font-semibold">CP-<span style="color:red;">*</span></label>
                  </div>
                  <div class="col-span-6 sm:col-span-6">
                    <textarea id="cp-min" class="form-control" maxlength="4000" placeholder="CP-"></textarea>
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

        $("#btn-simpan").click(function(){
            var nis             = $("#select-siswa").val() ? $("#select-siswa").val().split('#')[0] : "";
            var namaSiswa       = $("#select-siswa").val() ? $("#select-siswa").val().split('#')[1] : "";
            var kelas           = $("#select-kelas").val();
            var tahunAjaran     = $("#select-tahun-ajaran").val();
            var semester        = $("#select-semester").val();
            var idMapelSplit    = $("#select-mata-pelajaran").val();
            var idMapel         = idMapelSplit ? idMapelSplit.split('#')[0] : "";
            var nip             = idMapelSplit ? idMapelSplit.split('#')[2] : "";
            var nilaiAkhir      = $("#nilai-akhir").val();
            var cpPlus          = $("#cp-plus").val();
            var cpMin           = $("#cp-min").val(); 

            if(nis === "" || namaSiswa === "" || kelas === "" || tahunAjaran === "" || semester === "" || idMapel === "" || nip === "" || nilaiAkhir === "" || cpPlus === "" || cpMin === "")
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
            
                insertDataPenilaian(nis,namaSiswa,kelas,tahunAjaran,semester,idMapel,nip,nilaiAkhir,cpPlus,cpMin);
            }
        });

      
        $("#btn-tutup").click(function(){
           window.location.href = "daftar_nilai_mata_pelajaran";
        });

        $("#select-siswa").select2({
            placeholder: "-- Pilih Siswa --",
            allowClear: true,
            width: '100%'
        });

        if(window.statusAdmin == 'T')
        {
            $("#nip").val("<?=$session_user_id?>"+" - "+"<?=$session_nama?>");
        }

    });

    function insertDataPenilaian(nis,namaSiswa,kelas,tahunAjaran,semester,idMapel,nip,nilaiAkhir,cpPlus,cpMin)
    {
      $.ajax({
        url: "../proses/proses_insert_score.php",
        type: "POST",
            data: {
                nis             : nis,
                namaSiswa       : namaSiswa,
                kelas           : kelas,
                tahunAjaran     : tahunAjaran,
                semester        : semester,
                idMapel         : idMapel,
                nip             : nip,
                nilaiAkhir      : nilaiAkhir,
                cpPlus          : cpPlus,
                cpMin           : cpMin
            },
        success: function(response){
          var jdata = JSON.parse(response);
          if(jdata.isSuccessful == true){
            alert(jdata.msg);
            window.location.href = "daftar_nilai_mata_pelajaran";
          } else {
            alert(jdata.msg);
          }
        }
      });
    }

    function selectedTahunAjaran() 
    {
        var tahunAjaran = $("#select-tahun-ajaran").val();

        if(tahunAjaran !== ""){
            $("#select-semester").prop("disabled", false);
        } else {
            $("#select-semester").prop("disabled", true);
        }  
        
        $("#select-semester").val("");
        $("#select-kelas").prop("disabled", true);
        $("#select-kelas").val("");
        $("#select-mata-pelajaran").prop("disabled", true);
        $("#select-mata-pelajaran").val("");  
        $("#select-siswa").prop("disabled", true);
        $("#select-siswa").html('<option value="">-- Pilih Siswa --</option>');
    }

    function selectedSemsester() {
        var semester = $("#select-semester").val();
        if(semester !== ""){
            loadKelas();
            $("#select-kelas").prop("disabled", false);
        } else {
            $("#select-kelas").prop("disabled", true);
        }
        
        $("#select-kelas").val("");
        $("#select-mata-pelajaran").prop("disabled", true);
        $("#select-mata-pelajaran").val("");
        $("#select-siswa").prop("disabled", true);
        $("#select-siswa").html('<option value="">-- Pilih Siswa --</option>');
    }

    function selectedKelas() {
        var kelas = $("#select-kelas").val();
        if(kelas !== ""){
            loadMataPelajaran();
            $("#select-mata-pelajaran").prop("disabled", false);
        } else {
            $("#select-mata-pelajaran").prop("disabled", true);
            $("#select-mata-pelajaran").val("");
        }
        
        $("#select-siswa").prop("disabled", true);
        $("#select-siswa").html('<option value="">-- Pilih Siswa --</option>');
    }

    function loadKelas() {
        var tahunAjaran = $("#select-tahun-ajaran").val();
        var semester    = $("#select-semester").val();
        $.ajax({
            url: '../proses/proses_get_kelas.php',
            type: 'POST',
            data: {
                tahunAjaran  : tahunAjaran,
                semester        : semester
            },
            success: function(response){
                var data = JSON.parse(response);
                if(data.isSuccessful){
                    var options = '<option value="">-- Pilih Kelas --</option>';
                    data.data.forEach(function(row){
                        options += `<option value="${row.KELAS}">${row.KELAS}</option>`;
                    });
                    $("#select-kelas").html(options);
                } else {
                    alert(data.msg);
                    $("#select-kelas").html('<option value="">-- Pilih Kelas --</option>');
                    $("#select-kelas").prop("disabled", true);
                }
            }
        });
    }

    function loadMataPelajaran() {
        var tahunAjaran = $("#select-tahun-ajaran").val();
        var semester    = $("#select-semester").val();
        var kelas       = $("#select-kelas").val();
        $.ajax({
            url: '../proses/proses_get_subject_per_kelas.php',
            type: 'POST',
            data: {
                tahunAjaran  : tahunAjaran,
                semester     : semester,
                kelas        : kelas
            },
            success: function(response){
                var data = JSON.parse(response);
                if(data.isSuccessful){
                    var options = '<option value="">-- Pilih Mata Pelajaran --</option>';
                    data.data.forEach(function(row){
                        options += `<option value="${row.ID_MAPEL}#${row.NAMA_MAPEL}#${row.NIP}#${row.NAMA_GURU}">${row.NAMA_MAPEL}</option>`;
                    });
                    $("#select-mata-pelajaran").html(options);
                } else {
                    alert(data.msg);
                    $("#select-mata-pelajaran").html('<option value="">-- Pilih Mata Pelajaran --</option>');
                    $("#select-mata-pelajaran").prop("disabled", true);
                }
            }
        });
    }

    function selectedMataPelajaran() {
        var tahunAjaran = $("#select-tahun-ajaran").val();
        var semester    = $("#select-semester").val();
        var kelas       = $("#select-kelas").val();
        var mapel       = $("#select-mata-pelajaran").val();

        if(mapel !== "") {
          loadSiswa(kelas,semester,tahunAjaran);
          $("#select-siswa").prop("disabled", false); 
          
          if(window.statusAdmin == 'Y')
          {
            var dataGuru    = $("#select-mata-pelajaran").val();
            var dataGuru    = dataGuru ? dataGuru.split('#')[2] + " - " + dataGuru.split('#')[3] : "";
            $("#nip").val(dataGuru);
          }
        } else {
          $("#select-siswa").prop("disabled", true);
          $("#select-siswa").html('<option value="">-- Pilih Siswa --</option>');
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
                    $("#select-siswa").html(options).trigger('change');;
                } else {  
                    alert(data.msg);
                    $("#select-siswa").html('<option value="">-- Pilih Siswa --</option>');
                }
            }
        });
    }

  </script>
</html>
