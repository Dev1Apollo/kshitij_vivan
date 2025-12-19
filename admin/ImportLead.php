<?php
ob_start();
error_reporting(0);
require_once '../common.php';
$connect = new connect();
include('IsLogin.php');
?>
<!DOCTYPE html>

<html lang="en">

    <head>
        <meta charset="utf-8" />
        <title><?php echo $ProjectName; ?> | Import Lead </title>
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
                                            <span class="caption-subject bold uppercase" > Import Lead </span>
                                        </div>
                                    </div>
                                    <div class="portlet-body form">
                                        <div class="row m-search-box">
                                            <div class="col-md-12">
                                                <form  role="form"  method="POST"  action="" name="frmparameter"  id="frmparameter" enctype="multipart/form-data">
                                                    <input type="hidden" value="ImportExcelData" name="action" id="action"> 
                                                    <div class="row">
                                                        <div class="form-group col-md-3">
                                                            <label for="exampleInputFile1">Branch *</label><br />
                                                            <?php
                                                            $querysBranch = "SELECT * FROM `branchmaster`  where isDelete='0' order by branchid asc";
                                                            $resultsBranch = mysqli_query($dbconn,$querysBranch) or die(mysqli_error($dbconn));
                                                            echo '<select class="form-control" name="branchid" id="branchid" onchange="getEmployee();" required>';
                                                            echo "<option value='' >Select Branch</option>";
                                                            while ($rowsBranch = mysqli_fetch_array($resultsBranch)) {
                                                                echo "<option value='" . $rowsBranch['branchid'] . "'>" . $rowsBranch['branchname'] . "</option>";
                                                            }
                                                            echo "</select>";
                                                            ?>
                                                        </div>
                                                        <div class="form-group col-md-3">
                                                            <label for="exampleInputFile1">Employee *</label><br />
                                                            <?php
                                                            $querysBranch = "SELECT * FROM `employeemaster`  where isDelete='0' and employeeReportTo=1 order by employeeMasterId asc";
                                                            $resultsBranch = mysqli_query($dbconn,$querysBranch) or die(mysqli_error($dbconn));
                                                            ?>
                                                            <div id="empDiv">
                                                            <?php
                                                            echo '<select class="form-control" name="employeeMasterId" id="employeeMasterId" required>';
                                                            echo "<option value='' >Select Employee</option>";
                                                            while ($rowsBranch = mysqli_fetch_array($resultsBranch)) {
                                                                echo "<option value='" . $rowsBranch['employeeMasterId'] . "'>" . $rowsBranch['employeeName'] . "</option>";
                                                            }
                                                            echo "</select>";
                                                            ?>
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-md-3">
                                                            <label for="exampleInputFile1">Inquiry Source *</label><br />
                                                            <?php
                                                            $querysBranch = "select * from inquirysource where isDelete = '0'";
                                                            $resultsBranch = mysqli_query($dbconn,$querysBranch) or die(mysqli_error($dbconn));
                                                            echo '<select class="form-control" name="inquirySourceId" id="inquirySourceId" required>';
                                                            echo "<option value='' >Select Inquiry Source</option>";
                                                            while ($rowsBranch = mysqli_fetch_array($resultsBranch)) {
                                                                echo "<option value='" . $rowsBranch['inquirySourceId'] . "'>" . $rowsBranch['inquirySourceName'] . "</option>";
                                                            }
                                                            echo "</select>";
                                                            ?>
                                                        </div>
                                                        <div class="form-group col-md-3">
                                                            <label for="exampleInputFile1">Inquiry Status *</label><br />
                                                            <?php
                                                            $querysBranch = "select * from `status`  where isDelete='0'  and  istatus='1' and NOT statusId in ('3','4','5')";
                                                            $resultsBranch = mysqli_query($dbconn,$querysBranch) or die(mysqli_error($dbconn));
                                                            echo '<select class="form-control" name="statusId" id="statusId" required>';
                                                            echo "<option value='' >Select  Inquiry Status</option>";
                                                            while ($rowsBranch = mysqli_fetch_array($resultsBranch)) {
                                                                echo "<option value='" . $rowsBranch['statusId'] . "'>" . $rowsBranch['statusName'] . "</option>";
                                                            }
                                                            echo "</select>";
                                                            ?>
                                                        </div>
                                                        <div class="form-group form-md-line-input has-warning col-md-3">
                                                        <div>
                                                            <div class="form-group">
                                                                <label for="exampleInputFile1">Excel File Upload</label><br />
                                                                <input type="file"  id="gallery" name="gallery" class="btn blue" required/>
                                                                <input type="hidden" name="galeryID" ID="galeryID" />
                                                            </div>
                                                            <div id="ImageGallery" style="display:none;">  </div>
                                                        </div>    
                                                    </div>

                                                        <!-- <div class="form-group col-md-4">
                                                            <a href="#" class="btn blue" onclick="PageLoadData(1);">Search</a>
                                                        </div> -->
                                                        <div class="form-actions noborder">
                                                        <input class="btn blue margin-top-20" type="submit" id="Btnmybtn"  value="Submit" name="submit">
                                                        <button type="button" class="btn blue margin-top-20" onClick="checkclose();">Cancel</button>
                                                        <a href="genratedemoexcel.php" class="btn green margin-top-20">Demo Excel</a>
                                                    </div>
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

        <?php include_once './footer.php'; ?>
        <script src="assets/jquery.character-counter.js" type="text/javascript"></script>

        <script type="text/javascript">
        $(document).ready(function ()
            {
                $("#gallery").on('change', function ()
                {
                    var galeryID = 0;
                    galeryID = galeryID + 1;
                    $("#galeryID").val(galeryID);
                    $("#ImageGallery").html('<img src="<?php echo $web_url; ?>images/loader1.gif" alt="Uploading...."/>');

                    var formData = new FormData($('form#frmparameter')[0]);
                    $.ajax({
                        type: "POST",
                        url: "<?php echo $web_url; ?>admin/uploadExcelTemp.php",
                        processData: false,
                        contentType: false,
                        data: formData,
                        success: function (msg) {
                            $("#ImageGallery").show();
                            $("#ImageGallery").html(msg);
                        },
                    });
                });
            });

            $('#frmparameter').submit(function (e) {
                e.preventDefault();
                var $form = $(this);
                $('#loading').css("display", "block");
                $.ajax({
                    type: 'POST',
                    url: '<?php echo $web_url; ?>admin/importExcelQueryData.php',
                    data: $('#frmparameter').serialize(),
                    success: function (response) {
//                        alert(response);
                        console.log(response);
                        $('#loading').css("display", "none");
                        response = response.replace("1_", "");
                        $('#loading').css("display", "none");
                        //alert('Invalid Request.');
                        $("#PlaceUsersDataHere").html(response);
                        //window.location.href = '';
                    }
                });
            });

            function getEmployee(){
                var branchid = $('#branchid').val();
                var urlp = '<?php echo $web_url; ?>admin/findEmployee.php?bId=' + branchid;
                $.ajax({
                    type: 'POST',
                    url: urlp,
                    success: function (data) {
                        $('#empDiv').html(data);
                    }
                }).error(function () {
                    alert('An error occured');
                });
            }


        </script>
    </body>
</html>
