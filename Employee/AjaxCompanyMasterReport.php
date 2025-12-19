<?php
error_reporting(0);
require_once('../common.php');
include('IsLogin.php');
$connect = new connect();
include('User_Paging.php');


if ($_POST['action'] == 'ListUser') {
    
	$where = "where 1=1 ";
	if (isset($_REQUEST['FormDate'])) {
		if ($_POST['FormDate'] != '') {
			$where .= " and  STR_TO_DATE(strEntryDate,'%d-%m-%Y') >= STR_TO_DATE('".$_POST['FormDate']."','%d-%m-%Y')";
		}
	}
	if (isset($_REQUEST['ToDate'])) {
		if ($_POST['ToDate'] != '') {
			$where .= " and STR_TO_DATE(strEntryDate,'%d-%m-%Y') <= STR_TO_DATE('".$_POST['ToDate']."','%d-%m-%Y')";
		}
	}

	$filterstr = "SELECT * FROM `company`  " . $where . " and isDelete='0'  and  iStatus='1' order by id desc";
	$countstr = "SELECT count(*) as TotalRow FROM `company` " . $where . " and isDelete='0' and  iStatus='1' ";

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
		<link href="<?php echo $web_url; ?>admin/assets/global/plugins/datatables/datatables.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo $web_url; ?>admin/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet" type="text/css" />
		<script src="<?php echo $web_url; ?>admin/assets/global/plugins/datatables/datatables.js" type="text/javascript"></script>
		<script src="<?php echo $web_url; ?>admin/assets/global/plugins/datatables/table-datatables-responsive.js" type="text/javascript"></script>
		<div class="table-responsive">
			<table class="table table-bordered table-hover center table-responsive" width="100%" id="tableC">
				<thead class="tbg">
					<tr>
						<th class="all">Sr.No</th>
						<th class="desktop">Month</th>
						<th class="desktop">Company Name</th>
						<th class="desktop">Contact Person</th>
						<th class="desktop">Mobile</th>
						<th class="desktop">Email</th>
						<th class="desktop">Desgination</th>
						<th class="desktop">Website</th>
						<th class="desktop">Address</th>
						<!--<th class="none">Upload date & time</th>-->
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
								<div class="form-group form-md-line-input "><?php 
								    if(isset($rowfilter['strEntryDate']) && $rowfilter['strEntryDate'] != ""){
								        echo date('M-Y',strtotime($rowfilter['strEntryDate']));
								    } else {
								        echo "";
								    }
								?>
								</div>
							</td>
							<td>
								<div class="form-group form-md-line-input "><?php echo $rowfilter['strCompanyName']; ?>
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
								<div class="form-group form-md-line-input "><?php echo $rowfilter['strWebsite']; ?>
								</div>
							</td>
							<td>
								<div class="form-group form-md-line-input "><?php echo substr($rowfilter['strAddress'],0,25) . '...'; ?>
								</div>
							</td>
							
							<!--<td>-->
							<!--	<div class="form-group form-md-line-input "><?php echo $rowfilter['strEntryDate']; ?>-->
							<!--	</div>-->
							<!--</td>-->
						<?php
					}
						?>
						</tr>
				</tbody>
			</table>
		</div>
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

if ($_REQUEST['action'] == 'Delete') {
	$data = array(
		"isDelete" => '1',
		"strEntryDate" => date('d-m-Y H:i:s')
	);
	$where = ' where id=' . $_REQUEST['ID'];
	$dealer_res = $connect->updaterecord($dbconn, 'company', $data, $where);
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
