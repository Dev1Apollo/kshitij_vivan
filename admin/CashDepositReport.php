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
        <title><?php echo $ProjectName; ?> |Student Projection Report </title>
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
                            <div class="col-md-12">
                                <div class="portlet light ">
                                    <div class="portlet-title">
                                        <div class="caption grey-gallery">
                                            <i class="icon-settings grey-gallery"></i>
                                            <span class="caption-subject bold uppercase" >List of Student Projection Report</span>
                                        </div>
                                    </div>
                                    <div class="portlet-body">
                                        <div class="row m-search-box">
                                            <div class="col-md-12">
                                                <form  role="form"  method="POST"  action="" name="frmSearch"  id="frmSearch" enctype="multipart/form-data">
                                                    <div class="row">
                                                        <div class="form-group col-md-2">
                                                            <input type="text" id="FromDate" name="FromDate" class="form-control date-picker" placeholder="Enter From Date"/>
                                                        </div>
                                                        <div class="form-group col-md-2">
                                                            <input type="text" id="ToDate" name="ToDate" class="form-control date-picker" placeholder="Enter To Date"/>
                                                        </div>
                                                        <div class="form-group col-md-2">
                                                            <select name="studentPortal_Id[]" id="studentPortal_Id" multiple="multiple" class="form-control" required="">
                                                                <!--                                                                <option value="">Select Type</option>-->
                                                                <option value="4">Maac Satellite</option>
                                                                <option value="1">Maac CG</option>
                                                                <option value="2">Kshitij Vivan</option>                                                                                                          
                                                                <option value="3">Other</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-2">
                                                            <select name="paymentMode[]" id="paymentMode" multiple="multiple" class="form-control" required="">
                                                                <!--<option value="">Select Payment Mode</option>-->
                                                                <?php
                                                                $filterMode = mysqli_query($dbconn, "Select * from paymentmode where isDelete='0' and iStatus='1'");
                                                                while ($rowMode = mysqli_fetch_array($filterMode)) {
                                                                    echo '<option value=' . $rowMode['paymentId'] . '>' . $rowMode['paymentName'] . '</option>';
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-2">
                                                            <?php
                                                            $querysBranch = "SELECT * FROM `branchmaster`  where isDelete='0' order by  branchid asc";
                                                            $resultsBranch = mysqli_query($dbconn,$querysBranch) or die(mysql_error());
                                                            echo '<select class="form-control" name="branch" id="branch">';
                                                            echo "<option value='' >Select Branch</option>";
                                                            while ($rowsBranch = mysqli_fetch_array($resultsBranch)) {
                                                                echo "<option value='" . $rowsBranch['branchid'] . "'>" . $rowsBranch['branchname'] . "</option>";
                                                            }
                                                            echo "</select>";
                                                            ?>
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
        <script src="assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>
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
                                                                    $('#studentPortal_Id').multiselect({
                                                                        nonSelectedText: 'Select Type',
                                                                        includeSelectAllOption: true,
                                                                        buttonWidth: '100%',
                                                                    });
                                                                    $('#paymentMode').multiselect({
                                                                        nonSelectedText: 'Select Payment Mode',
                                                                        includeSelectAllOption: true,
                                                                        buttonWidth: '100%',
                                                                    });
                                                                });

                                                                $(document).ready(function () {
                                                                    $("#FromDate").datepicker({
                                                                        format: 'dd-mm-yyyy',
                                                                        autoclose: true,
                                                                        todayHighlight: true,
                                                                        defaultDate: "now"
                                                                    });
                                                                    $("#ToDate").datepicker({
                                                                        format: 'dd-mm-yyyy',
                                                                        autoclose: true,
                                                                        todayHighlight: true,
                                                                        defaultDate: "now"
                                                                    });
                                                                });

                                                                function PageLoadData(Page) {
                                                                    var FromDate = $('#FromDate').val();
                                                                    var ToDate = $('#ToDate').val();
                                                                    var studentPortal_Id = $('#studentPortal_Id').val();
                                                                    var paymentMode = $('#paymentMode').val();
                                                                    var branch = $('#branch').val();
                                                                    $('#loading').css("display", "block");
                                                                    $.ajax({
                                                                        type: "POST",
                                                                        url: "AjaxCashDepositReport.php",
                                                                        data: {action: 'ListUser', Page: Page,branch: branch, FromDate: FromDate, ToDate: ToDate,studentPortal_Id: studentPortal_Id, paymentMode: paymentMode},
                                                                        success: function (msg) {

                                                                            $("#PlaceUsersDataHere").html(msg);
                                                                            $('#loading').css("display", "none");
                                                                        },
                                                                    });
                                                                }// end of filter
                                                                // PageLoadData(1);

                                                                function exportexceldata()
                                                                {
                                                                    var FromDate = $('#FromDate').val();
                                                                    var ToDate = $('#ToDate').val();
                                                                    var studentPortal_Id = $('#studentPortal_Id').val();
                                                                    var paymentMode = $('#paymentMode').val();
                                                                    var branch = $('#branch').val();
                                                                    window.location.href = 'export-cash-deposit-report.php?FromDate=' + FromDate + "&ToDate=" + ToDate+"&studentPortal_Id="+studentPortal_Id+"&paymentMode="+paymentMode+"&branch="+ branch;
                                                                }

        </script>
    </body>
</html>