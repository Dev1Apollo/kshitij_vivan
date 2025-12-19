<?php
ob_start();
error_reporting(0);
require_once('../common.php');
include('IsLogin.php');
$connect = new connect();
include('User_Paging.php');

if ($_POST['action'] == 'ListUser') {
	$where = " where 1=1";
	if ($_REQUEST['stud_id'] != NULL && isset($_REQUEST['stud_id']))
		$where .= " and  sjs.iStudId =" . $_REQUEST['stud_id'];

	$filterstr = "SELECT sjs.iJobSubmissionId,sjs.strPlacementDate,sjs.strInterviewDate,sjs.iJobId,sjs.iStudId,sjs.iJobStatus,sjs.iSalary,sjs.strRemarks,sjs.iJobCategoryId,(select jobcategory.strJobCategory from jobcategory where sjs.iJobCategoryId=jobcategory.iJobCategoryId) as strJobCategory,jm.strJobTitle,jm.iPosition,jm.strExperience,jm.strEndDate,(select company.strCompanyName from company where company.id=sjs.iCompanyId) as strCompanyName FROM studentjobsubmission sjs left join jobmaster jm on sjs.iJobId=jm.iJobId  " . $where . " and sjs.iStatus=1 order by sjs.iJobSubmissionId asc";
	$countstr = "SELECT count(*) as TotalRow FROM studentjobsubmission sjs left join jobmaster jm on sjs.iJobId=jm.iJobId " . $where . " and sjs.iStatus=1";

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
			<table class="table table-bordered table-hover center table-responsive dt-responsive" width="100%" id="tableC">
				<thead class="tbg">
					<tr>
						<th class="all">Sr.No</th>
						<th class="desktop">Company Name</th>
						<th class="desktop">Job Category</th>
						<th class="desktop">Date Of Interview</th>
						<th class="desktop">Job Status</th>
						<th class="desktop">Placement Date</th>
						<th class="desktop">Salary</th>
						<th class="desktop">Remarks</th>
						<th class="desktop">Action</th>
					</tr>
				</thead>
				<tbody>
					<?php
					while ($rowfilter = mysqli_fetch_array($resultfilter)) {
						$serial++;
					?>
						<tr>
							<td>
								<div class="form-group form-md-line-input "><?php echo $serial; ?>
								</div>
							</td>
							<td>
								<div class="form-group form-md-line-input "><?php echo $rowfilter['strCompanyName']; ?>
								</div>
							</td>
							<td>
								<div class="form-group form-md-line-input "><?php echo $rowfilter['strJobCategory']; ?>
								</div>
							</td>
							<td>
								<div class="form-group form-md-line-input "><?php echo $rowfilter['strInterviewDate']; ?>
								</div>
							</td>
							<td>
								<div class="form-group form-md-line-input "><?php 
									if($rowfilter['iJobStatus'] == 1){
										echo "Pass";
									} else if($rowfilter['iJobStatus'] == 2){
										echo "Fail";
									} else if($rowfilter['iJobStatus'] == 3){
										echo "Not Attempted";
									}else if($rowfilter['iJobStatus'] == 4){
										echo "Pass But Not Join";
									} else {
										echo "Pending";
									}
								?>
								</div>
							</td>
							<td>
								<div class="form-group form-md-line-input "><?php echo $rowfilter['strPlacementDate']; ?>
								</div>
							</td>
							<td>
								<div class="form-group form-md-line-input"><?php echo $rowfilter['iSalary']; ?>
								</div>
							</td>
							<td>
								<div class="form-group form-md-line-input"><?php echo $rowfilter['strRemarks']; ?>
								</div>
							</td>
							<td>
								<div class="form-group form-md-line-input">
									<a class="btn blue" data-toggle="modal" data-target="#exampleModal" href="#" onclick="SetStudentData(<?php echo $rowfilter['iStudId']; ?>,<?php echo $rowfilter['iJobSubmissionId']; ?>)" title="STUDENT PLACEMENT DETAILS"><i class="fa fa-eye"></i></a>
								</div>
							</td>
						</tr>
					<?php
						$i++;
					}
					?>
				</tbody>
			</table>
		</div>
	<?php } else {
	?>
		<div class="row">
			<div class="col-lg-12 col-md-12  col-xs-12 col-sm-12 padding-5 bottom-border-verydark">
				<div class="alert alert-info clearfix profile-information padding-all-10 margin-all-0 backgroundDark">
					<h1 class="font-white text-center"> No Data Found ! </h1>
				</div>
			</div>
		</div>
	<?php
	} ?>
	<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Student Job Submission</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						X
					</button>
				</div>
				<div class="modal-body">
					<div id="PlacedRemarkDataHere"></div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<!-- <button type="button" class="btn btn-primary">Save changes</button> -->
				</div>
			</div>
		</div>
	</div>
	<script src="<?php echo $web_url; ?>Employee/assets/global/plugins/datatables/datatables.js" type="text/javascript"></script>
	<script src="<?php echo $web_url; ?>Employee/assets/global/plugins/datatables/table-datatables-responsive.js" type="text/javascript"></script>
	<script>
		$(document).ready(function() {
			$('#tableC').DataTable({});
		});
	</script>
	<script>
		function SetStudentData(StudId, iJobSubmissionId) {
			//$("#stud_id").val(StudId);
			$('#loading').css("display", "block");
			$.ajax({
				type: "POST",
				url: "<?php echo $web_url; ?>Employee/querydata.php",
				data: {
					action: 'GetPlacedStudentJob',
					StudId: StudId,
					iJobSubmissionId: iJobSubmissionId
				},
				success: function(msg) {
					$("#PlacedRemarkDataHere").html(msg);
					$('#loading').css("display", "none");
				},
			});
		}
	</script>

<?php
}

if ($totalrecord > $per_page) {
?>
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
<?php } ?>
