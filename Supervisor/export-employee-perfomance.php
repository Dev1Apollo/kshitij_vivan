<?php
//include database configuration file
require_once('../config.php');
include('IsLogin.php');
//$connect = new connect();

//get records from database
$where = "where 1=1 and employeeMasterId = '".$_SESSION['EmployeeId']."'";

    if ($_REQUEST['FormDate'] != NULL && isset($_REQUEST['FormDate']))
        $where.=" and STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y')>= STR_TO_DATE('$_REQUEST[FormDate]','%d-%m-%Y')";
    if ($_REQUEST['ToDate'] != NULL && isset($_REQUEST['ToDate']))
        $where.=" and STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y')<=STR_TO_DATE('$_REQUEST[ToDate]','%d-%m-%Y')";
    
    $whereA = "where 1=1";
    if ($_REQUEST['FormDate'] != NULL && isset($_REQUEST['FormDate']))
        $whereA.=" and STR_TO_DATE(l1.nextFollowupModifyDate,'%d-%m-%Y')>= STR_TO_DATE('$_REQUEST[FormDate]','%d-%m-%Y')";
    if ($_REQUEST['ToDate'] != NULL && isset($_REQUEST['ToDate']))
        $whereA.=" and STR_TO_DATE(l1.nextFollowupModifyDate,'%d-%m-%Y')<=STR_TO_DATE('$_REQUEST[ToDate]','%d-%m-%Y')";
    
echo "SELECT count(*) as Inqcount,employeeMasterId ,(select employeemaster.employeeName from employeemaster where employeemaster.employeeMasterId = lead.employeeMasterId) as EmployeeName ,(select count(*) from lead as l1 " . $whereA . " and l1.statusId = 3 and l1.employeeMasterId = lead.employeeMasterId ) as bookedInq,(select count(*) from lead as l1 " . $whereA . "  and walkin_datetime != ''and l1.employeeMasterId = lead.employeeMasterId ) as walkininquiry,(select count(*) from lead as l1 " . $whereA . " and walkin_datetime != '' and l1.statusId = 3 and l1.employeeMasterId = lead.employeeMasterId) as convertedinquiry  FROM `lead`  " . $where . "   GROUP by employeeMasterId";
    $query = mysqli_query($dbconn,"SELECT count(*) as Inqcount,employeeMasterId ,(select employeemaster.employeeName from employeemaster where employeemaster.employeeMasterId = lead.employeeMasterId) as EmployeeName ,(select count(*) from lead as l1 " . $whereA . " and l1.statusId = 3 and l1.employeeMasterId = lead.employeeMasterId ) as bookedInq,(select count(*) from lead as l1 " . $whereA . "  and walkin_datetime != ''and l1.employeeMasterId = lead.employeeMasterId ) as walkininquiry,(select count(*) from lead as l1 " . $whereA . " and walkin_datetime != '' and l1.statusId = 3 and l1.employeeMasterId = lead.employeeMasterId) as convertedinquiry  FROM `lead`  " . $where . "   GROUP by employeeMasterId");
exit;
if(mysqli_num_rows($query) > 0){
    $delimiter = ",";
    $filename = "Employee-Perfomanace_" . date('Y-m-d H:i:s') . ".csv";
    //create a file pointer
    $f = fopen('php://memory', 'w');
    //set column headers
    $fields = array('ID','Employee Name', 'Total Inquiry', 'Walk-in Inquiry', 'Walk-in Inquiry Percentage' ,'Booked Inquiry', 'Booked Inquiry Percentage');
    fputcsv($f, $fields, $delimiter);
    $i=1;
    //output each row of the data, format line as csv and write to file pointer
    while($row = mysqli_fetch_assoc($query)){
       
        
        $perntage = $row['walkininquiry'] / $row['Inqcount'];
        $perntage_book = $row['bookedInq'] / $row['walkininquiry'];
                               $lineData = array($i, $row['EmployeeName'], $row['Inqcount'],$row['walkininquiry'],number_format($perntage * 100, 2) . '%',$row['bookedInq'],number_format($perntage_book * 100, 2) . '%');
        fputcsv($f, $lineData, $delimiter);
    $i++;}
    
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
    header('location:EmployeePerfomance.php?flg=1');
}
exit;

?>