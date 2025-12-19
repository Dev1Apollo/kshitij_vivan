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
        <link href="<?php echo $web_url; ?>admin/assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" />
    </head>
    <body class="page-container-bg-solid page-boxed">
        <div style="display: none; z-index: 10060;" id="loading">
            <img id="loading-image" src="<?php echo $web_url; ?>admin/images/loader1.gif">
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
                                            $rowfilter = mysqli_fetch_array(mysqli_query($dbconn,"SELECT * FROM `lead`  where leadId='" . $_REQUEST['token'] . "'"));
                                            ?>
                                            <div class="row">
                                                <?php
                                                $rowfilterCD = mysqli_fetch_array(mysqli_query($dbconn,"SELECT *,(select inquirySourceName from inquirysource where inquirysource.inquirySourceId = customerentry.inquirySourceId) as inquirySourceName FROM `customerentry`  where isDelete='0'  and  istatus='1' and  customerEntryId='" . $_REQUEST['cid'] . "'"));
                                                ?>
                                                <div class="col-md-12">
                                                    <h4 class="bold text-center">Customer Details</h4>
                                                    <div class="col-md-4">
                                                        <h4 class=""><span class="bold">Name:</span>&nbsp;<?php echo $rowfilterCD['title'] . ' ' . $rowfilterCD['firstName'] . ' ' . $rowfilterCD['MiddleName'] . ' ' . $rowfilterCD['lastName']; ?>  </h4>
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
                                                            ?>
                                                        </h4>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <h4 class=""><span class="bold">Inquiry Source :</span>&nbsp;
                                                            <?php
                                                            echo $rowfilterCD['inquirySourceName'];
                                                            ?>
                                                        </h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr />
                                            <form  role="form"  method="POST"  action="" name="frmparameter"  id="frmparameter" enctype="multipart/form-data">
                                                <input type="hidden" value="AddInquiryFollowUp" name="action" id="action">
                                                <input type="hidden" value="<?php echo $_REQUEST['token'] ?>" name="leadId" id="leadId">
                                                <input type="hidden" value="<?php echo $_REQUEST['cid'] ?>" name="customerEntryId" id="customerEntryId">
                                                <div class="form-body">
                                                    <div class="form-group col-md-3">
                                                        <label for="form_control_1">Select Inquiry Status*</label>
                                                        <?php
                                                        $querysInq = "SELECT * FROM `status`  where isDelete='0'  and  istatus='1' order by  statusId asc";
                                                        $resultsInq = mysqli_query($dbconn,$querysInq) or die(mysqli_error($dbconn));
                                                        ?>
                                                        <select class="form-control" name="InquiryStatus" id="InquiryStatus" required="" onchange="addbookingamount()">
                                                            <?php
                                                            echo "<option value='' >Select Inquiry Status</option>";
                                                            while ($rowsInq = mysqli_fetch_array($resultsInq)) {
                                                                $rowfilter = mysqli_fetch_array(mysqli_query($dbconn,"SELECT * FROM `lead`  where leadId='" . $_REQUEST['token'] . "'"));
                                                                ?>
                                                                <option value=<?php echo $rowsInq['statusId']; ?> <?php
                                                                if ($rowsInq['statusId'] == $rowfilter['statusId']) {
                                                                    echo 'selected="selected"';
                                                                }
                                                                ?> ><?php echo $rowsInq['statusName'] ?> </option>
                                                                    <?php } ?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group col-md-3" style="display:none;" id="bookingamountid">
                                                        <label for="form_control_1">Booking Amount*</label>
                                                        <input type="text" name="booking_amount" id="booking_amount" class="form-control"  onkeyup="if (/\D/g.test(this.value))
                                                                    this.value = this.value.replace(/\D/g, '')"/>
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label for="form_control_1">Next Follow Up Date</label>
                                                        <input type="text" id="Date" name="Date" class="form-control date-set" placeholder="Enter The Next Follow Up Date"/>
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label for="form_control_1">Comment*</label>
                                                        <textarea name="comment" id="comment" class="form-control" required=""></textarea>
                                                    </div>
                                                </div>
                                                <div class="form-actions noborder">
                                                    <input class="btn blue margin-top-20" type="submit" id="Btnmybtn"  value="Submit" name="submit">      
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
                                            $resultfilter = mysqli_query($dbconn,$filterstr);
                                            if (mysqli_num_rows($resultfilter) > 0) {
                                                $i = 1;
                                                ?>
                                                <table class="table table-bordered table-hover center table-responsive" width="100%" id="tableC">
                                                    <thead class="tbg">
                                                        <tr>
                                                            <th class="all">Customer Name</th>
                                                            <th class="desktop">Employee Name</th>
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
                                                                        $resultCustomer = mysqli_query($dbconn,$customerentry);
                                                                        $rowCustomer = mysqli_fetch_array($resultCustomer);
                                                                        echo $rowCustomer['title'] . ' ' . $rowCustomer['firstName'] . ' ' . $rowCustomer['MiddleName'] . ' ' . $rowCustomer['lastName'];
                                                                        ?> 
                                                                    </div>
                                                                </td> 
                                                                <td>
                                                                    <div class="form-group form-md-line-input "><?php
                                                                        $employeemaster = mysqli_fetch_array(mysqli_query($dbconn,"SELECT * FROM `employeemaster`  where isDelete='0'  and  istatus='1' and employeeMasterId='" . $rowfilterLF['employeeMasterId'] . "'"));
                                                                        echo $employeemaster['employeeName'];
                                                                        ?> 
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group form-md-line-input "><?php echo $rowfilterLF['nextFollowupDate']; ?> 
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group form-md-line-input "><?php
                                                                        $inquiryStatus = mysqli_fetch_array(mysqli_query($dbconn,"SELECT * FROM `status`  where isDelete='0'  and  istatus='1' and statusId='" . $rowfilterLF['statusId'] . "'"));
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
            <script src="<?php echo $web_url; ?>admin/assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js" type="text/javascript"></script>
            <script>
                                                        function addbookingamount()
                                                        {
                                                            var InquiryStatus = $('#InquiryStatus').val();
                                                            if (InquiryStatus == '3')
                                                            {
                                                                $('#bookingamountid').show();
                                                                $('#booking_amount').attr('required', true);
                                                                $("#Date").attr('required', false);
                                                            } else if (InquiryStatus == '6')
                                                            {
                                                                $('#booking_amount').attr('required', false);
                                                                $('#bookingamountid').hide();
                                                                $("#Date").attr('required', true);
                                                            } else
                                                            {
                                                                $('#booking_amount').attr('required', false);
                                                                $('#bookingamountid').hide();
                                                                $("#Date").attr('required', false);
                                                            }

                                                        }

                                                        $(document).ready(function () {

                                                            addbookingamount();

                                                            var date = new Date();
                                                            $("#Date").datetimepicker({
                                                                format: "dd-mm-yyyy,hh:ii:ss",
                                                                autoclose: true,
                                                                todayHighlight: true,
                                                                startDate: date
                                                            });

                                                        });

                                                        function checkclose() {
                                                            window.close();
                                                        }
                                                        $('#frmparameter').submit(function (e) {

                                                            e.preventDefault();
                                                            var $form = $(this);
                                                            $('#loading').css("display", "block");
                                                            $.ajax({
                                                                type: 'POST',
                                                                url: '<?php echo $web_url; ?>admin/querydata.php',
                                                                data: $('#frmparameter').serialize(),
                                                                success: function (response) {
                                                                    if (response != 0)
                                                                    {
                                                                        $('#loading').css("display", "none");
                                                                        $("#Btnmybtn").attr('disabled', 'disabled');
                                                                        alert('Added Sucessfully.');
                                                                        window.close();
                                                                    }
                                                                }

                                                            });
                                                        });



            </script>
    </body>
</html>
