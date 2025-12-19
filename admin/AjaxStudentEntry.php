<?php
error_reporting(0);
require_once('../common.php');
include('IsLogin.php');
$connect = new connect();
include ('User_Paging.php');

if ($_POST['action'] == 'ListUser') {
$where = "";

    if ($_POST['firstName'] != NULL && isset($_REQUEST['firstName']))
        $where.=" and  firstName like '%$_POST[firstName]%'";
    if ($_POST['surName'] != NULL && isset($_REQUEST['surName']))
        $where.=" and  surName like '%$_POST[surName]%'";
    if ($_POST['studentPortal_Id'] != NULL && isset($_REQUEST['studentPortal_Id']))
        $where.=" and  studentPortal_Id like '%$_POST[studentPortal_Id]%'";
    if ($_POST['student'] != NULL && isset($_REQUEST['student'])) {
        if ($_POST['student'] == 'Registered_Student') {
            $where .=" and isRegister = '1' and isAdmission = '0' ";
        } else if ($_POST['student'] == 'Enroll_Student') {
            $where .=" and isRegister = '1' and isAdmission = '1' ";
        }
    }
    if ($_POST['student'] == 'Enroll_Student') {
        $filterstr = "SELECT * FROM `studentadmission`,studentcourse where 1=1 and studentadmission.stud_id=studentcourse.stud_id " . $where . " and studentcourse.istatus=1 and studentadmission.isDelete='0' and  studentadmission.istatus='1' order by studentadmission.stud_id desc";
    } else {
        $filterstr = "SELECT * FROM `studentadmission` where 1=1 " . $where . " and isDelete='0' and  istatus='1'  order by studentadmission.stud_id desc";
    }
    $countstr = "SELECT count(*) as TotalRow FROM `studentadmission` where 1=1 " . $where . " and isDelete='0' and istatus='1'";

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

        <table class="table table-bordered table-hover center table-responsive dt-responsive" width="100%" id="tableC">
            <thead class="tbg">
                <tr>
                    <th class="all">Sr.No</th>
                    <th class="desktop">Student Name</th>
                    <th class="desktop">Details</th>
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
                            <div class="form-group form-md-line-input">
                                <?php if ($_POST['student'] == 'Enroll_Student') { ?>
                                    <a  class="btn blue" href="<?php echo $web_url; ?>admin/EditStudent.php?token=<?php echo $rowfilter['stud_id']; ?>" title="EDIT STUDENT"><i class="fa fa-edit"></i></a>
                                    <?php // if($rowfilter['studentPortal_Id'] != 2){ ?>
                                    <!--<button type="button" class="btn blue" value="<==>"  title="MOVE STUDENT" onclick="window.open('<?php // echo $web_url;   ?>admin/MoveStudent.php?token=<?php // echo $rowfilter['stud_id'];   ?>&refToken=<?php // echo $rowfilter['studentcourseId'];  ?>', 'popUpWindow', 'height=500,width=1250,left=100,top=100,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no,status=yes');"><i class="fa fa-exchange"></i></button>-->
                                    <?php // } ?>
                                    <!--<a  class="btn blue" href="<?php // echo $web_url;   ?>admin/EditEmi.php?token=<?php // echo $rowfilter['stud_id'];   ?>" title="EDIT EMI STUDENT"><i class="fa fa-edit"></i> <i class="fa fa-rupee"></i></a>-->
                                <?php } if ($_POST['student'] == 'Registered_Student') { ?>
                                    <a  class="btn blue" href="<?php echo $web_url; ?>admin/EditRegisterStudent.php?token=<?php echo $rowfilter['leadId']; ?>&stud_id=<?php echo $rowfilter['stud_id']; ?>" title="EDIT REGISTERED STUDENT"><i class="fa fa-edit"></i></a>
                                    <a  class="btn blue" onClick="javascript: return deletedata('Delete', '<?php echo $rowfilter['leadId']; ?>', '<?php echo $rowfilter['stud_id']; ?>');" title="STUDENT STUDENT"><i class="fa fa-trash-o iconshowFirst"></i></a>
                                <?php } ?>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <script src="<?php echo $web_url; ?>admin/assets/global/plugins/datatables/datatables.js" type="text/javascript"></script>
        <script src="<?php echo $web_url; ?>admin/assets/global/plugins/datatables/table-datatables-responsive.js" type="text/javascript"></script>
        <script>
                                $(document).ready(function () {
                                    $('#tableC').DataTable({
                                    });
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

if ($_REQUEST['action'] == 'Delete') {
    $data = array(
        'isDelete' => '1',
        'isRegister' => '0',
        "strEntryDate" => date('d-m-Y')
    );
    $where = "where stud_id='" . $_REQUEST['stud_id'] . "' and leadId='" . $_REQUEST['leadId'] . "' ";
    $update = $connect->updaterecord($dbconn, "studentadmission", $data, $where);

    $dataLead = array(
        "isRegister" => '0'
    );
    $where = ' where  leadId=' . $_REQUEST['leadId'];
    $dealer_resLead = $connect->updaterecord($dbconn, 'lead', $dataLead, $where);
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