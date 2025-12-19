<?php
error_reporting(0);
require_once('../common.php');
include('IsLogin.php');
$connect = new connect();
include('User_Paging.php');


if ($_POST['action'] == 'ListUser') {
    if ($_REQUEST['viewDetails'] == 'TF')
        $filterstr = "SELECT * FROM lead where  MONTH(STR_TO_DATE(nextFollowupDate,'%d-%m-%Y'))=MONTH(CURRENT_DATE()) and YEAR(STR_TO_DATE(nextFollowupDate,'%d-%m-%Y'))=YEAR(CURRENT_DATE()) and STR_TO_DATE(nextFollowupDate,'%d-%m-%Y')= CURRENT_DATE() and employeeMasterId='" . $_SESSION['EmployeeId'] . "' order by STR_TO_DATE(strEntryDate,'%d-%m-%Y') desc";
    else if ($_REQUEST['viewDetails'] == 'TNI')
        $filterstr = "SELECT * FROM lead where  MONTH(STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y'))=MONTH(CURRENT_DATE()) and YEAR(STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y'))=YEAR(CURRENT_DATE()) and STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y')= CURRENT_DATE() and employeeMasterId='" . $_SESSION['EmployeeId'] . "' order by STR_TO_DATE(strEntryDate,'%d-%m-%Y') desc";
    else if ($_REQUEST['viewDetails'] == 'MI')
        $filterstr = "SELECT * FROM lead where  MONTH(STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y'))=MONTH(CURRENT_DATE()) and YEAR(STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y'))=YEAR(CURRENT_DATE())  and employeeMasterId='" . $_SESSION['EmployeeId'] . "' order by STR_TO_DATE(strEntryDate,'%d-%m-%Y') desc";
    else if ($_REQUEST['viewDetails'] == 'TB')


        $filterstr = "SELECT * FROM lead where  MONTH(STR_TO_DATE(nextFollowupModifyDate,'%d-%m-%Y'))=MONTH(CURRENT_DATE()) and YEAR(STR_TO_DATE(nextFollowupModifyDate,'%d-%m-%Y'))=YEAR(CURRENT_DATE()) and STR_TO_DATE(nextFollowupModifyDate,'%d-%m-%Y')= CURRENT_DATE() and statusId='3' and employeeMasterId='" . $_SESSION['EmployeeId'] . "' ORDER BY STR_TO_DATE(strEntryDate,'%d-%m-%Y') DESC";

    else if ($_REQUEST['viewDetails'] == 'MB')
        $filterstr = "SELECT * FROM lead where  MONTH(STR_TO_DATE(nextFollowupModifyDate,'%d-%m-%Y'))=MONTH(CURRENT_DATE()) and YEAR(STR_TO_DATE(nextFollowupModifyDate,'%d-%m-%Y'))=YEAR(CURRENT_DATE()) and statusId='3' and employeeMasterId='" . $_SESSION['EmployeeId'] . "' order by STR_TO_DATE(strEntryDate,'%d-%m-%Y') desc";
    else if ($_REQUEST['viewDetails'] == 'TL')
        $filterstr = "SELECT * FROM lead where  MONTH(STR_TO_DATE(nextFollowupModifyDate,'%d-%m-%Y'))=MONTH(CURRENT_DATE()) and YEAR(STR_TO_DATE(nextFollowupModifyDate,'%d-%m-%Y'))=YEAR(CURRENT_DATE()) and STR_TO_DATE(nextFollowupModifyDate,'%d-%m-%Y')= CURRENT_DATE() and statusId='2' and employeeMasterId='" . $_SESSION['EmployeeId'] . "' order by STR_TO_DATE(strEntryDate,'%d-%m-%Y') desc";
    else if ($_REQUEST['viewDetails'] == 'ML')
        $filterstr = "SELECT * FROM lead where  MONTH(STR_TO_DATE(nextFollowupModifyDate,'%d-%m-%Y'))=MONTH(CURRENT_DATE()) and YEAR(STR_TO_DATE(nextFollowupModifyDate,'%d-%m-%Y'))=YEAR(CURRENT_DATE()) and statusId='2'  and employeeMasterId='" . $_SESSION['EmployeeId'] . "' order by STR_TO_DATE(strEntryDate,'%d-%m-%Y') desc";
    else if ($_REQUEST['viewDetails'] == 'odc')
        $filterstr = "SELECT * FROM lead where  DATE_FORMAT(STR_TO_DATE(nextFollowupDate, '%d-%m-%Y'), '%Y-%m-%d') < DATE_FORMAT(STR_TO_DATE('" . date('d-m-Y') . "', '%d-%m-%Y'), '%Y-%m-%d') and statusId in ('1','6') and employeeMasterId='" . $_SESSION['EmployeeId'] . "' order by STR_TO_DATE(strEntryDate,'%d-%m-%Y') desc";

    if ($_REQUEST['viewDetails'] == 'TF')
        $countstr = "SELECT count(*) as TotalRow FROM lead where  MONTH(STR_TO_DATE(nextFollowupDate,'%d-%m-%Y'))=MONTH(CURRENT_DATE()) and YEAR(STR_TO_DATE(nextFollowupDate,'%d-%m-%Y'))=YEAR(CURRENT_DATE()) and STR_TO_DATE(nextFollowupDate,'%d-%m-%Y')= CURRENT_DATE() and employeeMasterId='" . $_SESSION['EmployeeId'] . "'";
    else if ($_REQUEST['viewDetails'] == 'TNI')
        $countstr = "SELECT count(*) as TotalRow FROM lead where  MONTH(STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y'))=MONTH(CURRENT_DATE()) and YEAR(STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y'))=YEAR(CURRENT_DATE()) and STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y')= CURRENT_DATE() and employeeMasterId='" . $_SESSION['EmployeeId'] . "'";
    else if ($_REQUEST['viewDetails'] == 'MI')
        $countstr = "SELECT count(*) as TotalRow FROM lead where  MONTH(STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y'))=MONTH(CURRENT_DATE()) and YEAR(STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y'))=YEAR(CURRENT_DATE())  and employeeMasterId='" . $_SESSION['EmployeeId'] . "'";

    else if ($_REQUEST['viewDetails'] == 'TB')


        $countstr = "SELECT count(*) as TotalRow FROM lead where  MONTH(STR_TO_DATE(nextFollowupModifyDate,'%d-%m-%Y'))=MONTH(CURRENT_DATE()) and YEAR(STR_TO_DATE(nextFollowupModifyDate,'%d-%m-%Y'))=YEAR(CURRENT_DATE()) and STR_TO_DATE(nextFollowupModifyDate,'%d-%m-%Y')= CURRENT_DATE() and statusId='3' and employeeMasterId='" . $_SESSION['EmployeeId'] . "'";

    else if ($_REQUEST['viewDetails'] == 'MB')
        $countstr = "SELECT count(*) as TotalRow FROM lead where  MONTH(STR_TO_DATE(nextFollowupModifyDate,'%d-%m-%Y'))=MONTH(CURRENT_DATE()) and YEAR(STR_TO_DATE(nextFollowupModifyDate,'%d-%m-%Y'))=YEAR(CURRENT_DATE())  and statusId='3' and employeeMasterId='" . $_SESSION['EmployeeId'] . "'";
    else if ($_REQUEST['viewDetails'] == 'TL')
        $countstr = "SELECT count(*) as TotalRow FROM lead where  MONTH(STR_TO_DATE(nextFollowupModifyDate,'%d-%m-%Y'))=MONTH(CURRENT_DATE()) and YEAR(STR_TO_DATE(nextFollowupModifyDate,'%d-%m-%Y'))=YEAR(CURRENT_DATE()) and STR_TO_DATE(nextFollowupModifyDate,'%d-%m-%Y')= CURRENT_DATE() and statusId='2' and employeeMasterId='" . $_SESSION['EmployeeId'] . "'";
    else if ($_REQUEST['viewDetails'] == 'ML')
        $countstr = "SELECT count(*) as TotalRow FROM lead where  MONTH(STR_TO_DATE(nextFollowupModifyDate,'%d-%m-%Y'))=MONTH(CURRENT_DATE()) and YEAR(STR_TO_DATE(nextFollowupModifyDate,'%d-%m-%Y'))=YEAR(CURRENT_DATE())  and statusId='2' and employeeMasterId='" . $_SESSION['EmployeeId'] . "'";
    else if ($_REQUEST['viewDetails'] == 'odc')
        $countstr = "SELECT count(*) as TotalRow FROM lead where  DATE_FORMAT(STR_TO_DATE(nextFollowupDate, '%d-%m-%Y'), '%Y-%m-%d') < DATE_FORMAT(STR_TO_DATE('" . date('d-m-Y') . "', '%d-%m-%Y'), '%Y-%m-%d') and employeeMasterId='" . $_SESSION['EmployeeId'] . "' and statusId in ('1','6') and employeeMasterId='" . $_SESSION['EmployeeId'] . "'";


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
                    <th class="desktop">Lead Unique ID</th>
                    <th class="desktop">Customer Name</th>
                    <th class="desktop">Employee Name</th>
                    <th class="desktop">Source Of Lead</th>
                    <th class="desktop">Entry Date</th>
                    <th class="desktop">Inquiry Status</th>
                    <th class="desktop">Next FollowUp Date</th>
                    <th class="desktop">FollowUp Comment</th>

                    <?php if ($_REQUEST['viewDetails'] == 'odc') { ?>
                        <th class="desktop">Action</th>
                    <?php } ?>
                    <!--                    <th class="desktop">Action</th>-->
                </tr>
            </thead>
            <tbody>
                <?php
                while ($rowfilter = mysqli_fetch_array($resultfilter)) {
                    $get_inquirysource = mysqli_fetch_array(mysqli_query($dbconn, "select * from customerentry where customerEntryId = '" . $rowfilter['customerEntryId'] . "'"));
                    $get_source = mysqli_fetch_array(mysqli_query($dbconn, "select * from inquirysource where inquirySourceId = '" . $get_inquirysource['inquirySourceId'] . "'"));
                    $serial++;
                    // $i++;
                ?>
                    <tr>
                        <td>
                            <div class="form-group form-md-line-input count"> <?php echo $serial; ?>
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['leaduniqueid']; ?>
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php
                                                                        $customerentry = "SELECT * FROM `customerentry` where isDelete='0'  and  istatus='1' and  customerEntryId='" . $rowfilter['customerEntryId'] . "'";
                                                                        $resultCustomer = mysqli_query($dbconn, $customerentry);
                                                                        $rowCustomer = mysqli_fetch_array($resultCustomer);
                                                                        echo $rowCustomer['title'] . ' ' . $rowCustomer['firstName'] . ' ' . $rowCustomer['MiddleName'] . ' ' . $rowCustomer['lastName'];
                                                                        ?>
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php
                                                                        $employeemaster = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `employeemaster`  where isDelete='0'  and  istatus='1' and employeeMasterId='" . $rowfilter['employeeMasterId'] . "'"));
                                                                        echo $employeemaster['employeeName'];
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
                            <div class="form-group form-md-line-input "><?php
                                                                        $inquiryStatus = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `status`  where isDelete='0'  and  istatus='1' and statusId='" . $rowfilter['statusId'] . "'"));
                                                                        echo $inquiryStatus['statusName'];
                                                                        ?>
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php
                                                                        echo $rowfilter['nextFollowupDate'];
                                                                        ?>
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['comment']; ?>
                            </div>
                        </td>
                        <?php if ($_REQUEST['viewDetails'] == 'odc') { ?>
                            <td style="width: 10%">
                                <div class="form-group form-md-line-input">
                                    <input type="button" class="btn blue" value="=" title="INQUIRY FOLLOWUP" onclick="window.open('<?php echo $web_url; ?>Supervisor/AddInquiryFollowup.php?token=<?php echo $rowfilter['leadId']; ?>&cid=<?php echo $rowfilter['customerEntryId']; ?>' , 'popUpWindow', 'height=500,width=1250,left=100,top=100,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no,status=yes');">
                                    <!--                                    <a  class="btn blue" href="<?php echo $web_url; ?>Supervisor/AddInquiryFollowup.php?token=<?php echo $rowfilter['leadId']; ?>&cid=<?php echo $rowfilter['customerEntryId']; ?>" title="INQUIRY FOLLOWUP"><i class="fa fa-bars"></i></a>-->
                                </div>
                            </td>
                    <?php
                        }
                    }
                    ?>
                    </tr>
            </tbody>
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