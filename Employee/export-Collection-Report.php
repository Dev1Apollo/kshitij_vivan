<?php

//include database configuration file
require_once('../config.php');
include('IsLogin.php');
//$connect = new connect();
//get records from database
$whereEmpId = " and studentadmission.branchId = '" . $_SESSION['branchid'] . "'";
$where = " ";
if ($_REQUEST['FormDate'] != NULL && isset($_REQUEST['FormDate']))
    $where.=" and STR_TO_DATE(studentfee.payDate,'%d-%m-%Y')>= STR_TO_DATE('" . $_REQUEST['FormDate'] . "','%d-%m-%Y')";

if ($_REQUEST['ToDate'] != NULL && isset($_REQUEST['ToDate']))
    $where.=" and DATE_FORMAT(STR_TO_DATE(studentfee.payDate, '%d-%m-%Y'), '%Y-%m-%d')<=STR_TO_DATE('" . $_REQUEST['ToDate'] . "','%d-%m-%Y')";

if ($_REQUEST['studentPortal_Id'] != NULL && isset($_REQUEST['studentPortal_Id']) && $_REQUEST['studentPortal_Id'] != 'null')
    $where .= " and studentadmission.studentPortal_Id in (" . $_REQUEST['studentPortal_Id'] . ") ";

$filterstr = "select studentfee.*, studentadmission.* from studentadmission,studentfee where studentfee.stud_id=studentadmission.stud_id and studentfee.amount!=0 and studentfee.feetype not in (5) " . $where . $whereEmpId . " ORDER BY STR_TO_DATE(studentfee.payDate,'%d-%m-%Y') ASC  ";

$query = mysqli_query($dbconn, $filterstr);

if (mysqli_num_rows($query) > 0) {
    $delimiter = ",";
    $filename = "Collection-Report_" . date('Y-m-d H:i:s') . ".csv";
    //create a file pointer
    $f = fopen('php://memory', 'w');
    //set column headers
//    $fields = array(
//        'ID',
//        'Receipt NO',
//        'Day',
//        'Month',
//        'Year',
//        'Name Of Student',
//        'Cash / Bank',
//        'Amount',
//        'Bank Name',
//        'Cheque No',
//        'Deposit',
//        'Deposit Date',
//        'Deposit Amount',
//        'Fees Type',
//        'Actual Fee',
//        'CGST',
//        'SGST',
//        'Total Fee',
//        'Total Fee',
//        'Remarks',
//    );
    $fields = array(
        'Sr.No',
        'Ref Date',
        'Type',
        'Ref No',
//        'Course',
        'Enrollment No',
        'Name Of Student',
        'Total Receipt Amount',
        'Tax Amount',
        'Without Tax Amount',
        'Payment Mode',
        'Bank Name',
        'Cheque Number',
        'Deposit Date',
        'Deposited Bank',
        'Deposit Amount',
        'Rejection type',
        'Remarks'
    );

    fputcsv($f, $fields, $delimiter);
    $i = 1;

    $Total = array("Total", "-", "-", "-", "-", "-", 0, 0, 0, "-", "-", "-", "-", "-", 0);
    $Total[0] = "Total";
    //output each row of the data, format line as csv and write to file pointer
    while ($row = mysqli_fetch_assoc($query)) {
        $gst = $row['decGst'];

        if ($row['studentPortal_Id'] == 1) {
            $type = "Maac CG";
        } else if ($row['studentPortal_Id'] == 2) {
            $type = "Kshitij Vivan";
        } else if ($row['studentPortal_Id'] == 3) {
            $type = "Other";
        } else if ($row['studentPortal_Id'] == 4) {
            $type = "Maac Satellite";
        }

        if (isset($row['studentEnrollment']) && $row['studentEnrollment'] != '') {
            $studentEnrollment = $row['studentEnrollment'];
        } else {
            $studentEnrollment = 'NA';
        }
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
//        $coureName = '';
//        if ($row['studentcourseId'] != NULL && $row['studentcourseId'] != 0) {
//            $Course = mysqli_fetch_array(mysqli_query($dbconn,"SELECT * FROM `studentcourse` where studentcourseId='" . $row['studentcourseId'] . "' "));
//            $stundentCourse = mysqli_query($dbconn,"SELECT * FROM course where courseId in (" . $Course['courseId'] . ") order by courseId DESC ");
//
//            while ($courseName = mysqli_fetch_array($stundentCourse)) {
//                $coureName = $courseName['courseName'] . ',' . $coureName;
//            }
//            $coureName = rtrim($coureName, ',');
//        } else {
//            $coureName = 'NA';
//        }
        if ($row['isCancel'] == 1) {
            $remark = $row['CancellationComment'];
        } else {
            $remark = $row['comments'];
        }

        if ($row['rejectiontype'] == 1) {
            $rejectiontype = "Cheque bounce";
        } else if ($row['rejectiontype'] == 2) {
            $rejectiontype = "Wrong Entry";
        } else {
            $rejectiontype = "Other";
        }

        $filterMode = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `paymentmode` where paymentId='" . $row['paymentMode'] . "' and isDelete='0' and iStatus='1'"));
        $filterBankdeposit = mysqli_fetch_array(mysqli_query($dbconn, "select * from bank where bankId=" . $row['toBank'] . " and isDelete='0' and istatus='1'"));
//                                    echo $filterBankdeposit['bankName'];
        $lineData = array(
            $i,
            $row['payDate'],
            $type,
            $row['receiptNo'],
//            $coureName,
            $studentEnrollment,
            $row['title'] . ' ' . $row['firstName'] . ' ' . $row['middleName'] . ' ' . $row['surName'],
            $row['amount'],
            $gst,
            $row['texFreeAmt'],
            $filterMode['paymentName'],
            $bankName,
            $chequeNo,
            $row['depositDate'],
            $filterBankdeposit['bankName'],
            $row['depositAmount'],
            $rejectiontype,
            $remark
        );
        $Total[6] = $row['amount'] * 1 + $Total[6] * 1;
        $Total[7] = $gst * 1 + $Total[7] * 1;
        $Total[8] = $row['texFreeAmt'] * 1 + $Total[8] * 1;
        $Total[14] = $row['depositAmount'] * 1 + $Total[14] * 1;
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
    header('location:CollectionReport.php?flg=1');
}
exit;
?>