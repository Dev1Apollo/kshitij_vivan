<?php
error_reporting(0);
require_once('../common.php');
include('IsLogin.php');
$connect = new connect();
include('User_Paging.php');

if ($_POST['action'] == 'ListUser') {
	$where = "where 1=1";
	if ($_REQUEST['month'] == NULL && $_REQUEST['month'] == '') {
		$con_date = implode(',', $_REQUEST['Year']);
	} else {
		$con_date = $_REQUEST['month'] . '-' . implode(',', $_REQUEST['Year']);
	}

	//$whereEmpId = " and studentadmission.branchId = '" . $_SESSION['branchid'] . "'";
    if ($_SESSION['EmployeeType'] == 'Supervisor') {
        if (isset($_POST['branchid']) && $_POST['branchid'] != ""){
            $where .= " and studentadmission.branchId='" . $_POST['branchid'] . "'";
        }
    } 
    /*else {
        $where .= " and studentadmission.branchId=" . $_SESSION['branchid'] . "'";
    }*/
	if ($_REQUEST['month'] == NULL && $_REQUEST['month'] == '') {
		//$where .= " and DATE_FORMAT(STR_TO_DATE(studentcourse.dateOfJoining,'%d-%m-%Y'),'%Y') = DATE_FORMAT(STR_TO_DATE('" . $con_date . "','%Y'),'%Y')";
		$where .= " and DATE_FORMAT(STR_TO_DATE(studentcourse.EnrollmentDate,'%d-%m-%Y'),'%Y') = DATE_FORMAT(STR_TO_DATE('" . $con_date . "','%Y'),'%Y')";
	} else {
		//$where .= " and DATE_FORMAT(STR_TO_DATE(studentcourse.dateOfJoining,'%d-%m-%Y'),'%m-%Y') = DATE_FORMAT(STR_TO_DATE('" . '01-' . $con_date . "','%d-%m-%Y'),'%m-%Y')";
		$where .= " and DATE_FORMAT(STR_TO_DATE(studentcourse.EnrollmentDate,'%d-%m-%Y'),'%m-%Y') = DATE_FORMAT(STR_TO_DATE('" . '01-' . $con_date . "','%d-%m-%Y'),'%m-%Y')";
	}

	if ($_REQUEST['Search_Status'] != NULL && isset($_REQUEST['Search_Status']))
		$where .= " and studentadmission.iJobStatus='" . $_REQUEST['Search_Status'] . "' ";

	// if (isset($_REQUEST['Search_Company']) &&  $_POST['Search_Company'] != NULL)
	// 	$where .= " and  iCompanyId in (select id from company where  strCompanyName like '%$_POST[Search_Company]%')";
	// if (isset($_REQUEST['Search_Student']) && $_POST['Search_Student'] != NULL)
	// 	$where .= " and  (firstName like '%$_POST[Search_Student]%' or surName like '%$_POST[Search_Student]%')";
	// if (isset($_REQUEST['Search_Status']) && $_POST['Search_Status'] != NULL)
	// 	$where .= " and  iJobStatus like '%$_POST[Search_Status]%'";


	/*$filterstr = "SELECT *,studentadmission.iJobStatus as jobstatus FROM `studentadmission` inner join studentcourse on  studentcourse.stud_id=studentadmission.stud_id left join studentjobsubmission on studentadmission.stud_id=studentjobsubmission.iStudId  " . $where . $whereEmpId .  " and studentadmission.isDelete='0' and  studentadmission.istatus='1' group by studentcourse.stud_id order by STR_TO_DATE(studentcourse.EnrollmentDate,'%d-%m-%Y') asc";
	$countstr = "SELECT count(*) as TotalRow FROM `studentadmission` inner join studentcourse on  studentcourse.stud_id=studentadmission.stud_id left join studentjobsubmission on studentadmission.stud_id=studentjobsubmission.iStudId  " . $where . $whereEmpId .  " and studentadmission.isDelete='0' and studentadmission.istatus='1'";*/
	$filterstr = "SELECT *,studentadmission.iJobStatus as jobstatus FROM `studentadmission` inner join studentcourse on  studentcourse.stud_id=studentadmission.stud_id left join studentjobsubmission on studentadmission.stud_id=studentjobsubmission.iStudId  " . $where . $whereEmpId .  " and studentadmission.isDelete='0' and  studentadmission.istatus='1' group by studentcourse.stud_id order by STR_TO_DATE(studentcourse.EnrollmentDate,'%d-%m-%Y') asc";
	$countstr = "SELECT count(*) as TotalRow FROM `studentadmission` inner join studentcourse on  studentcourse.stud_id=studentadmission.stud_id left join studentjobsubmission on studentadmission.stud_id=studentjobsubmission.iStudId  " . $where . $whereEmpId .  " and studentadmission.isDelete='0' and studentadmission.istatus='1'";

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
		<div class="table-responsive">
			<style>
				.table thead tr th {
					font-size: 12px;
				}

				.table td,
				.table th {
					font-size: 12px;
				}
			</style>
			<form role="form" method="POST" action="" name="frmparameter" id="frmparameter" enctype="multipart/form-data">
				<div style="display: flex;
    justify-content: end;" class="row d-flex justify-content-end">

					<div class="col-md-4">
						<select name="iUpdatedJobStatus" id="iUpdatedJobStatus" class="form-control" required="">
							<option value="">Select Status</option>
							<option value="0">Required</option>
							<option value="1">Not Required</option>
							<!-- <option value="2">Placed</option> -->
						</select>
					</div>
					<div style="text-align:end;" class="col-md-2">
						<input type="hidden" value="MultiUpdateJobStudentStatus" name="action" id="action">
						<input class="btn btn-sm red" type="submit" id="Btnmybtn" value="Update Status" name="submit" />
					</div>
				</div>
				<hr />
				<table class="table table-bordered table-hover center  dt-responsive" width="100%" id="tableC">
					<thead class="tbg">
						<tr>

							<th class="all">Sr. No</th>
							<!-- <th class="desktop">Booking Id</th> -->
							<th class="desktop">Student Enrollment</th>
							<th class="desktop">Month Of Admission</th>
							<!-- <th class="desktop">Date Of Admission</th> -->
							<th class="desktop">Name Of Student</th>
							<th class="none">Contact Number</th>
							<th class="none">Course</th>
							<th class="desktop">Balance Amount</th>
							<th class="desktop">Student Status</th>
							<th class="desktop">Job Status</th>
							<th class="desktop">Action</th>
							<th class="desktop">
								<div class="md-checkbox">
									<input type="checkbox" onclick="javascript:CheckAll();" id="check_listall" class="md-check" value="">
									<label for="check_listall">
										<span></span>
										<span class="check"></span>
										<span class="box"></span>
									</label>
								</div>
							</th>
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
								<!-- <td>
									<div class="form-group form-md-line-input "><?php
																				echo $rowfilter['bookingId'];
																				?>
									</div>
								</td> -->
								<td>
									<div class="form-group form-md-line-input "><?php
																				echo $rowfilter['studentEnrollment'];
																				?>
									</div>
								</td>
								<td>
									<div class="form-group form-md-line-input "><?php
																				echo date("M'Y", strtotime($rowfilter['EnrollmentDate']));
																				?>
									</div>
								</td>
								<!-- <td>
									<div class="form-group form-md-line-input "><?php
																				echo $rowfilter['EnrollmentDate'];
																				?>
									</div>
								</td> -->

								<td>
									<div class="form-group form-md-line-input "><?php
																				echo $rowfilter['title'] . ' ' . $rowfilter['firstName'] . ' ' . $rowfilter['middleName'] . ' ' . $rowfilter['surName'];
																				?>
									</div>
								</td>
								<td>
									<div class="form-group form-md-line-input "><?php echo $rowfilter['mobileOne']; ?>
									</div>
								</td>
								<td>
									<div class="form-group form-md-line-input "><?php
																				$filterCourse = mysqli_query($dbconn, "Select * from course where courseId in (" . $rowfilter['courseId'] . ") order by courseId DESC");
																				$courseName = '';
																				while ($rowcourse = mysqli_fetch_array($filterCourse)) {
																					$courseName = $rowcourse['courseName'] . "," . $courseName;
																				}
																				echo $courseName = rtrim($courseName, ',');
																				?>
									</div>
								</td>
								<td>
									<div class="form-group form-md-line-input "><?php
																				$filterfee = "select sum(amount) as recievedfee from studentfee where stud_id='" . $rowfilter['stud_id'] . "' and studentcourseId='" . $rowfilter['studentcourseId'] . "' ORDER by STR_TO_DATE(payDate,'%d-%m-%Y') DESC";
																				$rowFee = mysqli_fetch_array(mysqli_query($dbconn, $filterfee));
																				$balanceAmount = $rowfilter['offeredfee'] - $rowFee['recievedfee'];
																				echo $balanceAmount;
																				//$walkin[3] += $balanceAmount;
																				?>
									</div>
								</td>
								<td>
									<div class="form-group form-md-line-input ">
										<?php
										if ($rowfilter['iStudentStatus'] == 0) {
											echo "Pending";
										} else {
											$filterStatus = mysqli_fetch_array(mysqli_query($dbconn, "Select * from studentstatus where studstatusid=" . $rowfilter['iStudentStatus'] . " and isDelete=0 and istatus=1"));
											echo $filterStatus['studentStatusName'];
										}
										?>
									</div>
								</td>
								<td>
									<div class="form-group form-md-line-input ">
										<select name="iJobStatus" id="iJobStatus_<?php echo $rowfilter['stud_id']; ?>" class="form-control" required="">
											<option value="">Select Status</option>
											<option value="0" <?php if ($rowfilter['jobstatus'] == 0) {
																	echo 'selected';
																} ?>>Required</option>
											<option value="1" <?php if ($rowfilter['jobstatus'] == 1) {
																	echo 'selected';
																} ?>>Not Required</option>
											<option value="2" <?php if ($rowfilter['jobstatus'] == 2) {
																	echo 'selected';
																} ?>>Placed</option>
										</select>
									</div>
								</td>
								<td>
									<div class="form-actions noborder">
										<button class="btn blue margin-top-5" type="button" id="Btnmybtn" onclick="updateAttendanceDetail('<?php echo $rowfilter['stud_id']; ?>');" name="submit"><i class="fa fa-check"></i></button>
									</div>
								</td>
								<td>
									<div class="md-checkbox">
										<input type="hidden" name="stud_id[]" id="stud_id<?php echo $i; ?>" class="md-check" value="<?php echo $rowfilter['stud_id']; ?> ">
										<input type="checkbox" name="check_list[]" id="check_list<?php echo $i; ?>" class="md-check" value="<?php echo $rowfilter['stud_id']; ?> ">
										<label for="check_list<?php echo $i; ?>">
											<span></span>
											<span class="check"></span>
											<span class="box"></span>
										</label>
									</div>
								</td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</form>
		</div>
		<script src="<?php echo $web_url; ?>Supervisor/assets/global/plugins/datatables/datatables.js" type="text/javascript">
		</script>
		<script src="<?php echo $web_url; ?>Supervisor/assets/global/plugins/datatables/table-datatables-responsive.js" type="text/javascript"></script>

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
	// $('#frmparameter').submit(function(e) {
	// 	e.preventDefault();
	// 	var $form = $(this);
	// 	$('#loading').css("display", "block");
	// 	$.ajax({
	// 		type: 'POST',
	// 		url: '<?php echo $web_url; ?>Supervisor/querydata.php',
	// 		data: $('#frmparameter').serialize(),
	// 		success: function(response) {
	// 			if (response != 0) {
	// 				$('#loading').css("display", "none");
	// 				$("#Btnmybtn").attr('disabled', 'disabled');
	// 				alert('Added Sucessfully.');
	// 				//window.location.href="";
	// 			}
	// 		}
	// 	});
	// });
</script>