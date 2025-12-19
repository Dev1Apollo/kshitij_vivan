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
	<title><?php echo $ProjectName; ?> | Company Offer Report </title>
	<?php include_once './include.php'; ?>
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
	<link href="<?php echo $web_url; ?>Supervisor/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet" type="text/css" />
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
							<span>Company Offer Report</span>
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
											<span class="caption-subject bold uppercase">List of Company Offer Report</span>
										</div>
									</div>
									<div class="portlet-body form">
										<div class="row m-search-box">
											<div class="col-md-12">
												<form role="form" method="POST" action="" name="frmSearch" id="frmSearch" enctype="multipart/form-data">
													<div class="form-group col-md-offset-2 col-md-3">
														<input type="text" id="FormDate" name="FormDate" class="form-control date-picker" placeholder="Enter From Date" />
													</div>
													<div class="form-group col-md-3">
														<input type="text" id="ToDate" name="ToDate" class="form-control date-picker" placeholder="Enter To Date" />
													</div>
													<div class="form-group  col-md-3">
														<a href="#" class="btn blue" onclick="PageLoadData(1);">Search</a>
														<a onclick="exportexceldata()" class="btn btn-md btn-primary"><i class="fa fa-file-excel-o"></i></a>
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
	<script src="<?php echo $web_url; ?>Supervisor/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>
	<script type="text/javascript">
		function checkclose() {
			window.location.href = '';
		}

		$(document).ready(function() {
			$("#FormDate").datepicker({
				format: 'dd-mm-yyyy',
				autoclose: true,
				todayHighlight: true,
				defaultDate: "now"
			});

			$("#ToDate").datepicker({
				format: 'dd-mm-yyyy',
				autoclose: true,
				todayHighlight: true,
				defaultDate: "now"
			});
		});

		function PageLoadData(Page) {
			var FormDate = $('#FormDate').val();
			var ToDate = $('#ToDate').val();
			$('#loading').css("display", "block");
			$.ajax({
				type: "POST",
				url: "<?php echo $web_url; ?>Supervisor/AjaxCompanyOffer.php",
				data: {
					action: 'ListUser',
					Page: Page,
					FormDate: FormDate,
					ToDate: ToDate,
				},
				success: function(msg) {
					$("#PlaceUsersDataHere").html(msg);
					$('#loading').css("display", "none");
				},
			});
		} // end of filter
		//PageLoadData(1);

		function exportexceldata() {
			;
			var FormDate = $('#FormDate').val();
			var ToDate = $('#ToDate').val();
			//window.location.href = 'exportCompanyMasterReport.php?FormDate=' + FormDate + "&ToDate=" + ToDate;
			var Url = '<?php echo $web_url; ?>Supervisor/exportCompanyOfferReport.php?FormDate=' + FormDate + '&ToDate=' + ToDate;
			window.open(
				Url,
				'_blank' // <- This is what makes it open in a new window.
			);
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
	<!-- <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script> -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js"></script>
	<script>
		$('#Search_Txt').typeahead({
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

		$('#strCompanyName').typeahead({
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