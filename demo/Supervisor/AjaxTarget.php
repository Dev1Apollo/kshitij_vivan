<?php
error_reporting(0);
require_once('../common.php');
include('IsLogin.php');
$connect = new connect();
include('User_Paging.php');


if ($_POST['action'] == 'ListUser') {
    $where = "where 1=1";

    // if ($_REQUEST['targetMonth'] != NULL && isset($_REQUEST['targetMonth']))
    //     $where .=" and month=" . $_REQUEST['targetMonth'] . " ";
    //        $where.=" and DATE_FORMAT(STR_TO_DATE(strEntryDate, '%d-%m-%Y'), '%Y-%m-%d')>=STR_TO_DATE('" . $_REQUEST['FormDate'] . "','%d-%m-%Y')";
    // if ($_REQUEST['targetYear'] != NULL && isset($_REQUEST['targetYear']))
    //     $where .=" and year=" . $_REQUEST['targetYear'] . " ";
    //        $where.=" and DATE_FORMAT(STR_TO_DATE(strEntryDate, '%d-%m-%Y'), '%Y-%m-%d')<=STR_TO_DATE('" . $_REQUEST['ToDate'] . "','%d-%m-%Y')";
    if ($_REQUEST['FormDate'] != NULL && isset($_REQUEST['FormDate'])) {
        //$where.=" and DATE_FORMAT(STR_TO_DATE(strEntryDate, '%d-%m-%Y'), '%Y-%m-%d')>=STR_TO_DATE('" . $_REQUEST['FormDate'] . "','%d-%m-%Y')";
        $where .= " and  month>=MONTH(STR_TO_DATE('" . $_REQUEST['FormDate'] . "','%d-%m-%Y')) and year>=YEAR(STR_TO_DATE('" . $_REQUEST['FormDate'] . "','%d-%m-%Y'))";
    }
    if ($_REQUEST['ToDate'] != NULL && isset($_REQUEST['ToDate'])) {
        //$where.=" and DATE_FORMAT(STR_TO_DATE(strEntryDate, '%d-%m-%Y'), '%Y-%m-%d')<=STR_TO_DATE('" . $_REQUEST['ToDate'] . "','%d-%m-%Y')";
        $where .= " and  month<=MONTH(STR_TO_DATE('" . $_REQUEST['ToDate'] . "','%d-%m-%Y')) and year<=YEAR(STR_TO_DATE('" . $_REQUEST['ToDate'] . "','%d-%m-%Y'))";
    }

    if ($_REQUEST['ToDate'] != NULL && isset($_REQUEST['ToDate'])) {
        $where .= " and  month<=MONTH(STR_TO_DATE('" . $_REQUEST['ToDate'] . "','%d-%m-%Y')) and year<=YEAR(STR_TO_DATE('" . $_REQUEST['ToDate'] . "','%d-%m-%Y'))";
    }

    if ($_REQUEST['branch'] != NULL && isset($_REQUEST['branch'])) {
        $where .= " and  iBranchId='" . $_REQUEST['branch'] . "'";
    }


    $filterstr = "SELECT * FROM target  " . $where . "  and isDelete='0'  and  iStatus='1' order by itargetId desc";
    $countstr = "SELECT count(*) as TotalRow FROM target  " . $where . " and isDelete='0' and  iStatus='1'";

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
                    <th class="desktop">Month / Year</th>
                    <th class="desktop">Target Inquiry / Walking</th>
                    <th class="desktop">Target Enrollment</th>
                    <th class="desktop">Target Booking</th>
                    <th class="desktop">Target Collection</th>
                    <th class="desktop">Target FPS</th>
                    <th class="desktop">Branch</th>

                    <!--                    <th class="desktop">Action</th>-->
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
                            <div class="form-group form-md-line-input "><?php
                                                                        $monthNum = $rowfilter['month'];
                                                                        $dateObj = DateTime::createFromFormat('!m', $monthNum);
                                                                        $monthName  = $dateObj->format('M');
                                                                        echo $monthName . " / " . $rowfilter['year'];
                                                                        ?>
                            </div>
                        </td>
                        <!--                        <td>
                            <div class="form-group form-md-line-input "><?php // echo $rowfilter['year']; 
                                                                        ?>
                            </div>
                        </td>-->
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['targetInquiry']; ?>
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['targetEnroll']; ?>
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['targetBooking']; ?>
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['targetCollection']; ?>
                            </div>
                        </td>

                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['targetFPS']; ?>
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php
                                                                        $branch = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `branchmaster` where branchid = " . $rowfilter['iBranchId'] . ""));
                                                                        echo $branch['branchname'];
                                                                        ?>
                            </div>
                        </td>

                        <!--                        <td>
                <div class="form-group form-md-line-input">
                        <?php
                        //                                if ($rowfilter['isFreeze'] == 0) {
                        ?>
                        <a  class="btn blue" href="<?php echo $web_url; ?>Supervisor/EditTarget.php?token=<?php echo $rowfilter['itargetId']; ?>" title="Edit"><i class="fa fa-edit iconshowFirst"></i></i></a>
                        <a  class="btn blue" onClick="javascript: return deletedata('Delete', '<?php echo $rowfilter['itargetId']; ?>');"   title="Delete"><i class="fa fa-trash-o iconshowFirst"></i></a>
                        <a  class="btn blue" onClick="javascript: return FreezeTarget('FreezeTarget', '<?php echo $rowfilter['itargetId']; ?>');"   title="Freeze Target"><i class="fa fa-lock iconshowFirst"></i></a>
                        <?php // }  
                        ?>
                </div>
            </td>-->
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

if ($_REQUEST['action'] == 'Delete') {
    $data = array(
        "isDelete" => '1',
        "strEntryDate" => date('d-m-Y H:i:s')
    );
    $where = ' where itargetId=' . $_REQUEST['ID'];
    $dealer_res = $connect->updaterecord($dbconn, 'target', $data, $where);
}

if ($_REQUEST['action'] == 'FreezeTarget') {
    $data = array(
        "isFreeze" => '1',
        "strEntryDate" => date('d-m-Y H:i:s')
    );
    $where = ' where itargetId=' . $_REQUEST['ID'];
    $dealer_res = $connect->updaterecord($dbconn, 'target', $data, $where);
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