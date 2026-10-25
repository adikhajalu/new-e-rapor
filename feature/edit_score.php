<?php
  include_once '../includes/session.php';
  include_once '../includes/sidebar_menu.php';
  include_once '../includes/header.php';
  $datasent = isset($_GET['datasent']) ? $_GET['datasent'] : '-';
?>
<style>input, textarea{background-color:#D3D3D3 !important;}</style>

    <!-- [ Main Content ] start -->
    <div class="pc-container">
      <div class="pc-content">

        <!-- [ Main Content ] start -->
        <div class="grid grid-cols-1 gap-x-6">
          <div class="col-span-1 xl:col-span-2 md:col-span-6">
            <div class="card table-card">
              <!--  -->
              <div class="card-body !p-10">
                <h4 class="text-left font-medium mb-4"><center>Ubah Penilaian Siswa</center></h4><br>
                <div class="grid grid-cols-12 gap-3 mb-3">
                  <div class="col-span-3 flex items-center">
                    <label for="tahun-pelajaran" class="font-semibold">Tahun Ajaran<span style="color:red;">*</span></label>
                  </div>
                  <div class="col-span-6 sm:col-span-6">
                    <input type="text" id="tahun-pelajaran" class="form-control" placeholder="Tahun Ajaran" disabled>
                  </div>
                </div>
                <div class="grid grid-cols-12 gap-3 mb-3">
                  <div class="col-span-3 flex items-center">
                    <label for="semester" class="font-semibold">Semester<span style="color:red;">*</span></label>
                  </div>
                  <div class="col-span-6 sm:col-span-6">
                    <input type="text" id="semester" class="form-control" placeholder="Semester" disabled>
                  </div>
                </div>
                <div class="grid grid-cols-12 gap-3 mb-3">
                  <div class="col-span-3 flex items-center">
                    <label for="mata-pelajaran" class="font-semibold">Mata Pelajaran<span style="color:red;">*</span></label>
                  </div>
                  <div class="col-span-6 sm:col-span-6">
                    <input type="text" id="mata-pelajaran"  class="form-control" placeholder="Mata Pelajaran" disabled>
                  </div>
                </div>
                <div class="grid grid-cols-12 gap-3 mb-3">
                  <div class="col-span-3 flex items-center">
                    <label for="kelas" class="font-semibold">Kelas<span style="color:red;">*</span></label>
                  </div>
                  <div class="col-span-6 sm:col-span-6">
                    <input type="text" id="kelas"  class="form-control" placeholder="Kelas" disabled>
                  </div>
                </div>
                <div class="grid grid-cols-12 gap-3 mb-3">
                  <div class="col-span-3 flex items-center">
                    <label for="nama-siswa" class="font-semibold">Nama Siswa<span style="color:red;">*</span></label>
                  </div>
                  <div class="col-span-6 sm:col-span-6">
                    <input type="text" id="nama-siswa" class="form-control" placeholder="Nama Siswa" disabled>
                  </div>
                </div>
                <div class="grid grid-cols-12 gap-3 mb-3">
                  <div class="col-span-3 flex items-center">
                    <label for="nip-guru" class="font-semibold">NIP Guru Mapel<span style="color:red;">*</span></label>
                  </div>
                  <div class="col-span-6 sm:col-span-6">
                    <input type="text" id="nip-guru" maxlength="16"  class="form-control" placeholder="NIP Guru" disabled>
                  </div>
                </div>
                <div class="grid grid-cols-12 gap-3 mb-3">
                  <div class="col-span-3 flex items-center">
                    <label for="nama-guru" class="font-semibold">Nama Guru<span style="color:red;">*</span></label>
                  </div>
                  <div class="col-span-6 sm:col-span-6">
                    <input type="text" id="nama-guru" maxlength="100"  class="form-control" placeholder="Nama Guru" disabled>
                  </div>
                </div>
                <div class="grid grid-cols-12 gap-3 mb-3">
                  <div class="col-span-3 flex items-center">
                    <label for="nilai-akhir" class="font-semibold">Nilai Akhir<span style="color:red;">*</span></label>
                  </div>
                  <div class="col-span-6 sm:col-span-6">
                    <input type="number" id="nilai-akhir"  class="form-control" style="background-color:white !important;" placeholder="Nilai Akhir">
                  </div>
                </div>
                <div class="grid grid-cols-12 gap-3 mb-3">
                  <div class="col-span-3 flex items-center">
                    <label for="cp-plus" class="font-semibold">CP+<span style="color:red;">*</span></label>
                  </div>
                  <div class="col-span-6 sm:col-span-6">
                    <textarea id="cp-plus" class="form-control" maxlength="4000" placeholder="CP+" style="background-color:white !important;"></textarea>
                  </div>
                </div>
                <div class="grid grid-cols-12 gap-3 mb-3">
                  <div class="col-span-3 flex items-center">
                    <label for="cp-min" class="font-semibold">CP-<span style="color:red;">*</span></label>
                  </div>
                  <div class="col-span-6 sm:col-span-6">
                    <textarea id="cp-min" class="form-control" maxlength="4000" placeholder="CP-" style="background-color:white !important;"></textarea>
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
        var dataDecrypt = decryptJSON(decodeURIComponent('<?php echo $datasent; ?>'), '<?=$session_key?>');
        
        loadDataScore(dataDecrypt.idMapel,dataDecrypt.nis,dataDecrypt.kelas,dataDecrypt.semester,dataDecrypt.tahunAjaran);

        $("#btn-simpan").click(function(){
            var idMapel         = dataDecrypt.idMapel;
            var nis             = dataDecrypt.nis;
            var kelas           = dataDecrypt.kelas;
            var tahunAjaran  = dataDecrypt.tahunAjaran;
            var semester        = dataDecrypt.semester;
            var nilaiAkhir      = $("#nilai-akhir").val();
            var cpPlus          = $("#cp-plus").val();
            var cpMin           = $("#cp-min").val();

            if(idMapel == "" || nis == "" || kelas == "" || tahunAjaran == "" || semester == "" || nilaiAkhir == "" || cpPlus == "" || cpMin == "")
            {
                alert("Semua data harus diisi.");
                return false;
            }
            else 
            {
                var konfirmasi = confirm("Apakah data yang akan disimpan sudah benar?");
                if(!konfirmasi) 
                {
                    return false;
                }
                
                updateDataScore(idMapel,nis,kelas,tahunAjaran,semester,nilaiAkhir,cpPlus,cpMin);
            }
        });

        $("#btn-tutup").click(function(){
           window.location.href = "daftar_nilai_mata_pelajaran";
        });

    });

    function loadDataScore(idMapel,nis,kelas,semester,tahunAjaran)
    {
        $.ajax({
            url: "../proses/proses_get_detil_score.php",
            type: "POST",
                data: {
                    idMapel         : idMapel,
                    nis             : nis,
                    kelas           : kelas,
                    semester        : semester,
                    tahunAjaran  : tahunAjaran
                },
            success: function(response){
            var jdata = JSON.parse(response);
            if(jdata.isSuccessful == true){

                $("#tahun-pelajaran").val(jdata.data[0].TAHUN_PELAJARAN);
                $("#semester").val(jdata.data[0].SEMESTER);
                $("#mata-pelajaran").val(jdata.data[0].NAMA_MAPEL);
                $("#kelas").val(jdata.data[0].KELAS);
                $("#nama-siswa").val(jdata.data[0].NIS + " - " + jdata.data[0].NAMA_SISWA);
                $("#nip-guru").val(jdata.data[0].NIP_GURU);
                $("#nama-guru").val(jdata.data[0].NAMA_GURU);
                $("#nilai-akhir").val(jdata.data[0].NILAI_AKHIR);
                $("#cp-plus").val(jdata.data[0].CP_PLUS);
                $("#cp-min").val(jdata.data[0].CP_MIN);
            } else {
                alert(jdata.msg);
            }
            }
        });
    }

    function updateDataScore(idMapel,nis,kelas,tahunAjaran,semester,nilaiAkhir,cpPlus,cpMin)
    {
        
        $.ajax({
            url: "../proses/proses_update_score.php",
            type: "POST",
                data: {
                    idMapel             : idMapel,
                    nis                 : nis,
                    kelas               : kelas,
                    tahunAjaran      : tahunAjaran,
                    semester            : semester,
                    nilaiAkhir          : nilaiAkhir,
                    cpPlus              : cpPlus,
                    cpMin               : cpMin
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

  </script>
</html>
