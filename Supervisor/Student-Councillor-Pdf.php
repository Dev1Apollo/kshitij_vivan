<?php

ob_start();
ob_clean();
require_once('tcpdf/config/tcpdf_config.php');
require_once('tcpdf/tcpdf.php');
//include('../config.php');
require_once '../common.php';
$connect = new connect();
include 'IsLogin.php';

$firstname = $_REQUEST['firstName'];
$middleName = $_REQUEST['middleName'];
$surName = $_REQUEST['surName'];
$cid = $_REQUEST['cid'];
$fee = $_REQUEST['fee'];
$offeredfee = $_REQUEST['offeredfee'];
$booking_amount = $_REQUEST['booking_amount'];
$dateOfJoining = $_REQUEST['dateOfJoining'];
$joinAmount = $_REQUEST['joinAmount'];
$emiId = $_REQUEST['emiId'];
$emiStartDate = $_REQUEST['emiStartDate'];
$noOfEmi = $_REQUEST['noOfEmi'];
$emiAmount = $_REQUEST['emiAmount'];

$less = $fee - $offeredfee;
$positive = abs($less);
$query = "SELECT * FROM course where courseId=" . $cid;
$row = mysqli_fetch_array(mysqli_query($dbconn, $query));
$mailFormat_TrData = file_get_contents("Counceling.html");

$emitype = "select * from emitype where emiId=" . $emiId;
$rowEmiType = mysqli_fetch_array(mysqli_query($dbconn, $emitype));

$taxFreeAmt = round($offeredfee / 1.18, 2);
$gst = round($offeredfee - $taxFreeAmt, 2);

$CGST = round($gst / 2, 2);
$SGST = round($gst / 2, 2);

$mailFormat_TrData = str_replace("#NAME#", ucfirst(urldecode($_REQUEST['firstName'] . ' ' . $_REQUEST['middleName'] . ' ' . $_REQUEST['surName'])), $mailFormat_TrData);
$mailFormat_TrData = str_replace("#COURSE#", ucfirst(urldecode($row['courseName'])), $mailFormat_TrData);
$mailFormat_TrData = str_replace("#Total#", ucfirst(urldecode($fee)), $mailFormat_TrData);
$mailFormat_TrData = str_replace("#Discount#", ucfirst(urldecode($positive)), $mailFormat_TrData);
$mailFormat_TrData = str_replace("#CGST#", ucfirst(urldecode($CGST)), $mailFormat_TrData);
$mailFormat_TrData = str_replace("#SGST#", ucfirst(urldecode($SGST)), $mailFormat_TrData);
$mailFormat_TrData = str_replace("#NetAmount#", ucfirst(urldecode($offeredfee)), $mailFormat_TrData);
$mailFormat_TrData = str_replace("#Monthly#", ucfirst(urldecode($rowEmiType['emiTypeName'])), $mailFormat_TrData);
$mailFormat_TrData = str_replace("#Instalment#", ucfirst(urldecode($noOfEmi)), $mailFormat_TrData);
$mailFormat_TrData = str_replace("#InstalmentAmount#", ucfirst(urldecode($emiAmount)), $mailFormat_TrData);



$BookDate = date('d-m-Y');
$mailFormat_TrData = str_replace("#BookDate#", ucfirst(urldecode($BookDate)), $mailFormat_TrData);
$mailFormat_TrData = str_replace("#BookingAmount#", ucfirst(urldecode($booking_amount)), $mailFormat_TrData);
$mailFormat_TrData = str_replace("#JoinDate#", ucfirst(urldecode($dateOfJoining)), $mailFormat_TrData);
$mailFormat_TrData = str_replace("#JoiningAmount#", ucfirst(urldecode($joinAmount)), $mailFormat_TrData);

if ($emiId == 1) {
    $noOfEmi = $_REQUEST['noOfEmi'];
    $emiStartDate = $_REQUEST['emiStartDate'];
    $mailFormat_Datanew = file_get_contents("LastCounsiling.html");
    $emiDate = date('d-m-Y', strtotime($emiStartDate));
    $emiMount = date("M'Y", strtotime($emiDate));
    $mailFormat_Datanew = str_replace("#EMIDATE#", ucfirst(urldecode($emiDate)), $mailFormat_Datanew);
    $mailFormat_Datanew = str_replace("#EMIMONTH#", ucfirst(urldecode($emiMount)), $mailFormat_Datanew);
    $mailFormat_Datanew = str_replace("#EMIAMOUNT#", ucfirst(urldecode($emiAmount)), $mailFormat_Datanew);
    $mailFormat_DataAdd = $mailFormat_DataAdd . $mailFormat_Datanew;


    $mailFormat_TrData = str_replace("#AddDetail#", ucfirst(urldecode($mailFormat_DataAdd)), $mailFormat_TrData);
    $mailFormat_TrData = $mailFormat_TrData . $mailFormat_Datanew;
}

