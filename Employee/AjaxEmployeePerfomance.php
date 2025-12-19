<?php
error_reporting(0);
require_once('../common.php');
include('IsLogin.php');
$connect = new connect();
include ('User_Paging.php');


if ($_POST['action'] == 'ListUser') {
    $where = "where 1=1 and employeeMasterId = '".$_SESSION['EmployeeId']."'";

    if ($_REQUEST['FormDate'] != NULL && isset($_REQUEST['FormDate']))
        $where.=" and STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y')>= STR_TO_DATE('$_REQUEST[FormDate]','%d-%m-%Y')";
    if ($_REQUEST['ToDate'] != NULL && isset($_REQUEST['ToDate']))
        $where.=" and STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y')<=STR_TO_DATE('$_REQUEST[ToDate]','%d-%m-%Y')";
   
    $whereA = "where 1=1";
    if ($_REQUEST['FormDate'] != NULL && isset($_REQUEST['FormDate']))
        $whereA.=" and STR_TO_DATE(l1.nextFollowupModifyDate,'%d-%m-%Y')>= STR_TO_DATE('$_REQUEST[FormDate]','%d-%m-%Y')";
    if ($_REQUEST['ToDate'] != NULL && isset($_REQUEST['ToDate']))
        $whereA.=" and STR_TO_DATE(l1.nextFollowupModifyDate,'%d-%m-%Y')<=STR_TO_DATE('$_REQUEST[ToDate]','%d-%m-%Y')";
    
    $whereB = "where 1=1";
    if ($_REQUEST['FormDate'] != NULL && isset($_REQUEST['FormDate']))
        $whereB.=" and STR_TO_DATE(l1.walkin_datetime,'%d-%m-%Y')>= STR_TO_DATE('$_REQUEST[FormDate]','%d-%m-%Y')";
    if ($_REQUEST['ToDate'] != NULL && isset($_REQUEST['ToDate']))
        $whereB.=" and STR_TO_DATE(l1.walkin_datetime,'%d-%m-%Y')<=STR_TO_DATE('$_REQUEST[ToDate]','%d-%m-%Y')";

    $filterstr = "SELECT count(*) as Inqcount,employeeMasterId ,(select employeemaster.employeeName from employeemaster where employeemaster.employeeMasterId = lead.employeeMasterId) as EmployeeName ,(select count(*) from lead as l1 " . $whereA . " and l1.statusId = 3 and l1.employeeMasterId = lead.employeeMasterId ) as bookedInq,(select count(*) from lead as l1 " . $whereB . "  and walkin_datetime != ''and l1.employeeMasterId = lead.employeeMasterId ) as walkininquiry,(select count(*) from lead as l1 " . $whereA . " and walkin_datetime != '' and l1.statusId = 3 and l1.employeeMasterId = lead.employeeMasterId) as convertedinquiry  FROM `lead`  " . $where . "   GROUP by employeeMasterId";

    // $filterstr = "SELECT count(*) as Inqcount,employeeMasterId ,(select employeemaster.employeeName from employeemaster where employeemaster.employeeMasterId = lead.employeeMasterId) as EmployeeName ,(select count(*) from lead where statusId = 3 and STR_TO_DATE(nextFollowupModifyDate,'%d-%m-%Y')>= STR_TO_DATE('$_REQUEST[FormDate]','%d-%m-%Y') and STR_TO_DATE(nextFollowupModifyDate,'%d-%m-%Y')<=STR_TO_DATE('$_REQUEST[ToDate]','%d-%m-%Y')) as bookedInq  FROM `lead`  " . $where . "   GROUP by employeeMasterId";
    $countstr = "SELECT count(*) as Inqcount,employeeMasterId ,(select employeemaster.employeeName from employeemaster where employeemaster.employeeMasterId = lead.employeeMasterId) as EmployeeName ,(select count(*) from lead as l1 " . $whereA . " and l1.statusId = 3 and l1.employeeMasterId = lead.employeeMasterId ) as bookedInq,(select count(*) from lead as l1 " . $whereA . " and walkin_datetime != '' and l1.employeeMasterId = lead.employeeMasterId ) as walkininquiry,(select count(*) from lead as l1 " . $whereA . " and walkin_datetime != '' and l1.statusId = 3 and l1.employeeMasterId = lead.employeeMasterId and employeeConverted =1 ) as convertedinquiry  FROM `lead`  " . $where . "   GROUP by employeeMasterId";


    $resrowcount = mysqli_query($dbconn,$countstr);
    $resrowc = mysqli_fetch_array($resrowcount);
    $totalrecord = $resrowc['TotalRow'];
    $per_page = $cateperpaging;
    $total_pages = ceil($totalrecord / $per_page);
    $page = $_REQUEST['Page'] - 1;
    $startpage = $page * $per_page;
    $show_page = $page + 1;



    $filterstr = $filterstr . " LIMIT $startpage, $per_page";
// echo $filterstr;


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
                    <th class="desktop">Employee Name</th>
                    <th class="desktop">Total Inquiry </th>
                    <th class="desktop">Walk-in Inquiry </th>
                    <th class="desktop">Walk-in Inquiry Percentage </th>
                     <th class="desktop">Booked Inquiry </th>
                    <th class="desktop">Booked Inquiry Percentage </th>
                   

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
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['Inqcount']; ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['walkininquiry']; ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php $perntage = $rowfilter['walkininquiry'] / $rowfilter['Inqcount'];
            echo number_format($perntage * 100, 2) . '%'; ?> 
                            </div>
                        </td>
                         <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['bookedInq']; ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php $perntage = $rowfilter['bookedInq'] / $rowfilter['walkininquiry'];
            echo number_format($perntage * 100, 2) . '%'; ?> 
                            </div>
                        </td>
                      

                        <?php
                    }
                    ?>

                </tr>
            </tbody>
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