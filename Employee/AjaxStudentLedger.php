<?php
error_reporting(0);
require_once('../common.php');
include('IsLogin.php');
$connect = new connect();
include ('User_Paging.php');


if ($_POST['action'] == 'ListUser') {
    $where = "where 1=1";

    if ($_POST['firstName'] != NULL && isset($_POST['firstName']))
        $where.=" and  firstName like '%$_POST[firstName]%'";
    if ($_POST['surName'] != NULL && isset($_POST['surName']))
        $where.=" and  surName like '%$_POST[surName]%'";
    if ($_POST['leaduniqueid'] != NULL && isset($_POST['leaduniqueid']))
        $where.=" and  leaduniqueid like '%$_POST[leaduniqueid]%'";

    $filterstr = "SELECT * FROM studentadmission  " . $where . " and isRegister = '1' and isAdmission = '1' and branchId=" . $_SESSION['branchid'] . "";
//    $filterstr = "SELECT * FROM `studentadmission`  " . $where . " and isDelete='0'  and  istatus='1' order by  stud_id desc";
    $countstr = "SELECT count(*) as TotalRow FROM FROM studentadmission  " . $where . " and isRegister = '1' and isAdmission = '1' and branchId=" . $_SESSION['branchid'] . "";

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
//    print_r($filterstr);
//    exit;
    $resultfilter = mysqli_query($dbconn,$filterstr);
//    echo mysqli_num_rows($resultfilter);
//    print_r($resultfilter);
//    exit;
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
                    <th class="desktop">Enrollment</th>
                    <th class="desktop">Student Name</th>
                    <th class="desktop">Course Name</th>
<!--                    <th class="desktop">Address</th>
                    <th class="desktop">Details</th>-->

<!--                    <th class="desktop">Action</th>-->
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
                                echo $rowfilter['studentEnrollment'];
                                ?> 
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><a href="StudentLedgerFees.php?token=<?php echo $rowfilter['stud_id']; ?>"><?php echo $rowfilter['title'] . ' ' . $rowfilter['firstName'] . ' ' . $rowfilter['middleName'] . ' ' . $rowfilter['surName']; ?> </a>
                            </div>
                        </td> 
                        <td>
                            <div class="form-group form-md-line-input "><?php
                                $stundentData = mysqli_fetch_array(mysqli_query($dbconn,"SELECT * FROM `studentcourse` where stud_id=" . $rowfilter['stud_id'] . " and studentcourse.istatus=1"));
                                $stundentCourse = mysqli_query($dbconn,"SELECT * FROM course where courseId in (" . $stundentData['courseId'] . ") order by courseId DESC ");
                                $coureName = '';
                                while ($courseName = mysqli_fetch_array($stundentCourse)) {
                                    $coureName = $courseName['courseName'] . ',' . $coureName;
                                }
                                echo $coureName = rtrim($coureName, ',');
                                ?> 
                            </div>
                        </td>
<!--                        <td>
                            <div class="form-group form-md-line-input "><?php // echo $rowfilter['address']; ?> 
                            </div>
                        </td>-->
<!--                        <td>
                            <div class="form-group form-md-line-input "><?php
//                                if ($rowfilter['email'] != '' && $rowfilter['mobileOne'] != '') {
//                                    echo 'E:' . $rowfilter['email'] . ' <br>M:' . $rowfilter['mobileOne'];
//                                } elseif ($rowfilter['email'] != '') {
//                                    echo 'E:' . $rowfilter['email'];
//                                } else if ($rowfilter['mobileOne'] != '') {
//                                    echo 'M:' . $rowfilter['mobileOne'];
//                                } else {
//                                    echo '<center>-</center>';
//                                }
                                ?>
                            </div>
                        </td>-->


<!--                        <td>
                            <div class="form-group form-md-line-input">
                                  <a  class="btn blue" href="<?php // echo $web_url; ?>Employee/StudentLedgerFees.php?token=<?php // echo $rowfilter['stud_id']; ?>" title="STUDENT ENROLLMENT FEES"><i class="fa fa-bars"></i></a>
                                
                                
                                            <a  class="btn blue" href="<?php // echo $web_url; ?>Employee/EditCustomerEntry.php?token=<?php // echo $rowfilter['customerEntryId']; ?>" title="Edit"><i class="fa fa-edit iconshowFirst"></i></i></a>
                                <a  class="btn blue" onClick="javascript: return deletedata('Delete', '<?php // echo $rowfilter['customerEntryId']; ?>');"   title="Delete"><i class="fa fa-trash-o iconshowFirst"></i></a>
                              
                                    <a  class="btn blue" href="<?php // echo $web_url; ?>Employee/EditRegisterStudent.php?token=<?php // echo $rowfilter['leadId']; ?>" title="EDIT REGISTERED STUDENT"><i class="fa fa-edit"></i></a>
                            </div>
                        </td>-->

                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <script src="<?php echo $web_url; ?>Employee/assets/global/plugins/datatables/datatables.js" type="text/javascript"></script>
        <script src="<?php echo $web_url; ?>Employee/assets/global/plugins/datatables/table-datatables-responsive.js" type="text/javascript"></script>
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


//if ($_REQUEST['action'] == 'Delete') {
//    $data = array(
//        "isDelete" => '1',
//        "strEntryDate" => date('d-m-Y H:i:s')
//    );
//    $where = ' where  	stud_id=' . $_REQUEST['ID'];
//    $dealer_res = $connect->updaterecord($dbconn,'studentadmission', $data, $where);
//}
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

