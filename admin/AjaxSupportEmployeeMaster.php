<?php
error_reporting(0);
require_once('../common.php');
include('IsLogin.php');
$connect = new connect();
include ('User_Paging.php');


if ($_POST['action'] == 'ListUser') {
    $where = "where 1=1 ";
    if (isset($_REQUEST['Search_Txt'])) {
        if ($_POST['Search_Txt'] != '') {

            $where.=" and  support_emp_name like '%$_POST[Search_Txt]%'";
        }
    }

    $filterstr = "SELECT * FROM `support_employee`  " . $where . " and isDelete='0' order by  id desc";
    $countstr = "SELECT count(*) as TotalRow FROM `support_employee` " . $where . " and isDelete='0'";

    $resrowcount = mysqli_query($dbconn,$countstr);
    $resrowc = mysqli_fetch_array($resrowcount);
    $totalrecord = $resrowc['TotalRow'];
    $per_page = $cateperpaging;
    $total_pages = ceil($totalrecord / $per_page);
    $page = $_REQUEST['Page'] - 1;
    $startpage = $page * $per_page;
    $show_page = $page + 1;



    $filterstr = $filterstr . " LIMIT $startpage, $per_page";
// echo $filterstr;


    $resultfilter = mysqli_query($dbconn,$filterstr);
    if (mysqli_num_rows($resultfilter) > 0) {
        $i = 0;
         $serial = 0;
         $serial = ($page * $per_page);
        ?>  
        <link href="<?php echo $web_url; ?>admin/assets/global/plugins/datatables/datatables.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo $web_url; ?>admin/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet" type="text/css" />
        <script src="<?php echo $web_url; ?>admin/assets/global/plugins/datatables/datatables.js" type="text/javascript"></script>

        <script src="<?php echo $web_url; ?>admin/assets/global/plugins/datatables/table-datatables-responsive.js" type="text/javascript"></script>

        <form role="form" method="POST" action="" name="frmCourseStatus" id="frmCourseStatus" enctype="multipart/form-data">
            <input type="hidden" value="supportEmployeeMaster" name="action" id="action">
            <input class="btn btn-sm red margin-top-20" type="submit" id="Btnmybtn" value="Inactive" name="submit" />
            <div class="table-responsive">
            <table class="table table-bordered table-hover center table-responsive" width="100%" id="tableC">
                <thead class="tbg">
                    <tr>
                        <th>
                            <div class="md-checkbox">
                                <input type="checkbox" onclick="javascript:CheckAll();" id="check_listall" class="md-check"
                                    value="">
                                <label for="check_listall">
                                    <span></span>
                                    <span class="check"></span>
                                    <span class="box"></span>
                                </label>
                            </div>
                        </th>
                        <th class="all">Sr.No</th>
                        <th class="desktop">Expense Name</th>
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
                                <div class="md-checkbox">
                                    <input type="hidden" name="SupportEmployeeId[]" id="smsid<?php echo $i; ?>" class="md-check"
                                        value="<?php echo $rowfilter['id']; ?> ">
                                    <input type="checkbox" name="check_list[]" id="check_list<?php echo $i; ?>" class="md-check"
                                        value="<?php echo $rowfilter['id']; ?> ">
                                    <label for="check_list<?php echo $i; ?>">
                                        <span></span>
                                        <span class="check"></span>
                                        <span class="box"></span>
                                    </label>
                                </div>
                            </td>
                            <td>
                                <div class="form-group form-md-line-input "><?php echo $serial; ?> 
                                </div>
                            </td>
                            <td>
                                <div class="form-group form-md-line-input "><?php echo $rowfilter['support_emp_name']; ?> 
                                </div>
                            </td>
    
                            <td>
                                <div class="form-group form-md-line-input "> 
                                    <?php if ($rowfilter['istatus'] == 0) { ?>
                                        <a class="btn red"
                                            onClick="javascript: return deletedata('Active', '<?php echo $rowfilter['id']; ?>');"
                                            title="Inactive"><i class="fa fa-times iconshowFirst"></i></a>
                                    <?php } else { ?>
                                        <a class="btn green"
                                            onClick="javascript: return deletedata('Inactive', '<?php echo $rowfilter['id']; ?>');"
                                            title="Active"><i class="fa fa-check iconshowFirst"></i></a>
                                    <?php } ?>
                                    <a class="btn blue" onClick="javascript: return setEditdata('<?php echo $rowfilter['id']; ?>');"  title="Edit"><i class="fa fa-edit iconshowFirst"></i></a> 
    
                                    <a  class="btn blue" onClick="javascript: return deletedata('Delete', '<?php echo $rowfilter['id']; ?>');"   title="Delete"><i class="fa fa-trash-o iconshowFirst"></i></a>
                                </div>
                            </td>
    
                            <?php
                        }
                        ?>
    
                    </tr>
                </tbody>
            </table>

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
    $dealer_res = $connect->updaterecord($dbconn,'support_employee', $data, $where);
}
if ($_REQUEST['action'] == 'Inactive') {
	$data = array(
		"istatus" => '0',
		"strEntryDate" => date('d-m-Y H:i:s')
	);
	$where = ' where id=' . $_REQUEST['ID'];
	$dealer_res = $connect->updaterecord($dbconn, 'support_employee', $data, $where);
}

if ($_REQUEST['action'] == 'Active') {
	$data = array(
		"istatus" => '1',
		"strEntryDate" => date('d-m-Y H:i:s')
	);
	$where = ' where id=' . $_REQUEST['ID'];
	$dealer_res = $connect->updaterecord($dbconn, 'support_employee', $data, $where);
}
?>
<script type="text/javascript">
$('#frmCourseStatus').submit(function(e) {
    e.preventDefault();
    var $form = $(this);
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
                alert('Inquiry Source Inactivated.');
                window.location.href = '';
            } else {
                $('#loading').css("display", "none");
                $("#Btnmybtn").attr('disabled', 'disabled');
                alert('Invalid Request');
                window.location.href = '';
            }
        }
    });
});

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
</script>
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