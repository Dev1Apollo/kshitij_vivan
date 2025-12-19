<?php
error_reporting(0);
require_once('../common.php');
include('IsLogin.php');
$connect = new connect();
include ('User_Paging.php');

if ($_POST['action'] == 'ListUser') {
    $where = "where 1=1";

    $filterstr = "SELECT * FROM `studentcourse`  " . $where . " AND studentcourse.istatus=1 and studentcourse.stud_id=" . $_REQUEST['stud_id'];
    $countstr = "SELECT count(*) as TotalRow FROM studentcourse " . $where . " AND studentcourse.istatus=1 and studentcourse.stud_id=" . $_REQUEST['stud_id'];

    $resrowcount = mysqli_query($dbconn,$countstr);
    $resrowc = mysqli_fetch_array($resrowcount);
    $totalrecord = $resrowc['TotalRow'];
    $per_page = $cateperpaging;
    $total_pages = ceil($totalrecord / $per_page);
    $page = $_REQUEST['Page'] - 1;
    $startpage = $page * $per_page;
    $show_page = $page + 1;


    $filterstr = $filterstr . " LIMIT $startpage, $per_page";
    $resultfilter = mysqli_query($dbconn,$filterstr);

    if (mysqli_num_rows($resultfilter) > 0) {
        $i = 0;
        $serial = 0;
        $serial = ($page * $per_page);
        ?>  
        <link href="<?php echo $web_url; ?>Employee/assets/global/plugins/datatables/datatables.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo $web_url; ?>Employee/assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" />
        <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script> 

        <table class="table table-bordered table-hover center table-responsive dt-responsive" width="100%" id="tableC">
            <thead class="tbg">
                <tr>
                    <th class="all">Sr.No</th>
                    <th class="all">Book Id</th>
                    <th class="desktop">Course Name</th>
                    <th class="desktop">Actual Fee </th>
                    <th class="desktop">Received Fee</th>
                    <th class="desktop">Date Of Joining</th>
                    <th class="desktop">Emi Start Date</th>
                    <th class="desktop">Emi Type</th>
                    <th class="desktop">Emi Amount</th>
                    <th class="desktop">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($rowfilter = mysqli_fetch_array($resultfilter)) {
                    $i++;
                    $serial++;
                    ?>
                    <tr>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $serial; ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['bookingId']; ?> 
                            </div>
                        </td>
                        <td>
                            <?php
                            $query = mysqli_query($dbconn,"SELECT * FROM `course` where courseId in (" . $rowfilter['courseId'] . ")");
                            ?>
                            <div class="form-group form-md-line-input "><?php
                                $coureName = '';
                                while ($courseName = mysqli_fetch_array($query)) {
                                    $coureName = $courseName['courseName'] . ',' . $coureName;
                                }
                                echo $coureName = rtrim($coureName, ',');
                                ?> 
                            </div>
                        </td>   
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['fee']; ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php
                                $filterFee = mysqli_fetch_array(mysqli_query($dbconn, "select sum(amount) as FeePaid from studentfee where feeType in (1,2) and stud_id='" . $_REQUEST['stud_id'] . "' and studentcourseId='" . $rowfilter['studentcourseId'] . "'"));
                                echo $filterFee['FeePaid'];
                                ?>
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['dateOfJoining']; ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['emiStartDate']; ?> 
                            </div>
                        </td>
                        <td>
                            <?php
                            $query1 = mysqli_fetch_array(mysqli_query($dbconn,"SELECT * FROM `emitype` where emiId =" . $rowfilter['emiType']));
                            ?>
                            <div class="form-group form-md-line-input "><?php echo $query1['emiTypeName']; ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['emiAmount']; ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input">
                                <a  class="btn blue" href="<?php echo $web_url; ?>Employee/StudentFeePayHistory.php?token=<?php echo $rowfilter['stud_id']; ?>&studentcourseId=<?php echo $rowfilter['studentcourseId']; ?>"  title="Fee History"><i class="fa fa-database"></i><strong> Fees History</strong></a>
                                <!--<a  class="btn blue"  href="<?php echo $web_url; ?>Employee/StudentEnrollmentFees.php?token=<?php echo $rowfilter['stud_id']; ?>&studentcourseId=<?php echo $rowfilter['studentcourseId']; ?>" title="STUDENT FEES"><i class="fa fa-rupee"></i></a>-->
                                <a  class="btn blue" href="<?php echo $web_url; ?>Employee/StudentFeesHistory.php?token=<?php echo $rowfilter['stud_id']; ?>&studentcourseId=<?php echo $rowfilter['studentcourseId']; ?>"   title="Emi Chart"><i class="fa fa-history iconshowFirst"></i><strong> Emi Chart </strong></a>
                                <!--<a  class="btn blue" onClick="javascript: return deletedata('Delete', '<?php echo $rowfilter['stud_id']; ?>','<?php echo $rowfilter['studentcourseId']; ?>');"   title="Delete"><i class="fa fa-history iconshowFirst"></i></a>-->
                            </div>
                        </td>
                    </tr>
        <?php } ?>
            </tbody>
        </table>
    <?php } ?>
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

if ($_REQUEST['action'] == 'Delete') {
    $stud_id = $_REQUEST['ID'];
    $studentcourseId = $_REQUEST['studentcourseId'];

    $dealer_res1 = mysqli_query($dbconn,"delete from studentemidetail where stud_id='" . $stud_id . "' and studentemidetail.isDelete=0 and studentcourseId='" . $studentcourseId . "'");
    $dealer_res = mysqli_query($dbconn,"delete from studentfee where stud_id='" . $stud_id . "' and studentcourseId='" . $studentcourseId . "'");

    $dealer_res2 = mysqli_query($dbconn,"delete from studentcoursedetail where stud_id='" . $stud_id . "' and studentcourseId='" . $studentcourseId . "'");
    $dealer_res = mysqli_query($dbconn,"delete from studentcourse where stud_id='" . $stud_id . "' and studentcourseId='" . $studentcourseId . "'");
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