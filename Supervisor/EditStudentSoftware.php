<?php
ob_start();
error_reporting(E_ALL);
require_once '../common.php';
$connect = new connect();
include('IsLogin.php');

$result = mysqli_query($dbconn, "SELECT * FROM `studentcoursedetail` join course on studentcoursedetail.courseId=course.courseId join software on course.courseId=software.courseId where studentcoursedetailid='" . $_REQUEST['token'] . "'");

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_array($result);
    //    print_r($row);
    //    exit;

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
    <title> <?php echo $ProjectName ?> |Edit Customer Entry</title>
    <?php include_once './include.php'; ?>
    <link href="demo/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet" type="text/css" />
</head>

<body class="page-container-bg-solid page-boxed">
    <?php
    include('header.php');
    ?>
    <div style="display: none; z-index: 10060;" id="loading">
        <img id="loading-image" src="<?php echo $web_url; ?>Supervisor/images/loader1.gif">
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
                <ul class="page-breadcrumb breadcrumb">
                    <li>
                        <a href="<?php echo $web_url; ?>Supervisor/index.php">Home</a>
                        <i class="fa fa-circle"></i>
                    </li>


                    <li>
                        <span> Edit Student Course</span>

                    </li>
                </ul>

                <div class="page-content-inner">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="portlet light ">
                                <div class="portlet-title">
                                    <div class="caption grey-gallery">
                                        <i class="icon-settings grey-gallery"></i>
                                        <span class="caption-subject bold uppercase"> Edit Student Course</span>
                                    </div>
                                </div>
                                <div class="portlet-body form">

                                    <div class="portlet-body form">
                                        <form role="form" method="POST" action="" name="frmparameter" id="frmparameter" enctype="multipart/form-data">
                                            <input type="hidden" value="EditStudentSoftware" name="action" id="action">
                                            <input type="hidden" value="<?php echo $row['studentcoursedetailid'] ?>" name="studentcoursedetailid" id="studentcoursedetailid">
                                            <input type="hidden" value="<?php echo $row['stud_id']; ?>" id="stud_id" name="stud_id">
                                            <div class="form-body">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <h4 class="bold text-center">Course Details</h4>
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label for="form_control_1">Course Name</label>

                                                        <select name="cid" id="cid" class="form-control" required="" onchange="getSoftware();">
                                                            <option value="<?php echo $row['courseId'] ?>"><?php echo $row['courseName'] ?></option>
                                                            <?php
                                                            $rowdata = mysqli_query($dbconn, "SELECT * FROM `course` where istatus=1 and isDelete=0");
                                                            while ($resultdata = mysqli_fetch_array($rowdata)) {
                                                            ?>
                                                                <option value="<?php echo $resultdata['courseId'] ?>"><?php echo $resultdata['courseName'] ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label for="form_control_1">Software Name</label>
                                                        <div class="txt_field" id="FeeDiv">
                                                            <select name="softwareId" id="softwareId" class="form-control" required="">
                                                                <option value="<?php echo $row['softwareId'] ?>"><?php echo $row['softwareName'] ?></option>
                                                                <?php
                                                                $rowdata = mysqli_query($dbconn, "SELECT * FROM `software` where istatus=1 and isDelete=0");
                                                                while ($resultdata = mysqli_fetch_array($rowdata)) {
                                                                ?>
                                                                    <option value="<?php echo $resultdata['softwareId'] ?>"><?php echo $resultdata['softwareName'] ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <?php
                                                    //                                                                                                            $rowdata = mysqli_query($dbconn,"SELECT * FROM `course` where istatus=1 and isDelete=0");
                                                    ?>
                                                    <!--                                                                                                            <input name="studentcourseId" id="studentcourseId"  class="form-control" placeholder="Enter The Offered Fee" type="hidden" >-->

                                                    <div class="form-group col-md-3">
                                                        <label for="form_control_1">Software Start Date</label>
                                                        <input name="softStrDate" id="softStrDate" value="<?php echo $row['softStrDate'] ?>" class="form-control date-picker" placeholder="Enter The Last Payment Date" type="text">
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label for="form_control_1">Software End Date</label>
                                                        <div id="errordiv"></div>
                                                        <input name="softEndDate" id="softEndDate" value="<?php echo $row['softEndDate'] ?>" class="form-control date-picker" placeholder="Enter The Date Of Join" type="text">
                                                    </div>
                                                </div>

                                            </div>

                                            <!--</div>-->

                                            <div class="form-actions noborder">
                                                <input class="btn blue margin-top-20" type="submit" id="Btnmybtn" value="Submit" name="submit">
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
    <script src="<?php echo $web_url; ?>Supervisor/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>

    <script type="text/javascript">
        function getSoftware() {
            var q = $('#cid').val();
            //                alert(q);
            var urlp = '<?php echo $web_url; ?>Supervisor/findSoftwhere.php?cid=' + q;
            $.ajax({
                type: 'POST',
                url: urlp,
                success: function(data) {
                    //                        alert(data);
                    $('#FeeDiv').html(data);
                }
            }).error(function() {
                alert('An error occured');
            });

        }

        $(document).ready(function() {
            $("#softStrDate").datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                todayHighlight: true,
                defaultDate: "now",
                //                                                                                            endDate: "now"
            });

        });
        $(document).ready(function() {

            $("#softEndDate").datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                todayHighlight: true,
                defaultDate: "now",
                //                                                                                            endDate: "now"
            });

        });


        function checkclose() {
            window.location.href = '<?php echo $web_url; ?>Supervisor/student-course-details.php';
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
                    alert(response);
                    console.log(response);
                    //$("#Btnmybtn").attr('disabled', 'disabled');
                    //                        alert(response);
                    if (response != 0) {
                        $('#loading').css("display", "none");
                        $("#Btnmybtn").attr('disabled', 'disabled');
                        alert(' Edited Sucessfully.');
                        response = response.trim();
                        window.location.href = '<?php echo $web_url; ?>Supervisor/student-course-details.php?token=' + response;
                    }
                }

            });
        });
    </script>

</body>

</html>