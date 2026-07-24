<?php
$connectionsPath = __DIR__ . '/../../connections.php';
if (!file_exists($connectionsPath)) {
    die("Missing connections.php at $connectionsPath");
}
require_once $connectionsPath;
require '../../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A1', 'Student No');
$sheet->setCellValue('B1', 'Name');
$sheet->setCellValue('C1', 'Tuition Fee');
$sheet->setCellValue('D1', 'Misc. Fee');
    foreach(['A','B','C','D'] as $cols){

        $sheet  ->getColumnDimension($cols)
                ->setAutoSize(true);
    }
$row_count = 2;

if (!isset($connections) || !($connections instanceof mysqli)) {
    die('Database connection not available or invalid.');
}

$query = mysqli_query($connections, "SELECT * FROM student");
if ($query) {
    while($row = mysqli_fetch_assoc($query)){
        $student_No = $row['student_No'];
        $name = $row['name'];
        $tuition_fee = $row['tuition_fee'];
        $misc_fee = $row['misc_fee'];

        foreach(['A','B','C','D'] as $cols){

            $sheet  ->getColumnDimension($cols)
                    ->setAutoSize(true);
        }

        $sheet->setCellValue('A'.$row_count, $student_No);
        $sheet->setCellValue('B'.$row_count, $name);
        $sheet->setCellValue('C'.$row_count, $tuition_fee);
        $sheet->setCellValue('D'.$row_count, $misc_fee);

        $row_count++;
    }
} else {
    die('Query failed: ' . mysqli_error($connections));
}


$writer = new Xlsx($spreadsheet);
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="Plain.xlsx"');
$writer->save('php://output');
exit;

?>