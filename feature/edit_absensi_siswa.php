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
                <h4 class="text-left font-medium mb-4"><center>Ubah Absensi Siswa</center></h4><br>
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
                    <input type="hidden" id="nis" class="form-control" disabled>
                  </div>
                </div>
                <div class="grid grid-cols-12 gap-3 mb-3">
                  <div class="col-span-3 flex items-center">
                    <label for="kehadiran" class="font-semibold">Hadir<span style="color:red;">*</span></label>
                  </div>
                  <div class="col-span-6 sm:col-span-6">
                    <input type="number" id="kehadiran"  class="form-control" style="background-color:white !important;" placeholder="Hadir">
                  </div>
                </div>
                <div class="grid grid-cols-12 gap-3 mb-3">
                  <div class="col-span-3 flex items-center">
                    <label for="sakit" class="font-semibold">Sakit<span style="color:red;">*</span></label>
                  </div>
                  <div class="col-span-6 sm:col-span-6">
                    <input type="number" id="sakit"  class="form-control" style="background-color:white !important;" placeholder="Sakit">
                  </div>
                </div>
                <div class="grid grid-cols-12 gap-3 mb-3">
                  <div class="col-span-3 flex items-center">
                    <label for="izin" class="font-semibold">Izin<span style="color:red;">*</span></label>
                  </div>
                  <div class="col-span-6 sm:col-span-6">
                    <input type="number" id="izin"  class="form-control" style="background-color:white !important;" placeholder="Izin">
                  </div>
                </div>
                <div class="grid grid-cols-12 gap-3 mb-3">
                  <div class="col-span-3 flex items-center">
                    <label for="tanpa-keterangan" class="font-semibold">Tanpa Keterangan<span style="color:red;">*</span></label>
                  </div>
                  <div class="col-span-6 sm:col-span-6">
                    <input type="number" id="tanpa-keterangan"  class="form-control" style="background-color:white !important;" placeholder="Tanpa Keterangan">
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
        
        loadDataAbsensiSiswa(dataDecrypt.namaSiswa,dataDecrypt.nis,dataDecrypt.kelas,dataDecrypt.semester,dataDecrypt.tahunAjaran,dataDecrypt.kehadiran,dataDecrypt.sakit,dataDecrypt.izin,dataDecrypt.tanpaKeterangan);

        $("#btn-simpan").click(function(){
            var namaSiswa           = dataDecrypt.namaSiswa;
            var nis                 = dataDecrypt.nis;
            var kelas               = dataDecrypt.kelas;
            var tahunAjaran         = dataDecrypt.tahunAjaran;
            var semester            = dataDecrypt.semester;
            var sakit               = $("#sakit").val();
            var izin                = $("#izin").val();
            var tanpaKeterangan     = $("#tanpa-keterangan").val();
            var kehadiran           = $("#kehadiran").val();

            if(namaSiswa == "" || nis == "" || kelas == "" || tahunAjaran == "" || semester == "" || kehadiran == "" || sakit == "" || izin == "" || tanpaKeterangan == "")
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
                
                updateDataAbsensiSiswa(namaSiswa,nis,kelas,tahunAjaran,semester,kehadiran,sakit,izin,tanpaKeterangan);
            }
        });

        $("#btn-tutup").click(function(){
           window.location.href = "daftar_absensi";
        });

    });

    function loadDataAbsensiSiswa(namaSiswa,nis,kelas,semester,tahunAjaran,kehadiran,sakit,izin,tanpaKeterangan)
    {
        

        $("#tahun-pelajaran").val(tahunAjaran);
        $("#semester").val(semester);
        $("#kelas").val(kelas);
        $("#nama-siswa").val(nis + " - " + namaSiswa);
        $("#nis").val(nis);
        $("#kehadiran").val(kehadiran);
        $("#sakit").val(sakit);
        $("#izin").val(izin);
        $("#tanpa-keterangan").val(tanpaKeterangan);
    
    }

    function updateDataAbsensiSiswa(namaSiswa,nis,kelas,tahunAjaran,semester,kehadiran,sakit,izin,tanpaKeterangan)
    {
        
        $.ajax({
            url: "../proses/proses_update_absensi_siswa.php",
            type: "POST",
                data: {
                    namaSiswa           : namaSiswa,
                    nis                 : nis,
                    kelas               : kelas,
                    tahunAjaran         : tahunAjaran,
                    semester            : semester,
                    kehadiran           : kehadiran,
                    sakit               : sakit,
                    izin                : izin,
                    tanpaKeterangan     : tanpaKeterangan
                },
            success: function(response){
            var jdata = JSON.parse(response);
            if(jdata.isSuccessful == true){
                alert(jdata.msg);
                window.location.href = "daftar_absensi";
            } else {
                alert(jdata.msg);
            }
            }
        });
    }

  </script>
</html>
