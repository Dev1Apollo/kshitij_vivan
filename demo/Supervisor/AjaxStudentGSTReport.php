<?php
error_reporting(0);
require_once('../common.php');
include('IsLogin.php');
$connect = new connect();
include('User_Paging.php');

if ($_POST['action'] == 'ListUser') {
    if ($_REQUEST['FormDate'] != NULL && isset($_REQUEST['FormDate']))
        $where .= " and STR_TO_DATE(depositDate,'%d-%m-%Y')>= STR_TO_DATE('$_REQUEST[FormDate]','%d-%m-%Y')";
    if ($_REQUEST['ToDate'] != NULL && isset($_REQUEST['ToDate']))
        $where .= " and STR_TO_DATE(depositDate,'%d-%m-%Y')<=STR_TO_DATE('$_REQUEST[ToDate]','%d-%m-%Y')";
    $whereid = "where 1=1";
    //$whereid .= " and studentcourse.branchId = '" . $_SESSION['branchid'] . "'";
    if ($_SESSION['EmployeeType'] == 'Supervisor') {
        if ($_POST['branchid'] != NULL && isset($_POST['branchid']))
            $whereid .= " and studentcourse.branchId='" . $_POST['branchid'] . "'";
    } else {
        $whereid .= " and studentcourse.branchId=" . $_SESSION['branchid'] . "'";
    }

    $filterstr = "SELECT studentcourse.`stud_id`,studentcourse.`studentcourseId`,studentfee.chequeNo,studentfee.paymentMode,studentfee.bankName,studentcourse.courseId,studentfee.depositDate,studentfee.`payDate` , studentfee.`iGstRef` ,
SUM((SELECT studfee.amount FROM studentfee studfee where studentfee.studentfeeid = studfee.studentfeeid " . $where . " )) as netamount, 
SUM((SELECT studfee.decGst FROM studentfee studfee where studentfee.studentfeeid = studfee.studentfeeid " . $where . " )) as GstAmount, 
SUM((SELECT studfee.texFreeAmt FROM studentfee studfee where studentfee.studentfeeid = studfee.studentfeeid " . $where . " )) as taxFreeAmount 
FROM studentfee LEFT join studentcourse on studentfee.studentcourseId=studentcourse.studentcourseId JOIN bank on studentfee.toBank=bank.bankId
join studentadmission on studentadmission.stud_id=studentcourse.stud_id " . $whereid . " " . $where . " and studentfee.amount!=0 and studentcourse.istatus=1 and bank.isGst like 'YES' GROUP by studentfee.studentfeeId ORDER BY STR_TO_DATE(studentfee.depositDate,'%d-%m-%Y') ASC,iGstRef ASC";

    $countstr = "SELECT count(*) as TotalRow FROM studentfee LEFT join studentcourse on studentfee.studentcourseId=studentcourse.studentcourseId JOIN bank on studentfee.toBank=bank.bankId
        join studentadmission on studentadmission.stud_id=studentcourse.stud_id   " . $whereid . "  "
        . " " . $where . " and bank.isGst like 'YES' and studentcourse.istatus=1 and studentfee.amount!=0 ";

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
        <link href="<?php echo $web_url; ?>Supervisor/assets/global/plugins/datatables/datatables.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo $web_url; ?>Supervisor/assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" />
        <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>

        <table class="table table-bordered table-hover center table-responsive dt-responsive" width="100%" id="tableC">
            <thead class="tbg">
                <tr>
                    <th class="all">Sr.No</th>
                    <th class="desktop">Date</th>
                    <th class="all">Receipt No</th>
                    <th class="desktop">Name</th>
                    <th class="desktop">Fee (Without Tax)</th>
                    <th class="desktop">CGST</th>
                    <th class="desktop">SGST</th>
                    <th class="desktop"> Fee (With Tax)</th>
                    <th class="desktop">Payment Mode</th>
                    <th class="desktop">Bank Name</th>
                    <th class="desktop">Cheque Number</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $walkin = array();
                while ($rowfilter = mysqli_fetch_array($resultfilter)) {
                    $walkin[0] += $rowfilter['taxFreeAmount'];
                    $walkin[1] += $rowfilter['GstAmount'] / 2;
                    $walkin[2] += $rowfilter['GstAmount'] / 2;
                    $walkin[3] += $rowfilter['netamount'];
                    $i++;
                    $serial++;
                ?>
                    <tr>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $serial; ?>
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['depositDate']; ?>
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['iGstRef']; ?>
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php
                                                                        $getName = mysqli_query($dbconn, "SELECT * FROM studentadmission where stud_id ='" . $rowfilter['stud_id'] . "' ");
                                                                        while ($filtername = mysqli_fetch_array($getName)) {
                                                                            echo $filtername['title'] . ' ' . $filtername['firstName'] . ' ' . $filtername['middleName'] . ' ' . $filtername['surName'];
                                                                        }
                                                                        ?>
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['taxFreeAmount']; ?>
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['GstAmount'] / 2; ?>
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['GstAmount'] / 2; ?>
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['netamount']; ?>
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
                                                                        if (isset($rowfilter['bankName']) && $rowfilter['bankName'] != '') {
                                                                            $filterBank = mysqli_fetch_array(mysqli_query($dbconn, "SELECT bankName FROM `bankmaster` where isDelete='0' and istatus='1' and bankMasterId='" . $rowfilter['bankName'] . "' "));
                                                                            echo $filterBank['bankName'];
                                                                        } else {
                                                                            echo 'NA';
                                                                        }
                                                                        ?>
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php
                                                                        if (isset($rowfilter['chequeNo']) && $rowfilter['chequeNo'] != '') {
                                                                            echo $rowfilter['chequeNo'];
                                                                        } else {
                                                                            echo 'NA';
                                                                        }
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
                <td colspan="1"><?php echo $walkin[0]; ?></td>
                <td colspan="1"><?php echo $walkin[1]; ?></td>
                <td colspan="1"><?php echo $walkin[2]; ?></td>
                <td colspan="1"><?php echo $walkin[3]; ?></td>
                <td colspan="1">--</td>
                <td colspan="1">--</td>
                <td colspan="1">--</td>
            </tr>
        </table>
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
if ($totalrecord > $per_page) {
    ?>
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