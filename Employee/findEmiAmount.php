<?php
error_reporting(0);
require_once('../config.php');
include('IsLogin.php');

$offeredfee=intval($_GET['offeredfee']);
$noOfEmi=intval($_GET['noOfEmi']);
$stud_id=  intval($_GET['stud_id']);
$joinAmount=  intval($_GET['joinAmount']);



$query=  mysqli_fetch_array(mysqli_query($dbconn,"SELECT * FROM `studentadmission` where stud_id=".$stud_id));

$result=mysqli_query($dbconn,"SELECT booking_amount FROM `lead`  where customerEntryId=".$query['customerEntryId']);
$row=mysqli_fetch_array($result);



$booking_amount = $row['booking_amount'];
$emiAmt = $offeredfee - $joinAmount -$booking_amount;
$totalEMi=$emiAmt / $noOfEmi;

$total=round($totalEMi, 2);


$data='<input type="text" value="'.$total.'" name="emiAmount" onchange="return checkAmount();" id="emiAmount" class="form-control"  placeholder="Enter The Emi Amount"  required>';

echo $data;
?>