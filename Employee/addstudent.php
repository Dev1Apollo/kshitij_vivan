<?php
ob_start();
error_reporting(E_ALL);
require_once '../common.php';
$connect = new connect();
include('IsLogin.php');
if(is_numeric($_GET['id'])){
	$rowFilterData =mysqli_fetch_array(mysqli_query($dbconn, "SELECT iJobId,strCompanyName,strJobTitle,strExperience,iPosition,jobmaster.strEntryDate,jobmaster.iStatus,jobmaster.strJobDescription,jobmaster.strEndDate,jobmaster.iJobCategoryId,(select jobcategory.strJobCategory from jobcategory where jobmaster.iJobCategoryId=jobcategory.iJobCategoryId) as strJobCategory FROM `jobmaster` inner join company  on company.id=jobmaster.iCompanyId where iJobId='".$_GET['id']."' and jobmaster.isDelete='0' and jobmaster.iStatus='1' order by iJobId desc"));
	
} else {
	header('location: ActiveJob.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8" />
	<title><?php echo $ProjectName; ?> | Add Student </title>
	<?php include_once './include.php'; ?>
</head>

<body class="page-container-bg-solid page-boxed">
	<?php include_once './header.php'; ?>
	<div style="display: none; z-index: 10060;" id="loading">
		<img id="loading-image" src="<?php echo $web_url; ?>Employee/images/loader1.gif">
	</div>
	<div class="page-container">
		<div class="page-content-wrapper">
			<?php //include_once './interview_menu.php'; ?>
			<div class="page-content">
				<div class="container">
					<ul class="page-breadcrumb breadcrumb">
						<li>
							<a href="<?php echo $web_url; ?>Employee/index.php">Home</a>
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
												<table class="table table-bordered table-hover center table-responsive" width="100%">
													<tr>
														<th class="desktop">Company Name</th>
														<th class="desktop">Job Category</th>
														<th class="desktop">Job Title</th>
														<th class="desktop">No of Position</th>
														<th class="desktop">Experience</th>
													</tr>
													<tr>
														<td><?= $rowFilterData['strCompanyName'];?></td>
														<td><?= $rowFilterData['strJobCategory'];?></td>
														<td><?= $rowFilterData['strJobTitle'];?></td>
														<td><?= $rowFilterData['iPosition'];?></td>
														<td><?= $rowFilterData['strExperience'];?></td>
													</tr>
												</table>
											</div>
											<div class="col-md-12">
												<form role="form" method="POST" action="" name="frmSearch" id="frmSearch" enctype="multipart/form-data">
													<!-- <div class="form-group col-md-offset-3 col-md-5">
														<input type="text" value="" name="Search_Txt" class="form-control" id="Search_Txt" placeholder="Search Company" required />
													</div>
													<div class="form-actions  col-md-1">
														<a href="#" class="btn blue" onclick="PageLoadData(1);">Search</a>
													</div> -->
													<div class="col-md-12">
														<input type="hidden" value="<?= $_GET['id']; ?>" name="JobId" id="JobId">
														<div class="form-group  col-md-3">
															<input type="text" value="" name="firstName" class="form-control" id="firstName" placeholder="Search First Name " />
														</div>
														<div class="form-group col-md-3">
															<input type="text" value="" name="surName" class="form-control" id="surName" placeholder="Search Sur Name " />
														</div>
														<div class="form-group col-md-3">
															<input type="text" value="" name="studentPortal_Id" class="form-control" id="studentPortal_Id" placeholder="Search Student Portal ID" />
														</div>
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
			var firstName = $('#firstName').val();
			var surName = $('#surName').val();
			var studentPortal_Id = $("#studentPortal_Id").val();
			var JobId = $("#JobId").val();
			$('#loading').css("display", "block");
			$.ajax({
				type: "POST",
				url: "<?php echo $web_url; ?>Employee/AjaxAddStudent.php",
				data: {
					action: 'ListUser',
					Page: Page,
					firstName: firstName,
					surName: surName,
					studentPortal_Id: studentPortal_Id,
					JobId: JobId
				},
				success: function(msg) {
					$("#PlaceUsersDataHere").html(msg);
					$('#loading').css("display", "none");
				},
			});
		} // end of filter
		PageLoadData(1);

		function isNumber(evt) {
			evt = (evt) ? evt : window.event;
			var charCode = (evt.which) ? evt.which : evt.keyCode;
			if (charCode > 31 && (charCode < 48 || charCode > 57)) {
				return false;
			}
			return true;
		}

		function SetStudentData(StudId) {
			var errMsg = 'Are you sure to arrange interview?';
			var JobId = $("#JobId").val();
			if (confirm(errMsg)) {
				$('#loading').css("display", "block");
				$.ajax({
					type: 'POST',
					url: '<?php echo $web_url; ?>Employee/AjaxAddStudent.php',
					data: {action: 'ArrangeInterview', stud_id: StudId,JobId: JobId},
					success: function(response) {
						if (response != 0) {
							$('#loading').css("display", "none");
							//$("#Btnmybtn").attr('disabled', 'disabled');
							alert('Added Sucessfully.');
							window.location.href="";
						}
					}
				});
			}
		}
	</script>
</body>

</html>
