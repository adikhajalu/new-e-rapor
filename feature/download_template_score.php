<?php
require '../vendor/autoload.php';
include '../koneksi/koneksi.php';

$v_id_mapel_semester= $_GET['idMapelSemester'];
$v_nama_mapel       = $_GET['namaMapel'];
$v_tahun_pelajaran  = $_GET['tahunAjaran'];
$v_semester         = $_GET['semester'];
$v_kelas            = $_GET['kelas'];
$v_filter           = " AND A.KELAS = TRIM(UPPER('$v_kelas')) AND A.TAHUN_PELAJARAN = '$v_tahun_pelajaran' AND A.SEMESTER = '$v_semester' ";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Buat spreadsheet baru
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set header kolom sesuai template
$sheet->setCellValue('A1', 'ID MAPEL');
$sheet->setCellValue('B1', 'NAMA MAPEL');
$sheet->setCellValue('C1', 'NIS');
$sheet->setCellValue('D1', 'NAMA SISWA');
$sheet->setCellValue('E1', 'KELAS');
$sheet->setCellValue('F1', 'TAHUN PELAJARAN');
$sheet->setCellValue('G1', 'SEMESTER');
$sheet->setCellValue('H1', 'NA');
$sheet->setCellValue('I1', 'CP+');
$sheet->setCellValue('J1', 'CP-');

$sheet->getStyle('A1:K1')->applyFromArray([
                                            'font' => ['bold' => true],
                                            'fill' => [
                                                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                                                'startColor' => ['rgb' => 'E0E0E0']
                                            ]
                                        ]);

foreach (range('A','K') as $col) {
    $sheet->getStyle($col.'2')->getNumberFormat()->setFormatCode('@'); // TEXT
}

$sheet->getStyle('H2')->getNumberFormat()->setFormatCode('0.00');

$sheet->getStyle('A2:K2')->getFont()->setItalic(true)->setBold(true)->setSize(10)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FF0000')); // Italic + Merah

$sheet->setCellValue('A2', 'MP0000001.1');
$sheet->setCellValue('B2', 'Analisis Kimia');
$sheet->setCellValue('C2', '1242342444');
$sheet->setCellValue('D2', 'Rocky Bastian');
$sheet->setCellValue('E2', 'X.2');
$sheet->setCellValue('F2', '2025/2026');
$sheet->setCellValue('G2', 'Gasal');
$sheet->setCellValue('H2', '92.20');
$sheet->setCellValue('I2', '');
$sheet->setCellValue('J2', '');
$sheet->setCellValue('K2', '*Contoh');

$sheet->getStyle('A2:K2')->applyFromArray([
    'fill' => [
        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        'startColor' => ['rgb' => 'FFFF00']
    ]
]);

$sheet->getStyle('A1:J1')->applyFromArray([
                                            'borders' => [
                                                'allBorders' => [
                                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                                    'color' => ['rgb' => '000000']
                                                ]
                                            ]
                                        ]);
                                            

$sheet->getStyle('A2:J2')->applyFromArray([
                                            'borders' => [
                                                'allBorders' => [
                                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                                    'color' => ['rgb' => '000000']
                                                ]
                                            ]
                                        ]);

$query_list = "SELECT A.* FROM tb_siswa A WHERE 1=1 $v_filter 
                    AND NOT EXISTS (SELECT 1 FROM tb_penilaian B WHERE 1=1 
                        AND TRIM(UPPER(A.NIS)) = TRIM(UPPER(B.NIS)) 
                        AND TRIM(UPPER(A.KELAS)) = TRIM(UPPER(B.KELAS)) 
                        AND TRIM(UPPER(A.TAHUN_PELAJARAN)) = TRIM(UPPER(B.TAHUN_PELAJARAN)) 
                        AND TRIM(UPPER(A.SEMESTER)) = TRIM(UPPER(B.SEMESTER))
                        AND TRIM(UPPER(B.ID_MAPEL_SEMESTER)) = TRIM(UPPER('$v_id_mapel_semester'))
                    )
                ORDER BY A.NIS";   
$db_ext     = mysqli_query($db_connect,$query_list);

$rownum = 3;
if(mysqli_num_rows($db_ext) > 0){
    while ($data = mysqli_fetch_array($db_ext)) 
    {
        foreach (range('A','G') as $col) {
            $sheet->getStyle($col.$rownum)->getNumberFormat()->setFormatCode('@'); // TEXT
        }
        
        $sheet->getStyle('H'.$rownum)->getNumberFormat()->setFormatCode('0.00'); // NUMBER

        foreach (range('I', 'J') as $col) {
            $sheet->getStyle($col.$rownum)->getNumberFormat()->setFormatCode('@'); // TEXT
        }
        
        foreach (range('A', 'G') as $col) {
            $sheet->getStyle($col.$rownum)->applyFromArray([
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E0E0E0']
                ]
            ]);
        }

        $sheet->setCellValue('A'.$rownum, $v_id_mapel_semester);
        $sheet->setCellValue('B'.$rownum, $v_nama_mapel);
        $sheet->setCellValue('C'.$rownum, $data['NIS']);
        $sheet->setCellValue('D'.$rownum, $data['NAMA_SISWA']);
        $sheet->setCellValue('E'.$rownum, $data['KELAS']);
        $sheet->setCellValue('F'.$rownum, $data['TAHUN_PELAJARAN']);
        $sheet->setCellValue('G'.$rownum, $data['SEMESTER']);
        $sheet->setCellValue('H'.$rownum, '');
        $sheet->setCellValue('I'.$rownum, ''); 
        $sheet->setCellValue('J'.$rownum, '');


        foreach (range('A', 'G') as $col) {
            $sheet->getStyle($col.$rownum)->getProtection()->setLocked(true); // value
        }

        foreach (range('H', 'J') as $col) {
            $sheet->getStyle($col.$rownum)->getProtection()->setLocked(false); // value
        }

        $sheet->getStyle('A'.$rownum.':J'.$rownum)->applyFromArray([
                                                                    'borders' => [
                                                                        'allBorders' => [
                                                                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                                                            'color' => ['rgb' => '000000']
                                                                        ]
                                                                    ]
                                                                ]);

        $rownum++;
    }
} 



// Set lebar kolom agar rapi
foreach (range('A', 'H') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}
// Set lebar kolom I dan J lebih lebar secara manual
$sheet->getColumnDimension('I')->setWidth(25);
$sheet->getColumnDimension('J')->setWidth(25);

// Aktifkan protection pada sheet
$sheet->getProtection()->setSheet(true); // Aktifkan protection pada sheet
$sheet->getProtection()->setPassword('e-rapor'); // Opsional: set password untuk unlock

// Download file
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="template_upload_nilai_mapel_'.$v_nama_mapel.'_'.$v_tahun_pelajaran.'_'.$v_semester.'_'.$v_kelas.'.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>