<?php
error_reporting(0);
require_once('../common.php');
include('IsLogin.php');
$connect = new connect();
include('User_Paging.php');


if ($_POST['action'] == 'ListUser') {

    $where = "where 1=1";

    if ($_POST['courseName'] != NULL && isset($_REQUEST['courseName']))
        $where .= " and  courseName like '%$_POST[courseName]%'";
    if ($_POST['softwareName'] != NULL && isset($_REQUEST['softwareName']))
        $where .= " and  softwareName like '%$_POST[softwareName]%'";

    $filterstr = "SELECT * FROM `studentcoursedetail` left join  software on software.softwareId= studentcoursedetail.`softwareId` join course on studentcoursedetail.courseId=course.courseId  " . $where . " and studentcoursedetail.stud_id=" . $_REQUEST['stud_id'];

    $countstr = "SELECT count(*) as TotalRow FROM `studentcoursedetail`  " . $where . " and studentcoursedetail.stud_id=" . $_REQUEST['stud_id'];

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
                    <th class="desktop">Course Name</th>
                    <th class="desktop">Software Name </th>

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
                            <?php
                            $query =  mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `course` where courseId =" . $rowfilter['courseId']));
                            ?>
                            <div class="form-group form-md-line-input "><?php echo $query['courseName']; ?>
                            </div>
                        </td>
                        <td>
                            <?php
                            $query =  mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `software` where softwareId =" . $rowfilter['softwareId']));
                            ?>
                            <div class="form-group form-md-line-input "><?php echo $query['softwareName']; ?>
                            </div>
                        </td>


                    </tr>
                <?php }  ?>
            </tbody>
        </table>
    <?php } ?>
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


if ($_REQUEST['action'] == 'Delete') {
    $data = array(
        "isDelete" => '1',
        "strEntryDate" => date('d-m-Y H:i:s')
    );
    $where = ' where  	stud_id=' . $_REQUEST['ID'];
    $dealer_res = $connect->updaterecord($dbconn, 'studentadmission', $data, $where);
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