<?php
error_reporting(E_ALL);
require_once('../common.php');
include('IsLogin.php');
$connect = new connect();
include ('User_Paging.php');



if ($_POST['action'] == 'ListUser') {
//    $where = "where 1=1";
    $where = "";
    $whereCoure = " 1=1 ";
    $whereEmpId = " and studentadmission.branchId = '" . $_SESSION['branchid'] . "'";
    if ($_REQUEST['FormDate'] != NULL && isset($_REQUEST['FormDate']))
        $where.=" and STR_TO_DATE(studentcourse.EnrollmentDate,'%d-%m-%Y')>= STR_TO_DATE('" . $_REQUEST['FormDate'] . "','%d-%m-%Y')";

    if ($_REQUEST['ToDate'] != NULL && isset($_REQUEST['ToDate']))
        $where.=" and DATE_FORMAT(STR_TO_DATE(studentcourse.EnrollmentDate, '%d-%m-%Y'), '%Y-%m-%d')<=STR_TO_DATE('" . $_REQUEST['ToDate'] . "','%d-%m-%Y')";

    if ($_REQUEST['studentcourseId'] != NULL && isset($_REQUEST['studentcourseId'])){
        $where .= " and studentcourse.courseId in (" . implode (",", $_REQUEST['studentcourseId'] ). ") ";
        $whereCoure .= " and studentcourse.courseId in (" . implode (",", $_REQUEST['studentcourseId'] ). ") ";
    }
    
    if($_REQUEST['studentStatus'] != NULL && isset($_REQUEST['studentStatus']))
        $where .= " and studentadmission.iStudentStatus in (".implode (',',$_REQUEST['studentStatus']).")";
    else
        $where .= " and studentadmission.iStudentStatus in (select studentstatus.studstatusid from studentstatus where isDelete=0)";
    
    //$filterstr = "SELECT studentadmission.*,studentcourse.* from studentadmission,studentcourse where studentcourse.stud_id=studentadmission.stud_id " . $where . $whereEmpId . " and studentcourse.istatus=1 and studentcourse.courseId in (SELECT courseId from course) GROUP BY studentcourse.stud_id,studentcourse.courseId ORDER BY STR_TO_DATE(studentcourse.EnrollmentDate,'%d-%m-%Y') asc";
    $filterstr = "SELECT studentcourse.courseId,(Select course.courseName from course where course.courseId=studentcourse.courseId) as courseName,sum(fee) as 'totalfee',sum(offeredfee) as 'offeredfee',count(studentadmission.stud_id) as 'totalStudent',studentcourseId from studentadmission,studentcourse where studentcourse.stud_id=studentadmission.stud_id " . $where . $whereEmpId . " and studentcourse.istatus=1 and studentcourse.courseId in (SELECT courseId from course) GROUP BY studentcourse.courseId ORDER BY STR_TO_DATE(studentcourse.EnrollmentDate,'%d-%m-%Y') asc";
    $countstr = "SELECT count(DISTINCT courseId) as TotalRow from studentcourse where  " . $whereCoure . "  and istatus=1";

    $resrowcount = mysqli_query($dbconn,$countstr);
    $resrowc = mysqli_fetch_array($resrowcount);
    $totalrecord = $resrowc['TotalRow'];
    $per_page = $cateperpaging;
    $total_pages = ceil($totalrecord / $per_page);
    $page = $_REQUEST['Page'] - 1;
    $startpage = $page * $per_page;
    $show_page = $page + 1;



    $filterstr = $filterstr . " LIMIT $startpage, $per_page";
// echo $filterstr;


    $resultfilter = mysqli_query($dbconn,$filterstr);
    if (mysqli_num_rows($resultfilter) > 0) {
        $i = 0;
        $get_count = $resrowc['TotalRow'];
        ?>  


        <link href="<?php echo $web_url; ?>Employee/assets/global/plugins/datatables/datatables.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo $web_url; ?>Employee/assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" />
        <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script> 
        <div class="table-responsive">
            <table class="table table-bordered table-hover center table-responsive dt-responsive" width="100%" id="tableC">
                <thead class="tbg">
                    <tr>
                        <th class="all">Sr.No</th>
                        <th class="desktop">Course Name</th>
                        <th class="desktop">Actual Fee</th>
                        <th class="desktop">Offered Fee</th>
                        <th class="desktop">Till Date Payment Receive</th>
                        <!--<th class="desktop">Last Date Of Receipt</th>-->
                        <!--<th class="desktop">Balance Amount</th>-->
                        <th class="desktop">Student Count</th>
                        <!--<th class="desktop">Remark</th>-->
        <!--                    <th class="desktop">Remark - 2</th>-->
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($rowfilter = mysqli_fetch_array($resultfilter)) {
                        $i++;
                        ?>
                        <tr>
                            <td>
                                <div class="form-group form-md-line-input "><?php echo $i; ?> 
                                </div>
                            </td>
                            <td>
                                <div class="form-group form-md-line-input "><?php
                                    // $filterCourse = mysqli_query($dbconn,"Select * from course where courseId in (" . $rowfilter['courseId'] . ") order by courseId DESC");
                                    // $courseName = '';
                                    // while ($rowcourse = mysqli_fetch_array($filterCourse)) {
                                    //     $courseName = $rowcourse['courseName'] . "," . $courseName;
                                    // }
                                    // echo $courseName = rtrim($courseName, ',');
                                    echo $rowfilter['courseName']
                                    ?> 
                                </div>
                            </td>
                            <td>
                                <div class="form-group form-md-line-input "><?php
                                    echo $rowfilter['totalfee'];
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
                                    $filterfee = "select sum(amount) as recievedfee from studentfee where studentcourseId='" . $rowfilter['studentcourseId'] . "' ORDER by STR_TO_DATE(payDate,'%d-%m-%Y') DESC";
                                    $rowFee = mysqli_fetch_array(mysqli_query($dbconn,$filterfee));
                                    echo $rowFee['recievedfee'];
                                    // $walkin[2]+=$rowFee['recievedfee'];
                                    ?> 
                                </div>
                            </td>
                            <!-- <td>
                                <div class="form-group form-md-line-input "><?php
                                    $balanceAmount = $rowfilter['offeredfee'] - $rowFee['recievedfee'];
                                    echo $balanceAmount;
                                    // $walkin[3]+=$balanceAmount;
                                    ?> 
                                </div>
                            </td> -->
                            
                            <td>
                                <div class="form-group form-md-line-input "><?php
                                    echo $rowfilter['totalStudent'];
                                    ?> 
                                </div>
                            </td>
                            <?php
                        }
                        ?>

                    </tr>
                </tbody>
                
            </table>
        </div>
        <script src="<?php echo $web_url; ?>Employee/assets/global/plugins/datatables/datatables.js" type="text/javascript"></script>
        <script src="<?php echo $web_url; ?>Employee/assets/global/plugins/datatables/table-datatables-responsive.js" type="text/javascript"></script>
        <script>
            $(document).ready(function () {
                $('#tableC').DataTable({
                });
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