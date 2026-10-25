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
                <h4 class="text-left font-medium mb-4"><center>Tambah Wali Kelas</center></h4><br>
                <!-- ...existing code... -->
                <div class="grid grid-cols-12 gap-3 mb-3">
                  <div class="col-span-3 flex items-center">
                    <label for="nip" class="font-semibold">NIP<span style="color:red;">*</span></label>
                  </div>
                  <div class="col-span-6 sm:col-span-6">
                    <select name="" id="select-guru" class="form-control">
                        <option value="">-- Pilih NIP --</option>
                    </select>
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
        generateTahunAjaran();
        loadGuru();

        $("#btn-simpan").click(function(){
            var nipNama         = $("#select-guru").val();
            var nip             = nipNama.split(" - ")[0];
            var kelas           = $("#kelas").val();
            var urut            = $("#urut").val();
            var tahunAjaran     = $("#select-tahun-ajaran").val();
            var keterangan      = $("#keterangan").val();

            if(nip == "" || kelas == "" || tahunAjaran == "" || urut == "" )
            {
                alert("NIP, Kelas, dan Tahun Ajaran harus diisi.");
                return false;
            }
            else 
            {
                var konfirmasi = confirm("Apakah data yang akan disimpan sudah benar?");
                if(!konfirmasi) 
                {
                    return false;
                }
                kelas = kelas + "." + urut;
                insertDataWaliKelas(nip,kelas,tahunAjaran,keterangan);
            }
        });

      
        $("#btn-tutup").click(function(){
           window.location.href = "manajemen_wali_kelas";
        });


    });

    function insertDataWaliKelas(nip,kelas,tahunAjaran,keterangan)
    {
      $.ajax({
        url: "../proses/proses_insert_homeroom_teacher.php",
        type: "POST",
            data: {
                nip           : nip,
                kelas         : kelas,
                tahunAjaran   : tahunAjaran,
                keterangan    : keterangan
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

    function loadGuru() 
    {
        var nip = '';
      $("#select-guru").select2({
          placeholder: "-- Pilih NIP --",
          allowClear: true,
          width: '100%'
      });

      $.ajax({
          url: '../proses/proses_get_subject_teacher_option.php',
          type: 'POST',
          data: {
              nip : nip
          },
          success: function(response){
              var data = JSON.parse(response);
              if(data.isSuccessful){
                  var options = '<option value="">-- Pilih NIP --</option>';
                  data.data.forEach(function(row){
                      options += `<option value="${row.NIP}">${row.NIP} - ${row.NAMA}</option>`;
                  });
                  $("#select-guru").html(options).trigger('change');;
              } else {
                  alert(data.msg);
                  $("#select-guru").html('<option value="">-- Pilih NIP --</option>');
              }
                  $("#select-guru").prop("disabled", false);
          }
      });
    }

  </script>
</html>
