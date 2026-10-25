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
                <h4 class="text-left font-medium mb-4"><center>Ubah Guru Mata Pelajaran</center></h4><br>
                <div class="grid grid-cols-12 gap-3 mb-3">
                  <div class="col-span-3 flex items-center">
                    <label for="tahun-ajaran" class="font-semibold">Tahun Ajaran<span style="color:red;">*</span></label>
                  </div>
                  <div class="col-span-6 sm:col-span-6">
                    <input type="text" id="tahun-ajaran" class="form-control" disabled>
                  </div>
                </div>
                <div class="grid grid-cols-12 gap-3 mb-3">
                  <div class="col-span-3 flex items-center">
                    <label for="select-semester" class="font-semibold">Semester<span style="color:red;">*</span></label>
                  </div>
                  <div class="col-span-6 sm:col-span-6">
                      <input type="text" id="semester" class="form-control" disabled>
                  </div>
                </div>
                <div class="grid grid-cols-12 gap-3 mb-3">
                  <div class="col-span-3 flex items-center">
                    <label for="select-mata-pelajaran" class="font-semibold">Mata Pelajaran<span style="color:red;">*</span></label>
                  </div>
                  <div class="col-span-6 sm:col-span-6">
                    <input type="text" id="mata-pelajaran" class="form-control" disabled>
                  </div>
                </div>
                <div class="grid grid-cols-12 gap-3 mb-3">
                  <div class="col-span-3 flex items-center">
                    <label for="kelas" class="font-semibold">Kelas<span style="color:red;">*</span></label>
                  </div>
                  <div class="col-span-3 sm:col-span-3">
                    <div style="display: flex; gap: 10px; height: 40px; width:300px">
                      <input type="text" id="kelas" class="form-control" disabled>
                    </div>
                  </div>
                </div>
                <div class="grid grid-cols-12 gap-3 mb-3">
                  <div class="col-span-3 flex items-center">
                    <label for="select-guru" class="font-semibold">Guru Mapel<span style="color:red;">*</span></label>
                  </div>
                  <div class="col-span-6 sm:col-span-6">
                    <select name="" id="select-guru" class="form-control" disabled>
                        <option value="">-- Pilih Guru --</option>
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
    <script src="../assets/js/secure-encrypt.js"></script>
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
        var dataDecrypt = decryptJSON(decodeURIComponent('<?php echo $datasent; ?>'), '<?=$session_key?>');

        loadGuru(dataDecrypt.nip);
        
        $("#tahun-ajaran").val(dataDecrypt.tahunAjaran);
        $("#semester").val(dataDecrypt.semester);
        $("#select-guru").val(dataDecrypt.nip);
        $("#mata-pelajaran").val(dataDecrypt.namaMapel);
        $("#kelas").val(dataDecrypt.kelas);
        $("#keterangan").val(dataDecrypt.keterangan);

        $("#btn-simpan").click(function(){
            var konfirmasi  = confirm("Apakah data yang akan disimpan sudah benar?");
            var nip         = $("#select-guru").val();

            if(!konfirmasi) 
            {
                return false;
            }

            if(dataDecrypt.nip == nip){
                alert("Guru yang dipilih adalah guru yang sama dengan sebelumnya.");
                return false;
            }
            
            updateDataGuruMapel(dataDecrypt.idMapelSemester,dataDecrypt.kelas,nip);
        
        });


        $("#btn-tutup").click(function(){
           window.location.href = "manajemen_guru_mapel";
        });

    });

    function updateDataGuruMapel(idMapelSemester,kelas,nip)
    {
        $.ajax({
            url: "../proses/proses_update_guru_mapel.php",
            type: "POST",
                data: {
                    idMapelSemester     : idMapelSemester,
                    kelas               : kelas,
                    nip                 : nip,
                },
            success: function(response){
            var jdata = JSON.parse(response);
            if(jdata.isSuccessful == true){
                alert(jdata.msg);
                window.location.href = "manajemen_guru_mapel";
            } else {
                alert(jdata.msg);
            }
            }
        });
    }

    function loadGuru(nip) 
    {
      $("#select-guru").select2({
          placeholder: "-- Pilih Guru --",
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
                  var options = '<option value="">-- Pilih Guru --</option>';
                  data.data.forEach(function(row){
                      options += `<option value="${row.NIP}">${row.NIP} - ${row.NAMA}</option>`;
                  });
                  $("#select-guru").html(options).trigger('change');
                  if(nip){
                      $("#select-guru").val(nip);
                      $("#select-guru").trigger('change');
                  }
              } else {
                  alert(data.msg);
                  $("#select-guru").html('<option value="">-- Pilih Guru --</option>');
              }
                  $("#select-guru").prop("disabled", false);
          }
      });
    }


  </script>
</html>
