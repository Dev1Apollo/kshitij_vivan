<?php

ob_start();
error_reporting(0);
require_once('../common.php');
$connect = new connect();
include_once('./IsLogin.php');
include 'IsLogin.php';
require_once 'spreadsheet-reader-master/php-excel-reader/excel_reader2.php';
require_once 'spreadsheet-reader-master/SpreadsheetReader.php';

$action = $_REQUEST['action'];

switch ($action) {

    case "uploadExcel":
        $iTestPaperId = $_POST['iTestPaperId'];
        $iEduLevelId = $_POST['iEduLevelId'];
        $isubjectId = $_POST['isubjectId'];
        $iInstituteId = $_SESSION['sessId'];
        $iTeacherId = $_SESSION['iTeacherId'];
        $ValCounter = 0;
        $iColumnCounter = array();
        $errorString = "";
        $iColumnCnt = 0;
        $CountMobile = 0;

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
                                if (trim($slice[$icounter]) == "ID") {
                                    $iColumnCounter[0] = $icounter;
                                }
                                if (trim($slice[$icounter]) == "Name") {
                                    $iColumnCounter[1] = $icounter;
                                }
                                if (trim($slice[$icounter]) == "Subject") {
                                    $iColumnCounter[2] = $icounter;
                                }
                                if (trim($slice[$icounter]) == "Marks") {
                                    $iColumnCounter[3] = $icounter;
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
                            if ($icounter == $iColumnCounter[0]) {
                                $StudentID = $slice[$icounter];
                                if (trim($StudentID) == "") {
                                    $errorString .= "Row " . $RowCounter . " & Student ID is blank <br/>";
                                }else {
                                    $filterStudent = mysqli_fetch_array(mysqli_query($dbconn, "SELECT count(*)as countS FROM `student` where isDelete='0' and iStatus='1' and iStudentId='" . $StudentID . "' and  iEduLevel='" . $iEduLevelId . "' and iInstituteId='" . $iInstituteId . "' and iStudentId in (SELECT studentId FROM `studentsubject` where SubjectId='" . $_REQUEST['isubjectId'] . "') "));
                                    if ($filterStudent['countS'] == 0) {
                                        $errorString .= "Row " . $RowCounter . " & iStudent Id  =" . $StudentID . " Not exist. <br/>";
                                    }
                                }
                            }
                        }
                    }
                    $ValCounter ++;
                }
            }


            if ($iColumnCounter[0] === '' || $iColumnCounter[1] === '') {
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
                                if ($slice != null && $slice[$iColumnCounter[0]] != '' && $slice[$iColumnCounter[1]] != '') {
                                    
                                    $filterExam = mysqli_query($dbconn, "delete FROM `studentexam` where iTestPaperId='".$iTestPaperId."' and iEduLevelId='".$iEduLevelId."' and iInstituteId='".$iInstituteId."' and istudentId='".$slice[$iColumnCounter[0]]."' ");
                                    
                                    $dataDetails = array(
                                        'iTestPaperId' => $iTestPaperId,
                                        'iEduLevelId' => $iEduLevelId,
                                        'iInstituteId' => $iInstituteId,
                                        'istudentId' => trim($slice[$iColumnCounter[0]]),
                                        'iTotalMarks' => trim($slice[$iColumnCounter[3]]),
                                        'iExamType' => 1,
                                        'iSubjectId' => $isubjectId,
                                        'iEnterBy' => $iTeacherId,
                                        'strIP' => $_SERVER['REMOTE_ADDR'],
                                        'strStartTime' => date('d-m-Y H:i:s'),
                                        'strEndTime' => date('d-m-Y H:i:s')
                                    );
                                    $insertDetails = $connect->insertrecord($dbconn, 'studentexam', $dataDetails);
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
        break;
}
?>