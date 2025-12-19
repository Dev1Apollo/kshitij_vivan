<?php
error_reporting(0);
require_once('../common.php');
include('IsLogin.php');
$connect = new connect();
include('User_Paging.php');

if ($_POST['action'] == 'ListUser') {
	$where = "where 1=1";
	if (isset($_REQUEST['Search_Company']) &&  $_POST['Search_Company'] != NULL)
		$where .= " and  iCompanyId in (select id from company where  strCompanyName like '%$_POST[Search_Company]%')";
	if (isset($_REQUEST['Search_Student']) && $_POST['Search_Student'] != NULL)
		$where .= " and  (firstName like '%$_POST[Search_Student]%' or surName like '%$_POST[Search_Student]%')";
	// if (isset($_REQUEST['Search_Status']) && $_POST['Search_Status'] != NULL)
	// 	$where .= " and  iJobStatus like '%$_POST[Search_Status]%'";

	$filterstr = "SELECT *,(select company.strCompanyName from company where company.id=sj.iCompanyId) as strCompanyName FROM `studentadmission` sa inner join studentjobsubmission sj on sa.stud_id=sj.iStudId inner join jobmaster j on j.iJobId=sj.iJobId " . $where . " and sa.isDelete='0' and  sa.istatus='1' and sa.iJobStatus='2' and sj.iJobStatus=1 and branchId=" . $_SESSION['branchid'] . " order by stud_id desc";
	$countstr = "SELECT count(*) as TotalRow FROM `studentadmission` sa inner join studentjobsubmission sj on sa.stud_id=sj.iStudId inner join jobmaster j on j.iJobId=sj.iJobId " . $where . " and sa.isDelete='0' and sa.istatus='1' and sa.iJobStatus='2' and sj.iJobStatus=1 and branchId=" . $_SESSION['branchid'] . "";

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
		<link href="<?php echo $web_url; ?>Supervisor/assets/global/plugins/datatables/datatables.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo $web_url; ?>Supervisor/assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" />
		<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
		<table class="table table-bordered table-hover center table-responsive dt-responsive" width="100%" id="tableC">
			<thead class="tbg">
				<tr>
					<th class="all">Sr.No</th>
					<th class="desktop">Student Name</th>
					<th class="desktop">Details</th>
					<th class="desktop">Company Name</th>
					<th class="desktop">Job Details</th>
					<th class="desktop">Remarks</th>
					<th class="desktop">Action</th>
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
							<div class="form-group form-md-line-input "><?php echo $rowfilter['title'] . ' ' . $rowfilter['firstName'] . ' ' . $rowfilter['middleName'] . ' ' . $rowfilter['surName']; ?>
							</div>
						</td>
						<td>
							<div class="form-group form-md-line-input "><?php
																		if ($rowfilter['email'] != '' && $rowfilter['mobileOne'] != '' && $rowfilter['mobileTwo'] != '') {
																			echo 'E:' . $rowfilter['email'] . ' <br>M:' . $rowfilter['mobileOne'], '<br>' . $rowfilter['mobileTwo'];
																		} else if ($rowfilter['email'] != '' && $rowfilter['mobileOne'] != '') {
																			echo 'E:' . $rowfilter['email'] . ' <br>M:' . $rowfilter['mobileOne'];
																		} else if ($rowfilter['mobileTwo'] != '' && $rowfilter['mobileOne'] != '') {
																			echo 'M:' . $rowfilter['mobileTwo'], '<br>' . $rowfilter['mobileOne'];
																		} else if ($rowfilter['email'] != '' && $rowfilter['mobileTwo'] != '') {
																			echo 'E:' . $rowfilter['email'] . ' <br>M:' . $rowfilter['mobileTwo'];
																		} elseif ($rowfilter['email'] != '') {
																			echo 'E:' . $rowfilter['email'];
																		} else if ($rowfilter['mobileOne'] != '') {
																			echo 'M:' . $rowfilter['mobileOne'];
																		} else if ($rowfilter['mobileTwo'] != '') {
																			echo 'M:' . $rowfilter['mobileTwo'];
																		} else {
																			echo '<center>-</center>';
																		}
																		?>
							</div>
						</td>
						<td>
							<div class="form-group form-md-line-input "><?php echo $rowfilter['strCompanyName']; ?>
							</div>
						</td>
						<td>
							<div class="form-group form-md-line-input "><?php echo 'Title : ' . $rowfilter['strJobTitle'] . ' <br /> Position : ' . $rowfilter['iPosition'] . '<br /> Experience : ' . $rowfilter['strExperience'] . ' <br /> Salary : ' . $rowfilter['iSalary']; ?>
							</div>
						</td>
						<td>
							<div class="form-group form-md-line-input "><?php echo $rowfilter['strRemarks']; ?>
							</div>
						</td>
						<td>
							<div class="form-group form-md-line-input">
								<?php //if ($_POST['student'] == 'Enroll_Student') { 
								?>
								<!-- <a class="btn blue" href="<?php echo $web_url; ?>Supervisor/student-course.php?token=<?php echo $rowfilter['stud_id']; ?>" title="MANAGE STUDENT"><i class="fa fa-user"></i></a> -->
								<!--<a  class="btn blue" href="<?php echo $web_url; ?>Supervisor/EditStudent.php?token=<?php // echo $rowfilter['stud_id']; 
																														?>" title="EDIT STUDENT"><i class="fa fa-edit"></i></a>-->
								<?php //} 
								?>
								<?php //if ($_POST['student'] == 'Registered_Student') { 
								?>
								<a class="btn blue" data-toggle="modal" data-target="#exampleModal" href="#" onclick="SetStudentData(<?php echo $rowfilter['stud_id']; ?>,<?php echo $rowfilter['iJobSubmissionId']; ?>)" title="STUDENT PLACEMENT DETAILS"><i class="fa fa-eye"></i></a>
								<a class="btn blue" onClick="javascript: return sentToRequired('<?php echo $rowfilter['stud_id']; ?>',<?php echo $rowfilter['iJobSubmissionId']; ?>);" title="MOVE TO REQUIRED LIST"><i class="fa fa-check"></i></a>

								<?php //} 
								?>
							</div>
						</td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
		<script src="<?php echo $web_url; ?>Supervisor/assets/global/plugins/datatables/datatables.js" type="text/javascript"></script>
		<script src="<?php echo $web_url; ?>Supervisor/assets/global/plugins/datatables/table-datatables-responsive.js" type="text/javascript"></script>
		<script>
			$(document).ready(function() {
				$('#tableC').DataTable({});
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
}


if ($_REQUEST['action'] == 'MovetoRequiredList') {
	$iSalary = 0;
	$jobData = array(
		"iJobStatus" => 0,
		"strEntryDate" => date('d-m-Y H:i:s'),
		"strIP" => $_SERVER['REMOTE_ADDR']
	);
	$whereJob = ' where iJobSubmissionId =' . $_POST['iJobSubmissionId'] . ' ';
	$dealer_res = $connect->updaterecord($dbconn, 'studentjobsubmission', $jobData, $whereJob);

	$data = array(
		'iJobStatus' => 0
	);
	$where = ' where  stud_id =' . $_POST['stud_id'] . ' ';
	$connect->updaterecord($dbconn, 'studentadmission', $data, $where);
	echo $statusMsg = $dealer_res ? '1' : '0';
}
?>
<?php if ($totalrecord > $per_page) { ?>
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
<script>
	function SetStudentData(StudId, iJobSubmissionId) {
		//$("#stud_id").val(StudId);
		$('#loading').css("display", "block");
		$.ajax({
			type: "POST",
			url: "<?php echo $web_url; ?>Supervisor/querydata.php",
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