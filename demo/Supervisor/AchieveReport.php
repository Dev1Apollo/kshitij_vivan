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
    <title><?php echo $ProjectName; ?> | Achieve </title>
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
                            <?php include_once './menu-lms.php'; ?>
                        </div>
                        <div class="col-md-10">
                            <div class="portlet light ">
                                <div class="portlet-title">
                                    <div class="caption grey-gallery">
                                        <i class="icon-settings grey-gallery"></i>
                                        <span class="caption-subject bold uppercase" id="listdetail">List of Achieve</span>
                                    </div>
                                </div>
                                <div class="portlet-body form">
                                    <form role="form" method="POST" action="" name="frmSearch" id="frmSearch" enctype="multipart/form-data">
                                        <div class="row m-search-box">
                                            <div class="col-md-12">
                                                <!--<div class="form-group col-md-offset-2 col-md-3">-->
                                                <!--    <select name="targetMonth" id="targetMonth"  class="form-control" required="">-->
                                                <!--        <option value="">Select Target Month</option>-->
                                                <!--        <option value="01">January</option>-->
                                                <!--        <option value="02">February</option>-->
                                                <!--        <option value="03">March</option>-->
                                                <!--        <option value="04">April</option>-->
                                                <!--        <option value="05">May</option>-->
                                                <!--        <option value="06">June</option>-->
                                                <!--        <option value="07">July</option>-->
                                                <!--        <option value="08">August</option>-->
                                                <!--        <option value="09">September</option>-->
                                                <!--        <option value="10">October</option>-->
                                                <!--        <option value="11">November</option>-->
                                                <!--        <option value="12">December</option>-->
                                                <!--    </select>-->
                                                <!--</div>-->
                                                <!--<div class="form-group col-md-3">-->
                                                <!--    <select name="targetYear" id="targetYear"  class="form-control" required="">-->
                                                <!--        <option value="">Select Target Year</option>-->
                                                <?php
                                                // for ($i = 2014; $i <= date('Y'); $i++) {
                                                //     echo "<option value=" . $i . ">" . $i . "</option>";
                                                // }
                                                ?>
                                                <!--    </select>-->
                                                <!--</div>-->
                                                <div class="form-group col-md-offset-2 col-md-3">
                                                    <input type="text" id="FormDate" name="FormDate" class="form-control date-picker" placeholder="Enter From Date" />
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <input type="text" id="ToDate" name="ToDate" class="form-control date-picker" placeholder="Enter To Date" />
                                                </div>
                                                <div class="form-group  col-md-3">
                                                    <a href="#" class="btn  blue " onclick="PageLoadData(1);"><i class="fa fa-search"></i>&nbsp;&nbsp;Search</a>
                                                    <a onclick="exportexceldata()" class="btn btn-md btn-primary"><i class="fa fa-file-excel-o"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    <div id="PlaceUsersDataHere">
                                        <div class="row" id="nodataFound">
                                            <div class="col-lg-12 col-md-12  col-xs-12 col-sm-12 padding-5 bottom-border-verydark">
                                                <div class="alert alert-info clearfix profile-information padding-all-10 margin-all-0 backgroundDark">
                                                    <h1 class="font-white text-center"> No Data Found ! </h1>
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
    <script src="demo/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $("#FormDate").datepicker({
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

        function deletedata(task, id) {
            var errMsg = '';
            if (task == 'Delete') {
                errMsg = 'Are you sure to delete?';
            }
            if (confirm(errMsg)) {
                $('#loading').css("display", "block");
                $.ajax({
                    type: "POST",
                    url: "<?php echo $web_url; ?>Supervisor/AjaxAchieveReport.php",
                    data: {
                        action: task,
                        ID: id
                    },
                    success: function(msg) {
                        $('#loading').css("display", "none");
                        window.location.href = '';
                        return false;
                    },
                });
            }
            return false;
        }

        function FreezeTarget(task, id) {
            var errMsg = '';
            if (task == 'FreezeTarget') {
                errMsg = 'Are you sure to Freeze Target?';
            }
            if (confirm(errMsg)) {
                $('#loading').css("display", "block");
                $.ajax({
                    type: "POST",
                    url: "<?php echo $web_url; ?>Supervisor/AjaxAchieveReport.php",
                    data: {
                        action: task,
                        ID: id
                    },
                    success: function(msg) {
                        $('#loading').css("display", "none");
                        window.location.href = '';
                        return false;
                    },
                });
            }
            return false;
        }

        function PageLoadData(Page) {

            // var targetMonth = $('#targetMonth').val();
            // var targetYear = $('#targetYear').val();
            var FormDate = $("#FormDate").val();
            var ToDate = $("#ToDate").val();
            $('#loading').css("display", "block");
            $.ajax({
                type: "POST",
                url: "<?php echo $web_url; ?>Supervisor/AjaxAchieveReport.php",
                data: {
                    action: 'ListUser',
                    Page: Page,
                    FormDate: FormDate,
                    ToDate: ToDate
                },
                success: function(msg) {
                    $('#nodataFound').hide();
                    document.getElementById("listdetail").innerHTML = "List of View Target";
                    $("#PlaceUsersDataHere").html(msg);
                    $('#loading').css("display", "none");
                },
            });
        } // end of filter
        PageLoadData(1);

        function exportexceldata() {
            var FormDate = $('#FormDate').val();
            var ToDate = $('#ToDate').val();
            //window.location.href = 'exportCompanyMasterReport.php?FormDate=' + FormDate + "&ToDate=" + ToDate;
            var Url = '<?php echo $web_url; ?>Supervisor/exportAchieveReport.php?FormDate=' + FormDate + '&ToDate=' + ToDate;
            window.open(
                Url,
                '_blank' // <- This is what makes it open in a new window.
            );
        }
    </script>
</body>

</html>