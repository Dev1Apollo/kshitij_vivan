<?php
ob_start();
ob_clean();
require_once('../Employee/tcpdf/config/tcpdf_config.php');
require_once('../Employee/tcpdf/tcpdf.php');
//include('../config.php');
require_once '../common.php';
$connect = new connect();
include 'IsLogin.php';

$stud_id = $_REQUEST['token'];
$studentcourseId = $_REQUEST['studentcourseId'];

$student = mysqli_fetch_array(mysqli_query($dbconn,"select * from studentcourse where stud_id=" . $stud_id . " and studentcourseId=" . $studentcourseId . " and studentcourse.istatus=1"));
$BookDate= $student['dateOfJoining'];
$query = "SELECT * FROM course where courseId  in (" . $student['courseId'] . ")";

$row = mysqli_query($dbconn,$query);
$courseName = '';
while ($rowdata = mysqli_fetch_array($row)){
    $courseName = $rowdata['courseName'] . ',' . $courseName;
}
$courseName = rtrim($courseName, ',');

$query = mysqli_fetch_array(mysqli_query($dbconn,"SELECT * FROM `studentadmission` where stud_id=" . $stud_id));

$emitype = "select * from emitype where emiId=" . $student['emiType'];
$rowEmiType = mysqli_fetch_array(mysqli_query($dbconn,$emitype));

$JoiningAmount = mysqli_fetch_array(mysqli_query($dbconn,"SELECT * FROM `studentemidetail` where stud_id=" . $stud_id . " and studentcourseId=" . $studentcourseId . " and studentemidetail.isDelete=0 LIMIT 1"));

$fee = $student['fee'];
$offeredfee = $student['offeredfee'];
$booking_amount = $student['booking_amount'];
$dateOfJoining = $student['dateOfJoining'];
$joinAmount = $JoiningAmount['joinAmount'];
$noOfEmi = $student['noOfEmi'];
$emiAmount = $student['emiAmount'];
$emiStartDate = $student['emiStartDate'];
$emiId = $student['emiType'];

$less = $fee - $offeredfee;
$positive = abs($less);

$taxFreeAmt = round($offeredfee / 1.18, 2);
$gst = round($offeredfee - $taxFreeAmt, 2);

$CGST = round($gst / 2, 2);
$SGST = round($gst / 2, 2);

$mailFormat_TrData = file_get_contents("EmiDetails.html");

$mailFormat_TrData = str_replace("#NAME#", ucfirst(urldecode($query['firstName'] . ' ' . $query['middleName'] . ' ' . $query['surName'])), $mailFormat_TrData);
$mailFormat_TrData = str_replace("#COURSE#", ucfirst(urldecode($courseName)), $mailFormat_TrData);
$mailFormat_TrData = str_replace("#Total#", ucfirst(urldecode($fee)), $mailFormat_TrData);
$mailFormat_TrData = str_replace("#Discount#", ucfirst(urldecode($positive)), $mailFormat_TrData);
//$mailFormat_TrData = str_replace("#CGST#", ucfirst(urldecode($CGST)), $mailFormat_TrData);
//$mailFormat_TrData = str_replace("#SGST#", ucfirst(urldecode($SGST)), $mailFormat_TrData);
$mailFormat_TrData = str_replace("#NetAmount#", ucfirst(urldecode($offeredfee)), $mailFormat_TrData);
$mailFormat_TrData = str_replace("#Monthly#", ucfirst(urldecode($rowEmiType['emiTypeName'])), $mailFormat_TrData);
$mailFormat_TrData = str_replace("#Instalment#", ucfirst(urldecode($noOfEmi)), $mailFormat_TrData);
$mailFormat_TrData = str_replace("#InstalmentAmount#", ucfirst(urldecode($emiAmount)), $mailFormat_TrData);

//$BookDate = date('d-m-Y');
$mailFormat_TrData = str_replace("#BookDate#", ucfirst(urldecode($BookDate)), $mailFormat_TrData);
$mailFormat_TrData = str_replace("#BookingAmount#", ucfirst(urldecode($booking_amount)), $mailFormat_TrData);
$mailFormat_TrData = str_replace("#JoinDate#", ucfirst(urldecode($dateOfJoining)), $mailFormat_TrData);
$mailFormat_TrData = str_replace("#JoiningAmount#", ucfirst(urldecode($joinAmount)), $mailFormat_TrData);

if ($emiId == 1) {
    $emiStartDate = $student['emiStartDate'];
    $mailFormat_Datanew = file_get_contents("EmiList.html");
//    echo $time = strtotime($emiStartDate);
    $emiDate = date('d-m-Y', strtotime($emiStartDate));
    $emiMount = date("M'Y", strtotime($emiDate));
    
    $mailFormat_Datanew = str_replace("#EMIDATE#", ucfirst(urldecode("-")), $mailFormat_Datanew);
    $mailFormat_Datanew = str_replace("#EMIMONTH#", ucfirst(urldecode("-")), $mailFormat_Datanew);
    $mailFormat_Datanew = str_replace("#EMIAMOUNT#", ucfirst(urldecode("-")), $mailFormat_Datanew);
    $mailFormat_DataAdd = $mailFormat_DataAdd . $mailFormat_Datanew;


    $mailFormat_TrData = str_replace("#AddDetail#", ucfirst(urldecode($mailFormat_DataAdd)), $mailFormat_TrData);
    $mailFormat_TrData = $mailFormat_TrData . $mailFormat_Datanew;
} else
    if ($emiId == 2) {
    $i = 0;
    $emiStartDate = $student['emiStartDate'];
    $time = strtotime($emiStartDate);

    $mailFormat_DataTable = "";
    $mailFormat_DataTableaLLtR = "";
    while ($i < $noOfEmi) {
        $mailFormat_DataTable = file_get_contents("EmiList.html");
        $emiDate = date('d-m-Y', strtotime("+$i month", $time));
        $emiMount = date("M'Y", strtotime($emiDate));
        $mailFormat_DataTable = str_replace("#EMIDATE#", ucfirst(urldecode($emiDate)), $mailFormat_DataTable);
        $mailFormat_DataTable = str_replace("#EMIMONTH#", ucfirst(urldecode($emiMount)), $mailFormat_DataTable);
        $mailFormat_DataTable = str_replace("#EMIAMOUNT#", ucfirst(urldecode($emiAmount)), $mailFormat_DataTable);
        $mailFormat_DataTableaLLtR = $mailFormat_DataTableaLLtR . $mailFormat_DataTable;
        $i++;
    }
    $mailFormat_TrData = str_replace("#AddDetail#", ucfirst(urldecode($mailFormat_DataTableaLLtR)), $mailFormat_TrData);
    $mailFormat_TrData = $mailFormat_TrData . $mailFormat_DataTable;
}elseif ($emiId == 3) {
    $emiStartDate = $student['emiStartDate'];
    $time = strtotime($emiStartDate);
    $i = 0;
    $noOfEmi = $noOfEmi * 3;
    $mailFormat_Data = "";
    $mailFormat_DataAdd = "";
    while ($i < $noOfEmi) {
        $mailFormat_Data = file_get_contents("EmiList.html");
        $emiDate = date('d-m-Y', strtotime("+$i month", $time));
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

$emitotal = $emiAmount * $student['noOfEmi'];

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
$pdf->Output('StudentFeesHistory.pdf', 'I');
