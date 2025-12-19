<?php
ob_start();
error_reporting(E_ALL);
require_once '../common.php';
$connect = new connect();
include('IsLogin.php');

$result = mysqli_query($dbconn,"SELECT * FROM `studentcourse`INNER join course on studentcourse.courseId=course.courseId INNER join emitype on studentcourse.emiType=emitype.emiId WHERE `stud_id`='" . $_REQUEST['token'] . "' and studentcourse.istatus=1 and `studentcourseId`='" . $_REQUEST['studentcourseId'] . "'");
if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_array($result);
} else {
    echo 'somthig going worng! try again';
    exit();
}
$query = mysqli_query($dbconn,"select `studentfeeid`,`stud_id`,`studentcourseId`,`amount` from studentfee where stud_id= '" . $row['stud_id'] . "' and studentcourseId='" . $row['studentcourseId'] . "' and feetype = 'Booking_Amount'");
$data = mysqli_fetch_array($query);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <link rel="shortcut icon" href="images/favicon.png">
        <title> <?php echo $ProjectName ?> |Edit Student Course</title>
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
                            <div class="col-md-2">
                                <?php include_once './menu-admission.php'; ?>
                            </div>
                            <div class="col-md-10">
                                <div class="portlet light ">
                                    <div class="portlet-title">
                                        <div class="caption grey-gallery">
                                            <i class="icon-settings grey-gallery"></i>
                                            <span class="caption-subject bold uppercase"> Edit Student Course</span>
                                        </div>
                                    </div>
                                    <div class="portlet-body form">
                                        <form  role="form"  method="POST"  action="" name="frmparameter"  id="frmparameter" enctype="multipart/form-data">
                                            <input type="hidden" value="EditStudentCourse" name="action" id="action">
                                            <input type="hidden" value="<?php echo $row['studentcourseId']; ?>" name="studentcourseId" id="studentcourseId">
                                            <input type="hidden" value="<?php echo $row['stud_id']; ?>" name="stud_id" id="stud_id">
                                            <input type="hidden" value="<?php echo $data['studentfeeid']; ?>" name="studentfeeid" id="studentfeeid">
                                            <div class="form-body">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <h4 class="bold text-center">Course Details</h4>
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label for="form_control_1">Course Name</label>
                                                        <select name="cid[]" id="cid"  class="form-control" required=""  multiple="" onchange="getfee();"> 
                                                            <?php
                                                            // onchange="getfee();"
