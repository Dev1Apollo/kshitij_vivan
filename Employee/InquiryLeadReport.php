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
        <title><?php echo $ProjectName; ?> |Inquiry Report </title>
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
                <!--                <div class="page-head">
                                    <div class="container">
                                        <div class="page-title">
                                            <h1>Dashboard
                                                <small>dashboard</small>
                                            </h1>
                                        </div>                    
                                    </div>
                                </div>-->
                <div class="page-content">
                    <div class="container">
                        <ul class="page-breadcrumb breadcrumb">
                            <li>
                                <a href="index.php">Home</a>
                                <i class="fa fa-circle"></i>
                            </li>

                            <li>
                                <span>Inquiry Report</span>
                            </li>
                        </ul>

                        <div class="page-content-inner">




                            <div class="col-md-12">
                                <div class="portlet light ">
                                    <div class="portlet-title">
                                        <div class="caption grey-gallery">
                                            <i class="icon-settings grey-gallery"></i>
                                            <span class="caption-subject bold uppercase" >List of Inquiry Report</span>
                                        </div>
                                    </div>
                                    <div class="portlet-body">
                                        <div class="row m-search-box">
                                            <div class="col-md-12">
                                                <form  role="form"  method="POST"  action="" name="frmSearch"  id="frmSearch" enctype="multipart/form-data">
                                                    <div class="row">
                                                        <div class="form-group col-md-2">
                                                            <?php
                                                            $querysInq = "SELECT * FROM `inquirysource`  where isDelete='0'  and  istatus='1' order by  inquirySourceName asc";
                                                            $resultsInq = mysqli_query($dbconn,$querysInq) or die(mysqli_error($dbconn));
                                                            echo '<select class="form-control" name="InquirySource" id="InquirySource" >';
                                                            echo "<option value='' >Select Source Of Inquiry</option>";
                                                            while ($rowsInq = mysqli_fetch_array($resultsInq)) {
                                                                echo "<option value='" . $rowsInq['inquirySourceId'] . "'>" . $rowsInq['inquirySourceName'] . "</option>";
                                                            }
                                                            echo "</select>";
                                                            ?>
                                                        </div> 
                                                        <div class="form-group col-md-2">
                                                            <?php
                                                            $querysInqCat = "SELECT * FROM `categoryofinquiry`  where isDelete='0'  and  istatus='1' order by  id asc";
                                                            $resultsInqCat = mysqli_query($dbconn,$querysInqCat) or die(mysqli_error($dbconn));
                                                            echo '<select class="form-control" name="CategoryOfInquiry" id="CategoryOfInquiry" required="">';
                                                            echo "<option value='' >Select Category Of Inquiry</option>";
                                                            while ($rowsInqCat = mysqli_fetch_array($resultsInqCat)) {
                                                                echo "<option value='" . $rowsInqCat['id'] . "' >" . $rowsInqCat['COIname'] . "</option>";
                                                            }
                                                            echo "</select>";
                                                            ?>
                                                        </div>
                                                        <div class="form-group  col-md-2">
                                                            <input type="text" id="FormDate" name="FormDate" class="form-control date-picker" placeholder="Enter The From Date"/>
                                                        </div>
                                                        <div class="form-group col-md-2">
                                                            <input type="text" id="ToDate" name="ToDate" class="form-control date-picker" placeholder="Enter The To Date" />
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
        <script src="assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>

        <script>



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
                                                                function PageLoadData(Page) {
                                                                    var FormDate = $('#FormDate').val();
                                                                    var ToDate = $('#ToDate').val();

                                                                    var InquirySource = $('#InquirySource').val();
                                                                    var CategoryOfInquiry = $('#CategoryOfInquiry').val();

                                                                    if (FormDate == '' && ToDate == '' && InquirySource == '' && CategoryOfInquiry == '')
                                                                    {
                                                                        alert('Please Select Any One Field');
                                                                        return false;
                                                                    }

                                                                    $('#loading').css("display", "block");
                                                                    $.ajax({
                                                                        type: "POST",
                                                                        url: "AjaxInquiryLeadReport.php",
                                                                        data: {action: 'ListUser', Page: Page, InquirySource: InquirySource, CategoryOfInquiry: CategoryOfInquiry, FormDate: FormDate, ToDate: ToDate},
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
                                                                    var CategoryOfInquiry = $('#CategoryOfInquiry').val();
                                                                    window.location.href = 'export-inquiry-lead-report.php?FormDate=' + FormDate + "&ToDate=" + ToDate + "&InquirySource=" + InquirySource + "&CategoryOfInquiry=" + CategoryOfInquiry;
                                                                }



        </script>
    </body>
</html>