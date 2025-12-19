<?php
ob_start();

?>
<?php
require_once('../config.php');
include('IsLogin.php');

$query="select * from employeemaster where loginId='".$_GET['ID']."' and  isDelete='0'  and  istatus='1'";
$result=mysqli_query($dbconn,$query);
if(mysqli_num_rows($result)>= 1)
{
	echo 'Login ID Already Exits';
       
}
else
{
	echo '0';
}

?>