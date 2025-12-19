<?php
require_once '../common.php';
$connect = new connect();
include('IsLogin.php');
if ((!isset($_POST['Submit']))) {
    $_REQUEST['FormDate'] = date("d-m-Y");
    $date = strtotime(date("d-m-Y", strtotime($_REQUEST['FormDate'])) . "-3 months");
    $_REQUEST['ToDate'] = date("d-m-Y", $date);
}


if($_SESSION['EmployeeType'] == 'Supervisor'){
    $resultLineChartTI = mysqli_query($dbconn,"SELECT Count(*) as count ,MONTH(STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y')) as Month ,YEAR(STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y')) as YEAR FROM lead where STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y') >= ADDDATE(CURRENT_DATE(), INTERVAL -13 MONTH) and STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y') <= CURRENT_DATE() GROUP by MONTH(STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y')) ,YEAR(STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y')) ORDER by YEAR(STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y')),MONTH(STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y')) ASC ");
    $resultLineChartCI = mysqli_query($dbconn,"SELECT Count(*) as count ,MONTH(STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y')) as Month ,YEAR(STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y')) as YEAR FROM lead where statusId='3'  and STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y') >= ADDDATE(CURRENT_DATE(), INTERVAL -13 MONTH) and STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y') <= CURRENT_DATE() GROUP by MONTH(STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y')) ,YEAR(STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y')) ORDER by YEAR(STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y')),MONTH(STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y')) ASC ");
} else {
    $resultLineChartTI = mysqli_query($dbconn,"SELECT Count(*) as count ,MONTH(STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y')) as Month ,YEAR(STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y')) as YEAR FROM lead where  employeeMasterId='" . $_SESSION['EmployeeId'] . "' and STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y') >= ADDDATE(CURRENT_DATE(), INTERVAL -13 MONTH) and STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y') <= CURRENT_DATE() GROUP by MONTH(STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y')) ,YEAR(STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y')) ORDER by YEAR(STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y')),MONTH(STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y')) ASC ");
    $resultLineChartCI = mysqli_query($dbconn,"SELECT Count(*) as count ,MONTH(STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y')) as Month ,YEAR(STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y')) as YEAR FROM lead where  employeeMasterId='" . $_SESSION['EmployeeId'] . "' and statusId='3'  and STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y') >= ADDDATE(CURRENT_DATE(), INTERVAL -13 MONTH) and STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y') <= CURRENT_DATE() GROUP by MONTH(STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y')) ,YEAR(STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y')) ORDER by YEAR(STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y')),MONTH(STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y')) ASC ");
}
if ((isset($_POST['Submit'])) && (isset($_REQUEST['FormDate'])) && (isset($_REQUEST['ToDate']))) {
    if($_SESSION['EmployeeType'] == 'Supervisor'){
        $queryPiestatusChart = "SELECT Count(*) as count,statusId FROM lead where  statusId in('1','2','3','4','5') and STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y') <= STR_TO_DATE('$_REQUEST[FormDate]','%d-%m-%Y') and STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y') >= STR_TO_DATE('$_REQUEST[ToDate]','%d-%m-%Y')  group by statusId union ALL
            SELECT Count(*) as count,'6' as statusId FROM lead where 
            STR_TO_DATE(lead.walkin_datetime,'%d-%m-%Y') <= STR_TO_DATE('$_REQUEST[FormDate]','%d-%m-%Y') 
            and STR_TO_DATE(lead.walkin_datetime,'%d-%m-%Y') >= STR_TO_DATE('$_REQUEST[ToDate]','%d-%m-%Y') 
            and lead.walkin_datetime != '' ";
    } else{
        $queryPiestatusChart = "SELECT Count(*) as count,statusId FROM lead where  statusId in('1','2','3','4','5') and employeeMasterId = '" . $_SESSION['EmployeeId'] . "' and STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y') <= STR_TO_DATE('$_REQUEST[FormDate]','%d-%m-%Y') and STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y') >= STR_TO_DATE('$_REQUEST[ToDate]','%d-%m-%Y')  group by statusId union ALL
            SELECT Count(*) as count,'6' as statusId FROM lead where employeeMasterId = '" . $_SESSION['EmployeeId'] . "'
            and STR_TO_DATE(lead.walkin_datetime,'%d-%m-%Y') <= STR_TO_DATE('$_REQUEST[FormDate]','%d-%m-%Y') 
            and STR_TO_DATE(lead.walkin_datetime,'%d-%m-%Y') >= STR_TO_DATE('$_REQUEST[ToDate]','%d-%m-%Y') 
            and lead.walkin_datetime != '' ";
    }
    $resultPiestatusChart = mysqli_query($dbconn,$queryPiestatusChart);
    if (mysqli_num_rows($resultPiestatusChart) > 0) {
        while ($rowPiestatusChart = mysqli_fetch_array($resultPiestatusChart)) {
            $get_status = mysqli_fetch_array(mysqli_query($dbconn,"select * from status where statusId='" . $rowPiestatusChart['statusId'] . "'"));
            $yearPiestatusChart['yearPieChart'][] = array("y" => $rowPiestatusChart['count'], "label" => $get_status['statusName']);
        }
        $dataPointsPieChartstatus = $yearPiestatusChart['yearPieChart'];
    } else {
        $yearPieChart['yearPieChart'][] = '';
        $dataPointsPieChartstatus = $yearPiestatusChart['yearPieChart'];
    }
    // print_r(json_encode($dataPointsPieChart));
} else {
    if($_SESSION['EmployeeType'] == 'Supervisor'){
        $queryPiestatusChart = "SELECT Count(*) as count,statusId FROM lead where  statusId in('1','2','3','4','5') and STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y') <= STR_TO_DATE('$_REQUEST[FormDate]','%d-%m-%Y') and STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y') >= STR_TO_DATE('$_REQUEST[ToDate]','%d-%m-%Y')  group by statusId union ALL
            SELECT Count(*) as count,'6' as statusId FROM lead where 
            STR_TO_DATE(lead.walkin_datetime,'%d-%m-%Y') <= STR_TO_DATE('$_REQUEST[FormDate]','%d-%m-%Y') 
            and STR_TO_DATE(lead.walkin_datetime,'%d-%m-%Y') >= STR_TO_DATE('$_REQUEST[ToDate]','%d-%m-%Y') 
            and lead.walkin_datetime != '' ";
    } else {
        $queryPiestatusChart = "SELECT Count(*) as count,statusId FROM lead where  statusId in('1','2','3','4','5') and employeeMasterId = '" . $_SESSION['EmployeeId'] . "' and STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y') <= STR_TO_DATE('$_REQUEST[FormDate]','%d-%m-%Y') and STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y') >= STR_TO_DATE('$_REQUEST[ToDate]','%d-%m-%Y')  group by statusId union ALL
            SELECT Count(*) as count,'6' as statusId FROM lead where employeeMasterId = '" . $_SESSION['EmployeeId'] . "'
            and STR_TO_DATE(lead.walkin_datetime,'%d-%m-%Y') <= STR_TO_DATE('$_REQUEST[FormDate]','%d-%m-%Y') 
            and STR_TO_DATE(lead.walkin_datetime,'%d-%m-%Y') >= STR_TO_DATE('$_REQUEST[ToDate]','%d-%m-%Y') 
            and lead.walkin_datetime != '' ";
    }
    $resultPiestatusChart = mysqli_query($dbconn,$queryPiestatusChart);
    if (mysqli_num_rows($resultPiestatusChart) > 0) {
        while ($rowPiestatusChart = mysqli_fetch_array($resultPiestatusChart)) {
            $get_status = mysqli_fetch_array(mysqli_query($dbconn,"select * from status where statusId='" . $rowPiestatusChart['statusId'] . "'"));
            $yearPiestatusChart['yearPieChart'][] = array("y" => $rowPiestatusChart['count'], "label" => $get_status['statusName']);
        }
        $dataPointsPieChartstatus = $yearPiestatusChart['yearPieChart'];
    } else {
        $yearPiestatusChart['yearPieChart'][] = '';
        $dataPointsPieChartstatus = $yearPiestatusChart['yearPieChart'];
    }
    // print_r(json_encode($dataPointsPieChart));
}
//echo "select sum(emiAmount - actualReceivedAmount) as count, MONTH(STR_TO_DATE(emiDAte,'%d-%m-%Y')) as Month , YEAR (STR_TO_DATE(emiDate,'%d-%m-%Y')) As YEAR from  studentemidetail ,studentadmission where studentemidetail.stud_id=studentadmission.stud_id and studentadmission.branchId='" . $_SESSION['branchid'] . "' and STR_TO_DATE(emiDate,'%d-%m-%Y') <= ADDDATE(CURRENT_DATE() , INTERVAL +12 MONTH) and STR_TO_DATE(emiDate,'%d-%m-%Y') >= STR_TO_DATE( concat('01-',Month(CURRENT_DATE()),'-',YEAR(CURRENT_DATE())),'%d-%m-%Y')  GROUP BY YEAR(STR_TO_DATE(emiDate,'%d-%m-%Y')),MONTH(STR_TO_DATE(emiDate,'%d-%m-%Y')) ORDER by YEAR(STR_TO_DATE(emiDate,'%d-%m-%Y')),MONTH(STR_TO_DATE(emiDate,'%d-%m-%Y')) ASC";
if($_SESSION['EmployeeType'] == 'Supervisor'){
    $LineChart = mysqli_query($dbconn,"select sum(emiAmount - actualReceivedAmount) as count, MONTH(STR_TO_DATE(emiDAte,'%d-%m-%Y')) as Month , YEAR (STR_TO_DATE(emiDate,'%d-%m-%Y')) As YEAR from  studentemidetail ,studentadmission where studentemidetail.stud_id=studentadmission.stud_id and studentemidetail.isDelete=0 and STR_TO_DATE(emiDate,'%d-%m-%Y') <= ADDDATE(CURRENT_DATE() , INTERVAL +12 MONTH) and STR_TO_DATE(emiDate,'%d-%m-%Y') >= STR_TO_DATE( concat('01-',Month(CURRENT_DATE()),'-',YEAR(CURRENT_DATE())),'%d-%m-%Y')  GROUP BY YEAR(STR_TO_DATE(emiDate,'%d-%m-%Y')),MONTH(STR_TO_DATE(emiDate,'%d-%m-%Y')) ORDER by YEAR(STR_TO_DATE(emiDate,'%d-%m-%Y')),MONTH(STR_TO_DATE(emiDate,'%d-%m-%Y')) ASC");
} else {
    $LineChart = mysqli_query($dbconn,"select sum(emiAmount - actualReceivedAmount) as count, MONTH(STR_TO_DATE(emiDAte,'%d-%m-%Y')) as Month , YEAR (STR_TO_DATE(emiDate,'%d-%m-%Y')) As YEAR from  studentemidetail ,studentadmission where studentemidetail.stud_id=studentadmission.stud_id and studentadmission.branchId='" . $_SESSION['branchid'] . "' and studentemidetail.isDelete=0 and STR_TO_DATE(emiDate,'%d-%m-%Y') <= ADDDATE(CURRENT_DATE() , INTERVAL +12 MONTH) and STR_TO_DATE(emiDate,'%d-%m-%Y') >= STR_TO_DATE( concat('01-',Month(CURRENT_DATE()),'-',YEAR(CURRENT_DATE())),'%d-%m-%Y')  GROUP BY YEAR(STR_TO_DATE(emiDate,'%d-%m-%Y')),MONTH(STR_TO_DATE(emiDate,'%d-%m-%Y')) ORDER by YEAR(STR_TO_DATE(emiDate,'%d-%m-%Y')),MONTH(STR_TO_DATE(emiDate,'%d-%m-%Y')) ASC");
}
if (mysqli_num_rows($LineChart) > 0) {
    while ($dataLineChart = mysqli_fetch_array($LineChart)) {
        $MonthCounter['LineChartpointer'][] = array("y" => $dataLineChart['count'], "label" => date('M-Y', strtotime($dataLineChart['YEAR'] . "-" . $dataLineChart['Month'])));
    }
} else {
    $MonthCounter['LineChartpointer'][] = 0;
}
$dataLineChartpointer = $MonthCounter['LineChartpointer'];
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title><?php echo $ProjectName; ?> |Dashboard  </title>
        <?php include_once './include.php'; ?>
        <link href="<?php echo $web_url; ?>Supervisor/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet" type="text/css" />
        <link href="calendar-master/zabuto_calendar.css" rel="stylesheet" type="text/css"/>
    </head>
    <body class="page-container-bg-solid page-boxed">
        <?php include_once './header.php'; ?>
        <div class="page-container">        
            <div class="page-content-wrapper">
                <div class="page-content">
                    <div class="container">
                        <ul class="page-breadcrumb breadcrumb">
                            <li>
                                <a href="<?php echo $web_url; ?>Supervisor/index.php">Home</a>
                                <i class="fa fa-circle"></i>
                            </li>
                            <li>
                                <span>Dashboard</span>
                            </li>
                        </ul>
                        <div class="page-content-inner">
                            <div class="row">
                                <div class="col-md-12 ">
                                    <form class="login-form" action="" method="post"  >
                                        <div class="row pull-right">
                                            <div class="form-group col-md-5">
                                                <input type="text" id="ToDate" name="ToDate" class="form-control date-picker" placeholder="Enter The To Date" required="" value="<?php
                                                if (isset($_REQUEST['ToDate'])) {
                                                    echo $_REQUEST['ToDate'];
                                                } else {
                                                    '';
                                                }
                                                ?>"/>
                                            </div>
                                            <div class="form-group col-md-5">
                                                <input type="text" id="FormDate" name="FormDate" class="form-control date-picker" placeholder="Enter The From Date" required="" value="<?php
                                                if (isset($_REQUEST['FormDate'])) {
                                                    echo $_REQUEST['FormDate'];
                                                } else {
                                                    '';
                                                }
                                                ?>"/>
                                            </div>
                                            <input type="submit"  name="Submit" id="Submit" class="btn btn-sm blue" value="Submit">             
                                            <!--   <a href="#" class="btn btn-sm blue " onclick="PageLoadData(1);">Submit</a>-->
                                        </div>
                                    </form>
                                </div>

                                <div class="col-md-6">
                                    <div class="col-md-6">
                                        <div class="dashboard-stat blue-steel">
                                            <div class="visual">
                                                <i class="fa fa-line-chart fa-icon-medium"></i>
                                            </div>
                                            <div class="details">
                                                <div class="number">
                                                    <?php
                                                    if($_SESSION['EmployeeType'] == 'Supervisor'){
                                                        $rowTodayFollowup = mysqli_fetch_array(mysqli_query($dbconn,"SELECT count(*) as count FROM lead where  MONTH(STR_TO_DATE(nextFollowupDate,'%d-%m-%Y'))=MONTH(CURRENT_DATE()) and YEAR(STR_TO_DATE(nextFollowupDate,'%d-%m-%Y'))=YEAR(CURRENT_DATE()) and STR_TO_DATE(nextFollowupDate,'%d-%m-%Y')= CURRENT_DATE()"));
                                                    } else {
                                                        $rowTodayFollowup = mysqli_fetch_array(mysqli_query($dbconn,"SELECT count(*) as count FROM lead where  MONTH(STR_TO_DATE(nextFollowupDate,'%d-%m-%Y'))=MONTH(CURRENT_DATE()) and YEAR(STR_TO_DATE(nextFollowupDate,'%d-%m-%Y'))=YEAR(CURRENT_DATE()) and STR_TO_DATE(nextFollowupDate,'%d-%m-%Y')= CURRENT_DATE() and employeeMasterId='" . $_SESSION['EmployeeId'] . "'"));
                                                    }
                                                    echo $rowTodayFollowup['count'];
                                                    ?>
                                                </div>
                                                <div class="desc">Today's Followup </div>
                                            </div>
                                            <a class="more" href="ViewMoreDetail.php?Token=TF">View more
                                                <i class="m-icon-swapright m-icon-white"></i>
                                            </a>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="dashboard-stat red-mint">
                                            <div class="visual">
                                                <i class="fa fa-line-chart fa-icon-medium"></i>
                                            </div>
                                            <div class="details">
                                                <div class="number">
                                                    <?php
                                                    if($_SESSION['EmployeeType'] == 'Supervisor'){
                                                        $rowMonthlyConverted = mysqli_fetch_array(mysqli_query($dbconn,"SELECT count(*) as count FROM lead where  DATE_FORMAT(STR_TO_DATE(nextFollowupDate, '%d-%m-%Y'), '%Y-%m-%d') < DATE_FORMAT(STR_TO_DATE('" . date('d-m-Y') . "', '%d-%m-%Y'), '%Y-%m-%d') and statusId in ('1','6') "));
                                                    } else {
                                                        $rowMonthlyConverted = mysqli_fetch_array(mysqli_query($dbconn,"SELECT count(*) as count FROM lead where  DATE_FORMAT(STR_TO_DATE(nextFollowupDate, '%d-%m-%Y'), '%Y-%m-%d') < DATE_FORMAT(STR_TO_DATE('" . date('d-m-Y') . "', '%d-%m-%Y'), '%Y-%m-%d') and employeeMasterId='" . $_SESSION['EmployeeId'] . "' and statusId in ('1','6') "));
                                                    }
                                                    echo $rowMonthlyConverted['count'];
                                                    ?> </div>
                                                <div class="desc">Over Due Call </div>
                                            </div>
                                            <a class="more" href="ViewMoreDetail.php?Token=odc">Over Due Call
                                                <i class="m-icon-swapright m-icon-white"></i>
                                            </a>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="dashboard-stat green">
                                            <div class="visual">
                                                <i class="fa fa-line-chart fa-icon-medium"></i>
                                            </div>
                                            <div class="details">
                                                <div class="number">
                                                    <?php
                                                    if($_SESSION['EmployeeType'] == 'Supervisor'){
                                                        $rowTodayNewInquiry = mysqli_fetch_array(mysqli_query($dbconn,"SELECT count(*) as count FROM lead where  MONTH(STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y'))=MONTH(CURRENT_DATE()) and YEAR(STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y'))=YEAR(CURRENT_DATE()) and STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y')= CURRENT_DATE()"));
                                                    } else {
                                                        $rowTodayNewInquiry = mysqli_fetch_array(mysqli_query($dbconn,"SELECT count(*) as count FROM lead where  MONTH(STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y'))=MONTH(CURRENT_DATE()) and YEAR(STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y'))=YEAR(CURRENT_DATE()) and STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y')= CURRENT_DATE() and employeeMasterId='" . $_SESSION['EmployeeId'] . "'"));
                                                    }
                                                    echo $rowTodayNewInquiry['count'];
                                                    ?>
                                                </div>
                                                <div class="desc">Today's New Inquiry </div>
                                            </div>
                                            <a class="more" href="ViewMoreDetail.php?Token=TNI">View more
                                                <i class="m-icon-swapright m-icon-white"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="dashboard-stat green-turquoise">
                                            <div class="visual">
                                                <i class="fa fa-line-chart fa-icon-medium"></i>
                                            </div>
                                            <div class="details">
                                                <div class="number">
                                                    <?php
                                                    if($_SESSION['EmployeeType'] == 'Supervisor'){
                                                        $rowInquiryMonthly = mysqli_fetch_array(mysqli_query($dbconn,"SELECT count(*) as count FROM lead where  MONTH(STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y'))=MONTH(CURRENT_DATE()) and YEAR(STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y'))=YEAR(CURRENT_DATE())"));
                                                    } else {
                                                        $rowInquiryMonthly = mysqli_fetch_array(mysqli_query($dbconn,"SELECT count(*) as count FROM lead where  MONTH(STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y'))=MONTH(CURRENT_DATE()) and YEAR(STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y'))=YEAR(CURRENT_DATE())  and employeeMasterId='" . $_SESSION['EmployeeId'] . "'"));
                                                    }
                                                    echo $rowInquiryMonthly['count'];
                                                    ?></div>
                                                <div class="desc">Monthly Inquiry </div>
                                            </div>
                                            <a class="more" href="ViewMoreDetail.php?Token=MI">View more
                                                <i class="m-icon-swapright m-icon-white"></i>
                                            </a>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="dashboard-stat grey-gallery">
                                            <div class="visual">
                                                <i class="fa fa-line-chart fa-icon-medium"></i>
                                            </div>
                                            <div class="details">
                                                <div class="number">
                                                    <?php
                                                    if($_SESSION['EmployeeType'] == 'Supervisor'){
                                                        $rowTodayConverted = mysqli_fetch_array(mysqli_query($dbconn,"SELECT count(*) as count FROM lead where  MONTH(STR_TO_DATE(nextFollowupModifyDate,'%d-%m-%Y'))=MONTH(CURRENT_DATE()) and YEAR(STR_TO_DATE(nextFollowupModifyDate,'%d-%m-%Y'))=YEAR(CURRENT_DATE()) and STR_TO_DATE(nextFollowupModifyDate,'%d-%m-%Y')= CURRENT_DATE() and statusId='2'"));
                                                    } else {
                                                        $rowTodayConverted = mysqli_fetch_array(mysqli_query($dbconn,"SELECT count(*) as count FROM lead where  MONTH(STR_TO_DATE(nextFollowupModifyDate,'%d-%m-%Y'))=MONTH(CURRENT_DATE()) and YEAR(STR_TO_DATE(nextFollowupModifyDate,'%d-%m-%Y'))=YEAR(CURRENT_DATE()) and STR_TO_DATE(nextFollowupModifyDate,'%d-%m-%Y')= CURRENT_DATE() and statusId='2' and employeeMasterId='" . $_SESSION['EmployeeId'] . "'"));
                                                    }
                                                    echo $rowTodayConverted['count'];
                                                    ?> 
                                                </div>
                                                <div class="desc">Today's Lost </div>
                                            </div>
                                            <a class="more" href="ViewMoreDetail.php?Token=TL">View more
                                                <i class="m-icon-swapright m-icon-white"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="dashboard-stat grey-salsa">
                                            <div class="visual">
                                                <i class="fa fa-line-chart fa-icon-medium"></i>
                                            </div>
                                            <div class="details">
                                                <div class="number">
                                                    <?php
                                                    if($_SESSION['EmployeeType'] == 'Supervisor'){
                                                        $rowMonthlyConverted = mysqli_fetch_array(mysqli_query($dbconn,"SELECT count(*) as count FROM lead where  MONTH(STR_TO_DATE(nextFollowupModifyDate,'%d-%m-%Y'))=MONTH(CURRENT_DATE()) and YEAR(STR_TO_DATE(nextFollowupModifyDate,'%d-%m-%Y'))=YEAR(CURRENT_DATE())  and statusId='2'"));
                                                    } else {
                                                        $rowMonthlyConverted = mysqli_fetch_array(mysqli_query($dbconn,"SELECT count(*) as count FROM lead where  MONTH(STR_TO_DATE(nextFollowupModifyDate,'%d-%m-%Y'))=MONTH(CURRENT_DATE()) and YEAR(STR_TO_DATE(nextFollowupModifyDate,'%d-%m-%Y'))=YEAR(CURRENT_DATE())  and statusId='2' and employeeMasterId='" . $_SESSION['EmployeeId'] . "'"));
                                                    }
                                                    echo $rowMonthlyConverted['count'];
                                                    ?> </div>
                                                <div class="desc">Monthly Lost </div>
                                            </div>
                                            <a class="more" href="ViewMoreDetail.php?Token=ML">View more
                                                <i class="m-icon-swapright m-icon-white"></i>
                                            </a>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="dashboard-stat red">
                                            <div class="visual">
                                                <i class="fa fa-line-chart fa-icon-medium"></i>
                                            </div>
                                            <div class="details">
                                                <div class="number">
                                                    <?php
                                                    if($_SESSION['EmployeeType'] == 'Supervisor'){
                                                        $rowTodayConverted = mysqli_fetch_array(mysqli_query($dbconn, "SELECT count(*) as count FROM lead where  MONTH(STR_TO_DATE(nextFollowupModifyDate,'%d-%m-%Y'))=MONTH(CURRENT_DATE()) and YEAR(STR_TO_DATE(nextFollowupModifyDate,'%d-%m-%Y'))=YEAR(CURRENT_DATE()) and DAY(STR_TO_DATE(nextFollowupModifyDate,'%d-%m-%Y %H:%i:%s')) = DAY(CURRENT_DATE()) and statusId='3'"));
                                                    } else {
                                                        $rowTodayConverted = mysqli_fetch_array(mysqli_query($dbconn, "SELECT count(*) as count FROM lead where  MONTH(STR_TO_DATE(nextFollowupModifyDate,'%d-%m-%Y'))=MONTH(CURRENT_DATE()) and YEAR(STR_TO_DATE(nextFollowupModifyDate,'%d-%m-%Y'))=YEAR(CURRENT_DATE()) and DAY(STR_TO_DATE(nextFollowupModifyDate,'%d-%m-%Y %H:%i:%s')) = DAY(CURRENT_DATE()) and statusId='3' and employeeMasterId='" . $_SESSION['EmployeeId'] . "'"));
                                                    }

                                                    echo $rowTodayConverted['count'];
                                                    ?> 
                                                </div>
                                                <div class="desc">Today's Booked </div>
                                            </div>
                                            <a class="more" href="ViewMoreDetail.php?Token=TB">View more
                                                <i class="m-icon-swapright m-icon-white"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="dashboard-stat red-soft">
                                            <div class="visual">
                                                <i class="fa fa-line-chart fa-icon-medium"></i>
                                            </div>
                                            <div class="details">
                                                <div class="number">
                                                    <?php
                                                    if($_SESSION['EmployeeType'] == 'Supervisor'){
                                                        $rowInquiryConverted = mysqli_fetch_array(mysqli_query($dbconn,"SELECT count(*) as count FROM lead where  MONTH(STR_TO_DATE(nextFollowupModifyDate,'%d-%m-%Y'))=MONTH(CURRENT_DATE()) and YEAR(STR_TO_DATE(nextFollowupModifyDate,'%d-%m-%Y'))=YEAR(CURRENT_DATE()) and statusId='3'"));
                                                    } else {
                                                        $rowInquiryConverted = mysqli_fetch_array(mysqli_query($dbconn,"SELECT count(*) as count FROM lead where  MONTH(STR_TO_DATE(nextFollowupModifyDate,'%d-%m-%Y'))=MONTH(CURRENT_DATE()) and YEAR(STR_TO_DATE(nextFollowupModifyDate,'%d-%m-%Y'))=YEAR(CURRENT_DATE())  and employeeMasterId='" . $_SESSION['EmployeeId'] . "' and statusId='3'"));
                                                    }
                                                    echo $rowInquiryConverted['count'];
                                                    ?> </div>
                                                <div class="desc">Monthly Booked </div>
                                            </div>
                                            <a class="more" href="ViewMoreDetail.php?Token=MB">View more
                                                <i class="m-icon-swapright m-icon-white"></i>
                                            </a>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="dashboard-stat grey-salsa">
                                            <div class="visual">
                                                <i class="fa fa-line-chart fa-icon-medium"></i>
                                            </div>
                                            <div class="details">
                                                <div class="number">
                                                    <?php
