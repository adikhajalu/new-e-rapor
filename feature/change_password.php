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
                <h4 class="text-left font-medium mb-4"><center>Ubah Kata Sandi</center></h4><br>
                <!-- ...existing code... -->
                <div class="grid grid-cols-12 gap-3 mb-3">
                  <div class="col-span-3 flex items-center">
                    <label for="pwd-lama" class="font-semibold">Password Lama<span style="color:red;">*</span></label>
                  </div>
                  <div class="col-span-3 sm:col-span-3" style="display: flex; gap: 10px; height: 40px;">
                    <input type="password" id="pwd-lama" maxlength="20" class="form-control" placeholder="Password Lama" >
                    <input type="checkbox" style="height: 15px; width:20px; margin-top:10px;" class="form-check-input input-primary" onclick="var x = document.getElementById('pwd-lama'); if (x.type === 'password') { x.type = 'text'; } else { x.type = 'password'; }"> Lihat Password
                  </div>
                </div>
                <div class="grid grid-cols-12 gap-3 mb-3">
                  <div class="col-span-3 flex items-center">
                    <label for="pwd-baru" class="font-semibold">Password Baru<span style="color:red;">*</span></label>
                  </div>
                  <div class="col-span-3 sm:col-span-3" style="display: flex; gap: 10px; height: 40px;">
                    <input type="password" id="pwd-baru" maxlength="20" class="form-control" placeholder="Password Baru" >
                    <input type="checkbox" style="height: 15px; width:20px; margin-top:10px;" class="form-check-input input-primary" onclick="var x = document.getElementById('pwd-baru'); if (x.type === 'password') { x.type = 'text'; } else { x.type = 'password'; }"> <span>Lihat Password</span>
                  </div>
                </div>
                <div class="grid grid-cols-12 gap-3 mb-3">
                  <div class="col-span-3 flex items-center">
                    <label for="pwd-konfirmasi" class="font-semibold">Konfirmasi Password<span style="color:red;">*</span></label>
                  </div>
                  <div class="col-span-3 sm:col-span-3" style="display: flex; gap: 10px; height: 40px;">
                    <input type="password" id="pwd-konfirmasi" maxlength="20" class="form-control" placeholder="Konfirmasi Password" >
                    <input type="checkbox" style="height: 15px; width:20px; margin-top:10px;" class="form-check-input input-primary" onclick="var x = document.getElementById('pwd-konfirmasi'); if (x.type === 'password') { x.type = 'text'; } else { x.type = 'password'; }"> Lihat Password
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

        $("#btn-tutup").click(function(){
           window.location.href = "../dashboard";
        });

        $("#btn-simpan").click(function(){
            var nip = dataDecrypt.nip;
            var konfirmasi = confirm("Apakah anda yakin akan mengubah password?");
            var pwdLama = $("#pwd-lama").val().trim();
            var pwdBaru = $("#pwd-baru").val().trim();
            var pwdKonfirmasi = $("#pwd-konfirmasi").val().trim();

            if(pwdLama == "" || pwdBaru == "" || pwdKonfirmasi == "")
            {
                alert("Semua field harus diisi.");
                return false;
            }

            if(pwdBaru != pwdKonfirmasi)
            {
                alert("Password baru dan konfirmasi password tidak sesuai.");
                return false;
            }

            if(pwdBaru.length < 6)
            {
                alert("Password baru minimal 6 karakter.");
                return false;
            }

            if(pwdBaru.length > 20)
            {
                alert("Password baru maksimal 20 karakter.");
                return false;
            }

            var passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/;
            if(!passwordRegex.test(pwdBaru)) {
                alert("Password baru harus mengandung huruf besar, huruf kecil, angka, dan simbol.");
                return false;
            }


            if(!konfirmasi) 
            {
                return false;
            }
            else 
            {
                gantiPassword(nip, pwdLama, pwdBaru);
            }
        });

    });

    function gantiPassword(nip, pwdLama, pwdBaru)
    {
        $.ajax({
            url: "../proses/proses_ganti_password.php",
            type: "POST",
                data: {
                    nip : nip,
                    pwdLama : pwdLama,
                    pwdBaru : pwdBaru
                },
            success: function(response){
                var jdata = JSON.parse(response);
                if(jdata.isSuccessful == true){
                    alert(jdata.msg);
                    window.location.href = "../dashboard";
                } else {
                    alert(jdata.msg);
                }
            }
        });
    }

  </script>
</html>
