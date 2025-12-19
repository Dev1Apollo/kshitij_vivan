<?php
error_reporting(0);
require_once('../common.php');
include('IsLogin.php');
$connect = new connect();

//get records from database
// $where = "where 1=1 and employeeMasterId = '".$_SESSION['EmployeeId']."'";

   /*$where = "where 1=1 ";
    if ($_REQUEST['FormDate'] != NULL && isset($_REQUEST['FormDate']))
        $where .= " and STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y')>= STR_TO_DATE('$_REQUEST[FormDate]','%d-%m-%Y')";
    if ($_REQUEST['ToDate'] != NULL && isset($_REQUEST['ToDate']))
        $where .= " and STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y')<=STR_TO_DATE('$_REQUEST[ToDate]','%d-%m-%Y')";
    
    $whereA = "";
    if ($_REQUEST['FormDate'] != NULL && isset($_REQUEST['FormDate']))
        $whereA .= " and STR_TO_DATE(l1.nextFollowupModifyDate,'%d-%m-%Y')>= STR_TO_DATE('$_REQUEST[FormDate]','%d-%m-%Y')";
    if ($_REQUEST['ToDate'] != NULL && isset($_REQUEST['ToDate']))
        $whereA .= " and STR_TO_DATE(l1.nextFollowupModifyDate,'%d-%m-%Y')<=STR_TO_DATE('$_REQUEST[ToDate]','%d-%m-%Y')";
        
    $whereB = "";
    if ($_REQUEST['FormDate'] != NULL && isset($_REQUEST['FormDate']))
        $whereB .= " and STR_TO_DATE(l1.walkin_datetime,'%d-%m-%Y')>= STR_TO_DATE('$_REQUEST[FormDate]','%d-%m-%Y')";
    if ($_REQUEST['ToDate'] != NULL && isset($_REQUEST['ToDate']))
        $whereB .= " and STR_TO_DATE(l1.walkin_datetime,'%d-%m-%Y')<=STR_TO_DATE('$_REQUEST[ToDate]','%d-%m-%Y')";
    
    $whereC = "";
    if ($_REQUEST['FormDate'] != NULL && isset($_REQUEST['FormDate']))
        $whereC .= " and STR_TO_DATE(nextFollowupDate,'%d-%m-%Y')>= STR_TO_DATE('$_REQUEST[FormDate]','%d-%m-%Y')";
    if ($_REQUEST['ToDate'] != NULL && isset($_REQUEST['ToDate']))
        $whereC .= " and STR_TO_DATE(nextFollowupDate,'%d-%m-%Y')<=STR_TO_DATE('$_REQUEST[ToDate]','%d-%m-%Y')";*/
    
