<?php
include '../includes/session.php';
require '../vendor/autoload.php';
include '../koneksi/koneksi.php';
$v_tahun_pelajaran      = $_GET['tahunAjaran'];
$v_semester             = $_GET['semester'];
$v_kelas                = $_GET['kelas'];
$v_filter               = " AND A.KELAS = TRIM(UPPER('$v_kelas')) AND A.TAHUN_PELAJARAN = '$v_tahun_pelajaran' AND A.SEMESTER = TRIM(UPPER('$v_semester')) ";

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
$sheet->setCellValue('E1', 'NAMA SISWA');
$sheet->setCellValue('F1', 'HADIR');
$sheet->setCellValue('G1', 'SAKIT');
$sheet->setCellValue('H1', 'IZIN');
$sheet->setCellValue('I1', 'TANPA KETERANGAN');

// Style untuk header (bold + background abu-abu muda)
$headerStyle = [
    'font' => ['bold' => true],
    'fill' => [
        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        'startColor' => ['rgb' => 'E0E0E0']
    ]
];
$sheet->getStyle('A1:I1')->applyFromArray($headerStyle);

$sheet->getStyle('A2:J2')->getFont()->setItalic(true)->setBold(true)->setSize(10)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FF0000')); // Italic + Merah

$sheet->getStyle('A2:J2')->applyFromArray([
    'fill' => [
        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        'startColor' => ['rgb' => 'FFFF00']
    ]
]);

// Contoh data pada baris ke-2
$sheet->setCellValue('A2', '');
$sheet->setCellValue('B2', '');
$sheet->setCellValue('C2', '');
$sheet->setCellValue('D2', '');
$sheet->setCellValue('E2', '');
$sheet->setCellValue('F2', '150');
$sheet->setCellValue('G2', 1);
$sheet->setCellValue('H2', 2);
$sheet->setCellValue('I2', 2);
$sheet->setCellValue('J2', '*contoh');

$sheet->getStyle('A1:I1')->applyFromArray([
                                            'borders' => [
                                                'allBorders' => [
                                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                                    'color' => ['rgb' => '000000']
                                                ]
                                            ]
                                        ]);
                                            

$sheet->getStyle('A2:I2')->applyFromArray([
                                            'borders' => [
                                                'allBorders' => [
                                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                                    'color' => ['rgb' => '000000']
                                                ]
                                            ]
                                        ]);

$query_list = "SELECT A.* FROM tb_siswa A WHERE 1=1 $v_filter 
                    AND NOT EXISTS (SELECT 1 FROM tb_absensi_siswa B WHERE 1=1 
                        AND TRIM(UPPER(A.NIS)) = TRIM(UPPER(B.NIS)) 
                        AND TRIM(UPPER(A.KELAS)) = TRIM(UPPER(B.KELAS)) 
                        AND TRIM(UPPER(A.TAHUN_PELAJARAN)) = TRIM(UPPER(B.TAHUN_PELAJARAN)) 
                        AND TRIM(UPPER(A.SEMESTER)) = TRIM(UPPER(B.SEMESTER))
                    )
                ORDER BY A.NAMA_SISWA ASC";  
$db_ext     = mysqli_query($db_connect,$query_list);

$rownum = 3; // Mulai dari baris ke-3 untuk data siswa
if(mysqli_num_rows($db_ext) > 0){
    while ($data = mysqli_fetch_array($db_ext)) 
    {
        $sheet->setCellValue('A'.$rownum, $data['TAHUN_PELAJARAN']);
        $sheet->setCellValue('B'.$rownum, $data['SEMESTER']);
        $sheet->setCellValue('C'.$rownum, $data['KELAS']);
        $sheet->setCellValue('D'.$rownum, $data['NIS']);
        $sheet->setCellValue('E'.$rownum, $data['NAMA_SISWA']);
        $sheet->setCellValue('F'.$rownum, 0, DataType::TYPE_NUMERIC);
        $sheet->setCellValue('G'.$rownum, 0, DataType::TYPE_NUMERIC); 
        $sheet->setCellValue('H'.$rownum, 0, DataType::TYPE_NUMERIC); 
        $sheet->setCellValue('I'.$rownum, 0, DataType::TYPE_NUMERIC); 

        foreach (range('A', 'E') as $col) {
            $sheet->getStyle($col.$rownum)->applyFromArray([
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E0E0E0']
                ]
            ]);
        }

        foreach (range('A', 'E') as $col) {
            $sheet->getStyle($col.$rownum)->getProtection()->setLocked(true); // value
        }

        foreach (range('F', 'I') as $col) {
            $sheet->getStyle($col.$rownum)->getProtection()->setLocked(false); // value
        }

        $sheet->getStyle('A'.$rownum.':I'.$rownum)->applyFromArray([
                                                                    'borders' => [
                                                                        'allBorders' => [
                                                                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                                                            'color' => ['rgb' => '000000']
                                                                        ]
                                                                    ]
                                                                ]);

        $rownum++;
    }
} else {
    $sheet->setCellValue('A3', "-- Data siswa tidak ditemukan atau absensi semua siswa sudah direkam sebelumnya--");
}

// Set lebar kolom agar rapi
foreach (range('A', 'I') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// Aktifkan protection pada sheet
$sheet->getProtection()->setSheet(true); // Aktifkan protection pada sheet
$sheet->getProtection()->setPassword('e-rapor'); // Opsional: set password untuk unlock


// Download file
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="template_upload_absensi_siswa_'.$v_tahun_pelajaran.'_'.$v_semester.'_'.$v_kelas.'.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>