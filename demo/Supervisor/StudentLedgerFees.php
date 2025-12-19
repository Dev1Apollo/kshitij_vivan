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
    <title><?php echo $ProjectName; ?> | Student Enrollment Fees </title>
    <?php include_once './include.php'; ?>
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
                        <div class="col-md-2">
                            <?php include_once './menu-admission.php'; ?>
                        </div>
                        <div class="col-md-10">
                            <div class="portlet light ">
                                <div class="portlet-title">
                                    <div class="caption grey-gallery">
                                        <i class="icon-settings grey-gallery"></i>
                                        <span class="caption-subject bold uppercase" id="listdetail">Student Enrollment Fees</span>
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <div class="row" style="margin-bottom: 20px">
                                        <?php
                                        $stud_id = $_REQUEST['token'];
                                        $query = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `studentadmission` where stud_id=" . $stud_id));
                                        ?>
                                        <div class="col-md-4">
                                            <h4>Student Name :<?php echo $query['title'] . ' ' . $query['firstName'] . ' ' . $query['middleName'] . ' ' . $query['surName']; ?></h4>
                                        </div>
                                        <div class="col-md-4">
                                            <h4>Mobile No : <?php echo $query['mobileOne']; ?></h4>
                                        </div>
                                        <div class="col-md-4">
                                            <h4>Email ID : <?php echo $query['email']; ?> </h4>
                                        </div>
                                    </div>
                                    <div class="tab-content">
                                        <div class="tab-pane active">
                                            <div class="portlet-body form">
                                                <input type="hidden" value="<?php echo $_REQUEST['token']; ?>" id="stud_id" name="stud_id">
                                                <?php
                                                $course = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `studentcourse` where stud_id=" . $_REQUEST['token'] . " and studentcourse.istatus=1"));
                                                ?>
                                                <input type="hidden" value="<?php echo $course['courseId']; ?>" name="cid[]" id="cid">
                                                <div id="PlaceUsersDataHere">
                                                </div>
                                            </div>
                                            <!--                                                <div class="tab-content">
                                                    <div class="tab-pane active">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="portlet light ">
                                                                    <div class="portlet-title">
                                                                        <div class="caption grey-gallery">
                                                                            <i class="icon-settings grey-gallery"></i>
                                                                            <span class="caption-subject bold uppercase" id="listdetail">List of Student Fees Detail</span>
                                                                        </div>

                                                                        <div class="portlet-body form">
                                                                            <input type="hidden" value="<?php // echo $_REQUEST['token'];   
                                                                                                        ?>" id="stud_id" name="stud_id">
<?php
//                                                                            $course = mysqli_fetch_array(mysqli_query($dbconn,"SELECT * FROM `studentcourse` where stud_id=" . $_REQUEST['token'] . " "));
?>
                                                                            <input type="hidden" value="<?php // echo $course['courseId'];   
                                                                                                        ?>" name="cid[]" id="cid">

                                                                            <div id="PlaceUsersDataHere">
                                                                            </div>
                                                                        </div>


                                                                        <div class="portlet-body form">
                                                                        <div class="row">
                                                                        <form  role="form"  method="POST"   action="" name="frmSearch"  id="frmSearch" enctype="multipart/form-data">
<?php
//                                                                        $fetchcourseDetail = mysqli_query($dbconn,"Select *  from studentcourse where stud_id =" . $stud_id . " and emiType not in(1)");
//
//                                                                        if (mysqli_num_rows($fetchcourseDetail) == 1) {
//
//                                                                            $rowdata = mysqli_fetch_array($fetchcourseDetail);
////                                                                                        echo $rowdata['stud_id'];
////                                                                                        echo $rowdata['courseId'];
//                                                                            
?>
                                                                            <input type="hidden" value="<?php // echo $rowdata['stud_id'];  
                                                                                                        ?>" name="stud_id" id="stud_id">
                                                                            <input type="hidden" value="<?php // echo $rowdata['courseId'];  
                                                                                                        ?>" name="courseName" id="courseName">

<?php // } else {   
?>
                                                                            <div class="col-md-12">
                                                                            <input type="hidden" value="<?php // echo $stud_id;  
                                                                                                        ?>" id="stud_id" name="stud_id">

                                                                            <div class="form-group  col-md-4">
                                                                            <select name="courseName" id="courseName"  class="form-control" required="">
                                                                            <option value="">Select Course Name</option>
<?php
//                                                                            while ($data = mysqli_fetch_array($fetchcourseDetail)) {
//                                                                                $rowdata = mysqli_query($dbconn,"SELECT * FROM `course` where istatus=1 and courseId='" . $data['courseId'] . "' and isDelete=0 ORDER by courseName ASC");
//                                                                                while ($resultdata = mysqli_fetch_array($rowdata)) {
//                                                                                    
?>
                                                                                    <option value="<?php // echo $resultdata['courseId']  
                                                                                                    ?>"><?php // echo $resultdata['courseName']  
                                                                                                        ?></option>
                                                                                    <?php
                                                                                    //                                                                                }
                                                                                    //                                                                            }
                                                                                    ?>
                                                                                </select>
                                                                                </div>
                                                                                <div class="form-group  col-md-2">
                                                                                <a href="#" class="btn btn-block blue pull-right" onclick="PageLoadData(1);"><i class="fa fa-search"></i>&nbsp;&nbsp;Search</a>
                                                                                </div>
                                                                                </div>