//                                                    $rowMonthlyConverted = mysqli_fetch_array(mysqli_query($dbconn,"SELECT SUM(amount) as count FROM studentfee INNER JOIN studentadmission on studentfee.stud_id = studentadmission.stud_id where MONTH(STR_TO_DATE(studentfee.payDate,'%d-%m-%Y'))=MONTH(CURRENT_DATE()) and YEAR(STR_TO_DATE(studentfee.payDate,'%d-%m-%Y'))=YEAR(CURRENT_DATE()) and studentadmission.employeeMasterId='" . $_SESSION['EmployeeId'] . "'"));
//                                                    if (isset($rowMonthlyConverted['count'])) {
//                                                        echo $rowMonthlyConverted['count'];
//                                                    } else {
                                                    echo 0;
//                                                    }
                                                    ?> </div>
                                                <div class="desc">Total's Collection</div>
                                            </div>
                                            <a class="more" href="ViewMoreDetailstudent.php?Token=TMC">View more
                                                <i class="m-icon-swapright m-icon-white"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="dashboard-stat grey-salsa">
                                            <div class="visual">
                                                <i class="fa fa-line-chart fa-icon-medium"></i>
                                            </div>
                                            <div class="details">
                                                <div class="number">
                                                    <?php
//                                                    $rowMonthlyConverted = mysqli_fetch_array(mysqli_query($dbconn,"SELECT SUM(emiAmount - actualReceivedAmount) as count FROM studentemidetail  INNER JOIN studentadmission ON studentadmission.stud_id = studentemidetail.stud_id where  MONTH(STR_TO_DATE(studentemidetail.emiDate,'%d-%m-%Y'))=MONTH(CURRENT_DATE()) and YEAR(STR_TO_DATE(studentemidetail.emiDate,'%d-%m-%Y'))=YEAR(CURRENT_DATE()) and studentadmission.employeeMasterId='" . $_SESSION['EmployeeId'] . "' "));
//                                                    if (isset($rowMonthlyConverted['count'])) {
//                                                        echo $rowMonthlyConverted['count'];
//                                                    } else {
                                                    echo 0;
