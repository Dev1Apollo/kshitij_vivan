<?php
error_reporting(0);
require_once('../config.php');
include('IsLogin.php');

$emiAmount = intval($_GET['emiAmount']);

$offeredfee = intval($_GET['offeredfee']);

$noOfEmi = intval($_GET['noOfEmi']);

$stud_id = intval($_GET['stud_id']);

$joinAmount = intval($_GET['joinAmount']);

$noOfEmi = intval($_GET['noOfEmi']);


$query=  mysqli_fetch_array(mysqli_query($dbconn,"SELECT * FROM `studentadmission` where stud_id=".$stud_id));

$result=mysqli_query($dbconn,"SELECT booking_amount FROM `lead`  where customerEntryId=".$query['customerEntryId']);

$row=mysqli_fetch_array($result);



$booking_amount = $row['booking_amount'];
$tokenAmount=$joinAmount+$booking_amount;
$emiAmt = $offeredfee - $tokenAmount;
$totalEMi=$emiAmt / $noOfEmi;

$total=round($totalEMi, 2);

$maxAmount = $total + $noOfEmi;
$minAmount = $total - $noOfEmi;

if($maxAmount < $emiAmount){
    echo 'Amount Is Bigger Than The Given Amount.';
}
else if ($minAmount > $emiAmount)
{
    echo 'Amount Is Lass Than The Given Amount.';
}
else{
    echo '0';
}