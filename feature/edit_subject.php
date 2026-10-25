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
                <h4 class="text-left font-medium mb-4"><center>Ubah Mata Pelajaran</center></h4><br>
                <div class="grid grid-cols-12 gap-3 mb-3">
                  <div class="col-span-3 flex items-center">
                    <label for="nama-mapel" class="font-semibold">Mata Pelajaran<span style="color:red;">*</span></label>
                  </div>
                  <div class="col-span-6 sm:col-span-6">
                    <input type="text" id="nama-mapel" maxlength="100" class="form-control" placeholder="Nama Mata Pelajaran">
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
                    <label for="select-category" class="font-semibold">Kategori<span style="color:red;">*</span></label>
                  </div>
                  <div class="col-span-6 sm:col-span-6">
                    <select name="" id="select-category" class="form-control">
                        <option value="">-- Pilih Kategori -- </option>
                    </select>
                  </div>
                </div>
                <div class="grid grid-cols-12 gap-3 mb-3">
                  <div class="col-span-3 flex items-center">
                    <label for="select-sub-category" class="font-semibold">Sub Kategori</label>
                  </div>
                  <div class="col-span-6 sm:col-span-6">
                    <select name="" id="select-sub-category" class="form-control" disabled>
                        <option value="">-- Pilih Sub Kategori -- </option>
                    </select>
                  </div>
                </div>
                <!-- <div class="grid grid-cols-12 gap-3 mb-3">
                  <div class="col-span-3 flex items-center">
                    <label for="select-semester" class="font-semibold">Semester<span style="color:red;">*</span></label>
                  </div>
                  <div class="col-span-6 sm:col-span-6">
                    <select name="select-semester" id="select-semester" class="form-control">
                        <option value=""> -- Pilih Semester -- </option>
                        <option value="Gasal">Gasal</option>
                        <option value="Genap">Genap</option>
                    </select>
                  </div>
                </div> -->
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
        loadDataSubject(dataDecrypt.idMapel);

        $("#select-sub-category").prop("disabled", true);
        $("#select-tahun-ajaran").change(function(){
            var tahunAjaran = $(this).val();
            if(tahunAjaran != "")
            {
                loadCategory(tahunAjaran);
                $("#select-sub-category").html('<option value="">-- Pilih Sub Kategori --</option>');
                $("#select-sub-category").prop("disabled", true);
            }
            else            {
                $("#select-category").html('<option value="">-- Pilih Kategori --</option>');
                $("#select-category").prop("disabled", true);
                $("#select-sub-category").html('<option value="">-- Pilih Sub Kategori --</option>');
                $("#select-sub-category").prop("disabled", true);
            }
        });
        
        $("#select-category").change(function(){
            var idKategoriMapel = $(this).val();
            loadSubCategory(idKategoriMapel);
        });

        $("#btn-simpan").click(function(){
            var namaMapel           = $("#nama-mapel").val();
            var tahunAjaran      = $("#select-tahun-ajaran").val();
            var idKategoriMapel     = $("#select-category").val();
            var idSubKategoriMapel  = $("#select-sub-category").val();
            // var semester        = $("#select-semester").val();
            var keterangan      = $("#keterangan").val();

            if(namaMapel == "" || tahunAjaran == "") // || semester == ""
            {
                alert("Nama Mata Pelajaran, Tahun Ajaran harus diisi.");
                return false;
            }
            else 
            {
                var konfirmasi = confirm("Apakah data yang akan disimpan sudah benar?");
                if(!konfirmasi) 
                {
                    return false;
                }
                
                updateDataSubject(namaMapel,tahunAjaran,idKategoriMapel,idSubKategoriMapel,keterangan,dataDecrypt); //,semester
            }
        });

        $("#btn-tutup").click(function(){
           window.location.href = "daftar_mata_pelajaran";
        });

    });

    function loadDataSubject(idMapel)
    {
        $.ajax({
            url: "../proses/proses_get_detil_subject.php",
            type: "POST",
                data: {
                    idMapel : idMapel
                },
            success: function(response){
            var jdata = JSON.parse(response);
            if(jdata.isSuccessful == true){
                $("#nama-mapel").val(jdata.data[0].NAMA_MAPEL);
                $("#select-tahun-ajaran").val(jdata.data[0].TAHUN_PELAJARAN);
                // $("#select-semester").val(jdata.data[0].SEMESTER);
                $("#keterangan").val(jdata.data[0].KETERANGAN);
                loadCategory();
                setTimeout(function() {   
                    $("#select-category").val(jdata.data[0].ID_KATEGORI_MAPEL);
                }, 500);
                setTimeout(function() {   
                    loadSubCategory(jdata.data[0].ID_KATEGORI_MAPEL,jdata.data[0].ID_SUB_KATEGORI_MAPEL);
                }, 1000);
            } else {
                alert(jdata.msg);
            }
            }
        });
    }

    function updateDataSubject(namaMapel,tahunAjaran,idKategoriMapel,idSubKategoriMapel,keterangan,dataDecrypt) //,semester
    {
        
        $.ajax({
              url: "../proses/proses_update_subject.php",
              type: "POST",
                  data: {
                      namaMapel           : namaMapel,
                      tahunAjaran      : tahunAjaran,
                      idKategoriMapel     : idKategoriMapel,
                      idSubKategoriMapel  : idSubKategoriMapel,
                      // semester            : semester,
                      keterangan          : keterangan,
                      idMapel              : dataDecrypt.idMapel
                  },
              success: function(response){
              var jdata = JSON.parse(response);
              if(jdata.isSuccessful == true){
                  alert(jdata.msg);
                  window.location.href = "daftar_mata_pelajaran";
              } else {
                  alert(jdata.msg);
              }
            }
        });
    }

    function loadCategory() {
          var tahunAjaran = $("#select-tahun-ajaran").val();
        $.ajax({
            url: "../proses/proses_get_category.php",
            type: "POST",
            data: { tahunAjaran: tahunAjaran },
            success: function(response) {
                var jdata = JSON.parse(response);
                if (jdata.isSuccessful == true) {
                    var options = '<option value="">-- Pilih Kategori --</option>';
                    jdata.data.forEach(function(item) {
                        options += "<option value='" + item.ID_KATEGORI_MAPEL + "'>" + item.NAMA_KATEGORI_MAPEL + "</option>";
                    });
                    $("#select-category").html(options);
                    $("#select-category").prop("disabled", false);
                } else {
                    alert(jdata.msg);
                    $("#select-sub-category").html('<option value="">-- Pilih Sub Kategori --</option>');
                    $("#select-sub-category").prop("disabled", true);
                    $("#select-category").html('<option value="">-- Pilih Kategori --</option>');
                    $("#select-category").prop("disabled", true);
                    $("#select-tahun-ajaran").val("");
                }
            }
        });
    }

    function loadSubCategory(idKategoriMapel,idSubKategoriMapel = '') {
        $.ajax({
            url: "../proses/proses_get_sub_category.php",
            type: "POST",
            data: { idKategoriMapel: idKategoriMapel },
            success: function(response) {
                var jdata = JSON.parse(response);
                if (jdata.isSuccessful == true) {
                    var options = '<option value="">-- Pilih Sub Kategori --</option>';
                    jdata.data.forEach(function(item) {
                        options += "<option value='" + item.ID_SUB_KATEGORI_MAPEL + "'>" + item.NAMA_SUB_KATEGORI_MAPEL + "</option>";
                    });
                    $("#select-sub-category").html(options);
                    $("#select-sub-category").prop("disabled", false);
                    if(idSubKategoriMapel != '')
                    {
                        $("#select-sub-category").val(idSubKategoriMapel);
                    }
                } else {
                    $("#select-sub-category").html('<option value="">-- Pilih Sub Kategori --</option>');
                    $("#select-sub-category").prop("disabled", true);
                }
            }
        });
    }

  </script>
</html>
