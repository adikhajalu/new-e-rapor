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
                <h4 class="text-left font-medium mb-4"><center>Ubah Data Siswa</center></h4><br>
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
                    <input type="text" id="alamat" maxlength="200" class="form-control" placeholder="Nama Lengkap">
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
                <!-- <div class="grid grid-cols-12 gap-3 mb-3">
                  <div class="col-span-3 flex items-center">
                    <label for="select-semester" class="font-semibold">Semester<span style="color:red;">*</span></label>
                  </div>
                  <div class="col-span-6 sm:col-span-6">
                    <select name="" id="select-semester" class="form-control">
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
        loadDataSiswa(dataDecrypt.nis,dataDecrypt.kelas,dataDecrypt.tahunAjaran);//dataDecrypt.semester,

        $("#nis").prop({"disabled": true, "style":"background-color:#D3D3D3 !important"});
        // $("#select-semester").prop({"disabled": true, "style":"background-color:#D3D3D3 !important"});
        
        $("#btn-simpan").click(function(){
            var nis             = $("#nis").val();
            var namaSiswa       = $("#nama-siswa").val();
            var alamat          = $("#alamat").val();
            var kelas           = $("#kelas").val();
            var urut            = $("#urut").val();
            var tahunAjaran     = $("#select-tahun-ajaran").val();
            // var semester        = $("#select-semester").val();
            var keterangan      = $("#keterangan").val();
            var konfirmasi      = confirm("Apakah data yang akan disimpan sudah benar?");

            if(nis == "" || namaSiswa == "" || alamat == "" || kelas == "" || tahunAjaran == "") // || semester == ""
            {
                alert("NIS, Nama Lengkap, Alamat, Kelas, Tahun Ajaran harus diisi."); //Semester,
                return false;
            }

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
                updateDataSiswa(nis,namaSiswa,alamat,kelas,tahunAjaran,keterangan,dataDecrypt);//semester,
            }
        });

        $("#btn-tutup").click(function(){
           window.location.href = "daftar_siswa";
        });

    });

    function loadDataSiswa(nis,kelas,tahunAjaran)//semester,
    {
        $.ajax({
            url: "../proses/proses_get_detil_siswa.php",
            type: "POST",
                data: {
                    nis             : nis,
                    kelas           : kelas,
                    // semester        : semester,
                    tahunAjaran  : tahunAjaran
                },
            success: function(response){
            var jdata = JSON.parse(response);
            if(jdata.isSuccessful == true){
                var split_kelas = jdata.data[0].KELAS.split(".");
                
                $("#nis").val(jdata.data[0].NIS);
                $("#nama-siswa").val(jdata.data[0].NAMA_SISWA);
                $("#alamat").val(jdata.data[0].ALAMAT);
                $("#kelas").val(split_kelas[0]);
                $("#urut").val(split_kelas[1]);
                $("#select-tahun-ajaran").val(jdata.data[0].TAHUN_PELAJARAN);
                // $("#select-semester").val(jdata.data[0].SEMESTER);
                $("#keterangan").val(jdata.data[0].KETERANGAN);
            } else {
                alert(jdata.msg);
            }
            }
        });
    }

    function updateDataSiswa(nis,namaSiswa,alamat,kelas,tahunAjaran,keterangan,dataDecrypt)//semester,
    {
        
        $.ajax({
            url: "../proses/proses_update_siswa.php",
            type: "POST",
                data: {
                    nis                 : nis,
                    namaSiswa           : namaSiswa,
                    alamat              : alamat,
                    kelas               : kelas,
                    tahunAjaran      : tahunAjaran,
                    // semester            : semester,
                    keterangan          : keterangan,
                    nisOld              : dataDecrypt.nis,
                    kelasOld            : dataDecrypt.kelas,
                    semesterOld         : dataDecrypt.semester,
                    tahunAjaranOld   : dataDecrypt.tahunAjaran
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
