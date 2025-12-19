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
    <title><?php echo $ProjectName; ?> | Company Master </title>
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
                            <span>Company Master</span>
                        </li>
                    </ul>
                    <div class="page-content-inner">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="portlet light ">
                                    <div class="portlet-title">
                                        <div class="caption grey-gallery">
                                            <i class="icon-settings grey-gallery"></i>
                                            <span class="caption-subject bold uppercase" id="editcity">Add Company
                                                Master</span>
                                        </div>
                                    </div>
                                    <div class="portlet-body form">
                                        <form role="form" method="POST" action="" name="frmparameter" id="frmparameter"
                                            enctype="multipart/form-data">
                                            <input type="hidden" value="AddCompany" name="action" id="action">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="form_control_1">Company Name </label>
                                                        <input name="strCompanyName" id="strCompanyName"
                                                            class="form-control" placeholder="Enter Company Name"
                                                            required="" type="text">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="form_control_1">Contact Person Name </label>
                                                        <input name="strContactPerson" id="strContactPerson"
                                                            class="form-control" placeholder="Enter Contact Person Name"
                                                            required="" type="text">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="form_control_1">Mobile</label>
                                                        <input name="strContactNumber" id="strContactNumber"
                                                            pattern="[0-9]{10}" onkeypress="return isNumber(event)"
                                                            maxlength="10" minlength="10" class="form-control"
                                                            placeholder="Enter Mobile" required="" type="text">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="form_control_1">Email </label>
                                                        <input name="strEmail" id="strEmail" class="form-control"
                                                            placeholder="Enter Email" required="" type="email">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="form_control_1">Website</label>
                                                        <input name="strWebsite" id="strWebsite" class="form-control"
                                                            placeholder="Enter Website" required="" type="text">

                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="form_control_1">Desgination </label>
                                                        <input name="strDesgination" id="strDesgination"
                                                            class="form-control" placeholder="Enter Desgination"
                                                            required="" type="text">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="form_control_1">Company Address</label>
                                                        <textarea name="strAddress" id="strAddress" class="form-control"
                                                    placeholder="Enter Address" required="" type="text"></textarea>
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
                                            <span class="caption-subject bold uppercase">List of Company Name</span>
                                        </div>
                                    </div>
                                    <div class="portlet-body form">
                                        <div class="row m-search-box">
                                            <div class="col-md-12">
                                                <form role="form" method="POST" action="" name="frmSearch"
                                                    id="frmSearch" enctype="multipart/form-data">
                                                    <div class="form-group col-md-3">
                                                        <input type="text" value="" name="Search_Txt"
                                                            class="form-control" id="Search_Txt"
                                                            placeholder="Search Company" required />
                                                    </div>
													<div class="form-group col-md-3">
                                                        <input type="text" value="" name="Search_ContactPerson"
                                                            class="form-control" id="Search_ContactPerson"
                                                            placeholder="Search Contact Person" required />
                                                    </div>
													<div class="form-group col-md-2">
                                                        <input type="text" value="" name="Search_Mobile"
                                                            class="form-control" id="Search_Mobile"
                                                            placeholder="Search Mobile" required />
                                                    </div>
													<div class="form-group col-md-2">
                                                        <input type="text" value="" name="Search_Website"
                                                            class="form-control" id="Search_Website"
                                                            placeholder="Search Website" required />
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
    <script type="text/javascript">
    function checkclose() {
        window.location.href = '';
    }

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
                    alert('Company Added Sucessfully.');
                    window.location.href = '';
                } else if (response == 2) {
                    $('#loading').css("display", "none");
                    $("#Btnmybtn").attr('disabled', 'disabled');
                    alert('Company Edited Sucessfully.');
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
                action: "GetAdminCompany",
                ID: id
            },
            success: function(response) {
                document.getElementById("editcity").innerHTML = "EDIT Company ";
                $('#loading').css("display", "none");
                var json = JSON.parse(response);
                $('#strCompanyName').val(json.strCompanyName);
                $('#strContactPerson').val(json.strContactPerson);

                $('#strContactNumber').val(json.strContactNumber);
                $('#strEmail').val(json.strEmail);
                $('#strDesgination').val(json.strDesgination);
				$('#strWebsite').val(json.strWebsite);
				$('#strAddress').val(json.strAddress);
                $('#action').val('EditCompany');
                $('<input>').attr('type', 'hidden').attr('name', 'id').attr('value', json.id).attr('id',
                    'id').appendTo('#frmparameter');
            }
        });
    }

    function deletedata(task, id) {
        var errMsg = '';
        if (task == 'Delete') {
            errMsg = 'Are you sure to delete?';
        }
        if (confirm(errMsg)) {
            $('#loading').css("display", "block");
            $.ajax({
                type: "POST",
                url: "<?php echo $web_url; ?>admin/AjaxCompanyMaster.php",
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
		var Search_ContactPerson = $('#Search_ContactPerson').val();
		var Search_Mobile = $('#Search_Mobile').val();
		var Search_Website = $('#Search_Website').val();
        $('#loading').css("display", "block");
        $.ajax({
            type: "POST",
            url: "<?php echo $web_url; ?>admin/AjaxCompanyMaster.php",
            data: {
                action: 'ListUser',
                Page: Page,
                Search_Txt: Search_Txt,
				Search_ContactPerson: Search_ContactPerson,
				Search_Mobile: Search_Mobile,
				Search_Website: Search_Website
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
