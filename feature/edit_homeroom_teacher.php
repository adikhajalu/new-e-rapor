<?php
  include_once '../includes/session.php';
  include_once '../includes/sidebar_menu.php';
  include_once '../includes/header.php';
  $datasent = isset($_GET['datasent']) ? $_GET['datasent'] : '-';
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
                <h4 class="text-left font-medium mb-4"><center>Ubah Wali Kelas</center></h4><br>
                <!-- ...existing code... -->
                <div class="grid grid-cols-12 gap-3 mb-3">
                  <div class="col-span-3 flex items-center">
                    <label for="nip" class="font-semibold">NIP<span style="color:red;">*</span></label>
                  </div>
                  <div class="col-span-6 sm:col-span-6">
                    <input type="text" id="nipnama-wali-kelas" class="form-control" disabled>
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
                      <input type="number" id="urut" class="form-control" placeholder="Lokal">
                    </div>
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
        
        generateTahunAjaran();

        var kelas = dataDecrypt.kelas.split(".")[0];
        var urut  = dataDecrypt.kelas.split(".")[1];
        var nip   = dataDecrypt.nipNama.split(" - ")[0];

        $("#nipnama-wali-kelas").val(dataDecrypt.nipNama);
        $("#select-tahun-ajaran").val(dataDecrypt.tahunAjaran);
        $("#kelas").val(kelas);
        $("#urut").val(urut);
        $("#keterangan").val(dataDecrypt.keterangan);

        $("#btn-simpan").click(function(){
            var kelas           = $("#kelas").val() + "." + $("#urut").val();
            var tahunAjaran     = $("#select-tahun-ajaran").val();
            var keterangan      = $("#keterangan").val();

            if(nip == "" || kelas == "" || tahunAjaran == "" || urut == "" )
            {
                alert("NIP, Kelas, dan Tahun Ajaran wajib diisi.");
                return false;
            }
            else 
            {
                var konfirmasi = confirm("Apakah data yang akan disimpan sudah benar?");
                if(!konfirmasi) 
                {
                    return false;
                }
                
                updateDataWaliKelas(nip,kelas,tahunAjaran,keterangan,dataDecrypt);
            }
        });


        $("#btn-tutup").click(function(){
           window.location.href = "manajemen_wali_kelas";
        });

    });

    function updateDataWaliKelas(nip,kelas,tahunAjaran,keterangan,dataDecrypt)
    {
        
        $.ajax({
            url: "../proses/proses_update_wali_kelas.php",
            type: "POST",
                data: {
                    nip                 : nip,
                    kelas               : kelas,
                    tahunAjaran         : tahunAjaran,
                    keterangan          : keterangan,
                    oldKelas            : dataDecrypt.kelas,
                    oldTahunAjaran     : dataDecrypt.tahunAjaran
                },
            success: function(response){
            var jdata = JSON.parse(response);
            if(jdata.isSuccessful == true){
                alert(jdata.msg);
                window.location.href = "manajemen_wali_kelas";
            } else {
                alert(jdata.msg);
            }
            }
        });
    }


  </script>
</html>
