<?php
error_reporting(0);
require_once('../common.php');
include('IsLogin.php');
$connect = new connect();
include('User_Paging.php');


if ($_POST['action'] == 'ListUser') {
    //$where = "where 1=1 and employeeMasterId = '".$_SESSION['EmployeeId']."'";
    $where = "where 1=1 ";

    // if ($_REQUEST['FormDate'] != NULL && isset($_REQUEST['FormDate']))
    //     $where .= " and STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y')>= STR_TO_DATE('$_REQUEST[FormDate]','%d-%m-%Y')";
    // if ($_REQUEST['ToDate'] != NULL && isset($_REQUEST['ToDate']))
    //     $where .= " and STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y')<=STR_TO_DATE('$_REQUEST[ToDate]','%d-%m-%Y')";

    // $whereA = "where 1=1";
    // if ($_REQUEST['FormDate'] != NULL && isset($_REQUEST['FormDate']))
    //     $whereA .= " and STR_TO_DATE(l1.nextFollowupModifyDate,'%d-%m-%Y')>= STR_TO_DATE('$_REQUEST[FormDate]','%d-%m-%Y')";
    // if ($_REQUEST['ToDate'] != NULL && isset($_REQUEST['ToDate']))
    //     $whereA .= " and STR_TO_DATE(l1.nextFollowupModifyDate,'%d-%m-%Y')<=STR_TO_DATE('$_REQUEST[ToDate]','%d-%m-%Y')";

    // $whereB = "where 1=1";
    // if ($_REQUEST['FormDate'] != NULL && isset($_REQUEST['FormDate']))
    //     $whereB .= " and STR_TO_DATE(l1.walkin_datetime,'%d-%m-%Y')>= STR_TO_DATE('$_REQUEST[FormDate]','%d-%m-%Y')";
    // if ($_REQUEST['ToDate'] != NULL && isset($_REQUEST['ToDate']))
    //     $whereB .= " and STR_TO_DATE(l1.walkin_datetime,'%d-%m-%Y')<=STR_TO_DATE('$_REQUEST[ToDate]','%d-%m-%Y')";
    
    // $whereA = "";
    // if ($_REQUEST['FormDate'] != NULL && isset($_REQUEST['FormDate']))
    //     $whereA .= " and STR_TO_DATE(l1.nextFollowupModifyDate,'%d-%m-%Y')>= STR_TO_DATE('$_REQUEST[FormDate]','%d-%m-%Y')";
    // if ($_REQUEST['ToDate'] != NULL && isset($_REQUEST['ToDate']))
    //     $whereA .= " and STR_TO_DATE(l1.nextFollowupModifyDate,'%d-%m-%Y')<=STR_TO_DATE('$_REQUEST[ToDate]','%d-%m-%Y')";
        
    // $whereB = "";
    // if ($_REQUEST['FormDate'] != NULL && isset($_REQUEST['FormDate']))
    //     $whereB .= " and STR_TO_DATE(l1.walkin_datetime,'%d-%m-%Y')>= STR_TO_DATE('$_REQUEST[FormDate]','%d-%m-%Y')";
    // if ($_REQUEST['ToDate'] != NULL && isset($_REQUEST['ToDate']))
    //     $whereB .= " and STR_TO_DATE(l1.walkin_datetime,'%d-%m-%Y')<=STR_TO_DATE('$_REQUEST[ToDate]','%d-%m-%Y')";
    
    // $whereC = "";
    // if ($_REQUEST['FormDate'] != NULL && isset($_REQUEST['FormDate']))
    //     $whereC .= " and STR_TO_DATE(nextFollowupDate,'%d-%m-%Y')>= STR_TO_DATE('$_REQUEST[FormDate]','%d-%m-%Y')";
    // if ($_REQUEST['ToDate'] != NULL && isset($_REQUEST['ToDate']))
    //     $whereC .= " and STR_TO_DATE(nextFollowupDate,'%d-%m-%Y')<=STR_TO_DATE('$_REQUEST[ToDate]','%d-%m-%Y')";
        
    // if ($_REQUEST['employeeMasterId'] != NULL && isset($_REQUEST['employeeMasterId'])) {
    //     $where .= " and lead.employeeMasterId='" . $_REQUEST['employeeMasterId'] . "'";
    // }
    
    $formDate = $_REQUEST['FormDate'] ?? null;
    $toDate = $_REQUEST['ToDate'] ?? null;
    
    // Sanitize and prepare filters
    $dateFilters = function($column) use ($formDate, $toDate) {
        $filter = "";
        if (!empty($formDate)) {
            $filter .= " AND STR_TO_DATE($column,'%d-%m-%Y') >= STR_TO_DATE('$formDate','%d-%m-%Y')";
        }
        if (!empty($toDate)) {
            $filter .= " AND STR_TO_DATE($column,'%d-%m-%Y') <= STR_TO_DATE('$toDate','%d-%m-%Y')";
        }
        return $filter;
    };
    
    // Define reusable date filters
    $whereC = $dateFilters('strEntryDate');
    $whereA = $dateFilters('lf.strEntryDate');
    $whereB = $dateFilters('l1.strEntryDate');
    
    
    // $filterstr = "SELECT id,support_emp_name as EmployeeName,
    //             (select COUNT(*) cnt from leadfollowup as lf where lf.support_employee=s.id and lf.statusId=1 ".$whereC.") as CallCount,
    //             (select count(DISTINCT leadId) as cnt from leadfollowup as l1 where 1=1  ".$whereC." and l1.support_employee = s.id and l1.support_employee!=0 and l1.statusId in(6,7)) as walkininquiry,
    //             (select count(DISTINCT leadId) as cnt from leadfollowup as l1 where 1=1  ".$whereC." and l1.transfer_to = s.id and l1.support_employee!=0 and l1.statusId in(6,7)) as CounselingInquiry,
    //             (select count(*) from lead as l1 where 1=1 ".$whereA." and l1.statusId = 3 and l1.support_employee = s.id and l1.support_employee!=0) as bookedInq,
    //             (select sum(l1.booking_amount) from lead as l1 where 1=1 ".$whereA." and l1.statusId = 3 and l1.support_employee = s.id and l1.support_employee!=0) as bookingAmount
    //             FROM `support_employee` as s where s.istatus=1 and s.isDelete=0";
    
    // Final query
$filterstr = "SELECT  id, support_emp_name AS EmployeeName,(SELECT COUNT(*) FROM leadfollowup AS lf WHERE lf.support_employee = s.id AND lf.statusId in (1,6,7) $whereC) AS CallCount,
    (SELECT COUNT(DISTINCT leadId) FROM leadfollowup AS l1 WHERE l1.walkinby = s.id  AND l1.statusId IN (6,7) $whereC) AS walkininquiry,
    (SELECT COUNT(DISTINCT leadId) FROM leadfollowup AS l1 WHERE l1.transfer_to = s.id  AND l1.statusId IN (6,7) $whereC) AS CounselingInquiry,
    (SELECT COUNT(*) FROM lead AS l1 inner join leadfollowup AS lf on l1.leadId=lf.leadId  WHERE l1.statusId = 3 AND l1.bookedby = s.id  $whereA) AS bookedInq,
    (SELECT SUM(l1.booking_amount) FROM lead AS l1 inner join leadfollowup AS lf on l1.leadId=lf.leadId WHERE l1.statusId = 3 AND l1.bookedby = s.id  $whereA) AS bookingAmount
    FROM support_employee AS s WHERE s.istatus = 1 AND s.isDelete = 0";
    
    // $filterstr = "SELECT count(*) as Inqcount,employeeMasterId ,(select employeemaster.employeeName from employeemaster where employeemaster.employeeMasterId = lead.employeeMasterId) as EmployeeName ,(select count(*) from lead where statusId = 3 and STR_TO_DATE(nextFollowupModifyDate,'%d-%m-%Y')>= STR_TO_DATE('$_REQUEST[FormDate]','%d-%m-%Y') and STR_TO_DATE(nextFollowupModifyDate,'%d-%m-%Y')<=STR_TO_DATE('$_REQUEST[ToDate]','%d-%m-%Y')) as bookedInq  FROM `lead`  " . $where . "   GROUP by employeeMasterId";
    $countstr = "SELECT count(*) as Inqcount
            FROM `support_employee` as l where l.istatus=1 and l.isDelete=0 ";


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

        <table class="table table-bordered table-hover center table-responsive dt-responsive" width="100%" id="tableC">
            <thead class="tbg">
                <tr>
                    <th class="all">Sr.No</th>
                    <th class="desktop">Employee Name</th>
                    <th class="desktop">Total Inquiry (Call) </th>
                    <th class="desktop">Walk-in Inquiry </th>
                    <th class="desktop">Counseling Inquiry </th>
                    <th class="desktop">Booked Inquiry </th>
                    <th class="desktop">Booked Amount </th>


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
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['EmployeeName']; ?>
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['CallCount']; ?>
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['walkininquiry']; ?>
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['CounselingInquiry']; ?>
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['bookedInq']; ?>
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo isset($rowfilter['bookingAmount']) && $rowfilter['bookingAmount'] != "" ? $rowfilter['bookingAmount'] : 0; ?>
                            </div>
                        </td>


                    <?php
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