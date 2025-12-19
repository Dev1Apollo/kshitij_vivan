<?php

ob_start();
error_reporting(E_ALL);
require_once('../common.php');
$connect = new connect();
include 'IsLogin.php';
include 'password_hash.php';


$action = $_REQUEST['action'];
switch ($action) {

    case "EditRegisteredStudent":
        $oldPortalId = $_REQUEST['Portal_Id'];
        $newPortalId = $_REQUEST['studentPortal_Id'];
        $data = array(
            "title" => ucwords($_POST['Title']),
            "firstName" => ucwords($_POST['firstName']),
            "middleName" => ucwords($_POST['middleName']),
            "surName" => ucwords($_POST['surName']),
            "DOB" => $_POST['DOB'],
            "gender" => ucwords($_POST['Gender']),
            "address" => ucwords($_POST['address']),
            "addresstwo" => ucwords($_POST['addresstwo']),
            "state" => $_POST['state'],
            "city" => ucwords($_POST['city']),
            "pincode" => ucwords($_POST['pincode']),
            "email" => ucwords($_POST['email']),
            "phone" => $_POST['phone'],
            "mobileOne" => $_POST['mobileOne'],
            "mobileTwo" => $_POST['mobileTwo'],
            "occupation" => ucwords($_POST['occupation']),
            "qualification" => ucwords($_POST['qualification']),
            "designation" => ucwords($_POST['designation']),
            "studentPortal_Id" => $_POST['studentPortal_Id'],
            "strIP" => $_SERVER['REMOTE_ADDR']
        );
        $where = ' where stud_id=' . $_REQUEST['stud_id'];
        $dealer_res = $connect->updaterecord($dbconn, 'studentadmission', $data, $where);

        if ($oldPortalId != $newPortalId) {
            if ($newPortalId == 2) {
                $filterStudentFees = mysqli_query($dbconn, "select count(*) as TotalRow,studentfee.* from studentfee where stud_id='" . $_REQUEST['stud_id'] . "'");
                if (mysqli_num_rows($filterStudentFees) > 0) {
                    $rowstudentfee = mysqli_fetch_array($filterStudentFees);
                    for ($iCounter = 0; $iCounter < $rowstudentfee['TotalRow']; $iCounter++) {
                        $studentfee = mysqli_fetch_array(mysqli_query($dbconn, "select max(recepitCount) as receiptNo from studentfee"));
                        if (isset($studentfee['receiptNo'])) {
                            $receiptNo = $studentfee['receiptNo'] + 1;
                        }
                        $rcNo = "RC" . $receiptNo;
                        $feesData = array(
                            "receiptNo" => $rcNo,
                            "recepitCount" => $receiptNo,
                        );
                        $where = ' where  stud_id=' . $_REQUEST['stud_id'];
                        $dealer_resFee = $connect->updaterecord($dbconn, 'studentfee', $feesData, $where);
                    }
                }
            }
        }
        echo $_REQUEST['stud_id'];
        break;

    case "EditStudentEntry" :

        $data = array(
            "title" => ucwords($_POST['Title']),
            "firstName" => ucwords($_POST['firstName']),
            "middleName" => ucwords($_POST['middleName']),
            "surName" => ucwords($_POST['surName']),
            "DOB" => $_POST['DOB'],
            "gender" => ucwords($_POST['Gender']),
            "address" => ucwords($_POST['address']),
            "city" => ucwords($_POST['city']),
            "pincode" => $_POST['pincode'],
            "email" => $_POST['email'],
            "phone" => $_POST['phone'],
            "mobileOne" => $_POST['mobileOne'],
            "mobileTwo" => $_POST['mobileTwo'],
            "occupation" => ucwords($_POST['occupation']),
            "qualification" => ucwords($_POST['qualification']),
            "designation" => ucwords($_POST['designation']),
            "strEntryDate" => date('d-m-Y H:i:s'),
            "strIP" => $_SERVER['REMOTE_ADDR']
        );
        $where = ' where  stud_id=' . $_REQUEST['stud_id'];
        $dealer_res = $connect->updaterecord($dbconn, 'studentadmission', $data, $where);

        echo $_REQUEST['stud_id'];
        break;

    case "studentMove":

        if ($_POST['studentPortal_Id'] == 2) {

            $findMaxBookid = mysqli_fetch_array(mysqli_query($dbconn, "SELECT MAX(bookId) as bookId FROM studentcourse where branchId = " . $_SESSION['branchid'] . " "));
            $branchAbbName = mysqli_fetch_array(mysqli_query($dbconn, "Select * from branchmaster where branchid =" . $_SESSION['branchid'] . " "));

            $bookId = $findMaxBookid['bookId'] + 1;
            $bookingId = 'BC/' . $branchAbbName['AbbreviationName'] . '/' . $bookId;

            $studentcourse = array(
                "bookingId" => $bookingId,
                "bookId" => $bookId
            );
            $where = "where stud_id =" . $_POST['stud_id'] . " and studentcourseId=" . $_POST['studentcourseId'];
            $dealer_reg = $connect->updaterecord($dbconn, 'studentcourse', $studentcourse, $where);

            $findMaxstudId = mysqli_fetch_array(mysqli_query($dbconn, "SELECT MAX(EnrollmentId) as EnrollmentId FROM studentadmission where branchId = " . $_SESSION['branchid'] . " and studentPortal_Id=" . $_POST['studentPortal_Id'] . " "));
            $studentEnroll = mysqli_fetch_array(mysqli_query($dbconn, "SELECT studentEnrollment FROM studentadmission where stud_id=" . $_POST['stud_id'] . " "));

            $EnrollmentId = $findMaxstudId['EnrollmentId'] + 1;
            $studentEnrollment = 'EN/' . $branchAbbName['AbbreviationName'] . '/' . $EnrollmentId;

            $admission = array(
                "EnrollmentId" => $EnrollmentId,
                "studentPortal_Id" => $_POST['studentPortal_Id'],
                "studentEnrollment" => $studentEnrollment
            );
            $where = "where stud_id =" . $_POST['stud_id'];
            $dealer_reg = $connect->updaterecord($dbconn, 'studentadmission', $admission, $where);

            $studentfee = mysqli_query($dbconn, "select * from studentfee where stud_id=" . $_POST['stud_id'] . " and studentcourseId=" . $_POST['studentcourseId'] . " ");
            $i = 1;
            while ($rowStudentfee = mysqli_fetch_array($studentfee)) {
                $FeeRecepitCount = mysqli_fetch_array(mysqli_query($dbconn, "select max(recepitCount) as receiptNo from studentfee"));
                $recepitCount = $FeeRecepitCount['receiptNo'] + $i;
                $rcNo = "RC" . $recepitCount;
                $receiptNo = $rcNo;

                $newdataStudentfee = array(
                    "studentcourseId" => $rowStudentfee['studentcourseId'],
                    "receiptNo" => $receiptNo,
                    "recepitCount" => $recepitCount,
                    "stud_id" => $rowStudentfee['stud_id'],
                    "feetype" => $rowStudentfee['feetype'],
                    "amount" => $rowStudentfee['amount'],
                    "payDate" => date('d-m-Y'),
                    "paymentMode" => $rowStudentfee['paymentMode'],
                    "bankName" => ucwords($rowStudentfee['bankName']),
                    "chequeNo" => $rowStudentfee['chequeNo'],
                    "deposit" => $rowStudentfee['deposit'],
                    "comments" => ucwords($rowStudentfee['comments']),
                    "decGst" => $rowStudentfee['decGst'],
                    "texFreeAmt" => $rowStudentfee['texFreeAmt'],
                    "strIP" => $_SERVER['REMOTE_ADDR']
                );
                $dealer = $connect->insertrecord($dbconn, 'studentfee', $newdataStudentfee);

                $dataStudentfee = array(
                    "studentcourseId" => $rowStudentfee['studentcourseId'],
                    "receiptNo" => $receiptNo,
                    "recepitCount" => $recepitCount,
                    "stud_id" => $rowStudentfee['stud_id'],
                    "feetype" => $rowStudentfee['feetype'],
                    "amount" => -abs($rowStudentfee['amount']),
                    "payDate" => date('d-m-Y'),
                    "paymentMode" => $rowStudentfee['paymentMode'],
                    "bankName" => ucwords($rowStudentfee['bankName']),
                    "chequeNo" => $rowStudentfee['chequeNo'],
                    "deposit" => $rowStudentfee['deposit'],
                    "comments" => ucwords($rowStudentfee['comments']),
                    "decGst" => -abs($rowStudentfee['decGst']),
                    "texFreeAmt" => -abs($rowStudentfee['texFreeAmt']),
                    "strIP" => $_SERVER['REMOTE_ADDR']
                );
                $dealer_res = $connect->insertrecord($dbconn, 'studentfee', $dataStudentfee);
            }
        } else {
            $data = array(
                "studentPortal_Id" => $_POST['studentPortal_Id'],
            );
            $where = "where stud_id =" . $_POST['stud_id'];
            $dealer_reg = $connect->updaterecord($dbconn, 'studentadmission', $data, $where);
        }
        echo $status = $dealer_reg ? 1 : 0;
        break;

    case "EditStudentfees":
//        print_r($_POST);
//        exit;
        $amount = $_POST['amount'];
        $taxFreeAmt = round($amount / 1.18, 2);
        $gst = round($amount - $taxFreeAmt, 2);

        if ($_POST['paymentMode'] == 1 || $_POST['paymentMode'] == 2) {
            $deposit = 'No';
            $depositAmount = '0';
            $depositDate = '';
            $toBank = "";
        } else {
            $deposit = 'Yes';
            $depositAmount = $_POST['amount'];
            $depositDate = $_POST['payDate'];
            $toBank = $_POST['bankDeposit'];
        }

        $datafee = array(
            "amount" => $_POST['amount'],
            "payDate" => $_POST['payDate'],
            "paymentMode" => $_POST['paymentMode'],
            "bankName" => ucwords($_POST['bank_name']),
            "chequeNo" => $_POST['cheqNumber'],
            "deposit" => $deposit,
            "depositAmount" => $depositAmount,
            "depositDate" => $depositDate,
            "toBank" => $toBank,
            "comments" => ucwords($_POST['comments']),
            "decGst" => $gst,
            "texFreeAmt" => $taxFreeAmt,
            "strIP" => $_SERVER['REMOTE_ADDR']
        );
        $where = ' where  studentfeeid=' . $_POST['studentfeeid'];
        $dealer_res = $connect->updaterecord($dbconn, 'studentfee', $datafee, $where);

        $dataFee = array(
            "emiReceivedDate" => $_POST['payDate'],
            "comments" => ucwords($_POST['comments']),
            "studentFeeId" => $_POST['studentfeeid'],
            "strIP" => $_SERVER['REMOTE_ADDR']
        );
        $where = ' where  studemiId =' . $_POST['studemiId'] . ' and studentfeeid=' . $_POST['studentfeeid'] . ' ';
        $dealer_emi = $connect->updaterecord($dbconn, 'studentemidetail', $dataFee, $where);

        echo $_POST['stud_id'];
        break;


    case "EditStudentEmi":

        $filterEmi = mysqli_query($dbconn, "delete from studentemidetail where stud_id=" . $_POST['stud_id'] . " and studentcourseId=" . $_POST['studentcourseId'] . "");
//        $filterEmi = mysqli_query($dbconn, "update studentemidetail SET isDelete='1' where stud_id=" . $_POST['stud_id'] . " and studentcourseId=" . $_POST['studentcourseId'] . "");
        $noOfEmi = $_POST['noOfEmi'];
        $emiStartDate = strtotime($_POST['emiStartDate']);
        $type = $_POST['emiId'];
        if ($type == 1) {
            $totalOfEmiAmt = $_POST['offeredfee'] - $_POST['joinAmount'];
            $dataEmi = array(
                "studentcourseId" => $_POST['studentcourseId'],
                "stud_id" => ucwords($_POST['stud_id']),
                "emiAmount" => $_POST['joinAmount'],
                "totalOfEmiAmt" => $_POST['joinAmount'],
                "joinAmount" => $_POST['joinAmount'],
                "emiDate" => $_POST['emiStartDate'],
                "booking_amount" => $_POST['booking_amount'],
                "strIP" => $_SERVER['REMOTE_ADDR']
            );
            $dealer_res2 = $connect->insertrecord($dbconn, 'studentemidetail', $dataEmi);
        } else if ($type == 2) {
            $totalOfEmiAmt = $_POST['emiAmount'];
            $i = 0;
            if ($i == 0) {
                $dataemi = array(
                    "studentcourseId" => $_POST['studentcourseId'],
                    "stud_id" => ucwords($_POST['stud_id']),
                    "emiAmount" => $_POST['joinAmount'],
                    "totalOfEmiAmt" => $_POST['joinAmount'],
                    "joinAmount" => $_POST['joinAmount'],
                    "emiDate" => $_POST['emiStartDate'],
                    "booking_amount" => $_POST['booking_amount'],
                    "strIP" => $_SERVER['REMOTE_ADDR']
                );
                $dealer_res2 = $connect->insertrecord($dbconn, 'studentemidetail', $dataemi);
            }
            if ($noOfEmi == 1) {
                $dataEmi = array(
                    "studentcourseId" => $_POST['studentcourseId'],
                    "stud_id" => ucwords($_POST['stud_id']),
                    "emiAmount" => $_POST['emiAmount'],
                    "totalOfEmiAmt" => $totalOfEmiAmt,
                    "joinAmount" => $_POST['joinAmount'],
                    "booking_amount" => $_POST['registeredAmount'],
                    "emiDate" => $_POST['emiStartDate'],
                    "strIP" => $_SERVER['REMOTE_ADDR']
                );
                $dealer_res2 = $connect->insertrecord($dbconn, 'studentemidetail', $dataEmi);
            }
            for ($i = 1; $i < $noOfEmi; $i++) {
                if ($i == 1) {
                    $dataEmi = array(
                        "studentcourseId" => $_POST['studentcourseId'],
                        "stud_id" => ucwords($_POST['stud_id']),
                        "emiAmount" => $_POST['emiAmount'],
                        "totalOfEmiAmt" => $totalOfEmiAmt,
                        "joinAmount" => $_POST['joinAmount'],
                        "booking_amount" => $_POST['registeredAmount'],
                        "emiDate" => $_POST['emiStartDate'],
                        "strIP" => $_SERVER['REMOTE_ADDR']
                    );
                    $dealer_res2 = $connect->insertrecord($dbconn, 'studentemidetail', $dataEmi);
                }
                $emiDate = date('d-m-Y', strtotime("+$i month", $emiStartDate));
                $totalOfEmiAmt = $totalOfEmiAmt + $_POST['emiAmount'];

                $dataEmi = array(
                    "studentcourseId" => $_POST['studentcourseId'],
                    "stud_id" => ucwords($_POST['stud_id']),
                    "emiAmount" => $_POST['emiAmount'],
                    "totalOfEmiAmt" => $totalOfEmiAmt,
                    "joinAmount" => $_POST['joinAmount'],
                    "booking_amount" => $_POST['registeredAmount'],
                    "emiDate" => $emiDate,
                    "strIP" => $_SERVER['REMOTE_ADDR']
                );
                $dealer_res2 = $connect->insertrecord($dbconn, 'studentemidetail', $dataEmi);
            }
        } else if ($type == 3) {
            $i = 0;
            $noOfEmi = $noOfEmi * 3;
            $totalOfEmiAmt = $_POST['emiAmount'];
            for ($i = 0; $i < $noOfEmi; $i++) {
                if ($i == 0) {
                    $dataEmi = array(
                        "studentcourseId" => $_POST['studentcourseId'],
                        "stud_id" => ucwords($_POST['stud_id']),
                        "emiAmount" => $_POST['emiAmount'],
                        "totalOfEmiAmt" => $totalOfEmiAmt,
                        "joinAmount" => $_POST['joinAmount'],
                        "emiDate" => $_POST['emiStartDate'],
                        "booking_amount" => $_POST['booking_amount'],
                        "strIP" => $_SERVER['REMOTE_ADDR']
                    );
                    $dealer_res2 = $connect->insertrecord($dbconn, 'studentemidetail', $dataEmi);
                }
                $emiDate = date('d-m-Y', strtotime("+$i month", $emiStartDate));
                $totalOfEmiAmt = $totalOfEmiAmt + $_POST['emiAmount'];

                $Emidata = array(
                    "studentcourseId" => $_POST['studentcourseId'],
                    "stud_id" => ucwords($_POST['stud_id']),
                    "emiAmount" => $_POST['emiAmount'],
                    "totalOfEmiAmt" => $totalOfEmiAmt,
                    "joinAmount" => $_POST['joinAmount'],
                    "booking_amount" => $_POST['registeredAmount'],
                    "emiDate" => $emiDate,
                    "booking_amount" => $_POST['booking_amount'],
                    "strIP" => $_SERVER['REMOTE_ADDR']
                );
                $dealer_res2 = $connect->insertrecord($dbconn, 'studentemidetail', $Emidata);

                $i = $i + 2;
            }
        }

        $data = array(
            "stud_id" => $_POST['stud_id'],
            "offeredfee" => $_POST['offeredfee'],
            "dateOfJoining" => $_POST['dateOfJoining'],
            "emiType" => $_POST['emiId'],
            "noOfEmi" => $_POST['noOfEmi'],
            "emiAmount" => $_POST['emiAmount'],
            "emiStartDate" => $_POST['emiStartDate'],
        );

        $where = ' where  stud_id =' . $_POST['stud_id'] . ' and studentcourseId= ' . $_POST['studentcourseId'] . ' ';
        $dealer_studentcourse = $connect->updaterecord($dbconn, 'studentcourse', $data, $where);

        $studentadmission = array(
            "strEntryDate" => $_POST['dateOfRegistration'],
        );
        $wherestud = "where stud_id ='" . $_POST['stud_id'] . "'";
        $dealer_studentadmission = $connect->updaterecord($dbconn, 'studentadmission', $studentadmission, $wherestud);

        echo $status = $dealer_res2 ? 1 : 0;
        break;

    case "StudentFeeDiscount":
        // payment Type 5 for discount amount

        $amount = $_POST['DiscountAmount'];
        $taxFreeAmt = round($amount / 1.18, 2);
        $gst = round($amount - $taxFreeAmt, 2);
        $datafee = array(
            "studentcourseId" => $_POST['studentcourseId'],
            "stud_id" => $_POST['stud_id'],
            "amount" => $_POST['DiscountAmount'],
            "payDate" => date('d-m-Y'),
            "feetype" => 5,
            "comments" => ucwords($_POST['Comment']),
            "decGst" => $gst,
            "texFreeAmt" => $taxFreeAmt,
            "strIP" => $_SERVER['REMOTE_ADDR']
        );
        $dealer_res = $connect->insertrecord($dbconn, 'studentfee', $datafee);
        echo $dealer_res;
        break;

    default:
# code...
        echo "Page not Found";
        break;
}
?>