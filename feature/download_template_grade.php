<?php
require '../vendor/autoload.php';
include '../koneksi/koneksi.php';

$v_id_ekstrakurikuler   = $_GET['idEkstrakurikuler'];
$v_nama_ekstrakurikuler = $_GET['namaEkstrakurikuler'];
$v_tahun_pelajaran      = $_GET['tahunAjaran'];
$v_semester             = $_GET['semester'];
$v_jml_data             = $_GET['jmlData'];

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

// Buat spreadsheet baru
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// $sheet->getStyle('H:H')->getNumberFormat()->setFormatCode('@');
$sheet->getStyle('I:I')->getNumberFormat()->setFormatCode('@');
$sheet->getStyle('J:J')->getNumberFormat()->setFormatCode('@');

// Set header kolom sesuai template
$sheet->setCellValue('A1', 'TAHUN PELAJARAN');
$sheet->setCellValue('B1', 'SEMESTER');
$sheet->setCellValue('C1', 'ID EKSTRAKURIKULER');
$sheet->setCellValue('D1', 'NAMA EKSTRAKURIKULER');
$sheet->setCellValue('E1', 'KELAS');
$sheet->setCellValue('F1', 'NIS');
$sheet->setCellValue('G1', 'NA');


// Style untuk header (bold + background abu-abu muda)
$headerStyle = [
    'font' => ['bold' => true],
    'fill' => [
        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        'startColor' => ['rgb' => 'E0E0E0']
    ]
];
$sheet->getStyle('A1:G1')->applyFromArray($headerStyle);

$sheet->getStyle('A2:H2')->getFont()->setItalic(true)->setBold(true)->setSize(10)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FF0000')); // Italic + Merah


// Contoh data pada baris ke-2
$sheet->setCellValue('A2', '');
$sheet->setCellValue('B2', '');
$sheet->setCellValue('C2', '');
$sheet->setCellValue('D2', '');
$sheet->setCellValue('E2', 'XI.1');
$sheet->setCellValue('F2', '231242');
$sheet->setCellValue('G2', 'A/B/C/D/E');
$sheet->setCellValue('H2', '*contoh');

// Style untuk baris 2-6 (background abu-abu muda)
$sheet->getStyle('A2:H2')->applyFromArray([
    'fill' => [
        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        'startColor' => ['rgb' => 'FFFF00']
    ]
]);

// Style untuk baris 7 (italic + bold)
$sheet->getStyle('A2:H2')->applyFromArray([
    'font' => ['bold' => true, 'italic' => true]
]);


$sheet->getStyle('A1:G1')->applyFromArray([
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
        $sheet->getStyle('E'.$rownum)->getNumberFormat()->setFormatCode('@'); // TEXT
        $sheet->getStyle('F'.$rownum)->getNumberFormat()->setFormatCode('@'); // TEXT
        $sheet->getStyle('G'.$rownum)->getNumberFormat()->setFormatCode('@'); // TEXT
        $sheet->setCellValue('A'.$rownum, $v_tahun_pelajaran);
        $sheet->setCellValue('B'.$rownum, $v_semester);
        $sheet->setCellValue('C'.$rownum, $v_id_ekstrakurikuler);
        $sheet->setCellValue('D'.$rownum, $v_nama_ekstrakurikuler);
        $sheet->setCellValue('E'.$rownum, '');
        $sheet->setCellValue('F'.$rownum, '');
        $sheet->setCellValue('G'.$rownum, '');
        $sheet->setCellValue('H'.$rownum, '');

        foreach (range('A', 'D') as $col) {
            $sheet->getStyle($col.$rownum)->applyFromArray([
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E0E0E0']
                ]
            ]);
        }

        foreach (range('A', 'D') as $col) {
            $sheet->getStyle($col.$rownum)->getProtection()->setLocked(true); // value
        }

        foreach (range('E', 'G') as $col) {
            $sheet->getStyle($col.$rownum)->getProtection()->setLocked(false); // value
        }

        $sheet->getStyle('A'.$rownum.':G'.$rownum)->applyFromArray([
                                                                    'borders' => [
                                                                        'allBorders' => [
                                                                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                                                            'color' => ['rgb' => '000000']
                                                                        ]
                                                                    ]
                                                                ]);

        $rownum++;
}


// $listNilai = ['A', 'B', 'C', 'D', 'E'];
$listNilai = 'A,B,C,D,E';

// $kategoriStr = '"' . implode(',', $listNilai) . '"';

// Isi baris kosong sesuai jumlah yang diminta
$rownum = 3;
for ($i = 3; $i < $v_jml_data + 3; $i++) {
    $sheet->setCellValue('G'.$i, '--Pilih Nilai Akhir--');
}

// Data Validation untuk dropdown
$nilaiAkhir = new \PhpOffice\PhpSpreadsheet\Cell\DataValidation();
$nilaiAkhir->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
$nilaiAkhir->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
$nilaiAkhir->setAllowBlank(true);
$nilaiAkhir->setShowDropDown(true);
$nilaiAkhir->setFormula1('"A,B,C,D,E"');

// Terapkan dropdown ke setiap baris data
for ($i = 3; $i < $v_jml_data + 3; $i++) {
    $sheet->getCell('G'.$i)->setDataValidation(clone $nilaiAkhir);
}

// Set lebar kolom agar rapi
foreach (range('A', 'L') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// Aktifkan protection pada sheet
$sheet->getProtection()->setSheet(true); // Aktifkan protection pada sheet
$sheet->getProtection()->setPassword('e-rapor'); // Opsional: set password untuk unlock

// Download file
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="template_upload_ekstrakurikuler_'.$v_tahun_pelajaran.'_'.$v_semester.'.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>