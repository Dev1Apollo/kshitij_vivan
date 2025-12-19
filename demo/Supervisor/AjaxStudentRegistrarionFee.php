<?php
ob_start();
error_reporting(0);

require_once('../common.php');
include('IsLogin.php');
$connect = new connect();
include('User_Paging.php');


if ($_POST['action'] == 'ListUser') {

    $where = " where 1=1";
    if ($_REQUEST['stud_id'] != NULL && isset($_REQUEST['stud_id']))
        $where .= " and  studentfee.stud_id =" . $_REQUEST[stud_id];
    $filterstr = "SELECT * FROM `studentfee`   " . $where . " and studentcourseId = '0' order by studentfeeid desc";
    $countstr = "SELECT count(*) as TotalRow FROM `studentfee`  " . $where . " and studentcourseId = '0' order by STR_TO_DATE(studentfee.payDate,'%d-%m-%Y') desc";

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
        <div class="table-responsive">
            <table class="table table-bordered table-hover center table-responsive dt-responsive" width="100%" id="tableC">
                <thead class="tbg">
                    <tr>
                        <th class="all">Sr.No</th>
                        <th class="desktop">Fee Type</th>
                        <th class="desktop">Pay Date</th>
                        <th class="desktop">Payment Mode</th>
                        <th class="none">Deposit</th>
                        <th class="none">Bank Detail</th>
                        <th class="desktop">Gross Amount</th>
                        <th class="desktop">GST</th>
                        <th class="desktop">Net Amount</th>
                        <th class="desktop">Comment</th>
                        <th class="desktop">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($rowfilter = mysqli_fetch_array($resultfilter)) {
                        $serial++;
                    ?>
                        <tr>
                            <td>
                                <div class="form-group form-md-line-input "><?php echo $serial; ?>
                                </div>
                            </td>
                            <td>
                                <div class="form-group form-md-line-input "><?php
                                                                            if ($rowfilter['feetype'] == 1) {
                                                                                $feeType = "Registration Fee";
                                                                            } else if ($rowfilter['feetype'] == 2) {
                                                                                $feeType = "Joining Fee";
                                                                            } else {
                                                                                $feeType = "EMI Fee";
                                                                            }
                                                                            echo $feeType;
                                                                            ?>
                                </div>
                            </td>
                            <td>
                                <div class="form-group form-md-line-input "><?php echo $rowfilter['payDate']; ?>
                                </div>
                            </td>
                            <td>
                                <div class="form-group form-md-line-input "><?php
                                                                            $filterMode = mysqli_fetch_array(mysqli_query($dbconn, "SELECT paymentName FROM `paymentmode` where isDelete='0' and istatus='1' and paymentId='" . $rowfilter['paymentMode'] . "' "));
                                                                            echo $filterMode['paymentName'];
                                                                            ?>
                                </div>
                            </td>
                            <td>
                                <div class="form-group form-md-line-input "><?php echo $rowfilter['deposit']; ?>
                                </div>
                            </td>
                            <td>
                                <div class="form-group form-md-line-input ">Bank Name:<?php
                                                                                        $filterBank = mysqli_fetch_array(mysqli_query($dbconn, "SELECT bankName FROM `bankmaster` where isDelete='0' and istatus='1' and bankMasterId='" . $rowfilter['bankName'] . "' "));
                                                                                        echo $filterBank['bankName'];
                                                                                        ?><br>
                                    Cheque No:<?php echo $rowfilter['chequeNo']; ?><br>
                                    Deposited To:<?php echo $rowfilter['toBank']; ?><br>
                                </div>
                            </td>
                            <td>
                                <div class="form-group form-md-line-input "> <?php echo $rowfilter['texFreeAmt']; ?>
                                </div>
                            </td>
                            <td>
                                <div class="form-group form-md-line-input "><?php echo $rowfilter['decGst']; ?>
                                </div>
                            </td>

                            <td>
                                <div class="form-group form-md-line-input "><?php echo $rowfilter['amount']; ?>
                                </div>
                            </td>
                            <td>
                                <div class="form-group form-md-line-input "><?php echo $rowfilter['comments']; ?>
                                </div>
                            </td>
                            <td>
                                <div class="form-group form-md-line-input">
                                    <!--                                <a  class="btn blue" href="<?php // echo $web_url;  
                                                                                                    ?>Supervisor/EditStudentfee.php?token=<?php echo $rowfilter['studentfeeid']; ?>" title="EDIT STUDENT"><i class="fa fa-edit"></i></a>
                                    -->
                                    <?php
                                    $filterType = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `studentadmission` where stud_id='" . $rowfilter['stud_id'] . "'"));
                                    if ($filterType['studentPortal_Id'] != 1 && $filterType['studentPortal_Id'] != 4) {
                                    ?>
                                        <a class="btn blue" href="<?php echo $web_url; ?>Supervisor/StudentFeePDF.php?token=<?php echo $rowfilter['studentfeeid']; ?>" target="_blank" title="View Student Fee PDF"><i class="fa fa-eye"></i></a>
                                    <?php
                                    }
                                    //                                $query = "SELECT max(studentfeeid) as studentFeeId FROM `studentfee` join studentcourse on studentcourse.studentcourseId=studentfee.studentcourseId join course on course.courseId=studentcourse.courseId  " . $where . " and feetype='Emi_Amount' order by studentfeeid desc";
                                    //                                $filterid=  mysqli_fetch_array(mysqli_query($dbconn,$query));
                                    //                                if($rowfilter['feetype'] == 'Join_Amount' || $filterid['studentFeeId'] == $rowfilter['studentfeeid'] || $i==0){ 
                                    ?>
                                    <!--                                <a  class="btn blue" onClick="javascript: return deletedata('Delete', '<?php // echo $rowfilter['studentfeeid']; 
                                                                                                                                                ?>');"   title="Delete"><i class="fa fa-trash-o iconshowFirst"></i></a>-->
                                    <?php // }  
                                    ?>
                                </div>
                            </td>
                        </tr>
                    <?php
                        $i++;
                    }
                    ?>
                </tbody>
            </table>
        </div>
    <?php } ?>
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

