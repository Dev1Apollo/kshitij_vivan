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
        <title><?php echo $ProjectName; ?> | Student Enrollment Report</title>
        <?php include_once './include.php'; ?>
        <link href="<?php echo $web_url; ?>admin/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet" type="text/css" />
    </head>
    <body class="page-container-bg-solid page-boxed">
        <?php include_once './header.php'; ?>
        <div style="display: none; z-index: 10060;" id="loading">
            <img id="loading-image" src="<?php echo $web_url; ?>admin/images/loader1.gif">
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
                                            <span class="caption-subject bold uppercase" >Student Enrollment Report</span>
                                        </div>
                                    </div>
                                    <div class="portlet-body">
                                        <div class="row m-search-box">
                                            <div class="col-md-12">
                                                <form  role="form"  method="POST"  action="" name="frmSearch"  id="frmSearch" enctype="multipart/form-data">
                                                    <div class="row">
                                                        <div class="form-group col-md-offset-1 col-md-2">
                                                            <input type="text" id="FormDate" name="FormDate" class="form-control date-picker" placeholder="Enter From Date"/>
                                                        </div>
                                                        <div class="form-group col-md-2">
                                                            <input type="text" id="ToDate" name="ToDate" class="form-control date-picker" placeholder="Enter To Date" />
                                                        </div>
                                                        <div class="form-group col-md-2">
                                                            <select name="studentPortal_Id[]" id="studentPortal_Id"  class="form-control" multiple="multiple" required="">
                                                                <!--                                                                <option value="">Select Type</option>-->
                                                                <option value="4">Maac Satellite</option>
                                                                <option value="1">Maac CG</option>
                                                                <option value="2">Kshitij Vivan</option>                                                                                                          
                                                                <option value="3">Other</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-2">
                                                            <select name="studentStatus[]" id="studentStatus" multiple="multiple" class="form-control" required="">
<!--                                                                <option value="">Select Status</option>-->
                                                                <?php
                                                                $filterStatus = mysqli_query($dbconn,"SELECT * FROM `studentstatus` where istatus=1 and isDelete=0");
                                                                while ($strStatus = mysqli_fetch_array($filterStatus)) {
                                                                    ?>
                                                                    <option value="<?php echo $strStatus['studstatusid']; ?>"><?php echo $strStatus['studentStatusName']; ?></option>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <a href="#" class="btn blue" onclick="PageLoadData(1);">Search</a>
                                                            <a onclick="exportexceldata()" class="btn btn-md btn-primary"><i class="fa fa-file-excel-o fa-2x"></i></a>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
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
        <style>
            .multiselect
            {
                display: block;
                height: 35px;
                padding: 6px;

                text-align: left !important;
                line-height: 1.42857;
                /* color: #DFDFDF; */
                background-color: #fff;
                background-image: none;
                border: 1px solid #51c6dd !important;
                border-radius: 4px;
                color: #555555;
                font-size: 15px;
                font-weight: normal !important;
                text-transform: lowercase;

            }
        </style>
        <link href="assets/bootstrap-multiselect.css" rel="stylesheet" type="text/css"/>
        <script src="assets/bootstrap-multiselect.js" type="text/javascript"></script>

        <script src="<?php echo $web_url; ?>admin/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>
        <script>
                                                                $(document).ready(function () {
                                                                    $("#FormDate").datepicker({
                                                                        format: 'dd-mm-yyyy',
                                                                        autoclose: true,
                                                                        todayHighlight: true,
                                                                        defaultDate: "now"
                                                                    });
                                                                });
                                                                $(document).ready(function () {
                                                                    $('#studentPortal_Id').multiselect({
                                                                        nonSelectedText: 'Select Type',
                                                                        includeSelectAllOption: true,
                                                                        buttonWidth: '100%',
                                                                        
                                                                    });
                                                                    $('#studentStatus').multiselect({
                                                                        nonSelectedText: 'Select Status',
                                                                        includeSelectAllOption: true,
                                                                        buttonWidth: '100%',
                                                                    });
                                                                });
                                                                $(document).ready(function () {
                                                                    $("#ToDate").datepicker({
                                                                        format: 'dd-mm-yyyy',
                                                                        autoclose: true,
                                                                        todayHighlight: true,
                                                                        defaultDate: "now"
                                                                    });
                                                                });
                                                                function PageLoadData(Page) {
                                                                    var FormDate = $('#FormDate').val();
                                                                    var ToDate = $('#ToDate').val();
                                                                    var studentPortal_Id = $('#studentPortal_Id').val();
                                                                    var studentStatus = $('#studentStatus').val();

                                                                    $('#loading').css("display", "block");
                                                                    $.ajax({
                                                                        type: "POST",
                                                                        url: "AjaxStudentEnrollmentReport.php",
                                                                        data: {action: 'ListUser', Page: Page, studentStatus: studentStatus, FormDate: FormDate, ToDate: ToDate, studentPortal_Id: studentPortal_Id},
                                                                        success: function (msg) {
                                                                            $("#PlaceUsersDataHere").html(msg);
                                                                            $('#loading').css("display", "none");
                                                                        },
                                                                    });
                                                                }// end of filter
                                                                // PageLoadData(1);

                                                                function exportexceldata()
                                                                {
                                                                    var FormDate = $('#FormDate').val();
                                                                    var ToDate = $('#ToDate').val();
                                                                    var studentPortal_Id = $('#studentPortal_Id').val();
                                                                    var studentStatus = $('#studentStatus').val();
                                                                    
                                                                    window.location.href = 'export-Student-Enrollment-Report.php?FormDate=' + FormDate + "&ToDate=" + ToDate + '&studentPortal_Id=' + studentPortal_Id + '&studentStatus=' + studentStatus;
                                                                }


        </script>
    </body>
</html>