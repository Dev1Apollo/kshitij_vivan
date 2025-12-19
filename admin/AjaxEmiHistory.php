<?php
error_reporting(0);
require_once('../common.php');
include('IsLogin.php');
$connect = new connect();
include ('User_Paging.php');

if ($_POST['action'] == 'ListUser') {
    $where = "where 1=1";
    if ($_POST['stud_id'] != NULL && isset($_REQUEST['stud_id']))
        $where.=" and  studentemidetail.stud_id =" . $_POST[stud_id];
    if ($_POST['studentcourseId'] != NULL && isset($_REQUEST['studentcourseId']))
        $where.=" and  studentcourse.studentcourseId = " . $_POST['studentcourseId'];
    
    $whereFee = " 1=1 ";
    if ($_POST['stud_id'] != NULL && isset($_REQUEST['stud_id']))
        $whereFee.=" and  studentfee.stud_id =" . $_POST[stud_id];
    if ($_POST['studentcourseId'] != NULL && isset($_REQUEST['studentcourseId']))
        $whereFee.=" and  studentfee.studentcourseId = " . $_POST['studentcourseId'];

    $filterstr = "SELECT count(*) as count, sum(studentemidetail.emiAmount) as Emi,max(studentemidetail.emiDate) as emiDate "
            . " ,month(STR_TO_DATE(studentemidetail.emiDate,'%d-%m-%Y')) as Mon "
            . " ,year(STR_TO_DATE(studentemidetail.emiDate,'%d-%m-%Y')) as Yea "
            . " ,(select sum(studentfee.amount) from studentfee where month(STR_TO_DATE(studentfee.payDate,'%d-%m-%Y')) = month(STR_TO_DATE(studentemidetail.emiDate,'%d-%m-%Y')) and  year(STR_TO_DATE(studentfee.payDate,'%d-%m-%Y')) = year(STR_TO_DATE(studentemidetail.emiDate,'%d-%m-%Y')) "
            . " and ".$whereFee." and studentfee.feetype in (2,5) ) as ReceivedAmount "
            . " ,(select max(studentfee.payDate) from studentfee where month(STR_TO_DATE(studentfee.payDate,'%d-%m-%Y')) = month(STR_TO_DATE(studentemidetail.emiDate,'%d-%m-%Y')) and year(STR_TO_DATE(studentfee.payDate,'%d-%m-%Y')) = year(STR_TO_DATE(studentemidetail.emiDate,'%d-%m-%Y')) "
            . " and ".$whereFee." and studentfee.feetype in (2,5)) as ReceuvedDate"
            . " from studentemidetail join studentcourse on studentcourse.studentcourseId = studentemidetail.studentcourseId inner join course on studentcourse.courseId = course.courseId ".$where." and studentemidetail.isDelete=0"
            . " and studentcourse.istatus=1  and studentemidetail.isDelete=0 group by month(STR_TO_DATE(studentemidetail.emiDate,'%d-%m-%Y')),year(STR_TO_DATE(studentemidetail.emiDate,'%d-%m-%Y')) ORDER BY studentemidetail.studemiId ASC";

    $countstr = "SELECT count(*) as TotalRow FROM `studentemidetail` " . $where;

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
        <link href="<?php echo $web_url; ?>Employee/assets/global/plugins/datatables/datatables.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo $web_url; ?>Employee/assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" />
        <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script> 
        <table class="table table-bordered table-hover center table-responsive dt-responsive" width="100%" id="tableC">
            <thead class="tbg">
                <tr>
                    <th class="all">Sr.No</th>
                    <th class="desktop">Emi Date</th>
                    <th class="desktop">Particular</th>
                    <th class="desktop">Emi Amount</th>
                    <th class="desktop">Actual Recived Amount</th>
                    <th class="desktop">Due Amount</th>
                    <th class="desktop">Actual Received Date</th>
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
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['emiDate']; ?>
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php
                                if ($rowfilter['count'] == 2) {
                                    echo "Joining Amount,Emi Amount";
                                } else {
                                    echo "Emi Amount";
                                }
                                ?>
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['Emi']; ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php
                                echo $rowfilter['ReceivedAmount'];
                                ?>
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input">
                                <?php
                                $due = $rowfilter['Emi'] - $rowfilter['ReceivedAmount'];
                                echo $due;
                                ?>
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php
                                echo $rowfilter['ReceuvedDate'];
                                ?>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } ?>
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
 if ($totalrecord > $per_page) { ?>
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