<?php
ob_start();
error_reporting(E_ALL);
require_once '../common.php';
$connect = new connect();
include('IsLogin.php');

$result = mysqli_query($dbconn, "SELECT * FROM `studentemidetail` WHERE `studemiId`='" . $_REQUEST['token'] . "' and studentemidetail.isDelete=0");
if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_array($result);
} else {
    echo 'somthig going worng! try again';
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">

    <link rel="shortcut icon" href="images/favicon.png">
    <title> <?php echo $ProjectName ?> |Edit Student Course</title>
    <?php include_once './include.php'; ?>
    <link href="demo/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet" type="text/css" />

</head>

<body class="page-container-bg-solid page-boxed">
    <?php
    include('header.php');
    ?>
    <div style="display: none; z-index: 10060;" id="loading">
        <img id="loading-image" src="<?php echo $web_url; ?>Supervisor/images/loader1.gif">
    </div>
    <div class="page-container">
        <div class="page-content">
            <div class="container">
                <ul class="page-breadcrumb breadcrumb">
                    <li>
                        <a href="<?php echo $web_url; ?>Supervisor/index.php">Home</a>
                        <i class="fa fa-circle"></i>
                    </li>


                    <li>
                        <span> Edit Student Course</span>

                    </li>
                </ul>

                <div class="page-content-inner">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="portlet light ">
                                <div class="portlet-title">
                                    <div class="caption grey-gallery">
                                        <i class="icon-settings grey-gallery"></i>
                                        <span class="caption-subject bold uppercase"> Edit Student Course</span>
                                    </div>
                                </div>
                                <div class="portlet-body form">


                                    <form role="form" method="POST" action="" name="frmparameter" id="frmparameter" enctype="multipart/form-data">
                                        <input type="hidden" value="EditEMi" name="action" id="action">
                                        <input type="hidden" value="<?php echo $row['studemiId'] ?>" name="studemiId" id="studentcourseId">
                                        <input type="hidden" value="<?php echo $row['stud_id'] ?>" name="stud_id" id="stud_id">
                                        <div class="form-body">
                                            <div class="row">
                                                <div class="form-group col-md-4">
                                                    <label for="form_control_1">EMI Date</label>
                                                    <div class="txt_field" id="FeeDiv">
                                                        <input name="emiDate" id="emiDate" value="<?php echo $row['emiDate'] ?>" class="form-control date-picker" placeholder="Enter The EMI Date" type="text" required="" readonly="readonly">
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label for="form_control_1">EMI Amount</label>
                                                    <input name="emiAmount" id="emiAmount" value="<?php echo $row['emiAmount']; ?>" readonly="readonly" class="form-control" placeholder="Enter The EMI Amount" type="text">
                                                </div>
                                                <div class="form-group col-md-4">

                                                    <label for="form_control_1">EMI Received Date</label>
                                                    <input name="emiReceivedDate" id="emiReceivedDate" value="<?php echo $row['emiReceivedDate']; ?>" class="form-control date-picker" placeholder="Enter The EMI Received Date" type="text">
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label for="form_control_1">EMI Received Amount</label>
                                                    <input name="actualReceivedAmount" id="actualReceivedAmount" value="<?php echo $row['actualReceivedAmount']; ?>" class="form-control date-picker" placeholder="Enter The EMI Received Amount" type="text">
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label for="form_control_1">Comment</label>
                                                    <div id="errordiv"></div>
                                                    <input name="comments" id="comments" class="form-control date-picker" value="<?php echo $row['comments']; ?>" placeholder="Enter The Comment" type="text">
                                                </div>


                                            </div>

                                            <div class="form-actions noborder">
                                                <input class="btn blue margin-top-20" type="submit" id="Btnmybtn" value="Submit" name="submit">
                                                <button type="button" class="btn blue margin-top-20" onClick="checkclose();">Cancel</button>
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
    </div>
    <?php include_once './footer.php'; ?>
    <script src="<?php echo $web_url; ?>Supervisor/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>

    <script type="text/javascript">
        function checkclose() {
            history.go(-1);
            return false;
            //            window.location.href = '<?php echo $web_url; ?>Supervisor/student-emi.php';
        }

        $(document).ready(function() {
            $("#emiReceivedDate").datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                todayHighlight: true,
                defaultDate: "now",
                //                                                                                            endDate: "now"
            });

        });

        $(document).ready(function() {
            $("#actualReceivedAmount").keydown(function(e) {
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
        //                                    $(document).ready(function () {
        //
        //                                        $("#dateOfJoining").datepicker({
        //                                            format: 'dd-mm-yyyy',
        //                                            autoclose: true,
        //                                            todayHighlight: true,
        //                                            defaultDate: "now",
        //                                            //                                                                                            endDate: "now"
        //                                        });
        //
        //                                    });
        $(document).ready(function() {

            $("#emiStartDate").datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                todayHighlight: true,
                defaultDate: "now",
                //                                                                                            endDate: "now"
            });

        });

        function getfee() {
            var q = $('#cid').val();
            //                alert(q);
            var urlp = '<?php echo $web_url; ?>Supervisor/findfee.php?cid=' + q;
            $.ajax({
                type: 'POST',
                url: urlp,
                success: function(data) {
                    //                        alert(data);
                    $('#FeeDiv').html(data);
                }
            }).error(function() {
                alert('An error occured');
            });

        }

        function getAmount() {
            var offeredAmount = $('#offeredfee').val();
            var noOfEmi = $('#noOfEmi').val();
            var stud_id = $('#stud_id').val();
            var joinAmount = $('#joinAmount').val();
            var urlp = '<?php echo $web_url; ?>Supervisor/findEmiAmount.php?offeredfee=' + offeredAmount + "&noOfEmi=" + noOfEmi + "&stud_id=" + stud_id + "&joinAmount=" + joinAmount;
            $.ajax({
                type: 'POST',
                url: urlp,
                success: function(data) {
                    //                        alert(data);
                    $('#emiAmountDiv').html(data);
                }
            }).error(function() {
                alert('An error occured');
            });

        }
        $('#frmparameter').submit(function(e) {

            e.preventDefault();
            var $form = $(this);
            $('#loading').css("display", "block");
            $.ajax({
                type: 'POST',
                url: '<?php echo $web_url; ?>Supervisor/querydata.php',
                data: $('#frmparameter').serialize(),
                success: function(response) {
                    alert(response);
                    console.log(response);
                    //$("#Btnmybtn").attr('disabled', 'disabled');
                    if (response != 0) {
                        $('#loading').css("display", "none");
                        $("#Btnmybtn").attr('disabled', 'disabled');
                        alert(' Edited Sucessfully.');
                        response = response.trim();
                        window.location.href = '<?php echo $web_url; ?>Supervisor/student-emi.php?token=' + response;
                    }
                }

            });
        });
    </script>

</body>

</html>