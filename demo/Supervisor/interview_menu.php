<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<style>
	.w3-border-red {
		background-color: #df9f9a;
		color: #000;
		border-color: #eb1000 !important;
	}

	@media (min-width: 601px) {
		.w3-col.m4,
		.w3-third {
			width: 50% !important;
		}
	}
</style>
<div class="w3-row">
	<a href="addstudent.php?id=<?= $_GET['id'] ?>">
		<div class="w3-third tablink w3-bottombar w3-hover-light-grey w3-padding <?php
																					if (basename($_SERVER['REQUEST_URI']) == "addstudent.php?id=".$_GET['id']."") {
																						echo
																						'w3-border-red';
																					}
																					?>">Arrange Interview</div>
	</a>
	<a href="studentInterview.php?id=<?= $_GET['id'] ?>">
		<div class="w3-third tablink w3-bottombar w3-hover-light-grey w3-padding <?php
																					if (basename($_SERVER['REQUEST_URI']) == "studentInterview.php?id=".$_GET['id']."") {
																						echo
																						'w3-border-red';
																					}
																					?>">Interview</div>
	</a>
</div>
