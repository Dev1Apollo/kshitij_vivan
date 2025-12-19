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
	<title><?php echo $ProjectName; ?> | Active Job </title>
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
	<?php include_once './include.php'; ?>
</head>

<body class="page-container-bg-solid page-boxed">
	<?php include_once './header.php'; ?>
	<div style="display: none; z-index: 10060;" id="loading">
		<img id="loading-image" src="<?php echo $web_url; ?>Employee/images/loader1.gif">
	</div>
	<div class="page-container">

		<div class="page-content-wrapper">
			<div class="page-content">
				<div class="container">
					<ul class="page-breadcrumb breadcrumb">
						<li>
							<a href="<?php echo $web_url; ?>Employee/index.php">Home</a>
							<i class="fa fa-circle"></i>
						</li>
						<li>
							<span>Active Job</span>
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
											<span class="caption-subject bold uppercase">List of Active Job</span>
										</div>
									</div>
									<div class="portlet-body form">
										<div class="row m-search-box">
											<div class="col-md-12">
												<form role="form" method="POST" action="" name="frmSearch" id="frmSearch" enctype="multipart/form-data">
													<div class="form-group col-md-offset-1 col-md-4">
														<input type="text" value="" name="Search_Company" class="form-control" id="Search_Company" placeholder="Search Company" />
													</div>
													<div class="form-group col-md-4">
														<select type="text" name="Search_Category" class="form-control" id="Search_Category" placeholder="Search Job Category">
															<option value="">Select Job Category</option>
															<?php
															$filterJobCat = mysqli_query($dbconn, "SELECT * FROM `jobcategory` where isDelete=0 and iStatus=1");
															while ($rowData = mysqli_fetch_assoc($filterJobCat)) { ?>
																<option value="<?= $rowData['iJobCategoryId'] ?>">
																	<?= $rowData['strJobCategory'] ?></option>
															<?php }
															?>
														</select>
													</div>
													<div class="form-actions col-md-3">
														<a href="#" class="btn blue" onclick="PageLoadData(1);">Search</a>
														<a onclick="exportexceldata()" class="btn btn-md btn-primary"><i class="fa fa-file-excel-o fa-2x"></i></a>
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
			var Search_Category = $('#Search_Category').val();
			$('#loading').css("display", "block");
			$.ajax({
				type: "POST",
				url: "<?php echo $web_url; ?>Employee/AjaxActiveJob.php",
				data: {
					action: 'ListUser',
					Page: Page,
					Search_Company: Search_Company,
					Search_Category: Search_Category
				},
				success: function(msg) {
					$("#PlaceUsersDataHere").html(msg);
					$('#loading').css("display", "none");
				},
			});
		} // end of filter
		PageLoadData(1);
		function exportexceldata()
		{
			var Search_Company = $('#Search_Company').val();
			var Search_Category = $('#Search_Category').val();
			window.location.href = 'export-acrive-job-Report.php?Search_Company=' + Search_Company + "&Search_Category=" + Search_Category;
		}
		function isNumber(evt) {
			evt = (evt) ? evt : window.event;
			var charCode = (evt.which) ? evt.which : evt.keyCode;
			if (charCode > 31 && (charCode < 48 || charCode > 57)) {
				return false;
			}
			return true;
		}
	</script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js"></script>
	<script>
		$('#Search_Company').typeahead({
			source: function(query, process) {
				return $.get('ajaxcompanypro.php', {
					query: query
				}, function(data) {
					console.log(data);
					data = $.parseJSON(data);
					return process(data);
				});
			}
		});
	</script>
</body>

</html>
