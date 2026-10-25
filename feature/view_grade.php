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
                <h4 class="text-center font-medium mb-4">Penilaian Ekstrakurikuler</h4>
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
                    <label for="ekstrakurikuler-pelajaran" class="font-semibold">Ekstrakurikuler</label>
                  </div>
                  <div class="col-span-6 sm:col-span-6">
                    <input type="text" id="ekstrakurikuler"  class="form-control" placeholder="Ekstrakurikuler" disabled>
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
                    <label for="nilai-akhir" class="font-semibold">Nilai Akhir</label>
                  </div>
                  <div class="col-span-6 sm:col-span-6">
                    <input type="text" id="nilai-akhir"  class="form-control" placeholder="Nilai Akhir" disabled>
                  </div>
                </div>
                <div class="grid grid-cols-12 gap-3 mb-3">
                </div>
                <div class="mt-4 text-left">
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
        
        loadDataGrade(dataDecrypt.idEkstrakurikuler,dataDecrypt.nis,dataDecrypt.kelas,dataDecrypt.semester,dataDecrypt.tahunAjaran);

        $("#btn-tutup").click(function(){
           window.location.href = "daftar_nilai_ekstrakurikuler";
        });

    });

    function loadDataGrade(idEkstrakurikuler,nis,kelas,semester,tahunAjaran)
    {
        $.ajax({
            url: "../proses/proses_get_detil_grade.php",
            type: "POST",
                data: {
                    idEkstrakurikuler   : idEkstrakurikuler,
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
                $("#ekstrakurikuler").val(jdata.data[0].NAMA_EKSTRAKURIKULER);
                $("#kelas").val(jdata.data[0].KELAS);
                $("#nama-siswa").val(jdata.data[0].NIS + " - " + jdata.data[0].NAMA_SISWA);
                $("#nilai-akhir").val(jdata.data[0].NILAI_AKHIR);
            } else {
                alert(jdata.msg);
            }
            }
        });
    }

  </script>
</html>