<?php // }   
?>
                                                                                </div>
                                                                                </form>

                                                                            <div id="PlaceUsersDataHere">

                                                                            </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>-->
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
    <script src="<?php echo $web_url; ?>Supervisor/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>
    <style>
        .multiselect {
            display: block;
            height: 35px;
            padding: 6px;

            text-align: left !important;
            line-height: 1.42857;
            color: #DFDFDF;
            background-color: #fff;
            background-image: none;
            border: 1px solid #51c6dd !important;
            border-radius: 4px;
            color: #666;
            font-size: 15px;
            font-weight: normal !important;
            text-transform: lowercase;

        }
    </style>
    <link href="demo/assets/bootstrap-multiselect.css" rel="stylesheet" type="text/css" />
    <script src="demo/assets/bootstrap-multiselect.js" type="text/javascript"></script>
    <script>
        function checkclose() {
            window.location.href = '';
        }

        $(document).ready(function() {
            $('#emiType').multiselect({
                nonSelectedText: 'Select Emi Date',
                includeSelectAllOption: true,
                buttonWidth: '100%'
            });
        });
        $(document).ready(function() {
            $("#depositDate").datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                todayHighlight: true,
                defaultDate: "now"
            });
        });
        $(document).ready(function() {
            $("#payDate").datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                todayHighlight: true,
                defaultDate: "now"
            });
        });


        function selectType() {
            var feetype = $('#feetype').val();

            if (feetype == "Join_Amount") {
                $('#emiType').attr('required', false);
            }
            if (feetype == "Emi_Amount") {
                $('#emitypeDiv').show();
            } else {
                $('#emitypeDiv').hide();
            }
        }

        function addpaymentmode() {
            var paymentMode = $('#paymentMode').val();

            if (paymentMode == "Cash") {
                $('#divbankName').hide();
                $('#divchequeNo').hide();
            } else {
                $('#divbankName').show();
                $('#divchequeNo').show();
            }
        }

        function adddeposit() {
            var deposit = $('#deposit').val();

            if (deposit == "No") {
                $('#divtoBank').hide();
                $('#divdepositAmount').hide();
                $('#divdepositDate').hide();
            } else {
                $('#divtoBank').show();
                $('#divdepositAmount').show();
                $('#divdepositDate').show();
            }
        }

        function getcourse() {
            var q = $('#cid').val();
            var stud_id = $('#stud_id').val();
            var urlp = '<?php echo $web_url; ?>Supervisor/findCourse.php?cid=' + q + "&stud_id=" + stud_id;
            $.ajax({
                type: 'POST',
                url: urlp,
                success: function(dataemi) {
                    $('#divFeesValue').html(dataemi);
                    var valReceived = dataemi.split("##@@##");
                    $('#divFeesValue').html(valReceived[0]);
                    $('#emitypeInputDiv').html(valReceived[1]);
                }
            }).error(function() {
                alert('An error occured');
            });
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
                        response = response.trim();
                        window.location.href = '<?php echo $web_url; ?>Supervisor/StudentEnrollmentFees.php?token=' + response;
                    }
                }
            });
        });

        function deletedata(task, id) {
            var errMsg = '';
            if (task == 'Delete') {
                errMsg = 'Are you sure to delete?';
            }
            if (confirm(errMsg)) {
                $('#loading').css("display", "block");
                $.ajax({
                    type: "POST",
                    url: "<?php echo $web_url; ?>Supervisor/AjaxStudentFee.php",
                    data: {
                        action: task,
                        ID: id
                    },
                    success: function(msg) {
                        alert(msg);
                        $('#loading').css("display", "none");
                        window.location.href = '';
                    }
                });
            }
            return false;
        }

        function PageLoadData(Page) {
            var stud_id = $('#stud_id').val();
            var courseName = $('#cid').val();
            $('#loading').css("display", "block");
            $.ajax({
                type: "POST",
                url: "<?php echo $web_url; ?>Supervisor/AjaxStudentFee.php",
                data: {
                    action: 'ListUser',
                    Page: Page,
                    stud_id: stud_id,
                    courseName: courseName
                },
                success: function(msg) {
                    $("#PlaceUsersDataHere").html(msg);
                    $('#loading').css("display", "none");
                }
            });
        }
        PageLoadData(1);

        //        $(document).ready(function (Page) {
        //            var stud_id = $('#stud_id').val();
        //            var courseName = $('#courseName').val();
        //
        //            $('#loading').css("display", "block");
        //            $.ajax({
        //                type: "POST",
        //                url: "<?php // echo $web_url;  
                                ?>Supervisor/AjaxStudentFee.php",
        //                data: {action: 'ListUser', Page: Page, stud_id: stud_id, courseName: courseName},
        //                success: function (msg) {
        //                    $("#PlaceUsersDataHere").html(msg);
        //                    $('#loading').css("display", "none");
        //                }
        //            });
        //        });
        // end of filter
        //        PageLoadData(1);
    </script>
</body>

</html>