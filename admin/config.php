<?php
ob_start();
session_start();
error_reporting(E_ALL);
date_default_timezone_set("Asia/Calcutta");
$website_name = "Kshitij Vivan";
$ProjectName = "KSHITIJ";

if ($_SERVER['SERVER_NAME'] == 'localhost') {

    $dbhost = "localhost";
    $dbuser = "root";
    $dbpass = "";
    $dbname = "kshitij";
    $web_url = 'http://localhost/kshitij/';
    $dbconn = mysqli_connect("$dbhost", "$dbuser", "$dbpass", "$dbname") or die('Could not connect: ' . mysqli_error($dbconn));

    $cateperpaging = 50;
    $mailHost = "mail.getdemo.in";
    $mailUsername = "info@getdemo.in";
    $mailPassword = "info@123";
    $mailSMTPSecure = 'tls';
    $mailFrom = "no-replay@getdemo.in";
    $mailFromName = "LMS";
    $mailAddReplyTo = "no-replay@getdemo.in";
} else if ($_SERVER['SERVER_NAME'] == 'getdemo.in' || $_SERVER['SERVER_NAME'] == 'www.getdemo.in') {

    $dbhost = "localhost";
    $dbuser = "getdemo";
    $dbpass = "kWH7IM-hD)z=";
    $dbname = "getdemo_kshitij";
    $web_url = 'http://getdemo.in/kshitij/';
    $dbconn = mysqli_connect("$dbhost", "$dbuser", "$dbpass", "$dbname") or die('Could not connect: ' . mysqli_error($dbconn));

    $cateperpaging = 50;
    $mailHost = "mail.getdemo.in";
    $mailUsername = "info@getdemo.in";
    $mailPassword = "info@123";
    $mailSMTPSecure = 'tls';
    $mailFrom = "no-replay@getdemo.in";
    $mailFromName = "LMS";
    $mailAddReplyTo = "no-replay@getdemo.in";
} else if ($_SERVER['SERVER_NAME'] == 'kshitijvivan.com' || $_SERVER['SERVER_NAME'] == 'www.kshitijvivan.com') {

    $dbhost = "localhost";
    $dbuser = "kshitijvivan";
    $dbpass = "RVyM{0^83XnK";
    $dbname = "kshitijv_kshitijvivan";
    $web_url = 'http://' . $_SERVER['SERVER_NAME'] . '/';
    $dbconn = mysqli_connect("$dbhost", "$dbuser", "$dbpass", "$dbname") or die('Could not connect: ' . mysqli_error($dbconn));

    $cateperpaging = 50;
    $mailHost = "";
    $mailUsername = "";
    $mailPassword = "";
    $mailSMTPSecure = '';
    $mailFrom = "";
    $mailFromName = "";
    $mailAddReplyTo = "";
}
?>