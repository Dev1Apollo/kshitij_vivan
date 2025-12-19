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
        <title><?php echo $ProjectName; ?> | Student Admission </title>
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
                            <div class="col-md-12" id="studentcoursefrom">
                                <div class="portlet light ">
                                    <div class="portlet-title">
                                        <div class="caption grey-gallery">
                                            <i class="icon-settings grey-gallery"></i>
                                            <span class="caption-subject bold uppercase" id="listdetail">Student Course Details</span>
                                        </div>
                                    </div>
                                    <div class="portlet-body">
                                        <form  role="form"  method="POST"  action="" name="frmparameter"  id="frmparameter" enctype="multipart/form-data">
                                            <input type="hidden" value="EditStudentEmi" name="action" id="action">
                                            <?php
                                            $stud_id = $_REQUEST['token'];
                                            $query = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM studentadmission where stud_id=" . $stud_id));
                                            $stundentData = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `studentcourse` where studentcourseId=" . $_REQUEST['refToken'] . " and studentcourse.istatus=1"));
                                            ?>
                                            <input type="hidden" value="<?php echo $stud_id; ?>" name="stud_id" id="stud_id">
                                            <input type="hidden" value="<?php echo $_REQUEST['refToken']; ?>" name="studentcourseId" id="studentcourseId">
                                            <input type="hidden" value="<?php echo $stundentData['booking_amount'] ?>" name="booking_amount" id="booking_amount">
                                            <div class="form-body">
                                                <div id="StudentCourse">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <h4 class="bold text-center">Course Details</h4>
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label for="form_control_1">Portal: </label>
                                                            <span>
                                                                <strong>
                                                                    <?php
                                                                    if ($query['studentPortal_Id'] == 1) {
                                                                        echo 'Maac Satellite';
                                                                    } elseif ($query['studentPortal_Id'] == 2) {
                                                                        echo 'Kshitij Vivan';
                                                                    } elseif ($query['studentPortal_Id'] == 4) {
                                                                        echo 'Maac CG';
                                                                    } else {
                                                                        echo 'Other';
                                                                    }
                                                                    ?>
                                                                </strong>
                                                            </span>
                                                        </div>

                                                        <div class="form-group col-md-4">
                                                            <label for="form_control_1">Student Enrollment: </label>
                                                            <span><strong><?php echo $query['studentEnrollment'] ?></strong></span>
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label for="form_control_1">Course Name: </label>
                                                            <span>
                                                                <strong>
                                                                    <?php
                                                                    $rowdata = mysqli_query($dbconn, "SELECT * FROM `course` where courseId in (" . $stundentData['courseId'] . ") and istatus=1 and isDelete=0 ORDER by courseName ASC");
                                                                    $coureName = '';
                                                                    while ($resultdata = mysqli_fetch_array($rowdata)) {
                                                                        $coureName = $resultdata['courseName'] . ',' . $coureName;
                                                                    }
                                                                    echo $coureName = rtrim($coureName, ',');
                                                                    ?>
                                                                </strong>
                                                            </span>
                                                        </div>
                                                        <input name="offeredfee" id="offeredfee" value="<?php echo $stundentData['offeredfee'];    ?>"  type="hidden">
                                                        <?php
                                                            $filterRegFee = "select * from studentfee where stud_id = '" . $stud_id . "' and studentcourseId = " . $_REQUEST['refToken'] . " and feetype = '1'";
                                                            $rowRegFee = mysqli_query($dbconn, $filterRegFee);
                                                            $regFee = 0;
                                                            $i = 0;
                                                            while ($dataRegFee = mysqli_fetch_array($rowRegFee)) {
                                                                $regFee = $dataRegFee['amount'] + $regFee;
                                                                $i++;
                                                            }
                                                        ?>
                                                        <input name="registeredAmount" id="registeredAmount" value="<?php echo $regFee;    ?>"  type="hidden">
                                                        
                                                        <div id="divFeesValue">

                                                        </div>
                                                        <hr />
                                                        <div class="col-md-12">
                                                            <h4 class="bold text-center">EMI Details</h4>
                                                        </div>

                                                        <div class="form-group col-md-4">
                                                            <label for="form_control_1">Date Of Join</label>
                                                            <input name="dateOfJoining" id="dateOfJoining" value="<?php echo $stundentData['dateOfJoining']; ?>" required="" class="form-control date-picker" placeholder="Enter The Date Of Join"  type="text">
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label for="form_control_1">Emi Type*</label>
                                                            <select name="emiId" id="emiId" value="" class="form-control" required="" onchange="checkAmount();">
                                                                <option value="">Select Emi Type</option>
                                                                <?php
                                                                $rowdata = mysqli_query($dbconn, "SELECT * FROM `emitype`");
                                                                while ($resultdata = mysqli_fetch_array($rowdata)) {
                                                                    if ($stundentData['emiType'] == $resultdata['emiId']) {
                                                                        ?>
                                                                        <option value="<?php echo $resultdata['emiId'] ?>" selected=""><?php echo $resultdata['emiTypeName'] ?></option>
                                                                    <?php } else { ?>
                                                                        <option value="<?php echo $resultdata['emiId'] ?>"><?php echo $resultdata['emiTypeName'] ?></option>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                        <?php
                                                        $emiJoing = mysqli_fetch_array(mysqli_query($dbconn, "SELECT joinAmount FROM `studentemidetail` where stud_id=" . $stud_id . " and studentcourseId=" . $_REQUEST['refToken'] . " and studentemidetail.isDelete=0"));
                                                        ?>
                                                        <div class="form-group col-md-4">
                                                            <label for="form_control_1">Joining Amount</label>
                                                            <input name="joinAmount" id="joinAmount"  value="<?php echo $emiJoing['joinAmount'] ?>" class="form-control" placeholder="Enter The Join Amount"  type="text" onchange="checkAmount();">
                                                        </div>
                                                        <div class="form-group col-md-4" id="divemiStartDate">
                                                            <label for="form_control_1">Emi Start Date*</label>
                                                            <input name="emiStartDate" id="emiStartDate" value="<?php echo $stundentData['emiStartDate']; ?>" class="form-control date-picker" required="" placeholder="Enter The Emi Start Date"  type="text">
                                                        </div>
                                                        <div class="form-group col-md-4" id="divnoOfEmi"> 
                                                            <label for="form_control_1">No Of Emi*</label>
                                                            <input name="noOfEmi" id="noOfEmi" value="<?php echo $stundentData['noOfEmi']; ?>" class="form-control" required="" placeholder="Enter The No Of Emi" onblur="checkAmount();" type="text">
                                                        </div>
                                                        <div class="form-group col-md-4" id="divnoOfEmiAmount">
                                                            <label for="form_control_1">Emi Amount*</label>
                                                            <div id="emiAmountDiv"></div>
                                                            <input name="emiAmount" id="emiAmount" value="<?php echo $stundentData['emiAmount']; ?>" class="form-control" type="text" required="" onblur="checkAmount();" placeholder="Enter The Emi Amount">
                                                        </div>

                                                    </div>
                                                    <div class="form-actions noborder">
                                                        <input class="btn blue margin-top-20" type="submit" id="Btnmybtn" onclick="finalSubmit();" value="Submit" name="submit">      
                                                        <button type="button" class="btn blue margin-top-20" onClick="checkclose();">Cancel</button>
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
                color: #51c6dd;
                font-size: 15px;
                font-weight: normal !important;
                text-transform: lowercase;
            }
        </style>
        <link href="assets/bootstrap-multiselect.css" rel="stylesheet" type="text/css"/>
        <script src="assets/bootstrap-multiselect.js" type="text/javascript"></script>
        <script src="<?php echo $web_url; ?>admin/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>

        <script type="text/javascript">
                                                            $(document).ready(function () {
                                                                $('#dateOfEnrollment').datepicker({
                                                                    format: 'dd-mm-yyyy',
                                                                    autoclose: true,
                                                                    todayHighlight: true,
                                                                    defaultDate: "now"
                                                                });
                                                                $("#dateOfJoining").datepicker({
                                                                    format: 'dd-mm-yyyy',
                                                                    autoclose: true,
                                                                    todayHighlight: true,
                                                                    defaultDate: "now"
                                                                });
                                                                $("#emiStartDate").datepicker({
                                                                    format: 'dd-mm-yyyy',
                                                                    autoclose: true,
                                                                    todayHighlight: true,
                                                                    defaultDate: "now"
                                                                });
                                                                $('#dateOfRegistration').datepicker({
                                                                    format: 'dd-mm-yyyy',
                                                                    autoclose: true,
                                                                    todayHighlight: true,
                                                                    defaultDate: "now",
                                                                    endDate: "now"
                                                                });

                                                                var iEnroll = $('#iEnroll').val();
                                                                var isRegistered = $('#isRegistered').val();

                                                                if (iEnroll == 1 || iEnroll == 4) {
                                                                    $('#divStudentEnrollment').show();
                                                                    $('#divisRegister').show();
                                                                } else if (iEnroll == 2) {
                                                                    $('#divStudentEnrollment').hide();
                                                                    $('#divisRegister').hide();
                                                                    $('#studentEnrollment').attr('required', false);
                                                                } else {
                                                                    $('#divStudentEnrollment').hide();
                                                                    $('#divisRegister').hide();
                                                                    $('#studentEnrollment').attr('required', false);
                                                                }
                                                            });

                                                            $(document).change(function () {
                                                                var emiId = $('#emiId').val();
                                                                if (emiId == 1) {
                                                                    $('#divemiStartDate').hide();
                                                                    $('#emiStartDate').attr('required', false);
                                                                    $('#divnoOfEmi').hide();
                                                                    $('#noOfEmi').attr('required', false);
                                                                    $('#divnoOfEmiAmount').hide();
                                                                    $('#emiAmount').attr('required', false);
                                                                } else {
                                                                    $('#divemiStartDate').show();
                                                                    $('#emiStartDate').attr('required', true);
                                                                    $('#divnoOfEmi').show();
                                                                    $('#noOfEmi').attr('required', true);
                                                                    $('#divnoOfEmiAmount').show();
                                                                    $('#emiAmount').attr('required', true);
                                                                }
                                                            });

                                                            $(document).ready(function () {
                                                                var stud_id = $('#stud_id').val();
                                                                var studentcourseId = $('#studentcourseId').val();
                                                                var urlp = '<?php echo $web_url; ?>admin/findCourse.php?stud_id=' + stud_id + '&studentcourseId=' + studentcourseId;
                                                                $.ajax({
                                                                    type: 'POST',
                                                                    url: urlp,
                                                                    success: function (dataemi) {
                                                                        $('#divFeesValue').html(dataemi);
                                                                        var valReceived = dataemi.split("##@@##");
                                                                        $('#divFeesValue').html(valReceived[0]);
                                                                        $('#emitypeInputDiv').html(valReceived[1]);
                                                                    }
                                                                }).error(function () {
                                                                    alert('An error occured');
                                                                });
                                                            });

                                                            function checkAmount() {
                                                                var emiId = $('#emiId').val();
                                                                var noOfEmi = 1;
                                                                var joinAmount = $('#joinAmount').val();
                                                                if (emiId == 1) {
                                                                    var offeredAmount = $('#offeredfee').val();
                                                                    var noOfEmi = 1;
                                                                    var registeredAmount = $('#registeredAmount').val();
                                                                    var EmaiAmount = offeredAmount - registeredAmount;
                                                                    EmaiAmount = Math.round(EmaiAmount / noOfEmi);
                                                                    $('#joinAmount').val(EmaiAmount);
                                                                } else {
                                                                    var offeredAmount = $('#offeredfee').val();
                                                                    noOfEmi = $('#noOfEmi').val();
                                                                    var registeredAmount = $('#registeredAmount').val();
                                                                    var EmaiAmount = offeredAmount - registeredAmount - joinAmount;
                                                                    EmaiAmount = Math.round(EmaiAmount / noOfEmi);
                                                                    if (isNaN(EmaiAmount) || noOfEmi == '') {
                                                                        $('#emiAmount').val(0);
                                                                    } else {
                                                                        $('#emiAmount').val(EmaiAmount);
                                                                    }
                                                                }
                                                            }

                                                            function checkclose() {
                                                                window.close();
//                                                                window.location.href = '<?php echo $web_url; ?>admin/StudentList.php';
                                                            }

                                                            function finalSubmit() {
                                                                var msg = "Are You Sure To Update Change EMI.";
                                                                if (confirm(msg)) {
                                                                    $('#frmparameter').submit(function (e) {
                                                                        var offeredAmount = $('#offeredfee').val();
                                                                        var noOfEmi = 0;
                                                                        var emiId = $('#emiId').val();
                                                                        if (emiId == 1) {
                                                                            noOfEmi = 1;
                                                                        } else {
                                                                            noOfEmi = $('#noOfEmi').val();
                                                                        }
                                                                        var joinAmount = $('#joinAmount').val();
                                                                        var registeredAmount = $('#registeredAmount').val();
                                                                        var EmaiAmount = offeredAmount - registeredAmount - joinAmount;
                                                                        EmaiAmount = Math.round(EmaiAmount / noOfEmi);
                                                                        var maxVal = EmaiAmount * 1 + noOfEmi * 1;
                                                                        var minVal = EmaiAmount * 1 - noOfEmi * 1;
                                                                        var EmaiAmountActual = $('#emiAmount').val();
                                                                        if (maxVal >= EmaiAmountActual && minVal <= EmaiAmountActual)
                                                                        {
                                                                            e.preventDefault();
                                                                            var $form = $(this);
                                                                            $('#loading').css("display", "block");
                                                                            $.ajax({
                                                                                type: 'POST',
                                                                                url: '<?php echo $web_url; ?>admin/querydataStudent.php',
                                                                                data: $('#frmparameter').serialize(),
                                                                                success: function (response) {
                                                                                    console.log(response);
                                                                                    if (response != 0)
                                                                                    {
                                                                                        $('#loading').css("display", "none");
                                                                                        $("#Btnmybtn").attr('disabled', 'disabled');
                                                                                        alert('Enrolled Sucessfully.');
                                                                                        response = response.trim();
                                                                                        window.close();
//                                                                                        window.location.href = '<?php echo $web_url; ?>admin/StudentList.php';
                                                                                    }else{
                                                                                        $('#loading').css("display", "none");
                                                                                        $("#Btnmybtn").attr('disabled', 'disabled');
                                                                                        alert('Invalid Request');
                                                                                        response = response.trim();
                                                                                        window.close();
                                                                                    }
                                                                                }
                                                                            });
                                                                        } else
                                                                        {
                                                                            alert("amount is too large or small");
                                                                            return false;
                                                                        }
                                                                    });
                                                                    return true;
                                                                } else {
                                                                    $('#StudentCourse').show();
                                                                    $('#StudentDetail').hide();
                                                                    return false;
                                                                }
                                                            }

                                                            $(document).ready(function () {
                                                                $("#joinAmount").keydown(function (e) {
                                                                    if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110]) !== -1 ||
                                                                            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
                                                                            (e.keyCode >= 35 && e.keyCode <= 40)) {
                                                                        return;
                                                                    }
                                                                    if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                                                                        e.preventDefault();
                                                                    }
                                                                });
                                                            });

                                                            $(document).ready(function () {
                                                                $("#emiAmount").keydown(function (e) {
                                                                    if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110]) !== -1 ||
                                                                            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
                                                                            (e.keyCode >= 35 && e.keyCode <= 40)) {
                                                                        return;
                                                                    }
                                                                    if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                                                                        e.preventDefault();
                                                                    }
                                                                });
                                                            });

                                                            $(document).ready(function () {
                                                                $("#noOfEmi").keydown(function (e) {
                                                                    if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110]) !== -1 ||
                                                                            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
                                                                            (e.keyCode >= 35 && e.keyCode <= 40)) {
                                                                        return;
                                                                    }
                                                                    if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                                                                        e.preventDefault();
                                                                    }
                                                                });
                                                            });
        </script>
    </body>
</html>