//                                                    }
                                                    ?> </div>
                                                <div class="desc">Total Monthly Collection</div>
                                            </div>
                                            <a class="more" href="ViewMoreDetailstudent.php?Token=TMP">View more
                                                <i class="m-icon-swapright m-icon-white"></i>
                                            </a>
                                        </div>
                                    </div>

<!--                                    <div class="col-lg-12">
                                        <div class="portlet light ">
                                            <div class="portlet-title">
                                                <div class="caption">
                                                    <i class="icon-share font-red"></i>
                                                    <span class="caption-subject font-red bold uppercase">Action for Day</span>
                                                </div>
                                            </div>
                                            <div class="portlet-body">
                                                <ul class="list-group">
                                                    <?php
//                                                    $rowtodaycallback = mysqli_fetch_array(mysqli_query($dbconn,"SELECT count(*) as count FROM lead where  STR_TO_DATE(nextFollowupDate,'%d-%m-%Y')=CURRENT_DATE() and statusId='1' and employeeMasterId=".$_SESSION['EmployeeId']." "));
//                                                    $rowwalkin = mysqli_fetch_array(mysqli_query($dbconn,"SELECT count(*) as count FROM lead where  STR_TO_DATE(nextFollowupDate,'%d-%m-%Y')=CURRENT_DATE() and statusId='6' and employeeMasterId=".$_SESSION['EmployeeId'].""));
                                                    
                                                    ?>
                                                    <li class="list-group-item bg-red bg-font-red"> <img src="assets/call.png" /> <strong>To Call Back :</strong> <span class="pull-right"> <strong><?php // echo $rowtodaycallback['count']; ?></strong> </span></li>
                                                    <li class="list-group-item bg-red bg-font-red"> <img src="assets/walk.png" /> <strong>To Walk-in :</strong> <span class="pull-right"> <strong> <?php // echo $rowwalkin['count']; ?></strong></span></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>-->
                                </div>
                                <div class="col-md-6">
                                    <div class="col-md-12">
                                        <!-- Begin: life time stats -->
                                        <div class="portlet light ">
                                            <div class="portlet-title">
                                                <div class="caption">
                                                    <i class="icon-share font-blue"></i>
                                                    <span class="caption-subject font-blue bold uppercase">Target For Month OF <?php echo date('F, Y'); ?></span>
                                                </div>
                                            </div>
                                            <div class="portlet-body">
                                                <table class="table table-bordered table-hover center table-responsive" width="100%" id="tableC">
                                                    <tr>
                                                        <th>Detail</th>
                                                        <th>Target</th>
                                                        <th>Achieved</th>
                                                        <th>Percentage</th>
                                                    </tr>
                                                    <?php
                                                    if($_SESSION['EmployeeType'] == 'Supervisor'){
                                                        $filterTarget = mysqli_fetch_array(mysqli_query($dbconn,"Select sum(targetInquiry) as targetInquiry,sum(targetEnroll) as targetEnroll,sum(targetBooking) as targetBooking,sum(targetCollection) as targetCollection,sum(targetFPS) as targetFPS from target where month = MONTH(CURRENT_DATE()) and year = YEAR(CURRENT_DATE()) and isDelete = 0 and iStatus=1"));
                                                    } else {
                                                        $filterTarget = mysqli_fetch_array(mysqli_query($dbconn,"Select * from target where month = MONTH(CURRENT_DATE()) and year = YEAR(CURRENT_DATE()) and isDelete = 0 and iStatus=1 and iBranchId='" . $_SESSION['branchid'] . "'"));
                                                    }
