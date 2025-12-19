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
    <title><?php echo $ProjectName; ?> |New Inquiry </title>
    <?php include_once './include.php'; ?>
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
                            <?php include_once './menu-lms.php'; ?>
                        </div>
                        <div class="col-md-10">
                            <div class="portlet light ">
                                <div class="portlet-title">
                                    <div class="caption grey-gallery">
                                        <i class="icon-settings grey-gallery"></i>
                                        <span class="caption-subject bold uppercase">List of New Inquiry</span>
                                    </div>
                                </div>
                                <div class="portlet-body form">
                                    <?php if($_SESSION['EmployeeType'] == 'Supervisor'){ ?>
                                    <form role="form" method="POST" action="" name="frmSearch" id="frmSearch" enctype="multipart/form-data">
                                        <div class="row m-search-box">
                                            <div class="col-md-12">
                                                <div class="form-group col-md-2">
                                                    <?php
                                                    $querysBranch = "SELECT * FROM `employeemaster` where isDelete='0' and iEmployeeType!=2 order by  branchid asc";
                                                    $resultsBranch = mysqli_query($dbconn, $querysBranch) or die(mysqli_error($dbconn));
                                                    echo '<select class="form-control" name="employeeMasterId" id="employeeMasterId">';
                                                    echo "<option value='' >Select</option>";
                                                    while ($rowsBranch = mysqli_fetch_array($resultsBranch)) {
                                                        echo "<option value='" . $rowsBranch['employeeMasterId'] . "'>" . $rowsBranch['employeeName'] . "</option>";
                                                    }
                                                    echo "</select>";
                                                    ?>
                                                </div>
                                                <div class="form-group  col-md-2">
                                                    <a href="#" class="btn btn-block blue pull-right" onclick="PageLoadData(1);"><i class="fa fa-search"></i>&nbsp;&nbsp;Search</a>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    <?php } ?>
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
    <script type="text/javascript">
        function PageLoadData(Page) {
            var employeeMasterId = $("#employeeMasterId").val();
            $('#loading').css("display", "block");
            $.ajax({
                type: "POST",
                url: "<?php echo $web_url; ?>Supervisor/AjaxNewInquiry.php",
                data: {
                    action: 'ListUser',
                    Page: Page,
                    employeeMasterId: employeeMasterId
                },
                success: function(msg) {

                    $("#PlaceUsersDataHere").html(msg);
                    $('#loading').css("display", "none");
                },
            });
        } // end of filter
        PageLoadData(1);
    </script>
</body>

</html>