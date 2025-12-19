<?php
error_reporting(0);
require_once('../common.php');
include('IsLogin.php');
$connect = new connect();
include ('User_Paging.php');

if ($_POST['action'] == 'ListUser') {
    $where = "where 1=1";

    $filterstr = "SELECT * FROM `lead`  where statusId='1' and employeeMasterId='" . $_SESSION['EmployeeId'] . "'  order by  leadId desc";
    $countstr = "SELECT count(*) as TotalRow FROM `lead`  where statusId='1' and employeeMasterId='" . $_SESSION['EmployeeId'] . "' ";

    $resrowcount = mysqli_query($dbconn,$countstr);
    $resrowc = mysqli_fetch_array($resrowcount);
    $totalrecord = $resrowc['TotalRow'];
    $per_page = $cateperpaging;
    $total_pages = ceil($totalrecord / $per_page);
    $page = $_REQUEST['Page'] - 1;
    $startpage = $page * $per_page;
    $show_page = $page + 1;



    $filterstr = $filterstr . " LIMIT $startpage, $per_page";
// echo $filterstr;


    $resultfilter = mysqli_query($dbconn,$filterstr);
    if (mysqli_num_rows($resultfilter) > 0) {
        $i = 0;
        $serial = 0;
        $serial = ($page * $per_page);
        ?>  
        <link href="<?php echo $web_url; ?>Employee/assets/global/plugins/datatables/datatables.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo $web_url; ?>Employee/assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" />
        <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script> 
        <form  role="form"  method="POST"  action="" name="frmparameter"  id="frmparameter" enctype="multipart/form-data">
            <input type="hidden" value="AddTransferInquiry" name="action" id="action">
            <div class="form-body">

                <div class="row m-search-box">
                    <div class="col-md-12"  >

                        <div class="form-group col-md-offset-3 col-md-4">

                            <?php
                            $queryEmp = "SELECT * FROM `employeemaster`  where isDelete='0'  and  istatus='1' and employeeMasterId!='" . $_SESSION['EmployeeId'] . "' order by  employeeMasterId asc";
                            $resultEmp = mysqli_query($dbconn,$queryEmp) or die(mysqli_error($dbconn));
                            echo '<select class="form-control" name="TransferEmployee" id="TransferEmployee" required="">';
                            echo "<option value='' >Select Assign Employee</option>";
                            while ($rowsEmp = mysqli_fetch_array($resultEmp)) {
                                echo "<option value='" . $rowsEmp['employeeMasterId'] . "'>" . $rowsEmp['employeeName'] . "</option>";
                            }
                            echo "</select>";
                            ?>
                        </div> 

                        <div class="form-group col-md-4">
                            <input class="btn blue " type="submit" id="Btnmybtn"  value="Submit" name="submit">      
                            <button type="button" class="btn blue " onClick="checkclose();">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>


            <script src="<?php echo $web_url; ?>Employee/assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js" type="text/javascript"></script>
            <script src="<?php echo $web_url; ?>Employee/assets/global/plugins/bootstrap-datetimepicker/components-date-time-pickers.js" type="text/javascript"></script>


            <script type="text/javascript">


                                function checkclose() {
                                    window.location.href = '<?php echo $web_url; ?>Employee/TransferInquiry.php';
                                }

                                $('#frmparameter').submit(function (e) {

                                    e.preventDefault();
                                    var $form = $(this);


                                    $.ajax({
                                        type: 'POST',
                                        url: '<?php echo $web_url; ?>Employee/querydata.php',
                                        data: $('#frmparameter').serialize(),
                                        success: function (response) {
                                            // alert(response);
                                            $("#Btnmybtn").attr('disabled', 'disabled');
                                            alert('Added Sucessfully.');
                                            window.location.href = '<?php echo $web_url; ?>Employee/TransferInquiry.php';
                                        }
                                    });
                                });





            </script> 

            <link href="<?php echo $web_url; ?>Employee/assets/global/plugins/datatables/datatables.css" rel="stylesheet" type="text/css" />

            <table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="tableC">
                <thead class="tbg">
                    <tr>

                        <th>
                            <div class="md-checkbox">
                                <input type="checkbox"  onclick="javascript:CheckAll();" id="check_listall" class="md-check" value="">
                                <label for="check_listall">
                                    <span></span>
                                    <span class="check"></span>
                                    <span class="box"></span>
                                </label>

                            </div>
                        </th>
                        <th class="all">Sr.No</th>
                        <th class="desktop">Lead Unique ID</th>
                        <th class="desktop">Customer Name</th>
                        <th class="desktop">Employee Name</th>
                        <th class="desktop">Source Of Lead</th>
                        <th class="desktop">Entry Date</th>

                        <th class="desktop">Inquiry Status</th>

                        <th class="desktop">Next FollowUp Date</th>
                        <th class="desktop">FollowUp Comment</th>

        <!--                    <th class="desktop">Action</th>-->
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($rowfilter = mysqli_fetch_array($resultfilter)) {
                        $get_inquirysource = mysqli_fetch_array(mysqli_query($dbconn,"select * from customerentry where customerEntryId = '" . $rowfilter['customerEntryId'] . "'"));
                        $get_source = mysqli_fetch_array(mysqli_query($dbconn,"select * from inquirysource where inquirySourceId = '" . $get_inquirysource['inquirySourceId'] . "'"));

                        $i++;
                        $serial++;
                        ?>
                        <tr>
                            <td>
                                <!--                                <div class="form-group form-md-line-input ">-->
                                <!--                                    <div class="form-group form-md-checkboxes">-->
                                <!--                                        <div class="md-checkbox-inline">-->
                                <div class="md-checkbox">
                                    <input type="checkbox" name="check_list[]" id="check_list<?php echo $i; ?>" class="md-check " value="<?php echo $rowfilter['leadId']; ?> ">
                                    <label for="check_list<?php echo $i; ?>">
                                        <span></span>
                                        <span class="check"></span>
                                        <span class="box"></span>
                                    </label>
                                </div>
                                <!--                                         </div>-->
                                <!--                                    </div>-->
                                <!--                                </div>-->
                            </td>

                            <td>
                                <div class="form-group form-md-line-input "><?php echo $serial; ?> 
                                </div>
                            </td>
                            <td>
                                <div class="form-group form-md-line-input "><?php echo $rowfilter['leaduniqueid']; ?> 
                                </div>
                            </td>
                            <td>
                                <div class="form-group form-md-line-input "><?php
                                    $customerentry = "SELECT * FROM `customerentry` where isDelete='0'  and  istatus='1' and  customerEntryId='" . $rowfilter['customerEntryId'] . "'";
                                    $resultCustomer = mysqli_query($dbconn,$customerentry);
                                    $rowCustomer = mysqli_fetch_array($resultCustomer);
                                    echo $rowCustomer['title'] . ' ' . $rowCustomer['firstName'] . ' ' . $rowCustomer['MiddleName'] . ' ' . $rowCustomer['lastName'];
                                    ?> 
                                </div>
                            </td>  
                            <td>
                                <div class="form-group form-md-line-input "><?php
                                    $employeemaster = mysqli_fetch_array(mysqli_query($dbconn,"SELECT * FROM `employeemaster`  where isDelete='0'  and  istatus='1' and employeeMasterId='" . $rowfilter['employeeMasterId'] . "'"));
                                    echo $employeemaster['employeeName'];
                                    ?> 
                                </div>
                            </td>

                            <td>
                                <div class="form-group form-md-line-input "><?php
                                    echo $get_source['inquirySourceName'];
//                                
                                    ?> 
                                </div>
                            </td>
                            <td>
                                <div class="form-group form-md-line-input "><?php
                                    echo $rowfilter['strEntryDate'];
//                                $filterstr = "SELECT count(*) as count FROM `leadfollowup`  where leadId='" . $rowfilter['leadId'] . "'";
//                                $resultfilter = mysqli_query($dbconn,$filterstr);
//                                $rowfilterLF = mysqli_fetch_array($resultfilter);
//                                echo $rowfilterLF['count'];
                                    ?> 
                                </div>
                            </td> 




                            <td>
                                <div class="form-group form-md-line-input "><?php
                                    $inquiryStatus = mysqli_fetch_array(mysqli_query($dbconn,"SELECT * FROM `status`  where isDelete='0'  and  istatus='1' and statusId='" . $rowfilter['statusId'] . "'"));
                                    echo $inquiryStatus['statusName'];
                                    ?> 
                                </div>
                            </td>
                            <td>
                                <div class="form-group form-md-line-input "><?php
                                    echo $rowfilter['nextFollowupDate'];
                                    ?> 
                                </div>
                            </td>
                            <td>
                                <div class="form-group form-md-line-input "><?php echo $rowfilter['comment']; ?> 
                                </div>
                            </td>
            <!--                        <td >-->
                    <!--                        <td style="width: 10%">
                                    <div class="form-group form-md-line-input">
                                        <a  class="btn blue" href="<?php echo $web_url; ?>Employee/AddInquiryFollowup.php?token=<?php echo $rowfilter['leadId']; ?>&cid=<?php echo $rowfilter['customerEntryId']; ?>" title="INQUIRY FOLLOWUP"><i class="fa fa-bars"></i></a>
                                    </div>
                                </td>-->
                        </tr>
                        <?php
                        $i++;
                    }
                    ?>


                </tbody>
            </table>
            <script src="<?php echo $web_url; ?>Employee/assets/global/plugins/datatables/datatables.js" type="text/javascript"></script>
            <script src="<?php echo $web_url; ?>Employee/assets/global/plugins/datatables/table-datatables-responsive.js" type="text/javascript"></script>
            <script>
                                    $(document).ready(function () {
                                        $('#tableC').DataTable({
                                        });
                                    });
            </script>
            <?php
        } else {
            ?>
            <div class="row">
                <div class="col-lg-12 col-md-12  col-xs-12 col-sm-12 padding-5 bottom-border-verydark">
                    <div class="alert alert-info clearfix profile-information padding-all-10 margin-all-0 backgroundDark">
                        <h1 class="font-white text-center"> No Data Found ! </h1>
                    </div>   
                </div>
            </div>
            <?php
        }
    }
    ?>
    <?php if ($totalrecord > $per_page) { ?>
        <div class="row">
            <div class="col-lg-12 m-pager">

                <div class="col-lg-12 col-md-12  col-xs-12 col-sm-12 padding-5 bottom-border-verydark" style="text-align: center;">
                    <div class="form-actions noborder">
                        <?php
                        echo '<ul>';

                        if ($totalrecord > $per_page) {
                            echo paginate($reload = '', $show_page, $total_pages);
                        }
                        echo "</ul>";
                        ?>
                    </div>
                </div>

            </div>
        </div>
    <?php } ?>



    <script>
        function CheckAll()
        {

            if ($('#check_listall').is(":checked"))
            {
                // alert('cheked');
                $('input[type=checkbox]').each(function () {
                    $(this).prop('checked', true);
                });
            } else
            {
                //alert('cheked fail');
                $('input[type=checkbox]').each(function () {
                    $(this).prop('checked', false);
                });
            }
        }
    </script>
</form>

