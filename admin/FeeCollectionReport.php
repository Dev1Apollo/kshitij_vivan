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
        <title><?php echo $ProjectName; ?> | Collection Report</title>
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
                        <ul class="page-breadcrumb breadcrumb">
                            <li>
                                <a href="<?php echo $web_url; ?>admin/index.php">Home</a>
                                <i class="fa fa-circle"></i>
                            </li>
                            <li>
                                <span>Collection Report</span>
                            </li>
                        </ul>
                        <div class="page-content-inner">
                            <div class="col-md-12">
                                <div class="portlet light ">
                                    <div class="portlet-title">
                                        <div class="caption grey-gallery">
                                            <i class="icon-settings grey-gallery"></i>
                                            <span class="caption-subject bold uppercase" >Collection Report</span>
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
                                                                    var branch = $('#branch').val();

                                                                    $('#loading').css("display", "block");
                                                                    $.ajax({
                                                                        type: "POST",
                                                                        url: "AjaxFeeCollectionReport.php",
                                                                        data: {action: 'ListUser', Page: Page, FormDate: FormDate, ToDate: ToDate,branch: branch},
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
                                                                    var branch = $('#branch').val();
                                                                    
                                                                    window.location.href = 'export-Fee-Collection-Report.php?FormDate=' + FormDate + "&ToDate=" + ToDate+"&branch="+branch;
                                                                }

        </script>
    </body>
</html>