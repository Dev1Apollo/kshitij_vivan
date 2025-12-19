<?php
ob_start();
error_reporting(E_ALL);
require_once '../common.php';
$connect = new connect();
include('IsLogin.php');

$result = mysqli_query($dbconn,"SELECT * FROM `customerentry` WHERE `customerEntryId`='" . $_REQUEST['token'] . "'");
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
        <title> <?php echo $ProjectName ?> |Edit Customer Entry</title>
        <?php include_once './include.php'; ?>       
    </head>

    <body class="page-container-bg-solid page-boxed">
        <?php
        include('header.php');
        ?>
        <div style="display: none; z-index: 10060;" id="loading">
            <img id="loading-image" src="<?php echo $web_url; ?>Employee/images/loader1.gif">
        </div>
        <div class="page-container">        
            <!--            <div class="page-content-wrapper">
                            <div class="page-head">
                                <div class="container">
                                    <div class="page-title">
                                        <h1>Edit Category
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
                            <a href="<?php echo $web_url; ?>Employee/CustomerEntry.php">List Of Customer Entry</a>
                            <i class="fa fa-circle"></i>
                        </li>

                        <li>
                            <span> Edit Customer Entry</span>

                        </li>
                    </ul>

                    <div class="page-content-inner">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="portlet light ">
                                    <div class="portlet-title">
                                        <div class="caption grey-gallery">
                                            <i class="icon-settings grey-gallery"></i>
                                            <span class="caption-subject bold uppercase"> Edit Customer Entry</span>
                                        </div>
                                    </div>
                                    <div class="portlet-body form">


                                        <form  role="form"  method="POST"  action="" name="frmparameter"  id="frmparameter" enctype="multipart/form-data">
                                            <input type="hidden" value="EditCustomerEntry" name="action" id="action">
                                            <input type="hidden" value="<?php echo $row['customerEntryId'] ?>" name="customerEntryId" id="customerEntryId">
                                           <div class="form-body">
                                                    <div class="form-group col-md-3">
                                                        <label for="form_control_1">Title*</label>
                                                        <input value="<?php echo $row['title'];?>" name="Title" id="Title"  class="form-control" placeholder="Enter The Title" type="text" required="">
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label for="form_control_1">First Name*</label>
                                                        <input value="<?php echo $row['firstName'];?>"  name="FirstName" id="FirstName"  class="form-control" placeholder="Enter The First Name" type="text" required="">
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label for="form_control_1">Middle Name</label>
                                                        <input  value="<?php echo $row['MiddleName'];?>" name="MiddleName" id="MiddleName"  class="form-control" placeholder="Enter The Middle Name" type="text" >
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label for="form_control_1">Last Name*</label>
                                                        <input  value="<?php echo $row['lastName'];?>" name="LastName" id="LastName"  class="form-control" placeholder="Enter The Last Name" type="text" required="">
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label for="form_control_1">Mobile No.*</label><div id="errordiv"></div>
                                                        <input value="<?php echo $row['mobileNo'];?>"  name="Mobile" id="Mobile" readonly="" class="form-control" placeholder="Enter The Mobile No." pattern="[0-9]{10}" type="text" >
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label for="form_control_1">Email</label>
                                                        <input  value="<?php echo $row['email'];?>" name="Email" id="Email"  class="form-control" placeholder="Enter The Email Address"  type="email">
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label for="form_control_1">Company Name</label>
                                                        <input  value="<?php echo $row['companyName'];?>" name="CompanyName" id="CompanyName"  class="form-control" placeholder="Enter The Company Name" type="text" >
                                                    </div>

                                                    <div class="form-group col-md-3">
                                                        <label for="form_control_1">Select State*</label>
                                                        <?php
                                                        $querys = "SELECT * FROM `state`  where isDelete='0'  and  istatus='1' order by  stateName asc";
                                                        $results = mysqli_query($dbconn,$querys) or die(mysqli_error($dbconn));
                                                        echo '<select class="form-control" name="State" id="State" required="" onchange="getcity();">';
                                                        echo "<option value='' >Select State Name</option>";
                                                        while ($rows = mysqli_fetch_array($results)) {
                                                            if($rows['stateId'] == $row['stateId']){
                                                            echo "<option value='" . $rows['stateId'] . "' selected>" . $rows['stateName'] . "</option>";
                                                        }else{
                                                        echo "<option value='" . $rows['stateId'] . "'>" . $rows['stateName'] . "</option>";
                                                        }
                                                        }
                                                        echo "</select>";
                                                        ?>
                                                    </div>


                                                    <div class="form-group col-md-3">
                                                        <label for="form_control_1">Select City*</label>
                                                        <div class="txt_field" id="CityDiv">
                                                            <?php
                                                            $queryc = "SELECT * FROM `city`  where isDelete='0'  and  istatus='1' order by  name asc";
                                                            $resultc = mysqli_query($dbconn,$queryc) or die(mysqli_error($dbconn));
                                                            echo '<select class="form-control" name="City" id="City" required="">';
                                                            echo "<option value='' >Select City Name</option>";
                                                            while ($rowc = mysqli_fetch_array($resultc)) {
                                                                 if($rowc['cityid'] == $row['cityId']){
                                                            echo "<option value='" . $rowc['cityid'] . "' selected>" . $rowc['name'] . "</option>";
                                                           }else{
                                                        echo "<option value='" . $rowc['cityid'] . "'>" . $rowc['name'] . "</option>";
                                                           }
                                                        }
                                                            echo "</select>";
                                                            ?>
                                                        </div>
                                                    </div> 
                                                    <div class="form-group col-md-3">
                                                        <label for="form_control_1">Select Source Of Inquiry </label>
                                                        <?php
                                                        $querysInq = "SELECT * FROM `inquirysource`  where isDelete='0'  and  istatus='1' order by  inquirySourceName asc";
                                                        $resultsInq = mysqli_query($dbconn,$querysInq) or die(mysqli_error($dbconn));
                                                        echo '<select class="form-control" name="InquirySource" id="InquirySource" >';
                                                        echo "<option value='' >Select Source Of Inquiry</option>";
                                                        while ($rowsInq = mysqli_fetch_array($resultsInq)) {
                                                              if($rowsInq['inquirySourceId'] == $row['inquirySourceId']){
                                                            echo "<option value='" . $rowsInq['inquirySourceId'] . "' selected>" . $rowsInq['inquirySourceName'] . "</option>";
                                                          }else{
                                                         echo "<option value='" . $rowsInq['inquirySourceId'] . "' >" . $rowsInq['inquirySourceName'] . "</option>";
                                                         }
                                                        }
                                                        echo "</select>";
                                                        ?>
                                                    </div> 

                                                    <div class="form-group col-md-3">
                                                        <label for="form_control_1">Category Of Customer</label>
                                                        <select name="CategoryOfCustomer" id="CategoryOfCustomer"  class="form-control" >
                                                            <option value="">Select Category Of Customer</option>
                                                            <option value="Inquired" <?php if($row['categoryOfCustomer'] == 'Inquired'){echo 'selected';}?>>Inquired</option>
                                                            <option value="Regular" <?php if($row['categoryOfCustomer'] == 'Regular'){echo 'selected';}?>>Regular</option>
                                                            <option value="Silver" <?php if($row['categoryOfCustomer'] == 'Silver'){echo 'selected';}?>>Silver</option>
                                                            <option value="Gold" <?php if($row['categoryOfCustomer'] == 'Gold'){echo 'selected';}?>>Gold</option>
                                                            <option value="Platinum" <?php if($row['categoryOfCustomer'] == 'Platinum'){echo 'selected';}?>>Platinum</option>
                                                        </select>
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
            window.location.href = '<?php echo $web_url; ?>Employee/CustomerEntry.php';
        }

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
        function getcity()
            {
                var q = $('#State').val();

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

        $('#frmparameter').submit(function (e) {

            e.preventDefault();
            var $form = $(this);
            $('#loading').css("display", "block");
            $.ajax({
                type: 'POST',
                url: '<?php echo $web_url; ?>Employee/querydata.php',
                data: $('#frmparameter').serialize(),
                success: function (response) {
                    //alert(response);
                    console.log(response);
                    //$("#Btnmybtn").attr('disabled', 'disabled');
                      if (response != 0)
                    {
                        $('#loading').css("display", "none");
                        $("#Btnmybtn").attr('disabled', 'disabled');
                        alert(' Edited Sucessfully.');
                        response = response.trim();
                       window.location.href = '<?php echo $web_url;?>Employee/CustomerEntry.php';
                    }
                }

            });
        });

    </script>

</body>
</html>
