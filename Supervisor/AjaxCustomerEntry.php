<?php
error_reporting(0);
require_once('../common.php');
include('IsLogin.php');
$connect = new connect();
include('User_Paging.php');

if ($_POST['action'] == 'ListUser') {
    $where = "where 1=1";

    if (isset($_REQUEST['firstName'])) {
        if ($_POST['firstName'] != '') {

            $where .= " and  firstName like '%$_POST[firstName]%'";
        }
    }
    if (isset($_REQUEST['lastName'])) {
        if ($_POST['lastName'] != '') {

            $where .= " and  lastName like '%$_POST[lastName]%'";
        }
    }
    if (isset($_REQUEST['mobileNo'])) {
        if ($_POST['mobileNo'] != '') {

            $where .= " and  mobileNo like '%$_POST[mobileNo]%'";
        }
    }
    if (isset($_REQUEST['email'])) {
        if ($_POST['email'] != '') {

            $where .= " and  email like '%$_POST[email]%'";
        }
    }
    if ($_SESSION['EmployeeType'] == 'Supervisor') {
        if (isset($_REQUEST['employeeMasterId'])) {
            if ($_POST['employeeMasterId'] != '') {

                $where .= " and  employeeMasterId='$_POST[employeeMasterId]'";
            }
        }
    } else {
        $where .= " and  employeeMasterId='" . $_SESSION['EmployeeId'] . "'";
    }

    $filterstr = "SELECT * FROM `customerentry`  " . $where . " and isDelete='0'  and  istatus='1' order by  customerEntryId desc";
    $countstr = "SELECT count(*) as TotalRow FROM `customerentry`  " . $where . " and isDelete='0' and  istatus='1' ";

    $resrowcount = mysqli_query($dbconn, $countstr);
    $resrowc = mysqli_fetch_array($resrowcount);
    $totalrecord = $resrowc['TotalRow'];
    $per_page = $cateperpaging;
    $total_pages = ceil($totalrecord / $per_page);
    $page = $_REQUEST['Page'] - 1;
    $startpage = $page * $per_page;
    $show_page = $page + 1;



    $filterstr = $filterstr . " LIMIT $startpage, $per_page";
    // echo $filterstr;


    $resultfilter = mysqli_query($dbconn, $filterstr);
    if (mysqli_num_rows($resultfilter) > 0) {
        $i = 0;
        $serial = 0;
        $serial = ($page * $per_page);
?>
        <link href="<?php echo $web_url; ?>Supervisor/assets/global/plugins/datatables/datatables.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo $web_url; ?>Supervisor/assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" />
        <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
        <div class="table-responsive">
            <table class="table table-bordered table-hover center table-responsive dt-responsive" width="100%" id="tableC">
                <thead class="tbg">
                    <tr>
                        <th class="all">Sr.No</th>
                        <th class="desktop">Customer Name</th>
                        <th class="desktop">Company Name</th>
                        <th class="desktop">Employee Name</th>
                        <th class="desktop">Details</th>
                        <th class="none">State</th>
                        <th class="none">City</th>
                        <th class="desktop">Inquiry Source</th>
                        <th class="desktop">Category Of Customer</th>
                        <th class="none">No Of Inquiry</th>
                        <th class="none">No Of Booked Inquiry</th>
                        <th class="desktop">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($rowfilter = mysqli_fetch_array($resultfilter)) {
                        $i++;
                        $serial++;
                    ?>
                        <tr>
                            <td>
                                <div class="form-group form-md-line-input "><?php echo $serial; ?>
                                </div>
                            </td>
                            <td>
                                <div class="form-group form-md-line-input "><?php echo $rowfilter['title'] . ' ' . $rowfilter['firstName'] . ' ' . $rowfilter['MiddleName'] . ' ' . $rowfilter['lastName']; ?>
                                </div>
                            </td>
                            <td>
                                <div class="form-group form-md-line-input "><?php echo $rowfilter['companyName']; ?>
                                </div>
                            </td>
                            <td>
                                <div class="form-group form-md-line-input "><?php
                                                                            $Employee = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `employeemaster`  where isDelete='0' and istatus='1' and employeeMasterId='" . $rowfilter['employeeMasterId'] . "'"));
                                                                            echo $Employee['employeeName'];
                                                                            ?>
                                </div>
                            </td>
                            <td>
                                <div class="form-group form-md-line-input "><?php
                                                                            if ($rowfilter['email'] != '' && $rowfilter['mobileNo'] != '') {
                                                                                echo 'E:' . $rowfilter['email'] . ' <br>M:' . $rowfilter['mobileNo'];
                                                                            } else if ($rowfilter['email'] != '') {
                                                                                echo 'E:' . $rowfilter['email'];
                                                                            } else if ($rowfilter['mobileNo'] != '') {
                                                                                echo 'M:' . $rowfilter['mobileNo'];
                                                                            } else {
                                                                                echo '<center>-</center>';
                                                                            }
                                                                            ?>
                                </div>
                            </td>

                            <td>
                                <div class="form-group form-md-line-input "><?php
                                                                            $State = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `state`  where isDelete='0'  and  istatus='1' and stateId='" . $rowfilter['stateId'] . "'"));
                                                                            echo $State['stateName'];
                                                                            ?>
                                </div>
                            </td>
                            <td>
                                <div class="form-group form-md-line-input "><?php
                                                                            $State = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `city`  where isDelete='0'  and  istatus='1' and cityid='" . $rowfilter['cityId'] . "'"));
                                                                            echo $State['name'];
                                                                            ?>
                                </div>
                            </td>
                            <td>
                                <div class="form-group form-md-line-input "><?php
                                                                            $inquirySource = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `inquirysource`  where isDelete='0'  and  istatus='1' and inquirySourceId='" . $rowfilter['inquirySourceId'] . "'"));
                                                                            echo $inquirySource['inquirySourceName'];
                                                                            ?>
                                </div>
                            </td>
                            <td>
                                <div class="form-group form-md-line-input "><?php echo $rowfilter['categoryOfCustomer']; ?>
                                </div>
                            </td>
                            <td>
                                <div class="form-group form-md-line-input "><?php echo $rowfilter['noOfInquiry']; ?>
                                </div>
                            </td>
                            <td>
                                <div class="form-group form-md-line-input "><?php echo $rowfilter['noOfBookedInquiry']; ?>
                                </div>
                            </td>

                            <td>
                                <div class="form-group form-md-line-input">
                                    <!--                                <a  class="btn blue" href="<?php echo $web_url; ?>Supervisor/EditCustomerEntry.php?token=<?php echo $rowfilter['customerEntryId']; ?>" title="Edit"><i class="fa fa-edit iconshowFirst"></i></i></a>
                                <a  class="btn blue" onClick="javascript: return deletedata('Delete', '<?php echo $rowfilter['customerEntryId']; ?>');"   title="Delete"><i class="fa fa-trash-o iconshowFirst"></i></a>-->
                                    <input type="button" class="btn blue" value="=" title="CREATE INQUIRY" onclick="window.open('<?php echo $web_url; ?>Supervisor/AddLead.php?token=<?php echo $rowfilter['customerEntryId']; ?>' , 'popUpWindow', 'height=500,width=1250,left=100,top=100,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no,status=yes');">
                                    <!--                                <a class="btn blue" href="<?php echo $web_url; ?>Supervisor/AddLead.php?token=<?php echo $rowfilter['customerEntryId']; ?>" onclick="return popitup('Supervisor/AddLead.php')" title="CREATE INQUIRY"><i class="fa fa-bars"></i></a>-->
                                </div>
                            </td>

                        <?php
                    }
                        ?>

                        </tr>
                </tbody>
            </table>
        </div>
        <script src="<?php echo $web_url; ?>Supervisor/assets/global/plugins/datatables/datatables.js" type="text/javascript"></script>
        <script src="<?php echo $web_url; ?>Supervisor/assets/global/plugins/datatables/table-datatables-responsive.js" type="text/javascript"></script>
        <script>
            $(document).ready(function() {
                $('#tableC').DataTable({});
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

if ($_REQUEST['action'] == 'Delete') {
    $data = array(
        "isDelete" => '1',
        "strEntryDate" => date('d-m-Y H:i:s')
    );
    $where = ' where  	customerEntryId=' . $_REQUEST['ID'];
    $dealer_res = $connect->updaterecord($dbconn, 'customerentry', $data, $where);
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