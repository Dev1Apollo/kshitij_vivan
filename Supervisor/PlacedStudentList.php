<?php
ob_start();
error_reporting(E_ALL);
require_once '../common.php';
$connect = new connect();
include('IsLogin.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8" />
	<title><?php echo $ProjectName; ?> | Student List </title>
	<?php include_once './include.php'; ?>
</head>

<body class="page-container-bg-solid page-boxed">
	<?php include_once './header.php'; ?>
	<div style="display: none; z-index: 10060;" id="loading">
		<img id="loading-image" src="<?php echo $web_url; ?>Supervisor/images/loader1.gif">
	</div>
	<div class="page-container">
		<div class="page-content-wrapper">
			<?php include_once './studentlist_menu.php'; ?>
			<div class="page-content">

				<div class="container">

					<ul class="page-breadcrumb breadcrumb">
						<li>
							<a href="<?php echo $web_url; ?>Supervisor/index.php">Home</a>
							<i class="fa fa-circle"></i>
						</li>
						<li>
							<span>Student List</span>
						</li>
					</ul>
					<div class="page-content-inner">
						<div class="col-md-2">
							<?php include_once './menu-placement.php'; ?>
						</div>
						<div class="col-md-10">
							<div class="col-md-12">
								<div class="portlet light ">
									<div class="portlet-title">
										<div class="caption grey-gallery">
											<i class="icon-settings grey-gallery"></i>
											<span class="caption-subject bold uppercase">List of Student</span>
										</div>
									</div>
									<div class="portlet-body form">
										<div class="row m-search-box">
											<div class="col-md-12">
												<form role="form" method="POST" action="" name="frmSearch" id="frmSearch" enctype="multipart/form-data">
													<!-- <div class="form-group col-md-offset-3 col-md-5">
														<input type="text" value="" name="Search_Txt" class="form-control" id="Search_Txt" placeholder="Search Company" required />
													</div>
													<div class="form-actions  col-md-1">
														<a href="#" class="btn blue" onclick="PageLoadData(1);">Search</a>
													</div> -->
													<div class="col-md-12">
														<div class="form-group  col-md-3">
															<input type="text" value="" name="Search_Company" class="form-control" id="Search_Company" placeholder="Search Company" />
														</div>
														<div class="form-group col-md-3">
															<input type="text" value="" name="Search_Student" class="form-control" id="Search_Student" placeholder="Search Student" />
														</div>
														<!-- <div class="form-group col-md-3">
															<select name="Search_Status" id="Search_Status" class="form-control" required="">
																<option value="">Select Status</option>
																<option value="0">Required</option>
																<option value="1">Not Required</option>
																<option value="2">Placed</option>
															</select>
														</div> -->

														<div class="form-group  col-md-2">
															<a href="#" class="btn btn-block blue pull-right" onclick="PageLoadData(1);"><i class="fa fa-search"></i>&nbsp;&nbsp;Search</a>
														</div>
													</div>
												</form>
											</div>
										</div>
										<div id="PlaceUsersDataHere">

										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php include_once './footer.php'; ?>
	<script type="text/javascript">
		function checkclose() {
			window.location.href = '';
		}

		function PageLoadData(Page) {
			var Search_Company = $('#Search_Company').val();
			var Search_Student = $('#Search_Student').val();
			var Search_Status = $("#Search_Status").val();

			$('#loading').css("display", "block");
			$.ajax({
				type: "POST",
				url: "<?php echo $web_url; ?>Supervisor/AjaxPlacedStudentList.php",
				data: {
					action: 'ListUser',
					Page: Page,
					Search_Company: Search_Company,
					Search_Student: Search_Student,
					Search_Status: Search_Status
				},
				success: function(msg) {
					$("#PlaceUsersDataHere").html(msg);
					$('#loading').css("display", "none");
				},
			});
		} // end of filter
		PageLoadData(1);

		function sentToRequired(stud_id, iJobSubmissionId) {
			$('#loading').css("display", "block");
			$.ajax({
				type: "POST",
				url: "<?php echo $web_url; ?>Supervisor/AjaxPlacedStudentList.php",
				data: {
					action: 'MovetoRequiredList',
					stud_id: stud_id,
					iJobSubmissionId: iJobSubmissionId
				},
				success: function(msg) {
					alert(msg);
					//$("#PlaceUsersDataHere").html(msg);
					$('#loading').css("display", "none");
					if (msg != 0) {
						alert('Moved Sucessfully.');
						window.location.href = "";
					}
				},
			});
		}
	</script>
</body>

</html>