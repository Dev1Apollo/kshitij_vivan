<?php

error_reporting(E_ALL);
require_once('../config.php');
include('IsLogin.php');

$where = "where 1=1 ";
if ($_REQUEST['FormDate'] != NULL && isset($_REQUEST['FormDate']))
    $where.=" and STR_TO_DATE(walkin_datetime,'%d-%m-%Y')>= STR_TO_DATE('" . $_REQUEST['FormDate'] . "','%d-%m-%Y')";
if ($_REQUEST['ToDate'] != NULL && isset($_REQUEST['ToDate']))
    $where.=" and STR_TO_DATE(walkin_datetime, '%d-%m-%Y')<=STR_TO_DATE('" . $_REQUEST['ToDate'] . "','%d-%m-%Y')";
if ($_REQUEST['InquirySource'] != NULL && isset($_REQUEST['InquirySource']) && $_REQUEST['InquirySource'] != 'null')
    $where.=" and lead.customerEntryId in (select customerentry.customerEntryId from customerentry where inquirySourceId in (" . $_REQUEST['InquirySource'] . "))";
$filterstr = "SELECT * FROM `lead`  " . $where . " and isNewInquiry='0' and employeeMasterId='" . $_SESSION['EmployeeId'] . "' and   walkin_datetime != '' order by STR_TO_DATE(walkin_datetime,'%d-%m-%Y') asc";

$query = mysqli_query($dbconn, $filterstr);
if (mysqli_num_rows($query) > 0) {
//    $delimiter = ",";
//    $filename = "Walkin-Report_" . date('Y-m-d H:i:s') . ".csv";
//    $f = fopen('php://memory', 'w');
    $delimiter = ",";
    $filename = "Walkin_Report_" . date('Y-m-d H:i:s') . ".csv";
    //create a file pointer
    $f = fopen('php://memory', 'w');
    $fields = array('Sr.No','Month-Year','Walk-in Date','Lead Unique ID','Customer Name','Customer Mobile','Source Of Lead','Inquiry Status','Remarks');
    fputcsv($f, $fields, $delimiter);
    $i = 1;
    while ($row = mysqli_fetch_assoc($query)) {
        $get_inquirysource = mysqli_fetch_array(mysqli_query($dbconn, "select * from customerentry where customerEntryId = '" . $row['customerEntryId'] . "'"));
        $get_source = mysqli_fetch_array(mysqli_query($dbconn, "select * from inquirysource where inquirySourceId = '" . $get_inquirysource['inquirySourceId'] . "'"));
        $get_Remark = mysqli_fetch_array(mysqli_query($dbconn, "select comment from leadfollowup where leadId = '" . $row['leadId'] . "'  ORDER BY leadFollowupId  DESC LIMIT 1"));

        $customerentry = "SELECT * FROM `customerentry` where isDelete='0'  and  istatus='1' and  customerEntryId='" . $row['customerEntryId'] . "'";
        $resultCustomer = mysqli_query($dbconn, $customerentry);
        $rowCustomer = mysqli_fetch_array($resultCustomer);

        $inquiryStatus = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `status`  where isDelete='0'  and  istatus='1' and statusId='" . $row['statusId'] . "'"));
        $walkin = strtotime($row['walkin_datetime']);
        $lineData = array($i,$data = date('M-Y', $walkin),$row['walkin_datetime'],$row['leaduniqueid'], $rowCustomer['title'] . ' ' . $rowCustomer['firstName'] . ' ' . $rowCustomer['MiddleName'] . ' ' . $rowCustomer['lastName'],$rowCustomer['mobileNo'],$get_source['inquirySourceName'], $inquiryStatus['statusName'],  trim($get_Remark['comment']));
        fputcsv($f, $lineData, $delimiter);
        $i++;
    }
    
    //move back to beginning of file
    fseek($f, 0);
    
    //set headers to download file rather than displayed
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '";');
    
    //output all remaining data on a file pointer
    fpassthru($f);
} else {
    header('location:walkin-report.php?flg=1');
}
exit;
?>