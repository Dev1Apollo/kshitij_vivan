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
        <title><?php echo $ProjectName; ?> | Add Employee </title>
        <?php include_once 'include.php'; ?>
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
                                            <h1>Add Company
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
                                <a href="<?php echo $web_url; ?>admin/EmployeeMaster.php">List Of Employee</a>
                                <i class="fa fa-circle"></i>
                            </li>
                            <li>
                                <span>Add Employee</span>
                            </li>
                        </ul>

                        <div class="page-content-inner">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="portlet light ">
                                        <div class="portlet-title">
                                            <div class="caption grey-gallery">
                                             <i class="icon-settings grey-gallery"></i>
                                                <span class="caption-subject bold uppercase"> Add Employee</span>

                                            </div>
                                        </div>
                                        <div class="portlet-body form">
                                            <form  role="form"  method="POST"  action="" name="frmparameter"  id="frmparameter" enctype="multipart/form-data">
                                                <input type="hidden" value="AddAdminEmployee" name="action" id="action">
                                                <div class="form-body">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <h4 class="bold text-center">Personal Details</h4>


                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label for="form_control_1">Employee Name</label>
                                                            <input name="Employee" id="Employee"  class="form-control" placeholder="Enter Your Employee Name" type="text" required="">
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label for="form_control_1">Email</label>
                                                            <input name="Email" id="Email"  class="form-control" placeholder="Enter Your Email Address"  type="text">
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label for="form_control_1">Phone No.</label>
                                                            <input name="Phone" id="Phone"  class="form-control" placeholder="Enter Your Phone No." pattern="[0-9]{11}" type="text">
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label for="form_control_1">Mobile No.</label>
                                                            <input name="Mobile" id="Mobile"  class="form-control" placeholder="Enter Your Mobile No." pattern="[0-9]{10}" type="text">
                                                        </div>



                                                        <div class="form-group col-md-4">
                                                            <label for="form_control_1">Gender</label>
                                                            <select name="Gender" id="Gender"  class="form-control" required="">
                                                                <option value="">Select Gender</option>
                                                                <option value="Male">Male</option>
                                                                <option value="Female">Female</option>
                                                            </select>
                                                        </div> 
                                                    </div>
                                                    <hr>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <h4 class="bold text-center">Login Details</h4>


                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label for="form_control_1"> Type</label>
                                                            <select name="iEmployeeType" id="iEmployeeType"  class="form-control" required="">
                                                                <option value="">Select Type</option>
                                                                <option value="1">Employee</option>
                                                                <option value="2">Supervisor</option>
                                                            </select>
                                                        </div> 
                                                        <div class="form-group col-md-4">
                                                            <label for="form_control_1">Login ID</label><div id="errordiv"></div>
                                                            <input name="LoginID" id="LoginID"  class="form-control" placeholder="Enter Your Login ID." type="text" required="" onblur="return chkLoginId();">
                                                        </div> 
                                                        <div class="form-group col-md-4">
                                                            <label for="form_control_1">Password</label>
                                                            <input name="Password" id="Password"  class="form-control" placeholder="Enter Your Password." type="text" required="">
                                                        </div> 
                                                       
                                                    </div>
                                                    <hr>
                                                     <div class="row" id="divOtherDetails">
                                                        <div class="col-md-12">
                                                            <h4 class="bold text-center">Other Details</h4>


                                                        </div>

                                                        
                                                        <div class="form-group col-md-3">
                                                            <label for="form_control_1">Report To</label>
                                                            <?php
                                                            $queryEmp = "SELECT * FROM `employeemaster`  where isDelete='0'  and  istatus='1'  order by  employeeName asc";
                                                            $resultEmp = mysqli_query($dbconn,$queryEmp) or die(mysqli_error($dbconn));
                                                            echo '<select class="form-control" name="ReportTo" id="ReportTo" >';
                                                            echo "<option value='0' >Select Employee</option>";
                                                            while ($rowsEmp = mysqli_fetch_array($resultEmp)) {
                                                                echo "<option value='" . $rowsEmp['employeeMasterId'] . "'>" . $rowsEmp['employeeName'] . "</option>";
                                                            }
                                                            echo "</select>";
                                                            ?>
                                                        </div> 
                                                        <div class="form-group col-md-3">
                                                            <label for="form_control_1">Branch</label>
                                                            <?php
                                                            $querybranch = "SELECT * FROM `branchmaster`  where isDelete='0'  order by  branchid asc";
                                                            $resultbranch = mysqli_query($dbconn,$querybranch) or die(mysqli_error($dbconn));
                                                            echo '<select class="form-control" name="branchid" id="branchid" >';
                                                            echo "<option value='0' >Select Branch</option>";
                                                            while ($rowsbranch = mysqli_fetch_array($resultbranch)) {
                                                                echo "<option value='" . $rowsbranch['branchid'] . "'>" . $rowsbranch['branchname'] . "</option>";
                                                            }
                                                            echo "</select>";
                                                            ?>
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
                                </div>
                            </div>
                        </div> 
                    </div>
                </div>
            </div>
        </div>

        <?php include_once './footer.php'; ?>

        <script type="text/javascript">

            function checkclose() {
                window.location.href = '<?php echo $web_url; ?>admin/EmployeeMaster.php';
            }

            function chkLoginId(ID)
            {

                var q = $('#LoginID').val();

                var urlp = '<?php echo $web_url; ?>admin/findAdminCLoginID.php?ID=' + q;
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
                            $('#LoginID').val('');
                        }
                    }
                }).error(function () {
                    alert('An error occured');
                });

            }

            $('#frmparameter').submit(function (e) {

                e.preventDefault();
                var $form = $(this);
                $('#loading').css("display", "block");
                $.ajax({
                    type: 'POST',
                    url: '<?php echo $web_url; ?>admin/querydata.php',
                    data: $('#frmparameter').serialize(),
                    success: function (response) {
                        console.log(response);
                        //$("#Btnmybtn").attr('disabled', 'disabled');
                        if (response != 0)
                        {
                            $('#loading').css("display", "none");
                            $("#Btnmybtn").attr('disabled', 'disabled');
                            alert('Employee Added Sucessfully.');
                            response = response.trim();
                            window.location.href = '<?php echo $web_url; ?>admin/EmployeeMaster.php';
                        }
                    }

                });
            });

            $(document).ready(function () {
                $("#iEmployeeType").change(function () {
                    var iEmployeeType = $(this).val(); 
                    if(iEmployeeType == 2){
                        $("#divOtherDetails").hide();
                    } else {
                        $("#divOtherDetails").show();
                    }
                });
            });

        </script>
    </body>
</html>