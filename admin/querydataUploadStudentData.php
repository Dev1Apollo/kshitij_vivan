<?php

ob_start();
error_reporting(0);
require_once('../common.php');
$connect = new connect();
include 'IsLogin.php';

require('../spreadsheet-reader-master/SpreadsheetReader.php');
require_once '../spreadsheet-reader-master/php-excel-reader/excel_reader2.php';
$action = $_REQUEST['action'];



switch ($action) {

    case "UploadStudentData":

        $Date = 0;
        $errorString = "";
        $iColumnCounter = array();
        $ValCounter = 0;
        $Login = 0;
        $RowCounter = 0;
        $jCounterArray = 0;
        $LoginTime = 0;

//        $maxRow = 897;
        $EMIStartDateColumn = 13;
        $EMIEndDateColumn = 50;

        if (isset($_REQUEST['IMgallery'])) {
            $headerArray = array();
            $filename = trim($_REQUEST['IMgallery']);
            $file_path = 'temp/' . $filename;
            $Reader = new SpreadsheetReader($file_path);
            $Sheets = $Reader->Sheets();

            foreach ($Sheets as $Index => $Name) {
                $Reader->ChangeSheet($Index);
                $col1Value = "";
                foreach ($Reader as $key => $slice) {
                    if ($ValCounter == 0) {
                        for ($icounter = 0; $icounter < count($slice); $icounter ++) {
                            if (trim($slice[$icounter]) != "") {
                                       
                                $headerArray[$jCounterArray] = $slice[$icounter];
                                $jCounterArray++;
                                if (trim($slice[$icounter]) == "Student In") {
                                    $iColumnCounter[0] = $icounter;
                                }
                                if (trim($slice[$icounter]) == "Customer Number") {
                                    $iColumnCounter[1] = $icounter;
                                }
                                if (trim($slice[$icounter]) == "Date of Admission") {
                                    $iColumnCounter[2] = $icounter;
                                }
                                if (trim($slice[$icounter]) == "Name of the student") {
                                    $iColumnCounter[3] = $icounter;
                                }
                                if (trim($slice[$icounter]) == "Number") {
                                    $iColumnCounter[4] = $icounter;
                                }
                                if (trim($slice[$icounter]) == "Course") {
                                    $iColumnCounter[5] = $icounter;
                                }
                                if (trim($slice[$icounter]) == "Fees With ST") {
                                    $iColumnCounter[6] = $icounter;
                                }
                                if (trim($slice[$icounter]) == "Net Fees with ST") {
                                    $iColumnCounter[7] = $icounter;
                                }
                                if (trim($slice[$icounter]) == "Till date Pay received") {
                                    $iColumnCounter[8] = $icounter;
                                }
                                if (trim($slice[$icounter]) == "Last date of Receipt") {
                                    $iColumnCounter[9] = $icounter;
                                }
                                if (trim($slice[$icounter]) == "Balance Amt") {
                                    $iColumnCounter[10] = $icounter;
                                }
                                if (trim($slice[$icounter]) == "Status") {
                                    $iColumnCounter[11] = $icounter;
                                }
                                if (trim($slice[$icounter]) == "Over Due") {
                                    $iColumnCounter[13] = $icounter;
                                }
                                if (trim($slice[$icounter]) == "Duration") {
                                    $iColumnCounter[12] = $icounter;
                                }
                            }
                        }
                    } else {
                        $RowCounter = $ValCounter + 1;
                        for ($icounter = 0; $icounter < count($slice); $icounter++) {
                            if ($icounter == 0) {
                                $col1Value = $slice[$icounter];
                            }
                            if ($icounter == $iColumnCounter) {
                                $iColumnCnt = $slice[$icounter];
                            }
                            if ($icounter == $iColumnCounter[5]) {
                                $Course = $slice[$icounter];
                                if (trim($Course) != "") {
                                    $ResCourse = mysqli_query($dbconn, "SELECT count(*)as countS,course.* FROM course  where isDelete='0' and courseName='" . $Course . "'") OR die(mysqli_error($dbconn));
                                    $RowCourse = mysqli_fetch_array($ResCourse);
                                    if ($RowCourse['countS'] == 0) {
                                        $errorString .= "Row " . $RowCounter . " & Course Name =" . $Course . "  Not exists. <br/>";
                                    } else {
                                        $elisionloginidRow = $RowCourse['courseName'];
                                    }
                                }
                            }
                            if ($icounter == $iColumnCounter[11]) {
                                $studentStatusName = $slice[$icounter];
                                if (trim($studentStatusName) != "") {
                                    $ResStudentStatusName = mysqli_query($dbconn, "SELECT count(*)as countS,studentstatus.* FROM studentstatus  where isDelete='0' and studentStatusName  like '%" . $studentStatusName . "%'") OR die(mysqli_error($dbconn));
                                    $RowStudentStatusName = mysqli_fetch_array($ResStudentStatusName);
                                    if ($RowStudentStatusName['countS'] == 0) {
                                        $errorString .= "Row " . $RowCounter . " & Status =" . $studentStatusName . "  Not exists. <br/>";
                                    } else {
                                        $elisionloginidRow = $RowStudentStatusName['studentStatusName'];
                                    }
                                }
                            }
                            if ($icounter == $iColumnCounter[3]) {
                                $studentName = $slice[$icounter];
                                if (trim($studentName) == "") {
                                    $errorString .= "Row " . $RowCounter . " & Student Name =" . $studentName . "  Not exists. <br/>";
                                }
                            }
                            if ($icounter == $iColumnCounter[2]) {
                                $AdmissionDate = $slice[$icounter];
                                if (trim($AdmissionDate) == "") {
                                    $errorString .= "Row " . $RowCounter . " & Date Of Admission =" . $AdmissionDate . "  Not exists. <br/>";
                                }
                            }
                        }
                    }
                    $ValCounter ++;
                }
            }
            
            
            if ($iColumnCounter[1] == '' || $iColumnCounter[2] == '' || $iColumnCounter[3] == '' || $iColumnCounter[4] == '' || $iColumnCounter[5] == '' || $iColumnCounter[6] == '' || $iColumnCounter[7] == '' || $iColumnCounter[8] == '' || $iColumnCounter[9] == '' || $iColumnCounter[10] == '' || $iColumnCounter[11] == '' || $iColumnCounter[12] == '') {
                echo "Error : " . "Column Header Not Match";
                unlink($file_path);
                break;
            } else if (trim($errorString) != "") {
                echo "Error : " . $errorString;
                unlink($file_path);
                break;
            } else {
                $iCounterRow = 0;
                foreach ($Sheets as $Index => $Name) {
                    $Reader->ChangeSheet($Index);
                    if ($Reader != null) {
                        foreach ($Reader as $key => $slice) {
                            if ($iCounterRow > 0) {
                                if ($slice != null && $slice[$iColumnCounter[2]] != '' && $slice[$iColumnCounter[3]] != '' && $slice[$iColumnCounter[5]] != '' && $slice[$iColumnCounter[11]] != '') {
                                    $StudentCourse = $slice[$iColumnCounter[5]];
                                    $iStudentPortal = 0;
                                    if (trim($StudentCourse) != "") {
                                        $Course = mysqli_query($dbconn, "SELECT * FROM course where isDelete='0'  and  istatus='1' and courseName='" . $StudentCourse . "'");
                                        $rowStudentCourse = "";
                                        while ($rowCourse = mysqli_fetch_array($Course)) {
                                            $rowStudentCourse = $rowCourse['courseId'] . "," . $rowStudentCourse;
                                        }
                                        $rowStudentCourse = rtrim($rowStudentCourse, ",");
                                    }
                                    $StudentStatus = $slice[$iColumnCounter[11]];
                                    if (trim($StudentStatus) != "") {
                                        $rowStatus = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM studentstatus where isDelete='0'  and  istatus='1' and studentStatusName like '%" . $StudentStatus . "%'"));
                                        $rowStudentStatus = $rowStatus['studstatusid'];
                                    }
                                    $iStudentPortal = 0;
                                    $StudentPortal = $slice[$iColumnCounter[0]];
                                    if (trim($StudentPortal) != "") {
                                        if ($StudentPortal == "MAAC Satellite" || $StudentPortal == "Maac Satellite") {
                                            $iStudentPortal = 4;
                                        }
                                        if ($StudentPortal == "MAAC CG" || $StudentPortal == "Maac CG") {
                                            $iStudentPortal = 1;
                                        }
                                        if ($StudentPortal == "Other" || $StudentPortal == "OTHER") {
                                            $iStudentPortal = 3;
                                        }
                                        if ($StudentPortal == "Kshitij Vivan" || $StudentPortal == "KSHITIJ VIVAN") {
                                            $iStudentPortal = 2;
                                        }
                                    }
                                    $fName = "";
                                    $mName = "";
                                    $lName = "";
                                    $StudentName = $slice[$iColumnCounter[3]];
                                    if (trim($StudentName) != "") {
                                        $Name = preg_split('/\s+/', $StudentName, -1, PREG_SPLIT_NO_EMPTY);
                                        if (sizeof($Name) == 2) {
                                            $fName = $Name[0];
                                            $mName = "";
                                            $lName = $Name[1];
                                        } else if (sizeof($Name) == 3) {
                                            $fName = $Name[0];
                                            $mName = $Name[1];
                                            $lName = $Name[2];
                                        }
                                    }
                                    $DateAdmission = "";
                                    $AdmissionDate = $slice[$iColumnCounter[2]];
                                    if (trim($AdmissionDate) != "") {
                                        $timestamp = strtotime($AdmissionDate);
                                        $DateAdmission = date("d-m-Y", $timestamp);
                                    }
                                    $LastPayDate = "";
                                    $strPayDate = $slice[$iColumnCounter[9]];
                                    if (trim($strPayDate) != "") {
                                        $timestamp = strtotime($strPayDate);
                                        $LastPayDate = date("d-m-Y", $timestamp);
                                    }
                                    

                                    $data = array(
                                        "studentEnrollment" => $slice[$iColumnCounter[1]],
                                        "firstName" => $fName,
                                        "middleName" => $mName,
                                        "surName" => $lName,
                                        "mobileOne" => $slice[$iColumnCounter[4]],
                                        "studentPortal_Id" => $iStudentPortal,
                                        "strEntryDate" => date("d-m-Y H:i:s"),
                                        "strIP" => $_SERVER['REMOTE_ADDR'],
                                        "isRegister" => 1,
                                        "isAdmission" => 1,
                                        "branchId" => 2,
                                        "employeeMasterId" => 2,
                                        "iStudentStatus" => $rowStudentStatus
                                    );
                                    $StudentAdmissionInsert = $connect->insertrecord($dbconn, "studentadmission", $data);

                                    $dataCourse = array(
                                        "stud_id" => $StudentAdmissionInsert,
                                        "courseId" => $rowStudentCourse,
                                        "iPortal" => $iStudentPortal,
                                        "branchId" => 2,
                                        "fee" => $slice[$iColumnCounter[6]],
                                        "offeredfee" => $slice[$iColumnCounter[7]],
                                        "lastPaymentDate" => $slice[$iColumnCounter[9]],
                                        "noOfEmi" => $slice[$iColumnCounter[12]],
                                        "emiStartDate" => $DateAdmission,
                                        "dateOfJoining" => $DateAdmission,
                                        "EnrollmentDate" => $DateAdmission,
                                        "strIP" => $_SERVER['REMOTE_ADDR'],
                                    );
                                    $studentCourseInsert = $connect->insertrecord($dbconn, "studentcourse", $dataCourse);

                                    $dataCourseDetail = array(
                                        "stud_id" => $StudentAdmissionInsert,
                                        "courseId" => $rowStudentCourse,
                                        "studentcourseId" => $studentCourseInsert,
                                    );
                                    $studentCourseDetailInsert = $connect->insertrecord($dbconn, "studentcoursedetail", $dataCourseDetail);
                                    
                                    $amount = $slice[$iColumnCounter[8]];
                                    $taxFreeAmt = round($amount / 1.18, 2);
                                    $gst = round($amount - $taxFreeAmt, 2);
                                    $studentfee = array(
                                        "stud_id" => $StudentAdmissionInsert,
                                        "studentcourseId" => $studentCourseInsert,
                                        "feetype" => 2,
                                        "amount" => $amount,
                                        "payDate" => date('30' . "-m-Y", strtotime("-1 months")),
                                        "decGst" => $gst,
                                        "texFreeAmt" => $taxFreeAmt,
                                    );
                                    $studentFeeInsert = $connect->insertrecord($dbconn, "studentfee", $studentfee);
                                                                        
                                    $timestamp = strtotime($AdmissionDate);
                                    $i = 0;
                                    for ($icounter = $EMIStartDateColumn; $icounter < $EMIEndDateColumn; $icounter++) {
                                        $EmiDay = date("d", $timestamp);
                                        if ($icounter == 13) {
                                            $emiAmount = $slice[$iColumnCounter[8]] + $slice[$iColumnCounter[13]];
                                            $currentDate = date($EmiDay . "-m-Y", strtotime("-1 months"));
                                            $dataEmi = array(
                                                "stud_id" => $StudentAdmissionInsert,
                                                "emiDate" => $currentDate,
                                                "emiAmount" => $emiAmount,
                                                "studentcourseId" => $studentCourseInsert,
                                            );
                                            $studentEmiInsert = $connect->insertrecord($dbconn, "studentemidetail", $dataEmi);
                                            $dataEmi = array(
                                                "stud_id" => $StudentAdmissionInsert,
                                                "emiDate" => date($EmiDay . "-m-Y"),
                                                "emiAmount" => 0,
                                                "studentcourseId" => $studentCourseInsert,
                                            );
                                            $studentEmiInsert = $connect->insertrecord($dbconn, "studentemidetail", $dataEmi);
                                        }
                                        if ($icounter >= 14) {
                                            if ($slice[$icounter] != "null" && $slice[$icounter] != null && $slice[$icounter] != '' && $slice[$icounter] != NULL) {

                                                $dataEmi = array(
                                                    "stud_id" => $StudentAdmissionInsert,
                                                    "emiDate" => date($EmiDay . "-m-Y", strtotime("+ $i months")),
                                                    "emiAmount" => $slice[$icounter],
                                                    "studentcourseId" => $studentCourseInsert,
                                                );
                                                $studentEmiInsert = $connect->insertrecord($dbconn, "studentemidetail", $dataEmi);
                                            }
                                        }
                                        $i++;
                                    }

                                }
                            }
                            $iCounterRow++;
                        }
                    }
                }
                echo "Data Uploaded Successfully";
            }
            unlink($file_path);
        }
        @unlink($file_path);
        break;

    default:
# code...
        echo "Page not Found";
        break;
}
?>