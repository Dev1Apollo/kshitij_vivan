<?php
error_reporting(E_ALL);
require_once('../common.php');
include('IsLogin.php');
$connect = new connect();
include('User_Paging.php');

if ($_POST['action'] == 'ListUser') {
    //$where = "where 1=1 and lead.employeeMasterId = '".$_SESSION['EmployeeId']."'";
    $where = "";
    if ($_REQUEST['FormDate'] != NULL && isset($_REQUEST['FormDate']))
        $where .= " and STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y')>= STR_TO_DATE('$_REQUEST[FormDate]','%d-%m-%Y')";
    if ($_REQUEST['ToDate'] != NULL && isset($_REQUEST['ToDate']))
        $where .= " and STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y')<=STR_TO_DATE('$_REQUEST[ToDate]','%d-%m-%Y')";
    $whereB = "";
    if ($_REQUEST['FormDate'] != NULL && isset($_REQUEST['FormDate']))
        $whereB .= " and STR_TO_DATE(nextFollowupModifyDate,'%d-%m-%Y')>= STR_TO_DATE('$_REQUEST[FormDate]','%d-%m-%Y')";
    if ($_REQUEST['ToDate'] != NULL && isset($_REQUEST['ToDate']))
        $whereB .= " and STR_TO_DATE(nextFollowupModifyDate,'%d-%m-%Y')<=STR_TO_DATE('$_REQUEST[ToDate]','%d-%m-%Y')";

    $whereC = "";
    if ($_REQUEST['FormDate'] != NULL && isset($_REQUEST['FormDate']))
        $whereC .= " and STR_TO_DATE(walkin_datetime,'%d-%m-%Y')>= STR_TO_DATE('$_REQUEST[FormDate]','%d-%m-%Y')";
    if ($_REQUEST['ToDate'] != NULL && isset($_REQUEST['ToDate']))
        $whereC .= " and STR_TO_DATE(walkin_datetime,'%d-%m-%Y')<=STR_TO_DATE('$_REQUEST[ToDate]','%d-%m-%Y')";

    $whereA = " and 1=1";
    if ($_REQUEST['InquirySource'] != NULL && isset($_REQUEST['InquirySource']))
        $whereA .= " and lead.customerEntryId in (select customerentry.customerEntryId from customerentry where inquirySourceId in (" . implode(',', $_POST['InquirySource']) . "))";
    $whereE = "";
    if ($_REQUEST['employeeMasterId'] != NULL && isset($_REQUEST['employeeMasterId'])) {
        $whereE .= " and lead.employeeMasterId='" . $_REQUEST['employeeMasterId'] . "'";
    }
    //SELECT count(*) as Inqcount,
    //customerentry.inquirySourceId
    //,
    //(select count(*) from lead as l2,customerentry as c2  where c2.customerEntryId = l2.customerEntryId and l2.statusId = 3 and c2.inquirySourceId = customerentry.inquirySourceId
    //and STR_TO_DATE(l2.nextFollowupModifyDate,'%d-%m-%Y')>= STR_TO_DATE('01-05-2018','%d-%m-%Y') and STR_TO_DATE(l2.nextFollowupModifyDate,'%d-%m-%Y')<=STR_TO_DATE('16-05-2018','%d-%m-%Y')
    //) as bookedInq1
    //
    //
    //FROM `lead`,customerentry  
    //where customerentry.customerEntryId = lead.customerEntryId and STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y')>= STR_TO_DATE('01-05-2018','%d-%m-%Y') and STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y')<=STR_TO_DATE('16-05-2018','%d-%m-%Y') 
    //GROUP by customerentry.inquirySourceId 
    // SUM(CASE WHEN l.walkin_datetime != '' AND l.statusId=6 $whereC THEN 1 ELSE 0 END) AS walkingOfflinecount,
    // SUM(CASE WHEN l.walkin_datetime != '' AND l.statusId=7 $whereC THEN 1 ELSE 0 END) AS walkingOnlinecount,
    // SUM(CASE WHEN l.walkin_datetime != '' AND l.statusId in (6,7) $whereC THEN 1 ELSE 0 END) AS walkingcount,
    $filterstr = "SELECT 
        SUM(CASE WHEN inquiryEnterDate != '' $where THEN 1 ELSE 0 END) AS Inqcount,
        customerentry.inquirySourceId,
        SUM(CASE WHEN EXISTS (
            SELECT 1 FROM leadfollowup lf 
            WHERE lf.leadId = l.leadId AND lf.statusId=6  $whereC
            ORDER BY lf.leadFollowupId DESC LIMIT 1
        ) THEN 1 ELSE 0 END) AS walkingOfflinecount,
        
        SUM(CASE WHEN EXISTS (
            SELECT 1 FROM leadfollowup lf 
            WHERE lf.leadId = l.leadId AND lf.statusId=7 $whereC
            ORDER BY lf.leadFollowupId DESC LIMIT 1
        ) THEN 1 ELSE 0 END) AS walkingOnlinecount,
        
        SUM(CASE WHEN EXISTS (
            SELECT 1 FROM leadfollowup lf 
            WHERE lf.leadId = l.leadId AND lf.statusId in (6,7) $whereC
            ORDER BY lf.leadFollowupId DESC LIMIT 1
        ) THEN 1 ELSE 0 END) AS walkingcount,
        
        SUM(CASE WHEN l.statusId=3 AND EXISTS (
            SELECT 1 FROM leadfollowup lf 
            WHERE lf.leadId = l.leadId AND lf.statusId=6 $whereC
            ORDER BY lf.leadFollowupId DESC LIMIT 1
        ) THEN 1 ELSE 0 END) AS bookedOfflineInq,
        
        SUM(CASE WHEN l.statusId=3 AND EXISTS (
            SELECT 1 FROM leadfollowup lf 
            WHERE lf.leadId = l.leadId AND lf.statusId=7 $whereC
            ORDER BY lf.leadFollowupId DESC LIMIT 1
        ) THEN 1 ELSE 0 END) AS bookedOnlineInq,
        SUM(CASE WHEN l.statusId=3 $whereB THEN 1 ELSE 0 END) AS bookedInq,
        
        SUM(CASE WHEN l.statusId=3 AND EXISTS (
            SELECT 1 FROM leadfollowup lf 
            WHERE lf.leadId = l.leadId AND lf.statusId=6 $whereC
            ORDER BY lf.leadFollowupId DESC LIMIT 1
        ) $whereB THEN booking_amount ELSE 0 END) AS bookedOfflineAmt,
        
        SUM(CASE WHEN l.statusId=3 AND EXISTS (
            SELECT 1 FROM leadfollowup lf 
            WHERE lf.leadId = l.leadId AND lf.statusId=7 $whereC
            ORDER BY lf.leadFollowupId DESC LIMIT 1
        ) $whereB THEN booking_amount ELSE 0 END) AS bookedOnlineAmt,
        SUM(CASE WHEN l.statusId=3 $whereB THEN l.booking_amount ELSE 0 END) AS bookingamount
        FROM lead l
        JOIN customerentry ON customerentry.customerEntryId = l.customerEntryId
        WHERE 1=1 
        GROUP BY customerentry.inquirySourceId";
    /*$filterstr = "SELECT 
    SUM(CASE WHEN STR_TO_DATE(l.inquiryEnterDate,'%d-%m-%Y') BETWEEN STR_TO_DATE('01-05-2025','%d-%m-%Y') AND STR_TO_DATE('31-05-2025','%d-%m-%Y') THEN 1 ELSE 0 END) AS Inqcount,
    customerentry.inquirySourceId,
    SUM(CASE WHEN l.walkin_datetime != '' AND l.statusId=6 AND STR_TO_DATE(l.walkin_datetime,'%d-%m-%Y') BETWEEN STR_TO_DATE('01-05-2025','%d-%m-%Y') AND STR_TO_DATE('31-05-2025','%d-%m-%Y') THEN 1 ELSE 0 END) AS walkingOfflinecount,
    SUM(CASE WHEN l.walkin_datetime != '' AND l.statusId=7 AND STR_TO_DATE(l.walkin_datetime,'%d-%m-%Y') BETWEEN STR_TO_DATE('01-05-2025','%d-%m-%Y') AND STR_TO_DATE('31-05-2025','%d-%m-%Y') THEN 1 ELSE 0 END) AS walkingOnlinecount,
    SUM(CASE WHEN l.walkin_datetime != '' AND l.statusId in (6,7) AND STR_TO_DATE(l.walkin_datetime,'%d-%m-%Y')  BETWEEN STR_TO_DATE('01-05-2025','%d-%m-%Y') AND STR_TO_DATE('31-05-2025','%d-%m-%Y') THEN 1 ELSE 0 END) AS walkingcount,
    SUM(CASE WHEN l.statusId=3 AND EXISTS (
        SELECT 1 FROM leadfollowup lf 
        WHERE lf.leadId = l.leadId AND lf.statusId=6 
        ORDER BY lf.leadFollowupId DESC LIMIT 1
    ) AND STR_TO_DATE(l.nextFollowupModifyDate,'%d-%m-%Y') BETWEEN STR_TO_DATE('01-05-2025','%d-%m-%Y') AND STR_TO_DATE('31-05-2025','%d-%m-%Y') THEN 1 ELSE 0 END) AS bookedOfflineInq,
    
    SUM(CASE WHEN l.statusId=3 AND EXISTS (
        SELECT 1 FROM leadfollowup lf 
        WHERE lf.leadId = l.leadId AND lf.statusId=7 
        ORDER BY lf.leadFollowupId DESC LIMIT 1
    ) AND STR_TO_DATE(l.nextFollowupModifyDate,'%d-%m-%Y') BETWEEN STR_TO_DATE('01-05-2025','%d-%m-%Y') AND STR_TO_DATE('31-05-2025','%d-%m-%Y') THEN 1 ELSE 0 END) AS bookedOnlineInq,
    SUM(CASE WHEN l.statusId=3 AND STR_TO_DATE(l.nextFollowupModifyDate,'%d-%m-%Y') BETWEEN STR_TO_DATE('01-05-2025','%d-%m-%Y') AND STR_TO_DATE('31-05-2025','%d-%m-%Y') THEN 1 ELSE 0 END) AS bookedInq,
    SUM(CASE WHEN l.statusId=3 AND STR_TO_DATE(l.nextFollowupModifyDate,'%d-%m-%Y') BETWEEN STR_TO_DATE('01-05-2025','%d-%m-%Y') AND STR_TO_DATE('31-05-2025','%d-%m-%Y') THEN l.booking_amount ELSE 0 END) AS bookingamount
    FROM lead l
    JOIN customerentry ON customerentry.customerEntryId = l.customerEntryId
    WHERE 1=1 
    GROUP BY customerentry.inquirySourceId";*/

    $countstr = "select count(*) as TotalRow,customerentry.inquirySourceId ,sum((select count(*) from lead l1 where l1.leadId = lead.leadId and l1.statusId=3 " . $where . " )) as BookingCount ,"
        . "sum((select l1.booking_amount from lead l1 where l1.leadId = lead.leadId and l1.statusId=3)) as BookingAmount from lead,"
        . "customerentry where customerentry.customerEntryId = lead.customerEntryId  " . $whereE . $where . $whereA . "  GROUP by customerentry.inquirySourceId";
    // echo $filterstr;exit;

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
                    <th class="desktop">Inquiry Source</th>
                    <th class="desktop">Total Inquiry </th>
                    <th class="desktop">Walkin Offline Inquiry</th>
                    <th class="desktop">Walkin Online Inquiry</th>
                    <th class="desktop">Total Walkin Inquiry </th>
                    <th class="desktop">Book Offline Inquiry</th>
                    <th class="desktop">Book Online Inquiry </th>
                    <th class="desktop">Total Booked Inquiry</th>
                    <th class="desktop">Booked Amount Offline</th>
                    <th class="desktop">Booked Amount Online</th>
                    <th class="desktop">Total Booked Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $walkin = array(0, 0, 0, 0,0,0,0,0,0);
                while ($rowfilter = mysqli_fetch_array($resultfilter)) {
                    if (isset($rowfilter['walkingcount'])) {
                        $walkin[0] += $rowfilter['walkingcount'];
                    }
                    if (isset($rowfilter['walkingOfflinecount'])) {
                        $walkin[4] += $rowfilter['walkingOfflinecount'];
                    }
                    if (isset($rowfilter['walkingOnlinecount'])) {
                        $walkin[5] += $rowfilter['walkingOnlinecount'];
                    }
                    
                    $walkin[1] += $rowfilter['bookedInq'];
                    $walkin[2] += $rowfilter['bookingamount'];
                    $walkin[3] += $rowfilter['Inqcount'];
                    // if (isset($rowfilter['walkingOnlineCount'])) {
                    //     $walkin[4] += $rowfilter['walkingOnlineCount'];
                    // }
                    $walkin[6] += $rowfilter['bookedOfflineInq'];
                    $walkin[7] += $rowfilter['bookedOnlineInq'];
                    $walkin[8] += $rowfilter['bookedOfflineAmt'];
                    $walkin[9] += $rowfilter['bookedOnlineAmt'];
                    
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
                                    $inquirySource = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `inquirysource`  where isDelete='0'  and  istatus='1' and inquirySourceId='" . $rowfilter['inquirySourceId'] . "'"));
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
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['walkingOfflinecount']; ?>
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['walkingOnlinecount']; ?>
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['walkingcount']; ?>
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['bookedOfflineInq']; ?>
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['bookedOnlineInq']; ?>
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['bookedInq']; ?>
                            </div>
                        </td>
                        
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['bookedOfflineAmt']; ?>
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['bookedOnlineAmt']; ?>
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['bookingamount']; ?>
                            </div>
                        </td>
                    <?php
                }
                    ?>


                    </tr>

            </tbody>
            <tr style="background-color:#f3f3f3">
                <td colspan="1"><b>Total</b></td>
                <th class="desktop">-</th>
                <th class="desktop"><?php echo $walkin[3]; ?></th>
                <th class="desktop"><?php echo $walkin[4]; ?></th>
                <th class="desktop"><?php echo $walkin[5]; ?></th>
                <th class="desktop"><?php echo $walkin[0]; ?></th>
                <th class="desktop"><?php echo $walkin[6]; ?></th>
                <th class="desktop"><?php echo $walkin[7]; ?></th>
                <th class="desktop"><?php echo $walkin[1]; ?></th>
                <th class="desktop"><?php echo $walkin[8]; ?></th>
                <th class="desktop"><?php echo $walkin[9]; ?></th>
                <th class="desktop"><?php echo $walkin[2]; ?></th>
                
                    
                    
                <!--<td colspan="1">--</td>
                <td colspan="1"><?php echo $walkin[3]; ?></td>
                <td colspan="1"><?php echo $walkin[0]; ?></td>
                <td colspan="1"><?php echo $walkinPer; ?></td>
                <td colspan="1"><?php echo $walkin[4]; ?></td>
                <td colspan="1"><?php echo $walkinPerOnline; ?></td>
                <td colspan="1"><?php echo $walkin[1]; ?></td>
                <td colspan="1"><?php echo $bookPer; ?></td>
                <td colspan="1"><?php echo $walkin[2]; ?></td>-->
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