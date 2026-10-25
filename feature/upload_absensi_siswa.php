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
                    <h4 class="text-left font-medium mb-4"><center> Absensi Siswa Massal</center></h4> <br>
                    <form method="post" id="upload-form" enctype="multipart/form-data">
                        <div class="grid grid-cols-12 gap-3 mb-3">
                            <div class="col-span-4 sm:col-span-3">
                                <select name="" id="select-tahun-ajaran" class="form-control" onchange="selectedTahunAjaran()"></select>
                            </div>
                            <div class="col-span-3 sm:col-span-3">
                                <select name="" id="select-semester" class="form-control" onchange="selectedSemsester()" disabled>
                                    <option value="">-- Pilih Semester --</option>
                                    <option value="Gasal">Gasal</option>
                                    <option value="Genap">Genap</option>
                                </select>
                            </div>
                            <div class="col-span-4 sm:col-span-3">
                                <select name="" id="select-kelas" class="form-control" disabled>
                                    <option value="">-- Pilih Kelas --</option>
                                </select>
                            </div>
                            <div class="col-span-4 sm:col-span-3">
                                <a class="btn btn-success" id="btn-download-template">Download Template</a>
                            </div>
                        </div>
                        <span style="color:red;">*daftar siswa yang muncul pada template adalah siswa yang belum pernah direkam sebelumnya</span>
                        <hr><br>
                        <div class="grid grid-cols-12 gap-3 mb-3">
                            <div class="col-span-12 sm:col-span-6">
                                <input type="file" id="file_excel" name="file_excel" class="form-control" accept=".xls,.xlsx">
                                <small class="text-muted">Format file: XLS, XLSX. Max size: 5MB</small>
                            </div>
                            <div class="col-span-12 sm:col-span-6">
                                <button id="btn-upload" type="button" class="btn btn-primary mx-auto shadow-2xl">Upload</button>
                            </div>
                        </div>
                    </form>
                    <div id="preview-data" class="mt-4" style="display:none;">
                        <h5>Preview Data:</h5>
                        <div style='display: inline-block'>
                             &nbsp;&nbsp;&nbsp;&nbsp;<input id="kat-status-valid" type="checkbox"><span style="color:black !important; font-weight:bold;"> Lihat Hanya Data Invalid</span>
                        </div>
                        <div class="table-responsive">
                            <table class="table" id="preview-table">
                                <thead>
                                    <tr>
                                        <th>Status Valid</th>
                                        <th>Keterangan</th>
                                        <th>Tahun Ajaran</th>
                                        <th>Semester</th>
                                        <th>Kelas</th>
                                        <th>NIS</th>
                                        <th>Nama Siswa</th>
                                        <th>Hadir</th>
                                        <th>Sakit</th>
                                        <th>Izin</th>
                                        <th>Tanpa Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                        <button id="btn-save-all" class="btn btn-success mt-3" style="display:none;">Simpan Data</button>
                        <button id="btn-clear" class="btn btn-danger mt-3" style="display:none;">Hapus Semua Data</button>
                        <button id="btn-clear-invalid" class="btn btn-danger mt-3" style="display:none;">Hapus Data Invalid</button>
                        <a href="daftar_absensi" id="btn-close" class="btn btn-warning mt-3" style="display:none;">Tutup</a>
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
            loadDataPenilaianTemp('Y');

            $("#kat-status-valid").change(function() {
                if(this.checked) 
                {
                    loadDataPenilaianTemp('T');
                }
                else
                {
                    loadDataPenilaianTemp('Y');
                }
            });

            $("#select-semester").prop("style","background-color:#DCDCDC !important");
            $("#select-kelas").prop("style","background-color:#DCDCDC !important");
            
            $("#btn-clear").click(function(){
                if(confirm("Apakah Anda yakin akan menghapus semua data preview?")){
                    clearDataTemp();
                }
            });
            
            $("#btn-clear-invalid").click(function(){
                if(confirm("Apakah Anda yakin akan semua menghapus data tidak valid?")){
                    clearInvalidDataTemp();
                }
            })

            $("#btn-upload").click(function(){
                var formData = new FormData();
                var file = $('#file_excel')[0].files[0];
                formData.append('file_excel', file);
                $("#loader-overlay").css("display", "flex");

                $.ajax({
                    url: '../proses/proses_preview_upload_absensi_siswa.php',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response){
                        var data = JSON.parse(response);
                        if(data.isSuccessful){
                            // showPreview(data.data);
                            loadDataPenilaianTemp('Y');
                        } else {
                            $("#loader-overlay").css("display", "none");
                            alert(data.msg);
                        }
                    }
                });
            });

            $("#btn-save-all").click(function(){
                if(confirm("Apakah Anda yakin akan menyimpan semua data?")){
                    $("#loader-overlay").css("display", "none");
                    $.ajax({
                        url: '../proses/proses_save_upload_absensi_siswa.php',
                        type: 'POST',
                        data: {},
                        success: function(response){
                            var result = JSON.parse(response);
                            if(result.isSuccessful == true)
                            {
                                alert(result.msg);
                                clearDataTemp();
                            }
                            else
                            {
                                $("#loader-overlay").css("display", "none");
                                alert(result.msg);
                                location.reload();
                            }
                        }
                    });
                }
            });

            $("#btn-download-template").click(function(){
                var tahunAjaran         = $("#select-tahun-ajaran").val();
                var semester            = $("#select-semester").val();
                var kelas               = $("#select-kelas").val();

                if(tahunAjaran === "" || semester === "" || kelas === ""){
                    alert("Tahun Ajaran, Semester, dan Kelas harus diisi.");
                    return false;
                }
                else
                {
                    downloadTemplate(tahunAjaran,semester,kelas);
                }
            });
        });

        function tambah() {
            alert("Silakan hubungi administrator untuk menambahkan data semester baru.");
        }

        function showPreview(data) {
            window.previewData = data;
            var tbody = "";
            $("#preview-table tbody").html(tbody);
            data.forEach(function(row){
                
                if(row.STATUS_VALID == "Y"){
                    statusValid = "<center><img src='../assets/images/check.png' width='30px' title='Valid'></center>";
                } else {
                    statusValid = "<center><img src='../assets/images/cross.png' width='30px' title='Tidak Valid'></center>";
                }

                tbody += `<tr>
                    <td>${statusValid}</td>
                    <td><textarea cols="30" rows="2" style="font-weight:bolder">${row.KETERANGAN}</textarea></td>
                    <td>${row.TAHUN_PELAJARAN}</td>
                    <td>${row.SEMESTER}</td>
                    <td>${row.KELAS}</td>
                    <td>${row.NIS}</td>
                    <td>${row.NAMA_SISWA}</td>
                    <td>${row.KEHADIRAN}</td>
                    <td>${row.SAKIT}</td>
                    <td>${row.IZIN}</td>
                    <td>${row.TANPA_KETERANGAN}</td>
                </tr>`;
            });
            $("#preview-table tbody").html(tbody);
            $("#preview-data").show();
            $("#btn-save-all").show();
            $("#btn-clear").show();
            $("#btn-clear-invalid").show();
            $("#btn-close").show();
            $("#file_excel").val('');
            $("#loader-overlay").css("display", "none");
        }

        function loadDataPenilaianTemp(val) {
            $("#loader-overlay").css("display", "flex");
            $.ajax({
                url: '../proses/proses_get_absensi_siswa_list.php',
                type: 'POST',
                data: {statusValid:val},
                success: function(response){
                    var data = JSON.parse(response);
                    if(data.isSuccessful && data.data.length > 0){
                        showPreview(data.data);
                    }
                    else
                    {
                        var tbody = "`<tr><td colspan='13'><center>-- Belum tersedia data upload --</center></td></tr>"
                        $("#preview-table tbody").html(tbody);
                        $("#preview-data").show();
                        $("#btn-close").show();
                        $("#loader-overlay").css("display", "none");
                    }
                }
            });
        }

        function clearDataTemp() {
            $("#loader-overlay").css("display", "flex");
            $.ajax({
                url: '../proses/proses_delete_absensi_siswa_temp.php',
                type: 'POST',
                data: {},
                success: function(response){
                    var data = JSON.parse(response);
                    if(data.isSuccessful){
                        $("#loader-overlay").css("display", "none");
                        location.reload();
                    } else {
                        $("#loader-overlay").css("display", "none");
                        alert(data.msg);
                    }
                }
            });
        }

        function selectedTahunAjaran() {
            var tahunAjaran = $("#select-tahun-ajaran").val();
            var semester = $("#select-semester").val();

            if(tahunAjaran !== ""){
                $("#select-semester").prop("disabled", false);
                $("#select-semester").prop("style","background-color:white !important;");
                if(tahunAjaran === "tambah"){
                    alert("Silakan hubungi administrator untuk menambahkan data tahun pelajaran baru.");
                    $("#select-tahun-ajaran").val("");
                }
            } else {
                $("#select-semester").prop("disabled", true);
                $("#select-semester").val("");
                $("#select-semester").prop("style","background-color:#D3D3D3 !important;");
            }
        }

        function selectedSemsester() {
            var semester = $("#select-semester").val();
            if(semester !== ""){
                loadKelas();
            } else {
                $("#select-kelas").prop("disabled", true);
                $("#select-kelas").val("");
                $("#select-kelas").prop("style","background-color:#DCDCDC !important;");
            }
        }

        function loadKelas() {
            $("#loader-overlay").css("display", "flex");
            var tahunAjaran = $("#select-tahun-ajaran").val();
            var semester    = $("#select-semester").val();
            $.ajax({
                url: '../proses/proses_get_class_all.php',
                type: 'POST',
                data: {
                    tahunAjaran  : tahunAjaran,
                    semester     : semester
                },
                success: function(response){
                    var data = JSON.parse(response);
                    if(data.isSuccessful){
                        var options = '<option value="">-- Pilih Kelas --</option>';
                        data.data.forEach(function(row){
                            options += `<option value="${row.KELAS}">${row.KELAS}</option>`;
                        });
                        $("#select-kelas").html(options);
                        $("#select-kelas").prop("style","background-color:white !important;");
                        $("#select-kelas").prop("disabled", false);
                        $("#loader-overlay").css("display", "none");
                    } else {
                        alert(data.msg);
                        $("#select-kelas").html('<option value="">-- Pilih Kelas --</option>');
                        $("#select-kelas").prop("disabled", true);
                        $("#select-kelas").prop("style","background-color:#D3D3D3 !important;");
                        $("#loader-overlay").css("display", "none");
                        $("#select-semester").val("");
                    }
                }
            });
        }

        function downloadTemplate(tahunAjaran,semester,kelas)
        {
            $("#loader-overlay").css("display", "flex");
            $.ajax({
                url: '../proses/proses_validasi_download_template_absensi.php',
                type: 'POST',
                data: {
                    tahunAjaran      : tahunAjaran,
                    semester            : semester,
                    kelas               : kelas
                },
                success: function(response){
                    var data = JSON.parse(response);
                    if(data.isSuccessful == true)
                    {
                        var url = `download_template_absensi?tahunAjaran=${encodeURIComponent(tahunAjaran)}&semester=${encodeURIComponent(semester)}&kelas=${encodeURIComponent(kelas)}`;
                        window.location.href = url;
                        setTimeout(() => {
                            $("#loader-overlay").css("display", "none");
                        }, 2000);
                    }
                    else
                    {
                        $("#loader-overlay").css("display", "none");
                        alert(data.msg);
                    }
                }
            });
        }

        function clearInvalidDataTemp() {
            $("#loader-overlay").css("display", "flex");
            $.ajax({
                url: '../proses/proses_delete_invalid_absensi_temp.php',
                type: 'POST',
                data: {},
                success: function(response){
                    var data = JSON.parse(response);
                    if(data.isSuccessful){
                        $("#loader-overlay").css("display", "none");
                        location.reload();
                    } else {
                        $("#loader-overlay").css("display", "none");
                        alert(data.msg);
                    }
                }
            });
        }
    </script>
</html>