if ($emiId == 2) {
    $noOfEmi = $_REQUEST['noOfEmi'];
    $emiStartDate = strtotime($_REQUEST['emiStartDate']);
    $i = 0;

    $mailFormat_DataTable = "";
    $mailFormat_DataTableaLLtR = "";
    while ($i < $noOfEmi) {
        $mailFormat_DataTable = file_get_contents("LastCounsiling.html");
        $emiDate = date('d-m-Y', strtotime("+$i month", $emiStartDate));
        $emiMount = date("M'Y", strtotime($emiDate));
        $mailFormat_DataTable = str_replace("#EMIDATE#", ucfirst(urldecode($emiDate)), $mailFormat_DataTable);
        $mailFormat_DataTable = str_replace("#EMIMONTH#", ucfirst(urldecode($emiMount)), $mailFormat_DataTable);
        $mailFormat_DataTable = str_replace("#EMIAMOUNT#", ucfirst(urldecode($emiAmount)), $mailFormat_DataTable);
        $mailFormat_DataTableaLLtR = $mailFormat_DataTableaLLtR . $mailFormat_DataTable;
        $i++;
    }
    $mailFormat_TrData = str_replace("#AddDetail#", ucfirst(urldecode($mailFormat_DataTableaLLtR)), $mailFormat_TrData);
    $mailFormat_TrData = $mailFormat_TrData . $mailFormat_DataTable;
}



if ($emiId == 3) {
    $noOfEmi = $_REQUEST['noOfEmi'];
    $emiStartDate = strtotime($_REQUEST['emiStartDate']);
    $i = 0;
    $noOfEmi = $noOfEmi * 3;
    $mailFormat_Data = "";
    $mailFormat_DataAdd = "";
    while ($i < $noOfEmi) {
        $mailFormat_Data = file_get_contents("LastCounsiling.html");
        $emiDate = date('d-m-Y', strtotime("+$i month", $emiStartDate));
        $emiMount = date("M'Y", strtotime($emiDate));
        $mailFormat_Data = str_replace("#EMIDATE#", ucfirst(urldecode($emiDate)), $mailFormat_Data);
        $mailFormat_Data = str_replace("#EMIMONTH#", ucfirst(urldecode($emiMount)), $mailFormat_Data);
        $mailFormat_Data = str_replace("#EMIAMOUNT#", ucfirst(urldecode($emiAmount)), $mailFormat_Data);
        $mailFormat_DataAdd = $mailFormat_DataAdd . $mailFormat_Data;
        $i = $i + 3;
    }
    $mailFormat_TrData = str_replace("#AddDetail#", ucfirst(urldecode($mailFormat_DataAdd)), $mailFormat_TrData);
    $mailFormat_TrData = $mailFormat_TrData . $mailFormat_Data;
}
$noOfEmi = $_REQUEST['noOfEmi'];
$emiAmount = $_REQUEST['emiAmount'];
$booking_amount = $_REQUEST['booking_amount'];
$joinAmount = $_REQUEST['joinAmount'];

$emitotal = $emiAmount * $noOfEmi;
$total = $emitotal + $booking_amount + $joinAmount;
$mailFormat_TrData = str_replace("#TotalFees#", ucfirst(urldecode($total)), $mailFormat_TrData);





$pdf = new TCPDF(P, PDF_UNIT, PDF_PAGE_FORMAT, 'UTF-8', false);
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, 10, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// Set font
// dejavusans is a UTF-8 Unicode font, if you only need to
// print standard ASCII chars, you can use core fonts like
// helvetica or times to reduce file size.
$pdf->SetFont('dejavusans', '', 0, '', true);
//     $pdf->SetFont('helvetica', '', 8);
// Add a page
// This method has several options, check the source code documentation for more information.
$pdf->AddPage();
$pdf->setPage($pdf->getPage());
$pdf->writeHTML($mailFormat_TrData, true, false, false, false, '');

//$pdf->writeHTML($html, true, 0);
//$pdf->writeHTML($html, true, 0);
ob_end_clean();
$pdf->Output('StudentCouncillor.pdf', 'I');
