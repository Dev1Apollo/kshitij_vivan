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
    <title><?php echo $ProjectName; ?> | Add Inquiry</title>
    <?php include_once 'include.php'; ?>
    <link href="<?php echo $web_url; ?>Supervisor/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet" type="text/css" />
</head>

<body class="page-container-bg-solid page-boxed">
    <?php // include_once './header.php'; 
    ?>
    <div style="display: none; z-index: 10060;" id="loading">
        <img id="loading-image" src="<?php echo $web_url; ?>Supervisor/images/loader1.gif">
    </div>
    <div class="page-container">
        <div class="page-content-wrapper">
            <!--                <div class="page-head">
                                    <div class="container">
                                        <div class="page-title">
                                            <h1>Add Company
                                            </h1>
                                        </div>
                                    </div>
                                </div>-->
            <div class="page-content">
                <div class="container">
                    <!--                        <ul class="page-breadcrumb breadcrumb">
                            <li>
                                <a href="<?php echo $web_url; ?>Supervisor/index.php">Home</a>
                                <i class="fa fa-circle"></i>
                            </li>
                            <li>
                                <a href="<?php echo $web_url; ?>Supervisor/CustomerEntry.php">List Of Customer Entry</a>
                                <i class="fa fa-circle"></i>
                            </li>
                            <li>
                                <span>Add Inquiry</span>
                            </li>
                        </ul>-->

                    <div class="page-content-inner">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="portlet light ">
                                    <div class="portlet-title">
                                        <div class="caption grey-gallery">
                                            <i class="icon-settings grey-gallery"></i>
                                            <span class="caption-subject bold uppercase"> Add Inquiry</span>

                                        </div>
                                    </div>
                                    <div class="portlet-body form">
                                        <form role="form" method="POST" action="" name="frmparameter" id="frmparameter" enctype="multipart/form-data">
                                            <input type="hidden" value="AddCreateInquiry" name="action" id="action">
                                            <input type="hidden" value="<?php echo $_REQUEST['token'] ?>" name="customerEntryId" id="customerEntryId">
                                            <div class="form-body">
                                                <div class="row">
                                                    <h4 class="bold text-center">Customer Details</h4>
                                                </div>
                                                <div class="row">
                                                    <?php
                                                    $rowfilter = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `customerentry`  where isDelete='0'  and  istatus='1' and  customerEntryId='" . $_REQUEST['token'] . "'"));
                                                    ?>
                                                    <h4 class="col-md-4 col-md-offset-2"><span class="bold">Name:</span>&nbsp;<?php echo $rowfilter['title'] . ' ' . $rowfilter['firstName'] . ' ' . $rowfilter['MiddleName'] . ' ' . $rowfilter['lastName']; ?> </h4>
                                                    <h4 class="col-md-1"></h4>
                                                    <h4 class="col-md-4"><span class="bold">Mobile No:</span>&nbsp;<?php echo $rowfilter['mobileNo']; ?></h4>
                                                </div>
                                                <hr />
                                                <div class="row">
                                                    <h4 class="bold text-center">Inquiry for Course</h4>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-md-3">
                                                        <label for="form_control_1">Inquiry For *</label>
                                                        <select name="InquiryFor" id="InquiryFor" class="form-control" required="" onclick="return selectInquiryFor();">
                                                            <option value="">Select Inquiry For</option>
                                                            <?php
                                                            $get_source =  mysqli_query($dbconn, "select * from inquiryformaster where isDelete = '0' ");
                                                            while ($result_source = mysqli_fetch_array($get_source)) { ?>

                                                                <option value="<?php echo $result_source['inquiryforName'] ?>"><?php echo $result_source['inquiryforName'] ?></option>
                                                            <?php }
                                                            ?>

                                                        </select>
                                                    </div>
                                                    <?php if ($_SESSION['EmployeeType'] == 'Supervisor') { ?>
                                                        <!--<div class="form-group col-md-3">
                                                            <label for="form_control_1">Select Emplyee*</label>
                                                            <div class="txt_field" id="CityDiv">
                                                                <?php
                                                                $querysBranch = "SELECT * FROM `employeemaster` where isDelete='0' and iEmployeeType!=2 order by  branchid asc";
                                                                $resultsBranch = mysqli_query($dbconn, $querysBranch) or die(mysqli_error($dbconn));
                                                                echo '<select class="form-control" name="EmployeeId" id="EmployeeId" required>';
                                                                echo "<option value='' >Select</option>";
                                                                while ($rowsBranch = mysqli_fetch_array($resultsBranch)) {
                                                                    echo "<option value='" . $rowsBranch['employeeMasterId'] . "'>" . $rowsBranch['employeeName'] . "</option>";
                                                                }
                                                                echo "</select>";
                                                                ?>
                                                            </div>
                                                        </div>-->
                                                    <?php } ?>
                                                    <div class="form-group col-md-3">
                                                        <label for="form_control_1">Select Employee*</label>
                                                        <?php
                                                        $querysBranch = "SELECT * FROM `support_employee` where isDelete='0' order by  support_emp_name asc";
                                                        $resultsBranch = mysqli_query($dbconn, $querysBranch) or die(mysqli_error($dbconn));
                                                        echo '<select class="form-control" name="supportEmployeeId" id="supportEmployeeId">';
                                                        echo "<option value='' >Select</option>";
                                                        while ($rowsBranch = mysqli_fetch_array($resultsBranch)) {
                                                            echo "<option value='" . $rowsBranch['id'] . "'>" . $rowsBranch['support_emp_name'] . "</option>";
                                                        }
                                                        echo "</select>";
                                                        ?>
                                                    </div>

                                                    <!--                                                        <div class="form-group col-md-3" id="TicketsDetail" style="display: none;">
                                                            <label for="form_control_1">Inquiry Type</label>
                                                            <select name="TicketsType" id="TicketsType"  class="form-control"  >
                                                                <option value="">Select Inquiry Type</option>
                                                                <option value="Domestic">Domestic</option>
                                                                <option value="International">International</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-3" id="VisaDetail" style="display: none;">
                                                            <label for="form_control_1">Inquiry Type</label>
                                                            <select name="VisaType" id="VisaType"  class="form-control"  >
                                                                <option value="">Select Inquiry Type</option>
                                                                <option value="Tourist">Tourist</option>
                                                                <option value="Business">Business</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-3" id="ToursDetail" style="display: none;">
                                                            <label for="form_control_1">Inquiry Type</label>
                                                            <select name="ToursType" id="ToursType"  class="form-control" onclick="return selectTourInquiry();" >
                                                                <option value="">Select Inquiry Type</option>
                                                                <option value="Domestic">Domestic</option>
                                                                <option value="International">International</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-3" id="OtherCountry" style="display: none;">
                                                            <label for="form_control_1">Inquiry Detail</label>
                                                            <input name="InquiryCountry" id="InquiryCountry"  class="form-control" placeholder="Enter The Country" type="text" >
                                                        </div>
                                                        <div class="form-group col-md-3" id="OtherDetail" style="display: none;">
                                                            <label for="form_control_1">Inquiry Detail</label>
                                                            <input name="InquiryDetail" id="InquiryDetail"  class="form-control" placeholder="Enter The Inquiry Detail" type="text" >
                                                        </div>
                                                        <div class="form-group col-md-3" id="DomesticState" style="display: none;">
                                                            <label for="form_control_1">Select State</label>

                                                            <?php
                                                            $query = "SELECT * FROM `state`  where isDelete='0'  and  istatus='1' order by  stateName asc";
                                                            $result = mysqli_query($dbconn, $query) or die(mysqli_error($dbconn));
                                                            echo '<select class="form-control" name="State" id="State" >';
                                                            echo "<option value='' >Select State Name</option>";
                                                            while ($row = mysqli_fetch_array($result)) {
                                                                echo "<option value='" . $row['stateName'] . "'>" . $row['stateName'] . "</option>";
                                                            }
                                                            echo "</select>";
                                                            ?>

                                                        </div>-->
                                                </div>
                                                <hr />
                                                <!--                                                    <div class="row">
                                                        <h4 class="bold text-center">Passenger Information</h4>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group col-md-3">
                                                            <label for="form_control_1">No Of Adult</label>
                                                            <input name="NoOfAdult" id="NoOfAdult"  class="form-control" placeholder="Enter The No Of Adult" pattern="[0-9]+" type="text" >
                                                        </div>

                                                        <div class="form-group col-md-3">
                                                            <label for="form_control_1">No Of Child With Bed</label>
                                                            <input name="NoOfChildWithBed" id="NoOfChildWithBed"  class="form-control" placeholder="Enter The No Of Child With Bed" pattern="[0-9]+" type="text" >
                                                        </div>
                                                        <div class="form-group col-md-3">
                                                            <label for="form_control_1">No Of Child No Bed</label>
                                                            <input name="NoOfChildNoBed" id="NoOfChildNoBed"  class="form-control" placeholder="Enter The  No Of Child No Bed" pattern="[0-9]+" type="text" >
                                                        </div>
                                                        <div class="form-group col-md-3">
                                                            <label for="form_control_1">No Of Infant</label>
                                                            <input name="NoOfInfant" id="NoOfInfant"  class="form-control" placeholder="Enter The No Of Infant" pattern="[0-9]+" type="text" >
                                                        </div>

                                                    </div>-->

                                                <div class="row">
                                                    <h4 class="bold text-center">Other Details</h4>
                                                </div>
                                                <div class="row">
                                                    <!--                                                        <div class="form-group col-md-3">
                                                            <label for="form_control_1">Destination*</label>
                                                            <input name="Destination" id="Destination"  class="form-control" placeholder="Enter The Destination" type="text" required="">
                                                        </div>
                                                        <div class="form-group col-md-3">
                                                            <label for="form_control_1">No Of Nights*</label>
                                                            <input name="NoOfNights" id="NoOfNights"  class="form-control" placeholder="Enter The No Of Nights" pattern="[0-9]+" type="text" required="">
                                                        </div>
                                                        <div class="form-group col-md-3">
                                                            <label for="form_control_1"> Date Of Travel From </label>
                                                            <input type="text" id="FormDate" name="FormDate" class="form-control date-picker" placeholder="Enter The Date Of Travel From"/>
                                                        </div>
                                                        <div class="form-group col-md-3">
                                                            <label for="form_control_1">Date Of Travel To</label>
                                                            <input type="text" id="ToDate" name="ToDate" class="form-control date-picker" placeholder="Enter The Next Date Of Travel To"/>
                                                        </div>-->
                                                    <div class="form-group col-md-3">
                                                        <label for="form_control_1">Remarks*</label>
                                                        <input name="Remarks" id="Remarks" class="form-control" placeholder="Enter The Remarks" type="text" required="">
                                                    </div>

                                                    <div class="form-group col-md-3">
                                                        <label for="form_control_1">Select Inquiry Status*</label>
                                                        <?php
                                                        $querysInq = "SELECT * FROM `status`  where isDelete='0'  and  istatus='1' and NOT statusId in ('2','3','4','5') order by  statusId asc";
                                                        $resultsInq = mysqli_query($dbconn, $querysInq) or die(mysqli_error($dbconn));
                                                        echo '<select class="form-control" name="InquiryStatus" id="InquiryStatus" required="">';
                                                        echo "<option value='' >Select Inquiry Status</option>";
                                                        while ($rowsInq = mysqli_fetch_array($resultsInq)) {
                                                            if ($rowsInq['statusId'] == '1')
                                                                echo "<option value='" . $rowsInq['statusId'] . "' selected>" . $rowsInq['statusName'] . "</option>";
                                                            else
                                                                echo "<option value='" . $rowsInq['statusId'] . "' >" . $rowsInq['statusName'] . "</option>";
                                                        }
                                                        echo "</select>";
                                                        ?>
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label for="form_control_1">Select Category Of Inquiry*</label>
                                                        <?php
                                                        $querysInqCat = "SELECT * FROM `categoryofinquiry`  where isDelete='0'  and  istatus='1' order by  id asc";
                                                        $resultsInqCat = mysqli_query($dbconn, $querysInqCat) or die(mysqli_error($dbconn));
                                                        echo '<select class="form-control" name="CategoryOfInquiry" id="CategoryOfInquiry" required="">';
                                                        echo "<option value='' >Select  Category Of Inquiry</option>";
                                                        while ($rowsInqCat = mysqli_fetch_array($resultsInqCat)) {
                                                            echo "<option value='" . $rowsInqCat['id'] . "' >" . $rowsInqCat['COIname'] . "</option>";
                                                        }
                                                        echo "</select>";
                                                        ?>
                                                    </div>

                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group pull-right">
                                                            <input class="btn blue" type="submit" id="Btnmybtn" value="Submit" name="submit">
                                                            <button type="button" class="btn blue " onClick="checkclose();">Cancel</button>
                                                        </div>
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
    </div>

    <?php include_once './footer.php'; ?>
    <script src="<?php echo $web_url; ?>Supervisor/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            //                                                            var date = new Date();
            $("#FormDate").datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                todayHighlight: true,
                //                                                                defaultDate: "now",
                //                                                                endDate: "now",
                //                                                                startDate: date
            });

        });
        $(document).ready(function() {
            //                                                            var date = new Date();
            $("#ToDate").datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                todayHighlight: true,
                //                                                                defaultDate: "now",
                //                                                                endDate: "now",
                //                                                                startDate: date
            });

        });



        function checkclose() {
            window.close();
            //                                                                        window.location.href = '<?php echo $web_url; ?>Supervisor/CustomerEntry.php';
        }

        function selectInquiryFor() {
            var InquiryFor = $('#InquiryFor').val();
            if (InquiryFor == 'Tickets') {
                $('#TicketsDetail').show();
                $('#VisaDetail').hide();
                $('#ToursDetail').hide();
                $('#OtherDetail').hide();
                $('#DomesticState').hide();
                $('#OtherCountry').hide();
            } else if (InquiryFor == 'Visa') {
                $('#VisaDetail').show();
                $('#TicketsDetail').hide();
                $('#ToursDetail').hide();
                $('#OtherDetail').hide();
                $('#DomesticState').hide();
                $('#OtherCountry').hide();
            } else if (InquiryFor == 'Tours') {
                $('#ToursDetail').show();
                $('#TicketsDetail').hide();
                $('#VisaDetail').hide();
                $('#OtherDetail').hide();
                $('#DomesticState').hide();
                $('#OtherCountry').hide();
            } else if (InquiryFor == 'Cruise') {
                $('#OtherDetail').show();
                $('#TicketsDetail').hide();
                $('#VisaDetail').hide();
                $('#ToursDetail').hide();
                $('#DomesticState').hide();
                $('#OtherCountry').hide();
            }
            return false;
        }


        function selectTourInquiry() {
            var ToursType = $('#ToursType').val();
            if (ToursType == 'Domestic') {
                $('#DomesticState').show();
                $('#OtherCountry').hide();

            } else if (ToursType == 'International') {
                $('#DomesticState').hide();
                $('#OtherCountry').show();

            }
            return false;
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
                    //   alert(response);
                    if (response != 0) {
                        $('#loading').css("display", "none");
                        $("#Btnmybtn").attr('disabled', 'disabled');
                        alert('Added Sucessfully.');
                        window.close();
                        //                                                                                    window.location.href = '<?php echo $web_url; ?>Supervisor/CustomerEntry.php';
                    }
                }

            });
        });
    </script>
</body>

</html>