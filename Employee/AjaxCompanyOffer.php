<?php
error_reporting(0);
require_once('../common.php');
include('IsLogin.php');
$connect = new connect();
include('User_Paging.php');

if ($_POST['action'] == 'ListUser') {
	$where = "";

	//$whereEmpId = " and studentjobsubmission.branchId = '" . $_SESSION['branchid'] . "'";
	if (isset($_REQUEST['FormDate']) && $_REQUEST['FormDate'] != '') {
		$where .= " and STR_TO_DATE(studentjobsubmission.strEntryDate,'%d-%m-%Y') >= STR_TO_DATE('" . $_REQUEST['FormDate'] . "','%d-%m-%Y')";
	} 
	if (isset($_REQUEST['ToDate']) && $_REQUEST['ToDate'] != '') {
		$where .= " and STR_TO_DATE(studentjobsubmission.strEntryDate,'%d-%m-%Y') <= STR_TO_DATE('" . $_REQUEST['ToDate'] . "','%d-%m-%Y')";
	}
    //(select company.strCompanyName from company where company.id=jobmaster.iCompanyId) as 'Company'
	$filterstr = "SELECT jobcategory.iJobCategoryId,jobmaster.iCompanyId,jobcategory.strJobCategory,
	        company.strCompanyName as Company,strContactPerson,strContactNumber,strEmail,strDesgination,strWebsite
	        ,sum(jobmaster.iPosition) as 'No_of_jobs',
	        (select count(*) from studentjobsubmission where studentjobsubmission.iJobId=jobmaster.iJobId ".$where.") as 'Interview',
	        (select count(*) from studentjobsubmission where studentjobsubmission.iJobId=jobmaster.iJobId and studentjobsubmission.iJobStatus=1 ".$where.") as 'Pass',
	        (select count(*) from studentjobsubmission where studentjobsubmission.iJobId=jobmaster.iJobId and studentjobsubmission.iJobStatus=2 ".$where.") as 'Fail',
	        (select count(studentjobsubmission.strPlacementDate) from studentjobsubmission where studentjobsubmission.iJobId=jobmaster.iJobId and studentjobsubmission.iJobStatus=1 ".$where.") as 'Join',
	        (select AVG(studentjobsubmission.iSalary) from studentjobsubmission where studentjobsubmission.iJobId=jobmaster.iJobId and studentjobsubmission.iJobStatus=1 ".$where.") as 'Avg_Salary' 
	        FROM jobcategory inner JOIN jobmaster on jobcategory.iJobCategoryId=jobmaster.iJobCategoryId inner join company on company.id=jobmaster.iCompanyId where jobmaster.isDelete=0 and jobmaster.iStatus=1 GROUP BY jobmaster.iJobCategoryId,jobmaster.iCompanyId";
	$countstr = "SELECT count(*) as TotalRow FROM jobcategory inner JOIN jobmaster on jobcategory.iJobCategoryId=jobmaster.iJobCategoryId inner join company on company.id=jobmaster.iCompanyId where jobmaster.isDelete=0 and jobmaster.iStatus=1";

	$resrowcount = mysqli_query($dbconn, $countstr);
	$resrowc = mysqli_fetch_array($resrowcount);
	$totalrecord = $resrowc['TotalRow'];
	$per_page = $cateperpaging;
	$total_pages = ceil($totalrecord / $per_page);
	$page = $_REQUEST['Page'] - 1;
	$startpage = $page * $per_page;
	$show_page = $page + 1;

	$filterstr = $filterstr . " LIMIT $startpage, $per_page";
	$resultfilter = mysqli_query($dbconn, $filterstr);

	if (mysqli_num_rows($resultfilter) > 0) {
		$i = 0;
		$serial = 0;
		$serial = ($page * $per_page);
?>
		<link href="<?php echo $web_url; ?>Employee/assets/global/plugins/datatables/datatables.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo $web_url; ?>Employee/assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" />
		<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
		<div class="table-responsive">
			<style>
				.table thead tr th {
					font-size: 12px;
				}
				.table td, .table th {
					font-size: 12px;
				}
			</style>
			<table class="table table-bordered table-hover center  dt-responsive" width="100%" id="tableC">
					<thead class="tbg">
						<tr>
							<th class="all">Sr.No</th>
							<th class="desktop">Category</th>
							<th class="desktop">Company Name</th>
    						<th class="desktop">No of jobs</th>
    						<th class="desktop">Contact Person</th>
    						<th class="desktop">Mobile</th>
    						<th class="desktop">Email</th>
    						<th class="desktop">Desgination</th>
    						<th class="desktop">Website</th>
						</tr>
					</thead>
					<tbody>
						<?php
						while ($rowfilter = mysqli_fetch_array($resultfilter)) {
							$i++;
							$serial++;
						?>
							<tr>

								<td>
    								<div class="form-group form-md-line-input "><?php echo $serial; ?>
    								</div>
    							</td>
    							
    							<td>
    								<div class="form-group form-md-line-input "><?php echo $rowfilter['strJobCategory']; ?>
    								</div>
    							</td>
    							<td>
    								<div class="form-group form-md-line-input "><?php echo $rowfilter['Company']; ?>
    								</div>
    							</td>
    							<td>
    								<div class="form-group form-md-line-input "><?php echo $rowfilter['No_of_jobs']; ?>
    								</div>
    							</td>
    							<td>
    								<div class="form-group form-md-line-input "><?php echo $rowfilter['strContactPerson']; ?>
    								</div>
    							</td>
    							<td>
    								<div class="form-group form-md-line-input "><?php echo $rowfilter['strContactNumber']; ?>
    								</div>
    							</td>
    							
    							<td>
    								<div class="form-group form-md-line-input "><?php echo $rowfilter['strEmail']; ?>
    								</div>
    							</td>
    							<td>
    								<div class="form-group form-md-line-input "><?php echo $rowfilter['strDesgination']; ?>
    								</div>
    							</td>
    							<td>
    								<div class="form-group form-md-line-input"><?php echo $rowfilter['strWebsite']; ?>
    								</div>
    							</td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			
		</div>
		<script src="<?php echo $web_url; ?>Employee/assets/global/plugins/datatables/datatables.js" type="text/javascript">
		</script>
		<script src="<?php echo $web_url; ?>Employee/assets/global/plugins/datatables/table-datatables-responsive.js" type="text/javascript"></script>

		<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script> -->

		<script>
			$(document).ready(function() {
				$('#tableC').DataTable({});
			});

			

			
		</script>
		<script>
			function CheckAll() {
				if ($('#check_listall').is(":checked")) {
					$('input[type=checkbox]').each(function() {
						$(this).prop('checked', true);
					});
				} else {
					$('input[type=checkbox]').each(function() {
						$(this).prop('checked', false);
					});
				}
			}

			$('#frmparameter').submit(function(e) {
				e.preventDefault();
				var $form = $(this);
				// var errMsg = 'Are you sure to arrange interview?';
				// if (confirm(errMsg)) {
				$('#loading').css("display", "block");
				$.ajax({
					type: 'POST',
					url: 'querydata.php',
					data: $('#frmparameter').serialize(),
					success: function(response) {
						console.log(response);
						if (response == 1) {
							$('#loading').css("display", "none");
							$("#Btnmybtn").attr('disabled', 'disabled');
							alert('Added Sucessfully.');
							window.location.href = '';
						} else {
							$('#loading').css("display", "none");
							$("#Btnmybtn").attr('disabled', 'disabled');
							alert('Invalid Request');
							window.location.href = '';
						}
					}
				});
				//}
			});
		</script>
	<?php
	} else {
	?>
		<div class="row">
			<div class="col-lg-12 col-md-12  col-xs-12 col-sm-12 padding-5 bottom-border-verydark">
				<div class="alert alert-info clearfix profile-information padding-all-10 margin-all-0 backgroundDark">
					<h1 class="font-white text-center"> No Data Found ! </h1>
				</div>
			</div>
		</div>
	<?php
	}

	if ($totalrecord > $per_page) { ?>
		<div class="row">
			<div class="col-lg-12 m-pager">

				<div class="col-lg-12 col-md-12  col-xs-12 col-sm-12 padding-5 bottom-border-verydark" style="text-align: center;">
					<div class="form-actions noborder">
						<?php
						echo '<ul>';

						if ($totalrecord > $per_page) {
							echo paginate($reload = '', $show_page, $total_pages);
						}
						echo "</ul>";
						?>
					</div>
				</div>

			</div>
		</div>
<?php }
}


if ($_REQUEST['action'] == 'viewrequiredlist') {
	$filterstr = "SELECT c.strCompanyName,jb.strJobTitle,jb.iPosition,jb.strExperience,jb.strJobDescription,ss.iSalary,ss.iJobStatus,ss.strRemarks  FROM `studentjobsubmission` ss inner join jobmaster jb on ss.iJobId=jb.iJobId inner join company c on c.id=ss.iCompanyId  where iStudId=" . $_REQUEST['stud_id'] . "";
	$result = mysqli_query($dbconn, $filterstr);
	if (mysqli_num_rows($result) > 0) {
		$html = "";
		$html .= '<table class="table table-bordered table-hover center table-responsive dt-responsive" width="100%" id="tableC">
			<thead class="tbg">
				<tr>
					<th class="desktop">Compnay Name</th>
					<th class="desktop">Job Title </th>
					<th class="desktop">Job Experience</th>
					<th class="desktop">Job Postion</th>
					<th class="desktop">Status</th>
					<th class="desktop">Salary</th>
					<th class="desktop">Remarks</th>
				</tr>
			</thead>
			<tbody>';
		while ($row = mysqli_fetch_array($result)) {
			$iJobStatus = "";
			if ($row['iJobStatus'] == 1) {
				$iJobStatus = "Pass";
			} else if ($row['iJobStatus'] == 2) {
				$iJobStatus = "Fail";
			} else {
				$iJobStatus = "Not Attend";
			}
			$html .= '<tr>
					<td>' . $row['strCompanyName'] . '</td>	
					<td>' . $row['strJobTitle'] . '</td>
					<td>' . $row['strExperience'] . '</td>
					<td>' . $row['iPosition'] . '</td>
					<td>' . $iJobStatus . '</td>
					<td>' . $row['iSalary'] . '</td>
					<td>' . $row['strRemarks'] . '</td>
				</tr>';
		}
		$html .= '</tbody>
		</table>';
		echo $html;
	} else {
		$html = "";
		$html .= '<div class="row">
			<div class="col-lg-12 col-md-12  col-xs-12 col-sm-12 padding-5 bottom-border-verydark">
				<div class="alert alert-info clearfix profile-information padding-all-10 margin-all-0 backgroundDark">
					<h1 class="font-white text-center"> No Data Found ! </h1>
				</div>
			</div>
		</div>';
		echo $html;
	}
}

if ($_REQUEST['action'] == 'sendtonotrequiredlist') {
	$data = array(
		'iJobStatus' => 1
	);
	$where = ' where  stud_id =' . $_POST['stud_id'] . ' ';
	$connect->updaterecord($dbconn, 'studentadmission', $data, $where);
	echo $_POST['stud_id'];
}
?>



<script>
	$("#iStatus").change(function() {
		var iStatus = $(this).val();
		if (iStatus == 1) {
			$("#divSalary").show();
			$("#iSalary").attr('required', true);
		} else {
			$("#divSalary").hide();
			//$("#iSalary").val(0);
			$("#iSalary").removeAttr('required');
		}
	});
</script>
