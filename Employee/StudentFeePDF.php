<?php

ob_start();
ob_clean();
require_once('tcpdf/config/tcpdf_config.php');
require_once('tcpdf/tcpdf.php');
//include('../config.php');
require_once '../common.php';
$connect = new connect();
include 'IsLogin.php';

$studentfeeid = $_REQUEST['token'];

//$mailFormat_TrData = "";
$mailFormat_TrData = file_get_contents("receipt.html");
//echo "SELECT studentfee.* , studentadmission.* FROM `studentfee` Join studentadmission ON studentadmission.stud_id=studentfee.stud_id  where studentfeeid='".$studentfeeid."'";
//exit;
$query = mysqli_query($dbconn,"SELECT studentfee.* , studentadmission.* FROM `studentfee` Join studentadmission ON studentadmission.stud_id=studentfee.stud_id  where studentfeeid='" . $studentfeeid . "'");
$filterData = mysqli_fetch_array($query);
$number = $filterData['amount'];
convert_number_to_words($number);

function convert_number_to_words($number)  {

    $hyphen = '-';
    $conjunction = '  ';
    $separator = ' ';
    $negative = 'negative ';
    $decimal = ' point ';
    $dictionary = array(
        0 => 'Zero',
        1 => 'One',
        2 => 'Two',
        3 => 'Three',
        4 => 'Four',
        5 => 'Five',
        6 => 'Six',
        7 => 'Seven',
        8 => 'Eight',
        9 => 'Nine',
        10 => 'Ten',
        11 => 'Eleven',
        12 => 'Twelve',
        13 => 'Thirteen',
        14 => 'Fourteen',
        15 => 'Fifteen',
        16 => 'Sixteen',
        17 => 'Seventeen',
        18 => 'Eighteen',
        19 => 'Nineteen',
        20 => 'Twenty',
        30 => 'Thirty',
        40 => 'Fourty',
        50 => 'Fifty',
        60 => 'Sixty',
        70 => 'Seventy',
        80 => 'Eighty',
        90 => 'Ninety',
        100 => 'Hundred',
        1000 => 'Thousand',
        1000000 => 'Million',
        1000000000 => 'Billion',
        1000000000000 => 'Trillion',
        1000000000000000 => 'Quadrillion',
        1000000000000000000 => 'Quintillion'
    );

    if (!is_numeric($number)) {
        return false;
    }

    if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
        // overflow
        trigger_error(
                'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX, E_USER_WARNING
        );
        return false;
    }

    if ($number < 0) {
        return $negative . convert_number_to_words(abs($number));
    }

    $string = $fraction = null;

    if (strpos($number, '.') !== false) {
        list($number, $fraction) = explode('.', $number);
    }

    switch (true) {
        case $number < 21:
            $string = $dictionary[$number];
            break;
        case $number < 100:
            $tens = ((int) ($number / 10)) * 10;
            $units = $number % 10;
            $string = $dictionary[$tens];
            if ($units) {
                $string .= $hyphen . $dictionary[$units];
            }
            break;
        case $number < 1000:
            $hundreds = $number / 100;
            $remainder = $number % 100;
            $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
            if ($remainder) {
                $string .= $conjunction . convert_number_to_words($remainder);
            }
            break;
        default:
            $baseUnit = pow(1000, floor(log($number, 1000)));
            $numBaseUnits = (int) ($number / $baseUnit);
            $remainder = $number % $baseUnit;
            $string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
            if ($remainder) {
                $string .= $remainder < 100 ? $conjunction : $separator;
                $string .= convert_number_to_words($remainder);
            }
            break;
    }

    if (null !== $fraction && is_numeric($fraction)) {
        $string .= $decimal;
        $words = array();
        foreach (str_split((string) $fraction) as $number) {
            $words[] = $dictionary[$number];
        }
        $string .= implode(' ', $words);
    }

    return $string;
}


$filterCity = mysqli_fetch_array(mysqli_query($dbconn,"SELECT * FROM `city` where cityid='" . $filterData['city'] . "' and istatus='1' and isDelete='0'"));
$filterState = mysqli_fetch_array(mysqli_query($dbconn,"SELECT * FROM `state` where stateId='" . $filterData['state'] . "' and istatus='1' and isDelete='0'"));
$filterBank = mysqli_fetch_array(mysqli_query($dbconn,"SELECT bankName FROM `bankmaster` where isDelete='0' and istatus='1' and bankMasterId='".$filterData['bankName']."' "));
$address='';
if (isset($filterData['addresstwo']) && $filterData['addresstwo'] != '') {
    $address = $filterData['address'] . ', ' . $filterData['addresstwo'] . ', ' . $filterCity['name'] . ', ' . $filterState['stateName'];
} else if (isset($filterData['address']) && $filterData['address'] != '') {
    $address = $filterData['address'] . ', ' . $filterCity['name'] . ', ' . $filterState['stateName'];
}

