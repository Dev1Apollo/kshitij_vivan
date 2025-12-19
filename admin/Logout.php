<?php
error_reporting(E_ALL);
require_once '../common.php';
$connect = new connect();
//session_start();
unset($_SESSION['AdminId']);
unset($_SESSION['AdminName']);
unset($_SESSION['AdminType']);
unset($_SESSION['LastLoginAdmin']);
header('location:'.$web_url.'admin/login.php');
?>