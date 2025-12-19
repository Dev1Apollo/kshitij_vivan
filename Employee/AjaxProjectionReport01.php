<?php
error_reporting(0);
require_once('../common.php');
include('IsLogin.php');
$connect = new connect();
include ('User_Paging.php');

if ($_POST['action'] == 'ListUser') {
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
        $where .= " and STR_TO_DATE(studentemidetail.emiDate,'%d-%m-%Y')>= STR_TO_DATE('$date','%d-%m-%Y')";
        $whereEmi .= " and STR_TO_DATE(studentemidetail.emiDate,'%d-%m-%Y') < STR_TO_DATE('$date','%d-%m-%Y')";
        $whereFee .= " and STR_TO_DATE(studentfee.payDate,'%d-%m-%Y') < STR_TO_DATE('$date','%d-%m-%Y')";
    }
    if ($_REQUEST['ToDate'] != NULL && isset($_REQUEST['ToDate'])) {
        $where .= " and STR_TO_DATE(studentemidetail.emiDate,'%d-%m-%Y')<=STR_TO_DATE('$_REQUEST[ToDate]','%d-%m-%Y')";
        $whereEmi .= " and STR_TO_DATE(studentemidetail.emiDate,'%d-%m-%Y') < STR_TO_DATE('$_REQUEST[ToDate]','%d-%m-%Y')";
        $whereFee .= " and STR_TO_DATE(studentfee.payDate,'%d-%m-%Y') < STR_TO_DATE('$_REQUEST[ToDate]','%d-%m-%Y')";
    }
    $whereEmpDateWhere = " and studentadmission.stud_id in (select studentemidetail.stud_id from studentemidetail where stud_id > 0 " . $where . ")";

    $query = "SELECT max(studentemidetail.emiDate) as MaxEmiDate FROM `studentemidetail` where YEAR(STR_TO_DATE(studentemidetail.emiDate,'%d-%m-%Y')) = (select max(YEAR(STR_TO_DATE(studentemidetail.emiDate,'%d-%m-%Y'))) from studentemidetail)";
    $filterMaxDate = mysqli_fetch_array(mysqli_query($dbconn, $query));
    $MaxDate = $filterMaxDate['MaxEmiDate'];

    $filterstr = "SELECT DISTINCT studentcourse.stud_id,studentcourse.dateOfJoining,studentcourse.offeredfee,studentcourse.courseId,studentemidetail.studentcourseId,studentemidetail.emiDate,sum((select studemi.emiAmount from  studentemidetail studemi where studemi.studemiId=studentemidetail.studemiId  " . $where . " )) as TotalFees,sum((select studemi.actualReceivedAmount from  studentemidetail studemi where studemi.studemiId=studentemidetail.studemiId  " . $where . " )) as TotalactualReceivedAmount  FROM `studentemidetail` left join studentcourse on studentemidetail.studentcourseId=studentcourse.studentcourseId GROUP by studentemidetail.`studentcourseId` ORDER BY STR_TO_DATE(studentcourse.EnrollmentDate,'%d-%m-%Y') asc";
