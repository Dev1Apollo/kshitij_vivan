<?php
error_reporting(0);
//include database configuration file
require_once('../config.php');
include('IsLogin.php');
//$connect = new connect();
//get records from database
// $whereEmpId = " and studentadmission.employeeMasterId = '" . $_SESSION['EmployeeId'] . "'";
//get records from database
$whereEmpId = " and studentadmission.branchId = '" . $_SESSION['branchid'] . "'";
$where = "where 1=1 ";
if ($_REQUEST['FormDate'] != NULL && isset($_REQUEST['FormDate']))
    $where.=" and STR_TO_DATE(strEntryDate,'%d-%m-%Y')>= STR_TO_DATE('" . $_REQUEST['FormDate'] . "','%d-%m-%Y')";

if ($_REQUEST['ToDate'] != NULL && isset($_REQUEST['ToDate']))
    $where.=" and STR_TO_DATE(strEntryDate, '%d-%m-%Y')<=STR_TO_DATE('" . $_REQUEST['ToDate'] . "','%d-%m-%Y')";

$filterstr = "SELECT * FROM `company`  " . $where . " and isDelete='0'  and  iStatus='1' order by id desc";

$query = mysqli_query($dbconn,$filterstr);

if (mysqli_num_rows($query) > 0) {
    $delimiter = ",";
    $filename = "Company-Master-Report_" . date('Y-m-d H:i:s') . ".csv";
    //create a file pointer
    $f = fopen('php://memory', 'w');
    //set column headers
    
    $fields = array(
        'Sr.No',
        'Month',
        'Company Name',
        'Contact Person',
        'Mobile',
        'Email',
        'Desgination',
        'Website',
        'Address'
    );

    fputcsv($f, $fields, $delimiter);
    $i = 1;
    //output each row of the data, format line as csv and write to file pointer
    while ($row = mysqli_fetch_assoc($query)) {
        $strEntryDate ="";
        if(isset($row['strEntryDate']) && $row['strEntryDate'] != ""){
	        $strEntryDate = date('M-Y',strtotime($row['strEntryDate']));
	    } else {
	        $strEntryDate = "";
	    }
        $lineData = array(
            $i,
            $strEntryDate,
            $row['strCompanyName'],
            $row['strContactPerson'],
            $row['strContactNumber'],
            $row['strEmail'],
            $row['strDesgination'],
            $row['strWebsite'],
            $row['strAddress']
        );
        fputcsv($f, $lineData, $delimiter);
        $i++;
    }
    fputcsv($f, $Total, $delimiter);
    //move back to beginning of file
    fseek($f, 0);

    //set headers to download file rather than displayed
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '";');

    //output all remaining data on a file pointer
    fpassthru($f);
} else {
    header('location:CompanyReport.php');
}
exit;
?>