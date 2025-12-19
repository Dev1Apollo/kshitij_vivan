<?php
error_reporting(0);
require_once('../common.php');
include('IsLogin.php');
$connect = new connect();
include ('User_Paging.php');


if ($_POST['action'] == 'ListUser') {
    $where = "where 1=1";

    if ($_REQUEST['FormDate'] != NULL && isset($_REQUEST['FormDate']))
        $where.=" and STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y')>= STR_TO_DATE('$_REQUEST[FormDate]','%d-%m-%Y')";
    if ($_REQUEST['ToDate'] != NULL && isset($_REQUEST['ToDate']))
        $where.=" and STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y')<=STR_TO_DATE('$_REQUEST[ToDate]','%d-%m-%Y')";
    if ($_REQUEST['Employee'] != NULL && isset($_REQUEST['Employee']))
        $where.=" and lead.employeeMasterId='" . $_POST['Employee'] . "'";
//    if ($_REQUEST['branch'] != NULL && isset($_REQUEST['branch']))
//        $where.= " and employeeMasterId in (select branchid from employeemaster where employeemaster.branchid=".$_REQUEST['branch']." and istatus=1 and isDelete=0 )";
    if ($_REQUEST['branch'] != NULL && isset($_REQUEST['branch']))
        $where = " and employeemaster.branchid =" . $_REQUEST['branch'] . " ";
    if ($_REQUEST['InquirySource'] != NULL && isset($_REQUEST['InquirySource']))
        $where.=" and customerEntryId in (select customerEntryId from customerentry where inquirySourceId in (" . implode(',', $_POST['InquirySource']) . "))";

    $filterstr = "SELECT * FROM `lead` INNER JOIN employeemaster ON employeemaster.employeeMasterId = lead.employeeMasterId ".$where." order by  STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y') desc";
    $countstr = "SELECT count(*) as TotalRow FROM `lead` INNER JOIN employeemaster ON employeemaster.employeeMasterId = lead.employeeMasterId ".$where." ";

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
        <link href="<?php echo $web_url; ?>Employee/assets/global/plugins/datatables/datatables.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo $web_url; ?>Employee/assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" />
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
                    <th class="desktop">Action</th>
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
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['leaduniqueid']; ?> 
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
                                echo $rowfilter['employeeName'];
                                ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php
                                $get_source = mysqli_fetch_array(mysqli_query($dbconn,"select * from inquirysource where inquirySourceId = '" . $rowCustomer['inquirySourceId'] . "'"));
                                echo $get_source['inquirySourceName'];
                                ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php
                                echo $rowfilter['inquiryEnterDate'];
                                ?> 
                            </div>
                        </td> 
                        <td>
                            <div class="form-group form-md-line-input "><?php
                                $inquiryStatus = mysqli_fetch_array(mysqli_query($dbconn,"SELECT * FROM `status`  where isDelete='0'  and  istatus='1' and statusId='" . $rowfilter['statusId'] . "'"));
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
                        <td>
                            <div class="form-group form-md-line-input">
                                <?php
                                if ($rowfilter['statusId'] != 3) {
                                    ?>
                                    <input type="button" class="btn blue" value="="  title="INQUIRY FOLLOWUP" onclick="window.open('<?php echo $web_url; ?>admin/InquiryFollowupList.php?token=<?php echo $rowfilter['leadId']; ?>&cid=<?php echo $rowfilter['customerEntryId']; ?>', 'popUpWindow', 'height=500,width=1250,left=100,top=100,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no,status=yes');">

                <!--                                <a  class="btn blue" href="<?php // echo $web_url;   ?>admin/InquiryFollowupList.php?token=<?php // echo $rowfilter['leadId'];   ?>" title="INQUIRY FOLLOWUP"><i class="fa fa-bars"></i></a>-->
                                <?php } ?>
                                <a  class="btn blue" onClick="javascript: return deletedata('Delete', '<?php echo $rowfilter['customerEntryId']; ?>');"   title="Delete"><i class="fa fa-trash-o iconshowFirst"></i></a>
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

if ($_REQUEST['action'] == 'Delete') {
    $data = array(
        "isDelete" => '1',
        "strEntryDate" => date('d-m-Y H:i:s')
    );
    $where = ' where  	customerEntryId=' . $_REQUEST['ID'];
    $dealer_res = $connect->updaterecord($dbconn,'customerentry', $data, $where);

    mysqli_query($dbconn,"delete from lead where customerEntryId='" . $_REQUEST['ID'] . "'");
    mysqli_query($dbconn,"delete from leadfollowup where customerEntryId='" . $_REQUEST['ID'] . "'");
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