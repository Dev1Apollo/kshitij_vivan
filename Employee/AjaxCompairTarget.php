<?php
error_reporting(0);
require_once('../common.php');
include('IsLogin.php');
$connect = new connect();
include ('User_Paging.php');


if ($_POST['action'] == 'ListUser') {
    $where = "where 1=1";

    if ($_REQUEST['targetMonth'] != NULL && isset($_REQUEST['targetMonth']))
        $where .=" and month=" . $_REQUEST['targetMonth'] . " ";
//        $where.=" and DATE_FORMAT(STR_TO_DATE(strEntryDate, '%d-%m-%Y'), '%Y-%m-%d')>=STR_TO_DATE('" . $_REQUEST['FormDate'] . "','%d-%m-%Y')";
    if ($_REQUEST['targetYear'] != NULL && isset($_REQUEST['targetYear']))
        $where .=" and year=" . $_REQUEST['targetYear'] . " ";
//        $where.=" and DATE_FORMAT(STR_TO_DATE(strEntryDate, '%d-%m-%Y'), '%Y-%m-%d')<=STR_TO_DATE('" . $_REQUEST['ToDate'] . "','%d-%m-%Y')";

    $filterstr = "SELECT * FROM target  " . $where . " and iBranchId = " . $_SESSION['branchid'] . " and isDelete='0'  and  iStatus='1' order by itargetId desc";
//    $countstr = "SELECT count(*) as TotalRow FROM target  " . $where . " and iBranchId = " . $_SESSION['branchid'] . " and isDelete='0' and  iStatus='1'";
//
//    $resrowcount = mysqli_query($dbconn,$countstr);
//    $resrowc = mysqli_fetch_array($resrowcount);
//    $totalrecord = $resrowc['TotalRow'];
//    $per_page = $cateperpaging;
//    $total_pages = ceil($totalrecord / $per_page);
//    $page = $_REQUEST['Page'] - 1;
//    $startpage = $page * $per_page;
//    $show_page = $page + 1;
//    $filterstr = $filterstr . " LIMIT $startpage, $per_page";

    $resultfilter = mysqli_query($dbconn,$filterstr);
    if (mysqli_num_rows($resultfilter) > 0) {
        $i = 0;
        $serial = 0;
       // $serial = ($page * $per_page);
        ?>  
        <link href="<?php echo $web_url; ?>Employee/assets/global/plugins/datatables/datatables.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo $web_url; ?>Employee/assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" />
        <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script> 

        <table class="table table-bordered table-hover center table-responsive" width="100%" id="tableC">
            <thead>
                <tr>
                    <th>Detail</th>
                    <th>Target</th>
                    <th>Achieved</th>
                    <th>Percentage</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $rowTarget = mysqli_fetch_array($resultfilter);
                ?>
                <tr>
                    <td>Walk in</td>
                    <td> <?php
                        if (isset($rowTarget['targetInquiry'])) {
                            echo $rowTarget['targetInquiry'];
                        } else {
                            echo 0;
                        }
                        ?> </td>
                    <td> <?php
                        $inquiry = mysqli_fetch_array(mysqli_query($dbconn,"SELECT count(*) as TotalRow FROM lead where  MONTH(STR_TO_DATE(walkin_datetime,'%d-%m-%Y'))='" . $_REQUEST['targetMonth'] . "' and YEAR(STR_TO_DATE(walkin_datetime,'%d-%m-%Y'))='" . $_REQUEST['targetYear'] . "' and isNewInquiry='0' and employeeMasterId='" . $_SESSION['EmployeeId'] . "'"));
                        if ($inquiry['TotalRow'] != 0) {
                            echo $inquiry['TotalRow'];
                        } else {
                            echo "0";
                        }
                        ?></td>
                    <td> <?php
                        if ($rowTarget['targetInquiry'] != '' || $rowTarget['targetInquiry'] != null) {
                            if ($inquiry['TotalRow'] == '' || $inquiry['TotalRow'] == 0) {
                                echo "-";
                            } else {
                                $walkinPer = ($inquiry['TotalRow'] * 100) / $rowTarget['targetInquiry'];
                                echo number_format($walkinPer, 2) . "%";
                            }
                        } else {
                            echo "0 %";
                        }
                        ?> </td>
                </tr>
                <tr>
                    <td>Enrollment</td>
                    <td> <?php
                        if (isset($rowTarget['targetEnroll'])) {
                            echo $rowTarget['targetEnroll'];
                        } else {
                            echo 0;
                        }
                        ?></td>
                    <td><?php
                        $filterEnroll = mysqli_fetch_array(mysqli_query($dbconn,"SELECT count(*) as TotalRow FROM lead where  MONTH(STR_TO_DATE(nextFollowupModifyDate,'%d-%m-%Y'))='" . $_REQUEST['targetMonth'] . "' and YEAR(STR_TO_DATE(nextFollowupModifyDate,'%d-%m-%Y'))='" . $_REQUEST['targetYear'] . "' and isNewInquiry='0' and statusId='3' and employeeMasterId='" . $_SESSION['EmployeeId'] . "'"));
                        if ($filterEnroll['TotalRow'] != 0) {
                            echo $filterEnroll['TotalRow'];
                        } else {
                            echo "0";
                        }
                        ?></td>
                    <td> <?php
                        if ($rowTarget['targetEnroll'] != '' || $rowTarget['targetEnroll'] != null) {
                            if ($filterEnroll['TotalRow'] == '' || $filterEnroll['TotalRow'] == 0) {
                                echo "-";
                            } else {
                                $enrollPer = ($filterEnroll['TotalRow'] * 100 ) / $rowTarget['targetEnroll'];
                                echo number_format($enrollPer, 2) . "%";
                            }
                        } else {
                            echo "0 %";
                        }
                        ?> </td>
                </tr>
                <tr>
                    <td>Booking</td>
                    <td><?php
                        if (isset($rowTarget['targetBooking'])) {
                            echo $rowTarget['targetBooking'];
                        } else {
                            echo 0;
                        }
                        ?></td>
                    <td><?php
                        $filterBooking = mysqli_fetch_array(mysqli_query($dbconn,"SELECT sum(booking_amount) as TotalRow FROM lead where  MONTH(STR_TO_DATE(nextFollowupModifyDate,'%d-%m-%Y'))='" . $_REQUEST['targetMonth'] . "' and YEAR(STR_TO_DATE(nextFollowupModifyDate,'%d-%m-%Y'))='" . $_REQUEST['targetYear'] . "' and isNewInquiry='0' and statusId='3' and employeeMasterId='" . $_SESSION['EmployeeId'] . "'"));
                        if ($filterBooking['TotalRow'] != 0) {
                            echo $filterBooking['TotalRow'];
                        } else {
                            echo "0";
                        }
                        ?></td>
                    <td> <?php
                        if ($rowTarget['targetBooking'] != '' || $rowTarget['targetBooking'] != null) {
                            if ($filterBooking['TotalRow'] == '' || $filterBooking['TotalRow'] == 0) {
                                echo "-";
                            } else {
                                $bookPer = ($filterBooking['TotalRow'] * 100) / $rowTarget['targetBooking'];
                                echo number_format($bookPer, 2) . "%";
                            }
                        } else {
                            echo "0 %";
                        }
                        ?></td>
                </tr>
                <tr>
                    <td>Collection</td>
                    <td> <?php
                        if (isset($rowTarget['targetCollection'])) {
                            echo $rowTarget['targetCollection'];
                        } else {
                            echo 0;
                        }
                        ?></td>
                    <td> <?php
                        $filterCollection = mysqli_fetch_array(mysqli_query($dbconn,"select sum(amount) as collection from studentfee INNER JOIN studentadmission ON studentadmission.stud_id = studentfee.stud_id where MONTH(STR_TO_DATE(studentfee.payDate,'%d-%m-%Y'))='" . $_REQUEST['targetMonth'] . "' and YEAR(STR_TO_DATE(studentfee.payDate,'%d-%m-%Y'))='" . $_REQUEST['targetYear'] . "' and studentadmission.branchId='" . $_SESSION['branchid'] . "'"));
                        if ($filterCollection['collection'] != 0) {
                            echo $filterCollection['collection'];
                        } else {
                            echo 0;
                        }
                        ?></td>
                    <td> <?php
                        if ($rowTarget['targetCollection'] != '' || $rowTarget['targetCollection'] != null) {
                            if ($filterCollection['collection'] == '' || $filterCollection['collection'] == 0) {
                                echo "-";
                            } else {
                                $collectionPer = ($filterCollection['collection'] * 100) / $rowTarget['targetCollection'];
                                echo number_format($collectionPer, 2) . "%";
                            }
                        } else {
                            echo "0 %";
                        }
                        ?></td>
                </tr>                                                    
                <tr>
                    <td>FPS</td>
                    <td> <?php
                        if (isset($rowTarget['targetFPS'])) {
                            echo $rowTarget['targetFPS'];
                        } else {
                            echo 0;
                        }
                        ?></td>
                    <td> <?php echo 0; // $rowTarget['achieveFPS']   ?></td>
                    <td> <?php
                        if ($rowTarget['targetFPS'] != '' || $rowTarget['targetFPS'] != null) {
                            if ($rowTarget['targetFPS'] == 0 && $rowTarget['achieveFPS'] == 0) {
                                echo "-";
                            } else {
                                $fpsPer = ( $rowTarget['achieveFPS'] * 100 ) / $rowTarget['targetFPS'];
                                echo $fpsPer . '%';
                            }
                        } else {
                            echo "0 %";
                        }
                        ?> </td>
                </tr>
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
//
//if ($_REQUEST['action'] == 'Delete') {
//    $data = array(
//        "isDelete" => '1',
//        "strEntryDate" => date('d-m-Y H:i:s')
//    );
//    $where = ' where itargetId=' . $_REQUEST['ID'];
//    $dealer_res = $connect->updaterecord($dbconn,'target', $data, $where);
//}
//
//if ($_REQUEST['action'] == 'FreezeTarget') {
//    $data = array(
//        "isFreeze" => '1',
//        "strEntryDate" => date('d-m-Y H:i:s')
//    );
//    $where = ' where itargetId=' . $_REQUEST['ID'];
//    $dealer_res = $connect->updaterecord($dbconn,'target', $data, $where);
//}
//?>
<?php // if ($totalrecord > $per_page) { ?>
<!--    <div class="row">
        <div class="col-lg-12 m-pager">
            <div class="col-lg-12 col-md-12  col-xs-12 col-sm-12 padding-5 bottom-border-verydark" style="text-align: center;">
                <div class="form-actions noborder">-->
                    <?php
//                    echo '<ul>';
//                    if ($totalrecord > $per_page) {
//                        echo paginate($reload = '', $show_page, $total_pages);
//                    }
//                    echo "</ul>";
//                    ?>
<!--                </div>
            </div>
        </div>
    </div>-->
<?php // } ?>      