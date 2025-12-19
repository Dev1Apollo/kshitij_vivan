<?php 
if(!isset($_SESSION['EmployeeId']) && !isset($_SESSION['EmployeeName']) && !isset($_SESSION['Type']) && !isset($_SESSION['EmployeeType']))
{
	header('location:'.$web_url.'Employee/login.php');	
}

?>