<?php
error_reporting(0);
require_once('../common.php');
include('IsLogin.php');
$connect = new connect();
include('User_Paging.php');

if ($_POST['action'] == 'ListUser') {
    //    $where = "and studentadmission.branchId ='" . $_SESSION['branchid'] . "' ";
    if ($_REQUEST['FormDate'] != NULL && isset($_REQUEST['FormDate']))
        $where .= " and DATE_FORMAT(STR_TO_DATE(studentadmission.DOB,'%d-%m-%Y'), '%m-%d') >= DATE_FORMAT(STR_TO_DATE('" . $_REQUEST[FormDate] . "','%d-%m-%Y'), '%m-%d')";

    if ($_REQUEST['ToDate'] != NULL && isset($_REQUEST['ToDate']))
        $where .= " and DATE_FORMAT(STR_TO_DATE(studentadmission.DOB,'%d-%m-%Y'), '%m-%d') <= DATE_FORMAT(STR_TO_DATE('" . $_REQUEST[ToDate] . "','%d-%m-%Y'), '%m-%d')";


    $filterstr = "SELECT studentadmission.studentEnrollment,studentadmission.firstName,studentadmission.surName,studentadmission.DOB,studentadmission.branchId from studentadmission where studentadmission.iStudentStatus=1  " . $where . "
        and istatus=1 and isDelete=0 ORDER BY DATE_FORMAT(STR_TO_DATE(studentadmission.DOB,'%d-%m-%Y'),'%d-%m') ASC";
    $countstr = "SELECT count(*) as TotalRow from studentadmission where studentadmission.iStudentStatus=1  " . $where . "";


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
?>
        <link href="<?php echo $web_url; ?>Supervisor/assets/global/plugins/datatables/datatables.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo $web_url; ?>Supervisor/assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" />
        <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>

        <table class="table table-bordered table-hover center table-responsive dt-responsive" width="100%" id="tableC">
            <thead class="tbg">
                <tr>
                    <th class="all">Sr.No</th>
                    <th class="desktop">Student Enrollment</th>
                    <th class="desktop">Branch</th>
                    <th class="desktop">Student Name</th>
                    <th class="desktop">Birthday Date</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($rowfilter = mysqli_fetch_array($resultfilter)) {
                    $i++;
                    $serial++;
                    $employeeMaster = [];
                    if(isset($rowfilter['branchId']) && $rowfilter['branchId'] > 0){
                        $employeeMaster = mysqli_fetch_array(mysqli_query($dbconn, "select * from branchmaster where branchid = '" . $rowfilter['branchId'] . "'"));
                    }
                ?>
                    <tr>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $serial; ?>
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php
                                                                        echo $rowfilter['studentEnrollment'];
                                                                        ?>
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo !empty($employeeMaster) ? $employeeMaster['branchname'] : '-'; ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php
                                                                        echo $rowfilter['firstName'] . " " . $rowfilter['surName'];
                                                                        ?>
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['DOB']; ?>
                            </div>
                        </td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
        <script src="<?php echo $web_url; ?>Supervisor/assets/global/plugins/datatables/datatables.js" type="text/javascript"></script>
        <script src="<?php echo $web_url; ?>Supervisor/assets/global/plugins/datatables/table-datatables-responsive.js" type="text/javascript"></script>
        <script>
            $(document).ready(function() {
                $('#tableC').DataTable({});
            });
        </script>
    <?php } else {
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
if ($totalrecord > $per_page) {
    ?>
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