<?php
error_reporting(0);
require_once('../config.php');
include('IsLogin.php');

$whereEmpId = " and studentadmission.branchId = '" . $_SESSION['branchid'] . "'";
$where = "where 1=1 ";
if (isset($_REQUEST['FormDate']) && $_REQUEST['FormDate'] != '') {
	$where .= " and STR_TO_DATE(sjs.strPlacementDate,'%d-%m-%Y') >= STR_TO_DATE('" . $_REQUEST['FormDate'] . "','%d-%m-%Y')";
} 
if (isset($_REQUEST['ToDate']) && $_REQUEST['ToDate'] != '') {
	$where .= " and STR_TO_DATE(sjs.strPlacementDate,'%d-%m-%Y') <= STR_TO_DATE('" . $_REQUEST['ToDate'] . "','%d-%m-%Y')";
}
$filterstr = "SELECT sjs.iJobSubmissionId,sjs.iStudId,sjs.strPlacementDate,sjs.strInterviewDate,sjs.iJobId,sjs.iStudId,sjs.iJobStatus,sjs.iSalary,sjs.strRemarks,sjs.iJobCategoryId,(select jobcategory.strJobCategory from jobcategory where sjs.iJobCategoryId=jobcategory.iJobCategoryId) as strJobCategory,jm.strJobTitle,jm.iPosition,jm.strExperience,jm.strEndDate,(select company.strCompanyName from company where company.id=sjs.iCompanyId) as strCompanyName FROM studentjobsubmission sjs left join jobmaster jm on sjs.iJobId=jm.iJobId  " . $where . " and sjs.iStatus=1 and sjs.iJobStatus=1 order by sjs.iJobSubmissionId asc";
	
$query = mysqli_query($dbconn,$filterstr);

if (mysqli_num_rows($query) > 0) {
    $delimiter = ",";
    $filename = "Student-Placed-Report_" . date('Y-m-d H:i:s') . ".csv";
    //create a file pointer
    $f = fopen('php://memory', 'w');
    //set column headers
    
    $fields = array(
        'Sr.No',
        'Placement Date',
        'Branch',
        'Student Name',
        'Student Contact',
        'Student Email',
        'Company Name',
        'Job Category',
        'Salary',
        'Remarks'
    );

    fputcsv($f, $fields, $delimiter);
    $i = 1;
    //output each row of the data, format line as csv and write to file pointer
    while ($row = mysqli_fetch_assoc($query)) {
        $Student = mysqli_fetch_array(mysqli_query($dbconn,"SELECT *,(select branchmaster.branchname from branchmaster where branchmaster.branchid=studentadmission.branchId) as 'branchname' FROM `studentadmission` where stud_id=" . $row['iStudId']));
        $studentName = $Student['title'] . ' ' . $Student['firstName'] . ' ' . $Student['middleName'] . ' ' . $Student['surName'];
        $iJobStatus=  "";
        if($row['iJobStatus'] == 1){
			$iJobStatus= "Pass";
		} else if($row['iJobStatus'] == 2){
			$iJobStatus= "Fail";
		} else if($row['iJobStatus'] == 3){
			$iJobStatus= "Not Attempted";
		}else if($row['iJobStatus'] == 4){
			$iJobStatus= "Pass But Not Join";
		} else {
			$iJobStatus= "Pending";
		}
        $lineData = array(
            $i,
            date('M-Y',strtotime($row['strPlacementDate'])),
            $Student['branchname'],
            $studentName,
            $Student['mobileOne'],
            $Student['email'],
            $row['strCompanyName'],
            $row['strJobCategory'],
            $row['iSalary'],
            $row['strRemarks']
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