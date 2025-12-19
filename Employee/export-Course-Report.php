<?php
error_reporting(E_ALL);
//include database configuration file
require_once('../config.php');
include('IsLogin.php');
//$connect = new connect();
//get records from database
// $whereEmpId = " and studentadmission.employeeMasterId = '" . $_SESSION['EmployeeId'] . "'";
//get records from database
$whereEmpId = " and studentadmission.branchId = '" . $_SESSION['branchid'] . "'";
$where = " ";
if ($_REQUEST['FormDate'] != NULL && isset($_REQUEST['FormDate']))
    $where.=" and STR_TO_DATE(studentcourse.EnrollmentDate,'%d-%m-%Y')>= STR_TO_DATE('" . $_REQUEST['FormDate'] . "','%d-%m-%Y')";

if ($_REQUEST['ToDate'] != NULL && isset($_REQUEST['ToDate']))
    $where.=" and DATE_FORMAT(STR_TO_DATE(studentcourse.EnrollmentDate, '%d-%m-%Y'), '%Y-%m-%d')<=STR_TO_DATE('" . $_REQUEST['ToDate'] . "','%d-%m-%Y')";

// if ($_REQUEST['studentPortal_Id'] != NULL && isset($_REQUEST['studentPortal_Id']) && $_REQUEST['studentPortal_Id'] !='null')
//     $where .= " and studentadmission.studentPortal_Id in (" . $_REQUEST['studentPortal_Id'] . ") ";
if ($_REQUEST['studentcourseId'] != NULL && isset($_REQUEST['studentcourseId'])){
    $where .= " and studentcourse.courseId in (" . implode (",", $_REQUEST['studentcourseId'] ). ") ";
}

if($_REQUEST['studentStatus'] != NULL && isset($_REQUEST['studentStatus']) && $_REQUEST['studentStatus'] != 'null')
    $where .= " and studentadmission.iStudentStatus in (".$_REQUEST['studentStatus'].")";
else
    $where .= " and studentadmission.iStudentStatus in (select studentstatus.studstatusid from studentstatus where isDelete=0)";

$filterstr = "SELECT studentcourse.courseId,(Select course.courseName from course where course.courseId=studentcourse.courseId) as courseName,sum(fee) as 'totalfee',sum(offeredfee) as 'offeredfee',count(studentadmission.stud_id) as 'totalStudent',studentcourseId from studentadmission,studentcourse where studentcourse.stud_id=studentadmission.stud_id " . $where . $whereEmpId . " and studentcourse.istatus=1 and studentcourse.courseId in (SELECT courseId from course) GROUP BY studentcourse.courseId ORDER BY STR_TO_DATE(studentcourse.EnrollmentDate,'%d-%m-%Y') asc";

$query = mysqli_query($dbconn,$filterstr);

if (mysqli_num_rows($query) > 0) {
    $delimiter = ",";
    $filename = "Student-Enrollment-Report_" . date('Y-m-d H:i:s') . ".csv";
    //create a file pointer
    $f = fopen('php://memory', 'w');
    //set column headers
    $fields = array(
        'ID',
        'Course Name',
        'Actual Fee',
        'Offered Fee',
        'Till Date Payment Receive',
        //'Balance Amount',
        'Student Count'
    );

    fputcsv($f, $fields, $delimiter);
    $i = 1;
    // $Total = array("Total", "-", "-", "-", "-", "-", "-", "-", "-", 0, 0,0, "-", "-", "-");
    // $Total[0] = "Total";
    // $Total[1] = "-";
    //output each row of the data, format line as csv and write to file pointer
    while ($row = mysqli_fetch_assoc($query)) {
        $filterfee = "select sum(amount) as recievedfee from studentfee where studentcourseId='" . $row['studentcourseId'] . "' ORDER by studentfeeid DESC";
        $rowFee = mysqli_fetch_array(mysqli_query($dbconn,$filterfee));
        $balanceAmount = $row['offeredfee'] - $rowFee['recievedfee'];
        $balanceAmount;
        
        
        $lineData = array(
            $i,
            $row['courseName'],
            $row['totalfee'],
            $row['offeredfee'],
            $row['recievedfee'],
            //$balanceAmount,
            $row['totalStudent']
        );
        // $Total[9] = $row['fee'] * 1 + $Total[9] * 1;
        // $Total[10] = $row['offeredfee'] * 1 + $Total[10] * 1;
        // $Total[11] = $rowFee['recievedfee'] * 1 + $Total[11] * 1;

        // $Total[14] = $balanceAmount * 1 + $Total[14] * 1;

        fputcsv($f, $lineData, $delimiter);
        $i++;
    }
    //fputcsv($f, $Total, $delimiter);
    //move back to beginning of file
    fseek($f, 0);

    //set headers to download file rather than displayed
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '";');

    //output all remaining data on a file pointer
    fpassthru($f);
} else {
    header('location:CourseRoport.php');
}
exit;
?>