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
        <title><?php echo $ProjectName; ?> | Student Counseling </title>
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
                                            <span class="caption-subject bold uppercase" id="listdetail">Student Counseling</span>
                                        </div>
                                    </div>
                                    <div class="portlet-body form">

                                        <form  role="form"  method="POST"  action="" name="frmSearch"  id="frmSearch" enctype="multipart/form-data">
                                            <div class="row m-search-box">
                                                <div class="col-md-12"  >
                                                    <div class="form-group  col-md-4">
                                                        <label>First Name</label>
                                                        <input type="text"  name="firstName" class="form-control" id="firstName" placeholder="First Name " />
                                                    </div>
                                                    <div class="form-group  col-md-4">
                                                        <label>Middle Name</label>
                                                        <input type="text"  name="middleName" class="form-control" id="middleName" placeholder="Middle Name " />
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label>Surname</label>
                                                        <input type="text"  name="surName" class="form-control" id="surName" placeholder="Surname " />
                                                    </div>

                                                    <div class="form-group col-md-4">
                                                        <label>Course</label>
                                                        <select class="form-control" name="cid" id="cid" onchange="getfee();">
                                                            <option>Select Course Name</option>
                                                            <?php
                                                            $rowdata = mysqli_query($dbconn,"SELECT * FROM `course` where istatus=1 and isDelete=0 ORDER by courseName ASC");
                                                            while ($resultdata = mysqli_fetch_array($rowdata)) {
                                                                ?>
                                                                <option value="<?php echo $resultdata['courseId'] ?>"><?php echo $resultdata['courseName'] ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label>Fee</label>
                                                        <div id="FeeDiv" >
                                                            <input type="text" name="fee" id="fee" class="form-control" placeholder="Fee" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label>Offered Fee</label>
                                                        <input type="text"  name="offeredfee" class="form-control" id="offeredfee" placeholder="Offered Fee" />
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label>Booking Amount</label>
                                                        <input type="text"  name="booking_amount" class="form-control" id="booking_amount" placeholder="Booking Amount" />
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label>Joining Date</label>
                                                        <input type="text"  name="dateOfJoining" class="form-control" id="dateOfJoining" placeholder="Date of Joining" />
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label>Joining Amount</label>
                                                        <input type="text"  name="joinAmount" class="form-control" id="joinAmount" placeholder="Joining Amount" />
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label>Payment Type</label>
                                                        <select class="form-control" name="emiId" id="emiId">
                                                            <option>Select Payment Type</option>
                                                            <?php
                                                            $rowdata = mysqli_query($dbconn,"SELECT * FROM `emitype`");
                                                            while ($resultdata = mysqli_fetch_array($rowdata)) {
                                                                ?>
                                                                <option value="<?php echo $resultdata['emiId'] ?>"><?php echo $resultdata['emiTypeName'] ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label>EMI Start Date</label>
                                                        <input type="text"  name="emiStartDate" class="form-control" id="emiStartDate" placeholder="EMI Start Date" />
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label>Number of EMI</label>
                                                        <input type="text"  name="noOfEmi" class="form-control" id="noOfEmi" placeholder="Number of EMI" />
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label>EMI Amount</label>
                                                        <div id="emiAmountDiv"></div>
                                                        <input name="emiAmount" id="emiAmount" class="form-control" type="text"  class="form-control" placeholder="Emi Amount" onblur="checkAmount();">
                                                    </div>

                                                    <div class="form-group  col-md-2">
                                                        <a onclick="exportPDFdata()" class="btn btn-block blue pull-right margin-top-20" target="_blank">Submit</a>

                                                    </div>
                                                </div>
                                            </div>
                                        </form>
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

                                                                $("#dateOfJoining").datepicker({
                                                                    format: 'dd-mm-yyyy',
                                                                    autoclose: true,
                                                                    todayHighlight: true,
                                                                    defaultDate: "now",
                                                                    //                                                                                            endDate: "now"
                                                                });

                                                            });
                                                            $(document).ready(function () {

                                                                $("#emiStartDate").datepicker({
                                                                    format: 'dd-mm-yyyy',
                                                                    autoclose: true,
                                                                    todayHighlight: true,
                                                                    defaultDate: "now",
                                                                    //                                                                                            endDate: "now"
                                                                });

                                                            });
                                                            $(document).ready(function () {
                                                        $("#fee").keydown(function (e) {
                                                            // Allow: backspace, delete, tab, escape, enter and .
                                                            if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110]) !== -1 ||
                                                                    // Allow: Ctrl+A, Command+A
                                                                            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
                                                                            // Allow: home, end, left, right, down, up
                                                                                    (e.keyCode >= 35 && e.keyCode <= 40)) {
                                                                        // let it happen, don't do anything
                                                                        return;
                                                                    }
                                                                    // Ensure that it is a number and stop the keypress
                                                                    if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                                                                        e.preventDefault();
                                                                    }
                                                                });
                                                    });
                                                    $(document).ready(function () {
                                                        $("#offeredfee").keydown(function (e) {
                                                            // Allow: backspace, delete, tab, escape, enter and .
                                                            if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110]) !== -1 ||
                                                                    // Allow: Ctrl+A, Command+A
                                                                            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
                                                                            // Allow: home, end, left, right, down, up
                                                                                    (e.keyCode >= 35 && e.keyCode <= 40)) {
                                                                        // let it happen, don't do anything
                                                                        return;
                                                                    }
                                                                    // Ensure that it is a number and stop the keypress
                                                                    if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                                                                        e.preventDefault();
                                                                    }
                                                                });
                                                    });
                                                    $(document).ready(function () {
                                                        $("#booking_amount").keydown(function (e) {
                                                            // Allow: backspace, delete, tab, escape, enter and .
                                                            if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110]) !== -1 ||
                                                                    // Allow: Ctrl+A, Command+A
                                                                            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
                                                                            // Allow: home, end, left, right, down, up
                                                                                    (e.keyCode >= 35 && e.keyCode <= 40)) {
                                                                        // let it happen, don't do anything
                                                                        return;
                                                                    }
                                                                    // Ensure that it is a number and stop the keypress
                                                                    if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                                                                        e.preventDefault();
                                                                    }
                                                                });
                                                    });
                                                    $(document).ready(function () {
                                                        $("#joinAmount").keydown(function (e) {
                                                            // Allow: backspace, delete, tab, escape, enter and .
                                                            if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110]) !== -1 ||
                                                                    // Allow: Ctrl+A, Command+A
                                                                            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
                                                                            // Allow: home, end, left, right, down, up
                                                                                    (e.keyCode >= 35 && e.keyCode <= 40)) {
                                                                        // let it happen, don't do anything
                                                                        return;
                                                                    }
                                                                    // Ensure that it is a number and stop the keypress
                                                                    if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                                                                        e.preventDefault();
                                                                    }
                                                                });
                                                    });
                                                    $(document).ready(function () {
                                                        $("#emiAmount").keydown(function (e) {
                                                            // Allow: backspace, delete, tab, escape, enter and .
                                                            if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110]) !== -1 ||
                                                                    // Allow: Ctrl+A, Command+A
                                                                            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
                                                                            // Allow: home, end, left, right, down, up
                                                                                    (e.keyCode >= 35 && e.keyCode <= 40)) {
                                                                        // let it happen, don't do anything
                                                                        return;
                                                                    }
                                                                    // Ensure that it is a number and stop the keypress
                                                                    if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                                                                        e.preventDefault();
                                                                    }
                                                                });
                                                    });
                                                            function getfee()
                                                            {
                                                                var q = $('#cid').val();

                                                                var urlp = '<?php echo $web_url; ?>Employee/findcoursefees.php?cid=' + q;
                                                                $.ajax({
                                                                    type: 'POST',
                                                                    url: urlp,
                                                                    success: function (data) {

                                                                        $('#FeeDiv').html(data);
                                                                    }
                                                                }).error(function () {
                                                                    alert('An error occured');
                                                                });

                                                            }
                                                            function checkAmount() {
                                                                var offeredAmount = $('#offeredfee').val();
                                                                var noOfEmi = $('#noOfEmi').val();
                                                                var joinAmount = $('#joinAmount').val();
                                                                var BookingAmount = $('#booking_amount').val();
                                                                var EmaiAmount = offeredAmount - BookingAmount - joinAmount;
                                                                EmaiAmount = Math.round(EmaiAmount / noOfEmi);



                                                                $('#emiAmount').val(EmaiAmount);
                                                            }
                                                            function exportPDFdata()
                                                            {
                                                                var a = document.createElement("a");

                                                                var firstName = $('#firstName').val();
                                                                var middleName = $('#middleName').val();
                                                                var surName = $('#surName').val();
                                                                var cid = $('#cid').val();
                                                                var fee = $('#fee').val();
                                                                var offeredfee = $('#offeredfee').val();
                                                                var booking_amount = $('#booking_amount').val();
                                                                var dateOfJoining = $('#dateOfJoining').val();
                                                                var joinAmount = $('#joinAmount').val();
                                                                var emiId = $('#emiId').val();
                                                                var emiStartDate = $('#emiStartDate').val();
                                                                var noOfEmi = $('#noOfEmi').val();
                                                                var emiAmount = $('#emiAmount').val();
//                                                                a.target = "_blank";
//                                                                a.href = 'Student-Councillor-Pdf.php?firstName=' + firstName + "&middleName=" + middleName  + "&surName=" + surName + "&cid=" + cid + "&fee=" + fee + "&offeredfee=" + offeredfee + "&booking_amount=" + booking_amount + "&dateOfJoining=" + dateOfJoining  + "&joinAmount=" + joinAmount + "&emiId=" + emiId + "&emiStartDate=" + emiStartDate + "&noOfEmi=" + noOfEmi + "&emiAmount=" + emiAmount;
//                                                                a.click();
                                                                window.open('Student-Councillor-Pdf.php?firstName=' + firstName + "&middleName=" + middleName + "&surName=" + surName + "&cid=" + cid + "&fee=" + fee + "&offeredfee=" + offeredfee + "&booking_amount=" + booking_amount + "&dateOfJoining=" + dateOfJoining + "&joinAmount=" + joinAmount + "&emiId=" + emiId + "&emiStartDate=" + emiStartDate + "&noOfEmi=" + noOfEmi + "&emiAmount=" + emiAmount, target = '_blank')
//                                                                window.location.href = 'Student-Councillor-Pdf.php?firstName=' + firstName + "&middleName=" + middleName  + "&surName=" + surName + "&cid=" + cid + "&fee=" + fee + "&offeredfee=" + offeredfee + "&booking_amount=" + booking_amount + "&dateOfJoining=" + dateOfJoining  + "&joinAmount=" + joinAmount + "&emiId=" + emiId + "&emiStartDate=" + emiStartDate + "&noOfEmi=" + noOfEmi + "&emiAmount=" + emiAmount;
                                                            }



        </script>
    </body>
</html>