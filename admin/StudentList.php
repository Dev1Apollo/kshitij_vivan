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
        <title><?php echo $ProjectName; ?> | Student </title>
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
                                            <span class="caption-subject bold uppercase" id="listdetail">List of Enrollment</span>
                                        </div>
                                        <div id="admission">
                                        <!--<a href="<?php echo $web_url; ?>Employee/AddNewAdmission.php" class="btn blue" style="float: right;" title="Add New Admission">ADD New Enrollment</a>-->
                                        </div>
                                    </div>
                                    <div class="portlet-body form">
                                        <form  role="form"  method="POST"  action="" name="frmSearch"  id="frmSearch" enctype="multipart/form-data">
                                            <div class="row m-search-box">
                                                <div class="col-md-12"  >
                                                    <div class="form-group  col-md-3">
                                                        <input type="text" value="" name="firstName" class="form-control" id="firstName" placeholder="Search First Name " />
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <input type="text" value="" name="surName" class="form-control" id="surName" placeholder="Search Sur Name " />
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <input type="text" value="" name="studentPortal_Id" class="form-control" id="studentPortal_Id" placeholder="Search Student Portal ID" />
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <select id="student" name="student" class="form-control" onchange="newAdmission();">
                                                            <option value="Registered_Student">Registered Student</option>
                                                            <option value="Enroll_Student">Enrolled Student</option> 
                                                        </select>
                                                    </div>                                                 
                                                    <div class="form-group  col-md-2">
                                                        <a href="#" class="btn btn-block blue pull-right" onclick="PageLoadData(1);"><i class="fa fa-search"></i>&nbsp;&nbsp;Search</a>
                                                    </div>
                                                </div>
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
        </div>

        <div class="modal fade" id="MoveStudent" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Move Student</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Recipient:</label>
                                <input type="text" class="form-control" id="recipient-name">
                            </div>
                            <div class="form-group">
                                <label for="message-text" class="col-form-label">Message:</label>
                                <textarea class="form-control" id="message-text"></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Send message</button>
                    </div>
                </div>
            </div>
        </div>

        <?php include_once './footer.php'; ?>
        <script src="<?php echo $web_url; ?>admin/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>
        <script type="text/javascript">

                                                            $(document).ready(function () {
                                                                $("#FormDate").datepicker({
                                                                    format: 'dd-mm-yyyy',
                                                                    autoclose: true,
                                                                    todayHighlight: true,
                                                                    defaultDate: "now",
                                                                    endDate: "now"
                                                                });
                                                            });

                                                            $(document).ready(function () {
                                                                $("#ToDate").datepicker({
                                                                    format: 'dd-mm-yyyy',
                                                                    autoclose: true,
                                                                    todayHighlight: true,
                                                                    defaultDate: "now",
                                                                    endDate: "now"
                                                                });
                                                            });

                                                            function newAdmission() {
                                                                var student = $('#student').val();
                                                                if (student == 'Enroll_Student')
                                                                {
                                                                    $('#admission').show();
                                                                } else if (student == 'Registered_Student')
                                                                {
                                                                    $('#admission').hide();
                                                                } else
                                                                {
                                                                    $('#admission').show();
                                                                }
                                                            }

                                                            function deletedata(task, leadId, stud_id) {
                                                                $('#loading').show();
                                                                var errMsg = '';
                                                                if (task == 'Delete') {
                                                                    errMsg = 'Are you sure to delete?';
                                                                }
                                                                if (confirm(errMsg)) {
                                                                    $('#loading').css("display", "block");
                                                                    $.ajax({
                                                                        type: "POST",
                                                                        url: "<?php echo $web_url; ?>admin/AjaxStudentEntry.php",
                                                                        data: {action: task, leadId: leadId, stud_id: stud_id},
                                                                        success: function (msg) {
                                                                            alert(msg);
                                                                            $('#loading').css("display", "none");
                                                                            window.location.href = '';
                                                                            return false;
                                                                        }
                                                                    });
                                                                }
                                                                return false;
                                                            }
                                                            function PageLoadData(Page) {
                                                                var firstName = $('#firstName').val();
                                                                var surName = $('#surName').val();
                                                                var studentPortal_Id = $('#studentPortal_Id').val();
                                                                var student = $('#student').val();
                                                                if (student == '')
                                                                {
                                                                    alert("Plase Select Student Type");
                                                                }
                                                                $('#loading').css("display", "block");
                                                                $.ajax({
                                                                    type: "POST",
                                                                    url: "<?php echo $web_url; ?>admin/AjaxStudentEntry.php",
                                                                    data: {action: 'ListUser', Page: Page, firstName: firstName, surName: surName, studentPortal_Id: studentPortal_Id, student: student},
                                                                    success: function (msg) {

                                                                        $("#PlaceUsersDataHere").html(msg);
                                                                        $('#loading').css("display", "none");
                                                                    },
                                                                });
                                                            }// end of filter
                                                            PageLoadData(1);

        </script>
    </body>
</html>