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
    <title><?php echo $ProjectName; ?> | Registration Fee </title>
    <?php include_once './include.php'; ?>
    <link href="demo/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet" type="text/css" />
</head>

<body class="page-container-bg-solid page-boxed">
    <!--<link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">-->
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />
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
                                        <span class="caption-subject bold uppercase" id="listdetail">Registration Fee</span>
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <div class="row" style="margin-bottom: 20px">
                                        <?php
                                        $stud_id = $_REQUEST['token'];
                                        //                                            echo "SELECT * FROM `studentadmission` where stud_id=" . $stud_id;
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
                                        <div class="tab-pane active">
                                            <div class="tab-content">
                                                <div class="tab-pane active">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="portlet light ">
                                                                <div class="portlet-title">
                                                                    <div class="caption grey-gallery">
                                                                        <i class="icon-settings grey-gallery"></i>
                                                                        <span class="caption-subject bold uppercase" id="listdetail">Student Registration Fees</span>
                                                                    </div>
                                                                    <a class="btn blue pull-right " data-toggle="modal" href="#large"> Add Registration Fees </a>
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
                                                                                                <input type="hidden" value="AddRegistrationfees" name="action" id="action">
                                                                                                <input type="hidden" value="<?php echo $_REQUEST['token']; ?>" name="stud_id" id="stud_id">
                                                                                                <input type="hidden" value="<?php echo $query['studentPortal_Id']; ?>" name="studentPortal_Id" id="studentPortal_Id">
                                                                                                <?php
                                                                                                $studOf = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `studentadmission` where stud_id='" . $_REQUEST['token'] . "'"));

                                                                                                if ($studOf['studentPortal_Id'] == 2) {
                                                                                                    $receiptNo = '';
                                                                                                    $studentfee = mysqli_fetch_array(mysqli_query($dbconn, "select max(recepitCount) as receiptNo from studentfee"));

                                                                                                    if (isset($studentfee['receiptNo'])) {
                                                                                                        $receiptNo = $studentfee['receiptNo'] + 1;
                                                                                                    } else {
                                                                                                        $receiptNo = 1;
                                                                                                    }
                                                                                                    $rcNo = "RC" . $receiptNo;
                                                                                                } else {
                                                                                                    $rcNo = "";
                                                                                                }
                                                                                                ?>
                                                                                                <div class="form-body">
                                                                                                    <div class="row">
                                                                                                        <div class="col-md-12">
                                                                                                            <h4 class="bold text-center">Payment Detail</h4>
                                                                                                        </div>
                                                                                                        <?php if ($studOf['studentPortal_Id'] != 2) { ?>

                                                                                                            <!--                                                                                                            <div class="form-group row col-md-offset-1" style="display: none;">
                                                                                                                                                                                                            <label for="form_control_1" class="col-md-2">Receipt No.*</label>
                                                                                                                                                                                                            <div class="col-md-8">
                                                                                                                                                                                                            <input name="receiptNo" id="receiptNo" value="0" class="form-control"  placeholder="Enter The Receipt Number" type="text" required="">
                                                                                                                                                                                                            </div>
                                                                                                                                                                                                            </div>-->
                                                                                                        <?php } else { ?>
                                                                                                            <input type="hidden" name="recepitCount" id="recepitCount" value="<?php echo $studentfee['receiptNo']; ?>">
                                                                                                            <input name="receiptNo" id="receiptNo" value="<?php echo $rcNo; ?>" class="form-control" type="hidden">
                                                                                                        <?php } ?>
                                                                                                        <div class="form-group row col-md-offset-1" style="display:none;" id="emitypeDiv">
                                                                                                            <label for="form_control_1" class="col-md-2">Fee Date</label>
                                                                                                            <div class="col-md-8" id="emitypeInputDiv">
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <div class="form-group row col-md-offset-1">
                                                                                                            <label for="form_control_1" class="col-md-2">Pay Date*</label>
                                                                                                            <div class="col-md-8">
                                                                                                                <input name="payDate" id="payDate" class="form-control date-picker" placeholder="Enter The Pay Date" type="text" required="">
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <div class="form-group row col-md-offset-1">
                                                                                                            <label for="form_control_1" class="col-md-2">Amount*</label>
                                                                                                            <div class="col-md-8">
                                                                                                                <input name="amount" id="amount" class="form-control" placeholder="Enter The Amount" type="text" required="">
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <div class="form-group row col-md-offset-1">
                                                                                                            <label for="form_control_1" class="col-md-2">Payment Mode*</label>
                                                                                                            <div class="col-md-8">
                                                                                                                <select name="paymentMode" id="paymentMode" class="form-control" required="" onchange="addpaymentmode();">
                                                                                                                    <option value="">Select Payment Mode</option>
                                                                                                                    <?php
                                                                                                                    $filterMode = mysqli_query($dbconn, "Select * from paymentmode where isDelete='0' and iStatus='1'");
                                                                                                                    while ($rowMode = mysqli_fetch_array($filterMode)) {
                                                                                                                        echo '<option value=' . $rowMode['paymentId'] . '>' . $rowMode['paymentName'] . '</option>';
                                                                                                                    }
                                                                                                                    ?>
                                                                                                                    <!--                                                                                                                        <option value="Cash">Cash</option>
                                                                                                                                                                                                        <option value="Cheque">Cheque</option>-->
                                                                                                                </select>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <div class="form-group row col-md-offset-1" style="display:none;" id="divbankName">
                                                                                                            <label for="form_control_1" class="col-md-2">Bank Name</label>
                                                                                                            <div class="col-md-8">
                                                                                                                <select name="bank_name" id="bank_name" class="selectpicker form-control show-tick" data-show-subtext="true" data-live-search="true">
                                                                                                                    <option value="">Select Bank Name</option>
                                                                                                                    <?php
                                                                                                                    $fiterPayFor = mysqli_query($dbconn, "select * from bankmaster where istatus='1' and isDelete='0'");
                                                                                                                    while ($rowFee = mysqli_fetch_array($fiterPayFor)) {
                                                                                                                        echo "<option data-subtext=" . $rowFee['bankName'] . " value=" . $rowFee['bankMasterId'] . " >" . $rowFee['bankName'] . "</option>";
                                                                                                                    }
                                                                                                                    ?>
                                                                                                                </select>
                                                                                                                <!--<input name="bank_name" id="bank_name" class="form-control" placeholder="Enter The Bank Name" type="text" >-->
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <div class="form-group row col-md-offset-1" style="display:none;" id="divchequeNo">
                                                                                                            <label for="form_control_1" class="col-md-2">Cheque No</label>
                                                                                                            <div class="col-md-8">
                                                                                                                <input name="cheqNumber" id="cheqNumber" class="form-control" placeholder="Enter The Cheque Number" type="text">
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <div class="form-group row col-md-offset-1" style="display:none;" id="divBankDopsit">
                                                                                                            <label for="form_control_1" class="col-md-2">Deposited Bank</label>
                                                                                                            <div class="col-md-8">
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
                                                                                                            <div class="col-md-8">
                                                                                                                <input name="comments" id="comments" class="form-control" placeholder="Enter The Comments" type="text">
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>

                                                                                                <div class="form-actions noborder">
                                                                                                    <input class="btn blue margin-top-20 col-md-offset-3" type="submit" id="Btnmybtn" onclick="updatefeeDetail();" value="Submit" name="submit">
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
                                                                                <?php $fetchcourseDetail = mysqli_query($dbconn, "Select *  from studentcourse where stud_id =" . $stud_id . " and studentcourse.istatus=1 and emiType not in(1)");
                                                                                ?>
                                                                                <div class="col-md-12">
                                                                                    <input type="hidden" value="<?php echo $_REQUEST['token']; ?>" id="stud_id" name="stud_id">
                                                                                    <div class="form-group  col-md-2">
                                                                                        <a href="#" onclick="PageLoadData(1);"></a>
                                                                                    </div>
                                                                                </div>
                                                                            </form>
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
    <script>
        function checkclose() {
            history.go(-1);
        }

        $(document).ready(function() {
            $("#payDate").datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                //                                                                                                        todayHighlight: true,
                defaultDate: "now",
                todayHighlight: true,
                endDate: '+0d',
                startDate: '0d'
            });

            //                                                                                                    $('.selectpicker').selectpicker({
            //                                                                                                        liveSearch: true
            //                                                                                                    });

        });

        $(document).ready(function() {
            $("#amount").keydown(function(e) {
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

        function addpaymentmode() {
            var paymentMode = $('#paymentMode').val();
            if (paymentMode != "2") {
                $('#divbankName').hide();
                $('#divchequeNo').hide();
            } else {
                $('#divbankName').show();
                $('#divchequeNo').show();
            }
            if (paymentMode != 5) {
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
                        window.location.href = '<?php echo $web_url; ?>Supervisor/AddRegisterFee.php?token=' + response;
                    }
                }
            });
        });

        function PageLoadData(Page) {
            var stud_id = $('#stud_id').val();
            $('#loading').css("display", "block");
            $.ajax({
                type: "POST",
                url: "<?php echo $web_url; ?>Supervisor/AjaxStudentRegistrarionFee.php",
                data: {
                    action: 'ListUser',
                    Page: Page,
                    stud_id: stud_id
                },
                success: function(msg) {
                    $("#PlaceUsersDataHere").html(msg);
                    $('#loading').css("display", "none");
                }
            });
        }
        PageLoadData(1);
    </script>
    </div>
</body>

</html>