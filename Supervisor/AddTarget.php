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
    <title><?php echo $ProjectName; ?> | Add Target</title>
    <style>
        .ui-datepicker-calendar {
            display: none;
        }
    </style>
    <?php include_once 'include.php'; ?>
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
                        <div class="row">
                            <div class="col-md-2">
                                <?php include_once './menu-lms.php'; ?>
                            </div>
                            <div class="col-md-10">
                                <div class="portlet light ">
                                    <div class="portlet-title">
                                        <div class="caption grey-gallery">
                                            <i class="icon-settings grey-gallery"></i>
                                            <span class="caption-subject bold uppercase"> Add Target</span>
                                        </div>
                                    </div>
                                    <div class="portlet-body form">
                                        <form role="form" method="POST" action="" name="frmparameter" id="frmparameter" enctype="multipart/form-data">
                                            <input type="hidden" value="AddTarget" name="action" id="action">
                                            <div class="form-body">
                                                <div class="row">
                                                    <div class="form-group row">
                                                        <label for="staticEmail" class="col-md-2 col-md-offset-1 col-form-label">Month</label>
                                                        <div class="col-md-4">
                                                            <select name="targetMonth" id="targetMonth" class="form-control" required="">
                                                                <option value="">Select Month</option>
                                                                <option value="01">January</option>
                                                                <option value="02">February</option>
                                                                <option value="03">March</option>
                                                                <option value="04">April</option>
                                                                <option value="05">May</option>
                                                                <option value="06">June</option>
                                                                <option value="07">July</option>
                                                                <option value="08">August</option>
                                                                <option value="09">September</option>
                                                                <option value="10">October</option>
                                                                <option value="11">November</option>
                                                                <option value="12">December</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="staticEmail" class="col-md-2 col-md-offset-1 col-form-label">Year</label>
                                                        <div class="col-md-4">
                                                            <select name="targetYear" id="targetYear" class="form-control" required="">
                                                                <option value="">Select Year</option>
                                                                <?php
                                                                for ($i = 2014; $i <= date('Y'); $i++) {
                                                                    echo "<option value=" . $i . ">" . $i . "</option>";
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="staticEmail" class="col-md-2 col-md-offset-1 col-form-label">Inquiry / Walking *</label>
                                                        <div class="col-md-4">
                                                            <input name="targetInquiry" id="targetInquiry" class="form-control" placeholder="Enter The Inquiy" type="text" required="">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="staticEmail" class="col-md-2 col-md-offset-1 col-form-label">Enrollment *</label>
                                                        <div id="errordiv"></div>
                                                        <div class="col-md-4">
                                                            <input name="targetEnroll" id="targetEnroll" class="form-control" placeholder="Enter The Enrollment" type="text" required="">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="staticEmail" class="col-md-2 col-md-offset-1 col-form-label">Booking *</label>
                                                        <div class="col-md-4">
                                                            <input name="targetBooking" id="targetBooking" class="form-control" placeholder="Enter The Booking Amount" type="text" required="">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="staticEmail" class="col-md-2 col-md-offset-1 col-form-label">Collection *</label>
                                                        <div class="col-md-4">
                                                            <input name="targetCollection" id="targetCollection" class="form-control" placeholder="Enter The Collection Amount" type="text" required="">
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label for="staticEmail" class="col-md-2 col-md-offset-1 col-form-label">FPS *</label>
                                                        <div id="errordiv"></div>
                                                        <div class="col-md-4">
                                                            <input name="targetFPS" id="targetFPS" class="form-control" placeholder="Enter The FPS" type="text" required="">
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label for="Branch" class="col-md-2 col-md-offset-1 col-form-label">Branch *</label>

                                                        <div class="col-md-4">
                                                            <select class="form-control" name="branch" id="branch">
                                                                <option value="">Select Branch</option>
                                                                <?php
                                                                $filterBranch = mysqli_query($dbconn, "SELECT * FROM `branchmaster`  where isDelete='0' order by  branchid asc");
                                                                while ($rowsBranch = mysqli_fetch_array($filterBranch)) {
                                                                    echo "<option value='" . $rowsBranch['branchid'] . "'>" . $rowsBranch['branchname'] . "</option>";
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-actions noborder">
                                                <input class="btn blue margin-top-20 col-md-offset-3" type="submit" id="Btnmybtn" value="Submit" name="submit">
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
    <script src="demo/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>
    <script type="text/javascript">
        function checkclose() {
            window.location.href = '<?php echo $web_url; ?>Supervisor/AddTarget.php';
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
                        //response = response.trim();
                        window.location.href = '<?php echo $web_url; ?>Supervisor/EmployeeTarget.php';
                    } else if (response == 0) {
                        $('#loading').css("display", "none");
                        $("#Btnmybtn").attr('disabled', 'disabled');
                        alert('Target already Exists.');
                        window.location.href = '<?php echo $web_url; ?>Supervisor/EmployeeTarget.php';
                    } else {
                        $('#loading').css("display", "none");
                        $("#Btnmybtn").attr('disabled', 'disabled');
                        alert('Invalid Request');
                        window.location.href = '<?php echo $web_url; ?>Supervisor/AddTarget.php';
                    }
                }
            });
        });
    </script>
</body>

</html>