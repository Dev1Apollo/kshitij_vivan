<?php
error_reporting(0);
require_once('../common.php');
include('IsLogin.php');
$connect = new connect();
include ('User_Paging.php');

if ($_POST['action'] == 'ListUser') {
    $where = "where 1=1 ";
    $whereEmpId = " and studentadmission.branchId = '" . $_SESSION['branchid'] . "'";
    $con_date = "";
    if ($_REQUEST['month'] == NULL && $_REQUEST['month'] == '') {
        $con_date = $_REQUEST['Year'];
        $where.=" and DATE_FORMAT(STR_TO_DATE(sc.EnrollmentDate,'%d-%m-%Y'),'%Y') = DATE_FORMAT(STR_TO_DATE('" . $con_date . "','%Y'),'%Y')";

        echo $filterstr = "SELECT DATE_FORMAT(STR_TO_DATE(sc.EnrollmentDate,'%d-%m-%Y'),'%m-%Y') As MONTH,"
        . " (select sum(studentcourse.offeredfee) from studentcourse where studentcourse.stud_id = sc.stud_id " . $whereId . ") as Booked, 
(select sum(studentcourse.offeredfee) from studentcourse inner join studentadmission on studentadmission.stud_id = studentcourse.stud_id where studentcourse.stud_id = sc.stud_id " . $whereEmpId . " and studentadmission.iStudentStatus=1) as Active,
(select sum(studentcourse.offeredfee) from studentcourse inner join studentadmission on studentadmission.stud_id = studentcourse.stud_id where studentcourse.stud_id = sc.stud_id " . $whereEmpId . " and studentadmission.iStudentStatus=2) as Inactive,
(select sum(studentcourse.offeredfee) from studentcourse inner join studentadmission on studentadmission.stud_id = studentcourse.stud_id where studentcourse.stud_id = sc.stud_id " . $whereEmpId . " and studentadmission.iStudentStatus=3) as Dropout,
(select sum(studentcourse.offeredfee) from studentcourse inner join studentadmission on studentadmission.stud_id = studentcourse.stud_id where studentcourse.stud_id = sc.stud_id " . $whereEmpId . " and studentadmission.iStudentStatus=4) as StuyOver,
(select sum(studentcourse.offeredfee) from studentcourse inner join studentadmission on studentadmission.stud_id = studentcourse.stud_id where studentcourse.stud_id = sc.stud_id " . $whereEmpId . " and studentadmission.iStudentStatus=5) as Transfer
from studentcourse sc inner join studentadmission  on studentadmission.stud_id = sc.stud_id " . $where . $whereEmpId . " and sc.istatus=1 GROUP BY  DATE_FORMAT(STR_TO_DATE(sc.EnrollmentDate,'%d-%m-%Y'),'%m-%Y'),sc.branchId";
    } else {

        //$month = implode(',', $_REQUEST['month']);
//       print_r($month[1]);
        sizeof($_REQUEST['month']);
//        for($i=0;$i < sizeof($_REQUEST['month']); $i++){
        $month = $_REQUEST['month'];
        for($icounter=0;$icounter<sizeof($month);$icounter++){
            if (sizeof($_REQUEST['month']) == 1) {
                $con_date = $month[$icounter] . '-' . $_REQUEST['Year'];
                $whereMonth .=" DATE_FORMAT(STR_TO_DATE(sc.EnrollmentDate,'%d-%m-%Y'),'%m-%Y') = DATE_FORMAT(STR_TO_DATE('" . $con_date . "','%m-%Y'),'%m-%Y') ";
            } else {
                $con_date = $month[$icounter] . '-' . $_REQUEST['Year'];
                echo $whereMonth .=" DATE_FORMAT(STR_TO_DATE(sc.EnrollmentDate,'%d-%m-%Y'),'%m-%Y') = DATE_FORMAT(STR_TO_DATE('" . $con_date . "','%m-%Y'),'%m-%Y') OR ";
            }
        }
        exit;
        $whereMonth = "where 1=1 and ( ";
        foreach ($_REQUEST['month'] AS $Key => $month) {
            $con_date = $month . '-' . $_REQUEST['Year'];
            if (sizeof($_REQUEST['month']) == 1) {
                $whereMonth .=" DATE_FORMAT(STR_TO_DATE(sc.EnrollmentDate,'%d-%m-%Y'),'%m-%Y') = DATE_FORMAT(STR_TO_DATE('" . $con_date . "','%m-%Y'),'%m-%Y') ";
            } else {
                $whereMonth .=" DATE_FORMAT(STR_TO_DATE(sc.EnrollmentDate,'%d-%m-%Y'),'%m-%Y') = DATE_FORMAT(STR_TO_DATE('" . $con_date . "','%m-%Y'),'%m-%Y') OR ";
            }
        }
        
        if (sizeof($_REQUEST['month']) != 1) {
            
            $whereMonth = rtrim($whereMonth, " OR ");
        }
        $whereMonth .= ")";
        
//        SELECT
//sum(offeredfee) as Booked,DATE_FORMAT(STR_TO_DATE(EnrollmentDate,'%d-%m-%Y'),'%M-%Y') as MONTH,
//(select sum(offeredfee) FROM studentcourse INNER JOIN studentadmission on studentadmission.stud_id=studentcourse.stud_id
//WHERE studentadmission.iStudentStatus=1 and DATE_FORMAT(STR_TO_DATE(EnrollmentDate,'%d-%m-%Y'),'%m-%Y') = DATE_FORMAT(STR_TO_DATE('01-04-2017','%d-%m-%Y'),'%m-%Y') and studentadmission.branchId=1) as active,
//
//(select sum(offeredfee) FROM studentcourse INNER JOIN studentadmission on studentadmission.stud_id=studentcourse.stud_id
//WHERE studentadmission.iStudentStatus=2 and DATE_FORMAT(STR_TO_DATE(EnrollmentDate,'%d-%m-%Y'),'%m-%Y') = DATE_FORMAT(STR_TO_DATE('01-04-2017','%d-%m-%Y'),'%m-%Y') and studentadmission.branchId=1) as Inactive,
//
//(select sum(offeredfee) FROM studentcourse INNER JOIN studentadmission on studentadmission.stud_id=studentcourse.stud_id
//WHERE studentadmission.iStudentStatus=3 and DATE_FORMAT(STR_TO_DATE(EnrollmentDate,'%d-%m-%Y'),'%m-%Y') = DATE_FORMAT(STR_TO_DATE('01-04-2017','%d-%m-%Y'),'%m-%Y') and studentadmission.branchId=1) as Dropout,
//
//(select sum(offeredfee) FROM studentcourse INNER JOIN studentadmission on studentadmission.stud_id=studentcourse.stud_id
//WHERE studentadmission.iStudentStatus=4 and DATE_FORMAT(STR_TO_DATE(EnrollmentDate,'%d-%m-%Y'),'%m-%Y') = DATE_FORMAT(STR_TO_DATE('01-04-2017','%d-%m-%Y'),'%m-%Y') and studentadmission.branchId=1) as StudyOve,
//
//(select sum(offeredfee) FROM studentcourse INNER JOIN studentadmission on studentadmission.stud_id=studentcourse.stud_id
//WHERE studentadmission.iStudentStatus=5 and DATE_FORMAT(STR_TO_DATE(EnrollmentDate,'%d-%m-%Y'),'%m-%Y') = DATE_FORMAT(STR_TO_DATE('01-04-2017','%d-%m-%Y'),'%m-%Y') and studentadmission.branchId=1) as Transfer
//
//
//FROM `studentcourse` where branchId=1 and DATE_FORMAT(STR_TO_DATE(EnrollmentDate,'%d-%m-%Y'),'%m-%Y') = DATE_FORMAT(STR_TO_DATE('01-04-2017','%d-%m-%Y'),'%m-%Y') GROUP BY DATE_FORMAT(STR_TO_DATE(EnrollmentDate,'%d-%m-%Y'),'%m-%Y')
        
        echo $filterstr = "SELECT DATE_FORMAT(STR_TO_DATE(sc.EnrollmentDate,'%d-%m-%Y'),'%M-%Y') As MONTH,"
        . " (select sum(studentcourse.offeredfee) from studentcourse where studentcourse.stud_id = sc.stud_id " . $whereId . ") as Booked, 
(select sum(studentcourse.offeredfee) from studentcourse inner join studentadmission on studentadmission.stud_id = studentcourse.stud_id where studentcourse.stud_id = sc.stud_id " . $whereEmpId . " and studentadmission.iStudentStatus=1) as Active,
(select sum(studentcourse.offeredfee) from studentcourse inner join studentadmission on studentadmission.stud_id = studentcourse.stud_id where studentcourse.stud_id = sc.stud_id " . $whereEmpId . " and studentadmission.iStudentStatus=2) as Inactive,
(select sum(studentcourse.offeredfee) from studentcourse inner join studentadmission on studentadmission.stud_id = studentcourse.stud_id where studentcourse.stud_id = sc.stud_id " . $whereEmpId . " and studentadmission.iStudentStatus=3) as Dropout,
(select sum(studentcourse.offeredfee) from studentcourse inner join studentadmission on studentadmission.stud_id = studentcourse.stud_id where studentcourse.stud_id = sc.stud_id " . $whereEmpId . " and studentadmission.iStudentStatus=4) as StuyOver,
(select sum(studentcourse.offeredfee) from studentcourse inner join studentadmission on studentadmission.stud_id = studentcourse.stud_id where studentcourse.stud_id = sc.stud_id " . $whereEmpId . " and studentadmission.iStudentStatus=5) as Transfer
from studentcourse sc inner join studentadmission on studentadmission.stud_id = sc.stud_id " . $whereMonth . $whereEmpId . " GROUP BY  DATE_FORMAT(STR_TO_DATE(sc.EnrollmentDate,'%d-%m-%Y'),'%m-%Y'),sc.branchId" ;
    }



//    print_r($con_date);
//    if ($_REQUEST['month'] == NULL && $_REQUEST['month'] == '') {
////        foreach ($_REQUEST['month'] as $month) {
//        
////        }
//    } else {
////        foreach ($_REQUEST['month'] as $month) {
//        $where.=" and DATE_FORMAT(STR_TO_DATE(studentcourse.EnrollmentDate,'%d-%m-%Y'),'%m-%Y') = DATE_FORMAT(STR_TO_DATE('" . $con_date . "','%m-%Y'),'%m-%Y')";
////        }
//    }
//    if ($_REQUEST['Year'] != NULL && isset($_REQUEST['FormDate'])) {
//        $where .= " and STR_TO_DATE(studentcourse.EnrollmentDate,'%d-%m-%Y')>= STR_TO_DATE('$_REQUEST[FormDate]','%d-%m-%Y')";
//        $whereEmi = " and STR_TO_DATE(studentemidetail.emiDate,'%d-%m-%Y') < STR_TO_DATE('$_REQUEST[ToDate]','%d-%m-%Y')";
//        $whereFee .= " and STR_TO_DATE(studentfee.payDate,'%d-%m-%Y') < STR_TO_DATE('$date','%d-%m-%Y')";
//    }

    
//    $whereId = " and studentcourse.branchId = '" . $_SESSION['branchid'] . "'";
//    if ($_REQUEST['month'] != NULL && isset($_REQUEST['ToDate'])) {
//        $where .= " and STR_TO_DATE(studentcourse.EnrollmentDate,'%d-%m-%Y')<=STR_TO_DATE('$_REQUEST[ToDate]','%d-%m-%Y')";
//        $whereEmi = " and STR_TO_DATE(studentemidetail.emiDate,'%d-%m-%Y') < STR_TO_DATE('$_REQUEST[ToDate]','%d-%m-%Y')";
//        $whereFee .= " and STR_TO_DATE(studentfee.payDate,'%d-%m-%Y') < STR_TO_DATE('$date','%d-%m-%Y')";
//    }
//    if ($_REQUEST['studentPortal_Id'] != NULL && isset($_REQUEST['studentPortal_Id']))
//        $where .= " and studentadmission.studentPortal_Id='" . $_REQUEST['studentPortal_Id'] . "' ";
//     echo $filterstr = "SELECT DATE_FORMAT(STR_TO_DATE(studentcourse.EnrollmentDate,'%d-%m-%Y'),'%M-%Y') As MONTH,"
//    . " (select sum(studentcourse.offeredfee) from studentcourse " . $where . $whereId . ") as Booked, 
//(select sum(studentcourse.offeredfee) from studentcourse inner join studentadmission on studentadmission.stud_id = studentcourse.stud_id " . $where . $whereEmpId . " and studentadmission.iStudentStatus=1) as Active,
//(select sum(studentcourse.offeredfee) from studentcourse inner join studentadmission on studentadmission.stud_id = studentcourse.stud_id " . $where . $whereEmpId . " and studentadmission.iStudentStatus=2) as Inactive,
//(select sum(studentcourse.offeredfee) from studentcourse inner join studentadmission on studentadmission.stud_id = studentcourse.stud_id " . $where . $whereEmpId . " and studentadmission.iStudentStatus=3) as Dropout,
//(select sum(studentcourse.offeredfee) from studentcourse inner join studentadmission on studentadmission.stud_id = studentcourse.stud_id " . $where . $whereEmpId . " and studentadmission.iStudentStatus=4) as StuyOver,
//(select sum(studentcourse.offeredfee) from studentcourse inner join studentadmission on studentadmission.stud_id = studentcourse.stud_id " . $where . $whereEmpId . " and studentadmission.iStudentStatus=5) as Transfer
//from studentcourse inner join studentadmission on studentadmission.stud_id = studentcourse.stud_id " . $where . $whereEmpId . " GROUP BY  DATE_FORMAT(STR_TO_DATE(studentcourse.EnrollmentDate,'%d-%m-%Y'),'%m-%Y'),studentcourse.branchId";
////    echo $filterstr = "SELECT MONTH(STR_TO_DATE(studentcourse.EnrollmentDate,'%d-%m-%Y')) as Month,year(STR_TO_DATE(studentcourse.EnrollmentDate,'%d-%m-%Y')) as Year,sum(studentcourse.offeredfee) as Booked
////        , studentadmission.iStudentStatus,studentcourse.branchId from studentcourse inner join studentadmission on studentadmission.stud_id = studentcourse.stud_id " . $where . $whereEmpId . "
////        GROUP BY MONTH(STR_TO_DATE(studentcourse.EnrollmentDate,'%d-%m-%Y')),year(STR_TO_DATE(studentcourse.EnrollmentDate,'%d-%m-%Y')),studentadmission.iStudentStatus,studentcourse.branchId";

    $countstr = "SELECT count(*) as TotalRow from studentadmission,studentcourse where studentcourse.stud_id=studentadmission.stud_id " . $where . $whereEmpId . " ";

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
        $get_count = $resrowc['TotalRow'];
        ?>  
        <link href="<?php echo $web_url; ?>Employee/assets/global/plugins/datatables/datatables.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo $web_url; ?>Employee/assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" />
        <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script> 

        <form role="form"  method="POST" action="" name="frmparameter"  id="frmparameter" enctype="multipart/form-data">
            <input type="hidden" value="UpdateStudentStatus" name="action" id="action">
            <table class="table table-bordered table-hover center table-responsive dt-responsive" width="100%" id="tableC">
                <thead class="tbg">
                    <tr>
                        <th class="all">Sr.No</th>
                        <th class="desktop">Months</th>
                        <th class="desktop">Booked</th>
                        <th class="desktop">Active</th>
                        <th class="desktop">Inactive</th>
                        <th class="desktop">Study Over</th> 
                        <th class="desktop">Transfer</th>
                        <th class="desktop">Dropout</th>
                        <th class="desktop">Fees Uncollected</th>
                        <th class="desktop">% Dropout</th>
                        <th class="desktop">% Fees </th>
                    </tr>
                </thead>
                <tbody>
        <?php
        $filterfee = "select sum(amount) as recievedfee from studentfee where stud_id='" . $rowfilter['stud_id'] . "' and studentcourseId='" . $rowfilter['studentcourseId'] . "' ORDER by STR_TO_DATE(payDate,'%d-%m-%Y') DESC";
                                    $rowFee = mysqli_fetch_array(mysqli_query($dbconn,$filterfee));
                                    
        
        while ($rowfilter = mysqli_fetch_array($resultfilter)) {
            $i++;
            $serial++;
//                        echo "<pre>";
//                        print_r($rowfilter);
            $filterTotalEmi = mysqli_fetch_array(mysqli_query($dbconn, "select sum(emiAmount) as EmiAmount from studentemidetail where studentcourseId='" . $dataresult['studentcourseId'] . "' and stud_id='" . $dataresult['stud_id'] . "' " . $whereEmi . " group by stud_id,studentcourseId ORDER by STR_TO_DATE(emiDate,'%d-%m-%Y') asc"));
            $filterTotalReceiveAmount = mysqli_fetch_array(mysqli_query($dbconn, "select sum(amount) as ReceivedAmount from studentfee where studentcourseId='" . $dataresult['studentcourseId'] . "' and stud_id='" . $dataresult['stud_id'] . "' and feetype = '2' " . $whereFee . " group by stud_id,studentcourseId ORDER by STR_TO_DATE(payDate,'%d-%m-%Y') asc"));
            ?>
                        <tr>
                            <td>
                                <div class="form-group form-md-line-input "><?php echo $i; ?> 
                                </div>
                            </td>
                            <td>
                                <div class="form-group form-md-line-input "><?php echo $rowfilter['MONTH']; ?> 
                                </div>
                            </td>
                            <td>
                                <div class="form-group form-md-line-input "><?php
            echo $rowfilter['Booked'];
            ?> 
                                </div>
                            </td>
                            <td>
                                <div class="form-group form-md-line-input "><?php
            echo $rowfilter['Active'];
            ?> 
                                </div>
                            </td>                                                   
                            <td>
                                <div class="form-group form-md-line-input "><?php
            echo $rowfilter['Inactive'];
            ?> 
                                </div>
                            </td> 
                            <td>
                                <div class="form-group form-md-line-input "><?php
            echo $rowfilter['Dropout'];
            ?> 
                                </div>
                            </td>
                            <td>
                                <div class="form-group form-md-line-input "><?php
            echo $rowfilter['StuyOver'];
            ?> 
                                </div>
                            </td>
                            <td>
                                <div class="form-group form-md-line-input "><?php
            echo $rowfilter['Transfer'];
            ?>
                                </div>
                            </td>
                            <td>
                                <div class="form-group form-md-line-input "><?php
            echo $OverDueAmount = $filterTotalEmi['EmiAmount'] - $filterTotalReceiveAmount['ReceivedAmount'];
            ?>
                                </div>
                            </td>
                            <td>
                                <div class="form-group form-md-line-input "><?php
            echo ($rowfilter['Dropout'] / $rowfilter['Booked']) * 100;
//                                echo $rowfilter['Transfer'];
            ?>
                                </div>
                            </td>
                            <td>
                                <div class="form-group form-md-line-input "><?php
            echo $rowfilter['Transfer'];
            ?>
                                </div>
                            </td>

            <?php
        }
        ?>
                    </tr>
                </tbody>
            </table>
        </form>
        <script src="<?php echo $web_url; ?>Employee/assets/global/plugins/datatables/datatables.js" type="text/javascript"></script>
        <script src="<?php echo $web_url; ?>Employee/assets/global/plugins/datatables/table-datatables-responsive.js" type="text/javascript"></script>
        <script>
            $(document).ready(function () {
                $('#tableC').DataTable({
                });
            });

            function updateAttendanceDetail(stud_id) {
                var iStudentStatus = $('#iStudentStatus_' + stud_id).val();
                $('#loading').css("display", "block");
                $.ajax({
                    type: 'POST',
                    url: 'querydata.php',
                    data: {action: "UpdateStudentStatus", stud_id: stud_id, iStudentStatus: iStudentStatus},
                    success: function (response) {
                        console.log(response);
                        if (response != 0)
                        {
                            $('#loading').css("display", "none");
                            $("#Btnmybtn").attr('disabled', 'disabled');
                            alert('Student Status Updated Sucessfully.');
                        } else {
                            $('#loading').css("display", "none");
                            $("#Btnmybtn").attr('disabled', 'disabled');
                            alert('Invalid Request.');
                        }
                    }
                });
            }
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