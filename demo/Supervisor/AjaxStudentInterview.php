<?php
error_reporting(0);
require_once('../common.php');
include('IsLogin.php');
$connect = new connect();
include('User_Paging.php');

if ($_POST['action'] == 'ListUser') {
	$where = "where 1=1";
	if (isset($_REQUEST['firstName']) &&  $_POST['firstName'] != NULL)
		$where .= " and  firstName like '%$_POST[firstName]%'";
	if (isset($_REQUEST['surName']) && $_POST['surName'] != NULL)
		$where .= " and  surName like '%$_POST[surName]%'";
	if (isset($_REQUEST['studentPortal_Id']) && $_POST['studentPortal_Id'] != NULL)
		$where .= " and  studentPortal_Id like '%$_POST[studentPortal_Id]%'";
	if ($_POST['Search_Company'] != NULL && isset($_POST['Search_Company'])) {
		$where .= " and company.strCompanyName like '%" . $_POST['Search_Company'] . "%'";
	}
	if ($_POST['Search_Category'] != NULL && isset($_POST['Search_Category'])) {
		$where .= " and j.iJobCategoryId='" . $_POST['Search_Category'] . "'";
	}

	$filterstr = "SELECT *,(select company.strCompanyName from company where company.id=sj.iCompanyId) as strCompanyName FROM `studentadmission` sa inner join studentjobsubmission sj on sa.stud_id=sj.iStudId inner join jobmaster j on j.iJobId=sj.iJobId inner join company on company.id=sj.iCompanyId " . $where . " and sa.isDelete='0' and  sa.istatus='1' and sa.iJobStatus='0' and sj.iJobStatus=0 and branchId=" . $_SESSION['branchid'] . " order by sa.stud_id desc";
	$countstr = "SELECT count(*) as TotalRow FROM `studentadmission` sa inner join studentjobsubmission sj on sa.stud_id=sj.iStudId  inner join jobmaster j on j.iJobId=sj.iJobId inner join company on company.id=sj.iCompanyId " . $where . " and sa.isDelete='0' and sa.istatus='1' and sa.iJobStatus='0' and sj.iJobStatus=0 and branchId=" . $_SESSION['branchid'] . "";

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
					<!-- <th class="desktop">Details</th> -->
					<th class="desktop">Company Name</th>
					<th class="desktop">Job Category</th>
					<th class="desktop">Job Details</th>
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
						<!-- <td>
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
						</td> -->

						<td>
							<div class="form-group form-md-line-input "><?php echo $rowfilter['strCompanyName']; ?>
							</div>
						</td>
						<td>
							<div class="form-group form-md-line-input ">
								<?php $filterstrCat = mysqli_query($dbconn, "SELECT strJobCategory FROM `jobcategory` where iJobCategoryId='" . $rowfilter['iJobCategoryId'] . "' and  isDelete='0'");
								$rowCatData = mysqli_fetch_assoc($filterstrCat);
								echo $rowCatData['strJobCategory'];
								?>
							</div>
						</td>
						<td>
							<div class="form-group form-md-line-input ">
								<?php $filterstrCat = mysqli_query($dbconn, "SELECT strJobCategory FROM `jobcategory` where iJobCategoryId='" . $rowfilter['iJobCategoryId'] . "' and  isDelete='0'");
								$rowCatData = mysqli_fetch_assoc($filterstrCat); ?>
								<?php echo "Title : " . $rowfilter['strJobTitle'] . '<br /> Experience :' . $rowfilter['strExperience'] . '<br /> Position :' . $rowfilter['iPosition']; ?>
							</div>
						</td>
						<td>
							<div class="form-group form-md-line-input">
								<a class="btn blue" href="#" data-toggle="modal" data-target="#exampleModal" onclick="return SetStudentData(<?php echo $rowfilter['stud_id']; ?>,<?php echo $rowfilter['iJobId']; ?>,<?php echo $rowfilter['iJobSubmissionId']; ?>,<?php echo $rowfilter['iJobStatus']; ?>,<?php echo $rowfilter['iSalary']; ?>,'<?php echo $rowfilter['strRemarks']; ?>')" title="STUDENT PLACEMENT"><i class="fa fa-check"></i></a>
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
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form role="form" method="POST" action="" name="frmparameter" id="frmparameter" enctype="multipart/form-data">
					<input type="hidden" value="AddStudentJobEntry" name="action" id="action">
					<input type="hidden" value="" name="stud_id" id="stud_id">
					<input type="hidden" value="" name="iJobSubmissionId" id="iJobSubmissionId">
					<input type="hidden" value="" name="JobId" id="JobId">
					<div class="form-body">
						<div class="row">
							<div class="form-group col-md-12">
								<label for="form_control_1">Status*</label>
								<select name="iStatus" id="iStatus" class="form-control" required="">
									<option value="0">Select Status</option>
									<option value="1">Pass</option>
									<option value="2">Fail</option>
									<option value="3">Not Attempted</option>
									<option value="4">Pass But Not Join</option>
								</select>
							</div>
							<div class="form-group col-md-12" id="divSalary" style="display:none;">
								<label for="form_control_1">Salary*</label>
								<input name="iSalary" id="iSalary" class="form-control" placeholder="Enter Salary">
							</div>
							<div class="form-group col-md-12">
								<label for="form_control_1">Remarks</label>
								<textarea name="strRemarks" id="strRemarks" class="form-control" placeholder="Enter Remarks" type="text" required=""></textarea>
							</div>
						</div>
					</div>
					<div class="form-actions noborder">
						<input class="btn blue margin-top-20" type="submit" id="Btnmybtn" value="Submit" name="submit">
						<button type="button" class="btn blue margin-top-20" onClick="checkclose();">Cancel</button>
					</div>
				</form>
			</div>
			<!-- <div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary">Save changes</button>
			</div> -->
		</div>
	</div>
</div>
<script>
	function SetStudentData(stud_id, iJobId, iJobSubmissionId, iJobStatus, iSalary, strRemarks) {
		$("#stud_id").val(stud_id);
		$("#JobId").val(iJobId);
		$("#iJobSubmissionId").val(iJobSubmissionId);
		$("#iStatus").val(iJobStatus);
		$("#iSalary").val(iSalary);
		$("#strRemarks").val(strRemarks);
		if (iJobStatus == 1) {
			$("#divSalary").show();
			$("#iSalary").val(iSalary);
			$("#iSalary").attr('required', true);
		} else {
			$("#divSalary").hide();
			$("#iSalary").val(0);
			$("#iSalary").removeAttr('required');
		}
	}

	$('#frmparameter').submit(function(e) {
		e.preventDefault();
		$('#loading').css("display", "block");
		$.ajax({
			type: 'POST',
			url: '<?php echo $web_url; ?>Supervisor/querydata.php',
			data: $('#frmparameter').serialize(),
			success: function(response) {
				if (response != 0) {
					$('#loading').css("display", "none");
					//$("#Btnmybtn").attr('disabled', 'disabled');
					alert('Added Sucessfully.');
					window.location.href = "";
				}
			}
		});
	});
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