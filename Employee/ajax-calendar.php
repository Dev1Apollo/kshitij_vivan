<?php
ob_start();
error_reporting(E_ALL);
require_once '../common.php';
$connect = new connect();
include('IsLogin.php');
$_POST['date']=date('d-m-Y', strtotime($_POST['date']));
$get_count_totalcount=mysqli_fetch_array(mysqli_query($dbconn,"select COUNT(*) as totalcount from lead where statusId in ('1','6') and STR_TO_DATE(nextFollowupDate,'%d-%m-%Y') =  STR_TO_DATE('".$_POST['date']."','%d-%m-%Y') and employeeMasterId = '".$_SESSION['EmployeeId']."'"));
$get_count_callback=mysqli_fetch_array(mysqli_query($dbconn,"select COUNT(*) as totalcountcallback from lead where statusId in ('1') and STR_TO_DATE(nextFollowupDate,'%d-%m-%Y') =  STR_TO_DATE('".$_POST['date']."','%d-%m-%Y') and employeeMasterId = '".$_SESSION['EmployeeId']."'"));
$get_count_walkin=mysqli_fetch_array(mysqli_query($dbconn,"select COUNT(*) as totalcountwalkin from lead where statusId in ('6') and STR_TO_DATE(nextFollowupDate,'%d-%m-%Y') =  STR_TO_DATE('".$_POST['date']."','%d-%m-%Y') and employeeMasterId = '".$_SESSION['EmployeeId']."'"));
echo $get_count_totalcount['totalcount'].','.$get_count_callback['totalcountcallback'].','.$get_count_walkin['totalcountwalkin'];

?>