if ($_REQUEST['action'] == 'Delete') {
    $studentfeeid = $_REQUEST['ID'];
    $filterstud = "select * from studentfee where studentfeeid=" . $studentfeeid;
    $filterstuddetail = mysqli_fetch_array(mysqli_query($dbconn, $filterstud));
    $filteremidetail = "SELECT * FROM studentemidetail WHERE `studentfeeid`='" . $studentfeeid . "' and studentemidetail.isDelete=0 ORDER BY studemiId DESC";
    $row = mysqli_query($dbconn, $filteremidetail);
    $i = 0;
    while ($emidata = mysqli_fetch_array($row)) {
        $studemiId = $emidata['studemiId'] . "\n";
        $emiAmount = $emidata['emiAmount'];
        $actualReceivedAmount = $emidata['actualReceivedAmount'];
        $diffAmt = 0;
        $dueAmt = 0;
        $totalAmt = $filterstuddetail['amount'];
        $diffAmt = $totalAmt - $actualReceivedAmount;

        if ($diffAmt == 0) {
            $data = array(
                "actualReceivedAmount" => 0,
                "isPaid" => '0',
                "emiReceivedDate" => "",
                "comments" => "",
                "studentfeeid" => $studentfeeid
            );
            $where = ' where  studemiId =' . $studemiId;
            $dealer_emi = $connect->updaterecord($dbconn, 'studentemidetail', $data, $where);
            $dealer_emi;
        }
        if ($diffAmt != 0) {
            $diffAmt = $totalAmt - $actualReceivedAmount;
            $dataEmi = array(
                "actualReceivedAmount" => 0,
                "isPaid" => '0',
                "emiReceivedDate" => "",
                "comments" => "",
                "studentfeeid" => $studentfeeid
            );
            $where = ' where  studemiId =' . $studemiId;
            $dealer_emi = $connect->updaterecord($dbconn, 'studentemidetail', $dataEmi, $where);
            $totalAmt = $diffAmt;
            $dealer_emi;
        }
        $i++;
    }
    $dealer_res = mysqli_query($dbconn, "delete from studentfee where studentfeeid='" . $studentfeeid . "'");
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