<?php
error_reporting(0);
require_once('../common.php');
include('IsLogin.php');
$connect = new connect();
include ('User_Paging.php');


if ($_POST['action'] == 'ListUser') {
  
//    echo $_POST[courseName]."<br>";
    $where = "where 1=1";
    
    if ($_POST['stud_id'] != NULL && isset($_REQUEST['stud_id'])) 
         $where.=" and  studentemidetail.stud_id =".$_POST[stud_id];
    if ($_POST['courseName'] != NULL && isset($_REQUEST['courseName'])) 
         $where.=" and  course.courseId = ".$_POST['courseName'];

    $filterstr = "SELECT studentemidetail.* ,studentemidetail.emiAmount as Emi ,studentcourse.* from  studentemidetail join studentcourse on studentcourse.studentcourseId = studentemidetail.studentcourseId inner join course on studentcourse.courseId = course.courseId ".$where." and studentcourse.istatus=1 and studentemidetail.isDelete=0 ORDER BY studentemidetail.studemiId ASC";
//   echo $filterstr ="SELECT studentemidetail.* ,studentcourse.* from  studentemidetail join studentcourse on studentcourse.studentcourseId = studentemidetail.studentcourseId inner join course on studentcourse.courseId = course.courseId ".$where." order by STR_TO_DATE(studentemidetail.emiDate,'%d-%m-%Y') asc";
    $countstr = "SELECT count(*) as TotalRow FROM `studentemidetail`".$where ." and studentemidetail.isDelete=0";

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
<?php 
 $courseId=$_POST['courseName'];
  $stud_id= $_POST['stud_id'];
$getstudentId=  mysqli_query($dbconn,"select *  from studentcourse where stud_id = '".$stud_id."' and studentcourse.istatus=1 and courseId='".$courseId."'");

while($getcousreid = mysqli_fetch_array($getstudentId))
{
    $filterRegFee = "select *  from  studentfee where stud_id = '".$stud_id."' and  feetype = 'Registration_Amount'";
    $rowRegFee = mysqli_query($dbconn,$filterRegFee);
    $regFee = 0;
    $i=0;
    while($dataRegFee = mysqli_fetch_array($rowRegFee)){
        $regFee = $dataRegFee['amount'] + $regFee; 
        $i++;
    }
    $studcourseId = $getcousreid['studentcourseId'];

$query=  mysqli_fetch_array(mysqli_query($dbconn,"Select studentcourse.`offeredfee`,studentcourse.emiAmount ,studentemidetail.booking_amount,studentemidetail.joinAmount ,sum((select `emiAmount` from  studentemidetail stud where studentemidetail.studemiId=stud.studemiId and isDelete=0))as totalemiAmount , sum((select actualReceivedAmount from studentemidetail stud where studentemidetail.studemiId=stud.studemiId)) as actualReceivedAmount  from studentcourse join studentemidetail on studentemidetail.studentcourseId = studentcourse.studentcourseId where studentemidetail.stud_id = '".$stud_id."' and studentcourse.istatus=1 and studentemidetail.isDelete=0 and studentemidetail.studentcourseId = '". $studcourseId ."' group by studentemidetail.studentcourseId"));

$emiAmount = $query['emiAmount'];
$totalemiAmount = $query['totalemiAmount']; 
$actualReceivedAmount = $query['actualReceivedAmount'];
$offeredAmt= $query['offeredfee'];
//$booking_amount = $query['booking_amount'];
$joinAmount = $query['joinAmount'];
$totalemiAmount= $totalemiAmount+$regFee;
//$unpaidamt =  $totalemiAmount - $actualReceivedAmount;

$filterstudentfee = "SELECT * from studentfee where studentcourseId = '" . $studcourseId . "' and stud_id = '" . $stud_id . "' and feetype='Join_Amount'";
    $rowstudentfee = mysqli_fetch_array(mysqli_query($dbconn,$filterstudentfee));
    
        $receivedamount =  $actualReceivedAmount + $regFee + $rowstudentfee['amount'];
        $unpaidamt = $totalemiAmount - $actualReceivedAmount - $rowstudentfee['amount'] ;
} 
echo '<div class="col-md-6"><h4> Actual Amount :'.$offeredAmt.'</h4></div>';
echo '<div class="col-md-6"><h4> Received Amount :'.$receivedamount.'</h4></div>';
echo '<div class="col-md-6"><h4> Remaining Amount :'.$unpaidamt.'</h4></div>';
echo '<div class="col-md-6"><h4>EMI : '.$emiAmount.'</h4></div>';
echo '<div class="col-md-6"><h4>Joining Amount : '.$joinAmount.'</h4></div>';
echo '<div class="col-md-6"><h4>Registration Amount : '.$regFee.'</h4></div>';
?>
    <table class="table table-bordered table-hover center table-responsive dt-responsive" width="100%" id="tableC">
        <thead class="tbg">
            <tr>
                <th class="all">Sr.No</th>

                <th class="desktop">Emi Date</th>
                <th class="desktop">Emi Amount</th>
                <th class="desktop">Actual Recived Amount</th>
                <th class="desktop">Actual Received Date</th>
                <th class="desktop">Comment</th>

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
                   
                    <div class="form-group form-md-line-input "><?php echo $rowfilter['Emi']; ?> 
                    </div>
                </td>
                <td>
                    <div class="form-group form-md-line-input "><?php echo $rowfilter['actualReceivedAmount']; ?>
                        </div>
                </td>
                <td>
                    <div class="form-group form-md-line-input "><?php echo $rowfilter['emiReceivedDate']; ?>
                        </div>
                </td>
                <td>
                    <div class="form-group form-md-line-input "><?php echo $rowfilter['comments']; ?>
                        </div>
                </td>
<!--                <td>
                    <div class="form-group form-md-line-input">
                        <a  class="btn blue" href="<?php echo $web_url; ?>Employee/EditEmi.php?token=<?php echo $rowfilter['studemiId']; ?>" title="EDIT STUDENT"><i class="fa fa-edit"></i></a>

                        </div>
                    </td>-->

            </tr>
                    <?php }  ?>
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
