<?php
error_reporting(0);
//include database configuration file
require_once('../config.php');
include('IsLogin.php');
//$connect = new connect();
//get records from database
// $whereEmpId = " and studentadmission.employeeMasterId = '" . $_SESSION['EmployeeId'] . "'";
//get records from database
$whereEmpId = " and studentadmission.branchId = '" . $_SESSION['branchid'] . "'";
$where = " ";
$where = "where 1=1 ";
if (isset($_REQUEST['Search_Company'])) {
	if ($_REQUEST['Search_Company'] != '') {
		$where .= " and  strCompanyName like '%$_REQUEST[Search_Company]%'";
	}
}

if (isset($_REQUEST['Search_Category'])) {
	if ($_REQUEST['Search_Category'] != '') {
		$where .= " and  iJobCategoryId = '$_REQUEST[Search_Category]'";
	}
}

$filterstr = "SELECT iJobId,strCompanyName,strJobTitle,strExperience,iPosition,jobmaster.strEntryDate,jobmaster.iStatus,jobmaster.strJobDescription,jobmaster.strEndDate,jobmaster.iJobCategoryId FROM `jobmaster` inner join company  on company.id=jobmaster.iCompanyId " . $where . " and jobmaster.isDelete='0' and jobmaster.iStatus='1' order by iJobId desc";
$query = mysqli_query($dbconn,$filterstr);

if (mysqli_num_rows($query) > 0) {
    $delimiter = ",";
    $filename = "Active-Job-Report_" . date('Y-m-d H:i:s') . ".csv";
    //create a file pointer
    $f = fopen('php://memory', 'w');
    //set column headers
	
    $fields = array(
        'ID',
        'Month',
        'Company Name',
        'Job Category',
        'Job Title',
        'Experience',
        'Job Position',
        'Job Description',
        'End date'
    );

    fputcsv($f, $fields, $delimiter);
    $i = 1;
    
    //output each row of the data, format line as csv and write to file pointer
    while ($row = mysqli_fetch_assoc($query)) {
		$strJobCategory = "";
        if ($row['iJobCategoryId'] != 0) {
			$filterstrCat = mysqli_query($dbconn, "SELECT strJobCategory FROM `jobcategory` where iJobCategoryId='" . $row['iJobCategoryId'] . "' and  isDelete='0'");
			$rowCatData = mysqli_fetch_assoc($filterstrCat);
			$strJobCategory = $rowCatData['strJobCategory'];
		} else {
			$strJobCategory = "-";
		}
		$strEntryDate = date('M-Y', strtotime($row['strEntryDate']));
        $lineData = array(
            $i,
            $strEntryDate,
            $row['strCompanyName'],
            $strJobCategory,
            $row['strJobTitle'],
            $row['strExperience'],
            $row['iPosition'],
            $row['strJobDescription'],
            date('d-m-Y', strtotime($row['strEndDate']))
        );
        
        fputcsv($f, $lineData, $delimiter);
        $i++;
    }
    fseek($f, 0);

    //set headers to download file rather than displayed
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '";');

    //output all remaining data on a file pointer
    fpassthru($f);
} else {
    header('location:ActiveJob.php');
}
exit;
?>