//echo "SELECT count(*) as Inqcount,employeeMasterId ,(select employeemaster.employeeName from employeemaster where employeemaster.employeeMasterId = lead.employeeMasterId) as EmployeeName ,(select count(*) from lead as l1 " . $whereA . " and l1.statusId = 3 and l1.employeeMasterId = lead.employeeMasterId ) as bookedInq,(select count(*) from lead as l1 " . $whereA . "  and walkin_datetime != ''and l1.employeeMasterId = lead.employeeMasterId ) as walkininquiry,(select count(*) from lead as l1 " . $whereA . " and walkin_datetime != '' and l1.statusId = 3 and l1.employeeMasterId = lead.employeeMasterId) as convertedinquiry  FROM `lead`  " . $where . "   GROUP by employeeMasterId";
  //  $query = mysqli_query($dbconn,"SELECT count(*) as Inqcount,employeeMasterId ,(select employeemaster.employeeName from employeemaster where employeemaster.employeeMasterId = lead.employeeMasterId) as EmployeeName ,(select count(*) from lead as l1 " . $whereA . " and l1.statusId = 3 and l1.employeeMasterId = lead.employeeMasterId ) as bookedInq,(select count(*) from lead as l1 " . $whereA . "  and walkin_datetime != ''and l1.employeeMasterId = lead.employeeMasterId ) as walkininquiry,(select count(*) from lead as l1 " . $whereA . " and walkin_datetime != '' and l1.statusId = 3 and l1.employeeMasterId = lead.employeeMasterId) as convertedinquiry  FROM `lead`  " . $where . "   GROUP by employeeMasterId");
    /*$query = "SELECT id,support_emp_name as EmployeeName,
                (select COUNT(*) cnt from leadfollowup as lf where lf.support_employee=s.id and lf.statusId=1 ".$whereC.") as CallCount,
                (select count(DISTINCT leadId) as cnt from leadfollowup as l1 where 1=1 ".$whereC." and l1.support_employee = s.id and l1.support_employee!=0 and l1.statusId in(6,7)) as walkininquiry,
                (select count(DISTINCT leadId) as cnt from leadfollowup as l1 where 1=1 ".$whereC." and l1.transfer_to = s.id and l1.support_employee!=0 and l1.statusId in(6,7)) as CounselingInquiry,
                (select count(*) from lead as l1 where 1=1 ".$whereA." and l1.statusId = 3 and l1.support_employee = s.id and l1.support_employee!=0) as bookedInq,
                (select sum(l1.booking_amount) from lead as l1 where 1=1 ".$whereA." and l1.statusId = 3 and l1.support_employee = s.id and l1.support_employee!=0) as bookingAmount
                FROM `support_employee` as s where s.istatus=1 and s.isDelete=0";*/
                
    $formDate = $_REQUEST['FormDate'] ?? null;
    $toDate = $_REQUEST['ToDate'] ?? null;
    
    // Sanitize and prepare filters
    $dateFilters = function($column) use ($formDate, $toDate) {
        $filter = "";
        if (!empty($formDate)) {
            $filter .= " AND STR_TO_DATE($column,'%d-%m-%Y') >= STR_TO_DATE('$formDate','%d-%m-%Y')";
        }
        if (!empty($toDate)) {
            $filter .= " AND STR_TO_DATE($column,'%d-%m-%Y') <= STR_TO_DATE('$toDate','%d-%m-%Y')";
        }
        return $filter;
    };
    
    // Define reusable date filters
    $whereC = $dateFilters('nextFollowupDate');
    $whereA = $dateFilters('l1.nextFollowupModifyDate');
    $whereB = $dateFilters('l1.walkin_datetime');
    
    $query = "SELECT  id, support_emp_name AS EmployeeName,(SELECT COUNT(*) FROM leadfollowup AS lf WHERE lf.support_employee = s.id AND lf.statusId in (1,6,7) $whereC) AS CallCount,
    (SELECT COUNT(DISTINCT leadId) FROM leadfollowup AS l1 WHERE l1.walkinby = s.id  AND l1.statusId IN (6,7) $whereC) AS walkininquiry,
    (SELECT COUNT(DISTINCT leadId) FROM leadfollowup AS l1 WHERE l1.transfer_to = s.id  AND l1.statusId IN (6,7) $whereC) AS CounselingInquiry,
    (SELECT COUNT(*) FROM lead AS l1 WHERE l1.statusId = 3 AND l1.bookedby = s.id  $whereA) AS bookedInq,
    (SELECT SUM(l1.booking_amount) FROM lead AS l1 WHERE l1.statusId = 3 AND l1.bookedby = s.id  $whereA) AS bookingAmount
    FROM support_employee AS s WHERE s.istatus = 1 AND s.isDelete = 0";
    
$q= mysqli_query($dbconn, $query);

if(mysqli_num_rows($q) > 0){

    $delimiter = ",";
    $filename = "New-Employee-Perfomanace_" . date('Y-m-d H:i:s') . ".csv";
    //create a file pointer
    $f = fopen('php://memory', 'w');
    //set column headers
    $fields = array('ID','Employee Name', 'Total Inquiry (Call)', 'Walk-in Inquiry', 'Counseling Inquiry' ,'Booked Inquiry', 'Booked Amount');
    fputcsv($f, $fields, $delimiter);
    $i=1;
    //output each row of the data, format line as csv and write to file pointer
    while($row = mysqli_fetch_assoc($q)){
        $lineData = array($i, $row['EmployeeName'], $row['CallCount'],$row['walkininquiry'],$row['CounselingInquiry'],$row['bookedInq'],$row['bookingAmount']);
        fputcsv($f, $lineData, $delimiter);
        $i++;
    }
    
    //move back to beginning of file
    fseek($f, 0);
    
    //set headers to download file rather than displayed
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '";');
    
    //output all remaining data on a file pointer
    fpassthru($f);
}
else
{
    header('location:NewEmployeePerfomance.php?flg=1');
}
exit;

?>