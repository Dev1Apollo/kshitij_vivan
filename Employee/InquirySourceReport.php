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
        <title><?php echo $ProjectName; ?> |Inquiry Source </title>
        <?php include_once './include.php'; ?>
        <link href="assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet" type="text/css" />
    </head>
    <body class="page-container-bg-solid page-boxed">
        <?php include_once './header.php'; ?>
        <div style="display: none; z-index: 10060;" id="loading">
            <img id="loading-image" src="images/loader1.gif">
        </div>
        <div class="page-container">        
            <div class="page-content-wrapper">
                <div class="page-content">
                    <div class="container">
                        <div class="page-content-inner">
                            <div class="col-md-2">
                                <?php include_once './menu-lms.php'; ?>
                            </div>                          
                            <div class="col-md-10">
                                <div class="portlet light ">
                                    <div class="portlet-title">
                                        <div class="caption grey-gallery">
                                            <i class="icon-settings grey-gallery"></i>
                                            <span class="caption-subject bold uppercase" >List of Inquiry Source</span>
                                        </div>
                                    </div>
                                    <div class="portlet-body">
                                        <div class="row m-search-box">
                                            <div class="col-md-12">
                                                <form  role="form"  method="POST"  action="" name="frmSearch"  id="frmSearch" enctype="multipart/form-data">
                                                    <div class="row">
                                                        <div class="form-group col-md-offset-1 col-md-3">
                                                            <input type="text" id="FormDate" name="FormDate" class="form-control date-picker" placeholder="Enter The From Date"/>
                                                        </div>
                                                        <div class="form-group col-md-3">
                                                            <input type="text" id="ToDate" name="ToDate" class="form-control date-picker" placeholder="Enter The To Date" />
                                                        </div>
                                                        <div class="form-group col-md-3">
                                                            <?php
                                                            $querysInq = "SELECT * FROM `inquirysource`  where isDelete='0'  and  istatus='1' order by  inquirySourceName asc";
                                                            $resultsInq = mysqli_query($dbconn,$querysInq) or die(mysqli_error($dbconn));
                                                            echo '<select class="form-control" name="InquirySource[]" id="InquirySource" multiple="multiple">';

                                                            while ($rowsInq = mysqli_fetch_array($resultsInq)) {
                                                                echo "<option value='" . $rowsInq['inquirySourceId'] . "'>" . $rowsInq['inquirySourceName'] . "</option>";
                                                            }
                                                            echo "</select>";
                                                            ?>
                                                        </div> 
                                                        <div class="form-group">
                                                            <a href="#" class="btn blue" onclick="PageLoadData(1);">Search</a>                         
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
        <script src="<?php echo $web_url; ?>admin/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>
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
        <script>

                                                                $(document).ready(function () {
                                                                    $('#InquirySource').multiselect({
                                                                        nonSelectedText: 'Select Any Inquiry Source',
                                                                        includeSelectAllOption: true,
                                                                        buttonWidth: '100%',
                                                                        maxHeight: 250
                                                                    });
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
                                                                
                                                                function PageLoadData(Page) {
                                                                    var FormDate = $('#FormDate').val();
                                                                    var ToDate = $('#ToDate').val();
                                                                    var InquirySource = $('#InquirySource').val();
                                                                    if (FormDate == '' && ToDate == '' && InquirySource == '')
                                                                    {
                                                                        alert('Please Select Any One Field');
                                                                        return false;
                                                                    }
                                                                    $('#loading').css("display", "block");
                                                                    $.ajax({
                                                                        type: "POST",
                                                                        url: "AjaxInquirySourceReport.php",
                                                                        data: {action: 'ListUser', Page: Page, FormDate: FormDate, ToDate: ToDate, InquirySource: InquirySource},
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
                                                                    var InquirySource = $('#InquirySource').val();
                                                                    window.location.href = 'export-inquiry-source.php?FormDate=' + FormDate + "&ToDate=" + ToDate + "&InquirySource=" + InquirySource;
                                                                }



        </script>
    </body>
</html>