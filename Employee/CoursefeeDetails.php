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
                                            <span class="caption-subject bold uppercase" id="listdetail">Student Course Details</span>
                                        </div>
                                    </div>
                                    <input type="hidden" value="<?php echo $_REQUEST['token']; ?>" id="stud_id" name="stud_id">
                                    <div class="portlet-body form">
                                        <div id="PlaceUsersDataHere">
                                        </div>
                                    </div>
<!--                                    <div id="PlaceUsersDataHere">

                                    </div>-->
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
            function PageLoadData(Page) {
//                                                                var courseName = $('#courseName').val();
                                                                var stud_id = $('#stud_id').val();
                $('#loading').css("display", "block");
                $.ajax({
                    type: "POST",
                    url: "<?php echo $web_url; ?>Employee/AjaxCourseFeeDetails.php",
                    data: {action: 'ListUser', Page: Page,stud_id:stud_id},
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