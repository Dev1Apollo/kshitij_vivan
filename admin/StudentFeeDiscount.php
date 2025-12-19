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
        <title><?php echo $ProjectName; ?> | Student Fee Discount </title>
        <?php include_once './include.php'; ?>
        <link href="assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet" type="text/css" />
    </head>
    <body class="page-container-bg-solid page-boxed">
        <?php include_once './header.php'; ?>
        <div style="display: none; z-index: 10060;" id="loading">
            <img id="loading-image" src="<?php echo $web_url; ?>Employee/images/loader1.gif">
        </div>
        <div class="page-container">        
            <div class="page-content-wrapper">
                <div class="page-content">
                    <div class="container">
                        <div class="page-content-inner">
                            <div class="col-md-12" id="studentcoursefrom">
                                <div class="portlet light ">
                                    <div class="portlet-title">
                                        <div class="caption grey-gallery">
                                            <i class="icon-settings grey-gallery"></i>
                                            <span class="caption-subject bold uppercase" id="listdetail">Student Student Fee Discount </span>
                                        </div>
                                    </div>
                                    <div class="portlet-body">
                                        <form  role="form"  method="POST"  action="" name="frmparameter"  id="frmparameter" enctype="multipart/form-data">
                                            <input type="hidden" value="StudentFeeDiscount" name="action" id="action">
                                            <?php
                                            $stud_id = $_REQUEST['token'];
                                            $query = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM studentadmission where stud_id=" . $stud_id));
                                            $stundentData = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `studentcourse` where studentcourseId=" . $_REQUEST['refToken'] . " and studentcourse.istatus=1"));
                                            ?>
                                            <input type="hidden" value="<?php echo $stud_id; ?>" name="stud_id" id="stud_id">
                                            <input type="hidden" value="<?php echo $_REQUEST['refToken']; ?>" name="studentcourseId" id="studentcourseId">

                                            <div class="form-body">
                                                <div id="StudentCourse">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <h4 class="bold text-center">Course Details</h4>
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label for="form_control_1">Portal: </label>
                                                            <span>
                                                                <strong>
                                                                    <?php
                                                                    if ($query['studentPortal_Id'] == 1) {
                                                                        echo 'Maac Satellite';
                                                                    } elseif ($query['studentPortal_Id'] == 2) {
                                                                        echo 'Kshitij Vivan';
                                                                    } elseif ($query['studentPortal_Id'] == 4) {
                                                                        echo 'Maac CG';
                                                                    } else {
                                                                        echo 'Other';
                                                                    }
                                                                    ?>
                                                                </strong>
                                                            </span>
                                                        </div>

                                                        <div class="form-group col-md-4">
                                                            <label for="form_control_1">Student Enrollment: </label>
                                                            <span><strong><?php echo $query['studentEnrollment'] ?></strong></span>
                                                        </div>

                                                        <div class="form-group col-md-4">
                                                            <label for="form_control_1">Course Name: </label>
                                                            <span>
                                                                <strong>
                                                                    <?php
                                                                    $rowdata = mysqli_query($dbconn, "SELECT * FROM `course` where courseId in (" . $stundentData['courseId'] . ") and istatus=1 and isDelete=0 ORDER by courseName ASC");
                                                                    $coureName = '';
                                                                    while ($resultdata = mysqli_fetch_array($rowdata)) {
                                                                        $coureName = $resultdata['courseName'] . ',' . $coureName;
                                                                    }
                                                                    echo $coureName = rtrim($coureName, ',');
                                                                    ?>
                                                                </strong>
                                                            </span>
                                                        </div>
                                                        <input name="offeredfee" id="offeredfee" value="<?php echo $stundentData['offeredfee']; ?>"  type="hidden">
                                                        <?php
                                                        $filterRegFee = "select * from studentfee where stud_id = '" . $stud_id . "' and studentcourseId = " . $_REQUEST['refToken'] . " and feetype = '1'";
                                                        $rowRegFee = mysqli_query($dbconn, $filterRegFee);
                                                        $regFee = 0;
                                                        $i = 0;
                                                        while ($dataRegFee = mysqli_fetch_array($rowRegFee)) {
                                                            $regFee = $dataRegFee['amount'] + $regFee;
                                                            $i++;
                                                        }
                                                        ?>
                                                        <input name="registeredAmount" id="registeredAmount" value="<?php echo $regFee; ?>"  type="hidden">
                                                        <div id="divFeesValue">

                                                        </div>
                                                        <hr />
                                                        <div class="col-md-12">
                                                            <h4 class="bold text-center">Student Fees Discount</h4>
                                                        </div><br><br>
                                                        <hr />
                                                        <div class="form-group col-md-4">
                                                            <label for="form_control_1">Discount Amount</label>
                                                            <input name="DiscountAmount" id="DiscountAmount"  class="form-control" placeholder="Enter The Discount Amount"  type="text" >
                                                        </div>      
                                                        <div class="form-group col-md-4">
                                                            <label for="form_control_1">Discount Comment</label>
                                                            <textarea name="Comment" id="Comment"  class="form-control" placeholder="Enter The Discount Comment"  type="text" ></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="form-actions noborder">
                                                        <input class="btn blue margin-top-20" type="submit" id="Btnmybtn" onclick="finalSubmit();" value="Submit" name="submit">      
                                                        <button type="button" class="btn blue margin-top-20" onClick="checkclose();">Cancel</button>
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

        <?php include_once './footer.php'; ?>
        <script type="text/javascript">

            $(document).ready(function () {
                var stud_id = $('#stud_id').val();
                var studentcourseId = $('#studentcourseId').val();
                var urlp = '<?php echo $web_url; ?>admin/findCourse.php?stud_id=' + stud_id + '&studentcourseId=' + studentcourseId;
                $.ajax({
                    type: 'POST',
                    url: urlp,
                    success: function (dataemi) {
                        $('#divFeesValue').html(dataemi);
                        var valReceived = dataemi.split("##@@##");
                        $('#divFeesValue').html(valReceived[0]);
                        $('#emitypeInputDiv').html(valReceived[1]);
                    }
                }).error(function () {
                    alert('An error occured');
                });
            });


            function checkclose() {
                window.close();
            }

            function finalSubmit() {
                var msg = "Are You Sure To Give Discount.";
                if (confirm(msg)) {
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
                                    alert('Discount Applied Sucessfully.');
                                    response = response.trim();
                                    window.close();
                                } else {
                                    $('#loading').css("display", "none");
                                    $("#Btnmybtn").attr('disabled', 'disabled');
                                    alert('Invalid Request');
                                    response = response.trim();
                                    window.close();
                                }
                            }
                        });
                    });
                    return true;
                } else {
                    $('#StudentCourse').show();
                    $('#StudentDetail').hide();
                    return false;
                }
            }

            $(document).ready(function () {
                $("#DiscountAmount").keydown(function (e) {
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

        </script>
    </body>
</html>