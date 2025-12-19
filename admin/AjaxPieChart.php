<?php

error_reporting(0);
require_once('../common.php');
include('IsLogin.php');
$connect = new connect();



if ($_POST['action'] == 'ListUser') {

    if ($_REQUEST['FormDate'] == '' && $_REQUEST['ToDate'] == '') {

        $queryPieChart = "SELECT Count(*) as count,inquiryfor FROM lead where STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y') >= ADDDATE(CURRENT_DATE(), INTERVAL -13 MONTH) and STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y') <= CURRENT_DATE() GROUP by inquiryfor ORDER by inquiryfor ASC ";
        $resultPieChart = mysqli_query($dbconn,$queryPieChart);
        while ($rowPieChart = mysqli_fetch_array($resultPieChart)) {
            $yearPieChart['yearPieChart'][] = array("y" => $rowPieChart['count'], "label" => $rowPieChart['inquiryfor']);
        }
        $dataPointsPieChart = $yearPieChart['yearPieChart'];
        print_r(json_encode($dataPointsPieChart));
    } else if ($_REQUEST['FormDate'] != '' && $_REQUEST['ToDate'] != '') {
        $queryPieChart = "SELECT Count(*) as count,inquiryfor FROM lead where STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y') >= STR_TO_DATE('$_REQUEST[FormDate]','%d-%m-%Y') and STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y') <= STR_TO_DATE('$_REQUEST[ToDate]','%d-%m-%Y') GROUP by inquiryfor ORDER by inquiryfor ASC";
        $resultPieChart = mysqli_query($dbconn,$queryPieChart);
        while ($rowPieChart = mysqli_fetch_array($resultPieChart)) {
            $yearPieChart['yearPieChart'][] = array("y" => $rowPieChart['count'], "label" => $rowPieChart['inquiryfor']);
        }
        $dataPointsPieChart = $yearPieChart['yearPieChart'];
        print_r(json_encode($dataPointsPieChart));
    }
}