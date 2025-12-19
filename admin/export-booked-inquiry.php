<?php

//include database configuration file
require_once('../config.php');
include('IsLogin.php');
//$connect = new connect();
//get records from database
$where = "where 1=1";

if ($_REQUEST['FormDate'] != NULL && isset($_REQUEST['FormDate']))
    $where.=" and STR_TO_DATE(nextFollowupModifyDate,'%d-%m-%Y')>= STR_TO_DATE('" . $_REQUEST['FormDate'] . "','%d-%m-%Y')";
else
    $where.=" and DATE_FORMAT(STR_TO_DATE(nextFollowupModifyDate, '%d-%m-%Y'), '%Y-%m-%d')=STR_TO_DATE('" . date('d-m-Y') . "','%d-%m-%Y')";
if ($_REQUEST['ToDate'] != NULL && isset($_REQUEST['ToDate']))
    $where.=" and DATE_FORMAT(STR_TO_DATE(nextFollowupModifyDate, '%d-%m-%Y'), '%Y-%m-%d')<=STR_TO_DATE('" . $_REQUEST['ToDate'] . "','%d-%m-%Y')";

if ($_REQUEST['Employee'] != NULL && isset($_REQUEST['Employee']))
    $where.=" and employeeMasterId ='" . $_REQUEST['Employee'] . "'";
if ($_REQUEST['branch'] != NULL && isset($_REQUEST['branch']))
    $where.= " and employeeMasterId in (select employeeMasterId from employeemaster where employeemaster.branchid=" . $_REQUEST['branch'] . " and istatus=1 and isDelete=0 )";

if ($_REQUEST['InquirySource'] != 'null' && $_REQUEST['InquirySource'] != NULL && isset($_REQUEST['InquirySource']) && $_REQUEST['InquirySource'] != '0') {
    $where.=" and lead.customerEntryId in (select customerentry.customerEntryId from customerentry where inquirySourceId in (" . $_REQUEST['InquirySource'] . "))";
} else {
    $where.=" and lead.customerEntryId in (select customerentry.customerEntryId from customerentry where inquirySourceId in (select inquirySourceId from inquirysource where isDelete=0))";
}

$filterstr = "SELECT * FROM `lead`  " . $where . " and isNewInquiry='0'  and  statusId in ('3') order by STR_TO_DATE(nextFollowupModifyDate,'%d-%m-%Y') desc";
$query = mysqli_query($dbconn,$filterstr);

if (mysqli_num_rows($query) > 0) {
    $delimiter = ",";
    $filename = "Booked_Report_" . date('Y-m-d H:i:s') . ".csv";
    //create a file pointer
    $f = fopen('php://memory', 'w');
    //set column headers
    $fields = array(
        'ID',
        'Lead Unique Id',
        'Customer Name',
        'Employee Name',
        'Mobile No',
        'Email',
        'Source Of Lead',
        'Entry Date',
        'Inquiry Status',
        'Next Follow Up Date',
        'Follow Up Comment',
        'Booked Amount'
    );

    fputcsv($f, $fields, $delimiter);
    $i = 1;
    //output each row of the data, format line as csv and write to file pointer

    $Total = array("Total", "-", "-", "-", "-", "-", "-", "-", "-", "-","-","-");
    $Total[0] = "Total";

    while ($row = mysqli_fetch_assoc($query)) {


        $customerentry = "SELECT * FROM `customerentry` where isDelete='0'  and  istatus='1' and  customerEntryId='" . $row['customerEntryId'] . "'";
        $resultCustomer = mysqli_query($dbconn,$customerentry);
        $rowCustomer = mysqli_fetch_array($resultCustomer);
        $employeemaster = mysqli_fetch_array(mysqli_query($dbconn,"SELECT * FROM `employeemaster`  where isDelete='0'  and  istatus='1' and employeeMasterId='" . $row['employeeMasterId'] . "'"));
        $inquirySource = mysqli_fetch_array(mysqli_query($dbconn,"SELECT * FROM `inquirysource`  where isDelete='0'  and  istatus='1' and inquirySourceId='" . $rowCustomer['inquirySourceId'] . "'"));
        $inquiryStatus = mysqli_fetch_array(mysqli_query($dbconn,"SELECT * FROM `status`  where isDelete='0'  and  istatus='1' and statusId='" . $row['statusId'] . "'"));
        $lineData = array(
            $i,
            $row['leaduniqueid'],
            $rowCustomer['title'] . ' ' . $rowCustomer['firstName'] . ' ' . $rowCustomer['MiddleName'] . ' ' . $rowCustomer['lastName'],
            $employeemaster['employeeName'],
            $rowCustomer['mobileNo'],
            $rowCustomer['email'],
            $inquirySource['inquirySourceName'],
            $row['inquiryEnterDate'],
            $inquiryStatus['statusName'],
            $row['nextFollowupModifyDate'],
            $row['comment'],
            $row['booking_amount']
        );
        $Total[11] = $row['booking_amount'] * 1 + $Total[11] * 1;

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
    header('location:booked-inquiry.php?flg=1');
}
exit;
?>