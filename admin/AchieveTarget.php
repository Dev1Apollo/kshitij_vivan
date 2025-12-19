<?php
error_reporting(E_ALL);
require_once '../common.php';
$connect = new connect();
include('IsLogin.php');
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title><?php echo $ProjectName; ?> | Target </title>
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
                                            <span class="caption-subject bold uppercase" id="listdetail">List of Target</span>
                                        </div>
                                    </div>
                                    <input type="hidden" name="itargetId" id="itargetId" value="<?php echo $_REQUEST['token']; ?>">
                                        <?php
                                    echo $_REQUEST['token'];
//                                    if (!preg_match('/^\d+$/', $_REQUEST['token '])) {
//                                        header('location: index.php');
//                                    }
                                    ?>
                                    
                                    <div class="portlet-body form">
                                        
                                        <!--                                        <form  role="form"  method="POST"  action="" name="frmSearch"  id="frmSearch" enctype="multipart/form-data">
                                                                                    <div class="row m-search-box">
                                                                                        <div class="col-md-12">
                                                                                            <div class="form-group col-md-offset-2 col-md-3">
                                                                                                <select name="targetMonth" id="targetMonth"  class="form-control" required="">
                                                                                                    <option value="">Select Target Month</option>
                                                                                                    <option value="01">January</option>
                                                                                                    <option value="02">February</option>
                                                                                                    <option value="03">March</option>
                                                                                                    <option value="04">April</option>
                                                                                                    <option value="05">May</option>
                                                                                                    <option value="06">June</option>
                                                                                                    <option value="07">July</option>
                                                                                                    <option value="08">August</option>
                                                                                                    <option value="09">September</option>
                                                                                                    <option value="10">October</option>
                                                                                                    <option value="11">November</option>
                                                                                                    <option value="12">December</option>
                                                                                                </select>
                                                                                            </div>
                                                                                            <div class="form-group col-md-3">
                                                                                                <select name="targetYear" id="targetYear"  class="form-control" required="">
                                                                                                    <option value="">Select Target Year</option>
                                        <?php
//                                        for ($i = 2014; $i <= date('Y'); $i++) {
//                                            echo "<option value=" . $i . ">" . $i . "</option>";
//                                        }
                                        ?>
                                                                                                </select>
                                                                                            </div>
                                                                                            <div class="form-group  col-md-2">
                                                                                                <a href="#" class="btn btn-block blue pull-right" onclick="PageLoadData(1);"><i class="fa fa-search"></i>&nbsp;&nbsp;Search</a>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </form>-->
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
        <script src="assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>
        <script type="text/javascript">

            function PageLoadData(Page) {

                var itargetId = $('#itargetId').val();
                $('#loading').css("display", "block");
                $.ajax({
                    type: "POST",
                    url: "<?php echo $web_url; ?>Employee/AjaxCompairTarget.php",
                    data: {action: 'ListUser', Page: Page, itargetId: itargetId},
                    success: function (msg) {
                        $('#nodataFound').hide();
                        document.getElementById("listdetail").innerHTML = "List of View Target";
                        $("#PlaceUsersDataHere").html(msg);
                        $('#loading').css("display", "none");
                    }
                });
            }// end of filter
//                                                            PageLoadData(1);
        </script>
    </body>
</html>