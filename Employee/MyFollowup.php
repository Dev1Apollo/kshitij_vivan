<?php
ob_start();
error_reporting(0);
require_once '../common.php';
$connect = new connect();
include('IsLogin.php');
?>
<!DOCTYPE html>

<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title><?php echo $ProjectName; ?> |My Follow Up</title>
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
                                <?php include_once './menu-lms.php'; ?>
                            </div>
                            <div class="col-md-10">
                                <div class="portlet light ">
                                    <div class="portlet-title">
                                        <div class="caption grey-gallery">
                                            <i class="icon-settings grey-gallery"></i>
                                            <span class="caption-subject bold uppercase" >List of My Follow Up</span>
                                        </div>
                                    </div>
                                    <div class="portlet-body form">
                                        <div class="row m-search-box">
                                            <div class="col-md-12">
                                                <form  role="form"  method="POST"  action="" name="frmSearch"  id="frmSearch" enctype="multipart/form-data">
                                                    <div class="row">
                                                        <div class="form-group  col-md-4">

                                                            <?php
                                                            $querysInq = "SELECT * FROM `status`  where isDelete='0'  and  istatus='1' and NOT statusId in ('2','3','4','5') order by  statusId asc";
                                                            $resultsInq = mysqli_query($dbconn, $querysInq) or die(mysqli_error($dbconn));
                                                            echo '<select class="form-control" name="InquiryStatus" id="InquiryStatus" >';
                                                            echo "<option value='' >Select Inquiry Status</option>";
                                                            while ($rowsInq = mysqli_fetch_array($resultsInq)) {
                                                                if ($rowsInq['statusId'] == '1')
                                                                    echo "<option value='" . $rowsInq['statusId'] . "' >" . $rowsInq['statusName'] . "</option>";
                                                                else
                                                                    echo "<option value='" . $rowsInq['statusId'] . "' >" . $rowsInq['statusName'] . "</option>";
                                                            }
                                                            echo "</select>";
                                                            ?>
                                                        </div>
                                                        <!--                                                        <div class="form-group col-md-4">
                                                        <?php
//                                                        $querysInqCat = "SELECT * FROM `categoryofinquiry`  where isDelete='0'  and  istatus='1' order by  id asc";
//                                                        $resultsInqCat = mysqli_query($dbconn, $querysInqCat) or die(mysqli_error($dbconn));
//                                                        echo '<select class="form-control" name="CategoryOfInquiry" id="CategoryOfInquiry" >';
//                                                        echo "<option value='' >Select  Category Of Inquiry</option>";
//                                                        while ($rowsInqCat = mysqli_fetch_array($resultsInqCat)) {
//                                                            echo "<option value='" . $rowsInqCat['id'] . "' >" . $rowsInqCat['COIname'] . "</option>";
//                                                        }
//                                                        echo "</select>";
                                                        ?>
                                                                                                                </div>-->
                                                        <div class="form-group col-md-4">
                                                            <?php
                                                            $querysInqCat = "SELECT * FROM `inquirysource`  where isDelete='0'  and  istatus='1' order by  inquirySourceName asc";
                                                            $resultsInqCat = mysqli_query($dbconn, $querysInqCat) or die(mysqli_error($dbconn));
                                                            echo '<select class="form-control" name="inquirySourceName" id="inquirySourceName" >';
                                                            echo "<option value='' >Select Inquiry Source</option>";
                                                            while ($rowsInqCat = mysqli_fetch_array($resultsInqCat)) {
                                                                echo "<option value='" . $rowsInqCat['inquirySourceId'] . "' >" . $rowsInqCat['inquirySourceName'] . "</option>";
                                                            }
                                                            echo "</select>";
                                                            ?>
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <?php
                                                            $queryEmp = "SELECT * FROM `employeemaster`  where isDelete='0'  and  istatus='1'  and (employeeReportTo='" . $_SESSION['EmployeeId'] . "' or employeeMasterId='" . $_SESSION['EmployeeId'] . "') order by  employeeName asc";
                                                            $resultEmp = mysqli_query($dbconn, $queryEmp) or die(mysqli_error($dbconn));
                                                            echo '<select class="form-control" name="Employee" id="Employee" >';
                                                            echo "<option value='' >Select Employee</option>";
                                                            while ($rowsEmp = mysqli_fetch_array($resultEmp)) {
                                                                if ($rowsEmp['employeeMasterId'] == $_SESSION['EmployeeId'])
                                                                    echo "<option value='" . $_SESSION['EmployeeId'] . "' selected>" . $_SESSION['EmployeeName'] . "</option>";
                                                                else
                                                                    echo "<option value='" . $rowsEmp['employeeMasterId'] . "'>" . $rowsEmp['employeeName'] . "</option>";
                                                            }
                                                            echo "</select>";
                                                            ?>
                                                        </div> 
                                                        <div class="form-group  col-md-4">
                                                            <input type="text" id="FormDate" name="FormDate" class="form-control date-picker" placeholder="Enter The From Date"/>
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <input type="text" id="ToDate" name="ToDate" class="form-control date-picker" placeholder="Enter The To Date" />
                                                        </div>
                                                        <div class="form-group  col-md-4">
                                                            <input type="text" value="" name="firstName" class="form-control" id="firstName" placeholder="Search First Name " />
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <input type="text" value="" name="lastName" class="form-control" id="lastName" placeholder="Search Last Name " />
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <input type="text" value="" name="mobileNo" class="form-control" id="mobileNo" placeholder="Search Mobile No" pattern="[7-9]{1}[0-9]{9}" />
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
        <script type="text/javascript">
                                                                $(document).ready(function () {

                                                                    $("#FormDate").datepicker({
                                                                        format: 'dd-mm-yyyy',
                                                                        minDate: 0,
                                                                        autoclose: true,
                                                                        todayHighlight: true,
                                                                        startDate: "now"


                                                                    });

                                                                });
                                                                $(document).ready(function () {

                                                                    $("#ToDate").datepicker({
                                                                        format: 'dd-mm-yyyy',
                                                                        autoclose: true,
                                                                        todayHighlight: true,
                                                                        defaultDate: "now",
                                                                        startDate: "now"
                                                                    });

                                                                });
                                                                function PageLoadData(Page) {
                                                                    var FormDate = $('#FormDate').val();
                                                                    var ToDate = $('#ToDate').val();
                                                                    var InquiryStatus = $('#InquiryStatus').val();
//                                                                    var CategoryOfInquiry = $('#CategoryOfInquiry').val();
                                                                    var inquirySourceName = $('#inquirySourceName').val();
                                                                    var Employee = $('#Employee').val();
                                                                    var firstName = $('#firstName').val();
                                                                    var lastName = $('#lastName').val();
                                                                    var mobileNo = $('#mobileNo').val();

                                                                    $('#loading').css("display", "block");
                                                                    $.ajax({
                                                                        type: "POST",
                                                                        url: "<?php echo $web_url; ?>Employee/AjaxMyFollowup.php",
                                                                        data: {action: 'ListUser', Page: Page, InquiryStatus: InquiryStatus, inquirySourceName: inquirySourceName, Employee: Employee, FormDate: FormDate, ToDate: ToDate, firstName: firstName, lastName: lastName, mobileNo: mobileNo},
                                                                        success: function (msg) {

                                                                            $("#PlaceUsersDataHere").html(msg);
                                                                            $('#loading').css("display", "none");
                                                                        },
                                                                    });
                                                                }// end of filter
                                                                PageLoadData(1);



        </script>
    </body>
</html>
