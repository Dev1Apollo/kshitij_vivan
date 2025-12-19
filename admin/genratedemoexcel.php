<?php

//include('config.php');
//include('IsLogin.php');
require_once('../common.php');
$connect = new connect();
include_once('./IsLogin.php');
$filename = 'Sample_Excal.xls';

header("Content-Type: application/vnd.ms-excel");
header("Content-disposition: attachment; filename=" . $filename);

ob_end_clean();

echo
"Title"
 . "\t" . "First Name *"
 . "\t" . "Middle Name"
 . "\t" . "Last Name"
 . "\t" . "Mobile No *"
 . "\t" . "Email"
 . "\t" . "Company Name"
 . "\t" . "State"
 . "\t" . "City"
 . "\n";


echo
"Mr"
. "\t" . "Krunal"
. "\t" . "J."
. "\t" . "Shah"
. "\t" . "9876543210"
. "\t" . "Test@test.com"
. "\t" . "Apollo"
. "\t" . "Gujarat"
. "\t" . "Ahmedabad"
. "\n";