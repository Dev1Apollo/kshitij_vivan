<?php
ob_start();
error_reporting(E_ALL);
require_once '../common.php';
$connect = new connect();
include('IsLogin.php');

$result = mysqli_query($dbconn, "SELECT * FROM `studentfee` WHERE `studentfeeid`='" . $_REQUEST['token'] . "'");
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
        <title> <?php echo $ProjectName ?> |Edit Student Fee</title>
        <?php include_once './include.php'; ?>      
        <link href="assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet" type="text/css" />
    </head>
    <body class="page-container-bg-solid page-boxed">
        <?php
        include('header.php');
        ?>
        <div style="display: none; z-index: 10060;" id="loading">
            <img id="loading-image" src="<?php echo $web_url; ?>Employee/images/loader1.gif">
        </div>
        <div class="page-container">        
            <div class="page-content">
                <div class="container">                    
                    <div class="page-content-inner">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="portlet light ">
                                    <div class="portlet-title">
                                        <div class="caption grey-gallery">
                                            <i class="icon-settings grey-gallery"></i>
                                            <span class="caption-subject bold uppercase"> Edit Student Fee</span>
                                        </div>
                                    </div>
                                    <div class="portlet-body form">
                                        <?php
                                        $filteremiId = "SELECT * FROM studentemidetail where studentfeeid='" . $row['studentfeeid'] . "' and studentcourseId='" . $row['studentcourseId'] . "' and stud_id='" . $row['stud_id'] . "' and studentemidetail.isDelete=0";
                                        $rowEmiId = mysqli_fetch_array(mysqli_query($dbconn, $filteremiId));
                                        ?>

                                        <form  role="form"  method="POST"  action="" name="frmparameter"  id="frmparameter" enctype="multipart/form-data">
                                            <input type="hidden" value="EditStudentfees" name="action" id="action">
                                            <input type="hidden" value="<?php echo $row['studentfeeid'] ?>" name="studentfeeid" id="studentfeeid">
                                            <input type="hidden" value="<?php echo $row['studentcourseId'] ?>" name="studentcourseId" id="studentcourseId">
                                            <input type="hidden" value="<?php echo $row['stud_id'] ?>" name="stud_id" id="stud_id">
                                            <input type="hidden" value="<?php echo $rowEmiId['studemiId'] ?>" name="studemiId" id="studemiId">
                                            <div class="form-body">

                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <h4 class="bold text-center">Payment Detail</h4>
                                                    </div>
