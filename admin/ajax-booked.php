<?php
error_reporting(0);
require_once('../common.php');
include('IsLogin.php');
$connect = new connect();
include ('User_Paging.php');


if ($_POST['action'] == 'ListUser') {
    $where = "where 1=1";

    if ($_REQUEST['FormDate'] != NULL && isset($_REQUEST['FormDate']))
        $where.=" and STR_TO_DATE(nextFollowupModifyDate,'%d-%m-%Y')>= STR_TO_DATE('" . $_REQUEST['FormDate'] . "','%d-%m-%Y')";
    else
        $where.=" and DATE_FORMAT(STR_TO_DATE(nextFollowupModifyDate, '%d-%m-%Y'), '%Y-%m-%d')=STR_TO_DATE('" . date('d-m-Y') . "','%d-%m-%Y')";
    if ($_REQUEST['ToDate'] != NULL && isset($_REQUEST['ToDate']))
        $where.=" and DATE_FORMAT(STR_TO_DATE(nextFollowupModifyDate, '%d-%m-%Y'), '%Y-%m-%d')<=STR_TO_DATE('" . $_REQUEST['ToDate'] . "','%d-%m-%Y')";
    if ($_REQUEST['InquirySource'] != NULL && isset($_REQUEST['InquirySource']))
        $where.=" and lead.customerEntryId in (select customerentry.customerEntryId from customerentry where inquirySourceId in (" . implode(',', $_POST['InquirySource']) . "))";
    if ($_REQUEST['Employee'] != NULL && isset($_REQUEST['Employee']))
        $where.=" and employeeMasterId ='" . $_REQUEST['Employee'] . "'";
    if ($_REQUEST['branch'] != NULL && isset($_REQUEST['branch']))
        $where.= " and employeeMasterId in (select employeeMasterId from employeemaster where employeemaster.branchid=" . $_REQUEST['branch'] . " and istatus=1 and isDelete=0 )";

    $filterstr = "SELECT * FROM `lead`  " . $where . " and isNewInquiry='0'  and  statusId in ('3') order by STR_TO_DATE(nextFollowupModifyDate,'%d-%m-%Y') desc";
//    print_r($filterstr);
//    exit;
    $countstr = "SELECT count(*) as TotalRow,avg(DATEDIFF(STR_TO_DATE(nextFollowupModifyDate,'%d-%m-%Y'),STR_TO_DATE(nextFollowupModifyDate,'%d-%m-%Y'))) AS timediff,sum(booking_amount) as bookedamount FROM `lead`  " . $where . " and  isNewInquiry='0'  and statusId in ('3')  ";

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
        $get_count = $resrowc['TotalRow'];
        ?>  
        <div class="row m-search-box">  
            <div class="col-md-12">
                <div class="row">
                    <h4 class="col-md-6"><span class="bold" style="color: #e31e24">Count:</span>&nbsp;&nbsp;<?php echo $totalrecord; ?></h4>
                    <h4 class="col-md-6"><span class="bold" style="color: #e31e24">Average Days:</span>&nbsp;&nbsp;<?php echo round($resrowc['timediff'], 2) . ' Days'; ?></h4>
                    <h4 class="col-md-6"><span class="bold" style="color: #e31e24">Total Booked Amount:</span>&nbsp;&nbsp;<?php echo $resrowc['bookedamount']; ?></h4>
                    <h4 class="col-md-6"><span class="bold" style="color: #e31e24">Total Fresh Collection Amount:</span>&nbsp;&nbsp;<?php echo $resrowc['fresh_collection']; ?></h4>
                </div>
            </div>
        </div>
        <link href="<?php echo $web_url; ?>Employee/assets/global/plugins/datatables/datatables.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo $web_url; ?>Employee/assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" />
        <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script> 

        <table class="table table-bordered table-hover center table-responsive dt-responsive" width="100%" id="tableC">
            <thead class="tbg">
                <tr>
                    <th class="all">Sr.No</th>
                    <th class="desktop">Lead Unique ID</th>
                    <th class="desktop">Customer Name</th>
                    <th class="desktop">Source Of Lead</th>
                    <th class="desktop">Entry Date</th>
                    <th class="none">No Of Followup</th> 
                    <th class="desktop">Walk In Date</th>
                    <th class="desktop">Booked  Date</th>
                    <th class="desktop">Booked Amount</th>
                    <th class="desktop">Difference</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($rowfilter = mysqli_fetch_array($resultfilter)) {
                    $get_inquirysource = mysqli_fetch_array(mysqli_query($dbconn,"select * from customerentry where customerEntryId = '" . $rowfilter['customerEntryId'] . "'"));
                    $get_source = mysqli_fetch_array(mysqli_query($dbconn,"select * from inquirysource where inquirySourceId = '" . $get_inquirysource['inquirySourceId'] . "'"));
                    $i++;
//                    $book[0]+=$rowfilter['booking_amount'];
                    ?>
                    <tr>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $i; ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php
                                echo $rowfilter['leaduniqueid'];
                                ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php
                                $customerentry = "SELECT * FROM `customerentry` where isDelete='0'  and  istatus='1' and  customerEntryId='" . $rowfilter['customerEntryId'] . "'";
                                $resultCustomer = mysqli_query($dbconn,$customerentry);
                                $rowCustomer = mysqli_fetch_array($resultCustomer);
                                echo $rowCustomer['title'] . ' ' . $rowCustomer['firstName'] . ' ' . $rowCustomer['MiddleName'] . ' ' . $rowCustomer['lastName'];
                                ?> 
                            </div>
                        </td>   
                        <td>
                            <div class="form-group form-md-line-input "><?php
                                echo $get_source['inquirySourceName'];
                                ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php
                                echo $rowfilter['strEntryDate'];
                                ?> 
                            </div>
                        </td>                        
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['remarks']; ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php
                                echo $rowfilter['walkin_datetime'];
                                ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php
                                echo $rowfilter['nextFollowupModifyDate'];
                                ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php
                                echo $rowfilter['booking_amount'];
                                ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php
                                $datediff = strtotime($rowfilter['nextFollowupModifyDate']) - strtotime($rowfilter['walkin_datetime']);
                                echo round($datediff / (60 * 60 * 24)) . ' Days';
                                ?> 
                            </div>
                        </td>
                        <?php
                    }
                    ?>
                </tr>
            </tbody>
<!--            <tr style="background-color:#f3f3f3">
                <td colspan="1"><b>Total</b></td>
                <td colspan="1"></td>
                <td colspan="1"></td>
                <td colspan="1"></td>
                <td colspan="1"></td>
                <td colspan="1"></td>
                <td colspan="1"></td>
                <td colspan="1"><?= $book[0]; ?></td>
                <td colspan="1"></td>
            </tr>-->
        </table>
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