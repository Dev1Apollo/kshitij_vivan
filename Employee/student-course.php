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
                            <div class="col-md-2">
                                <?php include_once './menu-admission.php'; ?>
                            </div>
                            <div class="col-md-10" id="studentcoursefrom">
                                <div class="portlet light ">
                                    <div class="portlet-title">
                                        <div class="caption grey-gallery">
                                            <i class="icon-settings grey-gallery"></i>
                                            <span class="caption-subject bold uppercase" id="listdetail">Student Course Details</span>
                                        </div>
                                    </div>
                                    <div class="portlet-body">
                                        <form  role="form"  method="POST"  action="" name="frmparameter"  id="frmparameter" enctype="multipart/form-data">
                                            <input type="hidden" value="AddStudentCourse" name="action" id="action">
                                            <?php
                                            $stud_id = $_REQUEST['token'];
                                            $query = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM studentadmission where stud_id=" . $stud_id));
                                            ?>
                                            <input type="hidden" value="<?php echo $stud_id; ?>" name="stud_id" id="stud_id">
                                            <input type="hidden" value="<?php echo $query['isRegister']; ?>" name="isRegister" id="isRegister">
                                            <input type="hidden" value="<?php echo $query['isAdmission']; ?>" name="isAdmission" id="isAdmission">
                                            <div class="form-body">
                                                <div id="StudentCourse">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <h4 class="bold text-center">Course Details</h4>
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label for="form_control_1">Portal</label>
                                                            <input type="hidden" value="<?php echo $query['studentPortal_Id']; ?>" id="iEnroll" name="iEnroll">
                                                            <input value="<?php
                                                            if ($query['studentPortal_Id'] == 4) {
                                                                echo 'Maac Satellite';
                                                            } else if ($query['studentPortal_Id'] == 2) {
                                                                echo 'Kshitij Vivan';
                                                            } else if ($query['studentPortal_Id'] == 1) {
                                                                echo 'Maac CG';
                                                            } else {
                                                                echo 'Other';
                                                            }
                                                            ?>" name="studentPortal_Id" id="studentPortal_Id"  class="form-control" required="" readonly> 
                                                        </div>
                                                        <div class="form-group col-md-4" style="display: none" id="divisRegister">
                                                            <label for="form_control_1">Do You Have Registered Number</label>
                                                            <select class="form-control" id="isRegistered" name="isRegistered">
                                                                <option value="1">Yes</option>
                                                                <option value="2">No</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-4" style="display: none" id="divStudentEnrollment">
                                                            <label for="form_control_1">Student Enrollment</label>
                                                            <input name="studentEnrollment" id="studentEnrollment" value="" class="form-control" placeholder="Enter The Student Enrollment" type="text" required="" >
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label for="form_control_1">Course Name</label>
                                                            <select name="cid[]" id="cid"  class="form-control" required="" onchange="getfee();" multiple="">
                                                                <?php
                                                                $rowdata = mysqli_query($dbconn, "SELECT * FROM `course` where istatus=1 and isDelete=0 ORDER by courseName ASC");
                                                                while ($resultdata = mysqli_fetch_array($rowdata)) {
                                                                    ?>
                                                                    <option value="<?php echo $resultdata['courseId'] ?>"><?php echo $resultdata['courseName'] ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label for="form_control_1">Fees</label>
                                                            <div class="txt_field" id="FeeDiv" >
                                                                <input name="fee" id="fee" class="form-control" placeholder="Enter The fee" disabled="" type="text" required="" readonly="readonly">
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label for="form_control_1">Offered Fee*</label>
                                                            <input name="offeredfee" id="offeredfee" value="" class="form-control" required="" placeholder="Enter The Offered Fee"  type="text" onchange="checkAmount();">
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label for="form_control_1">Registered Fees</label>
                                                            <?php
                                                            $filterRegFee = "select * from studentfee where stud_id = '" . $stud_id . "' and studentcourseId = '0' and feetype = '1'";
                                                            $rowRegFee = mysqli_query($dbconn, $filterRegFee);
                                                            $regFee = 0;
                                                            $i = 0;
                                                            while ($dataRegFee = mysqli_fetch_array($rowRegFee)) {
                                                                $regFee = $dataRegFee['amount'] + $regFee;
                                                                $i++;
                                                            }
                                                            ?>
                                                            <input name="registeredAmount" id="registeredAmount" readonly="readonly" class="form-control date-picker" value="<?php echo $regFee; ?>"  type="text">
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label for="form_control_1">Registration Date*</label>

                                                            <input name="dateOfRegistration" id="dateOfRegistration" class="form-control" placeholder="Enter The Date Of Registration" required="" type="text">

                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label for="form_control_1">Enrollment Date*</label>
                                                            <input name="dateOfEnrollment" id="dateOfEnrollment" value="" required="" class="form-control date-picker" placeholder="Enter The Date Of Enrollment"  type="text">
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label for="form_control_1">Date Of Join*</label>
                                                            <input name="dateOfJoining" id="dateOfJoining" value="" required="" class="form-control date-picker" placeholder="Enter The Date Of Join"  type="text">
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label for="form_control_1">Emi Type*</label>
                                                            <select name="emiId" id="emiId" value="" class="form-control" required="" onchange="checkAmount();">
                                                                <option value="">Select Emi Type</option>
                                                                <?php
                                                                $rowdata = mysqli_query($dbconn, "SELECT * FROM `emitype`");
                                                                while ($resultdata = mysqli_fetch_array($rowdata)) {
                                                                    ?>
                                                                    <option value="<?php echo $resultdata['emiId'] ?>"><?php echo $resultdata['emiTypeName'] ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label for="form_control_1">Joining Amount</label>
                                                            <input name="joinAmount" id="joinAmount"  value="0" class="form-control" placeholder="Enter The Join Amount"  type="text" onchange="checkAmount();">
                                                        </div>
                                                        <div class="form-group col-md-4" id="divemiStartDate">
                                                            <label for="form_control_1">Emi Start Date*</label>
                                                            <input name="emiStartDate" id="emiStartDate" value="" class="form-control date-picker" required="" placeholder="Enter The Emi Start Date"  type="text">
                                                        </div>
                                                        <div class="form-group col-md-4" id="divnoOfEmi"> 
                                                            <label for="form_control_1">No Of Emi*</label>
                                                            <input name="noOfEmi" id="noOfEmi" value="" class="form-control" required="" placeholder="Enter The No Of Emi" onblur="checkAmount();" type="text">
                                                        </div>
                                                        <div class="form-group col-md-4" id="divnoOfEmiAmount">
                                                            <label for="form_control_1">Emi Amount*</label>
                                                            <div id="emiAmountDiv"></div>
                                                            <input name="emiAmount" id="emiAmount" value="" class="form-control" type="text" required="" onblur="checkAmount();" placeholder="Enter The Emi Amount">
                                                        </div>
                                                    </div>
                                                    <div id="regErorr" style="color: red;"></div>
                                                    <div class="form-actions noborder">
                                                        <button class="btn blue margin-top-20" type="button" id="onSubmit" onclick="return getStudentDetail();">Submit</button>      
                                                        <button type="button" class="btn blue margin-top-20" onClick="checkclose();">Cancel</button>
                                                    </div>
                                                </div>         

                                                <div id="StudentDetail" style="display: none; align-items: center;">
                                                    <div class="row" style="text-align: justify;">
                                                        <div class="col-md-12">
                                                            <h4 class="bold text-center">Student Course Details</h4>
                                                        </div>
                                                        <!--                                                        <div class="form-group row">
                                                                                                                    <label class="col-md-3 col-form-label">Student Name : </label>
                                                                                                                    <div class="col-md-9">
                                                        <?php // echo $query['title'] . ' ' . $query['firstName'] . ' ' . $query['middleName'] . ' ' . $query['surName']; ?>
                                                                                                                    </div>
                                                                                                                </div>-->
                                                        <!--                                                        <div class="form-group row">
                                                                                                                    <label class="col-md-3 col-form-label">Student Email : </label>
                                                                                                                    <div class="col-md-9"><?php // echo $query['email'];    ?></div>
                                                                                                                </div>-->
                                                        <!--                                                        <div class="form-group row">
                                                                                                                    <label class="col-md-3 col-form-label">Student Mobile : </label>
                                                                                                                    <div class="col-md-9"><?php // echo $query['mobileOne'];    ?></div>
                                                                                                                </div>-->
                                                        <!--                                                        <div class="form-group row">
                                                                                                                    <label class="col-md-3 col-form-label">Student Mobile Two : </label>
                                                                                                                    <div class="col-md-9"><?php // echo $query['mobileTwo'];    ?></div>
                                                                                                                </div>-->
                                                        <!--                                                        <div class="form-group row">
                                                                                                                    <label class="col-md-3 col-form-label">Occupation : </label>
                                                                                                                    <div class="col-md-9"><?php // echo $query['occupation'];    ?></div>
                                                                                                                </div>
                                                                                                                <div class="form-group row">
                                                                                                                    <label class="col-md-3 col-form-label">Qualification : </label>
                                                                                                                    <div class="col-md-9"><?php // echo $query['qualification'];    ?></div>
                                                                                                                </div>
                                                                                                                <div class="form-group row">
                                                                                                                    <label class="col-md-3 col-form-label">Designation : </label>
                                                                                                                    <div class="col-md-9"><?php // echo $query['designation'];    ?></div>
                                                                                                                </div>-->
                                                        <!--                                                        <div class="form-group row">
                                                                                                                    <label class="col-md-3 col-form-label">Course Name : </label>
                                                                                                                    <div class="col-md-9"><span id="i_cid"></span> </div>
                                                                                                                </div>
                                                                                                                                                                        <div class="form-group row">
                                                                                                                                                                            <label class="col-md-3 col-form-label">Course Fees : </label>
                                                                                                                                                                                <div class="col-md-9"><span id="i_fee"></span> </div>
                                                                                                                                                                        </div>
                                                                                                                <div class="form-group row">
                                                                                                                    <label class="col-md-3 col-form-label">Offered Fees : </label>
                                                                                                                    <div class="col-md-9"><span id="i_offeredfee"></span> </div>
                                                                                                                </div>-->
                                                        <!--                                                        <div class="form-group row">
                                                                                                                    <label class="col-md-3 col-form-label">Enrollment Date : </label>
                                                                                                                    <div class="col-md-9"><span id="i_dateOfEnrollment"></span> </div>
                                                                                                                </div>-->
                                                        <!--                                                        <div class="form-group row">
                                                                                                                    <label class="col-md-3 col-form-label">Date Of Joining : </label>
                                                                                                                    <div class="col-md-9"><span id="i_dateOfJoining"></span> </div>
                                                                                                                </div>
                                                                                                                <div class="form-group row">
                                                                                                                    <label class="col-md-3 col-form-label">Joining Amount : </label>
                                                                                                                    <div class="col-md-9"><span id="i_joinAmount"></span> </div>
                                                                                                                </div>
                                                                                                                <div class="form-group row">
                                                                                                                    <label class="col-md-3 col-form-label">Emi Type :  </label>
                                                                                                                    <div class="col-md-9"><span id="i_emiId"></span> </div>
                                                                                                                </div>-->
                                                        <!--                                                        <div class="form-group row">
                                                                                                                    <label class="col-md-3 col-form-label">Emi Start Date : </label>
                                                                                                                    <div class="col-md-9"><span id="i_emiStartDate"></span> </div>
                                                                                                                </div>
                                                                                                                <div class="form-group row">
                                                                                                                    <label class="col-md-3 col-form-label">No Of Emi : </label>
                                                                                                                    <div class="col-md-9"><span id="i_noOfEmi"></span> </div>
                                                                                                                </div>
                                                                                                                <div class="form-group row">
                                                                                                                    <label class="col-md-3 col-form-label">Emi Amount : </label>
                                                                                                                    <div class="col-md-9"><span id="i_emiAmount"></span> </div>
                                                                                                                </div>
                                                                                                                <div class="form-group row">
                                                                                                                    <label class="col-md-3 col-form-label">Confirm Submission</label>
                                                                                                                    <div class="col-md-9">
                                                                                                                        <select class="form-control" id="confirm">
                                                                                                                            <option value="1">YES</option>
                                                                                                                            <option value="2">NO</option>
                                                                                                                        </select>
                                                                                                                    </div>
                                                                                                                </div>-->

                                                        <table class="table table-bordered">
                                                            <tbody>
                                                                <tr>
                                                                    <td>Student Name : 
                                                                        <?php echo $query['title'] . ' ' . $query['firstName'] . ' ' . $query['middleName'] . ' ' . $query['surName']; ?>
                                                                    </td>
                                                                    <td>Student Email : <?php echo $query['email']; ?> </td>
                                                                    <td>Student Mobile :<?php echo $query['mobileOne']; ?> </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Student Mobile Two : <?php echo $query['mobileTwo']; ?></td>
                                                                    <td>Occupation : <?php echo $query['occupation']; ?></td>
                                                                    <td>Qualification : <?php echo $query['qualification']; ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Course Name : <span id="i_cid"></span> </td>
                                                                    <td>Offered Fees : <span id="i_offeredfee"></span> </td>
                                                                    <td>Enrollment Date : <span id="i_dateOfEnrollment"></span></td>
                                                                </tr>
                                                                <tr>
                                                                    <td> Date Of Joining  : <span id="i_dateOfJoining"></span> </td>
                                                                    <td> Joining Amount : <span id="i_joinAmount"></span></td>
                                                                    <td> Emi Type : <span id="i_emiId"></span></td>
                                                                </tr>

                                                                <tr>
                                                                    <td>Emi Start Date : <span id="i_emiStartDate"></span></td>
                                                                    <td>No Of Emi : <span id="i_noOfEmi"></span></td>
                                                                    <td>Emi Amount : <span id="i_emiAmount"></span></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Confirm Submission : <select class="form-control" id="confirm">
                                                                            <option value="1">YES</option>
                                                                            <option value="2">NO</option>
                                                                        </select>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
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
                                                                $("#lastPaymentDate").datepicker({
                                                                    format: 'dd-mm-yyyy',
                                                                    autoclose: true,
                                                                    todayHighlight: true,
                                                                    defaultDate: "now"
                                                                });
                                                                $("#payDate").datepicker({
                                                                    format: 'dd-mm-yyyy',
                                                                    autoclose: true,
                                                                    todayHighlight: true,
                                                                    defaultDate: "now"
                                                                });
                                                                $('#dateOfEnrollment').datepicker({
                                                                    format: 'dd-mm-yyyy',
                                                                    autoclose: true,
                                                                    todayHighlight: true,
                                                                    defaultDate: "now"
                                                                });
                                                                $('#cid').multiselect({
                                                                    nonSelectedText: 'Select Course Name',
                                                                    includeSelectAllOption: true,
                                                                    buttonWidth: '100%',
                                                                    maxHeight: 250
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
//                                                                    if(isRegistered == '1'){
//                                                                        $('#divisRegister').show();
//                                                                        $('#isRegistered').attr('required', true);
//                                                                    }else{
//                                                                        $('#divisRegister').hide();
//                                                                        $('#isRegistered').attr('required', false);
//                                                                    }
//                                                                    $('#studentEnrollment').attr('required', true);
                                                                } else if (iEnroll == 2) {
                                                                    $('#divStudentEnrollment').hide();
                                                                    $('#divisRegister').hide();
                                                                    $('#studentEnrollment').attr('required', false);
                                                                } else {
                                                                    $('#divStudentEnrollment').hide();
                                                                    $('#divisRegister').hide();
                                                                    $('#studentEnrollment').attr('required', false);
                                                                }
                                                                var isAdmission = $('#isAdmission').val();
                                                                var isRegister = $('#isRegister').val();
                                                                var registeredAmount = $('#registeredAmount').val();
                                                                if (isAdmission == 0 && isRegister == 1) {

                                                                    if (registeredAmount < 4999) {
                                                                        $("#onSubmit").attr('disabled', true);
                                                                        $("#regErorr").html("To Enroll student minimum RS.: 5000 Registration Fees required.")
                                                                    } else {
                                                                        $("#onSubmit").attr('disabled', false);
                                                                    }
                                                                } else {
                                                                    $("#onSubmit").attr('disabled', false);
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

                                                            $('#isRegistered').change(function () {
                                                                var isRegistered = $(this).val();
                                                                if (isRegistered == '1') {
                                                                    $('#divStudentEnrollment').show();
                                                                    var studentEnrollment = $('#studentEnrollment').val();
                                                                    if (studentEnrollment == '') {
                                                                        $('#studentEnrollment').attr('required', true);
                                                                        $('#studentEnrollment').focus();
                                                                        alert("Please Add Student Enrollment");
                                                                    }
                                                                } else {
                                                                    $('#divStudentEnrollment').hide();
                                                                    $('#studentEnrollment').attr('required', false);
                                                                }
                                                            });

                                                            function getfee() {
                                                                var q = $('#cid').val();
                                                                var urlp = '<?php echo $web_url; ?>Employee/findfee.php?cid=' + q;
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

                                                            function getStudentDetail() {
                                                                var iEnroll = $('#iEnroll').val();
                                                                var offeredfee = $('#offeredfee').val();
                                                                var dateOfJoining = $('#dateOfJoining').val();
                                                                var emiId = $('#emiId').val();
                                                                var emiStartDate = $('#emiStartDate').val();
                                                                var noOfEmi = 0;
                                                                var emiId = $('#emiId').val();
                                                                var emiAmount = $('#emiAmount').val();
                                                                var dateOfEnrollment = $('#dateOfEnrollment').val();
                                                                var tValiate = true;
//                                                                if (iEnroll == 1)
//                                                                {
//                                                                    var studentEnrollment = $('#studentEnrollment').val();
//                                                                    if (studentEnrollment == '') {
//                                                                        $('#studentEnrollment').attr('required', true);
//                                                                        $('#studentEnrollment').focus();
//                                                                        alert("Please Add Student Enrollment");
//                                                                        tValiate = false;
//                                                                    }
//                                                                }
                                                                if (offeredfee == '') {
                                                                    $('#offeredfee').focus();
                                                                    alert("Please Add Offered Fee");
                                                                    tValiate = false;
                                                                }
                                                                if (dateOfJoining == '') {
                                                                    $('#dateOfJoining').focus();
                                                                    alert("Please Add Date Of Joining");
                                                                    tValiate = false;
                                                                }
                                                                if (emiId == '') {
                                                                    $('#emiId').focus();
                                                                    alert("Please Add Emi Type");
                                                                    tValiate = false;
                                                                }

                                                                if (emiId == 1 && emiId != '') {
                                                                    noOfEmi = 1;
                                                                } else {
                                                                    noOfEmi = $('#noOfEmi').val();
                                                                    if (noOfEmi == '') {
                                                                        $('#noOfEmi').focus();
                                                                        alert("Please Add No Of Emi");
                                                                        tValiate = false;
                                                                    }
                                                                }

                                                                if (tValiate != false)
                                                                {
                                                                    $('#StudentCourse').hide();
                                                                    $('#StudentDetail').show();
                                                                    var fee = $('#fee').val();
                                                                    var offeredfee = $('#offeredfee').val();
                                                                    var dateOfJoining = $('#dateOfJoining').val();
                                                                    var joinAmount = $('#joinAmount').val();
                                                                    var EmiType = $('#emiId option:selected').text();
                                                                    var Cname = $("#cid option:selected").map(function () {
                                                                        return this.text
                                                                    }).get().join(', ');
                                                                    $('#i_cid').html(Cname);
//                                                                    $('#i_fee').html(fee);
                                                                    $('#i_offeredfee').html(offeredfee);
                                                                    $('#i_dateOfJoining').html(dateOfJoining);
                                                                    $('#i_emiId').html(EmiType);
                                                                    $('#i_joinAmount').html(joinAmount);
                                                                    $('#i_emiStartDate').html(emiStartDate);
                                                                    $('#i_noOfEmi').html(noOfEmi);
                                                                    $('#i_emiAmount').html(emiAmount);
                                                                    $('#i_dateOfEnrollment').html(dateOfEnrollment);
                                                                }
                                                                return tValiate;
                                                            }

                                                            function checkclose() {
                                                                $('#StudentCourse').show();
                                                                $('#StudentDetail').hide();
                                                                return false;
                                                            }

                                                            function finalSubmit() {
                                                                var confirm = $('#confirm').val();
                                                                if (confirm == 1) {
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
                                                                                url: '<?php echo $web_url; ?>Employee/querydata.php',
                                                                                data: $('#frmparameter').serialize(),
                                                                                success: function (response) {
                                                                                    console.log(response);
                                                                                    if (response != 0)
                                                                                    {
                                                                                        $('#loading').css("display", "none");
                                                                                        $("#Btnmybtn").attr('disabled', 'disabled');
                                                                                        alert('Enrolled Sucessfully.');
                                                                                        response = response.trim();
                                                                                        window.location.href = '<?php echo $web_url; ?>Employee/StudentCourseDetails.php?token=' + response;
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
                                                                $("#offeredfee").keydown(function (e) {
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