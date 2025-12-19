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
if ($_REQUEST['FormDate'] != NULL && isset($_REQUEST['FormDate'])){
    $where .= " and  month>=MONTH(STR_TO_DATE('" . $_REQUEST['FormDate'] . "','%d-%m-%Y')) and year>=YEAR(STR_TO_DATE('" . $_REQUEST['FormDate'] . "','%d-%m-%Y'))";
}
if ($_REQUEST['ToDate'] != NULL && isset($_REQUEST['ToDate'])){
    $where .= " and  month<=MONTH(STR_TO_DATE('" . $_REQUEST['ToDate'] . "','%d-%m-%Y')) and year<=YEAR(STR_TO_DATE('" . $_REQUEST['ToDate'] . "','%d-%m-%Y'))";
}
$filterstr = "SELECT * FROM target  " . $where . " and iBranchId = " . $_SESSION['branchid'] . " and isDelete='0'  and  iStatus='1' order by itargetId desc";

$query = mysqli_query($dbconn,$filterstr);

if (mysqli_num_rows($query) > 0) {
    $delimiter = ",";
    $filename = "Achieve-Report_" . date('Y-m-d H:i:s') . ".csv";
    //create a file pointer
    $f = fopen('php://memory', 'w');
    //set column headers
    
    $fields = array(
        'Sr.No',
        'Month / Year',
        'Achieve Inquiry / Walking',
        'Achieve Enrollment',
        'Achieve Booking',
        'Achieve Collection',
        'Achieve FPS'
    );

    fputcsv($f, $fields, $delimiter);
    $i = 1;
    //output each row of the data, format line as csv and write to file pointer
    while ($rowfilter = mysqli_fetch_assoc($query)) {
        $Walking = 0;
        $inquiry = mysqli_fetch_array(mysqli_query($dbconn,"SELECT count(*) as TotalRow FROM lead where  MONTH(STR_TO_DATE(walkin_datetime,'%d-%m-%Y'))='" . $rowfilter['month'] . "' and YEAR(STR_TO_DATE(walkin_datetime,'%d-%m-%Y'))='" . $rowfilter['year'] . "' and isNewInquiry='0' and employeeMasterId='" . $_SESSION['EmployeeId'] . "'"));
        if ($inquiry['TotalRow'] != 0) {
            $Walking = $inquiry['TotalRow'];
        } 
        $Enrollment = 0;
        $filterEnroll = mysqli_fetch_array(mysqli_query($dbconn,"SELECT count(*) as TotalRow FROM lead where MONTH(STR_TO_DATE(nextFollowupModifyDate,'%d-%m-%Y'))='" . $rowfilter['month'] . "' and YEAR(STR_TO_DATE(nextFollowupModifyDate,'%d-%m-%Y'))='" . $rowfilter['year'] . "'  and isNewInquiry='0' and statusId='3' and employeeMasterId='" . $_SESSION['EmployeeId'] . "'"));
        if ($filterEnroll['TotalRow'] != 0) {
            $Enrollment = $filterEnroll['TotalRow'];
        } 
        $Booking = 0;
        $filterBooking = mysqli_fetch_array(mysqli_query($dbconn,"SELECT sum(booking_amount) as TotalRow FROM lead where MONTH(STR_TO_DATE(nextFollowupModifyDate,'%d-%m-%Y'))='" . $rowfilter['month'] . "' and YEAR(STR_TO_DATE(nextFollowupModifyDate,'%d-%m-%Y'))='" . $rowfilter['year'] . "' and isNewInquiry='0' and statusId='3' and employeeMasterId='" . $_SESSION['EmployeeId'] . "'"));
        if ($filterBooking['TotalRow'] != 0) {
            $Booking = $filterBooking['TotalRow'];
        } 
        $collection = 0;
        $filterCollection = mysqli_fetch_array(mysqli_query($dbconn,"select sum(amount) as collection from studentfee INNER JOIN studentadmission ON studentadmission.stud_id = studentfee.stud_id where MONTH(STR_TO_DATE(studentfee.payDate,'%d-%m-%Y'))='" . $rowfilter['month'] . "' and YEAR(STR_TO_DATE(studentfee.payDate,'%d-%m-%Y'))='" . $rowfilter['year'] . "' and studentadmission.branchId='" . $_SESSION['branchid'] . "'"));
        if ($filterCollection['collection'] != 0) {
            $collection = $filterCollection['collection'];
        } 
        
        $monthNum = $rowfilter['month'];
        $dateObj = DateTime::createFromFormat('!m', $monthNum);
        $monthName  = $dateObj->format('M');
        $monthYear =  $monthName ." / ". $rowfilter['year'];
        $lineData = array(
            $i,
            $monthYear,
            $Walking,
            $Enrollment,
            $Booking,
            $collection,
            $rowfilter['achieveFPS']
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
    header('location:AchieveReport.php');
}
exit;
?>