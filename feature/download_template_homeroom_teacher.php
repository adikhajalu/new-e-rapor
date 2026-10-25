<?php
require '../vendor/autoload.php';
include '../koneksi/koneksi.php';

$v_tahun_pelajaran  = $_GET['tahunAjaran'];
$v_jml              = isset($_GET['jmlRow']) ? intval($_GET['jmlRow']) : 10;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Buat spreadsheet baru
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set header kolom sesuai template
$sheet->setCellValue('A1', 'Tahun Ajaran');
$sheet->setCellValue('B1', 'Kelas');
$sheet->setCellValue('C1', 'NIP Guru Wali Kelas');

// Style untuk header (bold + background abu-abu muda)
$headerStyle = [
    'font' => ['bold' => true],
    'fill' => [
        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        'startColor' => ['rgb' => 'E0E0E0']
    ]
];
$sheet->getStyle('A1:C1')->applyFromArray($headerStyle);

// Contoh data pada baris ke-2
$sheet->setCellValue('A2', '2024/2025');
$sheet->setCellValue('B2', 'X.3');
$sheet->setCellValue('C2', '1992061XXXXXXX002');
$sheet->setCellValue('D2', '*Contoh');
$sheet->getStyle('A2')->getNumberFormat()->setFormatCode('@'); // TEXT
$sheet->getStyle('B2')->getNumberFormat()->setFormatCode('@'); // TEXT
$sheet->getStyle('C2')->getNumberFormat()->setFormatCode('@'); // TEXT
$sheet->getStyle('D2')->getNumberFormat()->setFormatCode('@'); // TEXT
$sheet->getStyle('A2:D2')->getFont()->setItalic(true)->setBold(true)->setSize(10)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FF0000')); // Italic + Merah

// Style untuk baris 2-6 (background abu-abu muda)
$sheet->getStyle('A2:D2')->applyFromArray([
    'fill' => [
        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        'startColor' => ['rgb' => 'FFFF00']
    ]
]);

// Border untuk seluruh area data template
$sheet->getStyle('A1:C' . ($v_jml + 2))->applyFromArray([
    'borders' => [
        'allBorders' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            'color' => ['rgb' => '000000']
        ]
    ]
]);

$sheet->getStyle('A:C')->getProtection()->setLocked(false);

// Isi baris kosong sesuai jumlah yang diminta
$rownum = 3;
for ($i = 3; $i < $v_jml + 3; $i++) {
    $sheet->setCellValue('A'.$i, $v_tahun_pelajaran);
    $sheet->setCellValue('B'.$i, '');
    $sheet->setCellValue('C'.$i, '');

    $sheet->getStyle('A'.$i)->getNumberFormat()->setFormatCode('@'); // TEXT
    $sheet->getStyle('B'.$i)->getNumberFormat()->setFormatCode('@'); // TEXT
    $sheet->getStyle('C'.$i)->getNumberFormat()->setFormatCode('@'); // TEXT
    
    // Lindungi kolom A agar tidak bisa diedit
    $sheet->getStyle('A'.$i)->getProtection()->setLocked(true);
    $sheet->getStyle('A'.$i)->applyFromArray([
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => ['rgb' => 'E0E0E0']
        ]
    ]);
    
    $sheet->getStyle('A'.$i.':C'.$i)->applyFromArray([
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

// Atur lebar kolom secara otomatis sesuai konten
$sheet->getColumnDimension('A')->setAutoSize(true);
$sheet->getColumnDimension('B')->setAutoSize(true);
$sheet->getColumnDimension('C')->setAutoSize(true);
$sheet->getColumnDimension('D')->setAutoSize(true);

// Download file
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="template_guru_wali_kelas.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>