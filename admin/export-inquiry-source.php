<?php

//include database configuration file
require_once('../config.php');
include('IsLogin.php');
//$connect = new connect();
$where = "and 1=1";
$wherebranch = "";

if ($_REQUEST['FormDate'] != NULL && isset($_REQUEST['FormDate']))
    $where.=" and STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y')>= STR_TO_DATE('$_REQUEST[FormDate]','%d-%m-%Y')";
if ($_REQUEST['ToDate'] != NULL && isset($_REQUEST['ToDate']))
    $where.=" and STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y')<=STR_TO_DATE('$_REQUEST[ToDate]','%d-%m-%Y')";
if ($_REQUEST['Employee'] != NULL && isset($_REQUEST['Employee']))
    $where.="  and lead.employeeMasterId= '" . $_REQUEST['Employee'] . "'";
if ($_REQUEST['branch'] != NULL && isset($_REQUEST['branch']))
    $where.= " and lead.employeeMasterId in (select employeeMasterId from employeemaster where employeemaster.branchid=" . $_REQUEST['branch'] . ")";

$whereB = "";
if ($_REQUEST['FormDate'] != NULL && isset($_REQUEST['FormDate']))
    $whereB.=" and STR_TO_DATE(nextFollowupModifyDate,'%d-%m-%Y')>= STR_TO_DATE('$_REQUEST[FormDate]','%d-%m-%Y')";
if ($_REQUEST['ToDate'] != NULL && isset($_REQUEST['ToDate']))
    $whereB.=" and STR_TO_DATE(nextFollowupModifyDate,'%d-%m-%Y')<=STR_TO_DATE('$_REQUEST[ToDate]','%d-%m-%Y')";
if ($_REQUEST['Employee'] != NULL && isset($_REQUEST['Employee']))
    $whereB.="  and lead.employeeMasterId= '" . $_REQUEST['Employee'] . "'";
if ($_REQUEST['branch'] != NULL && isset($_REQUEST['branch']))
    $whereB.= " and lead.employeeMasterId in (select employeeMasterId from employeemaster where employeemaster.branchid=" . $_REQUEST['branch'] . ")";

$whereC = "";
if ($_REQUEST['FormDate'] != NULL && isset($_REQUEST['FormDate']))
    $whereC.=" and STR_TO_DATE(walkin_datetime,'%d-%m-%Y')>= STR_TO_DATE('$_REQUEST[FormDate]','%d-%m-%Y')";
if ($_REQUEST['ToDate'] != NULL && isset($_REQUEST['ToDate']))
    $whereC.=" and STR_TO_DATE(walkin_datetime,'%d-%m-%Y')<=STR_TO_DATE('$_REQUEST[ToDate]','%d-%m-%Y')";
if ($_REQUEST['Employee'] != NULL && isset($_REQUEST['Employee']))
    $whereC.="  and lead.employeeMasterId= '" . $_REQUEST['Employee'] . "'";
if ($_REQUEST['branch'] != NULL && isset($_REQUEST['branch']))
    $whereC.= " and lead.employeeMasterId in (select employeeMasterId from employeemaster where employeemaster.branchid=" . $_REQUEST['branch'] . ")";


//if ($_REQUEST['FormDate'] != NULL && isset($_REQUEST['FormDate']))
//    $where.=" and STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y')>= STR_TO_DATE('$_REQUEST[FormDate]','%d-%m-%Y')";
//if ($_REQUEST['ToDate'] != NULL && isset($_REQUEST['ToDate']))
//    $where.=" and STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y')<=STR_TO_DATE('$_REQUEST[ToDate]','%d-%m-%Y')";
////if ($_REQUEST['branch'] != NULL && isset($_REQUEST['branch']))
////    $where.= " and lead.employeeMasterId in (select employeemaster.employeeMasterId from employeemaster where employeemaster.branchid=" . $_REQUEST['branch'] . ")";
////
//if ($_REQUEST['branch'] != NULL && isset($_REQUEST['branch']))
//    $wherebranch= " and lead.employeeMasterId in (select employeemaster.employeeMasterId from employeemaster where employeemaster.branchid=" . $_REQUEST['branch'] . ")";

$whereA = " and 1=1";
if ($_REQUEST['InquirySource'] != 'null' && $_REQUEST['InquirySource'] != NULL && isset($_REQUEST['InquirySource']) && $_REQUEST['InquirySource'] != '0') {
    $whereA.=" and lead.customerEntryId in (select customerentry.customerEntryId from customerentry where inquirySourceId in (" . $_REQUEST['InquirySource'] . "))";
} else {
    $where.=" and lead.customerEntryId in (select customerentry.customerEntryId from customerentry where inquirySourceId in (select inquirySourceId from inquirysource where isDelete=0))";
}

$filterstr = "select sum((select count(*) from lead l1 where l1.leadId = lead.leadId " . $where . " )) as Inqcount,customerentry.inquirySourceId ,sum((select count(*) from lead l1 where l1.leadId = lead.leadId and l1.statusId=3 " . $whereB . " )) as bookedInq ,"
        . "sum((select l1.booking_amount from lead l1 where l1.leadId = lead.leadId and l1.statusId=3 " . $whereB . ")) as bookingamount,sum((select count(*) from lead l1 where l1.leadId = lead.leadId and l1.walkin_datetime != '' " . $whereC . ")) as walkingcount from lead,"
        . "customerentry where customerentry.customerEntryId = lead.customerEntryId  " . $whereA . "  GROUP by customerentry.inquirySourceId";


//$filterstr = "select sum((select count(*) from lead l1 where l1.leadId = lead.leadId " . $where . " )) as Inqcount,customerentry.inquirySourceId ,sum((select count(*) from lead l1 where l1.leadId = lead.leadId and l1.statusId=3 " . $where . " )) as bookedInq ,"
//        . "sum((select l1.booking_amount from lead l1 where l1.leadId = lead.leadId and l1.statusId=3 " . $where . ")) as bookingamount,sum((select count(*) from lead l1 where l1.leadId = lead.leadId and l1.walkin_datetime != '' " . $where . ")) as walkingcount from lead,"
//        . "customerentry where customerentry.customerEntryId = lead.customerEntryId  " . $whereA . $wherebranch . "  GROUP by customerentry.inquirySourceId";

$query = mysqli_query($dbconn,$filterstr);
//echo $query;
//exit;
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
    $Total = array("0", "0", 0, 0, 0);
    $Total[0] = "Total";
    $Total[1] = "-";
    while ($row = mysqli_fetch_array($query)) {

        $inquirySource = mysqli_fetch_array(mysqli_query($dbconn,"SELECT * FROM `inquirysource`  where isDelete='0'  and  istatus='1' and inquirySourceId='" . $row['inquirySourceId'] . "'"));
        $lineData = array($i,
            $inquirySource['inquirySourceName'],
            $row['Inqcount'],
            $row['bookedInq'],
            $row['bookingamount']);
        $Total[2] = $row['Inqcount'] * 1 + $Total[2] * 1;
        $Total[3] = $row['bookedInq'] * 1 + $Total[3] * 1;
        $Total[4] = $row['bookingamount'] * 1 + $Total[4] * 1;
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
    header('location:InquirySourceReport.php?flg=1');
}
exit;
?>