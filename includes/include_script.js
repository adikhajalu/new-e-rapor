
function generateTahunAjaran2() {
    var today   = new Date();
    var tanggal = today.getFullYear() + "/" + (today.getMonth() + 1) + "/" + today.getDate();
    var options = '<option value="">-- Pilih Tahun Ajaran --</option>';
    $.ajax({
        url: '../proses/proses_get_tahun_ajaran.php',
        type: 'POST',
        data: {
            tanggal     : tanggal
        },
        success: function(response){
            var data = JSON.parse(response);
            if(data.isSuccessful){
                data.data.forEach(function(row){
                    options += `<option value="${row.TAHUN_PELAJARAN}">${row.TAHUN_PELAJARAN}</option>`;
                });
            }
            $("#select-tahun-ajaran").html(options);
            $("#select-tahun-ajaran2").html(options);
        }
    });

}

function generateTahunAjaran() {
    var today = new Date();
    var tahun = today.getFullYear();
    var bulan = today.getMonth() + 1; // January is 0
    var options = '<option value="">-- Pilih Tahun Ajaran --</option>';

    // Jika bulan >= 7 (Juli) maka tahun ajaran dimulai dari tahun ini
    // Jika bulan < 7 maka tahun ajaran dimulai dari tahun sebelumnya
    var tahunMulai = bulan >= 7 ? tahun : tahun - 1;

    // Generate 3 tahun ajaran dari yang terbaru (dibalik urutannya)
    for (var i = 0; i >= -2; i--) {
        var tahunAwal = tahunMulai + i;
        var tahunAkhir = tahunAwal + 1;
        var tahunAjaran = tahunAwal + "/" + tahunAkhir;

        options += "<option value='" + tahunAjaran + "'>" + tahunAjaran + "</option>";
    }

    $("#select-tahun-ajaran").html(options);
    $("#select-tahun-ajaran2").html(options);
}

function placeholderSearch(value) {
    var text = $("#search-type option[value='" + value + "']").text();

    if (text === "--Pilih--" || text === "--Pencari--") {
        $("#search-val").attr("placeholder", "Pilih Pencari...");
        return;
    }

    $("#search-val").attr("placeholder", "Cari " + text + "...");

}

function formatNull(val) {
    if (val === "-" || val === "--" || val === "" || val === null || val === undefined) {
        return "";
    }
    return val;
}

function escapeHtmlAttr(value) {
    return String(value)
        .replace(/&/g, "&amp;")
        .replace(/'/g, "&#39;")
        .replace(/"/g, "&quot;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;");
}
