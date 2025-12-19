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
    <title><?php echo $ProjectName; ?> | Job Master </title>

    <link href="<?php echo $web_url; ?>admin/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css"
        rel="stylesheet" type="text/css" />
    <?php include_once './include.php'; ?>
</head>

<body class="page-container-bg-solid page-boxed">
    <?php include_once './header.php'; ?>
    <div style="display: none; z-index: 10060;" id="loading">
        <img id="loading-image" src="<?php echo $web_url; ?>admin/images/loader1.gif">
    </div>
    <div class="page-container">
        <div class="page-content-wrapper">
            <div class="page-content">
                <div class="container">
                    <ul class="page-breadcrumb breadcrumb">
                        <li>
                            <a href="<?php echo $web_url; ?>admin/index.php">Home</a>
                            <i class="fa fa-circle"></i>
                        </li>
                        <li>
                            <span>Job Master</span>
                        </li>
                    </ul>
                    <div class="page-content-inner">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="portlet light ">
                                    <div class="portlet-title">
                                        <div class="caption grey-gallery">
                                            <i class="icon-settings grey-gallery"></i>
                                            <span class="caption-subject bold uppercase" id="editcity">Add Job
                                                Master</span>
                                        </div>
                                    </div>
                                    <div class="portlet-body form">
                                        <form role="form" method="POST" action="" name="frmparameter" id="frmparameter"
                                            enctype="multipart/form-data">
                                            <input type="hidden" value="AddJob" name="action" id="action">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="form_control_1">Company Name</label>
                                                        <select name="iCompanyId" id="iCompanyId" class="form-control"
                                                            required="">
                                                            <option value="">Select Company Name</option>
                                                            <?php
																$filterstr = "SELECT * FROM `company`  where  isDelete='0'  and  iStatus='1'";
																$result = mysqli_query($dbconn, $filterstr);
																while ($row = mysqli_fetch_array($result)) {
																?>
                                                            <option value="<?= $row['id'] ?>">
                                                                <?= $row['strCompanyName'] ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="form_control_1">Job Title </label>
                                                        <input name="strJobTitle" id="strJobTitle" class="form-control"
                                                            placeholder="Enter Job Title" required="" type="text">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="form_control_1">Experience</label>
                                                        <input name="strExperience" id="strExperience"
                                                            class="form-control" placeholder="Enter Experience"
                                                            required="" type="text">
                                                    </div>
                                                </div>
												<div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="form_control_1">Position</label>
                                                        <input name="iPosition" id="iPosition" pattern="[0-9]"
                                                            onkeypress="return isNumber(event)" class="form-control"
                                                            placeholder="Enter Position" required="" type="text">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="form_control_1">Job Category</label>
                                                        <select type="text" value="" name="iJobCategoryId"
                                                            class="form-control" id="iJobCategoryId" required>
                                                            <option value="">Select Job Category</option>
                                                            <?php
																$filterJobCat = mysqli_query($dbconn,"SELECT * FROM `jobcategory` where isDelete=0 and iStatus=1");
																while($rowData = mysqli_fetch_assoc($filterJobCat)){ ?>
                                                            <option value="<?= $rowData['iJobCategoryId']?>">
                                                                <?= $rowData['strJobCategory']?></option>
                                                            <?php }
															?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="form_control_1">End Date</label>
                                                        <input type="text" placeholder="Enter End Date"
                                                            name="strEndDate" class="form-control" id="strEndDate"
                                                            required />
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="form_control_1">Job Description </label>
                                                        <textarea name="strJobDescription" id="strJobDescription"
                                                            class="form-control" placeholder="Enter Job Description"
                                                            required="" type="text">
															</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-actions noborder">
                                                <input class="btn blue " type="submit" id="Btnmybtn" value="Submit"
                                                    name="submit">
                                                <button type="button" class="btn blue"
                                                    onClick="checkclose();">Cancel</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="portlet light ">
                                    <div class="portlet-title">
                                        <div class="caption grey-gallery">
                                            <i class="icon-settings grey-gallery"></i>
                                            <span class="caption-subject bold uppercase">List of Job Name</span>
                                        </div>
                                    </div>
                                    <div class="portlet-body form">
                                        <div class="row m-search-box">
                                            <div class="col-md-12">
                                                <form role="form" method="POST" action="" name="frmSearch"
                                                    id="frmSearch" enctype="multipart/form-data">
                                                    <div class="form-group col-md-3">
                                                        <input type="text" value="" name="Search_Company"
                                                            class="form-control" id="Search_Company"
                                                            placeholder="Search Compant" required />
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <input type="text" value="" name="Search_Txt"
                                                            class="form-control" id="Search_Txt"
                                                            placeholder="Search Job" required />
                                                    </div>

                                                    <div class="form-group col-md-3">
                                                        <select type="text" value="" name="Search_Category"
                                                            class="form-control" id="Search_Category"
                                                            placeholder="Search Job Category" required>
                                                            <option value="">Select Job Category</option>
                                                            <?php
																$filterJobCat = mysqli_query($dbconn,"SELECT * FROM `jobcategory` where isDelete=0 and iStatus=1");
																while($rowData = mysqli_fetch_assoc($filterJobCat)){ ?>
                                                            <option value="<?= $rowData['iJobCategoryId']?>">
                                                                <?= $rowData['strJobCategory']?></option>
                                                            <?php }
															?>
                                                        </select>
                                                    </div>
                                                    <div class="form-actions  col-md-1">
                                                        <a href="#" class="btn blue"
                                                            onclick="PageLoadData(1);">Search</a>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
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
    <?php include_once './footer.php'; ?>
    <script src="<?php echo $web_url; ?>admin/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"
        type="text/javascript"></script>
    <script type="text/javascript">
    function checkclose() {
        window.location.href = '';
    }
    $(document).ready(function() {
        var date = new Date();
        $("#strEndDate").datepicker({
            format: "dd-mm-yyyy",
            autoclose: true,
            todayHighlight: true,
            startDate: date
        });
    });
    $('#frmparameter').submit(function(e) {
        e.preventDefault();
        var $form = $(this);
        $('#loading').css("display", "block");
        $.ajax({
            type: 'POST',
            url: '<?php echo $web_url; ?>admin/querydata.php',
            data: $('#frmparameter').serialize(),
            success: function(response) {
                console.log(response);
                //$("#Btnmybtn").attr('disabled', 'disabled');
                if (response == 1) {
                    $('#loading').css("display", "none");
                    $("#Btnmybtn").attr('disabled', 'disabled');
                    alert('Job Added Sucessfully.');
                    window.location.href = '';
                } else if (response == 2) {
                    $('#loading').css("display", "none");
                    $("#Btnmybtn").attr('disabled', 'disabled');
                    alert('Job Edited Sucessfully.');
                    window.location.href = '';
                } else {
                    $('#loading').css("display", "none");
                    $("#Btnmybtn").attr('disabled', 'disabled');
                    alert('Invalid Request.');
                    window.location.href = '';
                }
            }
        });
    });

    function setEditdata(id) {
        $('#errorDIV').css('display', 'none');
        $('#errorDIV').html('');
        $('#loading').css("display", "block");
        $.ajax({
            type: 'POST',
            url: '<?php echo $web_url; ?>admin/querydata.php',
            data: {
                action: "GetAdminJob",
                ID: id
            },
            success: function(response) {
                document.getElementById("editcity").innerHTML = "EDIT Job ";
                $('#loading').css("display", "none");
                var json = JSON.parse(response);
                $('#iCompanyId').val(json.iCompanyId);
                $('#strJobTitle').val(json.strJobTitle);
                $('#strJobDescription').val(json.strJobDescription);
                $('#strExperience').val(json.strExperience);
                $('#iPosition').val(json.iPosition);

				$('#iJobCategoryId').val(json.iJobCategoryId);
				$('#strEndDate').val(json.strEndDate);
                $('#action').val('EditJob');
                $('<input>').attr('type', 'hidden').attr('name', 'iJobId').attr('value', json.iJobId).attr(
                    'id', 'iJobId').appendTo('#frmparameter');
            }
        });
    }

    function deletedata(task, id) {
        var errMsg = '';
        if (task == 'Delete') {
            errMsg = 'Are you sure to delete?';
        } else if (task == 'Active') {
            errMsg = 'Are you sure to Active!';
        } else if (task == 'Inactive') {
            errMsg = 'Are you sure to Inactive!';
        }

        if (confirm(errMsg)) {
            $('#loading').css("display", "block");
            $.ajax({
                type: "POST",
                url: "<?php echo $web_url; ?>admin/AjaxJobMaster.php",
                data: {
                    action: task,
                    ID: id
                },
                success: function(msg) {
                    $('#loading').css("display", "none");
                    window.location.href = '';
                    return false;
                },
            });
        }
        return false;
    }

    function PageLoadData(Page) {
        var Search_Txt = $('#Search_Txt').val();
		var Search_Company = $('#Search_Company').val();
		var Search_Category = $('#Search_Category').val();
        $('#loading').css("display", "block");
        $.ajax({
            type: "POST",
            url: "<?php echo $web_url; ?>admin/AjaxJobMaster.php",
            data: {
                action: 'ListUser',
                Page: Page,
                Search_Txt: Search_Txt,
				Search_Category: Search_Category,
				Search_Company: Search_Company
            },
            success: function(msg) {
                $("#PlaceUsersDataHere").html(msg);
                $('#loading').css("display", "none");
            },
        });
    } // end of filter
    PageLoadData(1);

    function isNumber(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
    }
    </script>
</body>

</html>
