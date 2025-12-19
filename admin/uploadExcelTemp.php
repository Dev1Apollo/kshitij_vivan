<?php

require_once '../common.php';
$connect = new connect();
$HospitalCoverImages = 'temp/';

if (!file_exists($HospitalCoverImages)) {
    mkdir($HospitalCoverImages, 0777, TRUE);
}

$imageno = $_POST['galeryID'];
$fname = 'gallery';
$valid_formats = array("xls", "xlsx");
if (isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST") {
    $name = $_FILES[$fname]['name'];
    if (strlen($name)) {
//       echo $name;
        //list($txt, $ext) = explode(".", $name);
        $ext = pathinfo($name, PATHINFO_EXTENSION);
        $ext = strtolower($ext);
        if (in_array($ext, $valid_formats)) {
            $actual_image_name = rand(1000, 9999) . "_" . time() . "." . $ext;
            $tmp = $_FILES[$fname]['tmp_name'];
            if (move_uploaded_file($tmp, $HospitalCoverImages . $actual_image_name)) {
                echo "<input type='hidden' value='" . $actual_image_name . "' name='IM" . $fname . "' class='ImgName' id='IM" . $fname . "' >";
                echo "<div class='GalleryBoxSingle' id='GalleryBox$imageno' >";
            } else
                echo "failed";
        } else
            echo "<button class='btn btn-danger' type='button'>Please Select XLS,XLSX  File Only</button>";
    } else
        echo "<button class='btn btn-danger' type='button'>Please Select XLS,XLSX File</button>";
    exit;
}
?>