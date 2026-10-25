<?php
require '../vendor/autoload.php';
include '../koneksi/koneksi.php';

$v_jml          = isset($_GET['jmlRow']) ? intval($_GET['jmlRow']) : 10;
$v_tahun_ajaran = isset($_GET['tahunAjaran']) ? $_GET['tahunAjaran'] : '';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Buat spreadsheet baru
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set header kolom sesuai template
$sheet->setCellValue('A1', 'Mata Pelajaran');
$sheet->setCellValue('B1', 'Kategori Mata Pelajaran');
$sheet->setCellValue('C1', 'Sub Kategori Mata Pelajaran');
$sheet->setCellValue('D1', 'Tahun Ajaran');

// Style untuk header (bold + background abu-abu muda)
$headerStyle = [
    'font' => ['bold' => true],
    'fill' => [
        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        'startColor' => ['rgb' => 'E0E0E0']
    ]
];
$sheet->getStyle('A1:D1')->applyFromArray($headerStyle);

// Contoh data pada baris ke-2
$sheet->setCellValue('A2', 'Matematika');
$sheet->setCellValue('B2', 'KMP0000001#Kelompok Mata Pelajaran Kejuruan');
$sheet->setCellValue('C2', 'SKMP000001#Projek Ilmu Pengetahuan Alam dan Sosial');
$sheet->setCellValue('D2', '2025/2026');
$sheet->setCellValue('E2', '*contoh');

// Style untuk baris 2-6 (background abu-abu muda)
$sheet->getStyle('A2:E2')->applyFromArray([
    'fill' => [
        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        'startColor' => ['rgb' => 'FFFF00']
    ]
]);

// Border untuk seluruh area data template
$sheet->getStyle('A1:D' . ($v_jml + 2))->applyFromArray([
    'borders' => [
        'allBorders' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            'color' => ['rgb' => '000000']
        ]
    ]
]);

// Style untuk baris 7 (italic + bold)
$sheet->getStyle('E2')->applyFromArray([
    'font' => ['bold' => true, 'italic' => true]
]);


// Query kategori
$query_kategori = "SELECT A.* FROM tb_kategori_mapel A WHERE tahun_pelajaran = '$v_tahun_ajaran' ORDER BY A.ID_KATEGORI_MAPEL";
$db_ext1 = mysqli_query($db_connect, $query_kategori);
$listKategori = [];
while ($row = mysqli_fetch_assoc($db_ext1)) {
    $listKategori[] = $row['ID_KATEGORI_MAPEL'] . '#' . $row['NAMA_KATEGORI_MAPEL'];
}
$kategoriStr = '"' . implode(',', $listKategori) . '"';

// Query sub kategori
$query_sub_kategori = "SELECT X.* FROM (
                            SELECT A.*, (SELECT tahun_pelajaran FROM tb_kategori_mapel WHERE id_kategori_mapel = A.id_kategori_mapel) AS tahun_pelajaran FROM tb_sub_kategori_mapel A ORDER BY A.ID_SUB_KATEGORI_MAPEL
                        ) X
                        WHERE X.tahun_pelajaran = '$v_tahun_ajaran'";
$db_ext2 = mysqli_query($db_connect, $query_sub_kategori);
$listSubKategori = [];
while ($row = mysqli_fetch_assoc($db_ext2)) {
    $listSubKategori[] = $row['ID_SUB_KATEGORI_MAPEL'] . '#' . $row['NAMA_SUB_KATEGORI_MAPEL'];
}
$subKategoriStr = '"' . implode(',', $listSubKategori) . '"';

$sheet->getStyle('D:D')->getProtection()->setLocked(false);

// Isi baris kosong sesuai jumlah yang diminta
$rownum = 3;
for ($i = 3; $i < $v_jml + 3; $i++) {
    $sheet->setCellValue('A'.$i, '');
    $sheet->setCellValue('B'.$i, '--Pilih Kategori--');
    $sheet->setCellValue('C'.$i, '--Pilih Sub Kategori--');
    $sheet->setCellValue('D'.$i, $v_tahun_ajaran);
     // Lindungi kolom D agar tidak bisa diedit
     $sheet->getStyle('D'.$i)->getProtection()->setLocked(true);
     $sheet->getStyle('D'.$i)->applyFromArray([
         'fill' => [
             'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
             'startColor' => ['rgb' => 'E0E0E0']
         ]
     ]);
     // Unlock kolom A, B, C agar bisa diedit
     $sheet->getStyle('A:A')->getProtection()->setLocked(false);
     $sheet->getStyle('B:B')->getProtection()->setLocked(false);
     $sheet->getStyle('C:C')->getProtection()->setLocked(false);
     $sheet->getStyle('A'.$i)->getNumberFormat()->setFormatCode('@'); // TEXT
     $sheet->getStyle('B'.$i)->getNumberFormat()->setFormatCode('@'); // TEXT
     $sheet->getStyle('C'.$i)->getNumberFormat()->setFormatCode('@'); // TEXT
     $sheet->getStyle('D'.$i)->getNumberFormat()->setFormatCode('@'); // TEXT

     $sheet->getStyle('A'.$i.':D'.$i)->applyFromArray([
         'borders' => [
             'allBorders' => [
                 'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                 'color' => ['rgb' => '000000']
             ]
         ]
     ]);
}

// Aktifkan protection pada sheet
$sheet->getProtection()->setSheet(true); // Aktifkan protection pada sheet
$sheet->getProtection()->setPassword('e-rapor'); // Opsional: set password untuk unlock

// Data Validation untuk dropdown
$validationKategori = new \PhpOffice\PhpSpreadsheet\Cell\DataValidation();
$validationKategori->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
$validationKategori->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
$validationKategori->setAllowBlank(true);
$validationKategori->setShowDropDown(true);
$validationKategori->setFormula1($kategoriStr);

$validationSubKategori = new \PhpOffice\PhpSpreadsheet\Cell\DataValidation();
$validationSubKategori->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
$validationSubKategori->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
$validationSubKategori->setAllowBlank(true);
$validationSubKategori->setShowDropDown(true);
$validationSubKategori->setFormula1($subKategoriStr);

// Terapkan dropdown ke setiap baris data
for ($i = 3; $i < $v_jml + 3; $i++) {
    $sheet->getCell('B'.$i)->setDataValidation(clone $validationKategori);
    $sheet->getCell('C'.$i)->setDataValidation(clone $validationSubKategori);
}

// Set lebar kolom agar rapi
foreach (range('A', 'F') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// Download file
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="template_upload_subject.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>