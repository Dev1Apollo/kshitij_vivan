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

	// $filterstr = "SELECT * FROM `studentadmission` left join studentjobsubmission on studentadmission.stud_id=studentjobsubmission.iStudId " . $where . " and studentadmission.isDelete='0' and  studentadmission.istatus='1' and studentadmission.iJobStatus='1' and branchId=" . $_SESSION['branchid'] . " order by stud_id desc";
	// $countstr = "SELECT count(*) as TotalRow FROM `studentadmission` left join studentjobsubmission on studentadmission.stud_id=studentjobsubmission.iStudId " . $where . " and studentadmission.isDelete='0' and studentadmission.istatus='1' and studentadmission.iJobStatus='1' and branchId=" . $_SESSION['branchid'] . "";
	$filterstr = "SELECT * FROM `studentadmission` " . $where . " and isDelete='0' and  istatus='1' and iJobStatus='1' and branchId=" . $_SESSION['branchid'] . " order by stud_id desc";
	$countstr = "SELECT count(*) as TotalRow FROM `studentadmission` " . $where . " and isDelete='0' and istatus='1' and iJobStatus='1' and branchId=" . $_SESSION['branchid'] . "";
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
					<!-- <th class="desktop">Remarks</th> -->
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
						<!-- <td>
							<div class="form-group form-md-line-input "><?php echo $rowfilter['strRemarks']; ?>
							</div>
						</td> -->
						<td>
							<div class="form-group form-md-line-input">
								<a class="btn blue" onclick="SetStudentData(<?php echo $rowfilter['stud_id']; ?>)" title="STUDENT SENT TO REQUIRED LIST"><i class="fa fa-check"></i></a>
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
if ($_REQUEST['action'] == 'sendtorequiredlist') {
	$data = array(
		'iJobStatus' => '0'
	);
	$where = ' where  stud_id =' . $_POST['stud_id'] . ' ';
	$connect->updaterecord($dbconn, 'studentadmission', $data, $where);
	echo $_POST['stud_id'];
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