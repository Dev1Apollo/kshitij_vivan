<?php

error_reporting(0);
ob_start();
//include database configuration file
include('../config.php');
include('IsLogin.php');
//$connect = new connect();
//get records from database
//$whereEmpId = " and studentadmission.branchId = '" . $_SESSION['branchid'] . "'";
//
//$where = "";
//$whereEmi = "";
//if ($_REQUEST['FormDate'] != NULL && isset($_REQUEST['FormDate'])) {
//    $where .= " and STR_TO_DATE(studentemidetail.emiDate,'%d-%m-%Y')>= STR_TO_DATE('$_REQUEST[FormDate]','%d-%m-%Y')";
//    $whereEmi .= " and STR_TO_DATE(studentemidetail.emiDate,'%d-%m-%Y') < STR_TO_DATE('$_REQUEST[FormDate]','%d-%m-%Y')";
//    $whereFee .= " and STR_TO_DATE(studentfee.payDate,'%d-%m-%Y')< STR_TO_DATE('$_REQUEST[FormDate]','%d-%m-%Y')";
//} 
//else {
//    $date = date("01-m-Y");
////        $date = strtotime(date("01-m-Y", strtotime($date)) . "-3 months");
//    $date = strtotime(date("01-m-Y", strtotime($date)));
//    $date = date("01-m-Y", $date);
////        echo $date;
//    $where .= " and STR_TO_DATE(studentemidetail.emiDate,'%d-%m-%Y')>= STR_TO_DATE('$date','%d-%m-%Y')";
//    $whereEmi .= " and STR_TO_DATE(studentemidetail.emiDate,'%d-%m-%Y') < STR_TO_DATE('$date','%d-%m-%Y')";
//    $whereFee .= " and STR_TO_DATE(studentfee.payDate,'%d-%m-%Y') < STR_TO_DATE('$date','%d-%m-%Y')";
//}
//if ($_REQUEST['ToDate'] != NULL && isset($_REQUEST['ToDate'])) {
//    $where .= " and STR_TO_DATE(studentemidetail.emiDate,'%d-%m-%Y')<=STR_TO_DATE('$_REQUEST[ToDate]','%d-%m-%Y')";
//    $whereEmi .= " and STR_TO_DATE(studentemidetail.emiDate,'%d-%m-%Y') < STR_TO_DATE('$_REQUEST[ToDate]','%d-%m-%Y')";
//    $whereFee .= " and STR_TO_DATE(studentfee.payDate,'%d-%m-%Y') < STR_TO_DATE('$_REQUEST[ToDate]','%d-%m-%Y')";
//}
//$whereEmpDateWhere = " and studentadmission.stud_id in (select studentemidetail.stud_id from studentemidetail where stud_id > 0 " . $where . ")";
//
//$query = "SELECT max(studentemidetail.emiDate) as MaxEmiDate FROM `studentemidetail` where YEAR(STR_TO_DATE(studentemidetail.emiDate,'%d-%m-%Y')) = (select max(YEAR(STR_TO_DATE(studentemidetail.emiDate,'%d-%m-%Y'))) from studentemidetail where studentemidetail.isDelete=0)";
//$filterMaxDate = mysqli_fetch_array(mysqli_query($dbconn, $query));
//$MaxDate = $filterMaxDate['MaxEmiDate'];

$whereEmpId = " and studentadmission.branchId = '" . $_SESSION['branchid'] . "'";

$where = "";
$whereEmi = "";
if ($_REQUEST['FormDate'] != NULL && isset($_REQUEST['FormDate'])) {
    $where .= " and STR_TO_DATE(studentemidetail.emiDate,'%d-%m-%Y')>= STR_TO_DATE('$_REQUEST[FormDate]','%d-%m-%Y')";
    $whereEmi .= " and STR_TO_DATE(studentemidetail.emiDate,'%d-%m-%Y') < STR_TO_DATE('$_REQUEST[FormDate]','%d-%m-%Y')";
    $whereFee .= " and STR_TO_DATE(studentfee.payDate,'%d-%m-%Y')< STR_TO_DATE('$_REQUEST[FormDate]','%d-%m-%Y')";
} else {
    $date = date("01-m-Y");
//        $date = strtotime(date("01-m-Y", strtotime($date)) . "-3 months");
    $date = strtotime(date("01-m-Y", strtotime($date)));
    $date = date("01-m-Y", $date);
//        echo $date;
//        $where .= " and STR_TO_DATE(studentemidetail.emiDate,'%d-%m-%Y')>= STR_TO_DATE('$date','%d-%m-%Y')";
    $whereEmi .= " and STR_TO_DATE(studentemidetail.emiDate,'%d-%m-%Y') < STR_TO_DATE('$date','%d-%m-%Y')";
    $whereFee .= " and STR_TO_DATE(studentfee.payDate,'%d-%m-%Y') < STR_TO_DATE('$date','%d-%m-%Y')";
}
if ($_REQUEST['ToDate'] != NULL && isset($_REQUEST['ToDate'])) {
    $where .= " and STR_TO_DATE(studentemidetail.emiDate,'%d-%m-%Y')<=STR_TO_DATE('$_REQUEST[ToDate]','%d-%m-%Y')";
    $whereEmi .= " and STR_TO_DATE(studentemidetail.emiDate,'%d-%m-%Y') < STR_TO_DATE('$_REQUEST[ToDate]','%d-%m-%Y')";
    $whereFee .= " and STR_TO_DATE(studentfee.payDate,'%d-%m-%Y') < STR_TO_DATE('$_REQUEST[ToDate]','%d-%m-%Y')";
}
$whereEmpDateWhere = " and studentadmission.stud_id in (select studentemidetail.stud_id from studentemidetail where stud_id > 0 " . $where . ")";

