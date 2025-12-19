<?php
error_reporting(0);
require_once('../common.php');
include('IsLogin.php');
$connect = new connect();
include('User_Paging.php');


if ($_POST['action'] == 'ListUser') {
    //    $where = "where 1=1";
    //$whereEmpId = " and studentadmission.branchId = '" . $_SESSION['branchid'] . "'";
    $whereEmpId = "";
    if ($_SESSION['EmployeeType'] == 'Supervisor') {
        if ($_POST['branchid'] != NULL && isset($_POST['branchid']))
            $whereEmpId .= " and studentadmission.branchId='" . $_POST['branchid'] . "'";
    } else {
        $whereEmpId .= " and studentadmission.branchId=" . $_SESSION['branchid'] . "'";
    }
    if ($_REQUEST['FormDate'] != NULL && isset($_REQUEST['FormDate']))
        $where .= " and STR_TO_DATE(studentfee.payDate,'%d-%m-%Y')>= STR_TO_DATE('" . $_REQUEST['FormDate'] . "','%d-%m-%Y')";

    if ($_REQUEST['ToDate'] != NULL && isset($_REQUEST['ToDate']))
        $where .= " and DATE_FORMAT(STR_TO_DATE(studentfee.payDate, '%d-%m-%Y'), '%Y-%m-%d')<=STR_TO_DATE('" . $_REQUEST['ToDate'] . "','%d-%m-%Y')";

    if ($_REQUEST['StudentName'] != NULL && isset($_REQUEST['StudentName']))
        $where .= " and (studentadmission.firstName like '%" . $_REQUEST['StudentName'] . "%' OR  studentadmission.middleName like '%" . $_REQUEST['StudentName'] . "%' OR studentadmission.surName like '%" . $_REQUEST['StudentName'] . "%' )";

    if ($_REQUEST['studentPortal_Id'] != NULL && isset($_REQUEST['studentPortal_Id']))
        $where .= " and studentadmission.studentPortal_Id in (" . implode(',', $_REQUEST['studentPortal_Id']) . ")";


    $filterstr = "SELECT studentfee.*,studentadmission.* FROM `studentfee`,studentadmission WHERE studentadmission.stud_id=studentfee.stud_id and studentfee.amount!=0 and studentfee.feetype not in (5) and studentfee.isCancel=0 " . $where . $whereEmpId . " ORDER BY STR_TO_DATE(studentfee.payDate,'%d-%m-%Y') ASC";

    $countstr = "select count(*) as TotalRow, studentfee.*,studentadmission.* FROM `studentfee`,studentadmission WHERE studentadmission.stud_id=studentfee.stud_id and studentfee.amount!=0 and studentfee.feetype not in (5) and studentfee.isCancel=0 " . $where . $whereEmpId . "";

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
        <div class="row m-search-box">
            <div class="col-md-12">
                <div class="row">
                    <h4 class="col-md-6"><span class="bold" style="color: #e31e24">Count:</span>&nbsp;&nbsp;<?php echo $totalrecord; ?></h4>

                </div>

            </div>
        </div>

        <link href="<?php echo $web_url; ?>Supervisor/assets/global/plugins/datatables/datatables.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo $web_url; ?>Supervisor/assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" />
        <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
        <div class="table-responsive">
            <table class="table table-bordered table-hover center table-responsive dt-responsive" width="100%" id="tableC">
                <thead class="tbg">
                    <tr>
                        <th class="all">Sr.No</th>
                        <th class="desktop">Lead Unique Id</th>
                        <th class="desktop">Enrollment Id</th>
                        <th class="desktop">Booking Id</th>
                        <th class="desktop">Name of Student</th>
                        <th class="desktop">Payment Mode</th>
                        <th class="desktop">Bank Name</th>
                        <th class="desktop">Cheque No.</th>
                        <th class="desktop">Payment Date</th>
                        <th class="desktop">Gross Amount</th>
                        <th class="desktop">CGST</th>
                        <th class="desktop">SGST</th>
                        <th class="desktop">Net Amount</th>

                    </tr>
                </thead>
                <tbody>
                    <?php
                    $addData = array();
                    while ($rowfilter = mysqli_fetch_array($resultfilter)) {

                        $addData[0] += $rowfilter['texFreeAmt'];
                        $addData[1] += $rowfilter['decGst'] / 2;
                        $addData[2] += $rowfilter['decGst'] / 2;
                        $addData[3] += $rowfilter['amount'];
                        $i++;
                        $serial++;
                    ?>
                        <tr>
                            <td>
                                <div class="form-group form-md-line-input "><?php echo $i; ?>
                                </div>
                            </td>
                            <td>
                                <div class="form-group form-md-line-input "><?php echo $rowfilter['leaduniqueid']; ?>
                                </div>
                            </td>
                            <td>
                                <div class="form-group form-md-line-input "><?php echo $rowfilter['studentEnrollment']; ?>
                                </div>
                            </td>
                            <td>
                                <div class="form-group form-md-line-input "><?php
                                                                            $filterCourse = mysqli_fetch_array(mysqli_query($dbconn, "SELECT bookingId FROM `studentcourse` where studentcourseId='" . $rowfilter['studentcourseId'] . "' and studentcourse.istatus=1 "));
                                                                            echo $filterCourse['bookingId']; ?>
                                </div>
                            </td>
                            <td>
                                <div class="form-group form-md-line-input "><a href="StudentFeePayHistory.php?token=<?php echo $rowfilter['stud_id']; ?>&studentcourseId=<?php echo $rowfilter['studentcourseId']; ?>"><?php
                                                                                                                                                                                                                        echo $rowfilter['title'] . ' ' . $rowfilter['firstName'] . ' ' . $rowfilter['middleName'] . ' ' . $rowfilter['surName'];
                                                                                                                                                                                                                        ?> </a>
                                </div>
                            </td>
                            <td>
                                <div class="form-group form-md-line-input "><?php
                                                                            $filterMode = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `paymentmode` where paymentId='" . $rowfilter['paymentMode'] . "' and isDelete='0' and iStatus='1'"));
                                                                            echo $filterMode['paymentName'];
                                                                            ?>
                                </div>
                            </td>
                            <td>
                                <div class="form-group form-md-line-input "><?php
                                                                            $filterBank = mysqli_fetch_array(mysqli_query($dbconn, "SELECT bankName FROM `bankmaster` where isDelete='0' and istatus='1' and bankMasterId='" . $rowfilter['bankName'] . "' "));
                                                                            echo $filterBank['bankName'];
                                                                            ?>
                                </div>
                            </td>

                            <td>
                                <div class="form-group form-md-line-input "><?php
                                                                            echo $rowfilter['chequeNo'];
                                                                            ?>
                                </div>
                            </td>

                            <td>
                                <div class="form-group form-md-line-input ">
                                    <?php echo $rowfilter['payDate']; ?>
                                </div>
                            </td>
                            <td>
                                <div class="form-group form-md-line-input "><?php
                                                                            echo $rowfilter['texFreeAmt'];
                                                                            ?>
                                </div>
                            </td>
                            <td>
                                <div class="form-group form-md-line-input "><?php
                                                                            echo $rowfilter['decGst'] / 2;
                                                                            ?>
                                </div>
                            </td>
                            <td>
                                <div class="form-group form-md-line-input "><?php
                                                                            echo $rowfilter['decGst'] / 2;
                                                                            ?>
                                </div>
                            </td>
                            <td>
                                <div class="form-group form-md-line-input "><?php
                                                                            echo $rowfilter['amount'];
                                                                            ?>
                                </div>
                            </td>
                        <?php
                    }
                        ?>

                        </tr>
                </tbody>
                <tr style="background-color:#f3f3f3">
                    <td colspan="1"><b>Total</b></td>
                    <td colspan="1">--</td>
                    <td colspan="1">--</td>
                    <td colspan="1">--</td>
                    <td colspan="1">--</td>
                    <td colspan="1">--</td>
                    <td colspan="1">--</td>
                    <td colspan="1">--</td>
                    <td colspan="1">--</td>
                    <td colspan="1"><?php echo $addData[0]; ?></td>
                    <td colspan="1"><?php echo $addData[1]; ?></td>
                    <td colspan="1"><?php echo $addData[2]; ?></td>
                    <td colspan="1"><?php echo $addData[3]; ?></td>
                </tr>
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