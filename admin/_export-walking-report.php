<?php
require_once('../config.php');
include('IsLogin.php');
//$connect = new connect();
//get records from database
$where = "where 1=1 ";

if ($_REQUEST['FormDate'] != NULL && isset($_REQUEST['FormDate']))
    $where.=" and STR_TO_DATE(walkin_datetime,'%d-%m-%Y')>= STR_TO_DATE('" . $_REQUEST['FormDate'] . "','%d-%m-%Y')";

if ($_REQUEST['ToDate'] != NULL && isset($_REQUEST['ToDate']))
    $where.=" and DATE_FORMAT(STR_TO_DATE(walkin_datetime, '%d-%m-%Y'), '%Y-%m-%d')<=STR_TO_DATE('" . $_REQUEST['ToDate'] . "','%d-%m-%Y')";
if ($_REQUEST['Employee'] != NULL && isset($_REQUEST['Employee']))
    $where.=" and lead.employeeMasterId ='" . $_REQUEST['Employee'] . "'";
if ($_REQUEST['branch'] != NULL && isset($_REQUEST['branch']))
    $where.= " and employeeMasterId in (select employeeMasterId from employeemaster where employeemaster.branchid=" . $_REQUEST['branch'] . " and istatus=1 and isDelete=0 )";

if ($_REQUEST['InquirySource'] != 'null' && $_REQUEST['InquirySource'] != NULL && isset($_REQUEST['InquirySource']) && $_REQUEST['InquirySource'] != '0') {
    $where.=" and lead.customerEntryId in (select customerentry.customerEntryId from customerentry where inquirySourceId in (" . $_REQUEST['InquirySource'] . "))";
} else {
    $where.=" and lead.customerEntryId in (select customerentry.customerEntryId from customerentry where inquirySourceId in (select inquirySourceId from inquirysource where isDelete=0))";
}

$filterstr = "SELECT customerEntryId,statusId,leadId,walkin_datetime,leaduniqueid FROM `lead`  " . $where . " and isNewInquiry='0'  and   walkin_datetime != '' order by STR_TO_DATE(walkin_datetime,'%d-%m-%Y') desc";
$query = mysqli_query($dbconn, $filterstr);
if (mysqli_num_rows($query) > 0) {
    $delimiter = ",";
    $filename = "Employee-Walking_" . date('Y-m-d H:i:s') . ".csv";
    $f = fopen('php://memory', 'w');
    $fields = array(
        'Sr.No',
        'Month-Year',
        'Walk-in Date',
        'Lead Unique ID',
        'Customer Name',
        'Source Of Lead',
        'Inquiry Status',        
        'Remarks'
        );
    fputcsv($f, $fields, $delimiter);
    $i = 1;
    while ($row = mysqli_fetch_assoc($query)) {
        $customerentry = "SELECT title,firstName,MiddleName,lastName,inquirySourceId FROM `customerentry` where isDelete='0'  and  istatus='1' and  customerEntryId='" . $row['customerEntryId'] . "'";
        $resultCustomer = mysqli_query($dbconn, $customerentry);
        $rowCustomer = mysqli_fetch_array($resultCustomer);
        $get_source = mysqli_fetch_array(mysqli_query($dbconn, "select inquirySourceName from inquirysource where inquirySourceId = '" . $rowCustomer['inquirySourceId'] . "'"));
        $inquiryStatus = mysqli_fetch_array(mysqli_query($dbconn, "SELECT statusName FROM `status`  where isDelete='0'  and  istatus='1' and statusId='" . $row['statusId'] . "'"));
        $get_Remark = mysqli_fetch_array(mysqli_query($dbconn, "select comment from leadfollowup where leadId = '" . $row['leadId'] . "'  ORDER BY leadFollowupId  DESC LIMIT 1"));
        $walkin = strtotime($row['walkin_datetime']);
        $lineData = array(
            $i,
            $data = date('M-Y', $walkin),
            $row['walkin_datetime'],
            $row['leaduniqueid'],
            $rowCustomer['title'] . ' ' . $rowCustomer['firstName'] . ' ' . $rowCustomer['MiddleName'] . ' ' . $rowCustomer['lastName'],
            $get_source['inquirySourceName'],
            $inquiryStatus['statusName'],
            trim($get_Remark['comment'])
        );
        fputcsv($f, $lineData, $delimiter);
        $i++;
    }
    fseek($f, 0);
    header('Content-Type:text/csv');
    header('Content-Disposition:attachment;filename="'.$filename.'";');
    fpassthru($f);
} else {
    header('location:walkin-report.php?flg=1');
}
exit;
?>