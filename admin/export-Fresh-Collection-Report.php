<?php

//include database configuration file
require_once('../config.php');
include('IsLogin.php');
//$connect = new connect();
//get records from database

$where = " ";
if ($_REQUEST['FormDate'] != NULL && isset($_REQUEST['FormDate']))
    $where .= " and STR_TO_DATE(studentadmission.strEntryDate,'%d-%m-%Y')>= STR_TO_DATE('" . $_REQUEST['FormDate'] . "','%d-%m-%Y')";

if ($_REQUEST['ToDate'] != NULL && isset($_REQUEST['ToDate']))
    $where .= " and DATE_FORMAT(STR_TO_DATE(studentadmission.strEntryDate, '%d-%m-%Y'), '%Y-%m-%d')<=STR_TO_DATE('" . $_REQUEST['ToDate'] . "','%d-%m-%Y')";

if ($_REQUEST['branch'] != NULL && isset($_REQUEST['branch']))
    $where .= " and studentadmission.stud_id in (select stud_id from studentadmission where branchId=" . $_REQUEST['branch'] . " and istatus=1 and isDelete=0 )";

if ($_REQUEST['FormDate'] != NULL && isset($_REQUEST['FormDate']))
    $where .= " and STR_TO_DATE(studentfee.payDate,'%d-%m-%Y')>= STR_TO_DATE('" . $_REQUEST['FormDate'] . "','%d-%m-%Y')";

if ($_REQUEST['ToDate'] != NULL && isset($_REQUEST['ToDate']))
    $where .= " and STR_TO_DATE(studentfee.payDate, '%d-%m-%Y')<=STR_TO_DATE('" . $_REQUEST['ToDate'] . "','%d-%m-%Y')";

$filterstr = "SELECT studentfee.*,studentadmission.*,(sum(amount)) as Amount,(sum(texFreeAmt)) AS TextFree ,(sum(decGst)) as GST  FROM `studentfee`,studentadmission WHERE studentadmission.stud_id=studentfee.stud_id and studentfee.amount!=0 and studentfee.feetype not in (5) and studentfee.isCancel=0 " . $where . "  group by studentfee.stud_id ORDER BY STR_TO_DATE(studentfee.payDate,'%d-%m-%Y') ASC";

$query = mysqli_query($dbconn,$filterstr);

if (mysqli_num_rows($query) > 0) {
    $delimiter = ",";
    $filename = "Fresh-Collection-Report_" . date('Y-m-d H:i:s') . ".csv";
    //create a file pointer
    $f = fopen('php://memory', 'w');
    //set column headers
    $fields = array(
        'ID',
        'Ref No.',
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
    $Total = array("Total", "-", "-", "-", "-", "-", "-", "-", "-", "-", "-");
    $Total[0] = "Total";
    $Total[1] = "-";
    //output each row of the data, format line as csv and write to file pointer
    while ($row = mysqli_fetch_assoc($query)) {
        $filterBank = mysqli_fetch_array(mysqli_query($dbconn,"SELECT bankName FROM `bankmaster` where isDelete='0' and istatus='1' and bankMasterId='".$row['bankName']."' "));
        $cgst = $row['GST'] / 2;
        $sgst = $row['GST'] / 2;
        $lineData = array(
            $i,
            $row['receiptNo'],
            $row['title'] . ' ' . $row['firstName'] . ' ' . $row['middleName'] . ' ' . $row['surName'],
            $row['paymentMode'],
            $filterBank['bankName'],
            $row['chequeNo'],
            $row['payDate'],
            $row['TextFree'],
            $cgst,
            $sgst,
            $row['Amount']
        );
        $Total[7] = $row['TextFree'] * 1 + $Total[7] * 1;
        $Total[8] = $cgst * 1 + $Total[8] * 1;
        $Total[9] = $sgst * 1 + $Total[9] * 1;
        $Total[10] = $row['Amount'] * 1 + $Total[10] * 1;
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
    header('location:FreshCollectionReport.php?flg=1');
}
exit;
?>