<?php
error_reporting(0);
require_once('../common.php');
include('IsLogin.php');
$connect = new connect();

//$courseId = $_GET['cid'];
$stud_id = intval($_GET['stud_id']);
$studentcourseId = intval($_GET['studentcourseId']);

$getstudentId = mysqli_query($dbconn, "select *  from studentcourse where stud_id='" . $stud_id . "' and studentcourse.istatus=1 and studentcourseId='" . $studentcourseId . "' ");

while ($getcousreid = mysqli_fetch_array($getstudentId)) {
    $studcourseId = $getcousreid['studentcourseId'];
    $filterRegFee = "select *  from  studentfee where stud_id = '" . $stud_id . "' and studentcourseId = '" . $studcourseId . "' and feetype = '1' and isCancel=0";
    $rowRegFee = mysqli_query($dbconn, $filterRegFee);
    $regFee = 0;
    $i = 0;
    while ($dataRegFee = mysqli_fetch_array($rowRegFee)) {
        $regFee = $dataRegFee['amount'] + $regFee;
        $i++;
    }
    $query = mysqli_fetch_array(mysqli_query($dbconn, "Select studentcourse.offeredfee,studentcourse.emiAmount ,studentemidetail.booking_amount,studentemidetail.joinAmount ,sum((select emiAmount from  studentemidetail stud where studentemidetail.studemiId=stud.studemiId))as totalemiAmount , sum((select actualReceivedAmount from studentemidetail stud where studentemidetail.studemiId=stud.studemiId)) as actualReceivedAmount  from studentcourse join studentemidetail on studentemidetail.studentcourseId = studentcourse.studentcourseId where studentemidetail.stud_id = '" . $stud_id . "' and studentcourse.istatus=1 and studentemidetail.isDelete=0 and studentemidetail.studentcourseId = '" . $studcourseId . "' group by studentemidetail.studentcourseId"));
    $emiAmount = $query['emiAmount'];
    $totalemiAmount = $query['totalemiAmount'];
    //    $actualReceivedAmount = $query['actualReceivedAmount'];
    $offeredAmt = $query['offeredfee'];
    $joinAmount = $query['joinAmount'];
    $totalAmount = $totalemiAmount;

    $filterstudentfee = "SELECT sum(amount) as amount from studentfee where studentcourseId = '" . $studcourseId . "' and stud_id = '" . $stud_id . "' and feetype not in (1,4) and isCancel=0 Group by stud_id,studentcourseId";
    $rowstudentfee = mysqli_fetch_array(mysqli_query($dbconn, $filterstudentfee));

    $receivedamount = $regFee + $rowstudentfee['amount'];
    $unpaidamt = $offeredAmt - $receivedamount;
}

$dataemi = '<div class="col-md-4"> Actual Amount :<strong>' . $offeredAmt . '</strong></div>';
$dataemi .= '<div class="col-md-4"> Received Amount :<strong>' . $receivedamount . '</strong></div>';
$dataemi .= '<div class="col-md-4"> Remaining Amount :<strong>' . $unpaidamt . '</strong></div>';
//$dataemi .=' <div class="col-md-6"><h4> EMI : ' . $emiAmount . '</h4></div>';
//$dataemi .=' <div class="col-md-6"><h4> Joining Amount : ' . $joinAmount . '</h4></div>';
//$dataemi .=' <div class="col-md-6"><h4> Registration Amount : ' . $regFee . '</h4></div>';
$dataemi .= '<input type="hidden" value=" ' . $studcourseId . '" name="studentcourseId" id="studentcourseId">';

$dataemi .= "##@@##";
$querysInq = "SELECT * FROM studentemidetail where stud_id='" . $stud_id . "' and isPaid=0 and studentcourseId='" . $studcourseId . "' order by studemiId asc";
$resultsInq = mysqli_query($dbconn, $querysInq) or die(mysqli_error($dbconn));


$dataemi .= '<select name="studemiId[]" id="emiType" style="display:none;" class="form-control" multiple="multiple" required="">';
while ($rowsInq = mysqli_fetch_array($resultsInq)) {
    $dueAmount = $rowsInq['emiAmount'] - $rowsInq['actualReceivedAmount'];

    $dataemi .= '<option value="' . $rowsInq['studemiId'] . '"> ' . $rowsInq['emiDate'] . "(" . $dueAmount . ")" . ' </option>';
}
$dataemi .= '</select>';
echo $dataemi;
?>
<style>
    .multiselect {
        display: block;
        height: 35px;
        padding: 6px;

        text-align: left !important;
        line-height: 1.42857;
        color: #DFDFDF;
        background-color: #fff;
        background-image: none;
        border: 1px solid #51c6dd !important;
        border-radius: 4px;
        color: #666;
        font-size: 15px;
        font-weight: normal !important;
        text-transform: lowercase;

    }
</style>
<link href="demo/assets/bootstrap-multiselect.css" rel="stylesheet" type="text/css" />
<script src="demo/assets/bootstrap-multiselect.js" type="text/javascript"></script>
<script>
    $(document).ready(function() {
        $('#emiType').multiselect({
            nonSelectedText: 'Select Emi Date',
            includeSelectAllOption: true,
            buttonWidth: '100%',
        });
    });


    //$dueAmount = $resultsInq['emiAmount'] - $resultsInq['actualReceivedAmount'];  "(" . $dueAmount . ")"