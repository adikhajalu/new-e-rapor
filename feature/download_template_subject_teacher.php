<?php
require '../vendor/autoload.php';
include '../koneksi/koneksi.php';

$v_tahun_pelajaran  = $_GET['tahunAjaran'];
$v_semester         = $_GET['semester'];
$v_jml              = isset($_GET['jmlRow']) ? intval($_GET['jmlRow']) : 10;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Buat spreadsheet baru
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set header kolom sesuai template
$sheet->setCellValue('A1', 'Tahun Ajaran');
$sheet->setCellValue('B1', 'Semester');
$sheet->setCellValue('C1', 'Kelas');
$sheet->setCellValue('D1', 'Mata Pelajaran');
$sheet->setCellValue('E1', 'NIP Guru Mapel');

// Style untuk header (bold + background abu-abu muda)
$headerStyle = [
    'font' => ['bold' => true],
    'fill' => [
        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        'startColor' => ['rgb' => 'E0E0E0']
    ]
];
$sheet->getStyle('A1:E1')->applyFromArray($headerStyle);

// Contoh data pada baris ke-2
$sheet->setCellValue('A2', '2024/2025');
$sheet->setCellValue('B2', 'Gasal');
$sheet->setCellValue('C2', 'X.3');
$sheet->setCellValue('D2', 'MP0000002.1#Bahasa Inggris');
$sheet->setCellValue('E2', '199506882024444002 ');
$sheet->getStyle('A2')->getNumberFormat()->setFormatCode('@'); // TEXT
$sheet->getStyle('B2')->getNumberFormat()->setFormatCode('@'); // TEXT
$sheet->getStyle('C2')->getNumberFormat()->setFormatCode('@'); // TEXT
$sheet->getStyle('D2')->getNumberFormat()->setFormatCode('@'); // TEXT
$sheet->getStyle('E2')->getNumberFormat()->setFormatCode('@'); // TEXT

// Style untuk baris 2-6 (background abu-abu muda)
$sheet->getStyle('A2:E2')->applyFromArray([
    'fill' => [
        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        'startColor' => ['rgb' => 'FFFF00']
    ]
]);

// Border untuk seluruh area data template
$sheet->getStyle('A1:E' . ($v_jml + 2))->applyFromArray([
    'borders' => [
        'allBorders' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            'color' => ['rgb' => '000000']
        ]
    ]
]);

$sheet->getStyle('A:E')->getProtection()->setLocked(false);


// Query kategori
$query_kategori = "SELECT A.* FROM tb_mapel A WHERE A.TAHUN_PELAJARAN = '$v_tahun_pelajaran' AND A.SEMESTER = '$v_semester' ORDER BY A.NAMA_MAPEL";
$db_ext1 = mysqli_query($db_connect, $query_kategori);
$listMapel = [];
while ($row = mysqli_fetch_assoc($db_ext1)) {
    // $listMapel[] = $row['ID_MAPEL_SEMESTER'] . '#' . $row['NAMA_MAPEL'];
    $namaMapel = str_replace(', ', '，', $row['NAMA_MAPEL']);
    $listMapel[] = $row['ID_MAPEL_SEMESTER'] . '#' . $namaMapel;
}
$mapelStr = '"' . implode(',', $listMapel) . '"';

// Isi baris kosong sesuai jumlah yang diminta
$rownum = 3;
for ($i = 3; $i < $v_jml + 3; $i++) {
    $sheet->setCellValue('A'.$i, $v_tahun_pelajaran);
    $sheet->setCellValue('B'.$i, $v_semester);
    $sheet->setCellValue('C'.$i, '');
    $sheet->setCellValue('D'.$i, '--Pilih Mapel--');
    $sheet->setCellValue('E'.$i, '');

    
    $sheet->getStyle('A'.$i)->getNumberFormat()->setFormatCode('@'); // TEXT
    $sheet->getStyle('B'.$i)->getNumberFormat()->setFormatCode('@'); // TEXT
    $sheet->getStyle('C'.$i)->getNumberFormat()->setFormatCode('@'); // TEXT
    $sheet->getStyle('D'.$i)->getNumberFormat()->setFormatCode('@'); // TEXT
    $sheet->getStyle('E'.$i)->getNumberFormat()->setFormatCode('@'); // TEXT

    // Lindungi kolom A agar tidak bisa diedit
    $sheet->getStyle('A'.$i)->getProtection()->setLocked(true);
    $sheet->getStyle('A'.$i)->applyFromArray([
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => ['rgb' => 'E0E0E0']
        ]
    ]);

    $sheet->getStyle('B'.$i)->getProtection()->setLocked(true);
    $sheet->getStyle('B'.$i)->applyFromArray([
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => ['rgb' => 'E0E0E0']
        ]
    ]);
    
    $sheet->getStyle('C'.$i)->getProtection()->setLocked(false);
    $sheet->getStyle('D'.$i)->getProtection()->setLocked(false);
    $sheet->getStyle('E'.$i)->getProtection()->setLocked(false);
    
    
    $sheet->getStyle('A'.$i.':E'.$i)->applyFromArray([
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
$validasiMapel = new \PhpOffice\PhpSpreadsheet\Cell\DataValidation();
$validasiMapel->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
$validasiMapel->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
$validasiMapel->setAllowBlank(true);
$validasiMapel->setShowDropDown(true);
$validasiMapel->setFormula1($mapelStr);

// Terapkan dropdown ke setiap baris data
for ($i = 3; $i < $v_jml + 3; $i++) {
    $sheet->getCell('D'.$i)->setDataValidation(clone $validasiMapel);
}

// Set lebar kolom agar rapi
foreach (range('A', 'F') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// Download file
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="template_guru_mapel.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>