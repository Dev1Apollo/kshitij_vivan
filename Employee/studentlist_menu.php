<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<style>
	.w3-border-red {
		background-color: #df9f9a;
		color: #000;
		border-color: #eb1000!important;
	}
</style>
<div class="w3-row">
	<a href="RequiredStudentList.php">
		<div class="w3-third tablink w3-bottombar w3-hover-light-grey w3-padding <?php
																					if (basename($_SERVER['REQUEST_URI']) == "RequiredStudentList.php") {
																						echo
																						'w3-border-red';
																					}
																					?>">Required Student List</div>
	</a>
	<a href="NotRequiredStudentList.php">
		<div class="w3-third tablink w3-bottombar w3-hover-light-grey w3-padding <?php
																					if (basename($_SERVER['REQUEST_URI']) == "NotRequiredStudentList.php") {
																						echo
																						'w3-border-red';
																					}
																					?>">Not Required Student List</div>
	</a>
	<a href="PlacedStudentList.php">
		<div class="w3-third tablink w3-bottombar w3-hover-light-grey w3-padding <?php
																					if (basename($_SERVER['REQUEST_URI']) == "PlacedStudentList.php") {
																						echo
																						'w3-border-red';
																					}
																					?>">Placed Student List</div>
	</a>
</div>
