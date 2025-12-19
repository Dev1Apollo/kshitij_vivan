<?php

//include database configuration file
require_once('../common.php');
include('IsLogin.php');
$connect = new connect();
//$connect = new connect();
//get records from database
//$whereEmpId = " and studentadmission.branchId = '" . $_SESSION['branchid'] . "'";
$whereEmpId = "";
if ($_SESSION['EmployeeType'] == 'Supervisor') {
    if ($_REQUEST['branchid'] != NULL && isset($_REQUEST['branchid']))
        $whereEmpId .= " and studentadmission.branchId='" . $_REQUEST['branchid'] . "'";
} else {
    $whereEmpId .= " and studentadmission.branchId=" . $_SESSION['branchid'] . "'";
}
$where = " ";
if ($_REQUEST['FormDate'] != NULL && isset($_REQUEST['FormDate']))
    $where .= " and STR_TO_DATE(studentfee.payDate,'%d-%m-%Y')>= STR_TO_DATE('" . $_REQUEST['FormDate'] . "','%d-%m-%Y')";

if ($_REQUEST['ToDate'] != NULL && isset($_REQUEST['ToDate']))
    $where .= " and DATE_FORMAT(STR_TO_DATE(studentfee.payDate, '%d-%m-%Y'), '%Y-%m-%d')<=STR_TO_DATE('" . $_REQUEST['ToDate'] . "','%d-%m-%Y')";

if ($_REQUEST['StudentName'] != NULL && isset($_REQUEST['StudentName']))
    $where .= " and (studentadmission.firstName like '%" . $_REQUEST['StudentName'] . "%' OR  studentadmission.middleName like '%" . $_REQUEST['StudentName'] . "%' OR studentadmission.surName like '%" . $_REQUEST['StudentName'] . "%' )";

if ($_REQUEST['studentPortal_Id'] != NULL && isset($_REQUEST['studentPortal_Id']) && $_REQUEST['studentPortal_Id'] != 'null')
    $where .= " and studentadmission.studentPortal_Id in (" .  $_REQUEST['studentPortal_Id'] . ")";

$filterstr = "SELECT studentfee.*,studentadmission.* FROM `studentfee`,studentadmission WHERE studentadmission.stud_id=studentfee.stud_id and studentfee.amount!=0 and studentfee.feetype not in (5) and studentfee.isCancel=0  " . $where . $whereEmpId . " ORDER BY STR_TO_DATE(studentfee.payDate,'%d-%m-%Y') ASC";

$query = mysqli_query($dbconn,$filterstr);

if (mysqli_num_rows($query) > 0) {
    $delimiter = ",";
    $filename = "Fee-Collection-Report_" . date('Y-m-d H:i:s') . ".csv";
    //create a file pointer
    $f = fopen('php://memory', 'w');
    //set column headers
    $fields = array(
        'ID',
        'Lead Unique Id',
        'Enrollment Id',
        'Booking Id',
        'Branch',
        'Name of Student',
        'Payment Mode',
        'Bank Name',
        'Cheque No.',
        'Payment Date',
        'Gross Amount',
        'CGST',
        'SGST',
        'Net Amount'
    );
    fputcsv($f, $fields, $delimiter);
    $i = 1;

    $Total = array("Total", "-", "-","-", "-", "-", "-", "-", "-", "-", "-");
    $Total[0] = "Total";
    $Total[1] = "-";
    //output each row of the data, format line as csv and write to file pointer
    while ($row = mysqli_fetch_assoc($query)) {
        $filterCourse = mysqli_fetch_array(mysqli_query($dbconn,"SELECT bookingId FROM `studentcourse` where studentcourseId='" . $row['studentcourseId'] . "' and studentcourse.istatus=1"));
        $filterBank = mysqli_fetch_array(mysqli_query($dbconn,"SELECT bankName FROM `bankmaster` where isDelete='0' and istatus='1' and bankMasterId='" . $row['bankName'] . "' "));
        $filterMode = mysqli_fetch_array(mysqli_query($dbconn,"SELECT * FROM `paymentmode` where paymentId='" . $row['paymentMode'] . "' and isDelete='0' and iStatus='1'"));
        $employeeMaster = [];
        if(isset($row['branchId']) && $row['branchId'] > 0){
            $employeeMaster = mysqli_fetch_array(mysqli_query($dbconn, "select * from branchmaster where branchid = '" . $row['branchId'] . "'"));
        }
        $branchname = !empty($employeeMaster) ? $employeeMaster['branchname'] : '-';
        $cgst = $row['decGst'] / 2;
        $sgst = $row['decGst'] / 2;
        $lineData = array(
            $i,
            $row['leaduniqueid'],
            $row['studentEnrollment'],
            $filterCourse['bookingId'],
            $branchname,
            $row['title'] . ' ' . $row['firstName'] . ' ' . $row['middleName'] . ' ' . $row['surName'],
            $filterMode['paymentName'],
            $filterBank['bankName'],
            $row['chequeNo'],
            $row['payDate'],
            $row['texFreeAmt'],
            $row['decGst'] / 2,
            $row['decGst'] / 2,
            $row['amount']
        );
        $Total[10] = $row['texFreeAmt'] * 1 + $Total[10] * 1;
        $Total[11] = $cgst * 1 + $Total[11] * 1;
        $Total[12] = $sgst * 1 + $Total[12] * 1;
        $Total[13] = $row['amount'] * 1 + $Total[13] * 1;
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
    header('location:FeeCollectionReport.php?flg=1');
}
exit;
?>