//                                                    echo "SELECT sum(booking_amount) as TotalRow FROM lead where  MONTH(STR_TO_DATE(nextFollowupModifyDate,'%d-%m-%Y'))=MONTH(CURRENT_DATE()) and YEAR(STR_TO_DATE(nextFollowupModifyDate,'%d-%m-%Y'))=YEAR(CURRENT_DATE()) and isNewInquiry='0' and statusId='3' and employeeMasterId='" . $_SESSION['EmployeeId'] . "'";
                                                    ?>
                                                    <tr>
                                                        <td>Walk in</td>
                                                        <td> <?php 
                                                        if(isset($filterTarget['targetInquiry'])){
                                                            echo $filterTarget['targetInquiry'];
                                                        }else{
                                                            echo 0;
                                                        }?> </td>
                                                        <td> <?php
                                                        if($_SESSION['EmployeeType'] == 'Supervisor'){
                                                            $inquiry = mysqli_fetch_array(mysqli_query($dbconn,"SELECT count(*) as TotalRow FROM lead where  MONTH(STR_TO_DATE(walkin_datetime,'%d-%m-%Y'))=MONTH(CURRENT_DATE()) and YEAR(STR_TO_DATE(walkin_datetime,'%d-%m-%Y'))=YEAR(CURRENT_DATE()) and isNewInquiry='0'"));
                                                        } else {
                                                            $inquiry = mysqli_fetch_array(mysqli_query($dbconn,"SELECT count(*) as TotalRow FROM lead where  MONTH(STR_TO_DATE(walkin_datetime,'%d-%m-%Y'))=MONTH(CURRENT_DATE()) and YEAR(STR_TO_DATE(walkin_datetime,'%d-%m-%Y'))=YEAR(CURRENT_DATE()) and isNewInquiry='0' and employeeMasterId='" . $_SESSION['EmployeeId'] . "'"));
                                                        }
                                                            if ($inquiry['TotalRow'] != 0) {
                                                                echo $inquiry['TotalRow'];
                                                            } else {
                                                                echo "0";
                                                            }
                                                            ?></td>
                                                        <td> <?php
                                                        if($filterTarget['targetInquiry'] != '' || $filterTarget['targetInquiry'] != null){
                                                            if ($inquiry['TotalRow'] == '' || $inquiry['TotalRow'] == 0) {
                                                                echo "-";
                                                            } else {
                                                                $walkinPer = ($inquiry['TotalRow'] * 100) / $filterTarget['targetInquiry'];
                                                                echo number_format($walkinPer, 2) . "%";
                                                            }
                                                        }else{
                                                            echo "0 %";
                                                        }
                                                            ?> </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Enrollment</td>
                                                        <td> <?php 
                                                            if(isset($filterTarget['targetEnroll'])){
                                                            echo $filterTarget['targetEnroll'];
                                                            }else{
                                                                echo 0;
                                                            }?></td>
                                                        <td><?php
                                                        if($_SESSION['EmployeeType'] == 'Supervisor'){
                                                            $filterEnroll = mysqli_fetch_array(mysqli_query($dbconn,"SELECT count(*) as TotalRow FROM lead where  MONTH(STR_TO_DATE(nextFollowupModifyDate,'%d-%m-%Y'))=MONTH(CURRENT_DATE()) and YEAR(STR_TO_DATE(nextFollowupModifyDate,'%d-%m-%Y'))=YEAR(CURRENT_DATE()) and isNewInquiry='0' and statusId='3'"));
                                                        } else {
                                                            $filterEnroll = mysqli_fetch_array(mysqli_query($dbconn,"SELECT count(*) as TotalRow FROM lead where  MONTH(STR_TO_DATE(nextFollowupModifyDate,'%d-%m-%Y'))=MONTH(CURRENT_DATE()) and YEAR(STR_TO_DATE(nextFollowupModifyDate,'%d-%m-%Y'))=YEAR(CURRENT_DATE()) and isNewInquiry='0' and statusId='3' and employeeMasterId='" . $_SESSION['EmployeeId'] . "'"));
                                                        }
                                                            if ($filterEnroll['TotalRow'] != 0) {
                                                                echo $filterEnroll['TotalRow'];
                                                            } else {
                                                                echo "0";
                                                            }
                                                            ?></td>
                                                        <td> <?php
                                                        if($filterTarget['targetEnroll'] != '' || $filterTarget['targetEnroll'] != null){
                                                            if ($filterEnroll['TotalRow'] == '' || $filterEnroll['TotalRow'] == 0) {
                                                                echo "-";
                                                            } else {
                                                                $enrollPer = ($filterEnroll['TotalRow'] * 100 ) / $filterTarget['targetEnroll'];
                                                                echo number_format($enrollPer, 2) . "%";
                                                            }
                                                        }else{
                                                            echo "0 %";
                                                        }
                                                            ?> </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Booking</td>
                                                        <td><?php 
                                                            if(isset($filterTarget['targetBooking'])){
                                                            echo $filterTarget['targetBooking'];
                                                            }else{
                                                                echo 0;
                                                            }?></td>
                                                        <td><?php
                                                        if($_SESSION['EmployeeType'] == 'Supervisor'){
                                                            $filterBooking = mysqli_fetch_array(mysqli_query($dbconn,"SELECT sum(booking_amount) as TotalRow FROM lead where  MONTH(STR_TO_DATE(nextFollowupModifyDate,'%d-%m-%Y'))=MONTH(CURRENT_DATE()) and YEAR(STR_TO_DATE(nextFollowupModifyDate,'%d-%m-%Y'))=YEAR(CURRENT_DATE()) and isNewInquiry='0' and statusId='3'"));
                                                        } else {
                                                            $filterBooking = mysqli_fetch_array(mysqli_query($dbconn,"SELECT sum(booking_amount) as TotalRow FROM lead where  MONTH(STR_TO_DATE(nextFollowupModifyDate,'%d-%m-%Y'))=MONTH(CURRENT_DATE()) and YEAR(STR_TO_DATE(nextFollowupModifyDate,'%d-%m-%Y'))=YEAR(CURRENT_DATE()) and isNewInquiry='0' and statusId='3' and employeeMasterId='" . $_SESSION['EmployeeId'] . "'"));
                                                        }
                                                            if ($filterBooking['TotalRow'] != 0) {
                                                                echo $filterBooking['TotalRow'];
                                                            } else {
                                                                echo "0";
                                                            }
                                                            ?></td>
                                                        <td> <?php
                                                        if($filterTarget['targetBooking'] != '' || $filterTarget['targetBooking'] != null){
                                                            if ($filterBooking['TotalRow'] == '' || $filterBooking['TotalRow'] == 0) {
                                                                echo "-";
                                                            } else {
                                                                $bookPer = ($filterBooking['TotalRow'] * 100) / $filterTarget['targetBooking'];
                                                                echo number_format($bookPer, 2) . "%";
                                                            }
                                                        }else{
                                                            echo "0 %";
                                                        }
                                                            ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Collection</td>
                                                        <td> <?php 
                                                            if (isset($filterTarget['targetCollection'])){
                                                                echo $filterTarget['targetCollection'];
                                                            }else{
                                                                echo 0;
                                                            }
                                                        ?></td>
                                                        <td> <?php
                                                        if($_SESSION['EmployeeType'] == 'Supervisor'){
                                                            $filterCollection = mysqli_fetch_array(mysqli_query($dbconn,"select sum(amount) as collection from studentfee INNER JOIN studentadmission ON studentadmission.stud_id = studentfee.stud_id where MONTH(STR_TO_DATE(studentfee.payDate,'%d-%m-%Y'))=MONTH(CURRENT_DATE()) and YEAR(STR_TO_DATE(studentfee.payDate,'%d-%m-%Y'))=YEAR(CURRENT_DATE()) and studentfee.feetype not in (5)"));
                                                        } else {
                                                            $filterCollection = mysqli_fetch_array(mysqli_query($dbconn,"select sum(amount) as collection from studentfee INNER JOIN studentadmission ON studentadmission.stud_id = studentfee.stud_id where MONTH(STR_TO_DATE(studentfee.payDate,'%d-%m-%Y'))=MONTH(CURRENT_DATE()) and YEAR(STR_TO_DATE(studentfee.payDate,'%d-%m-%Y'))=YEAR(CURRENT_DATE()) and studentadmission.branchId='" . $_SESSION['branchid'] . "' and studentfee.feetype not in (5)"));
                                                        }
                                                            if ($filterCollection['collection'] != 0) {
                                                                echo $filterCollection['collection'];
                                                            } else {
                                                                echo 0;
                                                            }
                                                            ?></td>
                                                        <td> <?php
                                                        if($filterTarget['targetCollection'] != '' || $filterTarget['targetCollection'] != null){
                                                            if ($filterCollection['collection'] == '' || $filterCollection['collection'] == 0) {
                                                                echo "-";
                                                            } else {
                                                                $collectionPer = ($filterCollection['collection'] * 100) / $filterTarget['targetCollection'];
                                                                echo number_format($collectionPer, 2) . "%";
                                                            }
                                                        }else{
                                                            echo "0 %";
                                                        }
                                                            ?></td>
                                                    </tr>                                                    
                                                    <tr>
                                                        <td>FPS</td>
                                                        <td> <?php if(isset($filterTarget['targetFPS'])){
                                                            echo $filterTarget['targetFPS'];
                                                        }else{
                                                            echo 0;
                                                        }
                                                            ?></td>
                                                        <td> <?php 
                                                        if($_SESSION['EmployeeType'] == 'Supervisor'){
                                                            $filterFPS = mysqli_fetch_array(mysqli_query($dbconn,"SELECT COUNT( DISTINCT studentfee.stud_id) as count FROM studentfee,studentadmission where studentadmission.stud_id=studentfee.stud_id and MONTH(STR_TO_DATE(payDate,'%d-%m-%Y'))=MONTH(CURRENT_DATE()) and YEAR(STR_TO_DATE(payDate,'%d-%m-%Y'))=YEAR(CURRENT_DATE())"));
                                                        } else {
                                                            $filterFPS = mysqli_fetch_array(mysqli_query($dbconn,"SELECT COUNT( DISTINCT studentfee.stud_id) as count FROM studentfee,studentadmission where studentadmission.stud_id=studentfee.stud_id and MONTH(STR_TO_DATE(payDate,'%d-%m-%Y'))=MONTH(CURRENT_DATE()) and YEAR(STR_TO_DATE(payDate,'%d-%m-%Y'))=YEAR(CURRENT_DATE()) and studentadmission.branchId='" . $_SESSION['branchid'] . "'"));
                                                        }
                                                                echo $filterFPS['count']; // $filterTarget['achieveFPS'] ?></td>
                                                        <td> <?php
                                                        if($filterTarget['targetFPS'] != '' || $filterTarget['targetFPS'] != null){
                                                            if ($filterTarget['targetFPS'] == 0 && $filterFPS['count'] == 0) {
                                                                echo "-";
                                                            } else {
                                                                $fpsPer = ( $filterFPS['count'] * 100 ) / $filterTarget['targetFPS'];
                                                                echo $fpsPer . '%';
                                                            }
                                                        }else{
                                                            echo "0 %";
                                                        }
                                                            ?> </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>


                                        <!-- Begin: life time stats -->
                                        <!-- BEGIN PORTLET-->
                                        <div class="portlet light ">
                                            <div class="portlet-title">
                                                <div class="caption">
                                                    <i class="icon-share font-green"></i>
                                                    <span class="caption-subject font-green bold uppercase">Month Planner</span>
                                                </div>
                                            </div>
                                            <div class="portlet-body">
                                                <div id="calendar" ></div>
                                            </div>
                                        </div>
                                        <!-- End: life time stats -->
                                        <!-- End: life time stats -->
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="col-md-12">

                                    </div>
                                </div>
                            </div>
                        </div>
                        
                            <div class="col-md-12">
                                <!-- Begin: life time stats -->
                                <div class="portlet light ">
                                    <div class="portlet-title tabbable-line">
                                        <div class="caption">
                                            <i class="icon-globe font-blue"></i>
                                            <span class="caption-subject font-blue bold uppercase">Quick Yearly Projection Analysis</span>
                                        </div>
                                    </div>
                                    <div class="portlet-body">
                                        <div id="LineChartpointer" style="height: 370px; max-width: 920px; margin: 0px auto;"></div>
                                    </div>
                                </div>
                                <!-- End: life time stats -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal " id="pop" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-grey-salt">
                        <h5 class="modal-title font-dark"><strong id="titlecount"></strong></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body bg-grey-salt">

                        <h4 class="font-dark"><img src="assets/call-back.png" /> <strong>To Call Back :</strong> <span class="pull-right"> <strong id="callbackcount"></strong> </span></h4>
                        <h4 class="font-dark"><img src="assets/walking.png" /> <strong> To Walk-in :</strong> <span class="pull-right"> <strong id="walkingcount"></strong> </span></h4>

                    </div>

                </div>
            </div>
        </div>
    </div>
