<?php
ob_start();
error_reporting(E_ALL);
require_once '../common.php';
$connect = new connect();
include('IsLogin.php');
?>
<?php
$stud_id = $_REQUEST['token'];
$query = mysqli_fetch_array(mysqli_query($dbconn,"SELECT * FROM `studentadmission` inner join studentcourse on studentcourse.stud_id = studentadmission.stud_id  where studentcourse.istatus=1 and studentadmission.stud_id=" . $stud_id));
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title><?php echo $ProjectName; ?> | Student Course Details Data </title>
        <?php include_once './include.php'; ?>
        <link href="assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet" type="text/css" />
    </head>
    <body class="page-container-bg-solid page-boxed">
        <?php include_once './header.php'; ?>
        <div style="display: none; z-index: 10060;" id="loading">
            <img id="loading-image" src="<?php echo $web_url; ?>Employee/images/loader1.gif">
        </div>
        <div class="page-container">        
            <div class="page-content-wrapper">
                <div class="page-content">
                    <div class="container">
                        <div class="page-content-inner">
                            <div class="col-md-2">
                                <?php include_once './menu-admission.php'; ?>
                            </div>
                            <div class="col-md-10" id="studentcoursefrom">
                                <div class="portlet light ">
                                    <div class="portlet-title">
                                        <div class="caption grey-gallery">
                                            <i class="icon-settings grey-gallery"></i>
                                            <span class="caption-subject bold uppercase" id="listdetail">Student Student Course Details Data</span>
                                        </div>
                                        <a class="btn blue pull-right" href="StudentEntry.php">Back To Enrollment</a>
                                        <a class="btn blue pull-right" style="margin-right: 5px !important;" onclick="exportPDFdata();">Print Emi</a>
                                    </div>
                                    <input type="hidden" value="<?php echo $_REQUEST['token']; ?>" id="stud_id" name="stud_id">
                                    <input type="hidden" value="<?php echo $query['studentcourseId']; ?>" id="studentcourseId" name="studentcourseId">
                                    <div class="portlet-body">
                                        <table class="table table-bordered">
                                            
                                            <tbody>
                                                <tr>
                                                    <td>Portal Name : <?php
                                                    if ($query['studentPortal_Id'] == 1) {
                                                        echo 'Maac CG';
                                                    } else if ($query['studentPortal_Id'] == 2) {
                                                        echo 'Kshitij Vivan';
                                                    } else if ($query['studentPortal_Id'] ==3) {
                                                        echo 'Other';
                                                    }else{
                                                        echo 'Maac Satellite';
                                                    }
                                                    ?></td>
                                                    <td>Enrollment Id : <?php echo $query['studentEnrollment']; ?></td>
                                                    <td>Course Name :<?php
                                                    $courseName = mysqli_query($dbconn,"SELECT * FROM `course` where courseId IN (" . $query['courseId'] . ")");
                                                    $course = "";
                                                    while ($rowCourse = mysqli_fetch_array($courseName)) {
                                                        $course = $rowCourse['courseName'] . ",<br />" . $course;
                                                    }
                                                    $courseStr = rtrim($course, ',<br />'); //rtrim(',',$course);
                                                    echo $courseStr;
                                                    ?> </td>
                                                </tr>
                                                <tr>
                                                    <td>Student Name : <?php echo $query['title'] . ' ' . $query['firstName'] . ' ' . $query['middleName'] . ' ' . $query['surName']; ?></td>
                                                    <td>Mobile No : <?php echo $query['mobileOne']; ?></td>
                                                    <td>Email ID : <?php echo $query['email']; ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Actual Fees : <?php
                                                    echo $query['fee'];
                                                    ?></td>
                                                    <td>Offered Fees : <?php echo $query['offeredfee']; ?></td>
                                                    <td>Registered Fees : <?php
                                                    echo $query['booking_amount'];
                                                    ?></td>
                                                </tr>
                                                <tr>
                                                    <td>Date Of Join : <?php echo $query['dateOfJoining']; ?></td>
                                                    <td>Joining Amount : <?php
                                                    $joiningDate = mysqli_fetch_array(mysqli_query($dbconn,"SELECT * FROM `studentemidetail` where stud_id=" . $stud_id . " and studentcourseId=" . $query['studentcourseId'] . " and studentemidetail.isDelete=0 group by joinAmount "));
                                                    echo $joiningDate['joinAmount'];
                                                    ?></td>
                                                    <td>Emi Type : <?php
                                                    if ($query['emiType'] == 1) {
                                                        echo 'One Time Emi';
                                                    } elseif ($query['emiType'] == 2) {
                                                        echo 'Monthly Emi';
                                                    } else {
                                                        echo 'Quarterly Emi';
                                                    }
                                                    ?></td>
                                                </tr>
                                                
                                                <?php if ($query['emiType'] != 1) { ?>
                                                <tr>
                                                    <td>Emi Start Date : <?php echo $query['emiStartDate']; ?></td>
                                                    <td>No Of EMI : <?php echo $query['noOfEmi']; ?></td>
                                                    <td>Emi Amount : <?php echo $query['emiAmount']; ?></td>
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                        <div class="row" style="margin-bottom: 20px">
<!--                                            <div class="col-md-4">
                                                <h4>Portal Name : <?php
//                                                    if ($query['studentPortal_Id'] == 1) {
//                                                        echo 'Aptech Portal';
//                                                    } else if ($query['studentPortal_Id'] == 2) {
//                                                        echo 'Kshitij Vivan';
//                                                    } else {
//                                                        echo 'Other';
//                                                    }
                                                    ?>
                                                </h4>
                                            </div>
                                            <div class="col-md-4">
                                                <h4>Enrollment Id : <?php // echo $query['studentEnrollment']; ?></h4>
                                            </div>
                                            <div class="col-md-4">
                                                <h4>Course Name : <?php
//                                                echo "SELECT * FROM `course` where courseId IN (".$query['courseId'].")";
//                                                    $courseName = mysqli_query($dbconn,"SELECT * FROM `course` where courseId IN (" . $query['courseId'] . ")");
//                                                    $course = "";
//                                                    while ($rowCourse = mysqli_fetch_array($courseName)) {
//                                                        $course = $rowCourse['courseName'] . ",<br />" . $course;
//                                                    }
//                                                    $courseStr = rtrim($course, ',<br />'); //rtrim(',',$course);
//                                                    echo $courseStr;
                                                    ?> 
                                                </h4>
                                            </div>-->
                                        </div>
<!--                                        <div class="row" style="margin-bottom: 20px">
                                            <div class="col-md-4">
                                                <h4>Student Name : <?php // echo $query['title'] . ' ' . $query['firstName'] . ' ' . $query['middleName'] . ' ' . $query['surName']; ?></h4>
                                            </div>
                                            <div class="col-md-4">
                                                <h4>Mobile No : <?php // echo $query['mobileOne']; ?></h4>
                                            </div>
                                            <div class="col-md-4">
                                                <h4>Email ID : <?php // echo $query['email']; ?> </h4>
                                            </div>
                                        </div>-->

<!--                                        <div class="row" style="margin-bottom: 20px">
                                            <div class="col-md-4">
                                                <h4>Actual Fees : <?php
//                                                    echo $query['fee'];
                                                    ?></h4>
                                            </div>
                                            <div class="col-md-4">
                                                <h4>Offered Fees : <?php // echo $query['offeredfee']; ?></h4>
                                            </div>
                                            <div class="col-md-4">
                                                <h4>Registered Fees : <?php
//                                                    echo $query['booking_amount'];
                                                    ?> </h4>
                                            </div>
                                        </div>-->
<!--                                        <div class="row" style="margin-bottom: 20px">
                                            <div class="col-md-4">
                                                <h4>Date Of Join : <?php // echo $query['dateOfJoining']; ?></h4>
                                            </div>
                                            <div class="col-md-4">
                                                <h4>Joining Amount : <?php
//                                                echo "SELECT * FROM `studentemidetail` where stud_id=".$stud_id." and studentcourseId=".$query['studentcourseId']." group by joinAmount ";
//                                                    $joiningDate = mysqli_fetch_array(mysqli_query($dbconn,"SELECT * FROM `studentemidetail` where stud_id=" . $stud_id . " and studentcourseId=" . $query['studentcourseId'] . " group by joinAmount "));
//                                                    echo $joiningDate['joinAmount'];
                                                    ?></h4>
                                            </div>
                                            <div class="col-md-4">
                                                <h4>Emi Type : <?php
//                                                    if ($query['emiType'] == 1) {
//                                                        echo 'One Time Emi';
//                                                    } elseif ($query['emiType'] == 2) {
//                                                        echo 'Monthly Emi';
//                                                    } else {
//                                                        echo 'Quarterly Emi';
//                                                    }
                                                    ?> </h4>
                                            </div>
                                        </div>-->
                                        <?php // if ($query['emiType'] != 1) { ?>
<!--                                            <div class="row" style="margin-bottom: 20px">
                                                <div class="col-md-4">
                                                    <h4>Emi Start Date : <?php // echo $query['emiStartDate']; ?></h4>
                                                </div>
                                                <div class="col-md-4">
                                                    <h4>No Of EMI : <?php // echo $query['noOfEmi']; ?></h4>
                                                </div>
                                                <div class="col-md-4">
                                                    <h4>Emi Amount : <?php // echo $query['emiAmount']; ?> </h4>
                                                </div>
                                            </div>-->
                                        <?php // } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php include_once './footer.php'; ?>
        <script>
            function exportPDFdata()
            {
                var stud_id = $('#stud_id').val();
                var studentcourseId = $('#studentcourseId').val();
                window.open('Student-Emai-Pdf.php?token=' + stud_id + '&studentcourseId=' + studentcourseId, target = '_blank')
            }
        </script>
    </body>
</html>