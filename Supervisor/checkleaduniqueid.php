<?php
error_reporting(0);
require_once('../config.php');
include('IsLogin.php');
?>
<?php



$leaduniqueid=intval($_GET['leaduniqueid']);
 $result = mysqli_query($dbconn,"SELECT * FROM `lead` WHERE `leaduniqueid`='". $leaduniqueid ."'");
if (mysqli_num_rows($result) > 0) {
     $query =  mysqli_query($dbconn,"SELECT * FROM `lead` inner join customerentry on lead.customerEntryId=customerentry.customerEntryId where `leaduniqueid` = '".$_REQUEST['leaduniqueid']."'");
    $row =  mysqli_fetch_array($query);
}
$result=mysqli_query($dbconn,"select * from city  where  istatus='1' and isDelete='0' and sId=".$sId." order by name ASC");
$data='<select name="City" id="City" class="form-control"  required>
<option value="">Select City</option>';
 while($row=mysqli_fetch_array($result)) { 
	$data.='<option value='.$row['cityid'].'>'.$row['name'].'</option>';
}
$data .='</select>';
echo $data;
?>
