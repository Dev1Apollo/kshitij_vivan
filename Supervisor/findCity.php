<?php
error_reporting(0);
require_once('../config.php');
include('IsLogin.php');
?>
<?php



$sId=intval($_GET['sId']);
$result=mysqli_query($dbconn,"select * from city  where  istatus='1' and isDelete='0' and sId=".$sId." order by name ASC");
$data='<select name="City" id="City" class="form-control"  required>
<option value="">Select City</option>';
 while($row=mysqli_fetch_array($result)) { 
	$data.='<option value='.$row['cityid'].'>'.$row['name'].'</option>';
}
$data .='</select>';
echo $data;
?>
