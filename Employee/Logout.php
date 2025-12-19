<?php
require_once '../common.php';
$connect = new connect();
//session_start();
unset($_SESSION['EmployeeId']);
unset($_SESSION['EmployeeName']);
unset($_SESSION['Type']);
unset($_SESSION['EmployeeType']);
unset($_SESSION['LastLoginEmployee']);
header('location:'.$web_url.'Employee/login.php');

?>