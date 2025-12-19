<?php

//include database configuration file
require_once('../common.php');
include('IsLogin.php');
$connect = new connect();

//$where = "and studentadmission.branchId ='" . $_SESSION['branchid'] . "' ";
$where = "";
// if ($_SESSION['EmployeeType'] == 'Supervisor') {
//     if ($_POST['branchid'] != NULL && isset($_POST['branchid']))
//         $where .= " and branchId='" . $_POST['branchid'] . "'";
// }
if ($_REQUEST['FormDate'] != NULL && isset($_REQUEST['FormDate']))
    $where.=" and DATE_FORMAT(STR_TO_DATE(studentadmission.DOB,'%d-%m-%Y'), '%m-%d') >= DATE_FORMAT(STR_TO_DATE('" . $_REQUEST['FormDate'] . "','%d-%m-%Y'), '%m-%d')";

if ($_REQUEST['ToDate'] != NULL && isset($_REQUEST['ToDate']))
    $where.=" and DATE_FORMAT(STR_TO_DATE(studentadmission.DOB,'%d-%m-%Y'), '%m-%d') <= DATE_FORMAT(STR_TO_DATE('" . $_REQUEST['ToDate'] . "','%d-%m-%Y'), '%m-%d')";


$filterstr = "SELECT studentadmission.studentEnrollment,studentadmission.firstName,studentadmission.surName,studentadmission.DOB from studentadmission where studentadmission.iStudentStatus=1  " . $where . " "
        . "and istatus=1 and isDelete=0 ORDER BY DATE_FORMAT(STR_TO_DATE(studentadmission.DOB,'%d-%m-%Y'),'%d-%m') ASC";
$query = mysqli_query($dbconn, $filterstr);
if (mysqli_num_rows($query) > 0) {
    $delimiter = ",";
    $filename = "BirthdayReport_" . date('Y-m-d H:i:s') . ".csv";
    //create a file pointer
    $f = fopen('php://memory', 'w');
    //set column headers
    $fields = array(
        'ID',
        'Student Enrollment',
        'Student Name',
        'Birthday Date'
    );

    fputcsv($f, $fields, $delimiter);
    $i = 1;

    //output each row of the data, format line as csv and write to file pointer
    while ($row = mysqli_fetch_assoc($query)) {
        $lineData = array(
            $i,
            $row['studentEnrollment'],
            $row['firstName'] . '  ' . $row['surName'],
            $row['DOB']
        );
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
    header('location:BirthdayRoport.php?flg=1');
}
exit;
?>