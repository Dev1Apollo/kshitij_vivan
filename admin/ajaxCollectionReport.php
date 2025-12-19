<?php
error_reporting(0);
require_once('../common.php');
include('IsLogin.php');
$connect = new connect();
include ('User_Paging.php');


if ($_POST['action'] == 'ListUser') {
   $where = "";

    if ($_REQUEST['FormDate'] != NULL && isset($_REQUEST['FormDate'])) {
        $where.=" and STR_TO_DATE(studentfee.payDate,'%d-%m-%Y')>= STR_TO_DATE('" . $_REQUEST['FormDate'] . "','%d-%m-%Y')";
    }
    if ($_REQUEST['ToDate'] != NULL && isset($_REQUEST['ToDate'])) {
        $where.=" and DATE_FORMAT(STR_TO_DATE(studentfee.payDate, '%d-%m-%Y'), '%Y-%m-%d')<=STR_TO_DATE('" . $_REQUEST['ToDate'] . "','%d-%m-%Y')";
    }
    if ($_REQUEST['studentPortal_Id'] != NULL && isset($_REQUEST['studentPortal_Id'])) {
        $where .= " and studentadmission.studentPortal_Id in (" . implode(',', $_REQUEST['studentPortal_Id']) . ") ";
    }
    if ($_REQUEST['branch'] != NULL && isset($_REQUEST['branch']))
        $where.= " and studentadmission.stud_id in (select stud_id from studentadmission where branchId=" . $_REQUEST['branch'] . " and istatus=1 and isDelete=0 )";

    $filterstr = "select studentfee.*, studentadmission.* from studentadmission,studentfee where studentfee.stud_id=studentadmission.stud_id and studentfee.feetype not in (5)  and studentfee.amount!=0 " . $where . " ORDER BY STR_TO_DATE(studentfee.payDate,'%d-%m-%Y') ASC ";

    $countstr = "select count(*) as TotalRow, studentfee.*, studentadmission.* from studentadmission,studentfee where studentfee.stud_id=studentadmission.stud_id and studentfee.amount!=0 and studentfee.feetype not in (5)  " . $where . " ";

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
        $get_count = $resrowc['TotalRow'];
          $serial = 0;
        $serial = ($page * $per_page);
        ?>  
        <div class="row m-search-box">  
            <div class="col-md-12">
                <div class="row">
                    <h4 class="col-md-6"><span class="bold" style="color: #e31e24">Count:</span>&nbsp;&nbsp;<?php echo $totalrecord; ?></h4>
                </div>
            </div>
        </div>

        <link href="<?php echo $web_url; ?>Employee/assets/global/plugins/datatables/datatables.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo $web_url; ?>Employee/assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" />
        <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script> 
        <div class="table-responsive">
            <table class="table table-bordered table-hover center table-responsive dt-responsive" width="100%" id="tableC">
                <thead class="tbg">
                    <tr>
                        <th class="all">Sr.No</th>
                        <th class="desktop">Ref Date</th>
                        <th class="desktop">Type</th>
                        <th class="desktop">Ref No</th>
<!--                        <th class="desktop">Receipt No</th>-->
                        <!--<th class="desktop">Course</th>-->
                        <th class="desktop">Enrollment No</th>
                        <th class="desktop">Name Of Student</th> 
                        <th class="desktop">Total Receipt Amount</th>
                        <th class="desktop">Tax Amount</th>
                        <th class="desktop">Without Tax Amount</th>
                        <th class="desktop">Payment Mode</th>
                        <th class="desktop">Bank Name</th>
                        <th class="desktop">Cheque Number</th>
                        <th class="desktop">Deposit Date</th>
                        <th class="desktop">Deposited Bank</th>
                        <th class="desktop">Deposit Amount</th>
                        <th class="desktop">Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                     $addData = array('0', '0','0','0');
                    while ($rowfilter = mysqli_fetch_array($resultfilter)) {
                        $get_inquirysource = mysqli_fetch_array(mysqli_query($dbconn, "select * from customerentry where customerEntryId = '" . $rowfilter['customerEntryId'] . "'"));
                        $get_source = mysqli_fetch_array(mysqli_query($dbconn, "select * from inquirysource where inquirySourceId = '" . $get_inquirysource['inquirySourceId'] . "'"));
                        $addData[0]+=$rowfilter['amount'];
                        $addData[1]+=$rowfilter['decGst'];
                        $addData[2]+=$rowfilter['texFreeAmt'];
                        $addData[3]+=$rowfilter['depositAmount'];
                        $i++;
                        $serial++;
                        ?>
                        <tr>
                            <td>
                                <div class="form-group form-md-line-input "><?php echo $i; ?> 
                                </div>
                            </td>
                            <td>
                                <div class="form-group form-md-line-input "><?php
                                    echo $rowfilter['payDate'];
                                    ?> 
                                </div>
                            </td>
                            <td>
                                <div class="form-group form-md-line-input ">
                                    <?php
                                    if ($rowfilter['studentPortal_Id'] == 1) {
                                        echo "Maac CG";
                                    } else if ($rowfilter['studentPortal_Id'] == 2) {
                                        echo "Kshitij Vivan";
                                    } else if ($rowfilter['studentPortal_Id'] == 3) {
                                        echo "Other";
                                    } else if ($rowfilter['studentPortal_Id'] == 4) {
                                        echo "Maac Satellite";
                                    }
                                    ?> 
                                </div>
                            </td>
                            <td>
                                <div class="form-group form-md-line-input "><?php
                                    echo $rowfilter['receiptNo'];
                                    ?> 
                                </div>
                            </td>
                            <td>
                                <div class="form-group form-md-line-input "><?php
                                    if (isset($rowfilter['studentEnrollment']) && $rowfilter['studentEnrollment'] != '') {
                                        echo $rowfilter['studentEnrollment'];
                                    } else {
                                        echo 'NA';
                                    }
                                    ?> 
                                </div>
                            </td> 
                            <td>
                                <div class="form-group form-md-line-input ">
                                    <?php echo $rowfilter['title'] . ' ' . $rowfilter['firstName'] . ' ' . $rowfilter['middleName'] . ' ' . $rowfilter['surName']; ?>
                                </div>
                            </td>
                            <td>
                                <div class="form-group form-md-line-input "><?php
                                    echo $rowfilter['amount'];
                                    ?> 
                                </div>
                            </td>
                            <td>
                                <div class="form-group form-md-line-input "><?php
                                    echo $rowfilter['decGst'];
                                    ?> 
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
                            <td>
                                <div class="form-group form-md-line-input "><?php
                                    echo $rowfilter['depositDate'];
                                    ?> 
                                </div>
                            </td>
                            <td>
                                <div class="form-group form-md-line-input "><?php
                                    $filterBankdeposit = mysqli_fetch_array(mysqli_query($dbconn, "select * from bank where bankId=" . $rowfilter['toBank'] . " and isDelete='0' and istatus='1'"));
                                    echo $filterBankdeposit['bankName'];
                                    ?> 
                                </div>
                            </td>
                            <td>
                                <div class="form-group form-md-line-input "><?php
                                    echo $rowfilter['depositAmount'];
                                    ?> 
                                </div>
                            </td>
                            <td>
                                <div class="form-group form-md-line-input "><?php
                                    echo $rowfilter['comments'];
                                    ?> 
                                </div>
                            </td>           
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
                <td colspan="1"><b>Total</b></td>
                <td colspan="1">--</td>
                <td colspan="1">--</td>
                <td colspan="1">--</td>
                <!--<td colspan="1">--</td>-->
                <td colspan="1">--</td>
                <td colspan="1">--</td>
                <td colspan="1"><?php echo $addData[0]; ?></td>
                <td colspan="1"><?php echo $addData[1]; ?></td>
                <td colspan="1"><?php echo $addData[2]; ?></td>
                <td colspan="1">--</td>
                <td colspan="1">--</td>
                <td colspan="1">--</td>
                <td colspan="1">--</td>
                <td colspan="1">--</td>
                <td colspan="1"><?php echo $addData[3]; ?></td>
                <td colspan="1">--</td>
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