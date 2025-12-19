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
                                                //                                                if (isset($_REQUEST['token'])){
                                                $stud_id = $_REQUEST['token'];

                                                ?>
                                                <li>
                                                    <a href="student-course.php?token=<?php echo $stud_id; ?>"> Student Course </a>
                                                </li>
                                                <li class="active">

                                                    <a href="student-course-details.php?token=<?php echo $stud_id; ?>"> Student Course Details </a>
                                                </li>
                                                <li>
                                                    <a href="student-fees.php?token=<?php echo $stud_id; ?>"> Student Fees </a>
                                                </li>
                                                <li>
                                                    <a href="student-emi.php?token=<?php echo $stud_id; ?>"> Student EMI </a>
                                                </li>
                                                <?php // } 
                                                ?>
                                            </ul>
                                            <div class="tab-content">
                                                <div class="tab-pane active">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="portlet light ">
                                                                <div class="portlet-title">
                                                                    <div class="caption grey-gallery">
                                                                        <i class="icon-settings grey-gallery"></i>
                                                                        <span class="caption-subject bold uppercase" id="listdetail">List of Student Course Detail</span>
                                                                    </div>


                                                                </div>
                                                                <div class="portlet-body form">

                                                                    <form role="form" method="POST" action="" name="frmSearch" id="frmSearch" enctype="multipart/form-data">
                                                                        <div class="row m-search-box">
                                                                            <div class="col-md-12">
                                                                                <input type="hidden" value="<?php echo $stud_id; ?>" id="stud_id" name="stud_id">
                                                                                <div class="form-group  col-md-2">
                                                                                    <input type="text" value="" name="softwareName" class="form-control" id="softwareName" placeholder="Search By Software Name " />
                                                                                </div>
                                                                                <div class="form-group col-md-2">
                                                                                    <input type="text" value="" name="courseName" class="form-control" id="courseName" placeholder="Search by Course Name " />
                                                                                </div>
                                                                                <!--                                                                                    <div class="form-group col-md-2">
                                                                                                                                                                            <input type="text" value="" name="studentPortal_Id" class="form-control" id="studentPortal_Id" placeholder="Search Student Portal ID" />
                                                                                                                                                                        </div>
                                                                                                                                                                        <div class="form-group  col-md-2">
                                                                                                                                                                            <input type="text" id="FormDate" name="FormDate" class="form-control date-picker" placeholder="Enter The From Date"/>
                                                                                                                                                                        </div>
                                                                                                                                                                        <div class="form-group col-md-2">
                                                                                                                                                                            <input type="text" id="ToDate" name="ToDate" class="form-control date-picker" placeholder="Enter The To Date" />
                                                                                                                                                                        </div>-->
                                                                                <div class="form-group  col-md-2">
                                                                                    <a href="#" class="btn btn-block blue pull-right" onclick="PageLoadData(1);"><i class="fa fa-search"></i>&nbsp;&nbsp;Search</a>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </form>
                                                                    <div id="PlaceUsersDataHere">

                                                                    </div>
                                                                    <!--                                                                        <input type="button" value="Next Page" class="btn blue" id="nextPage" onclick='window.location.href = "<?php echo $web_url; ?>Supervisor/student-fees.php?token=<?php echo $query['stud_id']; ?>"' />-->
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

            </div>
        </div>


        <?php include_once './footer.php'; ?>
        <script src="<?php echo $web_url; ?>Supervisor/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>
        <script>
            //                                                                            function checkclose(){
            //                                                                                 window.location.href = '<?php echo $web_url; ?>Supervisor/StudentEntry.php';   
            //                                                                            }
            //                                                                            function getSoftware()
            //                                                                            {
            //                                                                                var q = $('#cid').val();
            ////                alert(q);
            //                                                                                var urlp = '<?php echo $web_url; ?>Supervisor/findSoftwhere.php?cid=' + q;
            //                                                                                $.ajax({
            //                                                                                    type: 'POST',
            //                                                                                    url: urlp,
            //                                                                                    success: function (data) {
            ////                        alert(data);
            //                                                                                        $('#FeeDiv').html(data);
            //                                                                                    }
            //                                                                                }).error(function () {
            //                                                                                    alert('An error occured');
            //                                                                                });
            //
            //                                                                            }
            //
            //
            //                                                                            $(document).ready(function () {
            //                                                                                $("#softStrDate").datepicker({
            //                                                                                    format: 'dd-mm-yyyy',
            //                                                                                    autoclose: true,
            //                                                                                    todayHighlight: true,
            //                                                                                    defaultDate: "now",
            //                                                                                    //                                                                                            endDate: "now"
            //                                                                                });
            //
            //                                                                            });
            //                                                                            $(document).ready(function () {
            //
            //                                                                                $("#softEndDate").datepicker({
            //                                                                                    format: 'dd-mm-yyyy',
            //                                                                                    autoclose: true,
            //                                                                                    todayHighlight: true,
            //                                                                                    defaultDate: "now",
            //                                                                                    //                                                                                            endDate: "now"
            //                                                                                });
            //
            //                                                                            });
            //                                                                            $('#frmparameter').submit(function (e) {
            //
            //                                                                                e.preventDefault();
            //                                                                                var $form = $(this);
            //                                                                                $('#loading').css("display", "block");
            //                                                                                $.ajax({
            //                                                                                    type: 'POST',
            //                                                                                    url: '<?php echo $web_url; ?>Supervisor/querydata.php',
            //                                                                                    data: $('#frmparameter').serialize(),
            //                                                                                    success: function (response) {
            ////                       alert(response);
            //                                                                                        if (response != 0)
            //                                                                                        {
            //                                                                                            $('#loading').css("display", "none");
            //                                                                                            $("#Btnmybtn").attr('disabled', 'disabled');
            //                                                                                            alert('Added Sucessfully.');
            //                                                                                            response = response.trim();
            //                                                                                            window.location.href = '<?php echo $web_url; ?>Supervisor/student-course-details.php?token=' + response;
            //                                                                                        }
            //                                                                                    }
            //
            //                                                                                });
            //                                                                            });

            function PageLoadData(Page) {

                var courseName = $('#courseName').val();
                var softwareName = $('#softwareName').val();
                var stud_id = $('#stud_id').val();

                $('#loading').css("display", "block");
                $.ajax({
                    type: "POST",
                    url: "<?php echo $web_url; ?>Supervisor/AjaxStudentSoftware.php",
                    data: {
                        action: 'ListUser',
                        Page: Page,
                        courseName: courseName,
                        softwareName: softwareName,
                        stud_id: stud_id
                    },
                    success: function(msg) {

                        $("#PlaceUsersDataHere").html(msg);
                        $('#loading').css("display", "none");
                    },
                });
            } // end of filter
            PageLoadData(1);
        </script>

</body>

</html>