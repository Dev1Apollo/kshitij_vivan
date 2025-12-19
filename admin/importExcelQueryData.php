<?php

ob_start();
error_reporting(0);
require_once '../common.php';
$connect = new connect();
include 'IsLogin.php';
require_once '../spreadsheet-reader-master/php-excel-reader/excel_reader2.php';
require_once '../spreadsheet-reader-master/SpreadsheetReader.php';

$action = $_REQUEST['action'];

switch ($action) {

    case "ImportExcelData":
        $ValCounter = 0;
        $jCounterArray = 0;
        $iColumnCounter = array();
        $errorString = "";

        //$duplicateMobileTwoCount = 0;

        if (isset($_REQUEST['IMgallery'])) {
            $headerArray = array();
            $filename = trim($_REQUEST['IMgallery']);
            $file_path = 'temp/' . $filename;
            $Reader = new SpreadsheetReader($file_path);
            $Sheets = $Reader->Sheets();

            foreach ($Sheets as $Index => $Name) {
                $Reader->ChangeSheet($Index);
                $col1Value = "";
                foreach ($Reader as $key => $slice) {
                    if ($ValCounter == 0) {
                        for ($icounter = 0; $icounter < count($slice); $icounter ++) {
                            if (trim($slice[$icounter]) != "") {
                                $headerArray[$jCounterArray] = $slice[$icounter];
                                $jCounterArray++;
                                if (trim($slice[$icounter]) == "Title") {
                                   $iColumnCounter[0] = $icounter;
                                }
                                if (trim($slice[$icounter]) == "First Name") {
                                    $iColumnCounter[1] = $icounter;
                                }
                                if (trim($slice[$icounter]) == "Middle Name") {
                                    $iColumnCounter[2] = $icounter;
                                }
                                if (trim($slice[$icounter]) == "Last Name") {
                                   $iColumnCounter[3] = $icounter;
                                }
                                if (trim($slice[$icounter]) == "Mobile No") {
                                    $iColumnCounter[4] = $icounter;
                                }
                                if (trim($slice[$icounter]) == "Email") {
                                    $iColumnCounter[5] = $icounter;
                                }
                                if (trim($slice[$icounter]) == "Company Name") {
                                   $iColumnCounter[6] = $icounter;
                                }
                                if (trim($slice[$icounter]) == "State") {
                                    $iColumnCounter[7] = $icounter;
                                }
                                if (trim($slice[$icounter]) == "City") {
                                    $iColumnCounter[8] = $icounter;
                                }
                                /*if (trim($slice[$icounter]) == "Inquiry Source") {
                                   $iColumnCounter[9] = $icounter;
                                }
                                if (trim($slice[$icounter]) == " Inquiry Status") {
                                    $iColumnCounter[10] = $icounter;
                                }
                                if (trim($slice[$icounter]) == "Inquiry For") {
                                    $iColumnCounter[11] = $icounter;
                                }
                                if (trim($slice[$icounter]) == "Remarks") {
                                   $iColumnCounter[12] = $icounter;
                                }
                                if (trim($slice[$icounter]) == "Category Of Inqiury") {
                                    $iColumnCounter[13] = $icounter;
                                }*/
                            }
                        }
                    } else {
                        $RowCounter = $ValCounter + 1;
                        for ($icounter = 0; $icounter < count($slice); $icounter++) {
                            if ($icounter == 0) {
                                $col1Value = $slice[$icounter];
                            }
                            if ($icounter == $iColumnCounter) {
                                $iColumnCnt = $slice[$icounter];
                            }
                        }
                    }
                    $ValCounter ++;
                }
            }
            $totalcopyrecord = 0;
            if (trim($errorString) == "") {
                $iCounterRow = 0;
                foreach ($Sheets as $Index => $Name) {
                    $Reader->ChangeSheet($Index);
                    if ($Reader != null) {
                        foreach ($Reader as $key => $slice) {
                            if ($iCounterRow > 0) {
                                $RowCounter = $iCounterRow + 1;
                                if ($slice != null || $slice[1] != '' || $slice[4] != '') {
                                		//echo "SELECT * from city where isDelete='0' and name like '". $slice[$iColumnCounter[8]] ."'";
                            		if($slice[4] == "" || $slice[4] == null || empty($slice[4])){
                        				//$errorString = "Mobile Number is blank.";
                        				$errorString .= "Row " . $RowCounter . " & Mobile Number is blank. <br/>";
                        			} else {
	                            		/*$filterCustomer = mysqli_query($dbconn,"select * from customerentry where customerentry.mobileNo = '".$slice[4]."' ");
	                            		if(mysqli_num_rows($filterCustomer) == 0){*/
	                            			
	                            			$filterCity = mysqli_query($dbconn,"SELECT * from city where isDelete='0' and name like '". $slice[8] ."'");
	                                        if (mysqli_num_rows($filterCity) > 0) {
	                                        	$rowCity = mysqli_fetch_array($filterCity);
	                                            $City = $rowCity['cityid'];
	                                            $State = $rowCity['sId'];
	                                        } else {
	                                            $State = 0;
	                                            if(isset($State) || $State != 0){
	                                        	$filterState = mysqli_query($dbconn,"SELECT * from state where isDelete='0' and stateName like '". $slice[7] ."'");
	                                        	if(mysqli_num_rows($filterState) > 0){
	                                        		$rowState = mysqli_fetch_array($filterState);
	                                        		$State = $rowState['stateId'];
	                                        	}
	                                        	$date = date('d-m-Y H:i:s');
	                                        	$strIP = $_SERVER['REMOTE_ADDR'];
	                                        	$insert = mysqli_query($dbconn,"insert into city (`sId`, `name`, `strEntryDate`, `strIP`) VALUES ('".$State."','".$slice[8]."','".$date."','".$strIP."')");
		                                        $City = mysqli_insert_id($dbconn);
		                                        } else {
		                                        	$State = 0;
		                                        }
	                                        }

	                                        
	                                        /*$filterInquriySource=  mysqli_query($dbconn,"select * from inquirysource where isDelete = '0' and inquirySourceName like '". ucfirst($slice[$iColumnCounter[9]])."'");
	                                        if(mysqli_num_rows($filterInquriySource) > 0){
	                                        	$rowInquirySource = mysqli_fetch_array($filterInquriySource);
	                                        	$InquirySource = $rowInquirySource['inquirySourceId'];
	                                        }else{
	                                        	$InquirySource = 0;
	                                        }*/
	                                        /*if(!isset($slice[10]) && $slice[10] == "") {
	                                         	$statusId = "To Be Callback";
	                                     	} else {
	                                     		if($slice[10] == "Walkin" || $slice[10] == "Walk-in" || $slice[10] == "Walk in"){
	                                     			$statusId = "Walk-in";
	                                     		}else if($slice[10] == "Lost"){
	                                     			$statusId = "Lost";
	                                     		}
	                                 		}*/
	                                        /*$filterInquriyStatus=  mysqli_query($dbconn,"select * from `status`  where isDelete='0'  and  istatus='1' and NOT statusId in ('3','4','5') and statusName like '". $statusId ."'");
	                                        if(mysqli_num_rows($filterInquriyStatus) > 0){
	                                        	$rowInquiryStatus = mysqli_fetch_array($filterInquriyStatus);
	                                        	$InquiryStatus = $rowInquiryStatus['statusId'];
	                                        }else{
	                                        	$InquiryStatus = 0;
	                                        }*/

											/*$filtercategoryofinquiry=  mysqli_query($dbconn,"select * from categoryofinquiry where isDelete = '0' and COIname like '". ucfirst($slice[$iColumnCounter[13]])."'");
	                                        if(mysqli_num_rows($filtercategoryofinquiry) > 0){
	                                        	$rowcategoryofinquiry = mysqli_fetch_array($filtercategoryofinquiry);
	                                        	$categoryofinquiry = $rowcategoryofinquiry['id'];
	                                        }else{
	                                        	$categoryofinquiry = 0;
	                                        }*/                                        

	                                        $dataCustomer = array(
	                                            'title' => $slice[0],
	                                            'firstName' => $slice[1],
	                                            'MiddleName' => $slice[2],
	                                            'lastName' => $slice[3],
	                                            'mobileNo' => $slice[4],
	                                            'email' => $slice[5],
	                                            'companyName' => $slice[6],
	                                            'stateId' => $State,
	                                            'cityId' => $City,
	                                            'inquirySourceId' => $_POST['inquirySourceId'],
	                                            'lastInquiryDate' => date('d-m-Y H:i:s'),
	                                            'employeeMasterId' => $_POST['employeeMasterId'],
	                                            'strEntryDate' => date('d-m-Y H:i:s'),
	                                            'strIP' => $_SERVER['REMOTE_ADDR']
	                                        );
	                                        $sql = $connect->insertrecord($dbconn, "customerentry", $dataCustomer);
	                                        $nextFollowupModifyDate="";
									        $walkin_datetime="";
									        $nextFollowupDate= "";
	                                        if($_POST['statusId'] == '2'){
	                                        	$nextFollowupModifyDate = date('d-m-Y H:i:s');
	                                        }
	                                        if($_POST['statusId'] == '6'){
	                                        	$walkin_datetime = date('d-m-Y H:i:s');
	                                        }
	                                        if($_POST['statusId'] == '1'){
	                                        	$nextFollowupDate = date('d-m-Y H:i:s');
	                                        	$nextFollowupModifyDate = date('d-m-Y H:i:s');
	                                        }

	                                        $dataLead = array(
	                                        	'customerEntryId' => $sql,
	                                        	'inquiryfor' => 'Animation',
	                                        	'remarks' => "",
	                                        	'inquiryEnterDate' => date('d-m-Y H:i:s'),
	                                        	'employeeMasterId' => $_POST['employeeMasterId'],
	                                        	'statusId' => $_POST['statusId'],
	                                        	'nextFollowupDate' => $nextFollowupDate,
	                            			 	'nextFollowupModifyDate' => $nextFollowupModifyDate,
	                            			 	'walkin_datetime' => $walkin_datetime,
	                                        	'categoryOfInquiry' => "A",
	                                        	'isNewInquiry' => '0',
	                                        	'strEntryDate' => date('d-m-Y H:i:s'),
	                                        	'strIP' => $_SERVER['REMOTE_ADDR'],
	                                    	);
	                                    	$insertLead = $connect->insertrecord($dbconn, "lead", $dataLead);
	                                    	$employeeMaster = mysqli_fetch_array(mysqli_query($dbconn,"SELECT branchid FROM `employeemaster` where employeeMasterId='". $_POST['employeeMasterId'] ."'"));
	                                    	$branchAbbName = mysqli_fetch_array(mysqli_query($dbconn, "Select * from branchmaster where branchid =" . $employeeMaster['branchid'] . " "));
									        $data_uniqueid = array(
									            'leaduniqueid' => 'KV/' . $branchAbbName['AbbreviationName'] . '/' . $insertLead . '/' . date('m') . '/' . date('Y')
									        );
									        $where = ' where  leadId=' . $insertLead;
									        $dealer_lead_uniqueid = $connect->updaterecord($dbconn, 'lead', $data_uniqueid, $where);

									        $datafollowup = array(
	                        			 		'leadId' => $insertLead,
	                        			 		'customerEntryId' => $sql,
	                        			 		'employeeMasterId' => $_POST['employeeMasterId'],
	                        			 		'nextFollowupDate' => date('d-m-Y H:i:s'),
	                        			 		'statusId' => $_POST['statusId'],
	                        			 		'comment' => "",
	                        			 		'strEntryDate' => date('d-m-Y H:i:s'),
	                        			 		'strIP' => $_SERVER['REMOTE_ADDR'],
	                    			 		);
	                    			 		$insert = $connect->insertrecord($dbconn,'leadfollowup',$datafollowup);

	                    			 		$rowCount = mysqli_fetch_array(mysqli_query($dbconn,"SELECT count(*) as counter FROM `leadfollowup`  where leadId='" . $insertLead . "'"));
	                    			 		
        									$statusCount = $rowCount['counter'];
											$dataLeads = array(
	                            				"statusCount" => $statusCount
	                        			 	);
											$wherelead = "where leadId ='".$insertLead."'";
	                        			 	$updateLeadcount = $connect->updaterecord($dbconn,'lead',$dataLeads,$wherelead);
	                            		/*} else {

	                        			 	$rowCustomer = mysqli_fetch_array($filterCustomer);
	                            			$filterLead = mysqli_fetch_array(mysqli_query($dbconn,"select * from lead where customerEntryId='".$rowCustomer['customerEntryId']."'"));
	                            			
	                            			/*if(trim($slice[10]) == 'Walkin'){
	                            				$walkin = date('d-m-Y H:i:s');
	                            			}else{
	                            				$walkin = "";
	                            			}
	                            			if($slice[10] == "Walkin" || $slice[10] == "Walk-in"){
                                     			$statusId = "Walk-in";
                                     		} else if($slice[10] == "Lost"){
                                     			$statusId = "Lost";
                                     		} else if($slice[10] == "To Be Callback" || $slice[10] == "to be callback"){
                                     			$statusId = "To Be Callback";
                                     		} else{
                                     			$statusId = "To Be Callback";
                                     		}*/
	                                        /*$filterInquriyStatus=  mysqli_query($dbconn,"select * from `status`  where isDelete='0'  and  istatus='1' and NOT statusId in ('3','4','5') and statusName like '". $statusId ."'");
	                                        if(mysqli_num_rows($filterInquriyStatus) > 0){
	                                        	$rowInquiryStatus = mysqli_fetch_array($filterInquriyStatus);
	                                        	$InquiryStatus = $rowInquiryStatus['statusId'];
	                                        }else{
	                                        	$InquiryStatus = 0;
	                                        }*/
	                                        /*$nextFollowupModifyDate="";
									        $walkin_datetime="";
									        $nextFollowupDate= "";
	                                        if($_POST['statusId'] == '2'){
	                                        	$nextFollowupModifyDate = date('d-m-Y H:i:s');
	                                        }
	                                        if($_POST['statusId'] == '6'){
	                                        	$walkin_datetime = date('d-m-Y H:i:s');
	                                        }
	                                        if($_POST['statusId'] == '1'){
	                                        	$nextFollowupDate = date('d-m-Y H:i:s');
	                                        	$nextFollowupModifyDate = date('d-m-Y H:i:s');
	                                        }
	                            			$dataLead = array(
	                            				'statusId' => $_POST['statusId'],
	                            			 	'isNewInquiry' => '0',
	                            			 	'nextFollowupDate' => $nextFollowupDate,
	                            			 	'nextFollowupModifyDate' => $nextFollowupModifyDate,
	                            			 	'walkin_datetime' => $walkin_datetime,
	                            			 	'comment' => "",
	                        			 	);
	                        			 	$where = "where leadId ='".$filterLead['leadId']."'";
	                        			 	$updateLead = $connect->updaterecord($dbconn,'lead',$dataLead,$where);

	                        			 	$datafollowup = array(
	                        			 		'leadId' => $filterLead['leadId'],
	                        			 		'customerEntryId' => $rowCustomer['customerEntryId'],
	                        			 		'employeeMasterId' => $_POST['employeeMasterId'],
	                        			 		'nextFollowupDate' => date('d-m-Y H:i:s'),
	                        			 		'statusId' => $_POST['statusId'],
	                        			 		'comment' => "",
	                        			 		'strEntryDate' => date('d-m-Y H:i:s'),
	                        			 		'strIP' => $_SERVER['REMOTE_ADDR'],
	                    			 		);
	                    			 		$insert = $connect->insertrecord($dbconn,'leadfollowup',$datafollowup);

	                    			 		$rowCount = mysqli_fetch_array(mysqli_query($dbconn,"SELECT count(*) as counter FROM `leadfollowup`  where leadId='" . $filterLead['leadId'] . "'"));
	                    			 		
        									$statusCount = $rowCount['counter'];
											$dataLeads = array(
	                            				"statusCount" => $statusCount
	                        			 	);
											$wherelead = "where leadId ='".$filterLead['leadId']."'";
	                        			 	$updateLeadcount = $connect->updaterecord($dbconn,'lead',$dataLeads,$wherelead);
	                                    }*/
                                	}
                                }
                            }
                            $iCounterRow++;
                        }
                    }
                }
                echo "Data Uploaded Successfully";
                if (trim($errorString) != "") {
                    echo "<br /> Error : " . $errorString;
                }
            }
            unlink($file_path);
        }
        break;   
}
?>