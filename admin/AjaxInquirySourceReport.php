<?php
error_reporting(0);
require_once('../common.php');
include('IsLogin.php');
$connect = new connect();
include ('User_Paging.php');


if ($_POST['action'] == 'ListUser') {
    //$where = "where 1=1 and lead.employeeMasterId = '".$_SESSION['EmployeeId']."'";
    $where = "";
    if ($_REQUEST['FormDate'] != NULL && isset($_REQUEST['FormDate']))
        $where.=" and STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y')>= STR_TO_DATE('$_REQUEST[FormDate]','%d-%m-%Y')";
    if ($_REQUEST['ToDate'] != NULL && isset($_REQUEST['ToDate']))
        $where.=" and STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y')<=STR_TO_DATE('$_REQUEST[ToDate]','%d-%m-%Y')";
    if ($_REQUEST['Employee'] != NULL && isset($_REQUEST['Employee']))
        $where.="  and lead.employeeMasterId= '" . $_REQUEST['Employee'] . "'";
    if ($_REQUEST['branch'] != NULL && isset($_REQUEST['branch']))
        $where.= " and lead.employeeMasterId in (select employeeMasterId from employeemaster where employeemaster.branchid=" . $_REQUEST['branch'] . ")";

    $whereB = "";
    if ($_REQUEST['FormDate'] != NULL && isset($_REQUEST['FormDate']))
        $whereB.=" and STR_TO_DATE(nextFollowupModifyDate,'%d-%m-%Y')>= STR_TO_DATE('$_REQUEST[FormDate]','%d-%m-%Y')";
    if ($_REQUEST['ToDate'] != NULL && isset($_REQUEST['ToDate']))
        $whereB.=" and STR_TO_DATE(nextFollowupModifyDate,'%d-%m-%Y')<=STR_TO_DATE('$_REQUEST[ToDate]','%d-%m-%Y')";
    if ($_REQUEST['Employee'] != NULL && isset($_REQUEST['Employee']))
        $whereB.="  and lead.employeeMasterId= '" . $_REQUEST['Employee'] . "'";
    if ($_REQUEST['branch'] != NULL && isset($_REQUEST['branch']))
        $whereB.= " and lead.employeeMasterId in (select employeeMasterId from employeemaster where employeemaster.branchid=" . $_REQUEST['branch'] . ")";

    $whereC = "";
    if ($_REQUEST['FormDate'] != NULL && isset($_REQUEST['FormDate']))
        $whereC.=" and STR_TO_DATE(walkin_datetime,'%d-%m-%Y')>= STR_TO_DATE('$_REQUEST[FormDate]','%d-%m-%Y')";
    if ($_REQUEST['ToDate'] != NULL && isset($_REQUEST['ToDate']))
        $whereC.=" and STR_TO_DATE(walkin_datetime,'%d-%m-%Y')<=STR_TO_DATE('$_REQUEST[ToDate]','%d-%m-%Y')";
    if ($_REQUEST['Employee'] != NULL && isset($_REQUEST['Employee']))
        $whereC.="  and lead.employeeMasterId= '" . $_REQUEST['Employee'] . "'";
    if ($_REQUEST['branch'] != NULL && isset($_REQUEST['branch']))
        $whereC.= " and lead.employeeMasterId in (select employeeMasterId from employeemaster where employeemaster.branchid=" . $_REQUEST['branch'] . ")";

    $whereA = " and 1=1";
    if ($_REQUEST['InquirySource'] != NULL && isset($_REQUEST['InquirySource']))
        $whereA.=" and lead.customerEntryId in (select customerentry.customerEntryId from customerentry where inquirySourceId in (" . implode(',', $_POST['InquirySource']) . "))";


    $filterstr = "select sum((select count(*) from lead l1 where l1.leadId = lead.leadId " . $where . " )) as Inqcount,customerentry.inquirySourceId ,sum((select count(*) from lead l1 where l1.leadId = lead.leadId and l1.statusId=3 " . $whereB . " )) as bookedInq ,"
    . "sum((select l1.booking_amount from lead l1 where l1.leadId = lead.leadId and l1.statusId=3 " . $whereB . ")) as bookingamount,sum((select count(*) from lead l1 where l1.leadId = lead.leadId and l1.walkin_datetime != '' " . $whereC . ")) as walkingcount from lead,"
    . "customerentry where customerentry.customerEntryId = lead.customerEntryId  " . $whereA . "  GROUP by customerentry.inquirySourceId";

    $countstr = "select count(*) as TotalRow,customerentry.inquirySourceId ,sum((select count(*) from lead l1 where l1.leadId = lead.leadId and l1.statusId=3 " . $where . " )) as BookingCount ,"
            . "sum((select l1.booking_amount from lead l1 where l1.leadId = lead.leadId and l1.statusId=3 " . $where . " )) as BookingAmount from lead,"
            . "customerentry where customerentry.customerEntryId = lead.customerEntryId " . $whereA . "  GROUP by customerentry.inquirySourceId";


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
        <link href="assets/global/plugins/datatables/datatables.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" />
        <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script> 

        <table class="table table-bordered table-hover center table-responsive dt-responsive" width="100%" id="tableC">
            <thead class="tbg">
                <tr>
                    <th class="all">Sr.No</th>
                    <th class="desktop">Inquiry Source</th>
                    <th class="desktop">Total Inquiry </th>
                    <th class="desktop">Walk-in Inquiry </th>
                    <th class="desktop">Walk-in Inquiry(%)</th>
                    <th class="desktop">Booked Inquiry </th>
                    <th class="desktop">Booked Inquiry(%)</th>
                    <th class="desktop">Booked Amount </th>
                </tr>
            </thead>
            <tbody>
                <?php
                $walkin = array('0', '0','0','0');
                while ($rowfilter = mysqli_fetch_array($resultfilter)) {
                    $walkin[0]+=$rowfilter['walkingcount'];
                    $walkin[1]+=$rowfilter['bookedInq'];
                    $walkin[2]+=$rowfilter['bookingamount'];
                    $walkin[3]+=$rowfilter['Inqcount'];
                    $i++;
                    $serial++;
                    ?>
                    <tr>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $serial; ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php
                                if ($rowfilter['inquirySourceId'] == '0') {
                                    echo '-';
                                } else {
                                    $inquirySource = mysqli_fetch_array(mysqli_query($dbconn,"SELECT * FROM `inquirysource`  where isDelete='0'  and  istatus='1' and inquirySourceId='" . $rowfilter['inquirySourceId'] . "'"));
                                    echo $inquirySource['inquirySourceName'];
                                }
                                ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['Inqcount']; ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['walkingcount']; ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php //$perntage = $rowfilter['walkingcount'] / $rowfilter['Inqcount'];
                            $perntage = "0";
                            if($rowfilter['walkingcount'] > 0 && $rowfilter['Inqcount'] > 0){
                                $perntage = ($rowfilter['walkingcount'] / $rowfilter['Inqcount']) * 100;
                            } else {
                                $perntage = "0";
                            }
                            if($perntage != "" || $perntage != 0){
                                echo number_format($perntage , 2) . '%';
                            } else {
                                echo "0.00 %";
                            }
                    
                                ?> 
                            </div>
                        </td>                     
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['bookedInq']; ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php
                            $perntage = "0";
                            if($rowfilter['bookedInq'] > 0 && $rowfilter['walkingcount'] > 0){
                                $perntage = ($rowfilter['bookedInq'] / $rowfilter['walkingcount']) * 100;
                            } else {
                                $perntage = "0";
                            }
                            
                            if($perntage != "" || $perntage != 0){
                                echo number_format($perntage, 2) . '%';
                            }else {
                                echo "0.00 %";
                            }
                    
                    ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php if ($rowfilter['bookingamount'] != '' && $rowfilter['bookedInq'] != 0) {
                        echo $rowfilter['bookingamount'];
                    } else {
                        echo '0';
                    } ?> 
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
                <td colspan="1"><?php echo $walkin[3]; ?></td>
                <td colspan="1"><?php echo $walkin[0]; ?></td>
                <?php
                $walkingcount = $walkin[0];
                $Inqcount = $walkin[3];
                if($walkingcount > 0 && $Inqcount > 0){
                    $perntage = ($walkingcount / $Inqcount) * 100;
                } else {
                    $perntage = 0;
                }
                $walkinPer = number_format($perntage, 2) . '%';

                $bookedInq = $walkin[1];
                if($walkingcount > 0 && $bookedInq > 0){
                    $bookPerntage = ($bookedInq / $walkingcount) * 100;
                } else {
                    $bookPerntage = 0;
                }
                
                $bookPer = number_format($bookPerntage, 2) . '%';
                ?>
                <td colspan="1"><?php echo $walkinPer;?></td>
                <td colspan="1"><?php echo $walkin[1]; ?></td>
                <td colspan="1"><?php echo $bookPer;?></td>
                <td colspan="1"><?php echo $walkin[2]; ?></td>
            </tr>
        </table>
        <script src="assets/global/plugins/datatables/datatables.js" type="text/javascript"></script>
        <script src="assets/global/plugins/datatables/table-datatables-responsive.js" type="text/javascript"></script>
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
//                    echo '<ul>';
//
//                    if ($totalrecord > $per_page) {
//                        echo paginate($reload = '', $show_page, $total_pages);
//                    }
//                    echo "</ul>";
                    ?>
                </div>
            </div>

        </div>
    </div>
<?php } ?>