//    $countstr = "SELECT COUNT(*) AS TotalRow, studentcourse.stud_id,studentcourse.dateOfJoining,studentcourse.courseId,sum((select studemi.emiAmount from  studentemidetail studemi where studemi.studemiId=studentemidetail.studemiId  " . $where . " )) as TotalFees FROM `studentemidetail` left join studentcourse on studentemidetail.studentcourseId=studentcourse.studentcourseId ";
    $countstr = "Select COUNT(*) AS TotalRow  from studentadmission,studentcourse where studentcourse.stud_id=studentadmission.stud_id " . $whereEmpId . $whereEmpDateWhere . " and studentadmission.iStudentStatus in (0,1,2) ORDER BY STR_TO_DATE(studentcourse.EnrollmentDate,'%d-%m-%Y') asc";

    $resrowcount = mysqli_query($dbconn, $countstr);
    $resrowc = mysqli_fetch_array($resrowcount);
    $totalrecord = $resrowc['TotalRow'];
    $per_page = $cateperpaging;
    $total_pages = ceil($totalrecord / $per_page);
    $page = $_REQUEST['Page'] - 1;
    $startpage = $page * $per_page;
    $show_page = $page + 1;

    $filterstr = $filterstr . " LIMIT $startpage, $per_page";

    $resultfilter = mysqli_query($dbconn, $filterstr);
    if (mysqli_num_rows($resultfilter) > 0) {
        $i = 0;
        $serial = 0;
        $serial = ($page * $per_page);
        ?>  
        <link href="<?php echo $web_url; ?>Employee/assets/global/plugins/datatables/datatables.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo $web_url; ?>Employee/assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" />
        <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>       
        <?php
        $array[][] = array();
        if ($_REQUEST['FormDate'] != NULL && isset($_REQUEST['FormDate'])) {
            $currentDate = $_REQUEST['FormDate'];
            $start = $month = strtotime($currentDate);
        } else {
            $date = date("Y-m");
//            $dateNew =  date('Y-m', strtotime('-3 months'));
            $dateNew = date('Y-m');
            $start = $month = strtotime($dateNew);
//            $date = date("Y-m", $date);
            // code for current month
//            $currentDate = date('Y-m');
//            echo $date = date('Y-m',strtotime($currentDate));
//            $start = $month = strtotime($currentDate);
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
        $array[0][3] = "Total Fees";
        $array[0][4] = "Over Due Amount";
        $iCounter = 5;

        while ($month <= $end) {
            date("M'Y", $month);
            $array[0][$iCounter] = date("M'Y", $month);
            $month = strtotime("+1 month", $month);
            $iCounter++;
        }

        //query to get student and course only.
//        echo "Select studentadmission.title,studentadmission.firstName,studentadmission.middleName,studentadmission.surName,studentcourse.EnrollmentDate,studentcourse.offeredfee,studentadmission.employeeMasterId,studentadmission.mobileOne,studentcourse.studentcourseId,studentadmission.stud_id  from studentadmission,studentcourse where studentcourse.stud_id=studentadmission.stud_id " . $whereEmpId . $whereEmpDateWhere . " and studentadmission.iStudentStatus in (0,1,2) ORDER BY STR_TO_DATE(studentcourse.EnrollmentDate,'%d-%m-%Y') asc";
//        echo "Select studentadmission.title,studentadmission.firstName,studentadmission.middleName,studentadmission.surName,studentcourse.EnrollmentDate,studentcourse.offeredfee,studentadmission.employeeMasterId,studentadmission.mobileOne,studentcourse.studentcourseId,studentadmission.stud_id  from studentadmission,studentcourse where studentcourse.stud_id=studentadmission.stud_id " . $whereEmpId . $whereEmpDateWhere . " and studentadmission.iStudentStatus in (0,1,2) ORDER BY STR_TO_DATE(studentcourse.EnrollmentDate,'%d-%m-%Y') asc";
        $filterstudent = mysqli_query($dbconn, "Select studentadmission.title,studentadmission.firstName,studentadmission.middleName,studentadmission.surName,studentcourse.EnrollmentDate,studentcourse.offeredfee,studentadmission.employeeMasterId,studentadmission.mobileOne,studentcourse.studentcourseId,studentadmission.stud_id  from studentadmission,studentcourse where studentcourse.stud_id=studentadmission.stud_id " . $whereEmpId . $whereEmpDateWhere . " and studentadmission.iStudentStatus in (0,1,2) ORDER BY STR_TO_DATE(studentcourse.EnrollmentDate,'%d-%m-%Y') asc");
        $iCounter = 1;
        while ($dataresult = mysqli_fetch_array($filterstudent)) {
//            echo "select * from studentemidetail where studentcourseId='" . $dataresult['studentcourseId'] . "' and  stud_id='" . $dataresult['stud_id'] . "' " . $where . " ORDER by STR_TO_DATE(emiDate,'%d-%m-%Y') ASC";
//            echo "select * from studentemidetail where studentcourseId='" . $dataresult['studentcourseId'] . "' and  stud_id='" . $dataresult['stud_id'] . "' " . $where . " ORDER by STR_TO_DATE(emiDate,'%d-%m-%Y') ASC";
            $filterEmi = mysqli_query($dbconn, "select * from studentemidetail where studentcourseId='".$dataresult['studentcourseId']."' and  stud_id='" . $dataresult['stud_id'] . "' " . $where . " ORDER by STR_TO_DATE(emiDate,'%d-%m-%Y') ASC");
            
            $i = 0;
            $filterTotalEmi = mysqli_fetch_array(mysqli_query($dbconn, "select sum(emiAmount) as EmiAmount from studentemidetail where studentcourseId='" . $dataresult['studentcourseId'] . "' and stud_id='" . $dataresult['stud_id'] . "' " . $whereEmi . " group by stud_id,studentcourseId ORDER by STR_TO_DATE(emiDate,'%d-%m-%Y') asc"));
            $filterTotalReceiveAmount = mysqli_fetch_array(mysqli_query($dbconn, "select sum(amount) as ReceivedAmount from studentfee where studentcourseId='" . $dataresult['studentcourseId'] . "' and stud_id='" . $dataresult['stud_id'] . "' and feetype = '2' " . $whereFee . " group by stud_id,studentcourseId ORDER by STR_TO_DATE(payDate,'%d-%m-%Y') asc"));

            $OverDueAmount = $filterTotalEmi['EmiAmount'] - $filterTotalReceiveAmount['ReceivedAmount'];
            while ($getemi = mysqli_fetch_array($filterEmi)) {
                $date = $getemi['emiDate'];
                $dateOfmonth = date("M'Y", strtotime($date));
                $filterstr = mysqli_fetch_array(mysqli_query($dbconn, "SELECT  sum(studentfee.amount) as ReceivedFeeAmount from studentfee where month(STR_TO_DATE(studentfee.payDate,'%d-%m-%Y')) = month(STR_TO_DATE('" . $date . "','%d-%m-%Y')) "
                                . " and  year(STR_TO_DATE(studentfee.payDate,'%d-%m-%Y')) = year(STR_TO_DATE('" . $date . "','%d-%m-%Y')) "
                                . " and stud_id='" . $getemi['stud_id'] . "' and studentcourseId='" . $getemi['studentcourseId'] . "' and studentfee.feetype = '2' "
                                . " group by month(STR_TO_DATE(studentfee.payDate,'%d-%m-%Y')),year(STR_TO_DATE(studentfee.payDate,'%d-%m-%Y')) ORDER BY studentfee.studentfeeid ASC"));
                $i++;
                if ($dataresult['offeredfee'] - $filterTotalReceiveAmount['ReceivedAmount'] > 500) {
//                    
//                } else {
//                $ReceiveCarriedAmount = 0;
//                    print_r($array[0]);
//                    exit;
                    for ($jCounter = 0; $jCounter < sizeof($array[0]); $jCounter++) {
                        if ($OverDueAmount < 0) {
                            $overDue = ($OverDueAmount) * (-1);
                            $ReceiveCarriedAmount = $overDue;
                            $OverDueAmount = 0;
                            $overDue = 0;
                        } else {
                            $overDue = $OverDueAmount;
                        }
                        if ($array[$iCounter][$jCounter] == "" || $array[$iCounter][$jCounter] == null || $array[$iCounter][$jCounter] == 'null') {
                            if ($jCounter == 0) {
                                $array[$iCounter][$jCounter] = $dataresult['title'] . ' ' . $dataresult['firstName'] . ' ' . $dataresult['middleName'] . ' ' . $dataresult['surName'];
                            } else if ($jCounter == 1) {
                                $array[$iCounter][$jCounter] = $dataresult['mobileOne'];
                            } else if ($jCounter == 2) {
                                $array[$iCounter][$jCounter] = date("M'Y", strtotime($dataresult['EnrollmentDate']));
                            } else if ($jCounter == 3) {
                                $array[$iCounter][$jCounter] = $dataresult['offeredfee'];
                            } else if ($jCounter == 4) {
                                $array[$iCounter][$jCounter] = $overDue;
                            } else {
                                $array[$iCounter][$jCounter] = 0;
                            }
                        }
                        if ($dateOfmonth == $array[0][$jCounter]) {
                            $ReceiveCarriedAmount = $filterstr['ReceivedFeeAmount'] + $ReceiveCarriedAmount;
                            if ($getemi['emiAmount'] - $ReceiveCarriedAmount <= 0) {
                                $ReceiveCarriedAmount = $ReceiveCarriedAmount - $getemi['emiAmount'];
                                $array[$iCounter][$jCounter] = 0;
                            } else {
                                $array[$iCounter][$jCounter] = $getemi['emiAmount'] - $ReceiveCarriedAmount;
                                $ReceiveCarriedAmount = 0;
                            }
                        }
                    }
                }
            }
            $iCounter++;
        }
//        echo "<pre>";
//        print_r($array);
//        exit;
//       echo sizeof($array);

        $TotalArray = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
        echo "<div class='table-responsive'> <table class='table table-bordered table-hover center table-responsive dt-responsive' width='100%' id='tableC'>";
//        echo sizeof($array) . "<br />";
        //for($iCounter =0; $iCounter<sizeof($array); $iCounter++)
        $iCounter = 0;
        $kCounter = 0;
        while ($iCounter < sizeof($array)) {

//             if ($array[$kCounter] != "") {
//                 echo $iCounter . " ". $array[$kCounter][0] ."<br/>";
//                 $kCounter ++;
//                 $iCounter++;
//             }
//             else{
//                 echo $iCounter . " hi ". $array[$kCounter] ."<br/>";
//                 $kCounter ++;
//             }
            if ($array[$kCounter][0] != "") {
                if ($iCounter == 0) {
                    echo "<thead class='tbg'><tr>";
                } else if ($iCounter == 0) {
                    echo "<tbody><tr>";
                } else {
                    echo "<tr>";
                }
                for ($jCounter = 0; $jCounter < sizeof($array[$kCounter]); $jCounter++) {
                    if ($iCounter == 0) {
                        echo "<th class='desktop'>" . $array[$kCounter][$jCounter] . "</th>";
                    } else {
                        echo "<td><div class='form-group form-md-line-input '>" . $array[$kCounter][$jCounter] . "</div></td>";
                    }
                    if ($jCounter > 1) {
                        if ($TotalArray[$jCounter - 4] == "" || $TotalArray[$jCounter - 4] == null || $TotalArray[$jCounter - 4] == 'null') {
                            $TotalArray[$jCounter - 4] = 0;
                        }
                        $TotalArray[$jCounter - 4] = $TotalArray[$jCounter - 4] + $array[$kCounter][$jCounter];
                    }
                }


                if ($iCounter == 0) {
                    echo "</tr></thead>";
                } else {
                    echo "</tr>";
                }
                $iCounter++;
                $kCounter ++;
            } else {
                $kCounter ++;
            }
        }

        echo "<tr style='background-color:#f3f3f3'>";
        echo "<td><div class='form-group form-md-line-input '>Total</div></td>";
        echo "<td>-</td>";
        echo "<td>-</td>";

        for ($jCounter = -1; $jCounter < sizeof($TotalArray) - 2; $jCounter++) {
            echo "<td><div class='form-group form-md-line-input '>" . $TotalArray[$jCounter] . "</div></td>";
        }
        echo "</tr>";

        echo "</tbody></table>";
        ?>


        </tr>

        </tbody>

        <?php
    } else {
        ?>
        <div class="row">
            <div class="col-lg-12 col-md-12  col-xs-12 col-sm-12 padding-5 bottom-border-verydark">
                <div class="alert alert-info clearfix profile-information padding-all-10 margin-all-0 backgroundDark">
                    <h1 class="font-white text-center"> No Data Found ! </h1>
                </div>   
            </div>
        </div>
        <?php
    }
}
?>
<?php if ($totalrecord > $per_page) { ?>
    <div class="row">
        <div class="col-lg-12 m-pager">

            <div class="col-lg-12 col-md-12  col-xs-12 col-sm-12 padding-5 bottom-border-verydark" style="text-align: center;">
                <div class="form-actions noborder">
                    <?php
                    echo '<ul>';

                    if ($totalrecord > $per_page) {
                        echo paginate($reload = '', $show_page, $total_pages);
                    }
                    echo "</ul>";
                    ?>
                </div>
            </div>

        </div>
    </div>
<?php } ?>


