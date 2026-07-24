<?php
include("connections.php");

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
?>

<form method="POST" enctype="multipart/form-data">

<input type="file" name="file" value="">
<br>
<input type="submit" name="btnUpload" value="Upload Product">

</form>

<br>

<a href="PHPExcel/Examples/blank.php">Get</a>

<?php

if(isset($_POST["btnAdd"])){
    
    $price = $_POST["price"];

    foreach($_POST["product_name"] as $index => $value){

        $new_product_name = $value;

        $new_price = $price[$index];

        mysqli_query($connections, "INSERT INTO product(product,price)
        VALUES('$new_product_name', '$new_price')");

        echo "<script>window.location.href='index.php?notify=<font color=green>Product has been uploaded!</font>';</script>";
    }
}

if(isset($_POST["btnUpload"])){
    
    echo '<hr>';

    echo "<table border='1' width='40%'>";
        echo "<tr>

            <td width='70%'><b>Product Name</b></td>
            <td><b>Price</b></td>

            </tr>

            <tr><td colspan='2'><hr></td></tr>

            <form method='POST'>
        ";

        $btnStatus = "ENABLED";

        $filename = $_FILES["file"]["tmp_name"];

        if($_FILES["file"]["size"] > 0){
            
            $spreadsheet = IOFactory::load($filename);
            $worksheet = $spreadsheet->getActiveSheet();

            $row = 1;
                
            $product_name = $price = "";
            $product_nameErr = $priceErr = "";
        
            foreach ($worksheet->getRowIterator() as $excelRow){

                if($row == 1){
                    $row++;
                    continue;
                }
                
                $cellIterator = $excelRow->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false);

                $data = [];

                foreach($cellIterator as $cell){
                    $data[] = $cell->getValue();
                }

                if(empty($data[0])){
                    $product_nameErr = "Product name is empty.";

                    $btnStatus = "DISABLED";

                }else{
                    $product_name = $data[0];
                }

                if(empty($data[1])){
                    $priceErr = "Product price is empty.";

                    $btnStatus = "DISABLED";
                }else{
                    $price = $data[1];
                }
                
                echo "<input type='hidden' name='product_name[]' value='$product_name'>";
                echo "<input type='hidden' name='price[]' value='$price'>";
                    echo "<tr>
                        <td>$product_name</td><br>
                        <td>$price</td>

                    
                    </tr>";
            }
        }
    
    echo "<tr>
    <td>
    $product_nameErr
    <br>
    $priceErr
    </td>;

    <td>
        <div align='right'>
            <input type='submit' $btnStatus name='btnAdd' value='Add this Product'>
        </div>
    
    </td></tr>";
        

    echo "</table>";
}

?>