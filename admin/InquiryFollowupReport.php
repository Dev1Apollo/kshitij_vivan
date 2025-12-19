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
        <title><?php echo $ProjectName; ?> | Follow Up Report </title>
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
                                <a href="<?php echo $web_url; ?>admin/index.php">Home</a>
                                <i class="fa fa-circle"></i>
                            </li>

                            <li>
                                <span> Follow Up Report</span>
                            </li>
                        </ul>

                        <div class="page-content-inner">




                            <div class="col-md-12">
                                <div class="portlet light ">
                                    <div class="portlet-title">
                                        <div class="caption grey-gallery">
                                            <i class="icon-settings grey-gallery"></i>
                                            <span class="caption-subject bold uppercase" >List of  Follow Up Report</span>
                                        </div>
                                    </div>
                                    <div class="portlet-body form">
                                        <div class="row m-search-box">
                                            <div class="col-md-12">
                                                <form  role="form"  method="POST"  action="" name="frmSearch"  id="frmSearch" enctype="multipart/form-data">
                                                    <div class="row">
                                                        <div class="form-group col-md-offset-1 col-md-3">
                                                            <input type="text" id="FormDate" name="FormDate" value="<?php echo date('d-m-Y'); ?>" class="form-control date-picker" placeholder="Enter The From Date"/>
                                                        </div>
                                                        <div class="form-group col-md-3">
                                                            <input type="text" id="ToDate" name="ToDate" value="<?php echo date('d-m-Y'); ?>" class="form-control date-picker" placeholder="Enter The To Date" />
                                                        </div>
                                                        <div class="form-group col-md-3">
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

        <?php include_once './footer.php'; ?>
        <script src="<?php echo $web_url; ?>admin/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>

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
                                                                        //defaultDate: "now",
                                                                        //endDate: "now"
                                                                    });

                                                                });
                                                                function PageLoadData(Page) {
                                                                    var FormDate = $('#FormDate').val();
                                                                    var ToDate = $('#ToDate').val();
                                                                    var Employee = $('#Employee').val();

                                                                    if (FormDate == '' && ToDate == '' && Employee == '')
                                                                    {
                                                                        alert('Please Select Any One Field');
                                                                        return false;
                                                                    }
                                                                    $('#loading').css("display", "block");
                                                                    $.ajax({
                                                                        type: "POST",
                                                                        url: "<?php echo $web_url; ?>admin/AjaxInquiryFollowupReport.php",
                                                                        data: {action: 'ListUser', Page: Page, FormDate: FormDate, ToDate: ToDate, Employee: Employee},
                                                                        success: function (msg) {

                                                                            $("#PlaceUsersDataHere").html(msg);
                                                                            $('#loading').css("display", "none");
                                                                        },
                                                                    });
                                                                }// end of filter
                                                                PageLoadData(1);
                                                                function exportexceldata()
                                                                {
                                                                    var FormDate = $('#FormDate').val();
                                                                    var ToDate = $('#ToDate').val();
                                                                    var Employee = $('#Employee').val();
                                                                    window.location.href = 'export-followup.php?FormDate=' + FormDate + "&ToDate=" + ToDate + "&Employee" + Employee;
                                                                }


        </script>
    </body>
</html>