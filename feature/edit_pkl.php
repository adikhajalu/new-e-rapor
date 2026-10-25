<?php
  include_once '../includes/session.php';
  include_once '../includes/sidebar_menu.php';
  include_once '../includes/header.php';
  $datasent = isset($_GET['datasent']) ? $_GET['datasent'] : '-';
?>

<style>#tahun-pelajaran, #semester, #kelas, #nama-siswa{background-color:#D3D3D3 !important;}</style>

    <!-- [ Main Content ] start -->
    <div class="pc-container">
      <div class="pc-content">

        <!-- [ Main Content ] start -->
        <div class="grid grid-cols-1 gap-x-6">
          <div class="col-span-1 xl:col-span-2 md:col-span-6">
            <div class="card table-card">
              <!--  -->
              <div class="card-body !p-10">
                <h4 class="text-center font-medium mb-4">Detil PKL</h4>
                <br><br><br>
                <div class="grid grid-cols-12 gap-3 mb-3">
                  <div class="col-span-3 flex items-center">
                    <label for="tahun-pelajaran" class="font-semibold">Tahun Ajaran</label>
                  </div>
                  <div class="col-span-6 sm:col-span-6">
                    <input type="text" id="tahun-pelajaran" class="form-control" placeholder="Tahun Ajaran" disabled>
                  </div>
                </div>
                <div class="grid grid-cols-12 gap-3 mb-3">
                  <div class="col-span-3 flex items-center">
                    <label for="semester" class="font-semibold">Semester</label>
                  </div>
                  <div class="col-span-6 sm:col-span-6">
                    <input type="text" id="semester" class="form-control" placeholder="Semester" disabled>
                  </div>
                </div>
                <div class="grid grid-cols-12 gap-3 mb-3">
                  <div class="col-span-3 flex items-center">
                    <label for="kelas" class="font-semibold">Kelas</label>
                  </div>
                  <div class="col-span-6 sm:col-span-6">
                    <input type="text" id="kelas"  class="form-control" placeholder="Kelas" disabled>
                  </div>
                </div>
                <div class="grid grid-cols-12 gap-3 mb-3">
                  <div class="col-span-3 flex items-center">
                    <label for="nama-siswa" class="font-semibold">Nama Siswa</label>
                  </div>
                  <div class="col-span-6 sm:col-span-6">
                    <input type="text" id="nama-siswa" class="form-control" placeholder="Nama Siswa" disabled>
                  </div>
                </div>
                <div class="grid grid-cols-12 gap-3 mb-3">
                  <div class="col-span-3 flex items-center">
                    <label for="mitra-du-di" class="font-semibold">Mitra DU/DI</label>
                  </div>
                  <div class="col-span-6 sm:col-span-6">
                    <input type="text" id="mitra-du-di"  class="form-control" placeholder="Mitra DU/DI">
                  </div>
                </div>
                <div class="grid grid-cols-12 gap-3 mb-3">
                  <div class="col-span-3 flex items-center">
                    <label for="lokasi" class="font-semibold">Lokasi</label>
                  </div>
                  <div class="col-span-6 sm:col-span-6">
                    <input type="text" id="lokasi"  class="form-control" placeholder="Lokasi">
                  </div>
                </div>
                <div class="grid grid-cols-12 gap-3 mb-3">
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
        
        loadDataGrade(dataDecrypt.mitraDuDi,dataDecrypt.nis,dataDecrypt.kelas,dataDecrypt.semester,dataDecrypt.tahunAjaran,dataDecrypt.lokasi);

        $("#btn-tutup").click(function(){
           window.location.href = "praktik_kerja_lapangan";
        });

        $("#btn-simpan").click(function(){
            var nis             = dataDecrypt.nis;
            var kelas           = dataDecrypt.kelas;
            var tahunAjaran     = dataDecrypt.tahunAjaran;
            var semester        = dataDecrypt.semester;
            var mitraDuDiOld    = dataDecrypt.mitraDuDi;
            var mitraDuDiNew    = $("#mitra-du-di").val();
            var lokasi          = $("#lokasi").val();

            if(nis == "" || kelas == "" || tahunAjaran == "" || semester == "" || mitraDuDiOld == "" || mitraDuDiNew == "" || lokasi == "")
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
                
                updateDataPkl(nis,kelas,tahunAjaran,semester,mitraDuDiOld,mitraDuDiNew,lokasi);
            }
        });

    });

    function loadDataGrade(mitraDuDi,nis,kelas,semester,tahunAjaran,lokasi)
    {
        $.ajax({
            url: "../proses/proses_get_detil_pkl.php",
            type: "POST",
                data: {
                    mitraDuDi           : mitraDuDi,
                    nis                 : nis,
                    kelas               : kelas,
                    semester            : semester,
                    tahunAjaran         : tahunAjaran
                },
            success: function(response){
            var jdata = JSON.parse(response);
            if(jdata.isSuccessful == true){

                $("#tahun-pelajaran").val(jdata.data[0].TAHUN_PELAJARAN);
                $("#semester").val(jdata.data[0].SEMESTER);
                $("#mitra-du-di").val(jdata.data[0].MITRA_DU_DI);
                $("#kelas").val(jdata.data[0].KELAS);
                $("#nama-siswa").val(jdata.data[0].NIS + " - " + jdata.data[0].NAMA_SISWA);
                $("#lokasi").val(jdata.data[0].LOKASI);
            } else {
                alert(jdata.msg);
            }
            }
        });
    }

    function updateDataPkl(nis,kelas,tahunAjaran,semester,mitraDuDiOld,mitraDuDiNew,lokasi)
    {
      
        $.ajax({
            url: "../proses/proses_update_pkl.php",
            type: "POST",
                data: {
                    nis                 : nis,
                    kelas               : kelas,
                    tahunAjaran         : tahunAjaran,
                    semester            : semester,
                    mitraDuDiOld        : mitraDuDiOld,
                    mitraDuDiNew        : mitraDuDiNew,
                    lokasi              : lokasi
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

  </script>
</html>
