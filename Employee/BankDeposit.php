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
        <title><?php echo $ProjectName; ?> |  Cash and Cheque on hand </title>
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
                                            <span class="caption-subject bold uppercase" id="listdetail">List of Cash and Cheque on hand</span>
                                        </div>
<!--                                        <a href="<?php echo $web_url; ?>Employee/SearchLeadStudent.php" class="btn blue" style="float: right;" title="Add New Admission">ADD New Admission</a>-->
                                    </div>
                                    <div class="portlet-body form">
                                        <form  role="form"  method="POST"  action="" name="frmSearch"  id="frmSearch" enctype="multipart/form-data">
                                            <div class="row m-search-box">
                                                <div class="col-md-12"  >
                                                    <div class="form-group  col-md-4">
                                                        <input type="text"  name="FormDate" class="form-control date-picker" id="FormDate" placeholder="Search Form Date " />
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <input type="text"  name="ToDate" class="form-control date-picker" id="ToDate" placeholder="Search To Date " />
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
                                                                    defaultDate: "now"
//                                                                endDate: "now"
                                                                });
                                                            });

                                                            $(document).ready(function () {
                                                                $("#ToDate").datepicker({
                                                                    format: 'dd-mm-yyyy',
                                                                    autoclose: true,
                                                                    todayHighlight: true,
                                                                    defaultDate: "now"
//                                                                endDate: "now"w",                                                               
                                                                });
                                                            });

                                                            function PageLoadData(Page) {
                                                                var FormDate = $('#FormDate').val();
                                                                var ToDate = $('#ToDate').val();
                                                                $('#loading').css("display", "block");
                                                                $.ajax({
                                                                    type: "POST",
                                                                    url: "<?php echo $web_url; ?>Employee/AjaxBankDeposit.php",
                                                                    data: {action: 'ListUser', Page: Page, FormDate: FormDate, ToDate: ToDate},
                                                                    success: function (msg) {
                                                                        $('#loading').css("display", "none");
                                                                        $("#PlaceUsersDataHere").html(msg);
                                                                    }
                                                                });
                                                            }// end of filter
                                                            PageLoadData(1);

        </script>
    </body>
</html>