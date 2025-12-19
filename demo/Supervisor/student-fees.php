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
                            <?php include_once './menu-admission.php'; ?>
                        </div>
                        <div class="col-md-10">
                            <div class="portlet light ">
                                <div class="portlet-title">
                                    <div class="caption grey-gallery">
                                        <i class="icon-settings grey-gallery"></i>
                                        <span class="caption-subject bold uppercase" id="listdetail">Manage Student</span>
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <div class="row" style="margin-bottom: 20px">
                                        <?php
                                        $stud_id = $_REQUEST['token'];
                                        $query = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `studentadmission` where stud_id=" . $stud_id));
                                        ?>
                                        <div class="col-md-4">
                                            <h4>Student Name :<?php echo $query['title'] . ' ' . $query['firstName'] . ' ' . $query['middleName'] . ' ' . $query['surName']; ?></h4>
                                        </div>
                                        <div class="col-md-4">
                                            <h4>Mobile No : <?php echo $query['mobileOne']; ?></h4>
                                        </div>
                                        <div class="col-md-4">
                                            <h4>Email ID : <?php echo $query['email']; ?> </h4>
                                        </div>
                                    </div>
                                    <div class="portlet-body">
                                        <div class="tabbable-custom nav-justified">
                                            <ul class="nav nav-tabs nav-justified">
                                                <li>
                                                    <a href="student-course.php?token=<?php echo $_REQUEST['token']; ?>"> Student Course </a>
                                                </li>
                                                <li>
                                                    <a href="student-course-details.php?token=<?php echo $_REQUEST['token']; ?>"> Student Course Details </a>
                                                </li>
                                                <li class="active">
                                                    <a href="student-fees.php?token=<?php echo $_REQUEST['token']; ?>" id="#tab-1"> Student Fees </a>
                                                </li>
                                                <li>
                                                    <a href="student-emi.php?token=<?php echo $_REQUEST['token']; ?>"> Student EMI </a>
                                                </li>
                                            </ul>
                                            <div class="tab-content">
                                                <div class="tab-pane active">
                                                    <div class="tab-content">
                                                        <div class="tab-pane active">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="portlet light ">
                                                                        <div class="portlet-title">
                                                                            <div class="caption grey-gallery">
                                                                                <i class="icon-settings grey-gallery"></i>
                                                                                <span class="caption-subject bold uppercase" id="listdetail">List of Student Fees Detail</span>
                                                                            </div>
                                                                            <a class="btn blue pull-right " data-toggle="modal" href="#large"> Add Student Fees </a>
                                                                            <div class="modal fade bs-modal-lg" id="large" tabindex="-1" role="dialog" aria-hidden="true">
                                                                                <div class="modal-dialog modal-lg">
                                                                                    <div class="modal-content">
                                                                                        <div class="modal-header">
                                                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                                                            <h4 class="modal-title">Add Student Fees</h4>
                                                                                        </div>
                                                                                        <div class="modal-body">
                                                                                            <div class="modal-body">
                                                                                                <div class="portlet-body form">
                                                                                                    <form role="form" method="POST" action="" name="frmparameter" id="frmparameter" enctype="multipart/form-data">
                                                                                                        <input type="hidden" value="AddStudentfees" name="action" id="action">
                                                                                                        <input type="hidden" value="<?php echo $stud_id; ?>" name="stud_id" id="stud_id">
                                                                                                        <div class="form-body">
                                                                                                            <div class="row">
                                                                                                                <div class="form-group col-md-4">
                                                                                                                    <label for="form_control_1">Course</label>
                                                                                                                    <div class="txt_field">
                                                                                                                        <select name="cid" id="cid" class="form-control" required="" onchange="getcourse();">
                                                                                                                            <option value="">Select Course Name</option>
                                                                                                                            <?php
                                                                                                                            $fetchcourse = mysqli_query($dbconn, "Select *  from studentcourse where stud_id =" . $stud_id . " and studentcourse.istatus=1");
                                                                                                                            while ($data = mysqli_fetch_array($fetchcourse)) {
                                                                                                                                $rowdata = mysqli_query($dbconn, "SELECT * FROM `course` where istatus=1 and courseId='" . $data['courseId'] . "' and isDelete=0 ORDER by courseName ASC");
                                                                                                                                while ($resultdata = mysqli_fetch_array($rowdata)) {
                                                                                                                            ?>
                                                                                                                                    <option value="<?php echo $resultdata['courseId'] ?>"><?php echo $resultdata['courseName'] ?></option>
                                                                                                                            <?php
                                                                                                                                }
                                                                                                                            }
                                                                                                                            ?>
                                                                                                                        </select>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                            <div class="row" id="divFeesValue">
                                                                                                            </div>
                                                                                                            <hr />
                                                                                                            <div class="row">
                                                                                                                <div class="col-md-12">
                                                                                                                    <h4 class="bold text-center">Payment Detail</h4>
                                                                                                                </div>
                                                                                                                <div class="form-group col-md-4">
                                                                                                                    <label for="form_control_1">Receipt No.*</label>
                                                                                                                    <div class="txt_field">
                                                                                                                        <input name="receiptNo" id="receiptNo" class="form-control" placeholder="Enter The Receipt Number" type="text" required="">
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                                <div class="form-group col-md-4">
                                                                                                                    <label for="form_control_1">Pay Date*</label>
                                                                                                                    <div class="txt_field">
                                                                                                                        <input name="payDate" id="payDate" class="form-control date-picker" placeholder="Enter The Pay Date" type="text" required="">
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                                <div class="form-group col-md-4">
                                                                                                                    <label for="form_control_1">Amount*</label>
                                                                                                                    <div class="txt_field">
                                                                                                                        <input name="amount" id="amount" class="form-control" placeholder="Enter The Amount" type="text" required="">
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                                <div class="form-group col-md-4">
                                                                                                                    <label for="form_control_1">Payment Mode*</label>
                                                                                                                    <select name="paymentMode" id="paymentMode" class="form-control" required="" onchange="addpaymentmode();">
                                                                                                                        <option value="">Select Payment Mode</option>
                                                                                                                        <option value="Cash">Cash</option>
                                                                                                                        <option value="Cheque">Cheque</option>
                                                                                                                    </select>
                                                                                                                </div>
                                                                                                                <div class="form-group col-md-4" style="display:none;" id="divbankName">
                                                                                                                    <label for="form_control_1">Bank Name</label>
                                                                                                                    <div class="txt_field">
                                                                                                                        <input name="bank_name" id="bank_name" class="form-control" placeholder="Enter The Bank Name" type="text">
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                                <div class="form-group col-md-4" style="display:none;" id="divchequeNo">
                                                                                                                    <label for="form_control_1">Cheque No</label>
                                                                                                                    <input name="cheqNumber" id="cheqNumber" class="form-control" placeholder="Enter The Cheque Number" type="text">
                                                                                                                </div>
                                                                                                                <div class="form-group col-md-4">
                                                                                                                    <label for="form_control_1">Comment</label>
                                                                                                                    <input name="comments" id="comments" class="form-control" placeholder="Enter The Comments" type="text">
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <div class="form-actions noborder">
                                                                                                            <input class="btn blue margin-top-20" type="submit" id="Btnmybtn" onclick="updatefeeDetail();" value="Submit" name="submit">
                                                                                                            <button type="button" class="btn blue margin-top-20" onClick="checkclose();">Cancel</button>
                                                                                                        </div>
                                                                                                    </form>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="portlet-body form">
                                                                                <div class="row">
                                                                                    <form role="form" method="POST" action="" name="frmSearch" id="frmSearch" enctype="multipart/form-data">
                                                                                        <div class="col-md-12">
                                                                                            <?php
                                                                                            $fetchcourseDetail = mysqli_query($dbconn, "Select *  from studentcourse where stud_id =" . $stud_id . " and studentcourse.istatus=1 and emiType not in(1)");
                                                                                            if (mysqli_num_rows($fetchcourseDetail) == 1) {
                                                                                                $rowdata = mysqli_fetch_array($fetchcourseDetail);
                                                                                                $where = '';
                                                                                                if ($rowdata['stud_id'] != NULL && isset($rowdata['stud_id']))
                                                                                                    $where .= " and  studentfee.stud_id =" . $rowdata['stud_id'];
                                                                                                if ($rowdata['courseId'] != NULL && isset($rowdata['courseId']))
                                                                                                    $where .= " and  course.courseId = " . $rowdata['courseId'];
                                                                                                $filterstr = "SELECT * FROM `studentfee` join studentcourse on studentcourse.studentcourseId=studentfee.studentcourseId join course on course.courseId=studentcourse.courseId  " . $where . " and studentcourse.istatus=1 order by studentfeeid desc";
                                                                                                $resultfilter = mysqli_query($dbconn, $filterstr);
                                                                                                $i = 0;
                                                                                                $serial = 0;
                                                                                            ?>
                                                                                                <div class="table-responsive">
                                                                                                    <table class="table table-bordered table-hover center table-responsive dt-responsive" width="100%" id="tableC">
                                                                                                        <thead class="tbg">
                                                                                                            <tr>
                                                                                                                <th class="all">Sr.No</th>
                                                                                                                <th class="desktop">Fee Type</th>
                                                                                                                <th class="desktop">Pay Date</th>
                                                                                                                <th class="desktop">Payment Mode</th>
                                                                                                                <th class="none">Deposit</th>
                                                                                                                <th class="none">Bank Detail</th>
                                                                                                                <th class="desktop">Gross Amount</th>
                                                                                                                <th class="desktop">GST</th>
                                                                                                                <th class="desktop">Amount</th>
                                                                                                                <th class="desktop">Comment</th>
                                                                                                                <th class="desktop">Action</th>
                                                                                                            </tr>
                                                                                                        </thead>
                                                                                                        <tbody>
                                                                                                            <?php
                                                                                                            while ($rowfilter = mysqli_fetch_array($resultfilter)) {
                                                                                                                $serial++;
                                                                                                            ?>
                                                                                                                <tr>
                                                                                                                    <td>
                                                                                                                        <div class="form-group form-md-line-input "><?php echo $serial; ?>
                                                                                                                        </div>
                                                                                                                    </td>
                                                                                                                    <td>
                                                                                                                        <div class="form-group form-md-line-input "><?php echo $rowfilter['feetype']; ?>
                                                                                                                        </div>
                                                                                                                    </td>
                                                                                                                    <td>
                                                                                                                        <div class="form-group form-md-line-input "><?php echo $rowfilter['payDate']; ?>
                                                                                                                        </div>
                                                                                                                    </td>
                                                                                                                    <td>
                                                                                                                        <div class="form-group form-md-line-input "><?php echo $rowfilter['paymentMode']; ?>
                                                                                                                        </div>
                                                                                                                    </td>
                                                                                                                    <td>
                                                                                                                        <div class="form-group form-md-line-input "><?php echo $rowfilter['deposit']; ?>
                                                                                                                        </div>
                                                                                                                    </td>
                                                                                                                    <td>
                                                                                                                        <div class="form-group form-md-line-input ">Bank Name:<?php echo $rowfilter['bankName']; ?><br>
                                                                                                                            Cheque No:<?php echo $rowfilter['chequeNo']; ?><br>
                                                                                                                            Deposited To:<?php echo $rowfilter['toBank']; ?><br>
                                                                                                                        </div>
                                                                                                                    </td>
                                                                                                                    <td>
                                                                                                                        <div class="form-group form-md-line-input "> <?php echo $rowfilter['texFreeAmt']; ?>
                                                                                                                        </div>
                                                                                                                    </td>
                                                                                                                    <td>
                                                                                                                        <div class="form-group form-md-line-input "><?php echo $rowfilter['decGst']; ?>
                                                                                                                        </div>
                                                                                                                    </td>
                                                                                                                    <td>
                                                                                                                        <div class="form-group form-md-line-input "><?php echo $rowfilter['amount']; ?>
                                                                                                                        </div>
                                                                                                                    </td>
                                                                                                                    <td>
                                                                                                                        <div class="form-group form-md-line-input "><?php echo $rowfilter['comments']; ?>
                                                                                                                        </div>
                                                                                                                    </td>
                                                                                                                    <td>
                                                                                                                        <div class="form-group form-md-line-input">
                                                                                                                            <a class="btn blue" href="<?php echo $web_url; ?>Supervisor/EditStudentfee.php?token=<?php echo $rowfilter['studentfeeid']; ?>" title="EDIT STUDENT"><i class="fa fa-edit"></i></a>
                                                                                                                            <a class="btn blue" href="<?php echo $web_url; ?>Supervisor/StudentFeePDF.php?token=<?php echo $rowfilter['studentfeeid']; ?>" target="_blank" title="View Student Fee PDF"><i class="fa fa-eye"></i></a>
                                                                                                                            <?php
                                                                                                                            $query = "SELECT max(studentfeeid) as studentFeeId FROM `studentfee` join studentcourse on studentcourse.studentcourseId=studentfee.studentcourseId join course on course.courseId=studentcourse.courseId  " . $where . " and studentcourse.istatus=1 and feetype='Emi_Amount' order by studentfeeid desc";
                                                                                                                            $filterid = mysqli_fetch_array(mysqli_query($dbconn, $query));
                                                                                                                            if ($rowfilter['feetype'] == 'Join_Amount' || $filterid['studentFeeId'] == $rowfilter['studentfeeid'] || $i == 0) {
                                                                                                                            ?>
                                                                                                                                <a class="btn blue" onClick="javascript: return deletedata('Delete', '<?php echo $rowfilter['studentfeeid']; ?>');" title="Delete"><i class="fa fa-trash-o iconshowFirst"></i></a>
                                                                                                                            <?php } ?>
                                                                                                                        </div>
                                                                                                                    </td>
                                                                                                                </tr>
                                                                                                            <?php
                                                                                                                $i++;
                                                                                                            }
                                                                                                            ?>
                                                                                                        </tbody>
                                                                                                    </table>
                                                                                                </div>
                                                                                            <?php } else { ?>
                                                                                                <input type="hidden" value="<?php echo $stud_id; ?>" id="stud_id" name="stud_id">
                                                                                                <div class="form-group  col-md-4">
                                                                                                    <select name="courseName" id="courseName" class="form-control" required="">
                                                                                                        <option value="">Select Course Name</option>
                                                                                                        <?php
                                                                                                        while ($data = mysqli_fetch_array($fetchcourseDetail)) {
                                                                                                            $rowdata = mysqli_query($dbconn, "SELECT * FROM `course` where istatus=1 and courseId='" . $data['courseId'] . "' and isDelete=0 ORDER by courseName ASC");
                                                                                                            while ($resultdata = mysqli_fetch_array($rowdata)) {
                                                                                                        ?>
                                                                                                                <option value="<?php echo $resultdata['courseId'] ?>"><?php echo $resultdata['courseName'] ?></option>
                                                                                                        <?php
                                                                                                            }
                                                                                                        }
                                                                                                        ?>
                                                                                                    </select>
                                                                                                </div>
                                                                                                <div class="form-group  col-md-2">
                                                                                                    <a href="#" class="btn btn-block blue pull-right" onclick="PageLoadData(1);"><i class="fa fa-search"></i>&nbsp;&nbsp;Search</a>
                                                                                                </div>
                                                                                        </div>
                                                                                    <?php } ?>
                                                                                    </form>
                                                                                </div>
                                                                                <div id="PlaceUsersDataHere">
                                                                                </div>
                                                                            </div>
                                                                            <!--    <div class="portlet-body form">
                                                                                        <div class="row">
                                                                                            <form  role="form"  method="POST"   action="" name="frmSearch"  id="frmSearch" enctype="multipart/form-data">
                                                                                <?php
                                                                                $fetchcourseDetail = mysqli_query($dbconn, "Select *  from studentcourse where stud_id =" . $stud_id . " and studentcourse.istatus=1 and emiType not in(1)");
                                                                                if (mysqli_num_rows($fetchcourseDetail) == 1) {
                                                                                ?>
                                    
                                                                                                                                    <div class="col-md-12">
                                                                                                                                        <input type="hidden" value="<?php echo $stud_id; ?>" id="stud_id" name="stud_id"> 
                                                                                                                                        <div class="form-group  col-md-4">
                                                                                                                                            <select name="courseName" id="courseName"  class="form-control" required="">
                                                                                                                                                <option value="">Select Course Name</option>
                                                                                    <?php
                                                                                    while ($data = mysqli_fetch_array($fetchcourseDetail)) {
                                                                                        $rowdata = mysqli_query($dbconn, "SELECT * FROM `course` where istatus=1 and courseId='" . $data['courseId'] . "' and isDelete=0 ORDER by courseName ASC");
                                                                                        while ($resultdata = mysqli_fetch_array($rowdata)) {
                                                                                    ?>
                                                                                                                                                                                                                        <option value="<?php echo $resultdata['courseId'] ?>"><?php echo $resultdata['courseName'] ?></option>
                                                                                            <?php
                                                                                        }
                                                                                            ?>
                                                                                                                                                                                </select>
                                                                                                                                                                            </div>
                                                                                        <?php
                                                                                    }
                                                                                }
                                                                                        ?>
    
                                                                                                    <div class="form-group  col-md-2">
                                                                                                        <a href="#" class="btn btn-block blue pull-right" onclick="PageLoadData(1);"><i class="fa fa-search"></i>&nbsp;&nbsp;Search</a>
                                                                                                    </div>
                                                                                                </div>
    
                                                                                            </form>
                                                                                        </div>
                                                                                        <div id="PlaceUsersDataHere">
    
                                                                                        </div>
                                                                                    </div> -->
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include_once './footer.php'; ?>
    <script src="<?php echo $web_url; ?>Supervisor/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>
    <style>
        .multiselect {
            display: block;
            height: 35px;
            padding: 6px;

            text-align: left !important;
            line-height: 1.42857;
            color: #DFDFDF;
            background-color: #fff;
            background-image: none;
            border: 1px solid #51c6dd !important;
            border-radius: 4px;
            color: #666;
            font-size: 15px;
            font-weight: normal !important;
            text-transform: lowercase;

        }
    </style>
    <link href="demo/assets/bootstrap-multiselect.css" rel="stylesheet" type="text/css" />
    <script src="demo/assets/bootstrap-multiselect.js" type="text/javascript"></script>
    <script>
        function checkclose() {
            window.location.href = '';
        }


        $(document).ready(function() {
            $('#emiType').multiselect({
                nonSelectedText: 'Select Emi Date',
                includeSelectAllOption: true,
                buttonWidth: '100%'
            });
        });
        $(document).ready(function() {
            $("#depositDate").datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                todayHighlight: true,
                defaultDate: "now"

            });

        });

        $(document).ready(function() {

            $("#payDate").datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                todayHighlight: true,
                defaultDate: "now"

            });

        });

        $(document).ready(function() {
            $("#amount").keydown(function(e) {
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

        function selectType() {
            var feetype = $('#feetype').val();

            if (feetype == "Join_Amount") {
                $('#emiType').attr('required', false);
            }
            if (feetype == "Emi_Amount") {
                $('#emitypeDiv').show();
            } else {
                $('#emitypeDiv').hide();
            }
        }

        function addpaymentmode() {
            var paymentMode = $('#paymentMode').val();

            if (paymentMode == "Cash") {
                $('#divbankName').hide();
                $('#divchequeNo').hide();
            } else {
                $('#divbankName').show();
                $('#divchequeNo').show();
            }
        }

        function adddeposit() {
            var deposit = $('#deposit').val();

            if (deposit == "No") {
                $('#divtoBank').hide();
                $('#divdepositAmount').hide();
                $('#divdepositDate').hide();
            } else {
                $('#divtoBank').show();
                $('#divdepositAmount').show();
                $('#divdepositDate').show();
            }
        }

        function getcourse() {
            var q = $('#cid').val();
            var stud_id = $('#stud_id').val();
            $('#loading').css("display", "block");
            var urlp = '<?php echo $web_url; ?>Supervisor/findCourse.php?cid=' + q + "&stud_id=" + stud_id;
            $.ajax({
                type: 'POST',
                url: urlp,
                success: function(dataemi) {
                    $('#loading').css("display", "none");
                    $('#divFeesValue').html(dataemi);
                    var valReceived = dataemi.split("##@@##");

                    $('#divFeesValue').html(valReceived[0]);
                    $('#emitypeInputDiv').html(valReceived[1]);

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

                    if (response != 0) {
                        $('#loading').css("display", "none");
                        $("#Btnmybtn").attr('disabled', 'disabled');
                        alert('Added Sucessfully.');
                        response = response.trim();
                        window.location.href = '<?php echo $web_url; ?>Supervisor/student-fees.php?token=' + response;
                    }
                }

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
                    url: "<?php echo $web_url; ?>Supervisor/AjaxStudentFee.php",
                    data: {
                        action: task,
                        ID: id
                    },
                    success: function(msg) {
                        $('#loading').css("display", "none");
                        window.location.href = '';


                    }
                });
            }
            return false;
        }

        function PageLoadData(Page) {
            var stud_id = $('#stud_id').val();
            var courseName = $('#courseName').val();

            $('#loading').css("display", "block");
            $.ajax({
                type: "POST",
                url: "<?php echo $web_url; ?>Supervisor/AjaxStudentFee.php",
                data: {
                    action: 'ListUser',
                    Page: Page,
                    stud_id: stud_id,
                    courseName: courseName
                },
                success: function(msg) {

                    $("#PlaceUsersDataHere").html(msg);
                    $('#loading').css("display", "none");
                }
            });
        }
        $(document).ready(function() {



            var stud_id = $('#stud_id').val();
            var courseName = $('#courseName').val();

            $('#loading').css("display", "block");
            $.ajax({
                type: "POST",
                url: "<?php echo $web_url; ?>Supervisor/AjaxStudentFee.php",
                data: {
                    action: 'ListUser',
                    stud_id: stud_id,
                    courseName: courseName
                },
                success: function(msg) {

                    $("#PlaceUsersDataHere").html(msg);
                    $('#loading').css("display", "none");
                }
            });


        });
        //                                                                                                        $(document).ready(function (Page) { 
        //                                                                                                        
        //                                                                                                            var stud_id = $('#stud_id').val();
        //                                                                                                            var courseName = $('#courseName').val();
        //                                                                                                            
        //                                                                                                            $('#loading').css("display", "block");
        //                                                                                                            $.ajax({
        //                                                                                                                type: "POST",
        //                                                                                                                url: "<?php echo $web_url; ?>Supervisor/AjaxStudentFee.php",
        //                                                                                                                data: {action: 'ListUser', Page: Page, stud_id: stud_id, courseName: courseName},
        //                                                                                                                success: function (msg) {
        //                                                                                                                   alert(msg);
        //                                                                                                                    $("#PlaceUsersDataHere").html(msg);
        //                                                                                                                    $('#loading').css("display", "none");
        //                                                                                                                }
        //                                                                                                            });
        //                                                                                                            
        //                                                                                                        });

        // end of filter
        // PageLoadData(1);
    </script>
    </div>
</body>

</html>