$mailFormat_TrData = str_replace("#receiptNo#", ucfirst(urldecode($filterData['receiptNo'])), $mailFormat_TrData);
//$mailFormat_TrData = str_replace("#ReceiptDate#", ucfirst(urldecode($filterData['payDate'])), $mailFormat_TrData);
$mailFormat_TrData = str_replace("#Date#", ucfirst(urldecode($filterData['payDate'])), $mailFormat_TrData);
if ($filterData['isRegister'] == 1 && $filterData['isAdmission'] == 1) {
    $studentEnrollment = $filterData['studentEnrollment'];
    $studcourseId = mysqli_fetch_array(mysqli_query($dbconn,"SELECT bookingId FROM `studentcourse` where stud_id='".$filterData['stud_id']."' and studentcourseId=".$filterData['studentcourseId']." and studentcourse.istatus=1"));
    $bookId = $studcourseId['bookingId'];
} else {
    $studentEnrollment = '';
    $mailFormat_DataAdd = '';
    $bookId='';
}
$mailFormat_TrData = str_replace("#studentPortal_Id#", ucfirst(urldecode($studentEnrollment)), $mailFormat_TrData);
$mailFormat_TrData = str_replace("#Name#", ucfirst(urldecode($filterData['firstName'] . ' ' . $filterData['surName'])), $mailFormat_TrData);
$mailFormat_TrData = str_replace("#Address#", ucfirst(urldecode($address)), $mailFormat_TrData);
$mailFormat_TrData = str_replace("#BookingAmt#", ucfirst($bookId), $mailFormat_TrData);
$mailFormat_TrData = str_replace("#INR#", ucfirst(urldecode(convert_number_to_words($filterData['amount']))), $mailFormat_TrData);


if ($filterData['paymentMode'] != "2") {
    $mailFormat_Datanew = file_get_contents("Cash.html");
    $mailFormat_Datanew = str_replace("#CashAmount#", ucfirst(urldecode($filterData['amount'])), $mailFormat_Datanew);
    $mailFormat_DataAdd = $mailFormat_DataAdd . $mailFormat_Datanew;
    $mailFormat_TrData = str_replace("#CashDetail#", ucfirst(urldecode($mailFormat_DataAdd)), $mailFormat_TrData);
    $mailFormat_TrData = $mailFormat_TrData . $mailFormat_Datanew;
    $mailFormat_TrData = str_replace("#BankDetail#", "", $mailFormat_TrData);
} else if ($filterData['paymentMode'] == "2") {
    $mailFormat_DataTable = file_get_contents("Bank.html");
    $mailFormat_DataTable = str_replace("#ChequeAmount#", ucfirst(urldecode($filterData['amount'])), $mailFormat_DataTable);
    $mailFormat_DataTable = str_replace("#ChequeNo#", ucfirst(urldecode($filterData['chequeNo'])), $mailFormat_DataTable);
    $mailFormat_DataTable = str_replace("#ChequeDate#", ucfirst(urldecode($filterData['payDate'])), $mailFormat_DataTable);
    $mailFormat_DataTable = str_replace("#BankName#", ucfirst(urldecode($filterBank['bankName'])), $mailFormat_DataTable);

    $mailFormat_DataTableaLLtR = $mailFormat_DataTableaLLtR . $mailFormat_DataTable;

    $mailFormat_TrData = str_replace("#BankDetail#", ucfirst(urldecode($mailFormat_DataTableaLLtR)), $mailFormat_TrData);
    $mailFormat_TrData = str_replace("#CashDetail#", "", $mailFormat_TrData);
    $mailFormat_TrData = $mailFormat_TrData . $mailFormat_DataTable;
}


$mailFormat_TrData = str_replace("#COURSEFEE#", ucfirst(urldecode($filterData['texFreeAmt'])), $mailFormat_TrData);
$mailFormat_TrData = str_replace("#CGST#", ucfirst(urldecode($filterData['decGst'] / 2)), $mailFormat_TrData);
$mailFormat_TrData = str_replace("#SGST#", ucfirst(urldecode($filterData['decGst'] / 2)), $mailFormat_TrData);
$mailFormat_TrData = str_replace("#Total#", ucfirst(urldecode($filterData['amount'])), $mailFormat_TrData);

$amt = $filterData['amount'];

function formatPageNumber($amt) {
    $strnum = strval($amt);
    $strnum = preg_replace_callback("/[0-9]/", create_function('$matches', '
        $numarr = array("۰", "۱", "۲", "۳", "۴", "۵", "۶", "۷", "۸", "۹");
        return $numarr[intval($matches[0])];'), $strnum);
    return $strnum;
}

//$mailFormat_TrData = str_replace("#INR#", ucfirst(urldecode($strnum)), $mailFormat_TrData);




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
$pdf->Output('StudentFeeRecceipt.pdf', 'I');
?>