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
                    <h4 class="text-left font-medium mb-4"><center>Upload Data Guru Mapel</center></h4> <br>
                    <form method="post" id="upload-form" enctype="multipart/form-data">
                        <div class="grid grid-cols-12 gap-3 mb-3">
                            <div class="col-span-6 sm:col-span-3">
                                <input type="number" id="jml-row" width="300px" class="form-control" placeholder="Jumlah Data Upload"  min="1" max="1000">
                            </div>
                            <div class="col-span-4 sm:col-span-3">
                                <select name="" id="select-tahun-ajaran" class="form-control" onchange="selectedTahunAjaran()"></select>
                            </div>
                            <div class="col-span-4 sm:col-span-3">
                                <select name="" id="select-semester" class="form-control" onchange="selectedSemsester()" disabled>
                                    <option value="">-- Pilih Semester --</option>
                                    <option value="Gasal">Gasal</option>
                                    <option value="Genap">Genap</option>
                                </select>
                            </div>
                            <div class="col-span-6 sm:col-span-6">
                                <a class="btn btn-success" id="btn-download-template">Download Template</a><br>
                            </div>
                        </div>
                        <hr><br>
                        <div class="grid grid-cols-12 gap-3 mb-3">
                            <div class="col-span-12 sm:col-span-6">
                                <input type="file" id="file_excel" name="file_excel" class="form-control" accept=".xls,.xlsx">
                                <small class="text-muted">Format file: XLS, XLSX. Max size: 5MB</small>
                            </div>
                            <div class="col-span-12 sm:col-span-6">
                                <button id="btn-upload" type="button" class="btn btn-primary mx-auto shadow-2xl">Upload File</button>
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
                                        <th>Mata Pelajaran</th>
                                        <th>NIP</th>
                                        <th>Nama Guru</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                        <button id="btn-save-all" class="btn btn-success mt-3" style="display:none;">Simpan Data</button>
                        <button id="btn-clear" class="btn btn-danger mt-3" style="display:none;">Hapus Semua Data</button>
                        <button id="btn-clear-invalid" class="btn btn-danger mt-3" style="display:none;">Hapus Data Invalid</button>
                        <a href="manajemen_guru_mapel" id="btn-close" class="btn btn-warning mt-3" style="display:none;">Tutup</a>
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
            loadDataGuruMapelTemp('Y');

            $("#kat-status-valid").change(function() {
                if(this.checked) 
                {
                    loadDataGuruMapelTemp('T');
                }
                else
                {
                    loadDataGuruMapelTemp('Y');
                }
            });

            $("#select-semester").prop("style","background-color:#D3D3D3 !important");
            
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
                $("#loader-overlay").css("display", "flex");
                var formData = new FormData();
                var file = $('#file_excel')[0].files[0];
                formData.append('file_excel', file);

                $.ajax({
                    url: '../proses/proses_preview_upload_subject_teacher.php',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response){
                        var data = JSON.parse(response);
                        if(data.isSuccessful){
                            // showPreview(data.data);
                            loadDataGuruMapelTemp('Y');
                        } else {
                            $("#loader-overlay").css("display", "none");
                            alert(data.msg);
                        }
                    }
                });
            });

            $("#btn-save-all").click(function(){
                if(confirm("Apakah Anda yakin akan menyimpan semua data?")){
                    $("#loader-overlay").css("display", "flex");
                    $.ajax({
                        url: '../proses/proses_save_upload_subject_teacher.php',
                        type: 'POST',
                        data: {},
                        success: function(response){
                            var result = JSON.parse(response);
                            if(result.isSuccessful == true)
                            {
                                $("#loader-overlay").css("display", "none");
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
                var tahunAjaran = $("#select-tahun-ajaran").val();
                var semester       = $("#select-semester").val();
                var jmlRow         = $("#jml-row").val() || 0;

                if(jmlRow <= 1){
                    alert("Jumlah Data Upload harus diisi dengan jumlah lebih dari 1.");
                    return false;
                }

                if(tahunAjaran === "" || semester === ""){
                    alert("Tahun Ajaran dan Semesterharus diisi.");
                    return false;
                }
                else
                {
                    downloadTemplate(tahunAjaran,semester,jmlRow);
                }
            });
        });

        function showPreview(data) {
            window.previewData = data;
            var tbody = "";
            $("#preview-table tbody").html(tbody);
            data.forEach(function(row){
                var statusValid = "";
                if(row.STATUS_VALID == "Y"){
                    statusValid = "<center><img src='../assets/images/check.png' width='30px' title='Valid'></center>";
                } else {
                    statusValid = "<center><img src='../assets/images/cross.png' width='30px' title='Tidak Valid'></center>";
                }
                tbody += `<tr>
                    <td>${statusValid}</td>
                    <td><textarea rows="2" style="font-weight:bolder; min-width:100px; max-width:300px;">`+row.KETERANGAN+`</textarea></td>
                    <td>${formatNull(row.TAHUN_PELAJARAN)}</td>
                    <td>${formatNull(row.SEMESTER)}</td>
                    <td>${formatNull(row.KELAS)}</td>
                    <td>${formatNull(row.NAMA_MAPEL)}</td>
                    <td>${formatNull(row.NIP)}</td>
                    <td>${formatNull(row.NAMA_GURU)}</td>
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

        function loadDataGuruMapelTemp(val) 
        {
            $("#loader-overlay").css("display", "flex");
            $.ajax({
                url: '../proses/proses_get_subject_teacher_temp.php',
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

        function clearDataTemp() 
        {
            $("#loader-overlay").css("display", "flex");
            $.ajax({
                url: '../proses/proses_delete_subject_teacher_temp.php',
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

        function downloadTemplate(tahunAjaran,semester,jmlRow) 
        {
            $("#loader-overlay").css("display", "flex");
            var url = `download_template_subject_teacher.php?tahunAjaran=${encodeURIComponent(tahunAjaran)}&semester=${encodeURIComponent(semester)}&jmlRow=${encodeURIComponent(jmlRow)}`;
            window.location.href = url;
            setTimeout(() => {
                $("#loader-overlay").css("display", "none");
            }, 3000);
        }

        function clearInvalidDataTemp() {
            $("#loader-overlay").css("display", "flex");
            $.ajax({
                url: '../proses/proses_delete_invalid_subject_teacher_temp.php',
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
