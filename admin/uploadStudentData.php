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
        <meta charset="utf-8">
        <link rel="shortcut icon" href="images/favicon.png">
        <title> <?php echo $ProjectName ?> | Upload Student Data</title>
        <?php include_once './include.php'; ?>       
    </head> 
    <body class="page-container-bg-solid page-boxed">
        <?php
        include('header.php');
        ?>
        <div style="display: none; z-index: 10060;" id="loading">
            <img id="loading-image" src="<?php echo $web_url; ?>admin/images/loader1.gif">
        </div>
        <div class="page-container">        
            <div class="page-content-wrapper">
                <div class="page-content">
                    <div class="container">                    
                        <ul class="page-breadcrumb breadcrumb">
                            <li>
                                <a href="index.php">Home</a>
                                <i class="fa fa-circle"></i>
                            </li>
                            <li>
                                <span>Update Student Data </span>
                            </li>
                        </ul>
                        <div class="page-content-inner">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="portlet light ">
                                        <div class="portlet-title">
                                            <div class="caption font-red-sunglo">
                                                <i class="icon-settings font-red-sunglo"></i>
                                                <span class="caption-subject bold uppercase">Update Student Data </span>
                                            </div>
                                        </div>
                                        <div class="portlet-body form">
                                            <div class="form-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <form  role="form"  method="POST"  action="" name="frmuploadExcel"  id="frmuploadExcel" enctype="multipart/form-data" class="margin-bottom-40">
                                                            <input type="hidden" value="UploadStudentData" name="action" id="action">

                                                            <div class="form-group form-md-line-input has-warning col-md-12">
                                                                <div>
                                                                    <div class="form-group">
                                                                        <label for="exampleInputFile1">Excel file</label><br />
                                                                        <input type="file"  id="gallery" name="gallery" class="btn red" required=""/>
                                                                        <input type="hidden" name="galeryID" ID="galeryID" />
                                                                    </div>
                                                                    <div id="ImageGallery" style="display:none;">  </div>
                                                                </div>    
                                                            </div>
                                                            <div class="form-group form-md-line-input col-md-12">                                                            
                                                                <input class="btn blue" type="submit" id="Btnmybtn"  value="Submit" name="submit"> 
                                                                <div style="display: none;" id="loading"><img src="<?php echo $web_url ?>images/loader.gif"></div>
                                                            </div>

                                                        </form>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <h4 style="color : #f03f2a; font-weight: bold" id="errorlog">

                                                        </h4>
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

            $('#frmuploadExcel').submit(function (e) {
                e.preventDefault();
                var $form = $(this);
                $('#loading').css("display", "block");
                $.ajax({
                    type: 'POST',
                    url: 'querydataUploadStudentData.php',
                    data: $('#frmuploadExcel').serialize(),
                    success: function (response) {
                        alert(response);
                        console.log(response);
                        $('#loading').css("display", "none");
                        response = response.replace("0", "");
                        $('#loading').css("display", "none");
                        //alert('Invalid Request.');
                        $("#errorlog").html(response);
                    }
                });
            });

        </script>
        <script type="text/javascript">

            $(document).ready(function ()
            {
                $("#gallery").on('change', function ()
                {
                    var galeryID = 0;
                    galeryID = galeryID + 1;
                    $("#galeryID").val(galeryID);
                    $("#ImageGallery").html('<img src="<?php echo $web_url; ?>admin/images/input-spinner.gif" alt="Uploading...."/>');
                    var formData = new FormData($('form#frmuploadExcel')[0]);
                    $.ajax({
                        type: "POST",
                        url: "uploadExcelTemp.php",
                        processData: false,
                        contentType: false,
                        data: formData,
                        success: function (msg) {
                            // alert(msg);
                            $("#ImageGallery").show();
                            $("#ImageGallery").html(msg);
                        }
                    });
                });
            });

        </script>       
    </body>
</html>