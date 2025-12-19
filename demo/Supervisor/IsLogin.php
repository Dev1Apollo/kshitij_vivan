<?php
if (!isset($_SESSION['SuperEmployeeId']) && !isset($_SESSION['SuperEmployeeName']) && !isset($_SESSION['Type']) && !isset($_SESSION['EmployeeType'])) {
	header('location:' . $web_url . 'Supervisor/login.php');
}
