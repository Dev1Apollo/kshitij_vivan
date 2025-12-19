<?php

//include database configuration file
require_once('../config.php');
include('IsLogin.php');
//$connect = new connect();
error_reporting(0);
//get records from database
$where = " ";
if ($_REQUEST['FormDate'] != NULL && isset($_REQUEST['FormDate']))
    $where.=" and STR_TO_DATE(depositDate,'%d-%m-%Y')>= STR_TO_DATE('$_REQUEST[FormDate]','%d-%m-%Y')";
if ($_REQUEST['ToDate'] != NULL && isset($_REQUEST['ToDate']))
    $where.=" and STR_TO_DATE(depositDate,'%d-%m-%Y')<=STR_TO_DATE('$_REQUEST[ToDate]','%d-%m-%Y')";
$whereid = "where 1=1";
$whereid.=" and studentcourse.branchId = '" . $_SESSION['branchid'] . "'";

$filterstr = "SELECT studentcourse.`stud_id`,studentcourse.`studentcourseId`,studentfee.chequeNo,studentfee.paymentMode,studentfee.bankName,studentcourse.courseId,studentfee.depositDate,studentfee.`payDate` , studentfee.`iGstRef` ,
SUM((SELECT studfee.amount FROM studentfee studfee where studentfee.studentfeeid = studfee.studentfeeid " . $where . " )) as netamount, 
SUM((SELECT studfee.decGst FROM studentfee studfee where studentfee.studentfeeid = studfee.studentfeeid " . $where . " )) as GstAmount, 
SUM((SELECT studfee.texFreeAmt FROM studentfee studfee where studentfee.studentfeeid = studfee.studentfeeid " . $where . " )) as taxFreeAmount 
FROM studentfee LEFT join studentcourse on studentfee.studentcourseId=studentcourse.studentcourseId JOIN bank on studentfee.toBank=bank.bankId
join studentadmission on studentadmission.stud_id=studentcourse.stud_id " . $whereid . " " . $where . " and studentfee.amount!=0 and studentcourse.istatus=1 and bank.isGst like 'YES' GROUP by studentfee.studentfeeId ORDER BY STR_TO_DATE(studentfee.depositDate,'%d-%m-%Y') ASC,iGstRef ASC";

$query = mysqli_query($dbconn, $filterstr);
//    $query = mysqli_query($dbconn,"SELECT count(*) as Inqcount,employeeMasterId ,(select employeemaster.employeeName from employeemaster where employeemaster.employeeMasterId = lead.employeeMasterId) as EmployeeName ,(select count(*) from lead as l1 " . $whereA . " and l1.statusId = 3 and l1.employeeMasterId = lead.employeeMasterId ) as bookedInq,(select count(*) from lead as l1 " . $whereA . "  and walkin_datetime != ''and l1.employeeMasterId = lead.employeeMasterId ) as walkininquiry,(select count(*) from lead as l1 " . $whereA . " and walkin_datetime != '' and l1.statusId = 3 and l1.employeeMasterId = lead.employeeMasterId) as convertedinquiry  FROM `lead`  " . $where . "   GROUP by employeeMasterId");

if (mysqli_num_rows($query) > 0) {
    $delimiter = ",";
    $filename = "student-GST-Report_" . date('Y-m-d H:i:s') . ".csv";
    //create a file pointer
    $f = fopen('php://memory', 'w');
    //set column headers
    $fields = array(
        'ID',
        'Date',
        'Reference No',
        'Name',
        'Fee (Without Tax)',
        'CGST',
        'SGST',
        'Fee (With Tax)',
        'Payment Mode',
        'Bank Name',
        'Cheque Number');
    fputcsv($f, $fields, $delimiter);
    $i = 1;
    $Total = array("Total", "-", "-", "-", "-", "-", "-", "-", '-', '-', '-');
    $Total[0] = "Total";
    $Total[1] = "-";
    //output each row of the data, format line as csv and write to file pointer
    while ($row = mysqli_fetch_assoc($query)) {
        $CGST = $row['GstAmount'] / 2;
        $SGST = $row['GstAmount'] / 2;
        $getName = mysqli_query($dbconn, "SELECT * FROM studentadmission where stud_id ='" . $row['stud_id'] . "' ");
        $filtername = mysqli_fetch_array($getName);
        $getcourseId = "select * from studentcourse where stud_id='" . $row['stud_id'] . "' and studentcourseId='" . $row['studentcourseId'] . "'";
        $data = mysqli_query($dbconn, $getcourseId);
        $result = mysqli_fetch_array($data);
        $filterMode = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `paymentmode` where paymentId='" . $row['paymentMode'] . "' and isDelete='0' and iStatus='1'"));
        if (isset($row['bankName']) && $row['bankName'] != '') {
            $filterBank = mysqli_fetch_array(mysqli_query($dbconn, "SELECT bankName FROM `bankmaster` where isDelete='0' and istatus='1' and bankMasterId='" . $row['bankName'] . "' "));
            $bankName = $filterBank['bankName'];
        } else {
            $bankName = 'NA';
        }
        if (isset($row['chequeNo']) && $row['chequeNo'] != '') {
            $chequeNo = $row['chequeNo'];
        } else {
            $chequeNo = 'NA';
        }
        $lineData = array($i,
            $row['depositDate'],
            $row['iGstRef'],
            $filtername['title'] . ' ' . $filtername['firstName'] . ' ' . $filtername['middleName'] . ' ' . $filtername['surName'],
            $row['taxFreeAmount'],
            $CGST,
            $SGST,
            $row['netamount'],
            $filterMode['paymentName'],
            $bankName,
            $chequeNo
        );
        $Total[4] = $row['taxFreeAmount'] * 1 + $Total[4] * 1;
        $Total[5] = $CGST * 1 + $Total[5] * 1;
        $Total[6] = $SGST * 1 + $Total[6] * 1;
        $Total[7] = $row['netamount'] * 1 + $Total[7] * 1;
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
    header('location:studentGstReport.php?flg=1');
}
exit;
?>