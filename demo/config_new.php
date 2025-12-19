<?php
ob_start();
session_start();
error_reporting(E_ALL);
date_default_timezone_set("Asia/Calcutta");
$website_name = "Kshitij Vivan";
$ProjectName = "KSHITIJ";

    $dbhost = "localhost";
    $dbuser = "kshitijvivan";
    $dbpass = "RVyM{0^83XnK";
    $dbname = "kshitijv_kshitijvivan_demo";
    $web_url = 'http://' . $_SERVER['SERVER_NAME'] . '/demo/';
    $dbconn = mysqli_connect("$dbhost", "$dbuser", "$dbpass", "$dbname") or die('Could not connect: ' . mysqli_connect_error($dbconn));

    $cateperpaging = 50;
    $mailHost = "";
    $mailUsername = "";
    $mailPassword = "";
    $mailSMTPSecure = '';
    $mailFrom = "";
    $mailFromName = "";
    $mailAddReplyTo = "";
?>