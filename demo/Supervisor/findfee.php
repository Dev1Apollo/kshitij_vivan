<?php
error_reporting(0);
require_once('../config.php');
include('IsLogin.php');
?>
<?php



$courseId=$_GET['cid'];
//echo $courseId;exit;
//echo "SELECT sum(fee) as fee FROM `course`  where courseId in (".$courseId.") order by courseName ASC";
$result=mysqli_query($dbconn,"SELECT sum(fee) as fee FROM `course`  where courseId in (".$courseId.") order by courseName ASC");
$row=mysqli_fetch_array($result);
//print_r($result);
$data='<input type="text" value="'.$row['fee'].'" name="fee" id="fee" readonly="readonly" class="form-control"  placeholder="Enter The fee"  required>';

echo $data;
?>
