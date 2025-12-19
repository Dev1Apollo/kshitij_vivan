<?php

//include database configuration file
require_once('../config.php');
include('IsLogin.php');
//$connect = new connect();
$where = "where 1=1 and lead.employeeMasterId = '" . $_SESSION['EmployeeId'] . "'";

//get records from database
if ($_REQUEST['FormDate'] != NULL && isset($_REQUEST['FormDate']))
    $where.=" and STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y')>= STR_TO_DATE('$_REQUEST[FormDate]','%d-%m-%Y')";
if ($_REQUEST['ToDate'] != NULL && isset($_REQUEST['ToDate']))
    $where.=" and STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y')<=STR_TO_DATE('$_REQUEST[ToDate]','%d-%m-%Y')";
if ($_REQUEST['InquirySource'] != NULL && isset($_REQUEST['InquirySource']))
    $where.=" and customerentry.customerEntryId in (select customerentry.customerEntryId from customerentry where inquirySourceId='" . $_REQUEST['InquirySource'] . "')";


$whereA = "where 1=1";
if ($_REQUEST['FormDate'] != NULL && isset($_REQUEST['FormDate']))
    $whereA.=" and STR_TO_DATE(l2.nextFollowupModifyDate,'%d-%m-%Y')>= STR_TO_DATE('$_REQUEST[FormDate]','%d-%m-%Y')";
if ($_REQUEST['ToDate'] != NULL && isset($_REQUEST['ToDate']))
    $whereA.=" and STR_TO_DATE(l2.nextFollowupModifyDate,'%d-%m-%Y')<=STR_TO_DATE('$_REQUEST[ToDate]','%d-%m-%Y')";
if ($_REQUEST['InquirySource'] != NULL && isset($_REQUEST['InquirySource']))
    $where.=" and lead.customerEntryId in (select customerentry.customerEntryId from customerentry where inquirySourceId='" . $_REQUEST['InquirySource'] . "')";


$filterstr = "SELECT count(*) as Inqcount,sum(booking_amount) as bookingamount,
customerentry.inquirySourceId as inquirySourceId
,(select count(*) from lead as l2,customerentry as c2  " . $whereA . " and customerentry.customerEntryId = lead.customerEntryId and l2.statusId = 3 and c2.inquirySourceId = customerentry.inquirySourceId
) as bookedInq
FROM `lead`,customerentry  
" . $where . " and customerentry.customerEntryId = lead.customerEntryId GROUP by customerentry.inquirySourceId";


$query = mysqli_query($dbconn,$filterstr);
if (mysqli_num_rows($query) > 0) {
    $delimiter = ",";
    $filename = "inquiry-source" . date('Y-m-d H:i:s') . ".csv";
    //create a file pointer
    $f = fopen('php://memory', 'w');
    //set column headers
    $fields = array('ID', 'Inquiry Source', 'Total Inquiry', 'Booked Inquiry', 'Booked Amount');
    fputcsv($f, $fields, $delimiter);
    $i = 1;
    //output each row of the data, format line as csv and write to file pointer
    while ($row = mysqli_fetch_assoc($query)) {

        $inquirySource = mysqli_fetch_array(mysqli_query($dbconn,"SELECT * FROM `inquirysource`  where isDelete='0'  and  istatus='1' and inquirySourceId='" . $row['inquirySourceId'] . "'"));
        $lineData = array($i, $inquirySource['inquirySourceName'], $row['Inqcount'], $row['bookedInq'], $row['bookingamount']);
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
    header('location:InquirySourceReport.php?flg=1');
}
exit;
?>