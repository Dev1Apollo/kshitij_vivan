<?php
error_reporting(0);
require_once('../config.php');
include('IsLogin.php');
?>
<?php



$courseId=intval($_GET['cid']);
//echo $courseId;exit;
$result=mysqli_query($dbconn,"SELECT * FROM `software`  where courseId=".$courseId." order by softwareName ASC");
$data='<select name="softwareId" id="softwareId" class="form-control"  required>
<option value="">Select Software</option>';
 while($row=mysqli_fetch_array($result)) { 
	$data.='<option value='.$row['softwareId'].'>'.$row['softwareName'].'</option>';
}
$data .='</select>';
echo $data;
?>
