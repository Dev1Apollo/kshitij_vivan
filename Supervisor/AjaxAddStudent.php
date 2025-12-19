<?php
error_reporting(0);
require_once('../common.php');
include('IsLogin.php');
$connect = new connect();
include('User_Paging.php');

if ($_POST['action'] == 'ListUser') {
	$where = " and  1=1";
	//$whereEmpId = " and studentadmission.branchId = '" . $_SESSION['branchid'] . "'";
	if ($_SESSION['EmployeeType'] == 'Supervisor') {
        if (isset($_POST['branchid']) && $_POST['branchid'] != ""){
            $where .= " and studentadmission.branchId='" . $_POST['branchid'] . "'";
        }
    }
	if (isset($_REQUEST['firstName']) &&  $_POST['firstName'] != NULL)
		$where .= " and  firstName like '%$_POST[firstName]%'";
	if (isset($_REQUEST['surName']) && $_POST['surName'] != NULL)
		$where .= " and  surName like '%$_POST[surName]%'";
	if (isset($_REQUEST['studentPortal_Id']) && $_POST['studentPortal_Id'] != NULL)
		$where .= " and  studentPortal_Id like '%$_POST[studentPortal_Id]%'";

	// if ($_POST['student'] != NULL && isset($_REQUEST['student'])) {
	// 	if ($_POST['student'] == 'Registered_Student') {
	// 		$where .= " and isRegister = '1' and isAdmission = '0' ";
	// 	} else if ($_POST['student'] == 'Enroll_Student') {
	// 		$where .= " and isRegister = '1' and isAdmission = '1' ";
	// 	}
	// }

	// $filterstr = "SELECT * FROM `studentadmission`  " . $where . " and stud_id not in (select iStudId from studentjobsubmission sj where studentadmission.stud_id=sj.iStudId and  sj.iJobId='".$_POST['JobId']."') and isDelete='0' and  istatus='1' and iJobStatus='0' and branchId=" . $_SESSION['branchid'] . " order by stud_id desc";
	// $countstr = "SELECT count(*) as TotalRow FROM `studentadmission` " . $where . " and stud_id not in (select iStudId from studentjobsubmission sj where studentadmission.stud_id=sj.iStudId and  sj.iJobId='".$_POST['JobId']."') and isDelete='0' and istatus='1' and iJobStatus='0' and branchId=" . $_SESSION['branchid'] . "";
	$filterstr = "SELECT studentadmission.*,studentcourse.* from studentadmission,studentcourse where studentcourse.stud_id=studentadmission.stud_id " . $where . $whereEmpId . " and studentcourse.istatus=1 and studentcourse.courseId in (SELECT courseId from course) and studentadmission.stud_id not in (select iStudId from studentjobsubmission sj where studentadmission.stud_id=sj.iStudId and  sj.iJobId='" . $_POST['JobId'] . "') and studentadmission.iJobStatus = 0 GROUP BY studentcourse.stud_id,studentcourse.courseId ORDER BY STR_TO_DATE(studentcourse.EnrollmentDate,'%d-%m-%Y') asc";
	$countstr = "SELECT count(*) as TotalRow, studentadmission.*,studentcourse.* from studentadmission,studentcourse where studentcourse.stud_id=studentadmission.stud_id " . $where . $whereEmpId . "  and studentcourse.istatus=1 and  studentcourse.courseId in (SELECT courseId from course) and studentadmission.stud_id not in (select iStudId from studentjobsubmission sj where studentadmission.stud_id=sj.iStudId and  sj.iJobId='" . $_POST['JobId'] . "') and studentadmission.iJobStatus = 0";

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
			<form role="form" method="POST" action="" name="frmCourseStatus" id="frmCourseStatus" enctype="multipart/form-data">
				<input type="hidden" value="MultiArrangeInterview" name="action" id="action">
				<input type="hidden" value="<?= $_POST['JobId'] ?>" name="iJobId" id="iJobId">
				<input class="btn btn-sm red margin-top-20" style="float: right;" type="submit" id="Btnmybtn" value="Arrange Interview" name="submit" />

				<table class="table table-bordered table-hover center table-responsive dt-responsive" width="100%" id="tableC">
					<thead class="tbg">
						<tr>

							<th class="all">Sr.No</th>
							<!-- <th class="desktop">Booking Id</th> -->
							<th class="desktop">Student Enrollment</th>
							<th class="desktop">Month Of Admission</th>
							<!-- <th class="desktop">Date Of Admission</th> -->
							<th class="desktop">Name Of Student</th>
							<th class="none">Contact Number</th>
							<th class="none">Course</th>
							<!-- <th class="desktop">Actual Fee</th>
                        <th class="desktop">Offered Fee</th>
                        <th class="desktop">Till Date Payment Receive</th>
                        <th class="desktop">Last Date Of Receipt</th>
                        <th class="desktop">Pending Days</th> -->
							<th class="desktop">Balance Amount</th>
							<th class="desktop">Student Status</th>
							<!-- <th class="desktop">Remark</th> -->
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
								<!-- <td>
							<div class="form-group form-md-line-input "><?php
																		echo $rowfilter['fee'];
																		?>
							</div>
						</td>
						<td>
							<div class="form-group form-md-line-input "><?php
																		echo $rowfilter['offeredfee'];
																		?>
							</div>
						</td>
						<td>
							<div class="form-group form-md-line-input "><?php
																		$filterfee = "select sum(amount) as recievedfee from studentfee where stud_id='" . $rowfilter['stud_id'] . "' and studentcourseId='" . $rowfilter['studentcourseId'] . "' ORDER by STR_TO_DATE(payDate,'%d-%m-%Y') DESC";
																		$rowFee = mysqli_fetch_array(mysqli_query($dbconn, $filterfee));
																		echo $rowFee['recievedfee'];
																		$walkin[2] += $rowFee['recievedfee'];
																		?>
							</div>
						</td>
						<td>
							<div class="form-group form-md-line-input "><?php
																		$filterpayDate = "select payDate,comments from studentfee where stud_id='" . $rowfilter['stud_id'] . "' and studentcourseId='" . $rowfilter['studentcourseId'] . "' ORDER by STR_TO_DATE(payDate,'%d-%m-%Y') DESC limit 1";
																		$rowPay = mysqli_fetch_array(mysqli_query($dbconn, $filterpayDate));
																		echo $rowPay['payDate'];
																		?>
							</div>
						</td>
						<td>
							<div class="form-group form-md-line-input "><?php
																		$currentDate = date('Y-m-d');
																		$LastPay = date('Y-m-d', strtotime($rowPay['payDate']));
																		$date1 = date_create($currentDate);
																		$date2 = date_create($LastPay);
																		$diff = date_diff($date2, $date1);
																		echo $diff->format("%R%a days");
																		?>
							</div>
						</td> -->
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
									<div class="form-group form-md-line-input "><?php
																				if ($rowfilter['iStudentStatus'] == 0) {
																					echo "Pending";
																				} else {
																					$filterStatus = mysqli_fetch_array(mysqli_query($dbconn, "Select * from studentstatus where studstatusid=" . $rowfilter['iStudentStatus'] . " and isDelete=0 and istatus=1"));
																					echo $filterStatus['studentStatusName'];
																				}
																				?>
									</div>
								</td>
								<!-- <td>
							<div class="form-group form-md-line-input "><?php
																		echo $rowPay['comments'];
																		?>
							</div>
						</td> -->
								<td>
									<div class="form-group form-md-line-input">
										<a class="btn blue" onclick="return SetStudentData(<?php echo $rowfilter['stud_id']; ?>)" title="STUDENT ARRANGE INTERVIEW"><i class="fa fa-check"></i></a>
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
				<form>
		</div>
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
<?php
if ($_REQUEST['action'] == 'ArrangeInterview') {
	$filterstr = "SELECT iCompanyId FROM `jobmaster`  where  isDelete='0'  and  iJobId=" . $_POST['JobId'] . "";
	$result = mysqli_query($dbconn, $filterstr);
	$row = mysqli_fetch_array($result);
	$jobData = array(
		"iJobId" => $_POST['JobId'],
		"iCompanyId" => $row['iCompanyId'],
		"iStudId" => $_POST['stud_id'],
		// "iJobStatus" => $_POST['iStatus'],
		// "strRemarks" => $_POST['strRemarks'],
		"strInterviewDate" => date('d-m-Y'),
		"strEntryDate" => date('d-m-Y H:i:s'),
		"strIP" => $_SERVER['REMOTE_ADDR']
	);
	$dealer_res = $connect->insertrecord($dbconn, 'studentjobsubmission', $jobData);

	// $data = array(
	// 	'	' => 1
	// );
	// $where = ' where  stud_id =' . $_POST['stud_id'] . ' ';
	// $connect->updaterecord($dbconn, 'studentadmission', $data, $where);
	// echo $_POST['stud_id'];
}
?>
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

	$('#frmCourseStatus').submit(function(e) {
		e.preventDefault();
		var $form = $(this);
		var errMsg = 'Are you sure to arrange interview?';
		if (confirm(errMsg)) {
			$('#loading').css("display", "block");
			$.ajax({
				type: 'POST',
				url: 'querydata.php',
				data: $('#frmCourseStatus').serialize(),
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
		}
	});
</script>