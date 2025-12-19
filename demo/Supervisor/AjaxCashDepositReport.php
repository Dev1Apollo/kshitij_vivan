<?php
error_reporting(0);
require_once('../common.php');
include('IsLogin.php');
$connect = new connect();
include('User_Paging.php');


if ($_POST['action'] == 'ListUser') {
    //$where = "where 1=1 and studentadmission.branchId = '" . $_SESSION['branchid'] . "'";
    //$whereEmp = " and sa.branchId= '" . $_SESSION['branchid'] . "'";
    $where = "";
    $whereEmp = "";
    if ($_SESSION['EmployeeType'] == 'Supervisor') {
        if ($_POST['branchid'] != NULL && isset($_POST['branchid']))
            $where .= " where 1=1 and studentadmission.branchId ='" . $_POST['branchid'] . "'";
            $whereEmp = " and sa.branchId= '" . $_POST['branchid'] . "'";
    } else {
        $where .= " where 1=1 and studentadmission.branchId =" . $_SESSION['branchid'] . "'";
        $whereEmp = " and sa.branchId= '" . $_SESSION['branchid'] . "'";
    }
    $whereSub = "";
    if ($_REQUEST['studentPortal_Id'] != NULL && isset($_REQUEST['studentPortal_Id'])) {
        $where .= " and studentadmission.studentPortal_Id in(" . implode(',', $_REQUEST[studentPortal_Id]) . ")";
        $whereSub .= " and sa.studentPortal_Id in(" . implode(',', $_REQUEST[studentPortal_Id]) . ")";
    }

    if ($_REQUEST['bankDeposit'] != NULL && isset($_REQUEST['bankDeposit'])) {
        $where .= " and studentfee.toBank in(" . implode(',', $_REQUEST[bankDeposit]) . ")";
        $whereSub .= " and sf.toBank in(" . implode(',', $_REQUEST[bankDeposit]) . ")";
    }

    if ($_REQUEST['FromDate'] != NULL && isset($_REQUEST['FromDate'])) {
        $where .= " and STR_TO_DATE(studentfee.depositDate,'%d-%m-%Y')>= STR_TO_DATE('$_REQUEST[FromDate]','%d-%m-%Y')";
        //        $whereSub.=" and STR_TO_DATE(studentfee.depositDate,'%d-%m-%Y')>= STR_TO_DATE('$_REQUEST[FromDate]','%d-%m-%Y')";
    }

    if ($_REQUEST['ToDate'] != NULL && isset($_REQUEST['ToDate'])) {
        $where .= " and STR_TO_DATE(studentfee.depositDate,'%d-%m-%Y')<=STR_TO_DATE('$_REQUEST[ToDate]','%d-%m-%Y')";
        //        $whereSub.= " and STR_TO_DATE(studentfee.depositDate,'%d-%m-%Y')<=STR_TO_DATE('$_REQUEST[ToDate]','%d-%m-%Y')";
    }

    $filterstr = "select depositDate,studentfee.comments,studentfee.remarks,studentfee.toBank,
   (SELECT SUM(depositAmount) from studentfee sf join studentadmission sa on sa.stud_id=sf.stud_id where deposit LIKE '%yes%' " . $whereSub . $whereEmp . "
    and STR_TO_DATE(sf.depositDate,'%d-%m-%Y') = STR_TO_DATE(studentfee.depositDate,'%d-%m-%Y'))as depositAmount,
    (SELECT SUM(amount) from studentfee sf where 
    STR_TO_DATE(sf.depositDate,'%d-%m-%Y') = STR_TO_DATE(studentfee.depositDate,'%d-%m-%Y'))as amount,
    (SELECT SUM(amount) from studentfee  sf join studentadmission sa on sa.stud_id=sf.stud_id where  sf.paymentMode=1 " . $whereSub . $whereEmp . " and deposit LIKE '%yes%' 
    and STR_TO_DATE(sf.depositDate,'%d-%m-%Y') = STR_TO_DATE(studentfee.depositDate,'%d-%m-%Y'))as cash,
    (SELECT SUM(amount) from studentfee sf join studentadmission sa on sa.stud_id=sf.stud_id where sf.paymentMode=2 " . $whereSub . $whereEmp . " and deposit LIKE '%yes%' 
    and STR_TO_DATE(sf.depositDate,'%d-%m-%Y') = STR_TO_DATE(studentfee.depositDate,'%d-%m-%Y'))as cheque,
    (SELECT SUM(amount) from studentfee sf join studentadmission sa on sa.stud_id=sf.stud_id where sf.paymentMode=3 " . $whereSub . $whereEmp . " and deposit LIKE '%yes%' 
    and STR_TO_DATE(sf.depositDate,'%d-%m-%Y') = STR_TO_DATE(studentfee.depositDate,'%d-%m-%Y'))as paytm,
    (SELECT SUM(amount) from studentfee sf join studentadmission sa on sa.stud_id=sf.stud_id where sf.paymentMode=5 " . $whereSub . $whereEmp . "  
    and STR_TO_DATE(sf.depositDate,'%d-%m-%Y') = STR_TO_DATE(studentfee.depositDate,'%d-%m-%Y'))as BankTransfer 
    from studentfee join studentadmission on studentadmission.stud_id=studentfee.stud_id " . $where . " and deposit LIKE '%yes%' GROUP by STR_TO_DATE(studentfee.depositDate,'%d-%m-%Y')";

    //    $filterstr = "select depositDate,SUM(amount) as amount,SUM(depositAmount) as depositAmount,studentfee.comments,studentfee.remarks,studentfee.toBank,"
    //            . "(SELECT SUM(amount) from studentfee sf where sf.depositDate=studentfee.depositDate and sf.stud_id = studentadmission.stud_id and sf.paymentMode=1 and deposit LIKE '%yes%' ".$whereSub." )as cash, "
    //            . "(SELECT SUM(amount) from studentfee sf where sf.depositDate=studentfee.depositDate and sf.stud_id = studentadmission.stud_id and sf.paymentMode=2 and deposit LIKE '%yes%' ".$whereSub.")as cheque, "
    //            . "(SELECT SUM(amount) from studentfee sf where sf.depositDate=studentfee.depositDate and sf.stud_id = studentadmission.stud_id and sf.paymentMode=3 and deposit LIKE '%yes%' ".$whereSub.")as paytm, "
    //            . "(SELECT SUM(amount) from studentfee sf where sf.depositDate=studentfee.depositDate and sf.stud_id = studentadmission.stud_id and sf.paymentMode=5 and deposit LIKE '%yes%' ".$whereSub.")as BankTransfer "
    //            . "from studentfee join studentadmission on studentadmission.stud_id=studentfee.stud_id  " . $where . " and  deposit LIKE '%yes%' GROUP by STR_TO_DATE(studentfee.depositDate,'%d-%m-%Y')";

    $countstr = "SELECT count(DISTINCT STR_TO_DATE(studentfee.depositDate,'%d-%m-%Y')) as TotalRow  from studentfee Join  studentadmission on studentfee.stud_id = studentadmission.stud_id " . $where . " ";


    $resrowcount = mysqli_query($dbconn, $countstr);
    $resrowc = mysqli_fetch_array($resrowcount);
    $totalrecord = $resrowc['TotalRow'];
    $per_page = $cateperpaging;
    $total_pages = ceil($totalrecord / $per_page);
    $page = $_REQUEST['Page'] - 1;
    $startpage = $page * $per_page;
    $show_page = $page + 1;



    $filterstr = $filterstr . " LIMIT $startpage, $per_page";



    $resultfilter = mysqli_query($dbconn, $filterstr);
    if (mysqli_num_rows($resultfilter) > 0) {
        $i = 0;
        $serial = 0;
        $serial = ($page * $per_page);
?>
        <link href="<?php echo $web_url; ?>Supervisor/assets/global/plugins/datatables/datatables.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo $web_url; ?>Supervisor/assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" />
        <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>

        <table class="table table-bordered table-hover center table-responsive dt-responsive" width="100%" id="tableC">
            <thead class="tbg">
                <tr>
                    <th class="all">Sr.No</th>
                    <!--                    <th class="desktop">Student Name</th>-->
                    <th class="desktop">Date</th>
                    <!--<th class="desktop">Amount</th>-->
                    <th class="desktop">Cash</th>
                    <th class="desktop">Cheque</th>
                    <th class="desktop">Paytm</th>
                    <th class="desktop">Bank transfer</th>
                    <!--                    <th class="desktop">Deposit Date</th>-->
                    <th class="desktop">Deposit Amount</th>
                    <!--<th class="desktop">Bank Name</th>-->
                    <th class="desktop">Remark</th>
                    <th class="desktop">Comment</th>

                </tr>
            </thead>
            <tbody>
                <?php
                $walkin = array();
                while ($rowfilter = mysqli_fetch_array($resultfilter)) {

                    $walkin[0] += $rowfilter['amount'];
                    $walkin[1] += $rowfilter['cash'];
                    $walkin[2] += $rowfilter['cheque'];
                    $walkin[3] += $rowfilter['paytm'];
                    $walkin[4] += $rowfilter['BankTransfer'];
                    $walkin[5] += $rowfilter['depositAmount'];
                    $i++;
                    $serial++;
                ?>
                    <tr>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $serial; ?>
                            </div>
                        </td>

                        <!--                        <td>
                                                                            <div class="form-group form-md-line-input "><?php
                                                                                                                        //                                echo $rowfilter['title'] . ' ' . $rowfilter['firstName'] . ' ' . $rowfilter['middleName'] . ' ' . $rowfilter['surName'];
                                                                                                                        ?> 
                                                                            </div>
                                                                        </td>-->
                        <td>
                            <div class="form-group form-md-line-input "><?php
                                                                        echo $rowfilter['depositDate'];
                                                                        ?>
                            </div>
                        </td>
                        <!--                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['amount']; ?> 
                            </div>
                        </td>-->
                        <td>
                            <div class="form-group form-md-line-input "><?php
                                                                        if ($rowfilter['cash'] == '') {
                                                                            echo 0;
                                                                        } else {
                                                                            echo $rowfilter['cash'];
                                                                        }
                                                                        ?>
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php
                                                                        if ($rowfilter['cheque'] == '') {
                                                                            echo 0;
                                                                        } else {
                                                                            echo $rowfilter['cheque'];
                                                                        }
                                                                        ?>
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php
                                                                        if ($rowfilter['paytm'] == '') {
                                                                            echo 0;
                                                                        } else {
                                                                            echo $rowfilter['paytm'];
                                                                        }
                                                                        ?>
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php
                                                                        if ($rowfilter['BankTransfer'] == '') {
                                                                            echo 0;
                                                                        } else {
                                                                            echo $rowfilter['BankTransfer'];
                                                                        }
                                                                        ?>
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php
                                                                        if ($rowfilter['deposit'] == 'No') {
                                                                            echo 0;
                                                                        } else {
                                                                            echo $rowfilter['depositAmount'];
                                                                        }
                                                                        ?>
                            </div>
                        </td>
                        <!--                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['toBank']; ?> 
                            </div>
                        </td>-->
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['comments']; ?>
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-md-line-input "><?php echo $rowfilter['remarks']; ?>
                            </div>
                        </td>
                    <?php
                }
                    ?>


                    </tr>

            </tbody>
            <tr style="background-color:#f3f3f3">
                <td colspan="1"><b>Total</b></td>
                <td colspan="1">--</td>


                <!--<td colspan="1"><?php // echo $walkin[0];    
                                    ?></td>-->
                <td colspan="1"><?php echo $walkin[1]; ?></td>
                <td colspan="1"><?php echo $walkin[2]; ?></td>
                <td colspan="1"><?php echo $walkin[3]; ?></td>
                <td colspan="1"><?php echo $walkin[4]; ?></td>
                <td colspan="1"><?php echo $walkin[5]; ?></td>
                <td colspan="1">--</td>
                <td colspan="1">--</td>
                <!--                <td colspan="1">--</td>-->
            </tr>
        </table>
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