<?php

//include database configuration file
require_once('../config.php');
include('IsLogin.php');
//$connect = new connect();
//get records from database
$where = "where 1=1 and studentadmission.branchId = '" . $_SESSION['branchid'] . "'";
$whereEmp = " and sa.branchId= '" . $_SESSION['branchid'] . "'";
$whereSub="";
if (isset($_REQUEST['studentPortal_Id'])) {
    if ($_REQUEST['studentPortal_Id'] != NULL && $_REQUEST['studentPortal_Id'] != 'null') {
        $where .= " and studentadmission.studentPortal_Id in(" . $_REQUEST['studentPortal_Id'] . ")";
        $whereSub.= " and sa.studentPortal_Id in(" . $_REQUEST['studentPortal_Id'] . ")";
    }
}

if (isset($_REQUEST['bankDeposit'])) {
    if ($_REQUEST['bankDeposit'] != NULL && $_REQUEST['bankDeposit'] != 'null') {
        $where .= " and studentfee.toBank in(" . $_REQUEST['bankDeposit'] . ")";
        $whereSub.= " and sf.toBank in(" . $_REQUEST['bankDeposit'] . ")";
    }
}

if (isset($_REQUEST['FromDate'])) {
    if ($_REQUEST['FromDate'] != NULL && $_REQUEST['FromDate'] != 'null')
        $where.=" and STR_TO_DATE(studentfee.depositDate,'%d-%m-%Y')>= STR_TO_DATE('$_REQUEST[FromDate]','%d-%m-%Y')";
}
if (isset($_REQUEST['ToDate'])) {
    if ($_REQUEST['ToDate'] != NULL && $_REQUEST['ToDate'] != 'null')
        $where.=" and STR_TO_DATE(studentfee.depositDate,'%d-%m-%Y')<=STR_TO_DATE('$_REQUEST[ToDate]','%d-%m-%Y')";
}

$filterstr = "select depositDate,studentfee.comments,studentfee.remarks,studentfee.toBank,
   (SELECT SUM(depositAmount) from studentfee sf join studentadmission sa on sa.stud_id=sf.stud_id where deposit LIKE '%yes%' " . $whereSub . $whereEmp ."
    and STR_TO_DATE(sf.depositDate,'%d-%m-%Y') = STR_TO_DATE(studentfee.depositDate,'%d-%m-%Y'))as depositAmount,
    (SELECT SUM(amount) from studentfee sf where 
    STR_TO_DATE(sf.depositDate,'%d-%m-%Y') = STR_TO_DATE(studentfee.depositDate,'%d-%m-%Y'))as amount,
    (SELECT SUM(amount) from studentfee  sf join studentadmission sa on sa.stud_id=sf.stud_id where  sf.paymentMode=1 " . $whereSub . $whereEmp ." and deposit LIKE '%yes%' 
    and STR_TO_DATE(sf.depositDate,'%d-%m-%Y') = STR_TO_DATE(studentfee.depositDate,'%d-%m-%Y'))as cash,
    (SELECT SUM(amount) from studentfee sf join studentadmission sa on sa.stud_id=sf.stud_id where sf.paymentMode=2 " . $whereSub . $whereEmp ." and deposit LIKE '%yes%' 
    and STR_TO_DATE(sf.depositDate,'%d-%m-%Y') = STR_TO_DATE(studentfee.depositDate,'%d-%m-%Y'))as cheque,
    (SELECT SUM(amount) from studentfee sf join studentadmission sa on sa.stud_id=sf.stud_id where sf.paymentMode=3 " . $whereSub . $whereEmp ." and deposit LIKE '%yes%' 
    and STR_TO_DATE(sf.depositDate,'%d-%m-%Y') = STR_TO_DATE(studentfee.depositDate,'%d-%m-%Y'))as paytm,
    (SELECT SUM(amount) from studentfee sf join studentadmission sa on sa.stud_id=sf.stud_id where sf.paymentMode=5 " . $whereSub . $whereEmp ."  
    and STR_TO_DATE(sf.depositDate,'%d-%m-%Y') = STR_TO_DATE(studentfee.depositDate,'%d-%m-%Y'))as BankTransfer 
    from studentfee join studentadmission on studentadmission.stud_id=studentfee.stud_id " . $where . " and deposit LIKE '%yes%' GROUP by STR_TO_DATE(studentfee.depositDate,'%d-%m-%Y')";


//$filterstr = "select depositDate,SUM(amount) as amount,SUM(depositAmount) as depositAmount,studentfee.comments,studentfee.remarks,studentfee.toBank,"
//            . "(SELECT SUM(amount) from studentfee sf where sf.depositDate=studentfee.depositDate and sf.paymentMode=1 and deposit LIKE '%yes%')as cash, "
//            . "(SELECT SUM(amount) from studentfee sf where sf.depositDate=studentfee.depositDate and sf.paymentMode=2 and deposit LIKE '%yes%')as cheque, "
//            . "(SELECT SUM(amount) from studentfee sf where sf.depositDate=studentfee.depositDate and sf.paymentMode=3 and deposit LIKE '%yes%')as paytm, "
//            . "(SELECT SUM(amount) from studentfee sf where sf.depositDate=studentfee.depositDate and sf.paymentMode=5 and deposit LIKE '%yes%')as BankTransfer "
//            . "from studentfee join studentadmission on studentadmission.stud_id=studentfee.stud_id  " . $where . " and  deposit LIKE '%yes%' GROUP by STR_TO_DATE(studentfee.depositDate,'%d-%m-%Y')";

$query = mysqli_query($dbconn, $filterstr);

if (mysqli_num_rows($query) > 0) {
    $delimiter = ",";
    $filename = "Cash-Deposit-Report_" . date('Y-m-d H:i:s') . ".csv";
    //create a file pointer
    $f = fopen('php://memory', 'w');
    //set column headers
    $fields = array(
        'ID',
        'Date',
//        'Amount',
        'Cash',
        'Cheque',
        'Paytm',
        'Bank transfer',
        'Deposit Amount',
//        'Bank Name',
        'Remark',
        'Comment'
    );

    fputcsv($f, $fields, $delimiter);
    $i = 1;

    $Total = array("Total", "-", 0, 0, 0, 0, 0, "-", "-");
    $Total[0] = "Total";
    $Total[1] = "-";
    //output each row of the data, format line as csv and write to file pointer
    while ($row = mysqli_fetch_assoc($query)) {

        $lineData = array(
            $i,
            $row['depositDate'],
//            $row['amount'],
            $row['cash'],
            $row['cheque'],
            $row['paytm'],
            $row['BankTransfer'],
            $row['depositAmount'],
//            $row['toBank'],
            $row['comments'],
            $row['remarks']
        );
//        $Total[2] = $row['amount'] * 1 + $Total[2] * 1;
        $Total[2] = $row['cash'] * 1 + $Total[2] * 1;
        $Total[3] = $row['cheque'] * 1 + $Total[3] * 1;
        $Total[4] = $row['paytm'] * 1 + $Total[4] * 1;
        $Total[5] = $row['BankTransfer'] * 1 + $Total[5] * 1;
        $Total[6] = $row['depositAmount'] * 1 + $Total[6] * 1;
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
    header('location:CashDepositReport.php?flg=1');
}
exit;
?>