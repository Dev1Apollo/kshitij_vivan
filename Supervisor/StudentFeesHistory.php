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
                                        <span class="caption-subject bold uppercase" id="listdetail">Student EMI History</span>
                                    </div>
                                    <a class="btn blue pull-right" href="javascript: history.go(-1)">Go Back</a> &nbsp;
                                    <a class="btn blue pull-right" style="margin-right: 5px !important;" onclick="exportPDFdata();">Print Emi</a>
                                    <a class="btn blue pull-right" style="margin-right: 5px !important;" href="<?php echo $web_url; ?>Supervisor/StudentFeePayHistory.php?token=<?php echo $_REQUEST['token']; ?>&studentcourseId=<?php echo $_REQUEST['studentcourseId']; ?>" title="Fee History"><i class="fa fa-database"></i><strong> Fees History</strong></a>
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
                                            <div class="portlet-body">
                                                <div class="row" id="divFeesValue">
                                                </div>
                                                <hr />
                                                <div class="row" style="margin-bottom: 20px">
                                                    <input type="hidden" name="stud_id" id="stud_id" value="<?php echo $_REQUEST['token']; ?>">
                                                    <input type="hidden" name="studentcourseId" id="studentcourseId" value="<?php echo $_REQUEST['studentcourseId']; ?>">
                                                    <div class="portlet-body form">
                                                        <div id="PlaceUsersDataHere">
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
    <script>
        $(document).ready(function() {
            var stud_id = $('#stud_id').val();
            var studentcourseId = $('#studentcourseId').val();
            var urlp = '<?php echo $web_url; ?>Supervisor/findCourse.php?stud_id=' + stud_id + '&studentcourseId=' + studentcourseId;
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
        });

        function PageLoadData(Page) {
            var stud_id = $('#stud_id').val();
            var studentcourseId = $('#studentcourseId').val();

            $('#loading').css("display", "block");
            $.ajax({
                type: "POST",
                url: "<?php echo $web_url; ?>Supervisor/AjaxEmiHistory.php",
                data: {
                    action: 'ListUser',
                    Page: Page,
                    stud_id: stud_id,
                    studentcourseId: studentcourseId
                },
                success: function(msg) {
                    $("#PlaceUsersDataHere").html(msg);
                    $('#loading').css("display", "none");
                },
            });
        }
        PageLoadData(1);

        function exportPDFdata() {
            var stud_id = $('#stud_id').val();
            var studentcourseId = $('#studentcourseId').val();
            window.open('Student-Emai-Pdf.php?token=' + stud_id + '&studentcourseId=' + studentcourseId, target = '_blank');
        }
    </script>
</body>

</html>