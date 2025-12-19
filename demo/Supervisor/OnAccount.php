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
    <title><?php echo $ProjectName; ?> | Other Fees</title>
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
                                        <span class="caption-subject bold uppercase" id="listdetail">List Of Students</span>
                                    </div>
                                    <!--                                        <a href="<?php echo $web_url; ?>Supervisor/SearchLeadStudent.php" class="btn blue" style="float: right;" title="Add New Admission">ADD New Admission</a>-->
                                </div>
                                <div class="portlet-body form">
                                    <form role="form" method="POST" action="" name="frmSearch" id="frmSearch" enctype="multipart/form-data">
                                        <div class="row m-search-box">
                                            <div class="col-md-12">
                                                <?php if ($_SESSION['EmployeeType'] == 'Supervisor') { ?>
                                                <div class="form-group  col-md-2">
                                                    <input type="text" value="" name="firstName" class="form-control" id="firstName" placeholder="Search First Name " />
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <input type="text" value="" name="surName" class="form-control" id="surName" placeholder="Search Sur Name " />
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <input type="text" value="" name="leaduniqueid" class="form-control" id="leaduniqueid" placeholder="Search Lead Unique Id" />
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <?php
                                                    $querysBranch = "SELECT * FROM `branchmaster` where isDelete='0' order by  branchid asc";
                                                    $resultsBranch = mysqli_query($dbconn, $querysBranch) or die(mysqli_error($dbconn));
                                                    echo '<select class="form-control" name="branchid" id="branchid">';
                                                    echo "<option value='' >Select</option>";
                                                    while ($rowsBranch = mysqli_fetch_array($resultsBranch)) {
                                                        echo "<option value='" . $rowsBranch['branchid'] . "'>" . $rowsBranch['branchname'] . "</option>";
                                                    }
                                                    echo "</select>";
                                                    ?>
                                                </div>
                                                <?php } else { ?>
                                                <div class="form-group  col-md-3">
                                                    <input type="text" value="" name="firstName" class="form-control" id="firstName" placeholder="Search First Name " />
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <input type="text" value="" name="surName" class="form-control" id="surName" placeholder="Search Sur Name " />
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <input type="text" value="" name="leaduniqueid" class="form-control" id="leaduniqueid" placeholder="Search Lead Unique Id" />
                                                </div>
                                                <?php } ?>
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

    <?php include_once './footer.php'; ?>
    <script src="<?php echo $web_url; ?>Supervisor/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>
    <script type="text/javascript">
        function deletedata(task, id) {
            var errMsg = '';
            if (task == 'Delete') {
                errMsg = 'Are you sure to delete?';
            }
            if (confirm(errMsg)) {
                $('#loading').css("display", "block");
                $.ajax({
                    type: "POST",
                    url: "<?php echo $web_url; ?>Supervisor/AjaxOnAccount.php",
                    data: {
                        action: task,
                        ID: id
                    },
                    success: function(msg) {
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
            var leaduniqueid = $('#leaduniqueid').val();
            var branchid = $("#branchid").val();
            if (leaduniqueid == '' && surName == '' && firstName == '') {
                alert("Plase Select any one");
                return false;
            }
            $('#loading').css("display", "block");
            $.ajax({
                type: "POST",
                url: "<?php echo $web_url; ?>Supervisor/AjaxOnAccount.php",
                data: {
                    action: 'ListUser',
                    Page: Page,
                    firstName: firstName,
                    surName: surName,
                    leaduniqueid: leaduniqueid,
                    branchid: branchid
                },
                success: function(msg) {
                    $("#PlaceUsersDataHere").html(msg);
                    $('#loading').css("display", "none");
                }
            });
        } // end of filter
        //                                                            PageLoadData(1);
    </script>
</body>

</html>