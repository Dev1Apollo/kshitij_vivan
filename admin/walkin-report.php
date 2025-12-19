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
        <title><?php echo $ProjectName; ?> |Walk-In Inquiry </title>
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
                                <span>Walk-In Inquiry</span>
                            </li>
                        </ul>
                        <div class="page-content-inner">
                            <div class="col-md-12">
                                <div class="portlet light ">
                                    <div class="portlet-title">
                                        <div class="caption grey-gallery">
                                            <i class="icon-settings grey-gallery"></i>
                                            <span class="caption-subject bold uppercase" >List of Walk-In Inquiry</span>
                                        </div>
                                    </div>
                                    <div class="portlet-body">
                                        <div class="row m-search-box">
                                            <div class="col-md-12">
                                                <form  role="form"  method="POST"  action="" name="frmSearch"  id="frmSearch" enctype="multipart/form-data">
                                                    <div class="row">
                                                        <div class="form-group col-md-2">
                                                            <input type="text" id="FormDate" name="FormDate" class="form-control date-picker" placeholder="Enter The From Date"/>
                                                        </div>
                                                        <div class="form-group col-md-2">
                                                            <input type="text" id="ToDate" name="ToDate" class="form-control date-picker" placeholder="Enter The To Date" />
                                                        </div>
                                                        <div class="form-group col-md-2">
                                                            <?php
                                                            $querysInq = "SELECT * FROM `inquirysource`  where isDelete='0'  and  istatus='1' order by  inquirySourceName asc";
                                                            $resultsInq = mysqli_query($dbconn,$querysInq) or die(mysqli_error($dbconn));
                                                            echo '<select class="form-control" name="InquirySource[]" id="InquirySource" multiple="multiple" required>';
                                                            while ($rowsInq = mysqli_fetch_array($resultsInq)) {
                                                                echo "<option value='" . $rowsInq['inquirySourceId'] . "'>" . $rowsInq['inquirySourceName'] . "</option>";
                                                            }
                                                            echo "</select>";
                                                            ?>
                                                        </div>
                                                        <div class="form-group col-md-2">
                                                            <?php
                                                            $querysBranch = "SELECT * FROM `branchmaster`  where isDelete='0' order by  branchid asc";
                                                            $resultsBranch = mysqli_query($dbconn,$querysBranch) or die(mysqli_error($dbconn));
                                                            echo '<select class="form-control" name="branch" id="branch">';
                                                            echo "<option value='' >Select Branch</option>";
                                                            while ($rowsBranch = mysqli_fetch_array($resultsBranch)) {
                                                                echo "<option value='" . $rowsBranch['branchid'] . "'>" . $rowsBranch['branchname'] . "</option>";
                                                            }
                                                            echo "</select>";
                                                            ?>
                                                        </div>
                                                        <div class="form-group col-md-2">
                                                            <?php
                                                            $queryEmp = "SELECT * FROM `employeemaster`  where isDelete='0'  and  istatus='1'  order by  employeeName asc";
                                                            $resultEmp = mysqli_query($dbconn,$queryEmp) or die(mysqli_error($dbconn));
                                                            echo '<select class="form-control" name="Employee" id="Employee" >';
                                                            echo "<option value='' >Select Employee</option>";
                                                            while ($rowsEmp = mysqli_fetch_array($resultEmp)) {
                                                                echo "<option value='" . $rowsEmp['employeeMasterId'] . "'>" . $rowsEmp['employeeName'] . "</option>";
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

        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                         <form method="POST" role="form" enctype="multipart/form-data" name="frmparameter" id="frmparameter" action="querydata.php">
                            <input type="hidden" value="LeadWalkinChane" name="action" id="action" />
                            <input type="hidden" value="" name="token" id="token" />
                            <div class="modal-body">  
                                <div class="form-group">  
                                    <label class="form_control">Walk-in Date</label>
                                    <input type="text" name="walkin_datetime" class="form-control datetimepicker" id="walkin_datetime" required=""/>
                                </div>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="submitForm()">
                                    Save
                                </button>
                                <button type="button" class="btn btn-light-scale-2" data-dismiss="modal">
                                    Close
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <?php include_once './footer.php'; ?>

        <script src="<?php echo $web_url; ?>admin/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
        <script>

                                                                $(document).ready(function () {
                                                                    $("input[id=walkin_datetime]").datetimepicker({
                                                                        format: "dd-mm-yyyy hh:ii:ss",
                                                                        autoclose: true,
                                                                        todayHighlight: true,
                                                                        startDate: "now"
                                                                    });
                                                                });
                                                                function PageLoadData(Page) {
                                                                    var FormDate = $('#FormDate').val();
                                                                    var ToDate = $('#ToDate').val();
                                                                    var InquirySource = $('#InquirySource').val();
                                                                    var Employee = $('#Employee').val();
                                                                    var branch = $('#branch').val();
                                                                    if (FormDate == '' && ToDate == '' && Employee == '')
                                                                    {
                                                                        alert('Please Select Any One Field');
                                                                        return false;
                                                                    }

                                                                    $('#loading').css("display", "block");
                                                                    $.ajax({
                                                                        type: "POST",
                                                                        url: "ajax-walkin.php",
                                                                        data: {action: 'ListUser', Page: Page, FormDate: FormDate, ToDate: ToDate, InquirySource: InquirySource, Employee: Employee, branch: branch},
                                                                        success: function (msg) {

                                                                            $("#PlaceUsersDataHere").html(msg);
                                                                            $('#loading').css("display", "none");
                                                                        },
                                                                    });
                                                                }// end of filter
                                                                // PageLoadData(1);

                                                                function ChangeWalkin(id, date)
                                                                {
                                                                    $('#token').val(id);
                                                                    $('#walkin_datetime').val(date);
                                                                    $("#exampleModal").modal('show');
                                                                }

                                                                function submitForm() {
                                                                    var token=$('#token').val() 
                                                                    var walkin_datetime = $('#walkin_datetime').val();
                                                                    $('#loading').css("display", "block");
                                                                    $.ajax({
                                                                        type: 'POST',
                                                                        url: 'querydata.php',
                                                                        data: {action: 'LeadWalkinChane', token: token, walkin_datetime: walkin_datetime},
                                                                        success: function (response) {
                                                                            console.log(response);
//                                                                            alert(response);
                                                                            $("#Btnmybtn").attr('disabled', 'disabled');

                                                                            if (response != 0)
                                                                            {
                                                                                $('#loading').css("display", "none");
                                                                                $("#Btnmybtn").attr('disabled', 'disabled');
                                                                                alert('Walk-in Date Updated Successfully.');
                                                                                window.location.href = '';

                                                                            } else
                                                                            {
                                                                                $('#loading').css("display", "none");
                                                                                $("#Btnmybtn").attr('disabled', 'disabled');
                                                                                alert('Invalid Request.');
                                                                                window.location.href = '';
                                                                                return false;
                                                                            }
                                                                        }
                                                                    });
                                                                }

                                                                function exportexceldata()
                                                                {
                                                                    var FormDate = $('#FormDate').val();
                                                                    var ToDate = $('#ToDate').val();
                                                                    var InquirySource = $('#InquirySource').val();
                                                                    var Employee = $('#Employee').val();
                                                                    var branch = $('#branch').val();
                                                                    window.location.href='export-walking-report.php?FormDate='+FormDate +"&ToDate="+ToDate+"&InquirySource="+InquirySource+"&Employee="+Employee+"&branch="+branch;
                                                                }


        </script>
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
        </script>
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
    </body>
</html>