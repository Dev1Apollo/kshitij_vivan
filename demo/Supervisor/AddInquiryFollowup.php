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
    <title><?php echo $ProjectName; ?> | Add Inquiry Follow Up</title>
    <?php include_once 'include.php'; ?>
    <!--        <link href="<?php echo $web_url; ?>Supervisor/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet" type="text/css" />-->
    <link href="<?php echo $web_url; ?>Supervisor/assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" />

</head>

<body class="page-container-bg-solid page-boxed">
    <?php // include_once './header.php'; 
    ?>
    <div style="display: none; z-index: 10060;" id="loading">
        <img id="loading-image" src="<?php echo $web_url; ?>Supervisor/images/loader1.gif">
    </div>
    <div class="page-container">
        <div class="page-content-wrapper">

            <div class="page-content">
                <div class="container">

                    <div class="page-content-inner">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="portlet light ">
                                    <div class="portlet-title">
                                        <div class="caption grey-gallery">
                                            <i class="icon-settings grey-gallery"></i>
                                            <span class="caption-subject bold uppercase"> Add Inquiry Follow Up</span>

                                        </div>
                                    </div>
                                    <div class="portlet-body form">
                                        <?php
                                        $rowfilter = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `lead`  where leadId='" . $_REQUEST['token'] . "'"));
                                        ?>
                                        <div class="row">
                                            <?php
                                            $rowfilterCD = mysqli_fetch_array(mysqli_query($dbconn, "SELECT *,(select inquirySourceName from inquirysource where inquirysource.inquirySourceId = customerentry.inquirySourceId) as inquirySourceName FROM `customerentry`  where isDelete='0'  and  istatus='1' and  customerEntryId='" . $_REQUEST['cid'] . "'"));
                                            ?>
                                            <div class="col-md-12">
                                                <h4 class="bold text-center">Customer Details</h4>
                                                <div class="col-md-4">
                                                    <h4 class=""><span class="bold">Name:</span>&nbsp;<?php echo $rowfilterCD['title'] . ' ' . $rowfilterCD['firstName'] . ' ' . $rowfilterCD['MiddleName'] . ' ' . $rowfilterCD['lastName']; ?> </h4>
                                                </div>
                                                <div class="col-md-4">
                                                    <h4 class=""><span class="bold">Mobile No:</span>&nbsp;<?php echo $rowfilterCD['mobileNo']; ?></h4>
                                                </div>
                                                <div class="col-md-4">
                                                    <h4 class=""><span class="bold">Email :</span>&nbsp;<?php echo $rowfilterCD['email']; ?></h4>
                                                </div>
                                            </div>
                                        </div>
                                        <hr />
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h4 class="bold text-center">Inquiry Details</h4>
                                                <div class="col-md-6">
                                                    <h4 class=""><span class="bold">Inquiry for:</span>&nbsp;
                                                        <?php
                                                        echo $rowfilter['inquiryfor'];
                                                        ?></h4>
                                                </div>
                                                <div class="col-md-6">
                                                    <h4 class=""><span class="bold">Inquiry Source :</span>&nbsp;
                                                        <?php
                                                        echo $rowfilterCD['inquirySourceName'];
                                                        ?></h4>
                                                </div>
                                                <!--                                                    <div class="col-md-2">
                                                                                                            <h4 class=""><span class="bold">Adult:</span>&nbsp;<?php echo $rowfilter['noOfAdult']; ?></h4>
                                                                                                            <h4 class=""><span class="bold">Child With Bed:</span>&nbsp;<?php echo $rowfilter['noOfChildWithBed']; ?></h4>
                                                                                                        </div>
                                                                                                        <div class="col-md-2">
                                                                                                            <h4 class=""><span class="bold">Child No Bed:</span>&nbsp;<?php echo $rowfilter['noOfchildNobed']; ?></h4>
                                                                                                            <h4 class=""><span class="bold">Infant:</span>&nbsp;<?php echo $rowfilter['infant']; ?></h4>
                                                                                                        </div>
                                                                                                        <div class="col-md-4">
                                                                                                            <h4 class=""><span class="bold">Destination:</span>&nbsp;<?php echo $rowfilter['destination']; ?></h4>
                                                                                                            <h4 class=""><span class="bold">No Of Nights:</span>&nbsp;<?php echo $rowfilter['noOfNights']; ?></h4>
                                                                                                        </div>
                                                                                                        <div class="col-md-4">
                                                                                                            <h4 class=""><span class="bold">Date Of Travel From :</span>&nbsp;<?php echo $rowfilter['dateOfTravelFrom']; ?></h4>
                                                                                                            <h4 class=""><span class="bold">Date Of Travel To :</span>&nbsp;<?php echo $rowfilter['dateOfTravelTo']; ?></h4>
                                                                                                        </div>-->
                                            </div>
                                        </div>
                                        <hr />
                                        <form role="form" method="POST" action="" name="frmparameter" id="frmparameter" enctype="multipart/form-data">
                                            <input type="hidden" value="AddInquiryFollowUp" name="action" id="action">
                                            <input type="hidden" value="<?php echo $_REQUEST['token'] ?>" name="leadId" id="leadId">
                                            <input type="hidden" value="<?php echo $_REQUEST['cid'] ?>" name="customerEntryId" id="customerEntryId">
                                            <div class="form-body">
                                                
                                                <div class="form-group col-md-3">
                                                    <label for="form_control_1">Select Inquiry Status*</label>
                                                    <?php
                                                    $querysInq = "SELECT * FROM `status`  where isDelete='0'  and  istatus='1' order by  statusId asc";
                                                    $resultsInq = mysqli_query($dbconn, $querysInq) or die(mysqli_error($dbconn));
                                                    $rowfilter = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `lead`  where leadId='" . $_REQUEST['token'] . "'"));
                                                    ?>
                                                    <select class="form-control" name="InquiryStatus" id="InquiryStatus" required="" onchange="addbookingamount()">
                                                        <?php
                                                        echo "<option value='' >Select Inquiry Status</option>";
                                                        while ($rowsInq = mysqli_fetch_array($resultsInq)) {
                                                        ?>
                                                            <?php if ($rowfilter['statusId'] == 1) { 
                                                                if ($rowsInq['statusId'] != 3) { 
                                                                ?>
                                                                <option value=<?php echo $rowsInq['statusId']; ?> <?php if ($rowsInq['statusId'] == $rowfilter['statusId']) { echo 'selected="selected"'; } ?>><?php echo $rowsInq['statusName'] ?> </option>
                                                                <?php } ?>
                                                            <?php } else { ?>
                                                                <option value=<?php echo $rowsInq['statusId']; ?> <?php if ($rowsInq['statusId'] == $rowfilter['statusId']) { echo 'selected="selected"'; } ?>><?php echo $rowsInq['statusName'] ?> </option>
                                                        <?php } } ?>
                                                    </select>

                                                </div>
                                                <div class="form-group col-md-3" style="display:none;" id="bookingamountid">
                                                    <label for="form_control_1">Booking Amount*</label>
                                                    <input type="text" name="booking_amount" id="booking_amount" class="form-control" onkeyup="if (/\D/g.test(this.value))
                                                                    this.value = this.value.replace(/\D/g, '')" />
                                                </div>
                                                <div class="form-group col-md-3" id="bookingDate">
                                                    <label for="form_control_1">Next Follow Up Date</label>
                                                    <input type="text" id="Date" name="Date" class="form-control date-set" placeholder="Enter The Next Follow Up Date" />
                                                </div>
                                                <?php //if ($_SESSION['EmployeeType'] == 'Supervisor') { ?>
                                                <div class="form-group col-md-3" id="DivEmployee">
                                                    <label for="form_control_1">Select Employee*</label>
                                                    <?php
                                                    $querysBranch = "SELECT * FROM `support_employee` where isDelete='0' order by  support_emp_name asc";
                                                    $resultsBranch = mysqli_query($dbconn, $querysBranch) or die(mysqli_error($dbconn)); ?>
                                                    <select class="form-control" name="supportEmployeeId" id="supportEmployeeId" <?= in_array($rowfilter['statusId'],[6,7]) ? 'readonly' : '';  ?>>
                                                    <?php echo "<option value='' >Select</option>";
                                                    while ($rowsBranch = mysqli_fetch_array($resultsBranch)) {
                                                        if (in_array($rowfilter['statusId'],[6,7])) {
                                                            if($rowsBranch['id']==$rowfilter['transfer_to']){
                                                                echo "<option value='" . $rowsBranch['id'] . "' selected>" . $rowsBranch['support_emp_name'] . "</option>";
                                                            // } else if($rowsBranch['id']==$rowfilter['support_employee']){
                                                            //     echo "<option value='" . $rowsBranch['id'] . "' selected>" . $rowsBranch['support_emp_name'] . "</option>";
                                                            } else {
                                                                echo "<option value='" . $rowsBranch['id'] . "'>" . $rowsBranch['support_emp_name'] . "</option>";    
                                                            }
                                                        } else if($rowsBranch['id']==$rowfilter['support_employee']){
                                                            echo "<option value='" . $rowsBranch['id'] . "' selected>" . $rowsBranch['support_emp_name'] . "</option>";
                                                        } else {
                                                            echo "<option value='" . $rowsBranch['id'] . "'>" . $rowsBranch['support_emp_name'] . "</option>";
                                                        }
                                                    }
                                                    echo "</select>";
                                                    ?>
                                                </div>
                                                <?php // } 
                                                ?>
                                                <?php  if (!in_array($rowfilter['statusId'],[6,7])) { ?>
                                                <div class="form-group col-md-3" style="display:none;" id="Divtransferto">
                                                    <label for="form_control_1">Counselling By *</label>
                                                    <?php
                                                    $querysBranch = "SELECT * FROM `support_employee` where isDelete='0' order by  support_emp_name asc";
                                                    $resultsBranch = mysqli_query($dbconn, $querysBranch) or die(mysqli_error($dbconn));
                                                    echo '<select class="form-control" name="transferToEmpId" id="transferToEmpId">';
                                                    echo "<option value='' >Select</option>";
                                                    while ($rowsBranch = mysqli_fetch_array($resultsBranch)) {
                                                        echo "<option value='" . $rowsBranch['id'] . "'>" . $rowsBranch['support_emp_name'] . "</option>";
                                                    }
                                                    echo "</select>";
                                                    ?>
                                                </div>
                                                <?php } ?>
                                                <div class="form-group col-md-3" style="display:none;" id="DivBranch">
                                                    <label for="form_control_1">Select Branch *</label>
                                                    <div class="txt_field">
                                                        <?php
                                                        $querysBranch = "SELECT employeeMasterId,employeeName FROM `employeemaster` inner join branchmaster on employeemaster.branchid=branchmaster.branchid where employeemaster.isDelete='0' and branchmaster.isDelete=0 and iEmployeeType!=2 and employeeMasterId!=1 order by employeemaster.employeeName asc;";
                                                        $resultsBranch = mysqli_query($dbconn, $querysBranch) or die(mysqli_error($dbconn));
                                                        echo '<select class="form-control" name="EmployeeId" id="EmployeeId" required>';
                                                        echo "<option value='' >Select</option>";
                                                        while ($rowsBranch = mysqli_fetch_array($resultsBranch)) {
                                                            echo "<option value='" . $rowsBranch['employeeMasterId'] . "'>" . $rowsBranch['employeeName'] . "</option>";
                                                        }
                                                        echo "</select>";
                                                        ?>
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <label for="form_control_1">Comment*</label>
                                                    <textarea name="comment" id="comment" class="form-control" required=""></textarea>
                                                </div>
                                                

                                            </div>

                                            <div class="form-actions noborder">
                                                <input class="btn blue margin-top-20" type="submit" id="Btnmybtn" value="Submit" name="submit">
                                                <button type="button" class="btn blue margin-top-20" onClick="checkclose();">Cancel</button>
                                            </div>
                                        </form>


                                    </div>
                                </div>
                                <div class="portlet light ">
                                    <div class="portlet-title">
                                        <div class="caption grey-gallery">
                                            <i class="icon-settings grey-gallery"></i>
                                            <span class="caption-subject bold uppercase">List Of Followup Details</span>

                                        </div>
                                    </div>
                                    <div class="portlet-body">
                                        <?php
                                        $filterstr = "SELECT * FROM `leadfollowup`  where leadId='" . $_REQUEST['token'] . "'  order by  leadFollowupId desc";
                                        $resultfilter = mysqli_query($dbconn, $filterstr);
                                        if (mysqli_num_rows($resultfilter) > 0) {
                                            $i = 1;
                                        ?>
                                            <table class="table table-bordered table-hover center table-responsive" width="100%" id="tableC">
                                                <thead class="tbg">
                                                    <tr>
                                                        <th class="all">Customer Name</th>
                                                        <th class="desktop">Call Back By</th>
                                                        <th class="desktop">Walk In By</th>
                                                        <th class="desktop">Counselling By</th>
                                                        <th class="desktop">Booked By</th>
                                                        <th class="desktop">Followup Date</th>
                                                        <th class="desktop">Status</th>
                                                        <th class="desktop">Comment</th>
                                                        <th class="desktop">Entry Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    while ($rowfilterLF = mysqli_fetch_array($resultfilter)) {
                                                    ?>
                                                        <tr>
                                                            <td>
                                                                <div class="form-group form-md-line-input "><?php
                                                                                                            $customerentry = "SELECT * FROM `customerentry`  where isDelete='0'  and  istatus='1' and  customerEntryId='" . $rowfilterLF['customerEntryId'] . "'";
                                                                                                            $resultCustomer = mysqli_query($dbconn, $customerentry);
                                                                                                            $rowCustomer = mysqli_fetch_array($resultCustomer);
                                                                                                            echo $rowCustomer['title'] . ' ' . $rowCustomer['firstName'] . ' ' . $rowCustomer['MiddleName'] . ' ' . $rowCustomer['lastName'];
                                                                                                            ?>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-group form-md-line-input ">
                                                                <?php
                                                                    $employeemasters = mysqli_query($dbconn, "SELECT * FROM `support_employee`  where isDelete='0'  and  istatus='1' and id='" . $rowfilterLF['support_employee'] . "'");
                                                                    if(mysqli_num_rows($employeemasters) == 0){
                                                                        echo "-";
                                                                    } else {
                                                                        $employeemaster = mysqli_fetch_array($employeemasters);
                                                                        echo $employeemaster['support_emp_name'];
                                                                    }
                                                                ?>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-group form-md-line-input ">
                                                                <?php
                                                                    $employeemasters = mysqli_query($dbconn, "SELECT * FROM `support_employee`  where isDelete='0'  and  istatus='1' and id='" . $rowfilterLF['walkinby'] . "'");
                                                                    if(mysqli_num_rows($employeemasters) == 0){
                                                                        echo "-";
                                                                    } else {
                                                                        $employeemaster = mysqli_fetch_array($employeemasters);
                                                                        echo $employeemaster['support_emp_name'];
                                                                    }
                                                                ?>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-group form-md-line-input ">
                                                                <?php
                                                                    $employeemasters = mysqli_query($dbconn, "SELECT * FROM `support_employee`  where isDelete='0'  and  istatus='1' and id='" . $rowfilterLF['transfer_to'] . "'");
                                                                    if(mysqli_num_rows($employeemasters) == 0){
                                                                        echo "-";
                                                                    } else {
                                                                        $employeemaster = mysqli_fetch_array($employeemasters);
                                                                        echo $employeemaster['support_emp_name'];
                                                                    }
                                                                ?>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-group form-md-line-input ">
                                                                <?php
                                                                    $employeemasters = mysqli_query($dbconn, "SELECT * FROM `support_employee`  where isDelete='0'  and  istatus='1' and id='" . $rowfilterLF['bookedby'] . "'");
                                                                    if(mysqli_num_rows($employeemasters) == 0){
                                                                        echo "-";
                                                                    } else {
                                                                        $employeemaster = mysqli_fetch_array($employeemasters);
                                                                        echo $employeemaster['support_emp_name'];
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-group form-md-line-input "><?php echo $rowfilterLF['nextFollowupDate']; ?>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-group form-md-line-input "><?php
                                                                                                            $inquiryStatus = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `status`  where isDelete='0'  and  istatus='1' and statusId='" . $rowfilterLF['statusId'] . "'"));
                                                                                                            echo $inquiryStatus['statusName'];
                                                                                                            ?>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-group form-md-line-input "><?php echo $rowfilterLF['comment']; ?>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-group form-md-line-input "><?php echo $rowfilterLF['strEntryDate']; ?>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        <?php
                                        } else {
                                        ?>
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12  col-xs-12 col-sm-12 padding-5 bottom-border-verydark">
                                                    <div class="alert alert-info clearfix profile-information padding-all-10 margin-all-0 backgroundDark">
                                                        <h1 class="font-white text-center"> No Data Found ! </h1>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php include_once './footer.php'; ?>

        <!--        <script src="<?php echo $web_url; ?>Supervisor/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>-->
        <script src="<?php echo $web_url; ?>Supervisor/assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js" type="text/javascript"></script>

        <script>
            function addbookingamount() {
                var InquiryStatus = $('#InquiryStatus').val();
                // alert(InquiryStatus);
                if (InquiryStatus == '3') {
                    $('#bookingamountid').show();
                    $('#booking_amount').attr('required', true);
                    $("#Date").attr('required', false);
                    $("#bookingDate").hide();
                    $("#Divtransferto").hide();
                    $("#transferToEmpId").attr('required', false);
                    $("#DivBranch").show();
                    $("#EmployeeId").attr('required', true);
                    
                    
                } else if (InquiryStatus == '6' || InquiryStatus == '7') {
                    $('#booking_amount').attr('required', false);
                    $('#bookingamountid').hide();
                    $("#Date").attr('required', true);
                    $("#bookingDate").show();
                    $("#Divtransferto").show();
                    $("#transferToEmpId").attr('required', true);
                    $("#DivBranch").hide();
                    $("#EmployeeId").attr('required', false);
                    
                    
                } else if (InquiryStatus == '2') {
                    $('#booking_amount').attr('required', false);
                    $('#bookingamountid').hide();
                    $("#Date").attr('required', false);
                    $("#bookingDate").hide();
                    $("#Divtransferto").hide();
                    $("#transferToEmpId").attr('required', false);
                    $("#DivBranch").hide();
                    $("#EmployeeId").attr('required', false);
                    
                    
                } else if (InquiryStatus == '1') {
                    $('#booking_amount').attr('required', false);
                    $('#bookingamountid').hide();
                    $("#Date").attr('required', true);
                    $("#bookingDate").show();
                    $("#Divtransferto").hide();
                    $("#transferToEmpId").attr('required', false);
                    $("#DivBranch").hide();
                    $("#EmployeeId").attr('required', false);
                    
                    
                } else {
                    $('#booking_amount').attr('required', false);
                    $('#bookingamountid').hide();
                    $("#Date").attr('required', false);
                    $("#bookingDate").show();
                    $("#Divtransferto").hide();
                    $("#transferToEmpId").attr('required', false);
                    $("#DivBranch").hide();
                    $("#EmployeeId").attr('required', false);
                    
                    
                }
                
                

            }
            
            function checkEmployeeReadonly() {
                const inquiryStatus = $("#InquiryStatus").val();
                const readonlyStatuses = ["2", "3", "6", "7"]; // Status IDs where readonly should be applied
                
                if (readonlyStatuses.includes(inquiryStatus)) {
                    $("#supportEmployeeId").prop('disabled', true); // Disable visually
                    // Add a hidden field to retain the value on submit
                    const selectedValue = $("#supportEmployeeId").val();
                    $("#supportEmployeeId").after(`<input type="hidden" name="supportEmployeeId" value="${selectedValue}">`);
                } else {
                    $("#supportEmployeeId").prop('disabled', false); // Enable
                    $("input[name='supportEmployeeId']").remove(); // Remove hidden field if exists
                }
            }
            
            // Run on page load and on change
            $(document).ready(checkEmployeeReadonly);
            $("#InquiryStatus").change(checkEmployeeReadonly);
            
            
            //                                                        $(document).ready(function () {
            //                                                            var date = new Date();
            //                                                            $("#Date").datepicker({
            //                                                                format: 'dd-m-yyyy',
            //                                                                autoclose: true,
            //                                                                todayHighlight: true,
            ////                                                                defaultDate: "now",
            ////                                                                endDate: "now"
            //                                                                startDate: date
            //
            //                                                            });

            //                                                        });
            $(document).ready(function() {

                addbookingamount();

                var date = new Date();
                $("#Date").datetimepicker({
                    format: "dd-mm-yyyy,hh:ii:ss",
                    autoclose: true,
                    todayHighlight: true,
                    //defaultDate: "now",
                    //detaultTime: "now",
                    //startDate: "now"
                    startDate: date
                });

            });

            function checkclose() {
                window.close();
                //              window.location.href = window.location.href;
                //              history.go(-1);
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
                            window.close();
                            //                                                                        window.location.href = window.location.href;
                            //                                                                        history.go(-1);
                        }
                    }

                });
            });
        </script>
</body>

</html>