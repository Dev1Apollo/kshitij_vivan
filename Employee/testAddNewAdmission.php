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
        <title><?php echo $ProjectName; ?> | Add New Student</title>
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
                <!--                <div class="page-head">
                                    <div class="container">
                                        <div class="page-title">
                                            <h1>Add Company
                                            </h1>
                                        </div>
                                    </div>
                                </div>-->
                <div class="page-content">
                    <div class="container">
                        <ul class="page-breadcrumb breadcrumb">
                            <li>
                                <a href="<?php echo $web_url; ?>Employee/index.php">Home</a>
                                <i class="fa fa-circle"></i>
                            </li>
                            <li>
                                <a href="<?php echo $web_url; ?>Employee/StudentEntry.php">List of Student Admission</a>
                                <i class="fa fa-circle"></i>
                            </li>
                            <li>
                                <span>Add New Student</span>
                            </li>
                        </ul>

                        <div class="page-content-inner">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="portlet light ">
                                        <div class="portlet-title">
                                            <div class="caption grey-gallery">
                                                <i class="icon-settings grey-gallery"></i>
                                                <span class="caption-subject bold uppercase"> Add New Student</span>
                                            </div>
                                            <form  role="form"  method="POST"  action="" name="frmsearch"  id="frmsearch" enctype="multipart/form-data">
                                                <div class="form-group col-md-3" style="float: right;">
                                                    <input type="text" id="leaduniqueid" name="leaduniqueid"  class="form-control" placeholder="Enter The Lead Unique No.">
                                                    <input type="submit"  name="Submit" id="Submit" class="btn btn-sm blue" value="Submit">
                                                </div>
                                            </form>
                                            <?php
                                              
                                                    $result = mysqli_query($dbconn,"SELECT * FROM `lead` WHERE `leaduniqueid`='". $_REQUEST['leaduniqueid'] ."'");
                                                    if (mysqli_num_rows($result) > 0) {
                                                         $query =  mysqli_query($dbconn,"SELECT * FROM `lead` inner join customerentry on lead.customerEntryId=customerentry.customerEntryId where `leaduniqueid` = '".$_REQUEST['leaduniqueid']."'");
                                                        $row =  mysqli_fetch_array($query);
                                                    
                                            ?>
                                                              
                                        </div>
                                        <div class="portlet-body form">
                                            <form  role="form"  method="POST"  action="" name="frmparameter"  id="frmparameter" enctype="multipart/form-data">
                                                <input type="hidden" value="AddCustomerEntry" name="action" id="action">
                                                <div class="form-body">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <h4 class="bold text-center">Personal Details</h4>


                                                        </div>
                                                    <div class="form-group col-md-3">
                                                        <label for="form_control_1">Title*</label>
                                                        
                                                        <select name="Title" id="Title"  class="form-control" required="">
                                                            <option value="">Select Any Title</option>
                                                             <option value="<?php if($row['title'] == 'MR'){echo 'selected';}?>">Mr.</option>
                                                              <option value="<?php if($row['title'] == 'MRS'){echo 'selected';}?>">Mrs.</option>
                                                               <option value="<?php if($row['title'] == 'Miss'){echo 'selected';}?>">Miss.</option>
                                                            
                                                        </select>
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label for="form_control_1">First Name*</label>
                                                        <input  name="firstName" id="firstName" value="<?php echo $row['firstName']; ?>" class="form-control" placeholder="Enter The First Name" type="text" required="">
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label for="form_control_1">Middle Name</label>
                                                        <input name="middleName" id="middleName" value="<?php echo $row['MiddleName']; ?>"  class="form-control" placeholder="Enter The Middle Name" type="text" >
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label for="form_control_1">Sur Name</label>
                                                        <input name="surName" id="surName" value="<?php echo $row['lastName']; ?>"  class="form-control" placeholder="Enter The Sur Name" type="text">
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label for="form_control_1">Date Of Birth</label>
                                                        <input name="DOB" id="DOB"  class="form-control date-picker" placeholder="Enter Date Of Birth" type="text">
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label for="form_control_1">Gender</label>
                                                        <select name="Gender" id="Gender"  class="form-control" equired="">
                                                            <option value="">Select Gender</option>
                                                            <option value="Male">Male</option>
                                                            <option value="Female">Female</option>
                                                        </select>
                                                    </div> 
                                                    <hr>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <h4 class="bold text-center">Address Details</h4>
                                                        </div>
                                                    <div class="form-group col-md-3">
                                                        <label for="form_control_1">Address</label><div id="errordiv"></div>
                                                        <input name="address" id="address" class="form-control" placeholder="Enter The Address" type="text">
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <?php
                                                            $city = mysqli_fetch_array(mysqli_query($dbconn,"select * from city where cityid='".$row['cityId']."' order by name asc"));
                                                        ?>
                                                        <label for="form_control_1">City</label><div id="errordiv"></div>
                                                        <input name="city" id="city" value="<?php echo $city['name']; ?>" class="form-control" placeholder="Enter The City" type="text">
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label for="form_control_1">Pin Code</label><div id="errordiv"></div>
                                                        <input name="pincode" id="pincode"  class="form-control" placeholder="Enter The Pin Code" type="text">
                                                    </div>
                                                    </div>
                                                    <hr>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <h4 class="bold text-center">Contact Details</h4>
                                                        </div>
                                                    <div class="form-group col-md-3">
                                                        <label for="form_control_1">Email</label>
                                                        <input name="email" id="email" value="<?php echo $row['email']; ?>" class="form-control" placeholder="Enter The Email Address"  type="email">
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label for="form_control_1">Phone</label><div id="errordiv"></div>
                                                        <input name="phone" id="phone"  class="form-control" placeholder="Enter The phone No." pattern="[0-9]{10}" type="text">
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label for="form_control_1">Mobile No. - 1*</label><div id="errordiv"></div>
                                                        <input name="mobileOne" id="mobileOne" value="<?php echo $row['mobileNo']; ?>" class="form-control" placeholder="Enter The Mobile No." pattern="[0-9]{10}" type="text">
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label for="form_control_1">Mobile No. - 2</label><div id="errordiv"></div>
                                                        <input name="mobileTwo" id="mobileTwo"  class="form-control" placeholder="Enter The Mobile No." pattern="[0-9]{10}" type="text">
                                                    </div>
                                                    </div>
                                                    
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <h4 class="bold text-center">Other Details</h4>
                                                        </div>
                                                    <div class="form-group col-md-3">
                                                        <label for="form_control_1">Occupation</label>
                                                        <input name="occupation" id="occupation"  class="form-control" placeholder="Enter The Occupation" type="text" >
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label for="form_control_1">Qualification</label>
                                                        <input name="qualification" id="qualification"  class="form-control" placeholder="Enter The Qualification" type="text" >
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label for="form_control_1">Designation</label>
                                                        <input name="designation" id="designation"  class="form-control" placeholder="Enter The Designation" type="text" >
                                                    </div>
<!--                                                    <div class="form-group col-md-3">
                                                        <label for="form_control_1">Branch ID</label>
                                                        <input name="branchId" id="branchId"  class="form-control" placeholder="Enter The Branch ID" type="text" >
                                                    </div>-->
                                                    <div class="form-group col-md-3">
                                                        <label for="form_control_1">Student Portal ID</label>
                                                        <input name="studentPortal_Id" id="studentPortal_Id"  class="form-control" placeholder="Enter The Student Portal ID" type="text" >
                                                    </div>
                                                    </div>
                                                 </div>
                                                <div class="form-actions noborder">
                                                    <input class="btn blue margin-top-20" type="submit" id="Btnmybtn"  value="Submit" name="submit">      
                                                    <button type="button" class="btn blue margin-top-20" onClick="checkclose();">Cancel</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                      <?php
                                            } else { 
                                       ?>
                                    </div>
                                            <div class="portlet-body form">
                                                <form  role="form"  method="POST"  action="" name="frmparameter"  id="frmparameter" enctype="multipart/form-data">
                                                    <input type="hidden" value="AddCustomerEntry" name="action" id="action">
                                                    <div class="form-body">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <h4 class="bold text-center">Personal Details</h4>


                                                            </div>
                                                            <div class="form-group col-md-3">
                                                                <label for="form_control_1">Title*</label>

                                                                <select name="Title" id="Title"  class="form-control" required="">
                                                                    <option value="">Select Any Title</option>
                                                                    <option value="MR">Mr.</option>
                                                                    <option value="MRS">Mrs.</option>
                                                                    <option value="Miss">Miss.</option>

                                                                </select>
                                                            </div>
                                                            <div class="form-group col-md-3">
                                                                <label for="form_control_1">First Name*</label>
                                                                <input  name="firstName" id="firstName"  class="form-control" placeholder="Enter The First Name" type="text" required="">
                                                            </div>
                                                            <div class="form-group col-md-3">
                                                                <label for="form_control_1">Middle Name</label>
                                                                <input name="middleName" id="middleName"   class="form-control" placeholder="Enter The Middle Name" type="text" >
                                                            </div>
                                                            <div class="form-group col-md-3">
                                                                <label for="form_control_1">Sur Name</label>
                                                                <input name="surName" id="surName"   class="form-control" placeholder="Enter The Sur Name" type="text">
                                                            </div>
                                                            <div class="form-group col-md-3">
                                                                <label for="form_control_1">Date Of Birth</label>
                                                                <input name="DOB" id="DOB" class="form-control date-picker" placeholder="Enter Date Of Birth" type="text">
                                                            </div>
                                                            <div class="form-group col-md-3">
                                                                <label for="form_control_1">Gender</label>
                                                                <select name="Gender" id="Gender"  class="form-control" equired="">
                                                                    <option value="">Select Gender</option>
                                                                    <option value="Male">Male</option>
                                                                    <option value="Female">Female</option>
                                                                </select>
                                                            </div> 
                                                            <hr>
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <h4 class="bold text-center">Address Details</h4>


                                                                </div>
                                                            <div class="form-group col-md-3">
                                                                <label for="form_control_1">Address</label><div id="errordiv"></div>
                                                                <input name="address" id="address"  class="form-control" placeholder="Enter The Address" type="text">
                                                            </div>
                                                            <div class="form-group col-md-3">
                                                                <label for="form_control_1">City</label><div id="errordiv"></div>
                                                                <input name="city" id="city"  class="form-control" placeholder="Enter The City" type="text">
                                                            </div>
                                                            <div class="form-group col-md-3">
                                                                <label for="form_control_1">Pin Code</label><div id="errordiv"></div>
                                                                <input name="pincode" id="pincode"  class="form-control" placeholder="Enter The Pin Code" type="text">
                                                            </div>
                                                            </div>
                                                            <hr>
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <h4 class="bold text-center">Contact Details</h4>
                                                             </div>
                                                            <div class="form-group col-md-3">
                                                                <label for="form_control_1">Email</label>
                                                                <input name="email" id="email"  class="form-control" placeholder="Enter The Email Address"  type="email">
                                                            </div>
                                                            <div class="form-group col-md-3">
                                                                <label for="form_control_1">Phone</label><div id="errordiv"></div>
                                                                <input name="phone" id="phone"  class="form-control" placeholder="Enter The phone No." pattern="[0-9]{10}" type="text">
                                                            </div>
                                                            <div class="form-group col-md-3">
                                                                <label for="form_control_1">Mobile No. - 1*</label><div id="errordiv"></div>
                                                                <input name="mobileOne" id="mobileOne"  class="form-control" placeholder="Enter The Mobile No." pattern="[0-9]{10}" type="text">
                                                            </div>
                                                            <div class="form-group col-md-3">
                                                                <label for="form_control_1">Mobile No. - 2</label><div id="errordiv"></div>
                                                                <input name="mobileTwo" id="mobileTwo"  class="form-control" placeholder="Enter The Mobile No." pattern="[0-9]{10}" type="text">
                                                            </div>
                                                            </div>
                                                            <hr>
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <h4 class="bold text-center">Other Details</h4>
                                                                </div>
                                                            <div class="form-group col-md-3">
                                                                <label for="form_control_1">Occupation</label>
                                                                <input name="occupation" id="occupation"  class="form-control" placeholder="Enter The Occupation" type="text" >
                                                            </div>
                                                            <div class="form-group col-md-3">
                                                                <label for="form_control_1">Qualification</label>
                                                                <input name="qualification" id="qualification"  class="form-control" placeholder="Enter The Qualification" type="text" >
                                                            </div>
                                                            <div class="form-group col-md-3">
                                                                <label for="form_control_1">Designation</label>
                                                                <input name="designation" id="designation"  class="form-control" placeholder="Enter The Designation" type="text" >
                                                            </div>
                                                            <!--                                                    <div class="form-group col-md-3">
                                                                                                                    <label for="form_control_1">Branch ID</label>
                                                                                                                    <input name="branchId" id="branchId"  class="form-control" placeholder="Enter The Branch ID" type="text" >
                                                                                                      </div>-->
                                                            </div>
                                                            <div class="form-group col-md-3">
                                                                <label for="form_control_1">Student Portal ID</label>
                                                                <input name="studentPortal_Id" id="studentPortal_Id"  class="form-control" placeholder="Enter The Student Portal ID" type="text" >
                                                            </div>
                                                        </div>
                                                        <div class="form-actions noborder">
                                                            <input class="btn blue margin-top-20" type="submit" id="Btnmybtn"  value="Submit" name="submit">      
                                                            <button type="button" class="btn blue margin-top-20" onClick="checkclose();">Cancel</button>
                                                        </div>
                                                </form>
                                            </div>
                                        </div>
                                     <?php
                                            }
//                                                    echo $_POST['leaduniqueid'];
                                       
                                        ?>  
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
//            function getcity()
//            {
//                var q = $('#State').val();
//
//                var urlp = '<?php echo $web_url; ?>Employee/findCity.php?sId=' + q;
//                $.ajax({
//                    type: 'POST',
//                    url: urlp,
//                    success: function (data) {
//                        $('#CityDiv').html(data);
//                    }
//                }).error(function () {
//                    alert('An error occured');
//                });
//
//            }

            $('#frmparameter').submit(function (e) {

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
                            alert('Added Sucessfully.');
                            response = response.trim();
                           window.location.href = '<?php echo $web_url; ?>Employee/AddLead.php?token=' + response;
                        }
                    }

                });
            });



        </script>
    </body>
</html>