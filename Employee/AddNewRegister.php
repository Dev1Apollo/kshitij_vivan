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
        <title><?php echo $ProjectName; ?> | Student Registration</title>
        <?php include_once 'include.php'; ?>
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
                            <div class="row">
                                <div class="col-md-2">
                                    <?php include_once './menu-admission.php'; ?>
                                </div>
                                <div class="col-md-10">
                                    <div class="portlet light ">
                                        <div class="portlet-title">
                                            <div class="caption grey-gallery">
                                                <i class="icon-settings grey-gallery"></i>
                                                <span class="caption-subject bold uppercase" id="AddNewStudent">Student Registration </span>
                                            </div>
                                            <?php
//                                                echo "SELECT * FROM `lead` inner join customerentry on lead.customerEntryId=customerentry.customerEntryId where lead.leadId = '" . $_REQUEST['token'] . "'";
                                            $query = mysqli_query($dbconn, "SELECT * FROM `lead` inner join customerentry on lead.customerEntryId=customerentry.customerEntryId where lead.leadId = '" . $_REQUEST['token'] . "'");
                                            $row = mysqli_fetch_array($query);
                                            $lid = $row['leadId'];
                                            ?>

                                        </div>
                                        <div class="portlet-body form">
                                            <form  role="form"  method="POST"  action="" name="frmparameter"  id="frmparameter" enctype="multipart/form-data">
                                                <input type="hidden" value="AddRegistredStudent" name="action" id="action">
                                                <input type="hidden" value="<?php
                                                if (isset($_REQUEST['token'])) {
                                                    echo $row['leadId'];
                                                } else {
                                                    echo "";
                                                }
                                                ?>" name="leadId" id="leadId">
                                                <input type="hidden" value="<?php
                                                if (isset($_REQUEST['token'])) {
                                                    echo $row['customerEntryId'];
                                                } else {
                                                    echo "";
                                                }
                                                ?>" name="customerEntryId" id="customerEntryId">
                                                <input name="leaduniqueid" value="<?php echo $row['leaduniqueid'] ?>" id="leaduniqueid" type="hidden">
                                                <div class="form-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="col-md-12">
                                                                <h4 class="bold text-center">Personal Details</h4>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label for="form_control_1" class="col-sm-3 col-form-label">Student In*</label>
                                                                <div class="col-sm-9">
                                                                    <select name="studentPortal_Id" id="studentPortal_Id"  class="form-control" required="">
                                                                        <option value="">Select Option</option>
                                                                        <!--                                                                        <option value="1">Aptech Portal</option>-->
                                                                        <option value="4">Maac Satellite</option>
                                                                        <option value="1">Maac CG</option>
                                                                        <option value="2">Kshitij Vivan</option>                                                                                                          
                                                                        <option value="3">Other</option>
                                                                    </select>
                                                                </div>
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
                                                                        echo $row['MiddleName'];
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
                                                                        echo $row['lastName'];
                                                                    } else {
                                                                        echo "";
                                                                    }
                                                                    ?>"  class="form-control" placeholder="Enter The Surname" required="" type="text">
                                                                </div>
                                                            </div>

                                                            <div class="form-group row">
                                                                <label for="form_control_1" class="col-sm-3 col-form-label">Date Of Birth</label>
                                                                <div class="col-sm-9">
                                                                    <input name="DOB" id="DOB"  class="form-control date-picker" placeholder="Enter Date Of Birth" type="text">
                                                                </div>
                                                            </div>

                                                            <div class="form-group row" >
                                                                <label for="form_control_1" class="col-sm-3 col-form-label">Gender*</label>
                                                                <div class="col-sm-9">
                                                                    <select name="Gender" id="Gender"  class="form-control" required="">
                                                                        <option value="">Select Gender</option>
                                                                        <option value="Male">Male</option>
                                                                        <option value="Female">Female</option>
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
                                                                    <input name="phone" id="phone"  class="form-control" placeholder="Enter The phone No."  type="text">
                                                                </div>
                                                            </div>
                                                            <div class="form-group row" >
                                                                <label for="form_control_1" class="col-sm-3 col-form-label">Mobile No. 1*</label><div id="errordiv"></div>
                                                                <div class="col-sm-9">
                                                                    <input name="mobileOne" id="mobileOne" value="<?php
                                                                    if (isset($_REQUEST['token'])) {
                                                                        echo $row['mobileNo'];
                                                                    } else {
                                                                        echo "";
                                                                    }
                                                                    ?>" class="form-control" placeholder="Enter The Mobile No." pattern="[0-9]{10}" required="" type="text">
                                                                </div>
                                                            </div>
                                                            <div class="form-group row" >
                                                                <label for="form_control_1" class="col-sm-3 col-form-label">Mobile No. 2</label><div id="errordiv"></div>
                                                                <div class="col-sm-9">
                                                                    <input name="mobileTwo" id="mobileTwo"  class="form-control" placeholder="Enter The Mobile No." pattern="[0-9]{10}" type="text">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="col-md-12">
                                                                <h4 class="bold text-center">Address Details</h4>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label for="form_control_1" class="col-sm-3 col-form-label">Address 1*</label><div id="errordiv"></div>
                                                                <div class="col-sm-9">
                                                                    <input name="address" id="address" class="form-control" placeholder="Enter The Address" required="" type="text">
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label for="form_control_1" class="col-sm-3 col-form-label">Address 2</label><div id="errordiv"></div>
                                                                <div class="col-sm-9">
                                                                    <input name="addresstwo" id="addresstwo" class="form-control" placeholder="Enter The Address"  type="text">
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <?php
                                                                if (isset($_REQUEST['token'])) {
                                                                    $query1 = mysqli_query($dbconn, "SELECT * FROM `lead` inner join customerentry on lead.customerEntryId=customerentry.customerEntryId where `leadId` = '" . $_REQUEST['token'] . "'");
                                                                    $row = mysqli_fetch_array($query1);
                                                                    $city = mysqli_fetch_array(mysqli_query($dbconn, "select * from city where cityid='" . $row['cityId'] . "' order by name asc"));
                                                                }
                                                                ?>
                                                                <label for="form_control_1" class="col-sm-3 col-form-label">State*</label>
                                                                <div class="col-sm-9">
                                                                    <select name="state" id="state" class="form-control" onchange="getcity();">
                                                                        <option value="">Select State</option>
                                                                        <?php
                                                                        $state = mysqli_query($dbconn, "SELECT * FROM state where istatus=1 and isDelete=0");
                                                                        while ($rowState = mysqli_fetch_array($state)) {
                                                                            if ($rowState['stateId'] == $row['stateId']) {
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
                                                                            $City = mysqli_query($dbconn, "SELECT * FROM city where istatus=1 and isDelete=0");
                                                                            while ($rowCity = mysqli_fetch_array($City)) {
                                                                                if ($rowCity['cityid'] == $row['cityId']) {
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
                                                                <label for="form_control_1" class="col-sm-3 col-form-label">Pincode</label><div id="errordiv"></div>
                                                                <div class="col-sm-9">
                                                                    <input name="pincode" id="pincode"  class="form-control" placeholder="Enter The Pin Code"  type="text">
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <div class="col-sm-9">
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <div class="col-sm-9">
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <div class="col-sm-9">
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <div class="col-sm-9">
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <div class="col-sm-9">
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <div class="col-sm-9">
                                                                </div>
                                                            </div>


                                                            <div class="col-md-12">
                                                                <hr />
                                                                <h4 class="bold text-center">Other Details</h4>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label for="form_control_1" class="col-sm-3 col-form-label">Occupation</label>
                                                                <div class="col-sm-9">
                                                                    <input name="occupation" id="occupation"  class="form-control" placeholder="Enter The Occupation" type="text" >
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label for="form_control_1" class="col-sm-3 col-form-label">Qualification</label>
                                                                <div class="col-sm-9">
                                                                    <input name="qualification" id="qualification"  class="form-control" placeholder="Enter The Qualification" type="text" >
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label for="form_control_1" class="col-sm-3 col-form-label">Designation</label>
                                                                <div class="col-sm-9">
                                                                    <input name="designation" id="designation"  class="form-control" placeholder="Enter The Designation" type="text" >
                                                                </div>
                                                            </div>
                                                            <?php
                                                            $branchid = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `employeemaster` WHERE `employeeMasterId` = " . $_SESSION['EmployeeId']));
                                                            ?>
                                                            <input name="branchId" id="branchId" value="<?php
                                                            if (isset($_REQUEST['token'])) {
                                                                echo $branchid['branchid'];
                                                            } else {
                                                                echo $branchid['branchid'];
                                                            }
                                                            ?>" class="form-control" type="hidden" >
                                                            <!--                                                            <div class="form-group col-md-6">
                                                                                                                            <label for="form_control_1">Student Portal ID</label>
                                                                                                                            <input name="studentPortal_Id" id="studentPortal_Id"  class="form-control" placeholder="Enter The Student Portal ID" type="text" >
                                                                                                                        </div>-->

                                                            <!--                                                            <div class="form-group row">
                                                                                                                            <label for="form_control_1" class="col-sm-3 col-form-label">Continue With Enrollment</label>
                                                                                                                            <div class="col-sm-9">
                                                                                                                                <select name="iEnrollStatus" id="iEnrollStatus"  class="form-control" required="">
                                                                                                                                    <option value="2">NO</option>
                                                                                                                                    <option value="1">YES</option>                                                                                                                                                                                
                                                                                                                                </select>
                                                                                                                            </div>
                                                                                                                        </div>-->
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
                                    <?php // }      ?>
                                </div>
                            </div> 
                        </div>
                    </div>
                </div>
            </div>

            <?php include_once './footer.php'; ?>
            <script src="<?php echo $web_url; ?>admin/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>
            <script type="text/javascript">
                                                            function getcity()
                                                            {
                                                                var q = $('#state').val();
                                                                var urlp = '<?php echo $web_url; ?>Employee/findCity.php?sId=' + q;
                                                                $.ajax({
                                                                    type: 'POST',
                                                                    url: urlp,
                                                                    success: function (data) {
                                                                        $('#CityDiv').html(data);
                                                                    }
                                                                }).error(function () {
                                                                    alert('An error occured');
                                                                });
                                                            }

                                                            function checkclose() {
                                                                window.location.href = '<?php echo $web_url; ?>Employee/StudentRegistration.php';
                                                            }

                                                            $(document).ready(function () {
                                                                $("#DOB").datepicker({
                                                                    format: 'dd-mm-yyyy',
                                                                    autoclose: true,
                                                                    todayHighlight: true,
                                                                    defaultDate: "now",
                                                                    endDate: "now"
                                                                });

                                                            });

                                                            function chkMobileno(ID)
                                                            {
                                                                var q = $('#Mobile').val();
                                                                var urlp = '<?php echo $web_url; ?>Employee/findAdminCEMobile.php?ID=' + q;
                                                                $.ajax({
                                                                    type: 'POST',
                                                                    url: urlp,
                                                                    success: function (data) {
                                                                        if (data == 0)
                                                                        {
                                                                            $('#errordiv').html('');
                                                                        } else
                                                                        {
                                                                            $('#errordiv').html(data);
                                                                            $('#Mobile').val('');
                                                                        }
                                                                    }
                                                                }).error(function () {
                                                                    alert('An error occured');
                                                                });
                                                            }

                                                            $('#frmparameter').submit(function (e) {
                                                                var studentPortal_Id = $('#studentPortal_Id').val();

                                                                e.preventDefault();
                                                                var $form = $(this);
                                                                $('#loading').css("display", "block");
                                                                $.ajax({
                                                                    type: 'POST',
                                                                    url: '<?php echo $web_url; ?>Employee/querydata.php',
                                                                    data: $('#frmparameter').serialize(),
                                                                    success: function (response) {
                                                                        if (response != 0)
                                                                        {
                                                                            $('#loading').css("display", "none");
                                                                            $("#Btnmybtn").attr('disabled', 'disabled');
                                                                            alert('Registered Sucessfully.');
                                                                            response = response.trim();

                                                                            if (studentPortal_Id == 1) {
                                                                                window.location.href = '<?php echo $web_url; ?>Employee/StudentRegistration.php';
                                                                            } else {
                                                                                window.location.href = '<?php echo $web_url; ?>Employee/StudentRegistration.php';
                                                                            }

                                                                        }
                                                                    }
                                                                });
                                                            });

            </script>
    </body>
</html>