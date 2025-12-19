<?php
error_reporting(0);
require_once('../config.php');
include('IsLogin.php');
?>
<?php



$bId=intval($_GET['bId']);
$result=mysqli_query($dbconn,"SELECT * FROM `employeemaster`  where isDelete='0' and employeeReportTo=1 and branchid='".$bId."' order by employeeMasterId asc");
$data='<select class="form-control" name="employeeMasterId" id="employeeMasterId" required><option value="">Select Employee</option>';
 while($row=mysqli_fetch_array($result)) { 
	$data.='<option value='.$row['employeeMasterId'].'>'.$row['employeeName'].'</option>';
}
$data .='</select>';
echo $data;
?>
