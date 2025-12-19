<?php
ini_set("error_reporting", E_ALL);
include('../common.php');
include('IsLogin.php');
$connect = new connect();
$filterstr = "SELECT *  FROM `usermaster`  where isDelete='0' and iStatus='1' and id='".$_SESSION['AdminId']."'";  
$rowcount = mysqli_query($dbconn, $filterstr);
$row = mysqli_fetch_array($rowcount);
?>
<!DOCTYPE html>
<html lang="en">
<!-- begin::Head -->

<head>
    <title><?php echo $website_name ?> | Fascia Details</title>
    <?php include_once './include.php'; ?>
    <link href="assets/datatables/datatables.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet" type="text/css" />
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <style>
    @media (max-width: 425px) {
        .sm-sc input {
            width: 100% !important;
            padding: 5px;
        }

        .sm-sc select {
            width: 100% !important;
            padding: 5px;
        }

        .jc-sm {
            justify-content: center;
        }
    }
    </style>

</head>
<!-- end::Head -->
<!-- end::Body -->

<body class="m-bg">
    <div id="preloader">
        <div id="status">&nbsp;</div>
    </div>
    <!-- begin:: Page -->
    <div class="m-grid m-grid--hor m-grid--root m-page">
        <!-- begin::Header -->
        <?php include_once './header.php'; ?>
        <div style="display: none; z-index: 10060;" id="loading">
            <img id="loading-image" src="<?php echo $web_url ?>admin/images/loader.gif">
        </div>
        <!-- end::Header -->
        <!-- begin::Body -->
        <div
            class="m-grid__item m-grid__item--fluid m-grid m-grid m-grid--hor m-container m-container--responsive m-container--xxl">
            <div class="m-grid__item m-grid__item--fluid m-grid m-grid--hor-desktop m-grid--desktop m-body">
                <?php include_once './menu.php'; ?>
                <div
                    class="m-grid__item m-grid__item--fluid m-grid m-grid--desktop m-grid--ver-desktop m-body__content">
                    <div class="m-grid__item m-grid__item--fluid m-wrapper">

                        <div class="m-content">
                            <div
                                class="m-portlet m-portlet--brand m-portlet--head-solid-bg m-portlet--head-sm m-portlet--bordered">
                                <div class="m-portlet__head">
                                    <!-- <div class="m-portlet__head-caption">
                                        <div class="m-portlet__head-title">
                                            <h3 class="m-portlet__head-text text-light">List Of User Registration</h3>
                                        </div>
                                    </div> -->
                                    <?php include('exhibitor_tabmenu.php'); ?>
                                </div>

                                <div class="m-portlet__body">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="m-portlet__body">
                                                <div class="table-responsive">
                                                    <div id="registerData">
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
    </div>
    <!-- end:: Page -->
    <!-- begin::Scroll Top -->
    <div class="m-scroll-top m-scroll-top--skin-top" data-toggle="m-scroll-top" data-scroll-offset="500"
        data-scroll-speed="300">
        <i class="la la-arrow-up"></i>
    </div>
    <!-- end::Scroll Top -->
    <?php include_once './footer-js.php'; ?>
    <script src="assets/datatables/datatables.js" type="text/javascript"></script>
    <script src="assets/datatables/table-datatables-responsive.js" type="text/javascript"></script>
    <script>
    function isNumber(evt) {
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;
        return true;
    }
    </script>
    <script>
    function checkclose() {
        window.location.href = '';
    }

	function PageLoadData(Page) {
        var name = $('#name').val();
        //$('#loading').css("display", "block");
        var name = $('#name').val();
        $.ajax({
            type: "POST",
            url: "AjaxExhibitorVendor.php",
            data: {
                action: 'ListFurniture',
                Page: Page,
                name: name
            },
            success: function(msg) {
                // $('#loading').css("display", "none");
                $("#registerData").html(msg);

            },
        });
    } // end of filter
    PageLoadData(1);
    </script>
</body>

</html>
