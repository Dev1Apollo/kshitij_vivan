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
    <title><?php echo $ProjectName; ?> | Course Report</title>
    <?php include_once './include.php'; ?>
    <link href="<?php echo $web_url; ?>Supervisor/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet" type="text/css" />
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
                            <?php include_once './menu-admission.php'; ?>
                        </div>
                        <div class="col-md-10">
                            <div class="portlet light ">
                                <div class="portlet-title">
                                    <div class="caption grey-gallery">
                                        <i class="icon-settings grey-gallery"></i>
                                        <span class="caption-subject bold uppercase">Course Report</span>
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <div class="row m-search-box">
                                        <div class="col-md-12">
                                            <form role="form" method="POST" action="" name="frmSearch" id="frmSearch" enctype="multipart/form-data">
                                                <div class="row">
                                                    <div class="form-group col-md-3">
                                                        <input type="text" id="FormDate" name="FormDate" class="form-control date-picker" placeholder="Enter From Date" />
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <input type="text" id="ToDate" name="ToDate" class="form-control date-picker" placeholder="Enter To Date" />
                                                    </div>
                                                    <?php if ($_SESSION['EmployeeType'] == 'Supervisor') { ?>
                                                        <div class="form-group col-md-3">
                                                        <?php
                                                        $querysBranch = "SELECT * FROM `branchmaster` where isDelete='0' order by  branchid asc";
                                                        $resultsBranch = mysqli_query($dbconn, $querysBranch) or die(mysqli_error($dbconn));
                                                        echo '<select class="form-control" name="branchid" id="branchid">';
                                                        echo "<option value='' >Select</option>";
                                                        while ($rowsBranch = mysqli_fetch_array($resultsBranch)) {
                                                            echo "<option value='" . $rowsBranch['branchid'] . "'>" . $rowsBranch['branchname'] . "</option>";
                                                        }
                                                        echo "</select>";
                                                        ?>
                                                    </div>
                                                    <?php } ?>
                                                    <!-- <div class="form-group col-md-2">
                                                            <select name="studentcourseId[]" id="studentcourseId"  class="form-control" multiple="multiple" required="">
                                                                <?php
                                                                $filterCourse = mysqli_query($dbconn, "SELECT courseId,courseName FROM `course` where istatus=1 and isDelete=0");
                                                                while ($strCourse = mysqli_fetch_array($filterCourse)) {
                                                                ?>
                                                                    <option value="<?php echo $strCourse['courseId']; ?>"><?php echo $strCourse['courseName']; ?></option>
                                                                    <?php
                                                                }
                                                                    ?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-2">
                                                            <select name="studentStatus[]" id="studentStatus" multiple="multiple" class="form-control" required="">
                                                                <?php
                                                                $filterStatus = mysqli_query($dbconn, "SELECT * FROM `studentstatus` where istatus=1 and isDelete=0");
                                                                while ($strStatus = mysqli_fetch_array($filterStatus)) {
                                                                ?>
                                                                    <option value="<?php echo $strStatus['studstatusid']; ?>"><?php echo $strStatus['studentStatusName']; ?></option>
                                                                    <?php
                                                                }
                                                                    ?>
                                                            </select>
                                                        </div> -->
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
        .multiselect {
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
    <link href="assets/bootstrap-multiselect.css" rel="stylesheet" type="text/css" />
    <script src="assets/bootstrap-multiselect.js" type="text/javascript"></script>

    <script src="<?php echo $web_url; ?>Supervisor/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>
    <script>
        $(document).ready(function() {
            $("#FormDate").datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                todayHighlight: true,
                defaultDate: "now"
            });
        });
        $(document).ready(function() {
            $('#studentcourseId').multiselect({
                nonSelectedText: 'Select Course',
                includeSelectAllOption: true,
                buttonWidth: '100%',

            });
            $('#studentStatus').multiselect({
                nonSelectedText: 'Select Status',
                includeSelectAllOption: true,
                buttonWidth: '100%',
            });
        });
        $(document).ready(function() {
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
            var studentcourseId = $('#studentcourseId').val();
            var studentStatus = $('#studentStatus').val();
            var branchid = $("#branchid").val();
            $('#loading').css("display", "block");
            $.ajax({
                type: "POST",
                url: "AjaxCourseReport.php",
                data: {
                    action: 'ListUser',
                    Page: Page,
                    studentStatus: studentStatus,
                    FormDate: FormDate,
                    ToDate: ToDate,
                    studentcourseId: studentcourseId,
                    branchid: branchid
                },
                success: function(msg) {
                    $("#PlaceUsersDataHere").html(msg);
                    $('#loading').css("display", "none");
                },
            });
        } // end of filter
        // PageLoadData(1);

        function exportexceldata() {
            var FormDate = $('#FormDate').val();
            var ToDate = $('#ToDate').val();
            var branchid = $("#branchid").val();
            // var studentPortal_Id = $('#studentPortal_Id').val();
            // var studentStatus = $('#studentStatus').val();
            //window.location.href = 'export-Course-Report.php?FormDate=' + FormDate + "&ToDate=" + ToDate + '&studentPortal_Id=' + studentPortal_Id + '&studentStatus=' + studentStatus;
            window.location.href = 'export-Course-Report.php?FormDate=' + FormDate + "&ToDate=" + ToDate+"&branchid="+branchid;
        }
    </script>
</body>

</html>