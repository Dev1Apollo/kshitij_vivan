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
        <title><?php echo $ProjectName; ?> |Other</title>
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
                        
                        <div class="page-content-inner">
                            <div class="col-md-2">

                                <?php include_once './menu-lms.php'; ?>

                            </div>
                            <div class="col-md-10">
                                <div class="portlet light ">
                                    <div class="portlet-title">
                                        <div class="caption grey-gallery">
                                            <i class="icon-settings grey-gallery"></i>
                                            <span class="caption-subject bold uppercase" >List of Other</span>
                                        </div>
                                    </div>
                                    <div class="portlet-body form">
                                        <div class="row m-search-box">
                                            <div class="col-md-12">
                                                <form  role="form"  method="POST"  action="" name="frmSearch"  id="frmSearch" enctype="multipart/form-data">
                                                    <div class="row">
                                                        <div class="form-group  col-md-4">

                                                            <?php
                                                            $querysInq = "SELECT * FROM `status`  where isDelete='0'  and  istatus='1'  order by  statusId asc";
                                                            $resultsInq = mysqli_query($dbconn,$querysInq) or die(mysqli_error($dbconn));
                                                            echo '<select class="form-control" name="InquiryStatus[]" id="InquiryStatus" multiple="">';
                                                            //echo "<option value='' >Select Inquiry Status</option>";
                                                            while ($rowsInq = mysqli_fetch_array($resultsInq)) {
                                                                if ($rowsInq['statusId'] == '1')
                                                                    echo "<option value='" . $rowsInq['statusId'] . "' >" . $rowsInq['statusName'] . "</option>";
                                                                else
                                                                    echo "<option value='" . $rowsInq['statusId'] . "' >" . $rowsInq['statusName'] . "</option>";
                                                            }
                                                            echo "</select>";
                                                            ?>
                                                        </div>

                                                        <div class="form-group col-md-4">
                                                            <?php
                                                            $querysInq = "SELECT * FROM `inquirysource`  where isDelete='0'  and  istatus='1' order by  inquirySourceName asc";
                                                            $resultsInq = mysqli_query($dbconn,$querysInq) or die(mysqli_error($dbconn));
                                                            echo '<select class="form-control" name="InquirySource[]" id="InquirySource" multiple="">';
                                                            // echo "<option value='' >Select Source Of Inquiry</option>";
                                                            while ($rowsInq = mysqli_fetch_array($resultsInq)) {
                                                                echo "<option value='" . $rowsInq['inquirySourceId'] . "'>" . $rowsInq['inquirySourceName'] . "</option>";
                                                            }
                                                            echo "</select>";
                                                            ?>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group  col-md-4">
                                                            <input type="text" id="FormDate" name="FormDate" class="form-control date-picker" placeholder="Followup From Date"/>
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <input type="text" id="ToDate" name="ToDate" class="form-control date-picker" placeholder="Followup To Date" />
                                                        </div>

                                                        <div class="form-group col-md-4">
                                                            <input type="text" id="leadId" name="leadId" class="form-control" placeholder="Enter Lead Id" />
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group  col-md-4">
                                                            <input type="text" id="EntryFormDate" name="EntryFormDate" class="form-control date-picker" placeholder="Enter From Date"/>
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <input type="text" id="EntryToDate" name="EntryToDate" class="form-control date-picker" placeholder="Enter To Date" />
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group  col-md-4">
                                                            <input type="text" value="" name="firstName" class="form-control" id="firstName" placeholder="Search First Name " />
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <input type="text" value="" name="lastName" class="form-control" id="lastName" placeholder="Search Last Name " />
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <input type="text" value="" name="mobileNo" class="form-control" id="mobileNo" placeholder="Search Mobile No" pattern="[7-9]{1}[0-9]{9}" />
                                                        </div>
                                                        <div class="form-group col-md-4">
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

        <script src="assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>
        <script type="text/javascript">
                                                                $(document).ready(function () {

                                                                    $('#InquiryStatus').multiselect({
                                                                        nonSelectedText: 'Select Any Inquiry Status',
                                                                        includeSelectAllOption: true,

                                                                        buttonWidth: '100%',
                                                                        maxHeight: 250
                                                                    });

                                                                    $('#InquirySource').multiselect({
                                                                        nonSelectedText: 'Select Any Inquiry Source',
                                                                        includeSelectAllOption: true,

                                                                        buttonWidth: '100%',
                                                                        maxHeight: 250
                                                                    });

                                                                    $("#FormDate").datepicker({
                                                                        format: 'dd-mm-yyyy',
                                                                        minDate: 0,
                                                                        autoclose: true,
                                                                        todayHighlight: true,
                                                                        endDate: "now"


                                                                    });

                                                                    $("#EntryFormDate").datepicker({
                                                                        format: 'dd-mm-yyyy',
                                                                        minDate: 0,
                                                                        autoclose: true,
                                                                        todayHighlight: true,
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

                                                                    $("#EntryToDate").datepicker({
                                                                        format: 'dd-mm-yyyy',
                                                                        autoclose: true,
                                                                        todayHighlight: true,
                                                                        defaultDate: "now",
                                                                        endDate: "now"
                                                                    });

                                                                });
                                                                function PageLoadData(Page) {

                                                                    var EntryFormDate = $('#EntryFormDate').val();
                                                                    var EntryToDate = $('#EntryToDate').val();
                                                                    var FormDate = $('#FormDate').val();
                                                                    var ToDate = $('#ToDate').val();
                                                                    var InquiryStatus = $('#InquiryStatus').val();
                                                                    var CategoryOfInquiry = '';
                                                                    var leadId = $('#leadId').val();
                                                                    var InquirySource = $('#InquirySource').val();
                                                                    var firstName = $('#firstName').val();
                                                                    var lastName = $('#lastName').val();
                                                                    var mobileNo = $('#mobileNo').val();


                                                                    if (FormDate == '' && ToDate == '' && leadId == '' && InquiryStatus == '' && CategoryOfInquiry == '' && InquirySource == '' && firstName == '' && lastName == '' && mobileNo == '')
                                                                    {
                                                                        alert('Please Select Any One Field');
                                                                        return false;
                                                                    }


                                                                    $('#loading').css("display", "block");
                                                                    $.ajax({
                                                                        type: "POST",
                                                                        url: "ajax-other.php",
                                                                        data: {action: 'ListUser', Page: Page, InquiryStatus: InquiryStatus, leadId: leadId, CategoryOfInquiry: CategoryOfInquiry, FormDate: FormDate, ToDate: ToDate, InquirySource: InquirySource, firstName: firstName, lastName: lastName, mobileNo: mobileNo, EntryFormDate: EntryFormDate, EntryToDate: EntryToDate},
                                                                        success: function (msg) {

                                                                            $("#PlaceUsersDataHere").html(msg);
                                                                            $('#loading').css("display", "none");
                                                                        },
                                                                    });
                                                                }// end of filter
                                                                //PageLoadData(1);



        </script>
    </body>
</html>
