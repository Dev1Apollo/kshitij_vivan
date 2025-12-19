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
        <title><?php echo $ProjectName; ?> | Enrollment </title>
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
                            <div class="col-md-10">
                                <div class="portlet light ">
                                    <div class="portlet-title">
                                        <div class="caption grey-gallery">
                                            <i class="icon-settings grey-gallery"></i>
                                            <span class="caption-subject bold uppercase" id="listdetail">List of Enrollment</span>
                                        </div>
                                        <div id="admission">
                                        <!--<a href="<?php echo $web_url; ?>Employee/AddNewAdmission.php" class="btn blue" style="float: right;" title="Add New Admission">ADD New Enrollment</a>-->
                                        </div>
                                    </div>
                                    <div class="portlet-body form">
                                        <form  role="form"  method="POST"  action="" name="frmSearch"  id="frmSearch" enctype="multipart/form-data">
                                            <div class="row m-search-box">
                                                <div class="col-md-12"  >
                                                    <div class="form-group  col-md-3">
                                                        <input type="text" value="" name="firstName" class="form-control" id="firstName" placeholder="Search First Name " />
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <input type="text" value="" name="surName" class="form-control" id="surName" placeholder="Search Sur Name " />
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <input type="text" value="" name="studentPortal_Id" class="form-control" id="studentPortal_Id" placeholder="Search Student Portal ID" />
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <select id="student" name="student" class="form-control" onchange="newAdmission();">
                                                            <option value="Registered_Student">Registered Student</option>
                                                            <option value="Enroll_Student">Enrolled Student</option> 

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
        <?php include_once './footer.php'; ?>
        <script src="<?php echo $web_url; ?>admin/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>
        <script type="text/javascript">

                                                            $(document).ready(function () {
                                                                $("#FormDate").datepicker({
                                                                    format: 'dd-mm-yyyy',
                                                                    autoclose: true,
                                                                    todayHighlight: true,
                                                                    defaultDate: "now",
                                                                    endDate: "now"
                                                                });
                                                            });
                                                            $(document).ready(function () {
                                                                $("#ToDate").datepicker({
                                                                    format: 'dd-mm-yyyy',
                                                                    autoclose: true,
                                                                    todayHighlight: true,
                                                                    defaultDate: "now",
                                                                    endDate: "now"
                                                                });
                                                            });
                                                            
                                                            function newAdmission() {
                                                                var student = $('#student').val();
                                                                if (student == 'Enroll_Student')
                                                                {
                                                                    $('#admission').show();
                                                                } else if (student == 'Registered_Student')
                                                                {
                                                                    $('#admission').hide();
                                                                } else
                                                                {
                                                                    $('#admission').show();
                                                                }
                                                            }

                                                            function isRegister(task, id)
                                                            {
                                                                var errMsg = '';
                                                                if (task == 'Addcourse') {
                                                                    errMsg = 'Do you want to continue with enrollment process?';
                                                                }
                                                                if (confirm(errMsg)) {
                                                                    $('#loading').css("display", "block");
                                                                    $.ajax({
                                                                        type: "POST",
                                                                        url: "<?php echo $web_url; ?>Employee/AjaxStudentEntry.php",
                                                                        data: {action: task, ID: id},
                                                                        success: function (response) {
                                                                            $('#loading').css("display", "none");
                                                                            response = response.trim();
                                                                            window.location.href = '<?php echo $web_url; ?>Employee/student-course.php?token=' + response;
                                                                            return false;
                                                                        }
                                                                    });
                                                                }
                                                                return false;
                                                            }
                                                            
                                                            function PageLoadData(Page) {
                                                                var firstName = $('#firstName').val();
                                                                var surName = $('#surName').val();
                                                                var studentPortal_Id = $('#studentPortal_Id').val();
                                                                var student = $('#student').val();
                                                                if (student == '')
                                                                {
                                                                    alert("Plase Select Student Type");
                                                                }
                                                                $('#loading').css("display", "block");
                                                                $.ajax({
                                                                    type: "POST",
                                                                    url: "<?php echo $web_url; ?>Employee/AjaxStudentEntry.php",
                                                                    data: {action: 'ListUser', Page: Page, firstName: firstName, surName: surName, studentPortal_Id: studentPortal_Id, student: student},
                                                                    success: function (msg) {
                                                                        $("#PlaceUsersDataHere").html(msg);
                                                                        $('#loading').css("display", "none");
                                                                    },
                                                                });
                                                            }// end of filter
                                                            PageLoadData(1);

        </script>
    </body>
</html>