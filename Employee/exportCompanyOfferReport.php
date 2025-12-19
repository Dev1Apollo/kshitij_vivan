<?php
error_reporting(0);
require_once('../config.php');
include('IsLogin.php');

$whereEmpId = " and studentadmission.branchId = '" . $_SESSION['branchid'] . "'";
$where = "";
if (isset($_REQUEST['FormDate']) && $_REQUEST['FormDate'] != '') {
	$where .= " and STR_TO_DATE(studentjobsubmission.strEntryDate,'%d-%m-%Y') >= STR_TO_DATE('" . $_REQUEST['FormDate'] . "','%d-%m-%Y')";
} 
if (isset($_REQUEST['ToDate']) && $_REQUEST['ToDate'] != '') {
	$where .= " and STR_TO_DATE(studentjobsubmission.strEntryDate,'%d-%m-%Y') <= STR_TO_DATE('" . $_REQUEST['ToDate'] . "','%d-%m-%Y')";
}
// (select company.strCompanyName from company where company.id=jobmaster.iCompanyId) as 'Company'
$filterstr = "SELECT jobcategory.iJobCategoryId,jobmaster.iCompanyId,jobcategory.strJobCategory,
	        company.strCompanyName as Company
	        ,strContactPerson,strContactNumber,strEmail,strDesgination,strWebsite
	        ,sum(jobmaster.iPosition) as 'No_of_jobs',
	        (select count(*) from studentjobsubmission where studentjobsubmission.iJobId=jobmaster.iJobId ".$where.") as 'Interview',
	        (select count(*) from studentjobsubmission where studentjobsubmission.iJobId=jobmaster.iJobId and studentjobsubmission.iJobStatus=1 ".$where.") as 'Pass',
	        (select count(*) from studentjobsubmission where studentjobsubmission.iJobId=jobmaster.iJobId and studentjobsubmission.iJobStatus=2 ".$where.") as 'Fail',
	        (select count(studentjobsubmission.strPlacementDate) from studentjobsubmission where studentjobsubmission.iJobId=jobmaster.iJobId and studentjobsubmission.iJobStatus=1 ".$where.") as 'Join',
	        (select AVG(studentjobsubmission.iSalary) from studentjobsubmission where studentjobsubmission.iJobId=jobmaster.iJobId and studentjobsubmission.iJobStatus=1 ".$where.") as 'Avg_Salary' 
	        FROM jobcategory inner JOIN jobmaster on jobcategory.iJobCategoryId=jobmaster.iJobCategoryId inner join company on company.id=jobmaster.iCompanyId where jobmaster.isDelete=0 and jobmaster.iStatus=1 GROUP BY jobmaster.iJobCategoryId,jobmaster.iCompanyId";
	
$query = mysqli_query($dbconn,$filterstr);

if (mysqli_num_rows($query) > 0) {
    $delimiter = ",";
    $filename = "Student-Job-Placement-Report_" . date('Y-m-d H:i:s') . ".csv";
    //create a file pointer
    $f = fopen('php://memory', 'w');
    //set column headers
    
    $fields = array(
        'Sr.No',
        'Category',
        'Company Name',
        'No of jobs',
        'Contact Person',
        'Mobile',
        'Email',
        'Desgination',
        'Website'
    );

    fputcsv($f, $fields, $delimiter);
    $i = 1;
    //output each row of the data, format line as csv and write to file pointer
    while ($row = mysqli_fetch_assoc($query)) {
        
        $lineData = array(
            $i,
            $row['strJobCategory'],
            $row['Company'],
            $row['No_of_jobs'],
            $row['strContactPerson'],
            $row['strContactNumber'],
            $row['strEmail'],
            $row['strDesgination'],
            $row['strWebsite']
        );
        fputcsv($f, $lineData, $delimiter);
        $i++;
    }
    fputcsv($f, $Total, $delimiter);
    //move back to beginning of file
    fseek($f, 0);

    //set headers to download file rather than displayed
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '";');

    //output all remaining data on a file pointer
    fpassthru($f);
} else {
    header('location:CompanyReport.php');
}
exit;
?>