//                                                            echo "<option value='' >Select Course Name</option>";
                                                            $rowdata = mysqli_query($dbconn,"SELECT * FROM `course` where istatus=1 and isDelete=0 ORDER by courseName ASC");
                                                            $iCounter = 0;
                                                            while ($resultdata = mysqli_fetch_array($rowdata)) {
//                                                                
                                                                $rowfilter = mysqli_fetch_array(mysqli_query($dbconn,"SELECT * FROM `studentcourse`  where stud_id='" . $_REQUEST['token'] . "' and studentcourse.istatus=1 and studentcourseId='" . $_REQUEST['studentcourseId'] . "'"));
                                                                $cousre = explode(',', $rowfilter['courseId']);
                                                                if ($resultdata['courseId'] == $cousre[$iCounter]) {
                                                                    ?>
                                                                    <option value="<?php echo $resultdata['courseId']; ?>" selected="" ><?php echo $resultdata['courseName'] ?> </option>
                                                                    <?php
                                                                    $iCounter++;
                                                                } else {
                                                                    ?>
                                                                    <option value="<?php echo $resultdata['courseId']; ?>"><?php echo $resultdata['courseName']; ?></option>

                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label for="form_control_1">Fees</label>
                                                        <div class="txt_field" id="FeeDiv" >
                                                            <input name="fee" id="fee" value="<?php echo $row['fee']; ?>" disabled="" class="form-control" placeholder="Enter The fee" type="text" required="" readonly="readonly">
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label for="form_control_1">Offered Fee</label>
                                                        <input name="offeredfee" id="offeredfee" value="<?php echo $row['offeredfee']; ?>" required="" class="form-control" placeholder="Enter The Offered Fee" type="text" onkeypress="getEmiClear();">
                                                    </div>

                                                    <div class="form-group col-md-4">
                                                        <label for="form_control_1">Registered Fees</label>
                                                        <?php
                                                        $filterRegFee = "select *  from  studentfee where stud_id = '" . $row['stud_id'] . "'  and feetype = 'Registration_Amount'";
                                                        $rowRegFee = mysqli_query($dbconn,$filterRegFee);
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
                                                        <label for="form_control_1">Date Of Join*</label><div id="errordiv"></div>
                                                        <input name="dateOfJoining" id="dateOfJoining" required="" value="<?php echo $row['dateOfJoining']; ?>" class="form-control date-picker" placeholder="Enter The Date Of Join"  type="text">
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label for="form_control_1">Joining Amount*</label><div id="errordiv"></div>
                                                        <?php
                                                        $querydata = mysqli_query($dbconn,"select *  from studentemidetail where stud_id = '" . $row['stud_id'] . "' and studentcourseId ='" . $row['studentcourseId'] . "' and studentemidetail.isDelete=0");
                                                        $datajoinamt = mysqli_fetch_array($querydata);
                                                        ?>
                                                        <input name="joinAmount" id="joinAmount" required="" value="<?php echo $datajoinamt['joinAmount']; ?>" class="form-control" placeholder="Enter The Join Amount"  type="text" onkeypress="getEmiClear();">
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label for="form_control_1">Emi Type*</label>
                                                        <select name="emiId" id="emiId"  class="form-control" required="">

                                                            <option value="">Select Emi Type</option>
                                                            <?php
                                                            $rowdata = mysqli_query($dbconn,"SELECT * FROM `emitype`");
                                                            while ($resultdata = mysqli_fetch_array($rowdata)) {
                                                                $rowfilter = mysqli_fetch_array(mysqli_query($dbconn,"SELECT * FROM `studentcourse`  where stud_id='" . $_REQUEST['token'] . "' and studentcourse.istatus=1 and studentcourseId = '" . $_REQUEST['studentcourseId'] . "' "));
                                                                ?>
                                                                <option value=<?php echo $resultdata['emiId']; ?> <?php
                                                                if ($resultdata['emiId'] == $rowfilter['emiType']) {
                                                                    echo 'selected="selected"';
                                                                }
                                                                ?> ><?php echo $resultdata['emiTypeName'] ?>
    <!--                                                                <option value="<?php echo $resultdata['emiId']; ?>"><?php echo $resultdata['emiTypeName']; ?></option>-->
                                                                <?php } ?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label for="form_control_1">Emi Start Date*</label><div id="errordiv"></div>
                                                        <input name="emiStartDate" id="emiStartDate" required="" value="<?php echo $row['emiStartDate']; ?>" class="form-control date-picker" placeholder="Enter The Date Of Join"  type="text">
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label for="form_control_1">No Of Emi*</label>
                                                        <input name="noOfEmi" id="noOfEmi" required="" value="<?php echo $row['noOfEmi']; ?>" class="form-control" placeholder="Enter The Date Of Join" onblur="checkAmount();" type="text">
                                                    </div>
                                                    <div class="form-group col-md-4">
                                                        <label for="form_control_1">Emi Amount*</label><div id="emiAmountDiv"></div>
                                                        <input name="emiAmount" id="emiAmount" value="<?php echo $row['emiAmount']; ?>" required="" class="form-control" placeholder="Enter The Emi Amount" type="text" >

                                                    </div>
                                                    <!--                                                    <div class="form-group col-md-4">
                                                                                                            <label for="form_control_1">Student Status*</label>
                                                                                                            <select name="studentStatus" id="studentStatus"  class="form-control" required="">
                                                                                                                <option value="">Select Student Status</option>
                                                    <?php
                                                    $rowdata = mysqli_query($dbconn,"SELECT * FROM `studentstatus` where studstatusid='" . $row['studentStatus'] . "'");
                                                    while ($resultdata = mysqli_fetch_array($rowdata)) {
                                                        ?>
                                                                                                                                                        <option value="<?php echo $resultdata['studstatusid'] ?>"><?php echo $resultdata['studentStatusName'] ?></option>
                                                    <?php } ?>
                                                                                                            </select>
                                                                                                        </div>-->
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

                                                        function getEmiClear() {
                                                            $('#noOfEmi').val("");
                                                            $('#emiAmount').val("");
                                                        }

                                                        function checkclose() {
                                                            history.go(-1);
                                                            return false;
                                                        }
                                                        $(document).ready(function () {
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
                                                        });

                                                        $(document).ready(function () {
                                                            $("#lastPaymentDate").datepicker({
                                                                format: 'dd-mm-yyyy',
                                                                autoclose: true,
                                                                todayHighlight: true,
                                                                defaultDate: "now",
                                                                //                                                                                            endDate: "now"
                                                            });
                                                            $('#cid').multiselect({
                                                                nonSelectedText: 'Select Course Name',
                                                                includeSelectAllOption: true,
                                                                buttonWidth: '100%',
                                                                maxHeight: 250
                                                            });
                                                        });
                                                        $(document).ready(function () {
                                                            $("#dateOfJoining").datepicker({
                                                                format: 'dd-mm-yyyy',
                                                                autoclose: true,
                                                                todayHighlight: true,
                                                                defaultDate: "now",
                                                                //                                                                                            endDate: "now"
                                                            });
                                                        });
                                                        $(document).ready(function () {
                                                            $("#emiStartDate").datepicker({
                                                                format: 'dd-mm-yyyy',
                                                                autoclose: true,
                                                                todayHighlight: true,
                                                                defaultDate: "now",
                                                                //                                                                                            endDate: "now"
                                                            });
                                                        });
                                                        $(document).ready(function () {
                                                            $("#emiAmount").keydown(function (e) {
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

                                                        $(document).ready(function () {
                                                            $("#noOfEmi").keydown(function (e) {
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
                                                        $(document).ready(function () {
                                                            $("#offeredfee").keydown(function (e) {
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
                                                        $(document).ready(function () {
                                                            $("#joinAmount").keydown(function (e) {
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
                                                            var offeredAmount = $('#offeredfee').val();
                                                            var noOfEmi = $('#noOfEmi').val();
                                                            var joinAmount = $('#joinAmount').val();
                                                            var registeredAmount = $('#registeredAmount').val();
                                                            //                                                                                        
                                                            var EmaiAmount = offeredAmount - registeredAmount - joinAmount;
                                                            EmaiAmount = Math.round(EmaiAmount / noOfEmi);

                                                            $('#emiAmount').val(EmaiAmount);
                                                        }

                                                        $('#frmparameter').submit(function (e) {
                                                            var offeredAmount = $('#offeredfee').val();
                                                            var noOfEmi = $('#noOfEmi').val();
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
                                                                            alert(' Edited Sucessfully.');
                                                                            response = response.trim();
                                                                            history.go(-1);
                                                                            return false;
//                                                                            window.location.href = '<?php echo $web_url; ?>Employee/student-course.php?token=' + response;
                                                                        }
                                                                    }

                                                                });
                                                            } else
                                                            {
                                                                alert("amount is too large or small");
                                                                return false;
                                                            }
                                                        });

    </script>

</body>
</html>
