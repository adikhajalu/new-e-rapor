<?php
  include_once '../includes/session.php';
  include_once '../includes/sidebar_menu.php';
  include_once '../includes/header.php';
  $datasent = isset($_GET['datasent']) ? $_GET['datasent'] : '-';
?>

<style>
/* Latar belakang popup */
.modal {
  display: none; /* awalnya tersembunyi */
  position: fixed;
  z-index: 2147483647;
  left: 0; top: 0;
  width: 100%; height: 100%;
  background-color: rgba(0,0,0,0.5);
  align-items: center;
  justify-content: center;
  padding: 20px;
  box-sizing: border-box;
}

/* Konten popup */
.modal-content {
  position: relative;
  z-index: 2147483647;
  background: #fff;
  margin: 0;
  padding: 20px;
  border-radius: 10px;
  width: min(1000px, 100%);
  /* min-height: min(800px, calc(100vh - 40px)); */
  max-height: calc(100vh - 40px);
  overflow-y: auto;
  box-shadow: 0 5px 10px rgba(0,0,0,0.3);
}

/* Tombol close (X) */
.close {
  float: right;
  font-size: 18px;
  font-weight: bold;
  cursor: pointer;
}
</style>

    <!-- [ Main Content ] start -->
    <div class="pc-container">
      <div class="pc-content">

        <!-- [ Main Content ] start -->
        <div class="grid grid-cols-1 gap-x-6">
          <div class="col-span-1 xl:col-span-2 md:col-span-6">
            <div class="card table-card">
              <!--  -->
              <div class="card-body !p-10">
                <h4 class="text-left font-medium mb-4"><center>Ubah Data</center></h4><br>
                <!-- ...existing code... -->
                <div class="grid grid-cols-12 gap-3 mb-3">
                  <div class="col-span-3 flex items-center">
                    <label for="nip" class="font-semibold">NIP<span style="color:red;">*</span></label>
                  </div>
                  <div class="col-span-6 sm:col-span-6">
                    <input type="text" id="nip" maxlength="20" class="form-control" placeholder="NIP" disabled>
                  </div>
                </div>
                <div class="grid grid-cols-12 gap-3 mb-3">
                  <div class="col-span-3 flex items-center">
                    <label for="nama" class="font-semibold">Nama Lengkap<span style="color:red;">*</span></label>
                  </div>
                  <div class="col-span-6 sm:col-span-6">
                    <input type="text" id="nama" maxlength="150" class="form-control" placeholder="Nama Lengkap">
                  </div>
                </div>
                <div class="grid grid-cols-12 gap-3 mb-3">
                  <div class="col-span-3 flex items-center">
                    <label for="posisi-jabatan" class="font-semibold">Posisi / Jabatan<span style="color:red;">*</span></label>
                  </div>
                  <div class="col-span-6 sm:col-span-6">
                    <input type="text" id="posisi-jabatan" maxlength="200" class="form-control" placeholder="Posisi / Jabatan">
                  </div>
                </div>
                <div class="grid grid-cols-12 gap-3 mb-3">
                  <div class="col-span-3 flex items-center">
                    <label for="kelas" class="font-semibold">Status Karyawan</label>
                  </div>
                  <div class="col-span-6 sm:col-span-6">
                    <select id="status-karyawan" class="form-control" style="width: 130px;">
                      <option value="">-- Status --</option>
                      <option value="STATUS_GURU">Guru</option>
                      <option value="STATUS_KESISWAAN">Kesiswaan</option>
                      <option value="STATUS_KURIKULUM">Kurikulum</option>
                    </select>
                  </div>
                </div>
                <div class="grid grid-cols-12 gap-3 mb-3">
                  <div class="col-span-3 flex items-center">
                    <label for="kelas" class="font-semibold">Status Aktif</label>
                  </div>
                  <div class="col-span-6 sm:col-span-6">
                    <input type="checkbox" id="status-aktif">
                  </div>
                </div>
                <div class="grid grid-cols-12 gap-3 mb-3">
                  <div class="col-span-3 flex items-center">
                    <label for="status-kepsek" class="font-semibold">Status Kepala Sekolah</label>
                  </div>
                  <div class="col-span-6 sm:col-span-6">
                    <input type="checkbox" id="status-kepsek">
                  </div>
                </div>
                <div class="grid grid-cols-12 gap-3 mb-3">
                  <div class="col-span-3 flex items-center">
                    <label for="kelas" class="font-semibold">Status Admin</label>
                  </div>
                  <div class="col-span-6 sm:col-span-6">
                    <input type="checkbox" id="status-admin">
                  </div>
                </div>
                <div class="mt-4 text-left">
                  <button id="btn-simpan" type="button" class="btn btn-primary mx-auto shadow-2xl">Simpan</button>
                  <button id="btn-tutup" type="button" class="btn btn-warning mx-auto shadow-2xl">Tutup</button>
                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  <button id="btn-reset-pwd" type="button" class="btn btn-danger mx-auto shadow-2xl">Reset Password</button>
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

    <div id="myModal" class="modal">
      <div class="modal-content" style="width: 300px !important; height: 300px !important;">
        <span class="close" onclick="closeModal()">&times;</span>
        <h5>Perhatian</h5><br>
        <div class="grid grid-cols-12 gap-6 mb-6">
          <div class="col-span-12 sm:col-span-12">
            <span id="alert-reset-password"></span>
        </div>
      </div>
    </div>
 
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
        loadDataGuru(dataDecrypt.nip);

        $("#status-kepsek").change(function() {
            if(this.checked) {
            cekStatusKepalaSekolah();
            }
        });


        $("#btn-simpan").click(function(){
            var nip             = $("#nip").val();
            var nama            = $("#nama").val();
            var posisiJabatan   = $("#posisi-jabatan").val();
            var statusAktif     = $("#status-aktif").is(":checked") ? "Y" : "T";
            var statusKepsek    = $("#status-kepsek").is(":checked") ? "Y" : "T";
            var statusAdmin     = $("#status-admin").is(":checked") ? "Y" : "T";
            var statusKaryawan  = $("#status-karyawan").val();

            if(nip == "" || nama == "" || posisiJabatan == "" || statusKaryawan == "")
            {
                alert("NIP, Nama Lengkap, Posisi/Jabatan dan Status Karyawan wajib diisi.");
                return false;
            }
            else 
            {
                var konfirmasi = confirm("Apakah data yang akan disimpan sudah benar?");
                if(!konfirmasi) 
                {
                    return false;
                }
                
                updateDataGuru(nip,nama,posisiJabatan,statusAktif,statusKepsek,statusAdmin,statusKaryawan,dataDecrypt);
            }
        });

        $("#btn-tutup").click(function(){
           window.location.href = "daftar_pengguna";
        });

        $("#btn-reset-pwd").click(function(){
            var nip = $("#nip").val();
            var konfirmasi = confirm("Apakah anda yakin akan melakukan reset password?");
            if(!konfirmasi) 
            {
                return false;
            }
            else 
            {
                resetPassword(nip);
            }
        });

    });

    function loadDataGuru(nip)
    {
        $.ajax({
            url: "../proses/proses_get_detil_guru.php",
            type: "POST",
                data: {
                    nip : nip
                },
            success: function(response){
                var jdata = JSON.parse(response);
                if(jdata.isSuccessful == true){
                    $("#nip").val(jdata.data[0].NIP);
                    $("#nama").val(jdata.data[0].NAMA);
                    $("#posisi-jabatan").val(jdata.data[0].POSISI_JABATAN);
                    if(jdata.data[0].STATUS_AKTIF == 'Y')
                    {
                        $("#status-aktif").prop("checked", true);
                    } 
                    else 
                    {
                        $("#status-aktif").prop("checked", false);
                    }
                    if(jdata.data[0].STATUS_KEPALA_SEKOLAH == 'Y')
                    {
                        $("#status-kepsek").prop("checked", true);
                    } else 
                    {
                        $("#status-kepsek").prop("checked", false);
                    }
                    if(jdata.data[0].STATUS_ADMIN == 'Y')
                    {
                        $("#status-admin").prop("checked", true);
                    } 
                    else 
                    {
                        $("#status-admin").prop("checked", false);
                    }
                    if(jdata.data[0].STATUS_GURU == 'Y')
                    {
                        $("#status-karyawan").val("STATUS_GURU");
                    } 
                    else if(jdata.data[0].STATUS_KESISWAAN == 'Y')
                    {
                        $("#status-karyawan").val("STATUS_KESISWAAN");
                    } 
                    else if(jdata.data[0].STATUS_KURIKULUM == 'Y')
                    {
                        $("#status-karyawan").val("STATUS_KURIKULUM");
                    } 
                    else 
                    {
                        $("#status-karyawan").val("");
                    }
                } 
                else 
                {
                    alert(jdata.msg);
                }
            }
        });
    }

    function updateDataGuru(nip,nama,posisiJabatan,statusAktif,statusKepsek,statusAdmin,statusKaryawan,dataDecrypt)
    {
        
        $.ajax({
            url: "../proses/proses_update_user.php",
            type: "POST",
                data: {
                    nip                 : nip,
                    nama                : nama,
                    posisiJabatan       : posisiJabatan,
                    statusAktif         : statusAktif,
                    statusKepsek        : statusKepsek,
                    statusAdmin         : statusAdmin,
                    nipOld              : dataDecrypt.nip,
                    statusKaryawan      : statusKaryawan
                },
            success: function(response){
            var jdata = JSON.parse(response);
            if(jdata.isSuccessful == true){
                alert(jdata.msg);
                window.location.href = "daftar_pengguna";
            } else {
                alert(jdata.msg);
            }
            }
        });
    }

    function cekStatusKepalaSekolah(){
      $.ajax({
        url: "../proses/proses_cek_status_kepala_sekolah.php",
        type: "POST",
            data: {
              statusKepalaSekolah : "Y"
            },
        success: function(response){
          var jdata = JSON.parse(response);
          if(jdata.isSuccessful == true){
            if(jdata.data.EXIST_KEPALA_SEKOLAH == "Y"){
              $("#status-kepsek").prop("checked", false);
              alert("Status Kepala Sekolah melekat pada " + jdata.data[0].NAMA + " ("+jdata.data[0].NIP+"). Silakan hapus statusnya terlebih dahulu.");
            } 
          } 
        }
      });
    }

    function resetPassword(nip)
    {
        $.ajax({
            url: "../proses/proses_reset_password.php",
            type: "POST",
                data: {
                    nip : nip
                },
            success: function(response){
                var jdata = JSON.parse(response);
                if(jdata.isSuccessful == true){
                    // alert(jdata.msg);
                    openModal(jdata.msg);
                } else {
                    alert(jdata.msg);
                }
            }
        });
    }

    
    function openModal(val) {
      document.getElementById("myModal").style.display = "flex";
      $("#alert-reset-password").html(val);
    }

    function closeModal(val) {
        document.getElementById("myModal").style.display = "none";
    }
  </script>
</html>
