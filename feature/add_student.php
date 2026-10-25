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
                <h4 class="text-left font-medium mb-4"><center>Tambah Siswa</center></h4><br>
                <!-- ...existing code... -->
                <div class="grid grid-cols-12 gap-3 mb-3">
                  <div class="col-span-3 flex items-center">
                    <label for="nis" class="font-semibold">Nomor Induk Siswa (NIS)<span style="color:red;">*</span></label>
                  </div>
                  <div class="col-span-6 sm:col-span-6">
                    <input type="text" id="nis" maxlength="20" class="form-control" placeholder="Nomor Induk Siswa (NIS)">
                  </div>
                </div>
                <div class="grid grid-cols-12 gap-3 mb-3">
                  <div class="col-span-3 flex items-center">
                    <label for="nama-siswa" class="font-semibold">Nama Lengkap<span style="color:red;">*</span></label>
                  </div>
                  <div class="col-span-6 sm:col-span-6">
                    <input type="text" id="nama-siswa" maxlength="150" class="form-control" placeholder="Nama Lengkap">
                  </div>
                </div>
                <div class="grid grid-cols-12 gap-3 mb-3">
                  <div class="col-span-3 flex items-center">
                    <label for="alamat" class="font-semibold">Alamat<span style="color:red;">*</span></label>
                  </div>
                  <div class="col-span-6 sm:col-span-6">
                    <textarea id="alamat" class="form-control" maxlength="4000" placeholder="Alamat..."></textarea>
                  </div>
                </div>
                <div class="grid grid-cols-12 gap-3 mb-3">
                  <div class="col-span-3 flex items-center">
                    <label for="kelas" class="font-semibold">Kelas<span style="color:red;">*</span></label>
                  </div>
                  <div class="col-span-3 sm:col-span-3">
                    <div style="display: flex; gap: 10px; height: 40px; width:300px">
                      <select name="" id="kelas" class="form-control">
                          <option value="">-- Kelas --</option>
                          <option value="X">X</option>
                          <option value="XI">XI</option>
                          <option value="XII">XII</option>
                          <option value="XIII">XIII</option>
                      </select>
                      <strong style="font-size:30px" >.</strong>
                      <input type="number" id="urut" class="form-control" placeholder="Lokal" min="0" step="1" pattern="[0-9]+">
                    </div>
                  </div>
                </div>
                <div class="grid grid-cols-12 gap-3 mb-3">
                  <div class="col-span-3 flex items-center">
                    <label for="select-tahun-ajaran" class="font-semibold">Tahun Ajaran<span style="color:red;">*</span></label>
                  </div>
                  <div class="col-span-6 sm:col-span-6">
                    <select name="" id="select-tahun-ajaran" class="form-control"></select>
                  </div>
                </div>
                <div class="grid grid-cols-12 gap-3 mb-3">
                  <div class="col-span-3 flex items-center">
                    <label for="keterangan" class="font-semibold">Keterangan</label>
                  </div>
                  <div class="col-span-6 sm:col-span-6">
                    <textarea id="keterangan" class="form-control" maxlength="4000" placeholder="Keterangan Tambahan jika dibutuhkan"></textarea>
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
    <!--  -->

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

        // Validasi input urut hanya angka (0-9)
        $("#urut").on('input', function(){
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        $("#btn-simpan").click(function(){
            var nis             = $("#nis").val();
            var namaSiswa       = $("#nama-siswa").val();
            var alamat          = $("#alamat").val();
            var kelas           = $("#kelas").val();
            var urut            = $("#urut").val();
            var tahunAjaran     = $("#select-tahun-ajaran").val();
            var keterangan      = $("#keterangan").val();
            var konfirmasi      = confirm("Apakah data yang akan disimpan sudah benar?");

            if(nis == "" || namaSiswa == "" || alamat == "" || kelas == "" || tahunAjaran == "" || urut == "" ) // || semester == ""
            {
                alert("NIS, Nama Lengkap, Alamat, Kelas, Tahun Ajaran harus diisi.");
                return false;
            }
            
            // Validasi format urut harus integer positif (0 ke atas)
            if(!/^[0-9]+$/.test(urut) || parseInt(urut, 10) < 0) {
                alert("Lokal harus berupa angka positif (0 atau lebih).");
                return false;
            }
            
            if(!konfirmasi) 
            {
                return false;
            }
            else
            {
                kelas = kelas + "." + urut;
                insertDataSiswa(nis,namaSiswa,alamat,kelas,tahunAjaran,keterangan);
            }
        });

      
        $("#btn-tutup").click(function(){
           window.location.href = "daftar_siswa";
        });

        
        $("#select-wali-kelas").select2({
            placeholder: " -- Pilih Wali Kelas -- ",
            allowClear: true,
            width: '100%'
        });


    });

    function insertDataSiswa(nis,namaSiswa,alamat,kelas,tahunAjaran,keterangan)
    {
      $.ajax({
        url: "../proses/proses_insert_siswa.php",
        type: "POST",
            data: {
                nis             : nis,
                namaSiswa       : namaSiswa,
                alamat          : alamat,
                kelas           : kelas,
                tahunAjaran     : tahunAjaran,
                keterangan      : keterangan
            },
        success: function(response){
          var jdata = JSON.parse(response);
          if(jdata.isSuccessful == true){
            alert(jdata.msg);
            window.location.href = "daftar_siswa";
          } else {
            alert(jdata.msg);
          }
        }
      });
    }

  </script>
</html>
