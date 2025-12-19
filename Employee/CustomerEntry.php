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
        <title><?php echo $ProjectName; ?> | Customer Entry </title>
        <?php include_once './include.php'; ?>
    </head>
    <body class="page-container-bg-solid page-boxed">
        <?php include_once './header.php'; ?>
        <div style="display: none; z-index: 10060;" id="loading">
            <img id="loading-image" src="<?php echo $web_url; ?>Employee/images/loader1.gif">
        </div>
        <div class="page-container">        
            <div class="page-content-wrapper">
                <!--                <div class="page-head">
                                    <div class="container">
                                        <div class="page-title">
                                            <h1>Dashboard
                                                <small>dashboard</small>
                                            </h1>
                                        </div>                    
                                    </div>
                                </div>-->
                <div class="page-content">
                    <div class="container">


                        <div class="page-content-inner">
                            <div class="col-md-2">

                                <?php include_once './menu-lms.php'; ?>

                            </div>
                            <div class="col-md-10">
                                <div class="portlet light ">
                                    <div class="portlet-title">
                                        <div class="caption grey-gallery">
                                            <i class="icon-settings grey-gallery"></i>
                                            <span class="caption-subject bold uppercase" id="listdetail">List of Customer Search</span>
                                        </div>
                                        <a href="<?php echo $web_url; ?>Employee/AddCustomerEntry.php" class="btn blue" style="float: right;" title="Add Employee">ADD Customer Entry</a>
                                    </div>
                                    <div class="portlet-body form">

                                        <form  role="form"  method="POST"  action="" name="frmSearch"  id="frmSearch" enctype="multipart/form-data">
                                            <div class="row m-search-box">
                                                <div class="col-md-12"  >
                                                    <div class="form-group col-md-offset-1 col-md-2">
                                                        <input type="text" value="" name="firstName" class="form-control" id="firstName" placeholder="Search First Name " />
                                                    </div>
                                                    <div class="form-group col-md-2">
                                                        <input type="text" value="" name="lastName" class="form-control" id="lastName" placeholder="Search Last Name " />
                                                    </div>
                                                    <div class="form-group col-md-2">
                                                        <input type="text" value="" name="mobileNo" class="form-control" id="mobileNo" placeholder="Search Mobile No" pattern="[7-9]{1}[0-9]{9}" />
                                                    </div>
                                                    <div class="form-group col-md-2">
                                                        <input type="text" value="" name="email" class="form-control" id="email" placeholder="Search Email ID" />
                                                    </div>
                                                    <div class="form-group  col-md-2">
                                                        <a href="#" class="btn btn-block blue pull-right" onclick="PageLoadData(1);"><i class="fa fa-search"></i>&nbsp;&nbsp;Search</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>


                                        <div id="PlaceUsersDataHere">
                                            <div class="row" id="nodataFound">
                                                <div class="col-lg-12 col-md-12  col-xs-12 col-sm-12 padding-5 bottom-border-verydark">
                                                    <div class="alert alert-info clearfix profile-information padding-all-10 margin-all-0 backgroundDark">
                                                        <h1 class="font-white text-center"> No Data Found ! </h1>
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
        <script type="text/javascript">




            function deletedata(task, id)
            {

                var errMsg = '';
                if (task == 'Delete') {
                    errMsg = 'Are you sure to delete?';
                }
                if (confirm(errMsg)) {
                    $('#loading').css("display", "block");
                    $.ajax({
                        type: "POST",
                        url: "<?php echo $web_url; ?>Employee/AjaxCustomerEntry.php",
                        data: {action: task, ID: id},
                        success: function (msg) {

                            $('#loading').css("display", "none");
                            window.location.href = '';

                            return false;
                        },
                    });
                }
                return false;
            }
            function PageLoadData(Page) {

                var firstName = $('#firstName').val();
                var lastName = $('#lastName').val();
                var mobileNo = $('#mobileNo').val();
                var email = $('#email').val();
                $('#loading').css("display", "block");
                $.ajax({
                    type: "POST",
                    url: "<?php echo $web_url; ?>Employee/AjaxCustomerEntry.php",
                    data: {action: 'ListUser', Page: Page, firstName: firstName, lastName: lastName, mobileNo: mobileNo, email: email},
                    success: function (msg) {
                        $('#nodataFound').hide();
                        document.getElementById("listdetail").innerHTML = "List of Customer Detail";
                        $("#PlaceUsersDataHere").html(msg);
                        $('#loading').css("display", "none");
                    },
                });
            }// end of filter
            //PageLoadData(1);



        </script>
    </body>
</html>