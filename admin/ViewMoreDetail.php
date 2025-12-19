<?php
ob_start();
error_reporting(E_ALL);
require_once '../common.php';
$connect = new connect();
include('IsLogin.php');
if ($_REQUEST['Token'] == 'TF')
    $viewdetailname = "Today's Followup";
else if ($_REQUEST['Token'] == 'TNI')
      $viewdetailname = "Today's New Inquiry";
else if ($_REQUEST['Token'] == 'MI')
      $viewdetailname = "Monthly Inquiry";
else if ($_REQUEST['Token'] == 'TB')
      $viewdetailname = "Today's Booked";
else if ($_REQUEST['Token'] == 'MB')
      $viewdetailname = "Monthly Booked";
else if ($_REQUEST['Token'] == 'TL')
      $viewdetailname = "Today's Lost";
else if ($_REQUEST['Token'] == 'ML')
      $viewdetailname = "Monthly Lost";
else if ($_REQUEST['Token'] == 'odc')
      $viewdetailname = "Over Due Call";
    
    ?>
<!DOCTYPE html>

<html lang="en">

    <head>
        <meta charset="utf-8" />
        <title><?php echo $ProjectName; ?> | <?php echo $viewdetailname;?></title>
        <?php include_once './include.php'; ?>
        <link href="<?php echo $web_url; ?>admin/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet" type="text/css" />

    </head>
    <body class="page-container-bg-solid page-boxed">
        <?php include_once './header.php'; ?>
        <div style="display: none; z-index: 10060;" id="loading">
            <img id="loading-image" src="<?php echo $web_url; ?>admin/images/loader1.gif">
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
                        <ul class="page-breadcrumb breadcrumb">
                            <li>
                                <a href="<?php echo $web_url; ?>admin/index.php">Home</a>
                                <i class="fa fa-circle"></i>
                            </li>

                            <li>
                                <span> <?php echo $viewdetailname;?></span>
                            </li>
                        </ul>

                        <div class="page-content-inner">

                            <div class="col-md-12">
                                <div class="portlet light ">
                                    <div class="portlet-title">
                                        <div class="caption grey-gallery">
                                            <i class="icon-settings grey-gallery"></i>
                                            <span class="caption-subject bold uppercase" >List of <?php echo $viewdetailname;?></span>
                                        </div>
                                         <a class="btn blue pull-right" href="javascript: history.go(-1)">Go Back</a> 
                                        <input type="hidden" name="viewDetails" id="viewDetails" value="<?php echo $_REQUEST['Token']; ?>">

                                    </div>
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

        <?php include_once './footer.php'; ?>
        <script src="<?php echo $web_url; ?>admin/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>

        <script>


            function PageLoadData(Page) {

                var viewDetails = $('#viewDetails').val();
                $('#loading').css("display", "block");
                $.ajax({
                    type: "POST",
                    url: "<?php echo $web_url; ?>admin/AjaxViewDetails.php",
                    data: {action: 'ListUser', Page: Page, viewDetails: viewDetails},
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