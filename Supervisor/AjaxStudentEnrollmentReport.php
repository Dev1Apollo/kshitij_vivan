<?php
error_reporting(0);
require_once('../common.php');
include('IsLogin.php');
$connect = new connect();
include('User_Paging.php');



if ($_POST['action'] == 'ListUser') {
    //    $where = "where 1=1";
    //$whereEmpId = " and studentadmission.branchId = '" . $_SESSION['branchid'] . "'";
    if ($_SESSION['EmployeeType'] == 'Supervisor') {
        if ($_POST['branchid'] != NULL && isset($_POST['branchid']))
            $whereEmpId .= " and studentadmission.branchId='" . $_POST['branchid'] . "'";
    } else {
        $whereEmpId .= " and studentadmission.branchId=" . $_SESSION['branchid'] . "'";
    }
    
    if ($_REQUEST['FormDate'] != NULL && isset($_REQUEST['FormDate']))
        $where .= " and STR_TO_DATE(studentcourse.EnrollmentDate,'%d-%m-%Y')>= STR_TO_DATE('" . $_REQUEST['FormDate'] . "','%d-%m-%Y')";

    if ($_REQUEST['ToDate'] != NULL && isset($_REQUEST['ToDate']))
        $where .= " and DATE_FORMAT(STR_TO_DATE(studentcourse.EnrollmentDate, '%d-%m-%Y'), '%Y-%m-%d')<=STR_TO_DATE('" . $_REQUEST['ToDate'] . "','%d-%m-%Y')";

    if ($_REQUEST['studentPortal_Id'] != NULL && isset($_REQUEST['studentPortal_Id']))
        $where .= " and studentadmission.studentPortal_Id in (" . implode(",", $_REQUEST['studentPortal_Id']) . ") ";

    if ($_REQUEST['studentStatus'] != NULL && isset($_REQUEST['studentStatus']))
        $where .= " and studentadmission.iStudentStatus in (" . implode(',', $_REQUEST['studentStatus']) . ")";
    else
        $where .= " and studentadmission.iStudentStatus in (select studentstatus.studstatusid from studentstatus where isDelete=0)";


    $filterstr = "SELECT studentadmission.*,studentcourse.* from studentadmission,studentcourse where studentcourse.stud_id=studentadmission.stud_id " . $where . $whereEmpId . " and studentcourse.istatus=1 and studentcourse.courseId in (SELECT courseId from course) GROUP BY studentcourse.stud_id,studentcourse.courseId ORDER BY STR_TO_DATE(studentcourse.EnrollmentDate,'%d-%m-%Y') asc";
    $countstr = "SELECT count(*) as TotalRow, studentadmission.*,studentcourse.* from studentadmission,studentcourse where studentcourse.stud_id=studentadmission.stud_id " . $where . $whereEmpId . "  and studentcourse.istatus=1 and  studentcourse.courseId in (SELECT courseId from course)";

    $resrowcount = mysqli_query($dbconn, $countstr);
    $resrowc = mysqli_fetch_array($resrowcount);
    $totalrecord = $resrowc['TotalRow'];
    $per_page = $cateperpaging;
    $total_pages = ceil($totalrecord / $per_page);
    $page = $_REQUEST['Page'] - 1;
    $startpage = $page * $per_page;
    $show_page = $page + 1;



    $filterstr = $filterstr . " LIMIT $startpage, $per_page";
    // echo $filterstr;


    $resultfilter = mysqli_query($dbconn, $filterstr);
    if (mysqli_num_rows($resultfilter) > 0) {
        $i = 0;
        $get_count = $resrowc['TotalRow'];
?>


        <link href="<?php echo $web_url; ?>Supervisor/assets/global/plugins/datatables/datatables.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo $web_url; ?>Supervisor/assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" />
        <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
        <div class="table-responsive">
            <table class="table table-bordered table-hover center table-responsive dt-responsive" width="100%" id="tableC">
                <thead class="tbg">
                    <tr>
                        <th class="all">Sr.No</th>
                        <th class="desktop">Type</th>
                        <th class="desktop">Booking Id</th>
                        <th class="desktop">Branch</th>
                        <th class="desktop">Student Enrollment</th>
                        <th class="desktop">Month Of Admission</th>
                        <th class="desktop">Date Of Admission</th>
                        <th class="desktop">Name Of Student</th>
                        <th class="desktop">Contact Number</th>
                        <th class="desktop">Course</th>
                        <!--<th class="desktop">No. Of EMI</th>-->
                        <!--<th class="desktop">Source</th>-->
                        <th class="desktop">Actual Fee</th>
                        <th class="desktop">Offered Fee</th>
                        <th class="desktop">Till Date Payment Receive</th>
                        <th class="desktop">Last Date Of Receipt</th>
                        <th class="desktop">Pending Days</th>
                        <th class="desktop">Balance Amount</th>
                        <th class="desktop">Student Status</th>
                        <th class="desktop">Remark</th>
                        <!--                    <th class="desktop">Remark - 2</th>-->
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($rowfilter = mysqli_fetch_array($resultfilter)) {
                        $walkin[0] += $rowfilter['fee'];
                        $walkin[1] += $rowfilter['offeredfee'];
                        $i++;
                        $serial++;
                        $employeeMaster = [];
                        if(isset($rowfilter['branchId']) && $rowfilter['branchId'] > 0){
                            $employeeMaster = mysqli_fetch_array(mysqli_query($dbconn, "select * from branchmaster where branchid = '" . $rowfilter['branchId'] . "'"));
                        }
                    ?>
                        <tr>
                            <td>
                                <div class="form-group form-md-line-input "><?php echo $i; ?>
                                </div>
                            </td>
                            <td>
                                <div class="form-group form-md-line-input ">
                                    <?php
                                    if ($rowfilter['studentPortal_Id'] == 1) {
                                        echo 'Maac CG';
                                    } else if ($rowfilter['studentPortal_Id'] == 2) {
                                        echo 'Kshitij Vivan';
                                    } else if ($rowfilter['studentPortal_Id'] == 4) {
                                        echo 'Maac Satellite';
                                    } else {
                                        echo 'Other';
                                    }
                                    ?>
                                </div>
                            </td>
                            <td>
                                <div class="form-group form-md-line-input "><?php
                                                                            echo $rowfilter['bookingId'];
                                                                            ?>
                                </div>
                            </td>
                            <td>
                                <div class="form-group form-md-line-input ">
                                <?php echo !empty($employeeMaster) ? $employeeMaster['branchname'] : '-'; ?> 
                                </div>
                            </td>
                            <td>
                                <div class="form-group form-md-line-input "><?php
                                                                            echo $rowfilter['studentEnrollment'];
                                                                            ?>
                                </div>
                            </td>
                            <td>
                                <div class="form-group form-md-line-input "><?php
                                                                            echo date("M'Y", strtotime($rowfilter['EnrollmentDate']));
                                                                            ?>
                                </div>
                            </td>

                            <td>
                                <div class="form-group form-md-line-input "><?php
                                                                            echo $rowfilter['EnrollmentDate'];
                                                                            ?>
                                </div>
                            </td>

                            <td>
                                <div class="form-group form-md-line-input "><?php
                                                                            echo $rowfilter['title'] . ' ' . $rowfilter['firstName'] . ' ' . $rowfilter['middleName'] . ' ' . $rowfilter['surName'];
                                                                            ?>
                                </div>
                            </td>
                            <td>
                                <div class="form-group form-md-line-input "><?php echo $rowfilter['mobileOne']; ?>
                                </div>
                            </td>
                            <td>
                                <div class="form-group form-md-line-input "><?php
                                                                            $filterCourse = mysqli_query($dbconn, "Select * from course where courseId in (" . $rowfilter['courseId'] . ") order by courseId DESC");
                                                                            $courseName = '';
                                                                            while ($rowcourse = mysqli_fetch_array($filterCourse)) {
                                                                                $courseName = $rowcourse['courseName'] . "," . $courseName;
                                                                            }
                                                                            echo $courseName = rtrim($courseName, ',');
                                                                            ?>
                                </div>
                            </td>
                            <!--                            <td>
                                <div class="form-group form-md-line-input "><?php
                                                                            //                                    echo $rowfilter['noOfEmi'];
                                                                            ?> 
                                </div>
                            </td>
                            <td>
                                <div class="form-group form-md-line-input "><?php
                                                                            //                                    $filtersourse = "SELECT * FROM studentadmission join lead on studentadmission.leadId=lead.leadId join customerentry on customerentry.customerEntryId=lead.customerEntryId join inquirysource on customerentry.inquirySourceId=inquirysource.inquirySourceId where studentadmission.stud_id='" . $rowfilter['stud_id'] . "'";
                                                                            //                                    $rowSourse = mysqli_fetch_array(mysqli_query($dbconn,$filtersourse));
                                                                            //                                    echo $rowSourse['inquirySourceName'];
                                                                            ?> 
                                </div>
                            </td>-->
                            <td>
                                <div class="form-group form-md-line-input "><?php
                                                                            echo $rowfilter['fee'];
                                                                            ?>
                                </div>
                            </td>
                            <td>
                                <div class="form-group form-md-line-input "><?php
                                                                            echo $rowfilter['offeredfee'];
                                                                            ?>
                                </div>
                            </td>
                            <td>
                                <div class="form-group form-md-line-input "><?php
                                                                            $filterfee = "select sum(amount) as recievedfee from studentfee where stud_id='" . $rowfilter['stud_id'] . "' and studentcourseId='" . $rowfilter['studentcourseId'] . "' ORDER by STR_TO_DATE(payDate,'%d-%m-%Y') DESC";
                                                                            $rowFee = mysqli_fetch_array(mysqli_query($dbconn, $filterfee));
                                                                            echo $rowFee['recievedfee'];
                                                                            $walkin[2] += $rowFee['recievedfee'];
                                                                            ?>
                                </div>
                            </td>
                            <td>
                                <div class="form-group form-md-line-input "><?php
                                                                            $filterpayDate = "select payDate,comments from studentfee where stud_id='" . $rowfilter['stud_id'] . "' and studentcourseId='" . $rowfilter['studentcourseId'] . "' ORDER by STR_TO_DATE(payDate,'%d-%m-%Y') DESC limit 1";
                                                                            $rowPay = mysqli_fetch_array(mysqli_query($dbconn, $filterpayDate));
                                                                            echo $rowPay['payDate'];
                                                                            ?>
                                </div>
                            </td>
                            <td>
                                <div class="form-group form-md-line-input "><?php
                                                                            $currentDate = date('Y-m-d');
                                                                            $LastPay = date('Y-m-d', strtotime($rowPay['payDate']));
                                                                            $date1 = date_create($currentDate);
                                                                            $date2 = date_create($LastPay);
                                                                            $diff = date_diff($date2, $date1);
                                                                            echo $diff->format("%R%a days");
                                                                            ?>
                                </div>
                            </td>
                            <td>
                                <div class="form-group form-md-line-input "><?php
                                                                            $balanceAmount = $rowfilter['offeredfee'] - $rowFee['recievedfee'];
                                                                            echo $balanceAmount;
                                                                            $walkin[3] += $balanceAmount;
                                                                            ?>
                                </div>
                            </td>
                            <td>
                                <div class="form-group form-md-line-input "><?php
                                                                            if ($rowfilter['iStudentStatus'] == 0) {
                                                                                echo "Pending";
                                                                            } else {
                                                                                $filterStatus = mysqli_fetch_array(mysqli_query($dbconn, "Select * from studentstatus where studstatusid=" . $rowfilter['iStudentStatus'] . " and isDelete=0 and istatus=1"));
                                                                                echo $filterStatus['studentStatusName'];
                                                                            }
                                                                            ?>
                                </div>
                            </td>
                            <td>
                                <div class="form-group form-md-line-input "><?php
                                                                            echo $rowPay['comments'];
                                                                            ?>
                                </div>
                            </td>
                            <!--                       <td>
                                <div class="form-group form-md-line-input "><?php
                                                                            //                                echo $rowfilter['comments'];
                                                                            ?> 
                                </div>
                            </td>-->



                        <?php
                    }
                        ?>

                        </tr>
                </tbody>
                <td colspan="1"><b>Total</b></td>
                <td colspan="1">--</td>
                <td colspan="1">--</td>
                <td colspan="1">--</td>
                <td colspan="1">--</td>
                <td colspan="1">--</td>
                <td colspan="1">--</td>
                <td colspan="1">--</td>
                <td colspan="1">--</td>
                <td colspan="1">--</td>
                <td colspan="1"><?php echo $walkin[0]; ?></td>
                <td colspan="1"><?php echo $walkin[1]; ?></td>
                <td colspan="1"><?php echo $walkin[2]; ?></td>
                <td colspan="1">--</td>
                <td colspan="1">--</td>
                <td colspan="1"><?php echo $walkin[3]; ?></td>
                <td colspan="1">--</td>
                <td colspan="1">--</td>
                <!--                <td colspan="1">--</td>-->
            </table>
        </div>
        <script src="<?php echo $web_url; ?>Supervisor/assets/global/plugins/datatables/datatables.js" type="text/javascript"></script>
        <script src="<?php echo $web_url; ?>Supervisor/assets/global/plugins/datatables/table-datatables-responsive.js" type="text/javascript"></script>
        <script>
            $(document).ready(function() {
                $('#tableC').DataTable({});
            });
        </script>
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