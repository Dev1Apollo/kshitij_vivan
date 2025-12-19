<?php
error_reporting(0);
//include database configuration file
require_once('../common.php');
include('IsLogin.php');
$connect = new connect();
//$connect = new connect();
//get records from database
// $whereEmpId = " and studentadmission.employeeMasterId = '" . $_SESSION['EmployeeId'] . "'";
//get records from database
//$whereEmpId = " and studentadmission.branchId = '" . $_SESSION['branchid'] . "'";
if ($_SESSION['EmployeeType'] == 'Supervisor') {
    if ($_REQUEST['branchid'] != NULL && isset($_REQUEST['branchid']))
        $whereEmpId .= " and studentadmission.branchId='" . $_REQUEST['branchid'] . "'";
} else {
    $whereEmpId .= " and studentadmission.branchId=" . $_SESSION['branchid'] . "'";
}
$where = " ";
if ($_REQUEST['FormDate'] != NULL && isset($_REQUEST['FormDate']))
    $where.=" and STR_TO_DATE(studentcourse.EnrollmentDate,'%d-%m-%Y')>= STR_TO_DATE('" . $_REQUEST['FormDate'] . "','%d-%m-%Y')";

if ($_REQUEST['ToDate'] != NULL && isset($_REQUEST['ToDate']))
    $where.=" and DATE_FORMAT(STR_TO_DATE(studentcourse.EnrollmentDate, '%d-%m-%Y'), '%Y-%m-%d')<=STR_TO_DATE('" . $_REQUEST['ToDate'] . "','%d-%m-%Y')";

if ($_REQUEST['studentPortal_Id'] != NULL && isset($_REQUEST['studentPortal_Id']) && $_REQUEST['studentPortal_Id'] !='null')
    $where .= " and studentadmission.studentPortal_Id in (" . $_REQUEST['studentPortal_Id'] . ") ";

if($_REQUEST['studentStatus'] != NULL && isset($_REQUEST['studentStatus']) && $_REQUEST['studentStatus'] != 'null')
    $where .= " and studentadmission.iStudentStatus in (".$_REQUEST['studentStatus'].")";
else
    $where .= " and studentadmission.iStudentStatus in (select studentstatus.studstatusid from studentstatus where isDelete=0)";


$filterstr = "SELECT studentadmission.*,studentfee.*,studentcourse.* from studentfee, studentadmission,studentcourse where studentadmission.stud_id=studentfee.stud_id and studentcourse.stud_id=studentadmission.stud_id " . $where . $whereEmpId ."  and studentcourse.istatus=1 and studentcourse.courseId in (SELECT courseId from course) GROUP BY studentcourse.stud_id,studentcourse.courseId  ORDER BY STR_TO_DATE(studentcourse.EnrollmentDate,'%d-%m-%Y') asc";

$query = mysqli_query($dbconn,$filterstr);

if (mysqli_num_rows($query) > 0) {
    $delimiter = ",";
    $filename = "Student-Enrollment-Report_" . date('Y-m-d H:i:s') . ".csv";
    //create a file pointer
    $f = fopen('php://memory', 'w');
    //set column headers
    $fields = array(
        'ID',
        'Booking Id',
        'Student Of',
        'Student Enrollment',
        'Month Of Admission',
        'Date Of Admission',
        'Name Of Student',
        'Contact Number',
        'Course',
//        'No Of EMI',
//        'Source',
        'Actual Fee',
        'Offered Fee',
        'Till Date Payment Receive',
        'Last Date Of Receipt',
        'Pending Days',
        'Balance Amount',
        'Student Status',
        'Remark',
        'Remark',
        'Remark'
    );

    fputcsv($f, $fields, $delimiter);
    $i = 1;
    $Total = array("Total", "-", "-", "-", "-", "-", "-", "-", "-", 0, 0,0, "-", "-", "-");
    $Total[0] = "Total";
    $Total[1] = "-";
    //output each row of the data, format line as csv and write to file pointer
    while ($row = mysqli_fetch_assoc($query)) {
        $filterCourse = mysqli_query($dbconn,"Select * from course where courseId in (" . $row['courseId'] . ") order by courseId DESC");
        $courseName = '';
        while ($rowcourse = mysqli_fetch_array($filterCourse)) {
            $courseName = $rowcourse['courseName'] . "," . $courseName;
        }
        $courseName = rtrim($courseName, ',');
         $filterfee = "select sum(amount) as recievedfee from studentfee where stud_id='" . $row['stud_id'] . "' and studentcourseId='" . $row['studentcourseId'] . "' ORDER by studentfeeid DESC";
        $rowFee = mysqli_fetch_array(mysqli_query($dbconn,$filterfee));
        $filterpayDate = "select payDate,comments from studentfee where stud_id='" . $row['stud_id'] . "' and studentcourseId='" . $row['studentcourseId'] . "' ORDER by STR_TO_DATE(payDate,'%d-%m-%Y') DESC limit 1";
        $rowPay = mysqli_fetch_array(mysqli_query($dbconn,$filterpayDate));
        $currentDate = date('d-m-Y');
        $balanceAmount = $row['offeredfee'] - $rowFee['recievedfee'];
        $balanceAmount;
        $filtersourse = "SELECT * FROM studentadmission join lead on studentadmission.leadId=lead.leadId join customerentry on customerentry.customerEntryId=lead.customerEntryId join inquirysource on customerentry.inquirySourceId=inquirysource.inquirySourceId where studentadmission.stud_id='" . $row['stud_id'] . "'";
        $rowSourse = mysqli_fetch_array(mysqli_query($dbconn,$filtersourse));
        if ($row['studentPortal_Id'] == 1) {
            $studentPortal_Id = 'Maac CG';
        } else if ($row['studentPortal_Id'] == 2) {
            $studentPortal_Id = 'Kshitij Vivan';
        }else if ($row['studentPortal_Id'] == 4) {
            $studentPortal_Id = 'Maac Satellite';
        } else {
            $studentPortal_Id = 'Other';
        }
        $currentDate = date('Y-m-d');
        $LastPay = date('Y-m-d', strtotime($rowPay['payDate']));
        $date1 = date_create($currentDate);
        $date2 = date_create($LastPay);
        $diff = date_diff($date2, $date1);
        $filterStatus = mysqli_fetch_array(mysqli_query($dbconn,"Select * from studentstatus where studstatusid=" . $row['iStudentStatus'] . " and isDelete=0 and istatus=1"));
        
        $lineData = array(
            $i,
            $row['bookingId'],
            $studentPortal_Id,
            $row['studentEnrollment'],
            date("M'Y", strtotime($row['EnrollmentDate'])),
            $row['EnrollmentDate'],
            $row['title'] . ' ' . $row['firstName'] . ' ' . $row['middleName'] . ' ' . $row['surName'],
            $row['mobileOne'],
            $courseName,
//            $row['noOfEmi'],
//            $rowSourse['inquirySourceName'],
            $row['fee'],
            $row['offeredfee'],
            $rowFee['recievedfee'],
            $rowPay['payDate'],
            $diff->format("%R%a days"),
            $balanceAmount,
            $filterStatus['studentStatusName'],
            $rowPay['comments'],
            '',
            ''
        );
        $Total[9] = $row['fee'] * 1 + $Total[9] * 1;
        $Total[10] = $row['offeredfee'] * 1 + $Total[10] * 1;
        $Total[11] = $rowFee['recievedfee'] * 1 + $Total[11] * 1;

        $Total[14] = $balanceAmount * 1 + $Total[14] * 1;

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
    header('location:StudentEnrollmentReport.php?flg=1');
}
exit;
?>