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
        <title><?php echo $ProjectName; ?> | Student Admission </title>
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
                            <div class="col-md-12">
                                <div class="portlet light ">
                                    <div class="portlet-title">
                                        <div class="caption grey-gallery">
                                            <i class="icon-settings grey-gallery"></i>
                                            <span class="caption-subject bold uppercase" id="listdetail">Manage Student</span>
                                        </div>
                                    </div>
                                    <div class="portlet-body">

                                        <div class="row" style="margin-bottom: 20px">
                                            <?php
                                            $stud_id = $_REQUEST['token'];
                                            $filterStdent = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `studentadmission` where stud_id=" . $stud_id));
                                            $filterCourse = mysqli_fetch_array(mysqli_query($dbconn, "SELECT studentcourseId FROM studentcourse where studentcourseId='" . $_REQUEST['refToken'] . "' and studentcourse.istatus=1"));
                                            ?>
                                            <div class="col-md-4">
                                                <h4>Student Name :<?php echo $filterStdent['title'] . ' ' . $filterStdent['firstName'] . ' ' . $filterStdent['middleName'] . ' ' . $filterStdent['surName']; ?></h4>
                                            </div>
                                            <div class="col-md-4">
                                                <h4>Mobile No : <?php echo $filterStdent['mobileOne']; ?></h4>
                                            </div>
                                            <div class="col-md-4">
                                                <h4>Email ID : <?php echo $filterStdent['email']; ?> </h4>
                                            </div>
                                        </div>
                                        <div class="portlet-body">
                                            <div class="tabbable-custom nav-justified">
                                                <ul class="nav nav-tabs nav-justified">
                                                    <?php
//                                                if (isset($_REQUEST['token'])){
                                                    ?>
                                                    <li>
                                                        <a href="EditStudent.php?token=<?php echo $stud_id; ?>" > Student Edit </a>
                                                    </li>
                                                    <li class="active">

                                                        <a href="MoveStudent.php?token=<?php echo $stud_id; ?>&refToken=<?php echo $filterCourse['studentcourseId'] ?>" > Student Transfer </a>
                                                    </li>
                                                    <li>
                                                        <a href="EditEmi.php?token=<?php echo $stud_id; ?>&refToken=<?php echo $filterCourse['studentcourseId'] ?>" > Edit Emi </a>
                                                    </li>
                                                    <?php // }  ?>
                                                </ul>
                                                <div class="tab-content">
                                                    <div class="tab-pane active" >
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="portlet light ">
                                                                    <div class="portlet-title">
                                                                        <div class="caption grey-gallery">
                                                                            <i class="icon-settings grey-gallery"></i>
                                                                            <span class="caption-subject bold uppercase" id="listdetail">List of Student Course Detail</span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="portlet-body form">
                                                                        <form  role="form"  method="POST"  action="" name="frmparameter"  id="frmparameter" enctype="multipart/form-data">
                                                                            <input type="hidden" value="studentMove" name="action" id="action">
                                                                            <input type="hidden" value="<?php echo $_REQUEST['token'] ?>" name="stud_id" id="stud_id">
                                                                            <input type="hidden" value="<?php echo $_REQUEST['refToken'] ?>" name="studentcourseId" id="studentcourseId">
                                                                            <div class="form-body">
                                                                                <div class="form-group col-md-offset-1 col-md-3">
                                                                                    <label for="form_control_1">Student Portal</label>
                                                                                    <select name="oldPortalId" id="oldPortalId" disabled="" class="form-control" required="">
                                                                                        <option value="0">Select Option</option>
                                                                                        <option value="1" <?php
                                                                                        if ($filterStdent['studentPortal_Id'] == 1) {
                                                                                            echo 'selected';
                                                                                        } else {
                                                                                            echo "";
                                                                                        }
                                                                                        ?>>Maac Satellite</option>
                                                                                        <option value="4" <?php
                                                                                        if ($filterStdent['studentPortal_Id'] == 4) {
                                                                                            echo 'selected';
                                                                                        } else {
                                                                                            echo "";
                                                                                        }
                                                                                        ?>>Maac CG</option>
                                                                                        <option value="2" <?php
                                                                                        if ($filterStdent['studentPortal_Id'] == 2) {
                                                                                            echo 'selected';
                                                                                        } else {
                                                                                            echo "";
                                                                                        }
                                                                                        ?>>Kshitij Vivan</option>                                                                                                          
                                                                                        <option value="3" <?php
                                                                                        if ($filterStdent['studentPortal_Id'] == 3) {
                                                                                            echo 'selected';
                                                                                        } else {
                                                                                            echo "";
                                                                                        }
                                                                                        ?>>Other</option>
                                                                                    </select>
                                                                                </div>
                                                                                <div class="form-group col-md-3">
                                                                                    <label for="form_control_1">Move To Student Portal</label>
                                                                                    <select name="studentPortal_Id" id="studentPortal_Id"  class="form-control" required="">
                                                                                        <option value="">Select Portal</option>
                                                                                        <option value="1">Maac Satellite</option>
                                                                                        <option value="4">Maac CG</option>
                                                                                        <option value="2">Kshitij Vivan</option>                                                                                                          
                                                                                        <option value="3">Other</option>
                                                                                    </select>
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

                                                    function checkclose() {
//                                                        window.close();
                                                        window.location.href = '<?php echo $web_url; ?>admin/StudentList.php';
                                                    }

                                                    $('#frmparameter').submit(function (e) {
                                                        var studentcourseId = $('#studentcourseId').val();
                                                        var stud_id = $('#stud_id').val();
                                                        e.preventDefault();
                                                        var $form = $(this);
                                                        $('#loading').css("display", "block");
                                                        $.ajax({
                                                            type: 'POST',
                                                            url: 'querydataStudent.php',
                                                            data: $('#frmparameter').serialize(),
                                                            success: function (response) {
                                                                if (response == 1)
                                                                {
                                                                    $('#loading').css("display", "none");
                                                                    $("#Btnmybtn").attr('disabled', 'disabled');
                                                                    alert('Moved Sucessfully.');
                                                                    window.location.href = '<?php echo $web_url; ?>admin/MoveStudent.php?token='+stud_id+'&refToken='+studentcourseId;
                                                                } else {
                                                                    $('#loading').css("display", "none");
                                                                    $("#Btnmybtn").attr('disabled', 'disabled');
                                                                    alert('Something Wrong.');
                                                                    window.location.href = '<?php echo $web_url; ?>admin/MoveStudent.php?token='+stud_id+'&refToken='+studentcourseId;
                                                                }
                                                            }
                                                        });
                                                    });

        </script>
    </body>
</html>