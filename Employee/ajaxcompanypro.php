<?php
ob_start();
error_reporting(E_ALL);
require_once '../common.php';
$connect = new connect();
include('IsLogin.php');

$sql = "SELECT company.strCompanyName FROM company
WHERE strCompanyName LIKE '%".$_GET['query']."%'
LIMIT 10";
$result = mysqli_query($dbconn,$sql);


$json = [];
while($row = mysqli_fetch_assoc($result)){
$json[] = $row['strCompanyName'];
}


echo json_encode($json);
?>
