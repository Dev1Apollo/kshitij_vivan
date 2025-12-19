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
	<title><?php echo $ProjectName; ?> | Job Master </title>
	<link href="<?php echo $web_url; ?>Supervisor/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet" type="text/css" />
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
	<?php include_once './include.php'; ?>
</head>

<body class="page-container-bg-solid page-boxed">
	<?php include_once './header.php'; ?>
	<div style="display: none; z-index: 10060;" id="loading">
		<img id="loading-image" src="<?php echo $web_url; ?>Supervisor/images/loader1.gif">
	</div>
	<div class="page-container">
		<div class="page-content-wrapper">
			<div class="page-content">
				<div class="container">
					<ul class="page-breadcrumb breadcrumb">
						<li>
							<a href="<?php echo $web_url; ?>Supervisor/index.php">Home</a>
							<i class="fa fa-circle"></i>
						</li>
						<li>
							<span>Job Master</span>
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
											<span class="caption-subject bold uppercase">Add Job </span>
										</div>

									</div>
									<div class="portlet-body form">
										<div class="row m-search-box">
											<div class="col-md-12">

												<form role="form" method="POST" action="" name="frmparameter" id="frmparameter" enctype="multipart/form-data">
													<input type="hidden" value="AddJob" name="action" id="action">
													<input type="hidden" value="<?= $_GET['id'] ?>" name="iCompanyId" id="iCompanyId">
													<div class="after-add-more">
														<div class="row">
															<div class="col-md-6">
																<div class="form-group">
																	<label for="form_control_1">Job Title </label>
																	<input name="strJobTitle[]" id="strJobTitle" class="form-control" placeholder="Enter Job Title" required="" type="text">
																</div>
															</div>
															<div class="col-md-6">
																<!-- <div class="form-body"> -->
																<div class="form-group">
																	<label for="form_control_1">Experience</label>
																	<input name="strExperience[]" id="strExperience" class="form-control" placeholder="Enter Experience" required="" type="text">
																</div>
																<!-- </div> -->
															</div>
															<div class="col-md-4">
																<!-- <div class="form-body"> -->
																<div class="form-group">
																	<label for="form_control_1">Position</label>
																	<input name="iPosition[]" id="iPosition" pattern="[0-9]" onkeypress="return isNumber(event)" class="form-control" placeholder="Enter Position" required="" type="text">
																</div>
																<!-- </div> -->
															</div>
															<div class="col-md-4">
																<!-- <div class="form-body"> -->
																<div class="form-group">
																	<label for="form_control_1">Job Category</label>
																	<select type="text" name="iJobCategoryId[]" class="form-control" id="iJobCategoryId" required>
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
																<!-- </div> -->
															</div>
															<div class="col-md-4">
																<!-- <div class="form-body"> -->
																<div class="form-group">
																	<label for="form_control_1">End Date</label>
																	<input type="text" placeholder="Enter End Date" name="strEndDate[]" class="form-control" id="strEndDate" required />
																</div>
																<!-- </div> -->
															</div>
															<div class="col-md-12">
																<!-- <div class="form-body"> -->
																<div class="form-group">
																	<label for="form_control_1">Job Description </label>
																	<textarea name="strJobDescription[]" id="strJobDescription" class="form-control" placeholder="Enter Job Description" required="" type="text">
															</textarea>
																</div>
																<!-- </div> -->
															</div>

															<div class="col-md-2">
																<div class="form-group change">
																	<label for="">&nbsp;</label><br />
																	<a class="btn btn-success add-more">+ Add More</a>
																</div>
															</div>
														</div>

													</div>
													<div class="form-actions noborder">
														<input class="btn blue " type="submit" id="Btnmybtn" value="Submit" name="submit">
														<button type="button" class="btn blue" onClick="checkclose();">Cancel</button>
													</div>
												</form>

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
	</div>


	<?php include_once './footer.php'; ?>
	<script src="<?php echo $web_url; ?>Supervisor/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>
	<script type="text/javascript">
		function checkclose() {
			window.location.href = '';
		}
		$(document).ready(function() {
			var date = new Date();
			$("#strEndDate").datepicker({
				format: "dd-mm-yyyy",
				autoclose: true,
				todayHighlight: true,
				startDate: date
			});
		});

		$('#frmparameter').submit(function(e) {
			e.preventDefault();
			var $form = $(this);
			$('#loading').css("display", "block");
			$.ajax({
				type: 'POST',
				url: '<?php echo $web_url; ?>Supervisor/querydata.php',
				data: $('#frmparameter').serialize(),
				success: function(response) {
					console.log(response);
					//$("#Btnmybtn").attr('disabled', 'disabled');
					if (response == 1) {
						$('#loading').css("display", "none");
						$("#Btnmybtn").attr('disabled', 'disabled');
						alert('Job Added Sucessfully.');
						window.location.href = '';
					} else if (response == 2) {
						$('#loading').css("display", "none");
						$("#Btnmybtn").attr('disabled', 'disabled');
						alert('Job Edited Sucessfully.');
						window.location.href = '';
					} else {
						$('#loading').css("display", "none");
						$("#Btnmybtn").attr('disabled', 'disabled');
						alert('Invalid Request.');
						window.location.href = '';
					}
				}
			});
		});

		function isNumber(evt) {
			evt = (evt) ? evt : window.event;
			var charCode = (evt.which) ? evt.which : evt.keyCode;
			if (charCode > 31 && (charCode < 48 || charCode > 57)) {
				return false;
			}
			return true;
		}
		$(document).ready(function() {
			$("body").on("click", ".add-more", function() {
				var html = $(".after-add-more").first().clone();
				//  $(html).find(".change").prepend("<label for=''>&nbsp;</label><br/><a class='btn btn-danger remove'>- Remove</a>");
				$(html).find(".change").html("<label for=''>&nbsp;</label><br/><a class='btn btn-danger remove'>- Remove</a>");
				$(".after-add-more").last().after(html);
			});
			$("body").on("click", ".remove", function() {
				$(this).parents(".after-add-more").remove();
			});
		});
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

		// $('#strCompanyName').typeahead({
		// 	source: function(query, process) {
		// 		return $.get('ajaxcompanypro.php', {
		// 			query: query
		// 		}, function(data) {
		// 			console.log(data);
		// 			data = $.parseJSON(data);
		// 			return process(data);
		// 		});
		// 	}
		// });
	</script>
</body>

</html>