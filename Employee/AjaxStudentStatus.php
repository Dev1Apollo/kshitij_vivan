<?php
error_reporting(0);
require_once('../common.php');
include('IsLogin.php');
$connect = new connect();
include ('User_Paging.php');

if ($_POST['action'] == 'ListUser') {
    if ($_REQUEST['month'] == NULL && $_REQUEST['month'] == '') {
        $con_date = implode(',', $_REQUEST['Year']);
    } else {
        $con_date = $_REQUEST['month']. '-'. implode(',',$_REQUEST['Year']);
    }
    
    $whereEmpId = " and studentadmission.branchId = '" . $_SESSION['branchid'] . "'";
    if ($_REQUEST['month'] == NULL && $_REQUEST['month'] == '') {
        $where.=" and DATE_FORMAT(STR_TO_DATE(studentcourse.dateOfJoining,'%d-%m-%Y'),'%Y') = DATE_FORMAT(STR_TO_DATE('" . $con_date . "','%Y'),'%Y')";
    } else {
        $where.=" and DATE_FORMAT(STR_TO_DATE(studentcourse.dateOfJoining,'%d-%m-%Y'),'%m-%Y') = DATE_FORMAT(STR_TO_DATE('" . '01-'. $con_date . "','%d-%m-%Y'),'%m-%Y')";
    }
    
    if ($_REQUEST['studentPortal_Id'] != NULL && isset($_REQUEST['studentPortal_Id']))
        $where .= " and studentadmission.studentPortal_Id='" . $_REQUEST['studentPortal_Id'] . "' ";
    if ($_REQUEST['iStudentStatus'] != NULL && isset($_REQUEST['iStudentStatus']))
        $where .= " and studentadmission.iStudentStatus='" . $_REQUEST['iStudentStatus'] . "' ";

    $filterstr = "SELECT studentadmission.*,studentcourse.* from studentadmission,studentcourse where studentcourse.stud_id=studentadmission.stud_id " . $where . $whereEmpId . " and studentcourse.istatus=1 GROUP BY studentcourse.stud_id,studentcourse.courseId ORDER BY STR_TO_DATE(studentcourse.EnrollmentDate,'%d-%m-%Y') asc";
    $countstr = "SELECT count(*) as TotalRow from studentadmission,studentcourse where studentcourse.stud_id=studentadmission.stud_id " . $where . $whereEmpId . " and studentcourse.istatus=1 ";

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
        $get_count = $resrowc['TotalRow'];
        ?>  


        <link href="<?php echo $web_url; ?>Employee/assets/global/plugins/datatables/datatables.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo $web_url; ?>Employee/assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" />
        <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script> 

        <form role="form"  method="POST" action="" name="frmparameter"  id="frmparameter" enctype="multipart/form-data">
            <input type="hidden" value="UpdateStudentStatus" name="action" id="action">
            <table class="table table-bordered table-hover center table-responsive dt-responsive" width="100%" id="tableC">
                <thead class="tbg">
                    <tr>
                        <th class="all">Sr.No</th>
                        <th class="desktop">Type</th>
                        <th class="desktop">Student Enrollment</th>
                        <th class="desktop">Date Of Admission</th>
                        <th class="desktop">Name Of Student</th>
                        <th class="desktop">Contact Number</th> 
                        <th class="desktop">Course</th>
                        <th class="desktop">Student Status</th>
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
                                <div class="form-group form-md-line-input "><?php echo $i; ?> 
                                </div>
                            </td>
                            <td>
                                <div class="form-group form-md-line-input "><?php
                                    if ($rowfilter['studentPortal_Id'] == 1) {
                                        echo 'Maac CG';
                                    } else if ($rowfilter['studentPortal_Id'] == 2) {
                                        echo 'Kshitij Vivan';
                                    } else if ($rowfilter['studentPortal_Id'] == 4) {
                                        echo 'Maac Satellite';
                                    } else if ($rowfilter['studentPortal_Id'] == 3) {
                                        echo 'Other';
                                    }
                                    ?> 
                                </div>
                            </td>
                            <td>
                                <div class="form-group form-md-line-input "><?php
                                    echo $rowfilter['studentEnrollment'];
                                    ?> 
                                </div>
                            </td>
                            <td>
                                <div class="form-group form-md-line-input "><?php
                                    echo $rowfilter['EnrollmentDate'];
                                    ?> 
                                </div>
                            </td>                                                   
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
                                    $filterCourse = mysqli_query($dbconn,"Select * from course where courseId in (" . $rowfilter['courseId'] . ") order by courseId DESC");
                                    $courseName = '';
                                    while ($rowcourse = mysqli_fetch_array($filterCourse)) {
                                        $courseName = $rowcourse['courseName'] . ",<br />" . $courseName;
                                    }
                                    echo $courseName = rtrim($courseName, ',');
                                    ?> 
                                </div>
                            </td>
                            <td>
                                <div class="form-group form-md-line-input ">
                                    <select name="iStudentStatus" id="iStudentStatus_<?php echo $rowfilter['stud_id']; ?>" class="form-control"> 
                                        <?php
                                        $filterStatus = mysqli_query($dbconn,"Select * from studentstatus where isDelete=0 and istatus=1");
                                        while ($rowStatus = mysqli_fetch_array($filterStatus)) {
                                            ?>
                                            <option value="<?php echo $rowStatus['studstatusid'] ?>" <?php
                                            if ($rowStatus['studstatusid'] == $rowfilter['iStudentStatus']) {
                                                echo 'selected';
                                            }
                                            ?> ><?php echo $rowStatus['studentStatusName']; ?></option>

                                        <?php } ?>
                                    </select>
                                </div>
                            </td>
                            <td>
                                <div class="form-actions noborder">
                                    <button class="btn blue margin-top-5" type="button" id="Btnmybtn" onclick="updateAttendanceDetail('<?php echo $rowfilter['stud_id']; ?>');"  name="submit">Submit</button>     
                                </div>
                            </td>
                            <?php
                        }
                        ?>
                    </tr>
                </tbody>
            </table>
        </form>
        <script src="<?php echo $web_url; ?>Employee/assets/global/plugins/datatables/datatables.js" type="text/javascript"></script>
        <script src="<?php echo $web_url; ?>Employee/assets/global/plugins/datatables/table-datatables-responsive.js" type="text/javascript"></script>
        <script>
                                        $(document).ready(function () {
                                            $('#tableC').DataTable({
                                            });
                                        });

                                        function updateAttendanceDetail(stud_id) {
//                                            alert(stud_id);
                                            var iStudentStatus = $('#iStudentStatus_' + stud_id).val();
//                                            alert(iStudentStatus);
                                            $('#loading').css("display", "block");
                                            $.ajax({
                                                type: 'POST',
                                                url: 'querydata.php',
                                                data: {action: "UpdateStudentStatus", stud_id: stud_id, iStudentStatus: iStudentStatus},
                                                success: function (response) {
//                                                    alert(response);
                                                    console.log(response);
                                                    if (response != 0)
                                                    {
                                                        $('#loading').css("display", "none");
                                                        $("#Btnmybtn").attr('disabled', 'disabled');
                                                        alert('Student Status Updated Sucessfully.');
                                                    } else {
                                                        $('#loading').css("display", "none");
                                                        $("#Btnmybtn").attr('disabled', 'disabled');
                                                        alert('Invalid Request.');
                                                    }
                                                }
                                            });
                                        }
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