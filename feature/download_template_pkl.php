<?php
require '../vendor/autoload.php';
include '../koneksi/koneksi.php';

$v_tahun_pelajaran      = $_GET['tahunAjaran'];
$v_semester             = $_GET['semester'];
$v_jml_data             = $_GET['jmlData'];

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

// Buat spreadsheet baru
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set header kolom sesuai template
$sheet->setCellValue('A1', 'TAHUN PELAJARAN');
$sheet->setCellValue('B1', 'SEMESTER');
$sheet->setCellValue('C1', 'KELAS');
$sheet->setCellValue('D1', 'NIS');
$sheet->setCellValue('E1', 'MITRA DU/DI');
$sheet->setCellValue('F1', 'LOKASI');


// Style untuk header (bold + background abu-abu muda)
$headerStyle = [
    'font' => ['bold' => true],
    'fill' => [
        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        'startColor' => ['rgb' => 'E0E0E0']
    ]
];
$sheet->getStyle('A1:F1')->applyFromArray($headerStyle);

$sheet->getStyle('A2:G2')->getFont()->setItalic(true)->setBold(true)->setSize(10)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FF0000')); // Italic + Merah


// Contoh data pada baris ke-2
$sheet->setCellValue('A2', '');
$sheet->setCellValue('B2', '');
$sheet->setCellValue('C2', 'XIII.1');
$sheet->setCellValue('D2', '227999');
$sheet->setCellValue('E2', 'PT MEGA KARYA SARANA');
$sheet->setCellValue('F2', 'SEMARANG');
$sheet->setCellValue('G2', '*contoh');

// Style untuk baris 2-6 (background abu-abu muda)
$sheet->getStyle('A2:G2')->applyFromArray([
    'fill' => [
        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        'startColor' => ['rgb' => 'FFFF00']
    ]
]);

// Style untuk baris 7 (italic + bold)
$sheet->getStyle('A2:G2')->applyFromArray([
    'font' => ['bold' => true, 'italic' => true]
]);


$sheet->getStyle('A1:F1')->applyFromArray([
                                            'borders' => [
                                                'allBorders' => [
                                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                                    'color' => ['rgb' => '000000']
                                                ]
                                            ]
                                        ]);
                                            

$sheet->getStyle('A2:G2')->applyFromArray([
                                            'borders' => [
                                                'allBorders' => [
                                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                                    'color' => ['rgb' => '000000']
                                                ]
                                            ]
                                        ]);

$rownum = 3;
for($i=3; $i<$v_jml_data + 3; $i++){
        $sheet->setCellValue('A'.$rownum, $v_tahun_pelajaran);
        $sheet->setCellValue('B'.$rownum, $v_semester);
        $sheet->setCellValue('C'.$rownum, '');
        $sheet->setCellValue('D'.$rownum, '');
        $sheet->setCellValue('E'.$rownum, '');
        $sheet->setCellValue('F'.$rownum, '');

        foreach (range('A', 'B') as $col) {
            $sheet->getStyle($col.$rownum)->applyFromArray([
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E0E0E0']
                ]
            ]);
        }

        foreach (range('A', 'B') as $col) {
            $sheet->getStyle($col.$rownum)->getProtection()->setLocked(true); // value
        }

        foreach (range('C', 'F') as $col) {
            $sheet->getStyle($col.$rownum)->getProtection()->setLocked(false); // value
        }

        $sheet->getStyle('A'.$rownum.':F'.$rownum)->applyFromArray([
                                                                    'borders' => [
                                                                        'allBorders' => [
                                                                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                                                            'color' => ['rgb' => '000000']
                                                                        ]
                                                                    ]
                                                                ]);

        $rownum++;
}


// Set lebar kolom agar rapi
foreach (range('A', 'F') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// Aktifkan protection pada sheet
$sheet->getProtection()->setSheet(true); // Aktifkan protection pada sheet
$sheet->getProtection()->setPassword('e-rapor'); // Opsional: set password untuk unlock

// Download file
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="template_upload_pkl_'.$v_tahun_pelajaran.'_'.$v_semester.'.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>