$query = "SELECT max(studentemidetail.emiDate) as MaxEmiDate FROM `studentemidetail` where YEAR(STR_TO_DATE(studentemidetail.emiDate,'%d-%m-%Y')) = (select max(YEAR(STR_TO_DATE(studentemidetail.emiDate,'%d-%m-%Y'))) from studentemidetail where studentemidetail.isDelete=0)";
$filterMaxDate = mysqli_fetch_array(mysqli_query($dbconn, $query));
$MaxDate = $filterMaxDate['MaxEmiDate'];

$filterstr = "SELECT DISTINCT studentcourse.stud_id,studentcourse.dateOfJoining,studentcourse.offeredfee,studentcourse.courseId,studentemidetail.studentcourseId,studentemidetail.emiDate,sum((select studemi.emiAmount from  studentemidetail studemi where studemi.studemiId=studentemidetail.studemiId  " . $where . " and studentemidetail.isDelete=0 )) as TotalFees,sum((select studemi.actualReceivedAmount from  studentemidetail studemi where studemi.studemiId=studentemidetail.studemiId  " . $where . " and studentemidetail.isDelete=0)) as TotalactualReceivedAmount  FROM `studentemidetail` left join studentcourse on studentemidetail.studentcourseId=studentcourse.studentcourseId and studentcourse.istatus=1 and studentemidetail.isDelete=0 GROUP by studentemidetail.`studentcourseId` ORDER BY STR_TO_DATE(studentcourse.EnrollmentDate,'%d-%m-%Y') asc";
$datafilter = mysqli_query($dbconn, $filterstr);
if (mysqli_num_rows($datafilter) > 0) {

    //set column headers   
    $array[][] = array();
    if ($_REQUEST['FormDate'] != NULL && isset($_REQUEST['FormDate'])) {
        $currentDate = $_REQUEST['FormDate'];
        $start = $month = strtotime($currentDate);
    } else {
        $date = date("Y-m");
        $dateNew = date('Y-m');
        $start = $month = strtotime($dateNew);
    }
    if ($_REQUEST['ToDate'] != NULL && isset($_REQUEST['ToDate'])) {
        $end = $_REQUEST['ToDate'];
        $end = strtotime($end);
    } else {
        $end = strtotime($MaxDate);
    }

    $array[0][0] = "Sudent Name";
    $array[0][1] = "Contact Number";
    $array[0][2] = "Month Of Admission";
    $array[0][3] = "Booking Id";
    $array[0][4] = "Total Fees";
    $array[0][5] = "Over Due Amount";
    $iCounter = 6;

    while ($month <= $end) {
        date("M'Y", $month);
        $array[0][$iCounter] = date("M'Y", $month);
        $month = strtotime("+1 month", $month);
        $iCounter++;
    }


    $filterstudent = mysqli_query($dbconn, "Select studentadmission.title,studentadmission.firstName,studentadmission.middleName,studentadmission.surName,studentcourse.EnrollmentDate,studentcourse.offeredfee,studentadmission.employeeMasterId,studentadmission.mobileOne,studentcourse.studentcourseId,studentadmission.stud_id,studentcourse.bookingId  from studentadmission,studentcourse where studentcourse.stud_id=studentadmission.stud_id " . $whereEmpId . $whereEmpDateWhere . " and studentcourse.istatus=1 and studentadmission.iStudentStatus in (0,1,2) ORDER BY STR_TO_DATE(studentcourse.EnrollmentDate,'%d-%m-%Y') asc");

    $iCounter = 1;
    $ReceivedAmount = 0;
    $EmiAmount = 0;
    while ($dataresult = mysqli_fetch_array($filterstudent)) {
        $filterEmi = mysqli_query($dbconn, "select stud_id,studentcourseId from studentemidetail where studentcourseId='" . $dataresult['studentcourseId'] . "' and  stud_id='" . $dataresult['stud_id'] . "' and isDelete=0 ORDER by STR_TO_DATE(emiDate,'%d-%m-%Y') ASC limit 1");

        $i = 0;
        $filterTotalEmi = mysqli_fetch_array(mysqli_query($dbconn, "select sum(emiAmount) as EmiAmount from studentemidetail where studentcourseId='" . $dataresult['studentcourseId'] . "' and isDelete=0 and stud_id='" . $dataresult['stud_id'] . "' " . $whereEmi . " group by stud_id,studentcourseId ORDER by STR_TO_DATE(emiDate,'%d-%m-%Y') asc"));
        $filterTotalReceiveAmount = mysqli_fetch_array(mysqli_query($dbconn, "select sum(amount) as ReceivedAmount from studentfee where studentcourseId='" . $dataresult['studentcourseId'] . "' and stud_id='" . $dataresult['stud_id'] . "' " . $whereFee . " and feetype in (2,5) group by stud_id,studentcourseId ORDER by STR_TO_DATE(payDate,'%d-%m-%Y') asc"));

        $OverDueAmount = $filterTotalEmi['EmiAmount'] - $filterTotalReceiveAmount['ReceivedAmount'];
        $AmountToDisplay = 0;
        $getemi = mysqli_fetch_array($filterEmi);
        $TotalReceiveAmount = mysqli_fetch_array(mysqli_query($dbconn, "select sum(amount) as ReceivedAmount from studentfee where studentcourseId='" . $dataresult['studentcourseId'] . "' and stud_id='" . $dataresult['stud_id'] . "' and feetype in (1,2,5) group by stud_id,studentcourseId ORDER by STR_TO_DATE(payDate,'%d-%m-%Y') asc"));
        if ($dataresult['offeredfee'] - $TotalReceiveAmount['ReceivedAmount'] > 500)
            for ($jCounter = 0; $jCounter < sizeof($array[0]); $jCounter++) {
                if ($jCounter <= 5) {
                    if ($array[$iCounter][$jCounter] == "" || $array[$iCounter][$jCounter] == null || $array[$iCounter][$jCounter] == 'null') {
                        if ($jCounter == 0) {
                            $array[$iCounter][$jCounter] = $dataresult['title'] . ' ' . $dataresult['firstName'] . ' ' . $dataresult['middleName'] . ' ' . $dataresult['surName'];
                        } else if ($jCounter == 1) {
                            $array[$iCounter][$jCounter] = $dataresult['mobileOne'];
                        } else if ($jCounter == 2) {
                            $array[$iCounter][$jCounter] = date("M'Y", strtotime($dataresult['EnrollmentDate']));
                        } else if ($jCounter == 3) {
                            $array[$iCounter][$jCounter] = $dataresult['bookingId'];
                        } else if ($jCounter == 4){
                            $array[$iCounter][$jCounter] = $dataresult['offeredfee'];
                        } else if ($jCounter == 5) {
                            $array[$iCounter][$jCounter] = $OverDueAmount;
                        } else {
                            $array[$iCounter][$jCounter] = 0;
                        }
                    }
                } else {
                    $date = date('d-m-Y', strtotime("01-" . str_replace("'", "-", $array[0][$jCounter]))); //$getemi['emiDate'];
                    $dateOfmonth = $array[0][$jCounter]; //date("M'Y", strtotime($date));
                    $filterstr = mysqli_fetch_array(mysqli_query($dbconn, "SELECT  sum(studentfee.amount) as ReceivedFeeAmount from studentfee where month(STR_TO_DATE(studentfee.payDate,'%d-%m-%Y')) = month(STR_TO_DATE('" . $date . "','%d-%m-%Y')) "
                                    . " and  year(STR_TO_DATE(studentfee.payDate,'%d-%m-%Y')) = year(STR_TO_DATE('" . $date . "','%d-%m-%Y')) "
                                    . " and stud_id='" . $getemi['stud_id'] . "' and studentcourseId='" . $getemi['studentcourseId'] . "' and studentfee.feetype = '2' "
                                    . " group by month(STR_TO_DATE(studentfee.payDate,'%d-%m-%Y')),year(STR_TO_DATE(studentfee.payDate,'%d-%m-%Y')) ORDER BY studentfee.studentfeeid ASC"));
                    $i++;
                    //code to get emi amount
                    $filterCurrentMOunthEmi = mysqli_fetch_array(mysqli_query($dbconn, "SELECT  sum(studentemidetail.emiAmount) as emiAmount from studentemidetail where month(STR_TO_DATE(studentemidetail.emiDate,'%d-%m-%Y')) = month(STR_TO_DATE('" . $date . "','%d-%m-%Y')) "
                                    . " and  year(STR_TO_DATE(studentemidetail.emiDate,'%d-%m-%Y')) = year(STR_TO_DATE('" . $date . "','%d-%m-%Y')) "
                                    . " and stud_id='" . $getemi['stud_id'] . "' and studentcourseId='" . $getemi['studentcourseId'] . "' "
                                    . " group by month(STR_TO_DATE(studentemidetail.emiDate,'%d-%m-%Y')),year(STR_TO_DATE(studentemidetail.emiDate,'%d-%m-%Y')) ORDER BY studentemidetail.`studemiId` ASC"));
                    //$filterCurrentMOunthEmi['emiAmount'];

                    $DispalyValue = 0;
                    $CurrentMonthEMIAmount = $filterCurrentMOunthEmi['emiAmount'];
                    $CurrentMonthReceivedAmount = $filterstr['ReceivedFeeAmount'];

                    if ($CurrentMonthReceivedAmount == "") {
                        $CurrentMonthReceivedAmount = 0;
                    }
                    if ($array[$iCounter][5] < 0) {

                        $CurrentMonthReceivedAmount = $CurrentMonthReceivedAmount + ($array[$iCounter][5] * -1);
                        $array[$iCounter][5] = 0;
                    }
                    $AmountToDisplay = $filterCurrentMOunthEmi['emiAmount'] - ($CurrentMonthReceivedAmount + $AmountToDisplay);
                    $DispalyValue = $AmountToDisplay;

                    if ($AmountToDisplay < 0) {
                        $AmountToDisplay = $AmountToDisplay * -1;
                        if ($array[$iCounter][5] >= 0) {
                            if ($array[$iCounter][5] >= $AmountToDisplay) {
                                $array[$iCounter][5] = $array[$iCounter][5] - $AmountToDisplay;
                                $AmountToDisplay = 0;
                                $DispalyValue = 0;
                            } else {
                                $DispalyValue = 0;
                            }
                        }
                    } else {
                        $AmountToDisplay = 0;
                    }
                    $array[$iCounter][$jCounter] = $DispalyValue;
                }
            }
        $iCounter++;
    }
   
    $filename = "Projction-Report_" . date('Y-m-d H:i:s') . ".csv";
    $f = fopen("php://output", 'w') or die('not open');
    $delimiter = ",";

    $iCounter = 0;
    $kCounter = 0;
    $TotalArray = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    
    while ($iCounter < sizeof($array)) {
        $ValueField = "";
        if ($array[$kCounter][0] != "") {
            if ($iCounter == 0) {
                $ValueField .= $array[$iCounter][$jCounter];
            } else {
                $ValueField .= $array[$iCounter][$jCounter];
            }
            for ($jCounter = 0; $jCounter < sizeof($array[$kCounter]); $jCounter++) {
                if ($iCounter == 0) {
                    $ValueField .= $array[$kCounter][$jCounter] . ",";
                } else {
                    $ValueField .= $array[$kCounter][$jCounter] . ",";
                }
                if ($jCounter > 1) {
                    if ($TotalArray[$jCounter - 3] == "" || $TotalArray[$jCounter - 3] == null || $TotalArray[$jCounter - 3] == 'null') {
                        $TotalArray[$jCounter - 3] = 0;
                    }
                    $TotalArray[$jCounter - 3] = $TotalArray[$jCounter - 3] + $array[$kCounter][$jCounter];
                }
            }
            if ($iCounter == 0) {
                $ValueField .= ",";
            } else {
                $ValueField .= ",";
            }
            $iCounter++;
            $kCounter ++;
            echo $ValueField . "\n";
        } else {
            $kCounter ++;
        }
    }

    for ($jCounter = 0; $jCounter < sizeof($TotalArray) - 4; $jCounter++) {
        if ($jCounter == 0) {
            echo "Total";
        } else if ($jCounter == -3) {
            echo "-";
        }else if ($jCounter == -2) {
            echo "-";
        }else{
            echo $TotalArray[$jCounter - 4] . ',';
        }
    }
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '";');
    fpassthru($f);
} else {
    header('location:ProjectionReport.php?flg=1');
}
exit;
?>