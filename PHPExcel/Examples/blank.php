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
$sheet->setCellValue('A1', 'Product Name');
$sheet->setCellValue('B1', 'Price');
    foreach(range('A','B') as $cols){

        $sheet  ->getColumnDimension($cols)
                ->setAutoSize(true);
    }
$row_count = 2;

if (!isset($connections) || !($connections instanceof mysqli)) {
    die('Database connection not available or invalid.');
}

$query = mysqli_query($connections, "SELECT * FROM product");
if ($query) {
    while($row = mysqli_fetch_assoc($query)){
        $product = $row['product'];
        $price = $row['price'];

        foreach(range('A','B') as $cols){

            $sheet  ->getColumnDimension($cols)
                    ->setAutoSize(true);
        }

        $sheet->setCellValue('A'.$row_count, $product);
        $sheet->setCellValue('B'.$row_count, $price);

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