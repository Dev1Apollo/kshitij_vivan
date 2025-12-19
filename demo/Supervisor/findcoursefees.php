<?php
error_reporting(0);
require_once('../config.php');
include('IsLogin.php');
?>
<?php



$courseId=intval($_GET['cid']);
//echo $courseId;exit;
$result=mysqli_query($dbconn,"SELECT * FROM `course`  where courseId=".$courseId." order by courseName ASC");
$row=mysqli_fetch_array($result);
//print_r($result);
$data='<input type="text" value="'.$row['fee'].'" name="fee" id="fee" class="form-control"  placeholder="Enter The fee"  required>';

echo $data;
?>
