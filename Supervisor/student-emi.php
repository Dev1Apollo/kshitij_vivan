<?php
ob_start();
error_reporting(E_ALL);
require_once '../common.php';
$connect = new connect();
include('IsLogin.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title><?php echo $ProjectName; ?> | Student Admission </title>
    <?php include_once './include.php'; ?>
    <link href="demo/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet" type="text/css" />
</head>

<body class="page-container-bg-solid page-boxed">
    <?php include_once './header.php'; ?>
    <div style="display: none; z-index: 10060;" id="loading">
        <img id="loading-image" src="<?php echo $web_url; ?>Supervisor/images/loader1.gif">
    </div>
    <div class="page-container">
        <div class="page-content-wrapper">
            <div class="page-content">
                <div class="container">
                    <div class="page-content-inner">
                        <div class="col-md-2">
                            <?php include_once './menu-admission.php'; ?>
                        </div>
                        <div class="col-md-10">
                            <div class="portlet light ">
                                <div class="portlet-title">
                                    <div class="caption grey-gallery">
                                        <i class="icon-settings grey-gallery"></i>
                                        <span class="caption-subject bold uppercase" id="listdetail">Manage Student</span>
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <div class="row" style="margin-bottom: 20px">
                                        <?php
                                        //                                            echo $_REQUEST['stud_id'];
                                        //                                            exit;
                                        $stud_id = $_REQUEST['token'];
                                        $query = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `studentadmission` where stud_id=" . $stud_id));
                                        ?>
                                        <div class="col-md-4">
                                            <h4>Student Name :<?php echo $query['title'] . ' ' . $query['firstName'] . ' ' . $query['middleName'] . ' ' . $query['surName']; ?></h4>
                                        </div>
                                        <div class="col-md-4">
                                            <h4>Mobile No : <?php echo $query['mobileOne']; ?></h4>
                                        </div>
                                        <div class="col-md-4">
                                            <h4>Email ID : <?php echo $query['email']; ?> </h4>
                                        </div>
                                    </div>
                                    <div class="portlet-body">
                                        <div class="tabbable-custom nav-justified">
                                            <ul class="nav nav-tabs nav-justified">
                                                <?php
                                                $stud_id = $_REQUEST['token'];
                                                ?>
                                                <li>
                                                    <a href="student-course.php?token=<?php echo $_REQUEST['token']; ?>"> Student Course </a>
                                                </li>
                                                <li>
                                                    <a href="student-course-details.php?token=<?php echo $_REQUEST['token']; ?>"> Student Course Details </a>
                                                </li>
                                                <li>
                                                    <a href="student-fees.php?token=<?php echo $_REQUEST['token']; ?>"> Student Fees </a>
                                                </li>
                                                <li class="active">
                                                    <a href="student-emi.php?token=<?php echo $_REQUEST['token']; ?>"> Student EMI </a>
                                                </li>
                                            </ul>
                                            <div class="tab-content">
                                                <div class="tab-pane active">
                                                    <form role="form" method="POST" action="" name="listEmiDetails" id="frmSearch" enctype="multipart/form-data">
                                                        <div class="row m-search-box">
                                                            <div class="col-md-12">
                                                                <input type="hidden" value="<?php echo $stud_id; ?>" id="stud_id" name="stud_id">
                                                                <div class="form-group  col-md-4">
                                                                    <select name="courseName" id="courseName" class="form-control" required="">
                                                                        <option value="">Select Course Name</option>
                                                                        <?php
                                                                        $fetchcourse = mysqli_query($dbconn, "Select *  from studentcourse where stud_id =" . $stud_id . " and studentcourse.istatus=1");
                                                                        while ($data = mysqli_fetch_array($fetchcourse)) {
                                                                            $rowdata = mysqli_query($dbconn, "SELECT * FROM `course` where istatus=1 and courseId='" . $data['courseId'] . "' and isDelete=0 ORDER by courseName ASC");
                                                                            while ($resultdata = mysqli_fetch_array($rowdata)) {
                                                                        ?>
                                                                                <option value="<?php echo $resultdata['courseId'] ?>"><?php echo $resultdata['courseName'] ?></option>
                                                                        <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                                <div class="form-group  col-md-2">
                                                                    <a href="#" class="btn btn-block blue pull-right" onclick="PageLoadData(1);"><i class="fa fa-search"></i>&nbsp;&nbsp;Search</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                    <div id="PlaceUsersDataHere">

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
        function PageLoadData(Page) {

            var stud_id = $('#stud_id').val();
            var courseName = $('#courseName').val();

            //                    if (courseName == '' || courseName == '')
            //                    {
            //                        alert("Please choose One");
            //                        
            //                    }
            $('#loading').css("display", "block");
            $.ajax({
                type: "POST",
                url: "<?php echo $web_url; ?>Supervisor/AjaxEmi.php",
                data: {
                    action: 'ListUser',
                    Page: Page,
                    stud_id: stud_id,
                    courseName: courseName
                },
                success: function(msg) {

                    $("#PlaceUsersDataHere").html(msg);
                    $('#loading').css("display", "none");
                },
            });
        } // end of filter
        //            PageLoadData(1);
    </script>
</body>

</html>