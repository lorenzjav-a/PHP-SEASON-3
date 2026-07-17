<?php

include("connections.php");

?>

<form method="POST" enctype="multipart/form-data" >

<input type="file" name="file" value="" >
<br>
<input type="submit" name="btnUpload" value="Upload Product">

</form>

<?php

if(isset($_POST["btnAdd"])){

    $name = $_POST["name"];

    foreach($_POST["student_no"] as $index => $value){
        
        $new_student_no = $value;

        $new_name = $name[$index];

        $new_tuition = $name[$index];

        $new_misc = $name[$index];



        mysqli_query($connections, "INSERT INTO enrollment(Student_No., Name, Tuition_Fee, Miscellaneous_Fee )
        VALUES('$new_student_no','$new_name','$new_tuition', '$new_misc')");

        echo "<script>window.location.href='index.php?notify=<font color=green>Student has been uploaded!</font>';</script>";

    }
}


if(isset($_POST["btnUpload"])){
    
    echo "<hr>";

    echo "<table border='1' width='50%'>";

    echo "<tr>
    
        <td width='70%'><b>Student No.</b></td>
        <td width='70%'><b>Name</b></td>
        <td width='70%'><b>Tuition Fee</b></td>
        <td width='70%'><b>Miscellaneous Fee</b></td>
        </tr>

        <tr><td colspan='2'><hr></td></tr>
    
        <form method='POST'>
    
    ";

    $btnStatus = "ENABLED";

    $filename = $_FILES["file"]["tmp_name"];

    if($_FILES["file"]["size" ] > 0){

        $file = fopen($filename, "r");

        $row = 1;

        $student_no = $name = $tuition = $misc = "";

        $student_noErr = $nameErr = $tuitionErr = $miscErr = "";

        while (($data = fgetcsv($file, 10000,",")) !== FALSE) {


            if($row == 1){
                $row++;
                continue;
            }

            if(empty($data[0])) {

                $student_noErr = "Student number is empty";

                $btnStatus = "DISABLED";
                
            }else{

                $student_no = $data[0];

            }

            if(empty($data[0])) {
                $nameErr = "Name is empty";

                $btnStatus = "DISABLED";
            }else{

                $name = $data[0];

            }

            if(empty($data[0])) {
                $tuitionErr = "Tuition Fee is empty";

                $btnStatus = "DISABLED";
            }else{

                $tuition = $data[0];

            }

            if(empty($data[1])) {
                $miscErr = "Miscellaneous Fee is empty";

                $btnStatus = "DISABLED";
            }else{

                $misc = $data[1];

            }

            echo "<input type='hidden' name='student_no[]' value='$student_no'>";

            echo "<input type='hidden' name='name[]' value='$name'>";

            echo "<input type='hidden' name='tuition[]' value='$tuition'>";

            echo "<input type='hidden' name='misc[]' value='$misc'>";


             echo "<tr>
            
             <td>$student_no</td>

             <td>$name</td>

             <td>$tuition</td>

             <td>$misc</td>

            </tr>";

        }

    }

    echo "<tr>

    <td>
    $student_noErr
    <br>
    $nameErr
    <br>
    $tuitionErr
    <br>
    $miscErr
    </td>

    <td> 
        <div align='right'>
            <input type='submit' $btnStatus name='btnAdd' value='Add this Info'>
        <div>
        
    </td></tr>

    </form>

    ";

    echo "</table>";

}

?>