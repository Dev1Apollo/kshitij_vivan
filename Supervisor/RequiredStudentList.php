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
	<link href="<?php echo $web_url; ?>Supervisor/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet" type="text/css" />
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="page-container-bg-solid page-boxed">
	<?php include_once './header.php'; ?>
	<div style="display: none; z-index: 10060;" id="loading">
		<img id="loading-image" src="<?php echo $web_url; ?>Supervisor/images/loader1.gif">
	</div>
	<div class="page-container">
		<div class="page-content-wrapper">
			<?php //include_once './studentlist_menu.php'; 
			?>
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
													<form role="form" method="POST" action="" name="frmSearch" id="frmSearch" enctype="multipart/form-data">
														<div class="row">
															<div class="form-group col-md-offset-1 col-md-2">
																<select name="month" id="month" size='1' class="form-control">
																	<option value="">Select Month</option>
																	<?php
																	for ($i = 0; $i < 12; $i++) {
																		$time = strtotime(sprintf('%d months', $i));
																		$label = date('F', $time);
																		$value = date('m', $time);
																		echo "<option value='$value'>$label</option>";
																	}
																	?>
																</select>
															</div>
															<div class="form-group col-md-2">
																<select name="Year[]" id="Year" class="form-control" multiple="multiple">
																	<?php
																	$starting_year = date('Y', strtotime('-5 year'));
																	$ending_year = date('Y', strtotime('+5 year'));
																	$current_year = date('Y');
																	for ($starting_year; $starting_year <= $ending_year; $starting_year++) {
																		echo '<option value="' . $starting_year . '"';
																		if ($starting_year == $current_year) {
																			echo ' selected="selected"';
																		}
																		echo ' >' . $starting_year . '</option>';
																	}
																	?>
																</select>
															</div>
															<div class="form-group col-md-2">
																<select name="Search_Status" id="Search_Status" class="form-control" required="">
																	<option value="">Select Status</option>
																	<option value="0">Required</option>
																	<option value="1">Not Required</option>
																	<option value="2">Placed</option>
																</select>
															</div>
															<div class="form-group col-md-2">
                                                                <?php
                                                                $querysBranch = "SELECT * FROM `branchmaster` where isDelete='0' order by  branchid asc";
                                                                $resultsBranch = mysqli_query($dbconn, $querysBranch) or die(mysqli_error($dbconn));
                                                                echo '<select class="form-control" name="branchid" id="branchid">';
                                                                echo "<option value='' >Select</option>";
                                                                while ($rowsBranch = mysqli_fetch_array($resultsBranch)) {
                                                                    echo "<option value='" . $rowsBranch['branchid'] . "'>" . $rowsBranch['branchname'] . "</option>";
                                                                }
                                                                echo "</select>";
                                                                ?>
                                                            </div>
															<div class="form-group">
																<a class="btn blue" onclick="PageLoadData(1);">Search</a>
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

	<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Interview Details</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<form role="form" method="POST" action="" name="frmplacedparameter" id="frmplacedparameter" enctype="multipart/form-data">
					<input type="hidden" value="" name="iStudId" id="iStudId">
					<input type="hidden" value="2" name="StudentJobStatus" id="StudentJobStatus">
					<input type="hidden" value="UpdatePlaceJobStudentStatus" name="action" id="action1">
					<div class="modal-body">
						<div class="row">
							<div class="form-group col-md-12">
								<label for="form_control_1">Company Name*</label>
								<!-- <select name="iCompanyId" id="iCompanyId" class="form-control" required="">
									<option value="">Select Company Name</option>
									<?php
									$filterCompany = mysqli_query($dbconn, "SELECT id,strCompanyName FROM `company` where isDelete=0 and iStatus=1");
									while ($rowCompay = mysqli_fetch_assoc($filterCompany)) {
									?>
										<option value="<?= $rowCompay['id'] ?>"><?= $rowCompay['strCompanyName'] ?></option>
									<?php } ?>
								</select> -->
								<input name="iCompanyId" id="iCompanyId" class="form-control" required="">
							</div>
							<div class="form-group col-md-12">
								<label for="form_control_1">Job Category*</label>
								<select name="iJobCategoryId" id="iJobCategoryId" class="form-control" required="">
									<option value="">Select Job Category</option>
									<?php
									$filterJobCat = mysqli_query($dbconn, "SELECT iJobCategoryId,strJobCategory FROM `jobcategory` where isDelete=0 and iStatus=1");
									while ($rowJobCat = mysqli_fetch_assoc($filterJobCat)) {
									?>
										<option value="<?= $rowJobCat['iJobCategoryId'] ?>"><?= $rowJobCat['strJobCategory'] ?></option>
									<?php } ?>
								</select>
							</div>
							<div class="form-group col-md-12">
								<label for="form_control_1">Placement Date*</label>
								<input name="strPlacementDate" id="strPlacementDate" class="form-control" placeholder="Enter Placement Date">
							</div>
							<input type="hidden" value="1" name="iStatus" id="iStatus">
							<!-- <div class="form-group col-md-12">
							<label for="form_control_1">Status*</label>
							<select name="iStatus" id="iStatus" class="form-control" required="">
								<option value="">Select Status</option>
								<option value="1">Pass</option>
								<option value="2">Fail</option>
								<option value="3">Not Attempted</option>
								<option value="4">Pass But Not Join</option>
							</select>
						</div> -->
							<div class="form-group col-md-12">
								<label for="form_control_1">Salary*</label>
								<input name="iSalary" id="iSalary" class="form-control" placeholder="Enter Salary">
							</div>
							<div class="form-group col-md-12">
								<label for="form_control_1">Remarks</label>
								<textarea name="strRemarks" id="strRemarks" class="form-control" placeholder="Enter Remarks" type="text" required=""></textarea>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button class="btn blue margin-top-5" type="submit" id="Btnmybtn" name="submit">Submit</button>
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<?php include_once './footer.php'; ?>
	<style>
		.multiselect {
			display: block;
			height: 35px;
			padding: 6px;
			text-align: left !important;
			line-height: 1.42857;
			/* color: #DFDFDF; */
			background-color: #fff;
			background-image: none;
			border: 1px solid #51c6dd !important;
			border-radius: 4px;
			color: #555555;
			font-size: 15px;
			font-weight: normal !important;
			text-transform: lowercase;

		}
	</style>
	<link href="assets/bootstrap-multiselect.css" rel="stylesheet" type="text/css" />
	<script src="assets/bootstrap-multiselect.js" type="text/javascript"></script>
	<script src="<?php echo $web_url; ?>Supervisor/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>
	<script type="text/javascript">
		function checkclose() {
			window.location.href = '';
		}
		$(document).ready(function() {
			var date = new Date();
			$("#strPlacementDate").datepicker({
				format: "dd-mm-yyyy",
				autoclose: true,
				todayHighlight: true,
				endDate: date
			});
		});
	</script>
	<script type="text/javascript">
		$(document).ready(function() {
			$('#Year').multiselect({
				nonSelectedText: 'Select Any Paymant Mode',
				includeSelectAllOption: true,
				buttonWidth: '100%',
			});
		});

		function checkclose() {
			window.location.href = '';
		}

		function PageLoadData(Page) {
			var month = $('#month').val();
			var Year = $('#Year').val();
			var Search_Status = $("#Search_Status").val();
            var branchid = $("#branchid").val()
			$('#loading').css("display", "block");
			$.ajax({
				type: "POST",
				url: "<?php echo $web_url; ?>Supervisor/AjaxRequiredStudentList.php",
				data: {
					action: 'ListUser',
					Page: Page,
					month: month,
					Year: Year,
					Search_Status: Search_Status,
					branchid: branchid
				},
				success: function(msg) {
					$("#PlaceUsersDataHere").html(msg);
					$('#loading').css("display", "none");
				},
			});
		} // end of filter
		//PageLoadData(1);

		function SetStudentData(StudId) {
			$("#stud_id").val(StudId);
			var errMsg = 'Are you sure to sent into not required list?';
			if (confirm(errMsg)) {
				$('#loading').css("display", "block");
				$.ajax({
					type: 'POST',
					url: '<?php echo $web_url; ?>Supervisor/AjaxRequiredStudentList.php',
					data: {
						action: 'sendtonotrequiredlist',
						stud_id: StudId
					},
					success: function(response) {
						if (response != 0) {
							$('#loading').css("display", "none");
							//$("#Btnmybtn").attr('disabled', 'disabled');
							alert('Added Sucessfully.');
							window.location.href = "";
						}
					}
				});
			}
		}

		function SetData(StudId) {
			//$('#loading').css("display", "block");
			$.ajax({
				type: 'POST',
				url: '<?php echo $web_url; ?>Supervisor/AjaxRequiredStudentList.php',
				data: {
					action: 'viewrequiredlist',
					stud_id: StudId
				},
				success: function(response) {
					$("#DataPlaceHere").html(response);
					// if (response != 0) {
					//     $('#loading').css("display", "none");
					//     //$("#Btnmybtn").attr('disabled', 'disabled');
					//     //window.location.href = "";
					// }
				}
			});
		}

		function updateAttendanceDetail(stud_id) {
			jQuery.noConflict();
			var iJobStatus = $('#iJobStatus_' + stud_id).val();
			if (iJobStatus == 2) {
				jQuery.noConflict();
				$("#iStudId").val(stud_id);
				//$('#exampleModal').modal('toggle');
				//$('#exampleModal').modal('show'); 
				//$("#Btnmybtn").click(function(){
				$("#exampleModal").modal('show');
				//});
				//$("#exampleModal").modal();
			} else {
				$('#loading').css("display", "block");
				$.ajax({
					type: 'POST',
					url: 'querydata.php',
					data: {
						action: "UpdateJobStudentStatus",
						stud_id: stud_id,
						iJobStatus: iJobStatus
					},
					success: function(response) {
						console.log(response);
						if (response != 0) {
							$('#loading').css("display", "none");
							$("#Btnmybtn").attr('disabled', 'disabled');
							alert('Student Job Status Updated Sucessfully.');
						} else {
							$('#loading').css("display", "none");
							$("#Btnmybtn").attr('disabled', 'disabled');
							alert('Invalid Request.');
						}
					}
				});
			}
		}

		$('#frmplacedparameter').submit(function(e) {
			e.preventDefault();
			// var $form = $(this);
			// var stud_id = $("#iStudId").val();
			// var StudentJobStatus = 2;
			// var iCompanyId = $("#iCompanyId").val();
			// var iJobCategoryId = $("#iJobCategoryId").val();
			// var iStatus = $("#iStatus").val();

			// var iSalary = $("#iSalary").val();
			// var strRemarks = $("#strRemarks").val();
			//var action = $("#action").val();
			//data: $('#frmparameter').serialize(),
			$('#loading').css("display", "block");
			$.ajax({
				type: 'POST',
				url: 'querydata.php',
				// data: {
				// 	action: "UpdatePlaceJobStudentStatus",
				// 	stud_id: stud_id,
				// 	iJobStatus: iStatus,
				// 	StudentJobStatus: StudentJobStatus,
				// 	iCompanyId: iCompanyId,
				// 	iJobCategoryId: iJobCategoryId,
				// 	iSalary: iSalary,
				// 	strRemarks: strRemarks,
				// 	strPlacementDate: strPlacementDate
				// },
				data: $('#frmplacedparameter').serialize(),
				success: function(response) {
					console.log(response);
					if (response != 0) {
						$('#loading').css("display", "none");
						$("#Btnmybtn").attr('disabled', 'disabled');
						alert('Student Job Status Updated Sucessfully.');
					} else {
						$('#loading').css("display", "none");
						$("#Btnmybtn").attr('disabled', 'disabled');
						alert('Invalid Request.');
					}

					$("#exampleModal").modal('hide');
				}
			});
		});
	</script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js"></script>
	<script>
		$('#iCompanyId').typeahead({
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