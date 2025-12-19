<?php
ob_start();
error_reporting(E_ALL);
require_once '../common.php';
$connect = new connect();
include('IsLogin.php');

$result = mysqli_query($dbconn,"SELECT * FROM `studentadmission` WHERE `stud_id`='" . $_REQUEST['token'] . "'");
if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_array($result);
} else {
    echo 'somthig going worng! try again';
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <link rel="shortcut icon" href="images/favicon.png">
        <title> <?php echo $ProjectName ?> |Edit Student Entry</title>
        <?php include_once './include.php'; ?>  
        <link href="assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet" type="text/css" />
    </head>
    <body class="page-container-bg-solid page-boxed">
        <?php
        include('header.php');
        ?>
        <div style="display: none; z-index: 10060;" id="loading">
            <img id="loading-image" src="<?php echo $web_url; ?>Employee/images/loader1.gif">
        </div>
        <div class="page-container">        
            <div class="page-content">
                <div class="container">                    
                    <ul class="page-breadcrumb breadcrumb">
                        <li>
                            <a href="<?php echo $web_url; ?>Employee/index.php">Home</a>
                            <i class="fa fa-circle"></i>
                        </li>
                        <li>
                            <a href="<?php echo $web_url; ?>Employee/StudentEntry.php">List Of Student Entry</a>
                            <i class="fa fa-circle"></i>
                        </li>
                        <li>
                            <span> Edit Student Entry</span>
                        </li>
                    </ul>
                    <div class="page-content-inner">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="portlet light ">
                                    <div class="portlet-title">
                                        <div class="caption grey-gallery">
                                            <i class="icon-settings grey-gallery"></i>
                                            <span class="caption-subject bold uppercase"> Edit Student Entry</span>
                                        </div>
                                    </div>
                                    <div class="portlet-body form">
                                        <form  role="form"  method="POST"  action="" name="frmparameter"  id="frmparameter" enctype="multipart/form-data">
                                            <input type="hidden" value="EditStudentEntry" name="action" id="action">
                                            <input type="hidden" value="<?php echo $row['stud_id'] ?>" name="stud_id" id="stud_id">
                                            <div class="form-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="col-md-12">
                                                            <h4 class="bold text-center">Personal Details</h4>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label for="form_control_1" class="col-sm-3 col-form-label">Title*</label>
                                                            <div class="col-sm-9">
                                                                <select name="Title" id="Title" required="" class="form-control">
                                                                    <option value="">Select Any Title</option>
                                                                    <option value="MR" <?php
                                                                    if (isset($_REQUEST['token'])) {
                                                                        if ($row['title'] == 'MR') {
                                                                            echo 'selected';
                                                                        }
                                                                    }
                                                                    ?>> Mr.</option>
                                                                    <option value="MRS" <?php
                                                                    if (isset($_REQUEST['token'])) {
                                                                        if ($row['title'] == 'MRS') {
                                                                            echo 'selected';
                                                                        }
                                                                    }
                                                                    ?>> Mrs.</option>
                                                                    <option value="Miss" <?php
                                                                    if (isset($_REQUEST['token'])) {
                                                                        if ($row['title'] == 'Miss') {
                                                                            echo 'selected';
                                                                        }
                                                                    }
                                                                    ?>>Miss.</option>

                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label for="form_control_1" class="col-sm-3 col-form-label">First Name*</label>
                                                            <div class="col-sm-9">
                                                                <input  name="firstName" id="firstName" value="<?php
                                                                if (isset($_REQUEST['token'])) {
                                                                    echo $row['firstName'];
                                                                } else {
                                                                    echo "";
                                                                }
                                                                ?>" class="form-control" placeholder="Enter The First Name" type="text" required="">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label for="form_control_1" class="col-sm-3 col-form-label">Middle Name</label>
                                                            <div class="col-sm-9">
                                                                <input name="middleName" id="middleName" value="<?php
                                                                if (isset($_REQUEST['token'])) {
                                                                    echo $row['middleName'];
                                                                } else {
                                                                    echo "";
                                                                }
                                                                ?>"  class="form-control" placeholder="Enter The Middle Name" type="text" >
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label for="form_control_1" class="col-sm-3 col-form-label">Surname*</label>
                                                            <div class="col-sm-9">
                                                                <input name="surName" id="surName" value="<?php
                                                                if (isset($_REQUEST['token'])) {
                                                                    echo $row['surName'];
                                                                } else {
                                                                    echo "";
                                                                }
                                                                ?>"  class="form-control" placeholder="Enter The Surname" required="" type="text">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label for="form_control_1" class="col-sm-3 col-form-label">Date Of Birth</label>
                                                            <div class="col-sm-9">
                                                                <input name="DOB" id="DOB" value="<?php
                                                                if (isset($_REQUEST['token'])) {
                                                                    echo $row['DOB'];
                                                                } else {
                                                                    echo "";
                                                                }
                                                                ?>" class="form-control date-picker" placeholder="Enter Date Of Birth" type="text">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row" >
                                                            <label for="form_control_1" class="col-sm-3 col-form-label">Gender*</label>
                                                            <div class="col-sm-9">
                                                                <select name="Gender" id="Gender"  class="form-control" required="">
                                                                    <option value="">Select Gender</option>
                                                                    <option value="Male" <?php
                                                                    if (isset($_REQUEST['token'])) {
                                                                        if ($row['gender'] == 'Male') {
                                                                            echo 'selected';
                                                                        }
                                                                    }
                                                                    ?>>Male</option>
                                                                    <option value="Female" <?php
                                                                    if (isset($_REQUEST['token'])) {
                                                                        if ($row['gender'] == 'Female') {
                                                                            echo 'selected';
                                                                        }
                                                                    }
                                                                    ?>>Female</option>
                                                                </select>
                                                            </div>
                                                        </div> 
                                                        <div class="col-md-12">
                                                            <hr />
                                                            <h4 class="bold text-center" >Contact Details</h4>
                                                        </div>
                                                        <div class="form-group row" >
                                                            <label for="form_control_1" class="col-sm-3 col-form-label">Email*</label>
                                                            <div class="col-sm-9">
                                                                <input name="email" id="email" value="<?php
                                                                if (isset($_REQUEST['token'])) {
                                                                    echo $row['email'];
                                                                } else {
                                                                    echo "";
                                                                }
                                                                ?>" class="form-control" placeholder="Enter The Email Address" required="" type="email">
                                                            </div>
                                                        </div>

                                                        <div class="form-group row" >
                                                            <label for="form_control_1" class="col-sm-3 col-form-label">Phone</label><div id="errordiv"></div>
                                                            <div class="col-sm-9">
                                                                <input name="phone" id="phone" value="<?php
                                                                if (isset($_REQUEST['token'])) {
                                                                    echo $row['phone'];
                                                                } else {
                                                                    echo "";
                                                                }
                                                                ?>" class="form-control" placeholder="Enter The phone No."  type="text">
                                                            </div>
                                                        </div>

                                                        <div class="form-group row" >
                                                            <label for="form_control_1" class="col-sm-3 col-form-label">Mobile No. - 1*</label><div id="errordiv"></div>
                                                            <div class="col-sm-9">
                                                                <input name="mobileOne" id="mobileOne" value="<?php
                                                                if (isset($_REQUEST['token'])) {
                                                                    echo $row['mobileOne'];
                                                                } else {
                                                                    echo "";
                                                                }
                                                                ?>" class="form-control" placeholder="Enter The Mobile No." pattern="[0-9]{10}" required="" type="text">
                                                            </div>
                                                        </div>

                                                        <div class="form-group row" >
                                                            <label for="form_control_1" class="col-sm-3 col-form-label">Mobile No. - 2</label><div id="errordiv"></div>
                                                            <div class="col-sm-9">
                                                                <input name="mobileTwo" id="mobileTwo" value="<?php
                                                                if (isset($_REQUEST['token'])) {
                                                                    echo $row['mobileTwo'];
                                                                } else {
                                                                    echo "";
                                                                }
                                                                ?>" class="form-control" placeholder="Enter The Mobile No." pattern="[0-9]{10}" type="text">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="col-md-12">
                                                            <h4 class="bold text-center">Address Details</h4>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label for="form_control_1" class="col-sm-3 col-form-label">Address-1*</label><div id="errordiv"></div>
                                                            <div class="col-sm-9">
                                                                <input name="address" id="address" value="<?php
                                                                if (isset($_REQUEST['token'])) {
                                                                    echo $row['address'];
                                                                } else {
                                                                    echo "";
                                                                }
                                                                ?>" class="form-control" placeholder="Enter The Address" required="" type="text">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label for="form_control_1" class="col-sm-3 col-form-label">Address-2</label><div id="errordiv"></div>
                                                            <div class="col-sm-9">
                                                                <input name="addresstwo" id="addresstwo" value="<?php
                                                                if (isset($_REQUEST['token'])) {
                                                                    echo $row['addresstwo'];
                                                                } else {
                                                                    echo "";
                                                                }
                                                                ?>" class="form-control" placeholder="Enter The Address"  type="text">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label for="form_control_1" class="col-sm-3 col-form-label">State*</label>
                                                            <div class="col-sm-9">
                                                                <select name="state" id="state" class="form-control" onchange="getcity();">
                                                                    <option value="">Select State</option>
                                                                    <?php
                                                                    $state = mysqli_query($dbconn,"SELECT * FROM state where istatus=1 and isDelete=0");
                                                                    while ($rowState = mysqli_fetch_array($state)) {
                                                                        if ($rowState['stateId'] == $row['state']) {
                                                                            ?>
                                                                            <option value="<?php echo $rowState['stateId']; ?>" selected=""><?php echo $rowState['stateName']; ?></option>
                                                                        <?php } else { ?>
                                                                            <option value="<?php echo $rowState['stateId']; ?>"><?php echo $rowState['stateName']; ?></option>
                                                                            <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label for="form_control_1" class="col-sm-3 col-form-label">City*</label><div id="errordiv"></div>
                                                            <div class="col-sm-9">
                                                                <div class="txt_field" id="CityDiv" >
                                                                    <select name="city" id="city" class="form-control">
                                                                        <option value="">Select City</option>
                                                                        <?php
                                                                        $City = mysqli_query($dbconn,"SELECT * FROM city where istatus=1 and isDelete=0");
                                                                        while ($rowCity = mysqli_fetch_array($City)) {
                                                                            if ($rowCity['cityid'] == $row['city']) {
                                                                                ?>
                                                                                <option value="<?php echo $rowCity['cityid']; ?>" selected=""><?php echo $rowCity['name']; ?></option>
                                                                            <?php } else { ?>
                                                                                <option value="<?php echo $rowCity['cityid']; ?>"><?php echo $rowCity['name']; ?></option>
                                                                                <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label for="form_control_1" class="col-sm-3 col-form-label">Pincode*</label><div id="errordiv"></div>
                                                            <div class="col-sm-9">
                                                                <input name="pincode" id="pincode" value="<?php
                                                                if (isset($_REQUEST['token'])) {
                                                                    echo $row['pincode'];
                                                                } else {
                                                                    echo "";
                                                                }
                                                                ?>" class="form-control" placeholder="Enter The Pin Code" required="" type="text">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <div class="col-sm-9"></div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <div class="col-sm-9"></div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <div class="col-sm-9"></div>
                                                        </div>
<!--                                                        <div class="form-group row">
                                                            <div class="col-sm-9"></div>
                                                        </div>-->
<!--                                                        <div class="form-group row">
                                                            <div class="col-sm-9"></div>
                                                        </div>-->

                                                        <div class="col-md-12">
                                                            <hr />
                                                            <h4 class="bold text-center">Other Details</h4>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label for="form_control_1" class="col-sm-3 col-form-label">Occupation</label>
                                                            <div class="col-sm-9">
                                                                <input name="occupation" id="occupation" value="<?php
                                                                if (isset($_REQUEST['token'])) {
                                                                    echo $row['occupation'];
                                                                } else {
                                                                    echo "";
                                                                }
                                                                ?>" class="form-control" placeholder="Enter The Occupation" type="text" >
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label for="form_control_1" class="col-sm-3 col-form-label">Qualification</label>
                                                            <div class="col-sm-9">
                                                                <input name="qualification" id="qualification" value="<?php
                                                                if (isset($_REQUEST['token'])) {
                                                                    echo $row['qualification'];
                                                                } else {
                                                                    echo "";
                                                                }
                                                                ?>" class="form-control" placeholder="Enter The Qualification" type="text" >
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label for="form_control_1" class="col-sm-3 col-form-label">Designation</label>
                                                            <div class="col-sm-9">
                                                                <input name="designation" id="designation" value="<?php
                                                                if (isset($_REQUEST['token'])) {
                                                                    echo $row['designation'];
                                                                } else {
                                                                    echo "";
                                                                }
                                                                ?>" class="form-control" placeholder="Enter The Designation" type="text" >
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label for="form_control_1" class="col-sm-3 col-form-label">Portal</label>
                                                            <div class="col-sm-9">
                                                                <select name="studentPortal_Id" id="studentPortal_Id"  class="form-control" required="">
                                                                    <option value="">Select Option</option>
                                                                    <option value="1" <?php
                                                                    if (isset($_REQUEST['token'])) {
                                                                        if ($row['studentPortal_Id'] == '1') {
                                                                            echo 'selected';
                                                                        }
                                                                    }
                                                                    ?>>Aptech Portal</option>
                                                                    <option value="2" <?php
                                                                    if (isset($_REQUEST['token'])) {
                                                                        if ($row['studentPortal_Id'] == '2') {
                                                                            echo 'selected';
                                                                        }
                                                                    }
                                                                    ?>>Kshitij Vivan</option>                                                                                                          
                                                                    <option value="3" <?php
                                                                    if (isset($_REQUEST['token'])) {
                                                                        if ($row['studentPortal_Id'] == '3') {
                                                                            echo 'selected';
                                                                        }
                                                                    }
                                                                    ?>>Other</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-actions noborder">
                                                    <input class="btn blue margin-top-20" type="submit" id="Btnmybtn"  value="Submit" name="submit">      
                                                    <button type="button" class="btn blue margin-top-20" onClick="checkclose();">Cancel</button>
                                                </div>
                                            </div>
                                        </form>
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
    <script type="text/javascript">




                                                        function checkclose() {
                                                            window.location.href = '<?php echo $web_url; ?>Employee/StudentEntry.php';
                                                        }
                                                        $(document).ready(function () {
                                                            $("#DOB").datepicker({
                                                                format: 'dd-mm-yyyy',
                                                                autoclose: true,
                                                                todayHighlight: true,
                                                                defaultDate: "now",
                                                                //                                                                                            endDate: "now"
                                                            });

                                                        });

                                                        $('#frmparameter').submit(function (e) {

                                                            e.preventDefault();
                                                            var $form = $(this);
                                                            $('#loading').css("display", "block");
                                                            $.ajax({
                                                                type: 'POST',
                                                                url: '<?php echo $web_url; ?>Employee/querydata.php',
                                                                data: $('#frmparameter').serialize(),
                                                                success: function (response) {

                                                                    console.log(response);

                                                                    if (response != 0)
                                                                    {
                                                                        $('#loading').css("display", "none");
                                                                        $("#Btnmybtn").attr('disabled', 'disabled');
                                                                        alert(' Edited Sucessfully.');
                                                                        response = response.trim();
                                                                        window.location.href = '<?php echo $web_url; ?>Employee/StudentEntry.php';
                                                                    }
                                                                }

                                                            });
                                                        });

    </script>

</body>
</html>
