<?php
ob_start();
error_reporting(E_ALL);
require_once '../common.php';
$connect = new connect();
include('IsLogin.php');

$result = mysqli_query($dbconn,"SELECT * FROM target WHERE `itargetId`='" . $_REQUEST['token'] . "'");
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
        <title> <?php echo $ProjectName ?> |Edit Target</title>
        <?php include_once './include.php'; ?>       
    </head>

    <body class="page-container-bg-solid page-boxed">
        <?php
        include('header.php');
        ?>
        <div style="display: none; z-index: 10060;" id="loading">
            <img id="loading-image" src="<?php echo $web_url; ?>Employee/images/loader1.gif">
        </div>
        <div class="page-container">        
            <!--            <div class="page-content-wrapper">
                            <div class="page-head">
                                <div class="container">
                                    <div class="page-title">
                                        <h1>Edit Category
                                        </h1>
                                    </div>
                                </div>
                            </div>-->
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
                                            <span class="caption-subject bold uppercase"> Edit Target</span>
                                        </div>
                                    </div>
                                    <div class="portlet-body form">


                                        <form  role="form"  method="POST"  action="" name="frmparameter"  id="frmparameter" enctype="multipart/form-data">
                                            <input type="hidden" value="EditTarget" name="action" id="action">
                                            <input type="hidden" value="<?php echo $_REQUEST['token'] ?>" name="itargetId" id="itargetId">
                                            
                                            <div class="form-body">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <h4 class="bold text-center">Target Details</h4>
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label for="form_control_1">Inquiry / Walking *</label>
                                                        <input value="<?php echo $row['targetInquiry'];?>" name="targetInquiry" id="targetInquiry"  class="form-control" placeholder="Enter The Inquiy" type="text" required="">
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                            <label for="form_control_1">Enrollment *</label><div id="errordiv"></div>
                                                            <input name="targetEnroll" id="targetEnroll" value="<?php echo $row['targetEnroll'];?>" class="form-control" placeholder="Enter The Enrollment"  type="text" required="">
                                                        </div>
                                                    <div class="form-group col-md-3">
                                                        <label for="form_control_1">Booking *</label>
                                                        <input value="<?php echo $row['targetBooking'];?>" name="targetBooking" id="targetBooking"  class="form-control" placeholder="Enter The Booking Amount" type="text" required="">
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label for="form_control_1">Collection *</label>
                                                        <input value="<?php echo $row['targetCollection'];?>" name="targetCollection" id="targetCollection"  class="form-control" placeholder="Enter The Collection Amount" type="text" required="">
                                                    </div>
                                                    
                                                    <div class="form-group col-md-3">
                                                        <label for="form_control_1">FPS *</label><div id="errordiv"></div>
                                                        <input value="<?php echo $row['targetFPS'];?>" name="targetFPS" id="targetFPS"  class="form-control" placeholder="Enter The FPS"  type="text" required="">
                                                    </div>
<!--                                                        <div class="form-group col-md-3">
                                                            <label for="form_control_1">Month</label>
                                                            <input name="targetMonth" id="targetMonth"  class="form-control" placeholder="Enter The Month"  type="text" required="">
                                                        </div>
                                                        <div class="form-group col-md-3">
                                                            <label for="form_control_1">Year</label>
                                                            <input name="targetYear" id="targetYear"  class="form-control" placeholder="Enter The Year" type="text" required="">
                                                        </div>-->
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
    <script type="text/javascript">

        function checkclose() {
            window.location.href = '<?php echo $web_url; ?>Employee/EmployeeTarget.php';
        }

        $('#frmparameter').submit(function (e) {

            e.preventDefault();
            var $form = $(this);
            $('#loading').css("display", "block");
            $.ajax({
                type: 'POST',
                url: '<?php echo $web_url; ?>Employee/querydata.php',
                data: $('#frmparameter').serialize(),
                success: function (response) {
                    console.log(response);
                    //$("#Btnmybtn").attr('disabled', 'disabled');
                    if (response != 0)
                    {
                        $('#loading').css("display", "none");
                        $("#Btnmybtn").attr('disabled', 'disabled');
                        alert(' Edited Sucessfully.');
                        response = response.trim();
                            window.location.href = '<?php echo $web_url; ?>Employee/EmployeeTarget.php';
                    }
                }

            });
        });

    </script>

</body>
</html>
