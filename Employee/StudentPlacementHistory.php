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
        <title><?php echo $ProjectName; ?> | Student Placement History</title>
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
                                <?php include_once './menu-placement.php'; ?>
                            </div>
                            <div class="col-md-10">
                                <div class="portlet light ">
                                    <div class="portlet-title">
                                        <div class="caption grey-gallery">
                                            <i class="icon-settings grey-gallery"></i>
                                            <span class="caption-subject bold uppercase" id="listdetail">Student Placement History</span>
                                        </div>
                                        <a class="btn blue pull-right" href="javascript: history.go(-1)">Go Back</a>
                                    </div>
                                    <div class="portlet-body">
                                        <div class="row" style="margin-bottom: 20px">
                                            <?php
                                            $stud_id = $_REQUEST['token'];
                                            $query = mysqli_fetch_array(mysqli_query($dbconn,"SELECT * FROM `studentadmission` where stud_id=" . $stud_id));
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
                                        <div class="tab-content">
											<input type="hidden" value="<?php echo $_REQUEST['token']; ?>" id="stud_id" name="stud_id">
                                            <div class="tab-pane active" >
                                                <div class="portlet-body form">
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

        <?php include_once './footer.php'; ?>
        <script src="<?php echo $web_url; ?>admin/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>                  
        <style>
            .multiselect
            {
                display: block;
                height: 35px;
                padding: 6px;

                text-align: left !important;
                line-height: 1.42857;
                color: #DFDFDF; 
                background-color: #fff;
                background-image: none;
                border: 1px solid #51c6dd !important;
                border-radius: 4px;
                color: #666;
                font-size: 15px;
                font-weight: normal !important;
                text-transform: lowercase;

            }
        </style>
        <link href="assets/bootstrap-multiselect.css" rel="stylesheet" type="text/css"/>
        <script src="assets/bootstrap-multiselect.js" type="text/javascript"></script>
        <script>
            function checkclose() {
                window.location.href = '';
            }

            $(document).ready(function (){
                var stud_id = $('#stud_id').val();
                var studentcourseId = $('#studentcourseId').val();
                var urlp = '<?php  echo $web_url; ?>Employee/findCourse.php?stud_id=' + stud_id + '&studentcourseId=' + studentcourseId;
                $.ajax({
                    type: 'POST',
                    url: urlp,
                    success: function (dataemi) {
                        $('#divFeesValue').html(dataemi);
                        var valReceived = dataemi.split("##@@##");
                        $('#divFeesValue').html(valReceived[0]);
                        $('#emitypeInputDiv').html(valReceived[1]);
                    }
                }).error(function () {
                    alert('An error occured');
                });
            });
            

            function PageLoadData(Page) {
                var stud_id = $('#stud_id').val();
                var studentcourseId = $('#studentcourseId').val();
                $('#loading').css("display", "block");
                $.ajax({
                    type: "POST",
                    url: "<?php echo $web_url; ?>Employee/AjaxStudentPlacementHistory.php",
                    data: {action: 'ListUser', Page: Page, stud_id: stud_id, studentcourseId: studentcourseId},
                    success: function (msg) {
                        $("#PlaceUsersDataHere").html(msg);
                        $('#loading').css("display", "none");
                    }
                });
            }
            PageLoadData(1);

        </script>
    </body>
</html>