<?php include_once './footer-js.php'; ?>

    <script src="<?php echo $web_url; ?>Supervisor/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>

    <!-- use fixed data -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="calendar-master/zabuto_calendar.js" type="text/javascript"></script>
    <script  type="text/javascript">
        $(document).ready(function () {
            $("#FormDate").datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                todayHighlight: true,
                defaultDate: "now",
                endDate: "now",
//                                                                startDate: date
            });

        });
        $(document).ready(function () {
//                                                            var date = new Date();
            $("#ToDate").datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                todayHighlight: true,
                defaultDate: "now",
                endDate: "now",
//                                                                startDate: date
            });

        });
        $(document).ready(function () {


            $('#calendar').zabuto_calendar({
                language: 'en',
                data: [
<?php
if($_SESSION['EmployeeType'] == 'Supervisor'){
    $get_count = mysqli_query($dbconn,"select * from lead where statusId in ('1','6','7')");    
} else {
    $get_count = mysqli_query($dbconn,"select * from lead where statusId in ('1','6','7') and employeeMasterId = '" . $_SESSION['EmployeeId'] . "'");
}

while ($fetch_count = mysqli_fetch_array($get_count)) {
    if($_SESSION['EmployeeType'] == 'Supervisor'){
        $get_count_total = mysqli_fetch_array(mysqli_query($dbconn,"select COUNT(*) as totalcount from lead where statusId in ('1','6','7') and STR_TO_DATE(nextFollowupDate,'%d-%m-%Y') =  STR_TO_DATE('" . $fetch_count['nextFollowupDate'] . "','%d-%m-%Y')"));
    } else {
        $get_count_total = mysqli_fetch_array(mysqli_query($dbconn,"select COUNT(*) as totalcount from lead where statusId in ('1','6','7') and STR_TO_DATE(nextFollowupDate,'%d-%m-%Y') =  STR_TO_DATE('" . $fetch_count['nextFollowupDate'] . "','%d-%m-%Y') and employeeMasterId = '" . $_SESSION['EmployeeId'] . "'"));    
    }
    ?>
                        {
                            'date': '<?php echo date('Y-m-d', strtotime($fetch_count['nextFollowupDate'])); ?>',
                            'badge': true,
                            'title': '<?php echo $get_count_total['totalcount']; ?>'

                        },
<?php } ?>

                ],
                action: function () {
                    //get the selected date

                    var date = $('#' + this.id).data('date');
                    $.ajax({
                        type: "POST",
                        url: "ajax-calendar.php",
                        data: {date: date},
                        success: function (data) {
                            var result = data.split(',');
                            $('#titlecount').text('');
                            $('#titlecount').text(result[0]);
                            $('#callbackcount').text(result[1]);
                            $('#walkingcount').text(result[2]);
                            $('#pop').modal('show');
                        }
                    });
                    //alert the date

                },
                today: true,
            });
<?php
$get_count1 = mysqli_query($dbconn,"select * from lead where statusId in ('1','6') and employeeMasterId = '" . $_SESSION['EmployeeId'] . "'");
while ($fetch_count1 = mysqli_fetch_array($get_count1)) {
    ?>

                $('.testlabel1').text(<?php echo date('d', strtotime($fetch_count1['strEntryDate'])); ?>);
<?php } ?>

        });
    </script>


    <script type="text/javascript">



        window.onload = function () {
//                var chart_inquirystatus = new CanvasJS.Chart("pieChartstatus", {
//                    animationEnabled: true,
//                    title: {
//                        text: "Inquiry Status"
//                    },
//                    data: [{
//                            type: "pie",
//                            indexLabel: "{label} {y}",
//                            dataPoints:<?php // echo json_encode($dataPointsPieChartstatus, JSON_NUMERIC_CHECK);      ?>
//                            //                                    [
//                            //                                {y: 10, label: "Tour"},
//                            //                                {y: 15, label: "Visa"},
//                            //                                {y: 1, label: "Ticket"},
//                            //                                {y: 5, label: "Cruse"},
//                            //                                {y: 15, label: "Car"}
//                            //                            ]
//                        }]
//                });
//                chart_inquirystatus.render();

            var chart2 = new CanvasJS.Chart("LineChartpointer", {
                animationEnabled: true,
                theme: "light2",
                title: {
                    text: "Projection"
                },
                axisY: {
                    includeZero: false
                },
                data: [{
                        type: "stackedColumn",
                        dataPoints:
<?php echo json_encode($dataLineChartpointer, JSON_NUMERIC_CHECK); ?>
//                                    [
//                                {y: 450},
//                                {y: 414},
//                                    {y: 520, indexLabel: "highest", markerColor: "red", markerType: "triangle"},
//                                {y: 460},
//                                {y: 450},
//                                {y: 500},
////                                {y: 480},
////                                {y: 480},
////                                {y: 410, indexLabel: "lowest", markerColor: "DarkSlateGrey", markerType: "cross"},
////                                {y: 500},
////                                {y: 480},
////                                {y: 510}
//                            ]
                    }]
            });
            chart2.render();
        }
    </script>

    <script src="assets/canvasjs.min.js"></script>


</body>
</html>