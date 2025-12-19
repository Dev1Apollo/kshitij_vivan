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
    <title><?php echo $ProjectName; ?> | Student Enrollment Fees </title>
    <?php include_once './include.php'; ?>
    <link href="demo/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet" type="text/css" />
    <!--<link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">-->
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />
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
                                        <span class="caption-subject bold uppercase" id="listdetail">Student Enrollment Fees</span>
                                    </div>
                                    <a class="btn blue pull-right" href="javascript: history.go(-1)">Go Back</a>
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
                                    <div class="tab-content">
                                        <form role="form" method="POST" action="" name="frmparameter" id="frmparameter" enctype="multipart/form-data">
                                            <input type="hidden" value="AddStudentfees" name="action" id="action">
                                            <input type="hidden" value="<?php echo $_REQUEST['token']; ?>" name="stud_id" id="stud_id">
                                            <input type="hidden" value="<?php echo $query['studentPortal_Id']; ?>" name="studentPortal_Id" id="studentPortal_Id">
                                            <input type="hidden" value="<?php echo $_REQUEST['studentcourseId']; ?>" name="studentcourseId" id="studentcourseId">
                                            <?php
                                            $course = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `studentcourse` where stud_id=" . $_REQUEST['token'] . " and studentcourse.istatus=1"));
                                            ?>
                                            <input type="hidden" value="<?php echo $course['courseId']; ?>" name="cid[]" id="cid">
                                            <div class="form-body">
                                                <div class="row" id="divFeesValue">
                                                </div>
                                                <hr />
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <h4 class="bold text-center">Payment Detail</h4>
                                                    </div>
                                                    <?php
                                                    $receiptNo = '';
                                                    $studentfee = mysqli_fetch_array(mysqli_query($dbconn, "select max(recepitCount) as receiptNo from studentfee"));
                                                    if (isset($studentfee['receiptNo'])) {
                                                        $receiptNo = $studentfee['receiptNo'] + 1;
                                                    } else {
                                                        $receiptNo = 1;
                                                    }
                                                    $rcNo = "RC" . $receiptNo;
                                                    if ($query['studentPortal_Id'] == 2) {
                                                    ?>
                                                        <input type="hidden" name="recepitCount" id="recepitCount" value="<?php echo $studentfee['receiptNo']; ?>">
                                                        <div class="form-group row col-md-offset-1" style="display: none;">
                                                            <label for="form_control_1" class="col-md-2">Receipt No.*</label>
                                                            <div class="col-md-6">
                                                                <div class="txt_field">
                                                                    <input name="receiptNo" id="receiptNo" value="<?php echo $rcNo; ?>" class="form-control" placeholder="Enter The Receipt Number" type="text" required="">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php
                                                    }
                                                    //else { 
                                                    ?>
                                                    <!--                                                            <div class="form-group row col-md-offset-1" style="display: none;">
                                                                                                                        <label for="form_control_1" class="col-md-2">Receipt No.*</label>
                                                                                                                        <div class="col-md-6">
                                                                                                                            <div class="txt_field">
                                                                                                                                <input name="receiptNo" id="receiptNo" value=""  class="form-control"  placeholder="Enter The Receipt Number" type="text" required="">
                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                                    </div>-->
                                                    <?php //}   
                                                    ?>
                                                    <div class="form-group row col-md-offset-1">
                                                        <label for="form_control_1" class="col-md-2">Pay Date*</label>
                                                        <div class="col-md-6">
                                                            <div class="txt_field">
                                                                <input name="payDate" id="payDate" class="form-control date-picker" placeholder="Enter The Pay Date" type="text" required="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row col-md-offset-1">
                                                        <label for="form_control_1" class="col-md-2">Amount*</label>
                                                        <div class="col-md-6">
                                                            <div class="txt_field">
                                                                <input name="amount" id="amount" class="form-control" placeholder="Enter The Amount" type="text" required="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--                                                        <div class="form-group row col-md-offset-1">
                                                                                                                    <label for="form_control_1" class="col-md-2">Fee Type*</label>
                                                                                                                    <div class="col-md-6">
                                                                                                                        <div class="txt_field">
                                                                                                                            <select name="feeType" id=feeType" class="form-control" required="">
                                                                                                                                <option value="">Select Fee Type</option>
                                                                                                                                <option value="2">Joining Amount</option>
                                                                                                                                <option value="3">Emi Amount</option>
                                                                                                                            </select>
                                                                                                                        </div>
                                                                                                                    </div>
                                                                                                                </div>-->
                                                    <div class="form-group row col-md-offset-1">
                                                        <label for="form_control_1" class="col-md-2">Payment Mode*</label>
                                                        <div class="col-md-6">
                                                            <select name="paymentMode" id="paymentMode" class="form-control" required="" onchange="addpaymentmode();">
                                                                <option value="">Select Payment Mode</option>
                                                                <?php
                                                                $filterMode = mysqli_query($dbconn, "Select * from paymentmode where isDelete='0' and iStatus='1'");
                                                                while ($rowMode = mysqli_fetch_array($filterMode)) {
                                                                    echo '<option value=' . $rowMode['paymentId'] . '>' . $rowMode['paymentName'] . '</option>';
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row col-md-offset-1" style="display:none;" id="divbankName">
                                                        <label for="form_control_1" class="col-md-2">Bank Name</label>
                                                        <div class="col-md-6">
                                                            <div class="txt_field">
                                                                <select name="bank_name" id="bank_name" class="selectpicker form-control show-tick" data-show-subtext="true" data-live-search="true">
                                                                    <option value="">Select Bank Name</option>
                                                                    <?php
                                                                    $fiterPayFor = mysqli_query($dbconn, "select * from bankmaster where istatus='1' and isDelete='0'");
                                                                    while ($rowFee = mysqli_fetch_array($fiterPayFor)) {
                                                                        echo "<option value=" . $rowFee['bankMasterId'] . " >" . $rowFee['bankName'] . "</option>";
                                                                    }
                                                                    ?>
                                                                </select>
                                                                <!--                                                                    <input name="bank_name" id="bank_name" class="form-control" placeholder="Enter The Bank Name" type="text" >-->
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row col-md-offset-1" style="display:none;" id="divchequeNo">
                                                        <label for="form_control_1" class="col-md-2">Cheque No</label>
                                                        <div class="col-md-6">
                                                            <input name="cheqNumber" id="cheqNumber" class="form-control" placeholder="Enter The Cheque Number" type="text">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row col-md-offset-1" style="display:none;" id="divBankDopsit">
                                                        <label for="form_control_1" class="col-md-2">Deposited Bank</label>
                                                        <div class="col-md-6">
                                                            <select name="bankDeposit" id="bankDeposit" class="form-control">
                                                                <option value="">Select Bank</option>
                                                                <?php
                                                                $filterMode = mysqli_query($dbconn, "Select * from bank where isDelete='0' and iStatus='1'");
                                                                while ($rowMode = mysqli_fetch_array($filterMode)) {
                                                                    echo '<option value=' . $rowMode['bankId'] . '>' . $rowMode['bankName'] . '</option>';
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row col-md-offset-1">
                                                        <label for="form_control_1" class="col-md-2">Comment</label>
                                                        <div class="col-md-6">
                                                            <input name="comments" id="comments" class="form-control" placeholder="Enter The Comments" type="text">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-actions noborder col-md-offset-3">
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
            </div>
        </div>
    </div>

    <?php include_once './footer.php'; ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>-->
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
    <script src="<?php echo $web_url; ?>Supervisor/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>
    <!--        <style>
            .multiselect
            {
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
        <link href="demo/assets/bootstrap-multiselect.css" rel="stylesheet" type="text/css"/>
        <script src="demo/assets/bootstrap-multiselect.js" type="text/javascript"></script>-->
    <script>
        function checkclose() {
            window.location.href = 'Enrollment.php';
        }

        $(document).ready(function() {
            $('#bank_name').selectpicker();
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
                //                                                                todayHighlight: true,
                defaultDate: "now",
                todayHighlight: true,
                endDate: '+0d',
                startDate: '0d'
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
            if (paymentMode != "2") {
                $('#divbankName').hide();
                $('#divchequeNo').hide();
            } else {
                $('#divbankName').show();
                $('#divchequeNo').show();
            }
            if (paymentMode != "5") {
                $('#divBankDopsit').hide();
                $('#bankDeposit').attr('required', false);
            } else {
                $('#divBankDopsit').show();
                $('#bankDeposit').attr('required', true);
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

        //                                                        function getcourse()
        $(document).ready(function() {
            //   var q = $('#cid').val();
            var stud_id = $('#stud_id').val();
            var studentcourseId = $('#studentcourseId').val();
            var urlp = '<?php echo $web_url; ?>Supervisor/findCourse.php?stud_id=' + stud_id + '&studentcourseId=' + studentcourseId;
            $('#loading').css("display", "block");
            $.ajax({
                type: 'POST',
                url: urlp,
                success: function(dataemi) {
                    console.log(dataemi);
                    $('#loading').css("display", "none");
                    $('#divFeesValue').html(dataemi);
                    var valReceived = dataemi.split("##@@##");

                    $('#divFeesValue').html(valReceived[0]);
                    $('#emitypeInputDiv').html(valReceived[1]);
                }
            }).error(function() {
                alert('An error occured');
            });
        });

        $('#frmparameter').submit(function(e) {
            var studentPortal_Id = $('#studentPortal_Id').val();
            if (studentPortal_Id != 1 || studentPortal_Id != 4) {
                var msg = confirm("Print Receipt!");
            }
            e.preventDefault();
            var $form = $(this);
            $('#loading').css("display", "block");
            $.ajax({
                type: 'POST',
                url: '<?php echo $web_url; ?>Supervisor/querydata.php',
                data: $('#frmparameter').serialize(),
                success: function(response) {
                    console.log(response);
                    if (response != 0) {
                        $('#loading').css("display", "none");
                        $("#Btnmybtn").attr('disabled', 'disabled');
                        alert('Added Sucessfully.');
                        response = response.trim();
                        if (msg == true) {
                            window.open('<?php echo $web_url; ?>Supervisor/StudentFeePDF.php?token=' + response, '_blank');
                        }
                        window.location.href = '<?php echo $web_url; ?>Supervisor/Enrollment.php';
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
    </script>
</body>

</html>