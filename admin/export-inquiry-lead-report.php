<?php
//include database configuration file
require_once('../config.php');
include('IsLogin.php');
//$connect = new connect();

//get records from database

$where = "where 1=1";
$whereBranch = "";
    if ($_REQUEST['FormDate'] != NULL && isset($_REQUEST['FormDate']))
        $where.=" and STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y')>= STR_TO_DATE('$_REQUEST[FormDate]','%d-%m-%Y')";
    if ($_REQUEST['ToDate'] != NULL && isset($_REQUEST['ToDate']))
        $where.=" and STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y')<=STR_TO_DATE('$_REQUEST[ToDate]','%d-%m-%Y')";
    if ($_REQUEST['Employee'] != NULL && isset($_REQUEST['Employee']))
        $where.=" and lead.employeeMasterId='" . $_REQUEST['Employee'] . "'";
    if ($_REQUEST['branch'] != NULL && isset($_REQUEST['branch']))
        $whereBranch = " and employeemaster.branchid =" . $_REQUEST['branch'] . " ";
if ($_REQUEST['InquirySource'] != NULL && $_REQUEST['InquirySource'] != null && $_REQUEST['InquirySource'] != 'null' && isset($_REQUEST['InquirySource']) && $_REQUEST['InquirySource'] != '0')
{
   $where.=" and lead.customerEntryId in (select customerentry.customerEntryId from customerentry where inquirySourceId in (" .$_REQUEST['InquirySource'] . "))";
}

    $query = mysqli_query($dbconn,"SELECT * FROM `lead` INNER JOIN employeemaster ON employeemaster.employeeMasterId = lead.employeeMasterId " . $where . $whereBranch . "  order by  STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y') desc");
//   $query = mysqli_query($dbconn,"SELECT * FROM `lead` " . $where . "   order by  leadId desc");
  
if(mysqli_num_rows($query) > 0){
    $delimiter = ",";
    $filename = "Inquiry_Lead_Report_" . date('Y-m-d H:i:s') . ".csv";
    //create a file pointer
    $f = fopen('php://memory', 'w');
    //set column headers
    $fields = array('ID','Lead Unique Id', 'Customer Name','Email','Mobile','Employee Name', 'Source Of Lead', 'Entry Date', 'Inquiry Status' ,'Next Follow Up Date','Follow Up Comment');
    fputcsv($f, $fields, $delimiter);
    $i=1;
    //output each row of the data, format line as csv and write to file pointer
    while($row = mysqli_fetch_assoc($query)){
       
        $customerentry = "SELECT * FROM `customerentry` where isDelete='0'  and  istatus='1' and  customerEntryId='" . $row['customerEntryId'] . "'";
                                $resultCustomer = mysqli_query($dbconn,$customerentry);
                                $rowCustomer = mysqli_fetch_array($resultCustomer);
                                $employeemaster = mysqli_fetch_array(mysqli_query($dbconn,"SELECT * FROM `employeemaster`  where isDelete='0'  and  istatus='1' and employeeMasterId='" . $row['employeeMasterId'] . "'"));
                              $inquirySource = mysqli_fetch_array(mysqli_query($dbconn,"SELECT * FROM `inquirysource`  where isDelete='0'  and  istatus='1' and inquirySourceId='" . $rowCustomer['inquirySourceId'] . "'"));
                              $inquiryStatus = mysqli_fetch_array(mysqli_query($dbconn,"SELECT * FROM `status`  where isDelete='0'  and  istatus='1' and statusId='" . $row['statusId'] . "'"));
                              $lineData = array($i, $row['leaduniqueid'], $rowCustomer['title'] . ' ' . $rowCustomer['firstName'] . ' ' . $rowCustomer['MiddleName'] . ' ' . $rowCustomer['lastName'],$rowCustomer['email'],$rowCustomer['mobileNo'],$employeemaster['employeeName'], $inquirySource['inquirySourceName'],$row['inquiryEnterDate'],$inquiryStatus['statusName'],$row['nextFollowupDate'],$row['comment']);
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
}
else
{
    header('location:InquiryLeadReport.php?flg=1');
}
exit;

?>