<?php
error_reporting(0);
require_once('../common.php');
include('IsLogin.php');
$connect = new connect();
include ('User_Paging.php');


if ($_POST['action'] == 'ListUser') {
    $where = "";

    if ($_POST['firstName'] != NULL && isset($_POST['firstName']))
        $where.=" and  customerentry.firstName like '%$_POST[firstName]%'";
    if ($_POST['surName'] != NULL && isset($_POST['surName']))
        $where.=" and  customerentry.lastName like '%$_POST[surName]%'";
    if ($_POST['leaduniqueid'] != NULL && isset($_POST['leaduniqueid']))
        $where.=" and  lead.leaduniqueid like '%$_POST[leaduniqueid]%'";

   $filterstr = "SELECT * FROM `lead` inner join customerentry on lead.customerEntryId=customerentry.customerEntryId " . $where . " and lead.statusId = '3' and lead.isRegister = '0' and customerentry.istatus = '1' and lead.employeeMasterId=" . $_SESSION['EmployeeId'] . " and customerentry.isDelete = '0' order by STR_TO_DATE(nextFollowupModifyDate,'%d-%m-%Y') DESC";

    $countstr = "SELECT count(*) as TotalRow FROM `lead` inner join customerentry on lead.customerEntryId=customerentry.customerEntryId " . $where . " and lead.statusId = '3' and customerentry.istatus = '1'  and customerentry.isDelete = '0' and lead.employeeMasterId=" . $_SESSION['EmployeeId'] . "and isRegister=0";

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
                    <th class="desktop">Lead Unique Id</th>
                    <th class="desktop">Student Name</th>
                    <th class="desktop">Address</th>
                    <th class="desktop">Details</th>
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
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['title'] . ' ' . $rowfilter['firstName'] . ' ' . $rowfilter['middleName'] . ' ' . $rowfilter['surName']; ?> 
                            </div>
                        </td>   
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['address']; ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php
                                if ($rowfilter['email'] != '' && $rowfilter['mobileNo'] != '') {
                                    echo 'E:' . $rowfilter['email'] . ' <br>M:' . $rowfilter['mobileNo'];
                                } elseif ($rowfilter['email'] != '') {
                                    echo 'E:' . $rowfilter['email'];
                                } else if ($rowfilter['mobileNo'] != '') {
                                    echo 'M:' . $rowfilter['mobileNo'];
                                } else {
                                    echo '<center>-</center>';
                                }
                                ?>
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input">
                                <a  class="btn blue" href="<?php echo $web_url; ?>Employee/AddNewRegister.php?token=<?php echo $rowfilter['leadId']; ?>&cid=<?php echo $rowfilter['customerEntryId']; ?>" title="STUDENT REGISTRATION"><i class="fa fa-user"></i></a>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <?php ?>
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