<!--                                                    <div class="form-group col-md-4">
                                                        <label for="form_control_1">Receipt No.*</label>
                                                        <div class="txt_field">
                                                            <input name="receiptNo" value="<?php // echo $row['receiptNo']; ?>" id="receiptNo" class="form-control"  placeholder="Enter The Receipt Number" type="text" required="">
                                                        </div>
                                                    </div>-->
                                                    <div class="form-group col-md-4">
                                                        <label for="form_control_1">Pay Date*</label>
                                                        <div class="txt_field" id="FeeDiv" >
                                                            <input name="payDate" id="payDate" value="<?php echo $row['payDate'] ?>" class="form-control date-picker" placeholder="Enter The Pay Date" type="text" readonly="">
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label for="form_control_1">Amount*</label>
                                                        <div class="txt_field" id="FeeDiv" >
                                                            <input name="amount" id="amount" value="<?php echo $row['amount'] ?>" class="form-control" placeholder="Enter The Amount" type="text" readonly="">
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label for="form_control_1">Payment Mode*</label>
                                                        <select name="paymentMode" id="paymentMode"  class="form-control" required="">
                                                            <option value="<?php echo $row['paymentMode'];?>">Select Payment Mode</option>
                                                            <?php
                                                            $filterPayMode = mysqli_query($dbconn, "SELECT * FROM `paymentmode` where isDelete='0'");
                                                            while ($rowMode = mysqli_fetch_array($filterPayMode)) {
                                                                ?>
                                                                <option value="<?php echo $rowMode['paymentId'] ?>" <?php
                                                                if ($row['paymentMode'] == $rowMode['paymentId']) {
                                                                    echo 'selected';
                                                                }
                                                                ?>><?php echo $rowMode['paymentName'] ?></option>
                                                                    <?php } ?>

                                                        </select>
                                                    </div>
                                                    <div class="form-group col-md-4" style="display:none;" id="divbankName">
                                                        <label for="form_control_1">Bank Name</label>
                                                        <div class="txt_field">
                                                            <input name="bank_name" id="bank_name" value="<?php echo $row['bankName'] ?>" class="form-control" placeholder="Enter The Bank Name" type="text" >
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-4" style="display:none;" id="divchequeNo">
                                                        <label for="form_control_1">Cheque No</label>
                                                        <input name="cheqNumber" id="cheqNumber"  value="<?php echo $row['chequeNo'] ?>" class="form-control" placeholder="Enter The Cheque Number" type="text" >
                                                    </div>
                                                    <div class="form-group col-md-4" style="display:none;" id="divBankDopsit">
                                                        <label for="form_control_1">Deposited Bank</label>
                                                        <select name="bankDeposit" id="bankDeposit" class="form-control">
                                                            <option value="">Select Bank</option>
                                                            <?php
                                                            $filterMode = mysqli_query($dbconn, "Select * from bank where isDelete='0' and iStatus='1'");
                                                            while ($rowMode = mysqli_fetch_array($filterMode)) { ?>
                                                                <option value="<?php echo $rowMode['bankId'] ?>" <?php if($rowMode['bankId'] == $row['toBank']) {echo "selected";}else{echo "";} ?>><?php echo $rowMode['bankName'] ?></option>
                                                            <?php 
                                                            }
                                                            ?>
                                                        </select>

                                                    </div>

                                                    <div class="form-group col-md-4">
                                                        <label for="form_control_1">Comment</label>
                                                        <input name="comments" id="comments" value="<?php echo $row['comments'] ?>" class="form-control" placeholder="Enter The Comments" type="text" >
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-actions noborder">
                                                <input class="btn blue margin-top-20" type="submit" id="Btnmybtn"  value="Submit" name="submit">      
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
    <script src="<?php echo $web_url; ?>admin/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>

    <script type="text/javascript">

                                                    function checkclose() {
                                                        history.go(-1);
                                                        return false;
                                                    }

                                                    $(document).ready(function () {
                                                        $("#depositDate").datepicker({
                                                            format: 'dd-mm-yyyy',
                                                            autoclose: true,
                                                            todayHighlight: true,
                                                            defaultDate: "now"
                                                        });
                                                        $('#paymentMode').change(function () {
                                                            var paymentMode = $(this).val();
                                                            if (paymentMode != '2')
                                                            {
                                                                $('#divbankName').hide();
                                                                $('#divchequeNo').hide();
                                                            } else {
                                                                $('#divbankName').show();
                                                                $('#divchequeNo').show();
                                                            }
                                                            if (paymentMode != "5")
                                                            {
                                                                $('#divBankDopsit').hide();
                                                                $('#bankDeposit').attr('required', false);
                                                            } else
                                                            {
                                                                $('#divBankDopsit').show();
                                                                $('#bankDeposit').attr('required', true);
                                                            }
                                                        });
                                                    });

                                                    $(document).ready(function () {
                                                        $("#payDate").datepicker({
                                                            format: 'dd-mm-yyyy',
                                                            autoclose: true,
                                                            todayHighlight: true,
                                                            defaultDate: "now"
                                                        });
                                                    });

                                                    $(document).ready(function () {
                                                        $("#amount").keydown(function (e) {
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

                                                    function adddeposit() {
                                                        var deposit = $('#deposit').val();
                                                        if (deposit == 'Yes')
                                                        {
                                                            $('#divtoBank').show();
                                                            $('#divdepositAmount').show();
                                                            $('#divdepositDate').show();
                                                        } else {
                                                            $('#divtoBank').hide();
                                                            $('#divdepositAmount').hide();
                                                            $('#divdepositDate').hide();
                                                        }
                                                    }

                                                    $(document).ready(function () {
                                                        var paymentMode = $('#paymentMode').val();
                                                        if (paymentMode == '' || paymentMode == null) {
                                                            $('#divbankName').hide();
                                                            $('#divchequeNo').hide();
                                                        }
                                                        if (paymentMode == 'Cash')
                                                        {
                                                            $('#divbankName').hide();
                                                            $('#divchequeNo').hide();
                                                        } else {
                                                            $('#divbankName').show();
                                                            $('#divchequeNo').show();
                                                        }
                                                    });

                                                    $(document).ready(function () {
                                                        var deposit = $('#deposit').val();
                                                        if (deposit == 'Yes')
                                                        {
                                                            $('#divtoBank').show();
                                                            $('#divdepositAmount').show();
                                                            $('#divdepositDate').show();
                                                        } else {
                                                            $('#divtoBank').hide();
                                                            $('#divdepositAmount').hide();
                                                            $('#divdepositDate').hide();
                                                        }
                                                    });

                                                    $('#frmparameter').submit(function (e) {
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
                                                                    alert(' Edited Sucessfully.');
                                                                    response = response.trim();
                                                                    window.location.href = '';
//                                                                    window.location.href = '<?php echo $web_url; ?>Employee/student-fees.php?token=' + response;
                                                                }
                                                            }

                                                        });
                                                    });

    </script>

</body>
</html>
