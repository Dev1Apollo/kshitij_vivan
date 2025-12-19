<?php

ob_start();
error_reporting(E_ALL);
require_once('../common.php');
$connect = new connect();
include 'IsLogin.php';
include 'password_hash.php';

$action = $_REQUEST['action'];
switch ($action) {
    case "UserProfileChangePassword":
        $hash_result = create_hash($_POST['oldpassword']);
        $hash_params = explode(":", $hash_result);
        $salt = $hash_params[HASH_SALT_INDEX];
        $hash = $hash_params[HASH_PBKDF2_INDEX];
        $existsmail = "SELECT * FROM employeemaster where employeeMasterId='" . $_SESSION['EmployeeId'] . "'";
        $result = mysqli_query($dbconn, $existsmail);
        $num_rows = mysqli_num_rows($result);
        $row = mysqli_fetch_array($result);

        if ($num_rows >= 1) {
            $good_hash = PBKDF2_HASH_ALGORITHM . ":" . PBKDF2_ITERATIONS . ":" . $row['salt'] . ":" . $row['password'];
            $oldpassword = mysqli_real_escape_string($dbconn, $_REQUEST['oldpassword']);
            if (validate_password($_REQUEST['oldpassword'], $good_hash)) {
                $hash_result = create_hash($_REQUEST['password']);
                $hash_params = explode(":", $hash_result);
                $salt = $hash_params[HASH_SALT_INDEX];
                $hash = $hash_params[HASH_PBKDF2_INDEX];
                $getItems1 = mysqli_query($dbconn, "update employeemaster SET password = '" . $hash . "', salt = '" . $salt . "' where employeeMasterId='" . $_SESSION['EmployeeId'] . "'");
                echo "Sucess";
            } else {
                echo "OldNot";
            }
        } else {
            echo "ID not found";
        }
        break;

    case "AddCustomerEntry":
        $employeeMasterId = 0;
        if($_SESSION['EmployeeType'] == 'Supervisor'){
            $employeeMasterId = isset($_POST['EmployeeId']) ? $_POST['EmployeeId'] : 0;
        } else {
            $employeeMasterId = $_SESSION['EmployeeId'];    
        }
        $data = array(
            "title" => $_POST['Title'],
            "firstName" => ucwords($_POST['FirstName']),
            "MiddleName" => ucwords($_POST['MiddleName']),
            "lastName" => ucwords($_POST['LastName']),
            "mobileNo" => $_POST['Mobile'],
            "email" => $_POST['Email'],
            "companyName" => ucwords($_POST['CompanyName']) ?? '',
            "stateId" => $_POST['State'],
            "cityId" => $_POST['City'],
            "inquirySourceId" => $_POST['InquirySource'],
            "categoryOfCustomer" => $_POST['CategoryOfCustomer'],
            "strEntryDate" => date('d-m-Y H:i:s'),
            "strIP" => $_SERVER['REMOTE_ADDR'],
            "employeeMasterId" => $employeeMasterId
        );
        $customerEntryId = $connect->insertrecord($dbconn, 'customerentry', $data);
        /****************AddCreateInquiry****************/
        if ($_POST['InquiryStatus'] == '6') {
            $walkin_datetime = date('d-m-Y H:i:s');
        } else {
            $walkin_datetime = '';
        }
        $employeeMasterId = 0;
        // if($_SESSION['EmployeeType'] == 'Supervisor'){
        //     //$employeeMasterId = $_POST['EmployeeId'];
        //     $employeeMasterId = isset($_POST['EmployeeId']) ? $_POST['EmployeeId'] : 0;
        // } else {
            //$employeeMasterId = $_SESSION['EmployeeId'];    
        // }
        $data = array(
            "customerEntryId" => $customerEntryId,
            "inquiryfor" => $_POST['InquiryFor'],
            "remarks" => $_POST['Remarks'],
            "walkin_datetime" => $walkin_datetime,
            "inquiryEnterDate" => date('d-m-Y H:i:s'),
            "employeeMasterId" => $employeeMasterId,
            "support_employee" => $_POST['supportEmployeeId'] ?? 0,
            "statusId" => $_POST['InquiryStatus'],
            "categoryOfInquiry" => $_POST['CategoryOfInquiry'],
            "isNewInquiry" => '1',
            "strEntryDate" => date('d-m-Y H:i:s'),
            "strIP" => $_SERVER['REMOTE_ADDR'],
            "month" => date('m'),
            "year" => date('Y')
        );
        $dealer_res = $connect->insertrecord($dbconn, 'lead', $data);
        
        $EmpBranch = mysqli_fetch_array(mysqli_query($dbconn, "Select * from employeemaster where employeeMasterId =" . $employeeMasterId . " "));

        $branchAbbName = mysqli_fetch_array(mysqli_query($dbconn, "Select * from branchmaster where branchid =" . $EmpBranch['branchid'] . " "));
        
        $uniqueNo = mysqli_fetch_array(mysqli_query($dbconn, "Select count(*) as cnt from lead where month =" . date('m') . " and year =" . date('Y') . " and employeeMasterId=".$employeeMasterId." "));
        if($_SESSION['EmployeeType'] == 'Supervisor'){
            $data_uniqueid = array(
                'leaduniqueid' => 'KV/SUV/' . $uniqueNo['cnt'] . '/' . date('m') . '/' . date('Y')
            );
            $where = ' where  leadId=' . $dealer_res;
            $dealer_lead_uniqueid = $connect->updaterecord($dbconn, 'lead', $data_uniqueid, $where);
        } else{
            $data_uniqueid = array(
                'leaduniqueid' => 'KV/' . $branchAbbName['AbbreviationName'] . '/' . $uniqueNo['cnt'] . '/' . date('m') . '/' . date('Y')
            );
            $where = ' where  leadId=' . $dealer_res;
            $dealer_lead_uniqueid = $connect->updaterecord($dbconn, 'lead', $data_uniqueid, $where);
        }
        $rowfilterInq = mysqli_fetch_array(mysqli_query($dbconn, "SELECT Count(*) as count  FROM `lead`  where  customerEntryId='" . $_REQUEST['customerEntryId'] . "'"));
        $noOfInquiry = $rowfilterInq['count'];
        $rowfilterBooked = mysqli_fetch_array(mysqli_query($dbconn, "SELECT Count(*) as count  FROM `lead`  where  customerEntryId='" . $_REQUEST['customerEntryId'] . "' and statusId='3'"));
        $noOfBookedInquiry = $rowfilterBooked['count'];

        $dataI = array(
            "noOfInquiry" => $noOfInquiry,
            "noOfBookedInquiry" => $noOfBookedInquiry,
            "lastInquiryDate" => date('d-m-Y H:i:s')
        );
        $where = ' where  customerEntryId=' . $customerEntryId;
        $dealer_upd = $connect->updaterecord($dbconn, 'customerentry', $dataI, $where);
        
        echo $dealer_res;
        break;

    case "EditCustomerEntry":

        $data = array(
            "title" => $_POST['Title'],
            "firstName" => ucwords($_POST['FirstName']),
            "MiddleName" => ucwords($_POST['MiddleName']),
            "lastName" => ucwords($_POST['LastName']),
            "mobileNo" => $_POST['Mobile'],
            "email" => $_POST['Email'],
            "companyName" => ucwords($_POST['CompanyName']),
            "stateId" => $_POST['State'],
            "cityId" => $_POST['City'],
            "inquirySourceId" => $_POST['InquirySource'],
            "categoryOfCustomer" => $_POST['CategoryOfCustomer'],
            "strEntryDate" => date('d-m-Y H:i:s'),
            "strIP" => $_SERVER['REMOTE_ADDR'],
            "employeeMasterId" => $_SESSION['EmployeeId']
        );
        $where = ' where  customerEntryId=' . $_REQUEST['customerEntryId'];
        $dealer_res = $connect->updaterecord($dbconn, 'customerentry', $data, $where);

        echo $_REQUEST['customerEntryId'];
        break;

    case "AddCreateInquiry":
        if ($_POST['InquiryStatus'] == '6') {
            $walkin_datetime = date('d-m-Y H:i:s');
        } else {
            $walkin_datetime = '';
        }
        $employeeMasterId = 0;
        if($_SESSION['EmployeeType'] == 'Supervisor'){
            //$employeeMasterId = $_POST['EmployeeId'];
            $employeeMasterId = isset($_POST['EmployeeId']) ? $_POST['EmployeeId'] : 0;
        } else {
            $employeeMasterId = $_SESSION['EmployeeId'];    
        }
        $data = array(
            "customerEntryId" => $_POST['customerEntryId'],
            "inquiryfor" => $_POST['InquiryFor'],
            "remarks" => $_POST['Remarks'],
            "walkin_datetime" => $walkin_datetime,
            "inquiryEnterDate" => date('d-m-Y H:i:s'),
            "employeeMasterId" => $employeeMasterId,
            "support_employee" => $_POST['supportEmployeeId'],
            "statusId" => $_POST['InquiryStatus'],
            "categoryOfInquiry" => $_POST['CategoryOfInquiry'],
            "isNewInquiry" => '1',
            "strEntryDate" => date('d-m-Y H:i:s'),
            "strIP" => $_SERVER['REMOTE_ADDR'],
            "month" => date('m'),
            "year" => date('Y')
        );
        $dealer_res = $connect->insertrecord($dbconn, 'lead', $data);
        
        $EmpBranch = mysqli_fetch_array(mysqli_query($dbconn, "Select * from employeemaster where employeeMasterId =" . $employeeMasterId . " "));

        $branchAbbName = mysqli_fetch_array(mysqli_query($dbconn, "Select * from branchmaster where branchid =" . $EmpBranch['branchid'] . " "));
        
        $uniqueNo = mysqli_fetch_array(mysqli_query($dbconn, "Select count(*) as cnt from lead where month =" . date('m') . " and year =" . date('Y') . " and employeeMasterId=".$employeeMasterId." "));
        if($_SESSION['EmployeeType'] == 'Supervisor'){
            $data_uniqueid = array(
                'leaduniqueid' => 'KV/SUV/' . $uniqueNo['cnt'] . '/' . date('m') . '/' . date('Y')
            );
            $where = ' where  leadId=' . $dealer_res;
            $dealer_lead_uniqueid = $connect->updaterecord($dbconn, 'lead', $data_uniqueid, $where);
        } else{
            $data_uniqueid = array(
                'leaduniqueid' => 'KV/' . $branchAbbName['AbbreviationName'] . '/' . $uniqueNo['cnt'] . '/' . date('m') . '/' . date('Y')
            );
            $where = ' where  leadId=' . $dealer_res;
            $dealer_lead_uniqueid = $connect->updaterecord($dbconn, 'lead', $data_uniqueid, $where);
        }
        $rowfilterInq = mysqli_fetch_array(mysqli_query($dbconn, "SELECT Count(*) as count  FROM `lead`  where  customerEntryId='" . $_REQUEST['customerEntryId'] . "'"));
        $noOfInquiry = $rowfilterInq['count'];
        $rowfilterBooked = mysqli_fetch_array(mysqli_query($dbconn, "SELECT Count(*) as count  FROM `lead`  where  customerEntryId='" . $_REQUEST['customerEntryId'] . "' and statusId='3'"));
        $noOfBookedInquiry = $rowfilterBooked['count'];

        $dataI = array(
            "noOfInquiry" => $noOfInquiry,
            "noOfBookedInquiry" => $noOfBookedInquiry,
            "lastInquiryDate" => date('d-m-Y H:i:s')
        );
        $where = ' where  customerEntryId=' . $_REQUEST['customerEntryId'];
        $dealer_upd = $connect->updaterecord($dbconn, 'customerentry', $dataI, $where);

        echo $dealer_res;
        break;

    case "AddInquiryFollowUp":
        
        if (isset($_POST['booking_amount'])) {
            $_POST['booking_amount'] = $_POST['booking_amount'];
        } else {
            $_POST['booking_amount'] = '';
        }
        $employeeMasterId = 0;
        // if($_SESSION['EmployeeType'] == 'Supervisor'){
        //     $employeeMasterId = $_POST['EmployeeId'];
        // } else {
        //     $employeeMasterId = $_SESSION['EmployeeId'];    
        // }
        if($_SESSION['EmployeeType'] == 'Supervisor'){
            if(isset($_POST['EmployeeId']) && $_POST['EmployeeId'] != ""){
                $employeeMasterId = $_POST['EmployeeId'];
            }
        } else {
            $employeeMasterId = $_SESSION['EmployeeId'];
        }
        $walkinby = 0;
        $bookedby = 0;
        $callBackBy = 0;
        $Counselling = 0;
        if($_POST['InquiryStatus'] == 1)
        {
            $walkinby = 0;
            $bookedby = 0;
            $callBackBy = $_POST['supportEmployeeId'];
            $Counselling = 0;
        } else if ($_POST['InquiryStatus'] == 6 || $_POST['InquiryStatus'] == 7){
            if((isset($_POST['supportEmployeeId']) && $_POST['supportEmployeeId'] != "") && (isset($_POST['transferToEmpId']) && $_POST['transferToEmpId'] != "")){
                $walkinby = $_POST['supportEmployeeId'];
                $bookedby = 0;
                $callBackBy = $_POST['supportEmployeeId'];
                $Counselling = $_POST['transferToEmpId'];
            } else {
                $walkinby = 0;
                $bookedby = 0;
                $callBackBy = 0;
                $Counselling = $_POST['supportEmployeeId'];
            }
        } else if($_POST['InquiryStatus'] == 2){
            $walkinby = 0;
            $bookedby = 0;
            $callBackBy = $_POST['supportEmployeeId'];
            $Counselling = 0;
        } else if($_POST['InquiryStatus'] == 3) {
            $walkinby = 0;
            $bookedby = $_POST['supportEmployeeId'];
            $callBackBy = 0;
            $Counselling = 0;
        } else {
            $walkinby = 0;
            $bookedby = 0;
            $callBackBy = $_POST['supportEmployeeId'];
            $Counselling = 0;
        }
        $data = array(
            "leadId" => $_POST['leadId'],
            "customerEntryId" => $_POST['customerEntryId'],
            "employeeMasterId" => isset($_POST['EmployeeId']) && $_POST['EmployeeId'] != "" ? $_POST['EmployeeId'] : 0,
            "nextFollowupDate" => $_POST['Date'],
            "statusId" => $_POST['InquiryStatus'],
            "comment" => $_POST['comment'],
            "support_employee" => $callBackBy,
            "transfer_to" => $Counselling,
            "walkinby" => $walkinby,
            "bookedby" => $bookedby,
            "strEntryDate" => date('d-m-Y H:i:s'),
            "strIP" => $_SERVER['REMOTE_ADDR']
        );
        $dealer_res = $connect->insertrecord($dbconn, 'leadfollowup', $data);

        $rowfilterLF = mysqli_fetch_array(mysqli_query($dbconn, "SELECT count(*) as count FROM `leadfollowup`  where leadId='" . $_POST['leadId'] . "'"));
        $statusCount = $rowfilterLF['count'];
        $rowfilterinquirystatus = mysqli_fetch_array(mysqli_query($dbconn, "SELECT * FROM `lead`  where leadId='" . $_POST['leadId'] . "'"));
        if ($_POST['InquiryStatus'] == '6' && $rowfilterinquirystatus['walkin_datetime'] == '') {
            $walkindate = date('d-m-Y H:i:s');
        } else {
            $walkindate = $rowfilterinquirystatus['walkin_datetime'];
        }

        $dataLead = array(
            "statusId" => $_POST['InquiryStatus'],
            "isNewInquiry" => '0',
            "nextFollowupDate" => $_POST['Date'],
            "nextFollowupModifyDate" => date('d-m-Y H:i:s'),
            "employeeMasterId" => isset($_POST['EmployeeId']) && $_POST['EmployeeId'] != "" ? $_POST['EmployeeId'] : 0,
            "walkin_datetime" => $walkindate,
            "comment" => $_POST['comment'],
            "booking_amount" => $_POST['booking_amount'],
            "support_employee" => $callBackBy,
            "transfer_to" => $Counselling,
            "walkinby" => $walkinby,
            "bookedby" => $bookedby,
            "statusCount" => $statusCount,
        );
        $where = ' where  leadId=' . $_REQUEST['leadId'];
        $update = $connect->updaterecord($dbconn, 'lead', $dataLead, $where);

        $rowfilterBooked = mysqli_fetch_array(mysqli_query($dbconn, "SELECT Count(*) as count  FROM `lead`  where  customerEntryId='" . $_REQUEST['customerEntryId'] . "' and statusId='3'"));
        $noOfBookedInquiry = $rowfilterBooked['count'];

        $dataI = array(
            "employeeMasterId" => isset($_POST['EmployeeId']) && $_POST['EmployeeId'] != "" ? $_POST['EmployeeId'] : 0,
            "noOfBookedInquiry" => $noOfBookedInquiry,
            "lastInquiryDate" => date('d-m-Y H:i:s')
        );
        $where = ' where  customerEntryId=' . $_REQUEST['customerEntryId'];
        $dealer_upd = $connect->updaterecord($dbconn, 'customerentry', $dataI, $where);

        echo $dealer_res;
        break;

    case "AddTransferInquiry":

        $CheckList = $_POST['check_list'];
        foreach ($CheckList as $key => $value) {
            $dataLead = array(
                "employeeMasterId" => $_POST['TransferEmployee'],
                "employeeConverted" => '1',
            );
            $where = ' where leadId=' . trim($value);
            $update = $connect->updaterecord($dbconn, 'lead', $dataLead, $where);

            $data = array(
                "leadId" => trim($value),
                "employeeMasterId" => $_SESSION['EmployeeId'],
                "transferEmployeeId" => $_POST['TransferEmployee'],
                "transferDate" => date('d-m-Y H:i:s'),
                "strIP" => $_SERVER['REMOTE_ADDR']
            );
            $dealer_res = $connect->insertrecord($dbconn, 'transferinquiry', $data);
        }
        break;

    case "AddRegistredStudent":
        $isRegister = '1';
        $isAdmission = '0';

        $data = array(
            "leaduniqueid" => $_POST['leaduniqueid'],
            "leadId" => $_POST['leadId'],
            "customerEntryId" => $_POST['customerEntryId'],
            "title" => ucwords($_POST['Title']),
            "firstName" => ucwords($_POST['firstName']),
            "middleName" => ucwords($_POST['middleName']),
            "surName" => ucwords($_POST['surName']),
            "DOB" => $_POST['DOB'],
            "gender" => ucwords($_POST['Gender']),
            "address" => ucwords($_POST['address']),
            "addresstwo" => ucwords($_POST['addresstwo']),
            "state" => $_POST['state'],
            "city" => ucwords($_POST['city']),
            "pincode" => $_POST['pincode'],
            "email" => ucwords($_POST['email']),
            "phone" => $_POST['phone'],
            "mobileOne" => $_POST['mobileOne'],
            "mobileTwo" => $_POST['mobileTwo'],
            "occupation" => ucwords($_POST['occupation']),
            "qualification" => ucwords($_POST['qualification']),
            "designation" => ucwords($_POST['designation']),
            "studentPortal_Id" => $_POST['studentPortal_Id'],
            "branchId" => $_POST['branchId'], //$_SESSION['branchid'],
            "strEntryDate" => date('d-m-Y H:i:s '),
            "strIP" => $_SERVER['REMOTE_ADDR'],
            "isRegister" => $isRegister,
            "isAdmission" => $isAdmission,
            "employeeMasterId" => $_SESSION['EmployeeId']
        );
        $dealer_res = $connect->insertrecord($dbconn, 'studentadmission', $data);

        $dataLead = array(
            "isRegister" => '1'
        );
        $where = ' where  leadId=' . $_POST['leadId'];
        $dealer_resLead = $connect->updaterecord($dbconn, 'lead', $dataLead, $where);

        echo $dealer_res;
        break;
            
    case "EditRegisteredStudent":
        $data = array(
            "title" => ucwords($_POST['Title']),
            "firstName" => ucwords($_POST['firstName']),
            "middleName" => ucwords($_POST['middleName']),
            "surName" => ucwords($_POST['surName']),
            "DOB" => $_POST['DOB'],
            "gender" => ucwords($_POST['Gender']),
            "address" => ucwords($_POST['address']),
            "addresstwo" => ucwords($_POST['addresstwo']),
            "state" => $_POST['state'],
            "city" => ucwords($_POST['city']),
            "pincode" => ucwords($_POST['pincode']),
            "email" => ucwords($_POST['email']),
            "phone" => $_POST['phone'],
            "mobileOne" => $_POST['mobileOne'],
            "mobileTwo" => $_POST['mobileTwo'],
            "occupation" => ucwords($_POST['occupation']),
            "qualification" => ucwords($_POST['qualification']),
            "designation" => ucwords($_POST['designation']),
            "studentPortal_Id" => $_POST['studentPortal_Id'],
            "strIP" => $_SERVER['REMOTE_ADDR'],
            "employeeMasterId" => $_SESSION['EmployeeId']
        );
        $where = ' where  stud_id=' . $_REQUEST['stud_id'];
        $dealer_res = $connect->updaterecord($dbconn, 'studentadmission', $data, $where);

        echo $_REQUEST['stud_id'];
        break;

    case "AddStudentEntry":

        $data = array(
            "leadId" => $_POST['leadId'],
            "customerEntryId" => $_POST['customerEntryId'],
            "title" => ucwords($_POST['Title']),
            "firstName" => ucwords($_POST['firstName']),
            "middleName" => ucwords($_POST['middleName']),
            "surName" => ucwords($_POST['surName']),
            "DOB" => $_POST['DOB'],
            "gender" => ucwords($_POST['Gender']),
            "address" => ucwords($_POST['address']),
            "city" => ucwords($_POST['city']),
            "pincode" => ucwords($_POST['pincode']),
            "email" => ucwords($_POST['email']),
            "phone" => $_POST['phone'],
            "mobileOne" => $_POST['mobileOne'],
            "mobileTwo" => $_POST['mobileTwo'],
            "occupation" => ucwords($_POST['occupation']),
            "qualification" => ucwords($_POST['qualification']),
            "designation" => ucwords($_POST['designation']),
            "branchId" => $_POST['branchId'],
            "studentPortal_Id" => $_POST['studentPortal_Id'],
            "strEntryDate" => date('d-m-Y H:i:s '),
            "strIP" => $_SERVER['REMOTE_ADDR'],
            "isRegister" => 1,
            "isAdmission" => 1,
            "employeeMasterId" => $_SESSION['EmployeeId']
        );
        $dealer_res = $connect->insertrecord($dbconn, 'studentadmission', $data);

        echo $dealer_res;
        break;

    case "EditStudentEntry":

        $data = array(
            "title" => ucwords($_POST['title']),
            "firstName" => ucwords($_POST['firstName']),
            "middleName" => ucwords($_POST['middleName']),
            "surName" => ucwords($_POST['surName']),
            "DOB" => $_POST['DOB'],
            "gender" => ucwords($_POST['Gender']),
            "address" => ucwords($_POST['address']),
            "city" => ucwords($_POST['city']),
            "pincode" => $_POST['pincode'],
            "email" => $_POST['email'],
            "phone" => $_POST['phone'],
            "mobileOne" => $_POST['mobileOne'],
            "mobileTwo" => $_POST['mobileTwo'],
            "occupation" => ucwords($_POST['occupation']),
            "qualification" => ucwords($_POST['qualification']),
            "designation" => ucwords($_POST['designation']),
            "branchId" => $_POST['branchId'],
            "studentPortal_Id" => $_POST['studentPortal_Id'],
            "strEntryDate" => date('d-m-Y H:i:s'),
            "strIP" => $_SERVER['REMOTE_ADDR'],
            "employeeMasterId" => $_SESSION['EmployeeId']
        );
        $where = ' where  stud_id=' . $_REQUEST['stud_id'];
        $dealer_res = $connect->updaterecord($dbconn, 'studentadmission', $data, $where);

        echo $_REQUEST['stud_id'];
        break;

    case "AddStudentCourse":

        $findMaxBookid = mysqli_fetch_array(mysqli_query($dbconn, "SELECT MAX(bookId) as bookId FROM studentcourse where branchId = " . $_POST['branchid'] . " "));
        $branchAbbName = mysqli_fetch_array(mysqli_query($dbconn, "Select * from branchmaster where branchid =" . $_POST['branchid'] . " "));

        if ($_POST['iEnroll'] == 2) {
            $bookId = $findMaxBookid['bookId'] + 1;
            $bookingId = 'BC/' . $branchAbbName['AbbreviationName'] . '/' . $bookId;
        } else {
            $bookId = "";
            $bookingId = "";
        }
        if ($_POST['emiId'] == 1) {
            $noOfEmi = 1;
            $emiStartDate = date('d-m-Y');
        } else {
            $noOfEmi = $_POST['noOfEmi'];
            $emiStartDate = $_POST['emiStartDate'];
        }
        $cid = implode(',', $_POST['cid']);
        $data = array(
            "bookId" => $bookId,
            "bookingId" => $bookingId,
            "iPortal" => $_POST['iEnroll'],
            "courseId" => $cid,
            "stud_id" => $_POST['stud_id'],
            "fee" => $_POST['fee'],
            "offeredfee" => $_POST['offeredfee'],
            "dateOfJoining" => $_POST['dateOfJoining'],
            "EnrollmentDate" => $_POST['dateOfEnrollment'],
            "emiType" => $_POST['emiId'],
            "noOfEmi" => $noOfEmi,
            "emiAmount" => $_POST['emiAmount'],
            "emiStartDate" => $emiStartDate,
            "booking_amount" => $_POST['registeredAmount'],
            "studentStatus" => 1,
            "branchId" => $_POST['branchid'],
            "strIP" => $_SERVER['REMOTE_ADDR']
        );
        $dealer_res = $connect->insertrecord($dbconn, 'studentcourse', $data);

        $findMaxstudId = mysqli_fetch_array(mysqli_query($dbconn, "SELECT MAX(EnrollmentId) as EnrollmentId FROM studentadmission where branchId = " . $_POST['branchid'] . " and studentPortal_Id=" . $_POST['iEnroll'] . " "));
        $studentEnroll = mysqli_fetch_array(mysqli_query($dbconn, "SELECT studentEnrollment FROM studentadmission where stud_id=" . $_POST['stud_id'] . " "));

        if ($_POST['iEnroll'] == 1) {
            $studentEnrollment = $_POST['studentEnrollment'];
            if ($studentEnroll['studentEnrollment'] == '' || $studentEnroll['studentEnrollment'] == null) {
                $EnrollmentId = $findMaxstudId['EnrollmentId'] + 1;
            } else {
                $EnrollmentId = $findMaxstudId['EnrollmentId'];
            }
        } else if ($_POST['iEnroll'] == 4) {
            $studentEnrollment = $_POST['studentEnrollment'];
            if ($studentEnroll['studentEnrollment'] == '' || $studentEnroll['studentEnrollment'] == null) {
                $EnrollmentId = $findMaxstudId['EnrollmentId'] + 1;
            } else {
                $EnrollmentId = $findMaxstudId['EnrollmentId'];
            }
        } else if ($_POST['iEnroll'] == 2) {
            if ($studentEnroll['studentEnrollment'] == '' || $studentEnroll['studentEnrollment'] == null) {
                $EnrollmentId = $findMaxstudId['EnrollmentId'] + 1;
                $branchAbbName = mysqli_fetch_array(mysqli_query($dbconn, "Select * from branchmaster where branchid =" . $_POST['branchid'] . " "));
                $studentEnrollment = 'EN/' . $branchAbbName['AbbreviationName'] . '/' . $EnrollmentId;
            } else {
                $EnrollmentId = $findMaxstudId['EnrollmentId'];
                $studentEnrollment = $studentEnroll['studentEnrollment'];
            }
        } else {
            $studentEnrollment = "";
            if ($studentEnroll['studentEnrollment'] == '' || $studentEnroll['studentEnrollment'] == null) {
                $EnrollmentId = $findMaxstudId['EnrollmentId'] + 1;
            } else {
                $EnrollmentId = $findMaxstudId['EnrollmentId'];
            }
        }

//        if (isset($_POST['dateOfRegistration'])) {
        $dateOfRegistration = $_POST['dateOfRegistration'];
        $data_uniqueid = array(
            "isAdmission" => 1,
            "iStudentStatus" => 1,
            "EnrollmentId" => $EnrollmentId,
            "studentEnrollment" => $studentEnrollment,
            "strEntryDate" => $dateOfRegistration
        );
        $where = "where stud_id =" . $_POST['stud_id'];
        $dealer_reg = $connect->updaterecord($dbconn, 'studentadmission', $data_uniqueid, $where);
//        } else {
//            $data_uniqueid = array(
//                "isAdmission" => 1,
//                "iStudentStatus" => 1,
//                "EnrollmentId" => $EnrollmentId,
//                "studentEnrollment" => $studentEnrollment
//            );
//            $where = "where stud_id =" . $_POST['stud_id'];
//            $dealer_reg = $connect->updaterecord($dbconn, 'studentadmission', $data_uniqueid, $where);
//        }
//        $query = mysqli_query($dbconn, "select * from studentcourse where courseId in (" . $cid . ") and stud_id =" . $_POST['stud_id'] . "");
//        $row = mysqli_fetch_array($query);

        $noOfEmi = $_POST['noOfEmi'];
        $emiStartDate = strtotime($_POST['emiStartDate']);
        $type = $_POST['emiId'];
        if ($type == 1) {
            $totalOfEmiAmt = $_POST['offeredfee'] - $_POST['joinAmount'];
            $dataEmi = array(
                "studentcourseId" => $dealer_res,
                "stud_id" => ucwords($_POST['stud_id']),
                "emiAmount" => $_POST['joinAmount'],
                "totalOfEmiAmt" => $_POST['joinAmount'],
                "joinAmount" => $_POST['joinAmount'],
                "booking_amount" => $_POST['registeredAmount'],
                "emiDate" => $_POST['dateOfJoining'],
                "strIP" => $_SERVER['REMOTE_ADDR']
            );
            $dealer_res2 = $connect->insertrecord($dbconn, 'studentemidetail', $dataEmi);
        }
        if ($type == 2) {
            $totalOfEmiAmt = $_POST['emiAmount'];
            $i = 0;
            if ($i == 0) {
                $dataemi = array(
                    "studentcourseId" => $dealer_res,
                    "stud_id" => ucwords($_POST['stud_id']),
                    "emiAmount" => $_POST['joinAmount'],
                    "totalOfEmiAmt" => $_POST['joinAmount'],
                    "joinAmount" => $_POST['joinAmount'],
                    "booking_amount" => $_POST['registeredAmount'],
                    "emiDate" => $_POST['dateOfJoining'],
                    "strIP" => $_SERVER['REMOTE_ADDR']
                );
                $dealer_res2 = $connect->insertrecord($dbconn, 'studentemidetail', $dataemi);
            }
            if ($noOfEmi == 1) {
                $dataEmi = array(
                    "studentcourseId" => $dealer_res,
                    "stud_id" => ucwords($_POST['stud_id']),
                    "emiAmount" => $_POST['emiAmount'],
                    "totalOfEmiAmt" => $totalOfEmiAmt,
                    "joinAmount" => $_POST['joinAmount'],
                    "booking_amount" => $_POST['registeredAmount'],
                    "emiDate" => $_POST['emiStartDate'],
                    "strIP" => $_SERVER['REMOTE_ADDR']
                );
                $dealer_res2 = $connect->insertrecord($dbconn, 'studentemidetail', $dataEmi);
            }
            for ($i = 1; $i < $noOfEmi; $i++) {
                if ($i == 1) {
                    $dataEmi = array(
                        "studentcourseId" => $dealer_res,
                        "stud_id" => ucwords($_POST['stud_id']),
                        "emiAmount" => $_POST['emiAmount'],
                        "totalOfEmiAmt" => $totalOfEmiAmt,
                        "joinAmount" => $_POST['joinAmount'],
                        "booking_amount" => $_POST['registeredAmount'],
                        "emiDate" => $_POST['emiStartDate'],
                        "strIP" => $_SERVER['REMOTE_ADDR']
                    );
                    $dealer_res2 = $connect->insertrecord($dbconn, 'studentemidetail', $dataEmi);
                }
                $emiDate = date('d-m-Y', strtotime("+$i month", $emiStartDate));
                $totalOfEmiAmt = $totalOfEmiAmt + $_POST['emiAmount'];

                $dataEmi = array(
                    "studentcourseId" => $dealer_res,
                    "stud_id" => ucwords($_POST['stud_id']),
                    "emiAmount" => $_POST['emiAmount'],
                    "totalOfEmiAmt" => $totalOfEmiAmt,
                    "joinAmount" => $_POST['joinAmount'],
                    "booking_amount" => $_POST['registeredAmount'],
                    "emiDate" => $emiDate,
                    "strIP" => $_SERVER['REMOTE_ADDR']
                );
                $dealer_res2 = $connect->insertrecord($dbconn, 'studentemidetail', $dataEmi);
            }
        }
        if ($type == 3) {
            $i = 0;
            $noOfEmi = $noOfEmi * 3;
            $totalOfEmiAmt = $_POST['emiAmount'];
            if ($i == 0) {
                $dataemi = array(
                    "studentcourseId" => $dealer_res,
                    "stud_id" => ucwords($_POST['stud_id']),
                    "emiAmount" => $_POST['joinAmount'],
                    "totalOfEmiAmt" => $_POST['joinAmount'],
                    "joinAmount" => $_POST['joinAmount'],
                    "booking_amount" => $_POST['registeredAmount'],
                    "emiDate" => $_POST['dateOfJoining'],
                    "strIP" => $_SERVER['REMOTE_ADDR']
                );
                $dealer_res2 = $connect->insertrecord($dbconn, 'studentemidetail', $dataemi);
            }
            for ($i = 1; $i < $noOfEmi; $i++) {
                if ($i == 1) {
                    $dataEmi = array(
                        "studentcourseId" => $dealer_res,
                        "stud_id" => ucwords($_POST['stud_id']),
                        "emiAmount" => $_POST['emiAmount'],
                        "totalOfEmiAmt" => $totalOfEmiAmt,
                        "joinAmount" => $_POST['joinAmount'],
                        "booking_amount" => $_POST['registeredAmount'],
                        "emiDate" => $_POST['emiStartDate'],
                        "strIP" => $_SERVER['REMOTE_ADDR']
                    );
                    $dealer_res2 = $connect->insertrecord($dbconn, 'studentemidetail', $dataEmi);
                } else {
                    $emiDate = date('d-m-Y', strtotime("+$i month", $emiStartDate));
                    $totalOfEmiAmt = $totalOfEmiAmt + $_POST['emiAmount'];

                    $Emidata = array(
                        "studentcourseId" => $dealer_res,
                        "stud_id" => ucwords($_POST['stud_id']),
                        "emiAmount" => $_POST['emiAmount'],
                        "totalOfEmiAmt" => $totalOfEmiAmt,
                        "joinAmount" => $_POST['joinAmount'],
                        "booking_amount" => $_POST['registeredAmount'],
                        "emiDate" => $emiDate,
                        "strIP" => $_SERVER['REMOTE_ADDR']
                    );
                    $dealer_res2 = $connect->insertrecord($dbconn, 'studentemidetail', $Emidata);
                }
                $i = $i + 2;
            }
        }

        $sqlquery = mysqli_query($dbconn, "SELECT * FROM studentcourse where stud_id='" . $_POST['stud_id'] . "' and istatus=1 and courseId in ('" . $cid . "')");
        while ($sqlres = mysqli_fetch_array($sqlquery)) {
            $dataSoftware = array(
                "courseId" => $cid,
                "stud_id" => $_POST['stud_id'],
                "studentcourseId" => $dealer_res,
                "strIP" => $_SERVER['REMOTE_ADDR']
            );
            $dealer_res1 = $connect->insertrecord($dbconn, 'studentcoursedetail', $dataSoftware);
        }

        $filetrStudId = "SELECT * from studentfee where stud_id ='" . $_POST['stud_id'] . "' and istatus=1 and studentcourseId = '0'";
        $rowstudId = mysqli_query($dbconn, $filetrStudId);
        while ($dataStudId = mysqli_fetch_array($rowstudId)) {
            $dataCourse = array(
                "studentcourseId" => $dealer_res
            );
            $where = ' where  stud_id=' . $_POST['stud_id'];
            $dealer_result = $connect->updaterecord($dbconn, 'studentfee', $dataCourse, $where);
        }

        echo $_REQUEST['stud_id'];
        break;

//    case "EditStudentCourse":
//        $cid = implode(',', $_POST['cid']);
//        $data = array(
//            "courseId" => $cid,
//            "offeredfee" => $_POST['offeredfee'],
//            "dateOfJoining" => $_POST['dateOfJoining'],
//            "emiType" => $_POST['emiId'],
//            "noOfEmi" => $_POST['noOfEmi'],
//            "emiAmount" => $_POST['emiAmount'],
//            "emiStartDate" => $_POST['emiStartDate'],
//            "booking_amount" => $_POST['registeredAmount'],
//            "studentStatus" => 1,
//            "lastPaymentDate" => date('d-m-Y H:i:s'),
//            "strIP" => $_SERVER['REMOTE_ADDR']
//        );
//        $where = ' where  studentcourseId=' . $_POST['studentcourseId'];
//        $dealer_res = $connect->updaterecord($dbconn,'studentcourse', $data, $where);
//
//        $sql = mysqli_query($dbconn,"select * from studentemidetail where stud_id='" . $_POST['stud_id'] . "' and studentcourseId ='" . $_POST['studentcourseId'] . "'");
//        if (mysqli_num_rows($sql) > 0) {
//            $q = mysqli_query($dbconn,"delete from studentemidetail where stud_id='" . $_POST['stud_id'] . "' and studentcourseId ='" . $_POST['studentcourseId'] . "'");
//        }
//        $noOfEmi = $_POST['noOfEmi'];
//        $emiStartDate = strtotime($_POST['emiStartDate']);
//
//        $type = $_POST['emiId'];
//        if ($type == 1) {
//            $totalOfEmiAmt = $_POST['offeredfee'] + $_POST['joinAmount'];
//            $totalOfEmiAmt = $_POST['offeredfee'] - $_POST['joinAmount'];
//            $dataEmi = array(
//                "studentcourseId" => $row['studentcourseId'],
//                "stud_id" => ucwords($_POST['stud_id']),
//                "emiAmount" => $_POST['emiAmount'],
//                "totalOfEmiAmt" => $_POST['joinAmount'],
//                "joinAmount" => $_POST['joinAmount'],
//                "booking_amount" => $_POST['registeredAmount'],
//                "emiDate" => $_POST['dateOfJoining'],
//                "strIP" => $_SERVER['REMOTE_ADDR']
//            );
//            $dealer_res2 = $connect->insertrecord($dbconn,'studentemidetail', $dataEmi);
//            $dataEmi = array(
//                "studentcourseId" => $row['studentcourseId'],
//                "stud_id" => ucwords($_POST['stud_id']),
//                "emiAmount" => $_POST['emiAmount'],
//                "totalOfEmiAmt" => $totalOfEmiAmt,
//                "joinAmount" => $_POST['joinAmount'],
//                "booking_amount" => $_POST['registeredAmount'],
//                "emiDate" => $_POST['emiStartDate'],
//                "strIP" => $_SERVER['REMOTE_ADDR']
//            );
//            $dealer_r = $connect->insertrecord($dbconn,'studentemidetail', $dataEmi);
//        }
//        if ($type == 2) {
//            $totalOfEmiAmt = 0;
//            $noOfEmi = $noOfEmi;
//
//            $i = 0;
//            if ($i == 0) {
//                $dataemi = array(
//                    "studentcourseId" => $row['studentcourseId'],
//                    "stud_id" => ucwords($_POST['stud_id']),
//                    "emiAmount" => $_POST['joinAmount'],
//                    "totalOfEmiAmt" => $_POST['joinAmount'],
//                    "joinAmount" => $_POST['joinAmount'],
//                    "booking_amount" => $_POST['registeredAmount'],
//                    "emiDate" => $_POST['dateOfJoining'],
//                    "strIP" => $_SERVER['REMOTE_ADDR']
//                );
//                $dealer_res2 = $connect->insertrecord($dbconn,'studentemidetail', $dataemi);
//            }
//            for ($i = 1; $i < $noOfEmi; $i++) {
//                if ($i == 1) {
//                    $dataEmi = array(
//                        "studentcourseId" => $row['studentcourseId'],
//                        "stud_id" => ucwords($_POST['stud_id']),
//                        "emiAmount" => $_POST['emiAmount'],
//                        "totalOfEmiAmt" => $totalOfEmiAmt,
//                        "joinAmount" => $_POST['joinAmount'],
//                        "booking_amount" => $_POST['registeredAmount'],
//                        "emiDate" => $_POST['emiStartDate'],
//                        "strIP" => $_SERVER['REMOTE_ADDR']
//                    );
//                    $dealer_res2 = $connect->insertrecord($dbconn,'studentemidetail', $dataEmi);
//                }
//                $emiDate = date('d-m-Y', strtotime("+$i month", $emiStartDate));
//                $totalOfEmiAmt = $totalOfEmiAmt + $_POST['emiAmount'];
//
//                $dataEmi = array(
//                    "studentcourseId" => $row['studentcourseId'],
//                    "stud_id" => ucwords($_POST['stud_id']),
//                    "emiAmount" => $_POST['emiAmount'],
//                    "totalOfEmiAmt" => $totalOfEmiAmt,
//                    "joinAmount" => $_POST['joinAmount'],
//                    "booking_amount" => $_POST['registeredAmount'],
//                    "emiDate" => $emiDate,
//                    "strIP" => $_SERVER['REMOTE_ADDR']
//                );
//                $dealer_res2 = $connect->insertrecord($dbconn,'studentemidetail', $dataEmi);
//            }
//        }
//
//        if ($type == 3) {
//            $noOfEmi = $noOfEmi * 3;
//            $totalOfEmiAmt = 0;
//            $i = 0;
//            if ($i == 0) {
//                $dataemi = array(
//                    "studentcourseId" => $_POST['studentcourseId'],
//                    "stud_id" => ucwords($_POST['stud_id']),
//                    "emiAmount" => $_POST['joinAmount'],
//                    "totalOfEmiAmt" => $_POST['joinAmount'],
//                    "joinAmount" => $_POST['joinAmount'],
//                    "booking_amount" => $_POST['registeredAmount'],
//                    "emiDate" => $_POST['dateOfJoining'],
//                    "strIP" => $_SERVER['REMOTE_ADDR']
//                );
//                $dealer_res2 = $connect->insertrecord($dbconn,'studentemidetail', $dataemi);
//            }
//            for ($i = 1; $i < $noOfEmi; $i++) {
//                if ($i == 1) {
//                    $dataEmi = array(
//                        "studentcourseId" => $_POST['studentcourseId'],
//                        "stud_id" => ucwords($_POST['stud_id']),
//                        "emiAmount" => $_POST['emiAmount'],
//                        "totalOfEmiAmt" => $totalOfEmiAmt,
//                        "joinAmount" => $_POST['joinAmount'],
//                        "booking_amount" => $_POST['registeredAmount'],
//                        "emiDate" => $_POST['emiStartDate'],
//                        "strIP" => $_SERVER['REMOTE_ADDR']
//                    );
//                    $dealer_res2 = $connect->insertrecord($dbconn,'studentemidetail', $dataEmi);
//                } else {
//                    $emiDate = date('d-m-Y', strtotime("+$i month", $emiStartDate));
//                    $totalOfEmiAmt = $totalOfEmiAmt + $_POST['emiAmount'];
//
//                    $Emidata = array(
//                        "studentcourseId" => $_POST['studentcourseId'],
//                        "stud_id" => ucwords($_POST['stud_id']),
//                        "emiAmount" => $_POST['emiAmount'],
//                        "totalOfEmiAmt" => $totalOfEmiAmt,
//                        "joinAmount" => $_POST['joinAmount'],
//                        "booking_amount" => $_POST['registeredAmount'],
//                        "emiDate" => $emiDate,
//                        "strIP" => $_SERVER['REMOTE_ADDR']
//                    );
//                    $dealer_res2 = $connect->insertrecord($dbconn,'studentemidetail', $Emidata);
//                }
//                $i = $i + 2;
//            }
//        }
//
//        $sql = "select * from software inner join studentcourse on studentcourse.courseId=software.courseId where software.courseId IN (" . $cid . ") and studentcourse.stud_id=" . $_POST['stud_id'] . " ";
//        $sqlquery = mysqli_query($dbconn,"select * from studentcoursedetail where stud_id='" . $_POST['stud_id'] . "' and studentcourseId ='" . $_POST['studentcourseId'] . "'");
//        if (mysqli_num_rows($sqlquery) > 0) {
//            $q = mysqli_query($dbconn,"delete from studentcoursedetail where stud_id='" . $_POST['stud_id'] . "' and studentcourseId ='" . $_POST['studentcourseId'] . "'");
//        }
//
//        $res = mysqli_query($dbconn,$sql);
//        while ($sqlres = mysqli_fetch_array($res)) {
//            $datasoftware = array(
//                "courseId" => $_POST['cid'],
//                "stud_id" => ucwords($_POST['stud_id']),
//                "softwareId" => ucwords($sqlres['softwareId']),
//                "studentcourseId" => $_POST['studentcourseId'],
//                "strIP" => $_SERVER['REMOTE_ADDR']
//            );
//            $dealer_res = $connect->insertrecord($dbconn,'studentcoursedetail', $datasoftware);
//        }
//        echo $_POST['stud_id'];
//        break;


    case "EditEMi":
        $dataEditEmi = array(
            "emiReceivedDate" => $_POST['emiReceivedDate'],
            "actualReceivedAmount" => $_POST['actualReceivedAmount'],
            "comments" => ucwords($_POST['comments']),
            "strIP" => $_SERVER['REMOTE_ADDR']
        );
        $where = ' where  studemiId=' . $_POST['studemiId'];
        $dealer_res = $connect->updaterecord($dbconn, 'studentemidetail', $dataEditEmi, $where);

        echo $_REQUEST['stud_id'];
        break;

    case "AddStudentfees":
        $amount = $_POST['amount'];
        $taxFreeAmt = round($amount / 1.18, 2);
        $gst = round($amount - $taxFreeAmt, 2);
        if (isset($_POST['receiptNo'])) {
            $receiptNo = $_POST['receiptNo'];
        } else {
            $receiptNo = 0;
        }

        if ($_POST['studentPortal_Id'] == 2) {
            $recepitCount = $_POST['recepitCount'] + 1;
        } else {
            $recepitCount = 0;
        }
        if ($_POST['paymentMode'] == 1 || $_POST['paymentMode'] == 2) {
            $deposit = 'No';
            $depositAmount = '0';
            $depositDate = "";
            $toBank = "";
        } else {
            $deposit = 'Yes';
            $depositAmount = $_POST['amount'];
            $depositDate = $_POST['payDate'];
            $toBank = $_POST['bankDeposit'];
        }
        $data = array(
            "studentcourseId" => $_POST['studentcourseId'],
            "receiptNo" => $receiptNo,
            "recepitCount" => $recepitCount,
            "stud_id" => $_POST['stud_id'],
            "feetype" => "2",
            "amount" => $_POST['amount'],
            "payDate" => $_POST['payDate'],
            "paymentMode" => $_POST['paymentMode'],
            "bankName" => ucwords($_POST['bank_name']),
            "chequeNo" => $_POST['cheqNumber'],
            "deposit" => $deposit,
            "depositAmount" => $depositAmount,
            "depositDate" => $depositDate,
            "toBank" => $toBank,
            "comments" => ucwords($_POST['comments']),
            "decGst" => $gst,
            "texFreeAmt" => $taxFreeAmt,
            "strIP" => $_SERVER['REMOTE_ADDR']
        );
        $dealer_res = $connect->insertrecord($dbconn, 'studentfee', $data);

        $ReceivedAmount = $_POST['amount'];
        $diffAmt = 0;
        $i = 0;

        $filteremiDate = mysqli_query($dbconn, "SELECT * FROM studentemidetail WHERE stud_id='" . $_POST['stud_id'] . "' and studentemidetail.isDelete=0 and studentcourseId= '" . $_POST['studentcourseId'] . "' and isPaid = 0 order by studemiId asc");
        while ($studemiid = mysqli_fetch_array($filteremiDate)) {

            //NEW CODE
            $diffAmt = $studemiid['emiAmount'] - $studemiid['actualReceivedAmount'];
            if ($diffAmt > 0) {
                if ($ReceivedAmount >= $diffAmt) {
                    $EmiAmountPaid = $studemiid['actualReceivedAmount'] + $diffAmt;
                    $isPaid = 0;
                    if ($EmiAmountPaid == $studemiid['emiAmount']) {
                        $isPaid = 1;
                    }
                    $dataFee = array(
                        "emiReceivedDate" => $_POST['payDate'],
                        "actualReceivedAmount" => $EmiAmountPaid,
                        "comments" => ucwords($_POST['comments']),
                        "isPaid" => $isPaid,
                        "studentFeeId" => $dealer_res,
                        "strIP" => $_SERVER['REMOTE_ADDR']
                    );
                    $where = ' where  stud_id =' . $_POST['stud_id'] . ' and studentcourseId= ' . $_POST['studentcourseId'] . ' and studemiId = ' . $studemiid['studemiId'] . ' ';
                    $dealer_emi = $connect->updaterecord($dbconn, 'studentemidetail', $dataFee, $where);
                    $TotalReceivedAmount = $ReceivedAmount - $diffAmt;
                    $ReceivedAmount = $TotalReceivedAmount;
                } else if ($ReceivedAmount != 0) {

                    if ($ReceivedAmount < $diffAmt) {
                        $EmiAmountPaid = $studemiid['actualReceivedAmount'] + $ReceivedAmount;
                        $dataFee = array(
                            "emiReceivedDate" => $_POST['payDate'],
                            "actualReceivedAmount" => $EmiAmountPaid,
                            "comments" => ucwords($_POST['comments']),
                            "isPaid" => 0,
                            "studentFeeId" => $dealer_res,
                            "strIP" => $_SERVER['REMOTE_ADDR']
                        );
                        $diffAmt = 0;
                        $ReceivedAmount = 0;
                        $where = ' where  stud_id =' . $_POST['stud_id'] . ' and studentcourseId= ' . $_POST['studentcourseId'] . ' and studemiId = ' . $studemiid['studemiId'] . ' ';
                        $dealer_emi = $connect->updaterecord($dbconn, 'studentemidetail', $dataFee, $where);
                        $ReceivedAmount = $ReceivedAmount - $diffAmt;
                    }
                }
            }
        }
        echo $dealer_res;
        break;


    case "EditStudentfees":
        $amount = $_POST['amount'];
        $taxFreeAmt = round($amount / 1.18, 2);
        $gst = round($amount - $taxFreeAmt, 2);

        $datafee = array(
            "receiptNo" => $_POST['receiptNo'],
            "feetype" => "2",
            "amount" => $_POST['amount'],
            "payDate" => $_POST['payDate'],
            "paymentMode" => $_POST['paymentMode'],
            "bankName" => ucwords($_POST['bank_name']),
            "chequeNo" => $_POST['cheqNumber'],
            "deposit" => 'No',
            "comments" => ucwords($_POST['comments']),
            "decGst" => $gst,
            "texFreeAmt" => $taxFreeAmt,
            "strIP" => $_SERVER['REMOTE_ADDR']
        );
        $where = ' where  studentfeeid=' . $_POST['studentfeeid'];
        $dealer_res = $connect->updaterecord($dbconn, 'studentfee', $datafee, $where);

        $filteremiDate = mysqli_query($dbconn, "SELECT * FROM studentemidetail WHERE stud_id='" . $_POST['stud_id'] . "' and studentfeeid= '" . $_POST['studentfeeid'] . "' and studentemidetail.isDelete=0 order by studemiId asc");

        $ReceivedAmount = $_POST['amount'];
        $diffAmt = 0;
        $i = 0;
        while ($studemiid = mysqli_fetch_array($filteremiDate)) {

            $dataFee = array(
                "emiReceivedDate" => $_POST['payDate'],
                "comments" => ucwords($_POST['comments']),
                "studentFeeId" => $dealer_res,
                "strIP" => $_SERVER['REMOTE_ADDR']
            );
            $where = ' where  studemiId =' . $_POST['studemiId'] . ' and studentfeeid=' . $_POST['studentfeeid'] . ' ';
            $dealer_emi = $connect->updaterecord($dbconn, 'studentemidetail', $dataFee, $where);
        }

        echo $_POST['stud_id'];
        break;


    case "sendsms":

        $CheckList = $_POST['check_list'];
        $message = $_POST['smssend'];
        $sendername = $_POST['sendername'];

        $i = 0;
        foreach ($CheckList as $key => $value) {
            $mobileno[$i] = trim($value);
            $i++;
        }
        if (count($mobileno) >= 2) {
            $result = $connect->sendmultisms(implode(',', $mobileno), $message, $sendername);
        } else {
            $result = $connect->sendsinglesms($mobileno[0], $message, $sendername);
        }

        if (!$result) {
            echo '0';
        } else {
            echo '1';
        }

        break;

    case "AddOnAccountfees":
        $amount = $_POST['amount'];
        $taxFreeAmt = round($amount / 1.18, 2);
        $gst = round($amount - $taxFreeAmt, 2);

        if ($_POST['studentcourseId'] == '') {
            $studentcourseId = 0;
        } else {
            $studentcourseId = $_POST['studentcourseId'];
        }
        if ($_POST['paymentMode'] == 1 || $_POST['paymentMode'] == 2) {
            $deposit = 'No';
            $depositAmount = '0';
            $depositDate = '';
            $toBank = "";
        } else {
            $deposit = 'Yes';
            $depositAmount = $_POST['amount'];
            $depositDate = $_POST['payDate'];
            $toBank = $_POST['bankDeposit'];
        }
        $data = array(
            "payFor" => ucwords($_POST['payFor']),
            "studentcourseId" => $studentcourseId,
            "receiptNo" => 0,
            "recepitCount" => 0,
            "stud_id" => $_POST['stud_id'],
            "feetype" => '4',
            "amount" => $_POST['amount'],
            "payDate" => $_POST['payDate'],
            "paymentMode" => $_POST['paymentMode'],
            "bankName" => ucwords($_POST['bank_name']),
            "chequeNo" => $_POST['cheqNumber'],
            "deposit" => $deposit,
            "depositAmount" => $depositAmount,
            "depositDate" => $depositDate,
            "toBank" => $toBank,
            "comments" => ucwords($_POST['comments']),
            "decGst" => $gst,
            "texFreeAmt" => $taxFreeAmt,
            "strIP" => $_SERVER['REMOTE_ADDR']
        );
        $dealer_res = $connect->insertrecord($dbconn, 'studentfee', $data);
        echo $_REQUEST['stud_id'];
        break;

    case "DepositDetail":

        $deposit = $_POST['depositMode'];
        $feeid = $_POST['studentfeeid'];
        $cmt = $_POST['comment'];
        $CheckList = $_POST['depAmount'];
        $i = 0;
        $filetrBank = mysqli_fetch_array(mysqli_query($dbconn, "select isGst FROM  bank where bankId='" . $_POST['toBank'] . "'"));
        foreach ($CheckList as $key => $value) {
            $depAmount[$i] = trim($value);
            $cmt[$i];
            $feeid[$i];
            $deposit[$i];

            if ($filetrBank['isGst'] == 'YES') {
                $filterStudentFee = mysqli_fetch_array(mysqli_query($dbconn, "select max(iGstCount) as GstCount from studentfee,studentcourse where studentcourse.studentcourseId=studentfee.studentcourseId and studentcourse.branchId='" . $_POST['branchId'] . "'"));
                $GstCount[$i] = $filterStudentFee['GstCount'];
                $GstCount[$i] = $GstCount[$i] + 1;

                $branchAbbName = mysqli_fetch_array(mysqli_query($dbconn, "Select * from branchmaster where branchid =" . $_POST['branchid'] . " "));
                $iGstRef[$i] = 'KV/' . $branchAbbName['AbbreviationName'] . '/' . $GstCount[$i];
            } else {
                $GstCount[$i] = "0";
                $iGstRef[$i] = "0";
            }

            if ($depAmount[$i] > 0) {
                $dataStudeFees = array(
                    "deposit" => $deposit[$i],
                    "iGstRef" => $iGstRef[$i],
                    "iGstCount" => $GstCount[$i],
                    "depositAmount" => $depAmount[$i],
                    "depositDate" => $_POST['depositDate'],
                    "toBank" => $_POST['toBank'],
                    "remarks" => $cmt[$i]
                );
                $where = ' where  studentfeeid =' . $feeid[$i] . ' ';
                $dealer_emi = $connect->updaterecord($dbconn, 'studentfee', $dataStudeFees, $where);
            }
            $i++;
        }
        echo $dealer_emi;
        break;

    case "AddRegistrationfees":

        if ($_POST['studentPortal_Id'] == 2) {
            $recepitCount = $_POST['recepitCount'] + 1;
        } else {
            $recepitCount = 0;
        }

        $amount = $_POST['amount'];
        $taxFreeAmt = round($amount / 1.18, 2);
        $gst = round($amount - $taxFreeAmt, 2);
        if (isset($_POST['receiptNo'])) {
            $receiptNo = $_POST['receiptNo'];
        } else {
            $receiptNo = 0;
        }
        if ($_POST['paymentMode'] == 1 || $_POST['paymentMode'] == 2) {
            $deposit = 'No';
            $depositAmount = '0';
            $depositDate = "";
            $toBank = "";
        } else {
            $deposit = 'Yes';
            $depositAmount = $_POST['amount'];
            $depositDate = $_POST['payDate'];
            $toBank = $_POST['bankDeposit'];
        }
        $data = array(
            "studentcourseId" => '0',
            "receiptNo" => $receiptNo,
            "recepitCount" => $recepitCount,
            "stud_id" => $_POST['stud_id'],
            "feetype" => '1',
            "amount" => $_POST['amount'],
            "payDate" => $_POST['payDate'],
            "paymentMode" => $_POST['paymentMode'],
            "bankName" => ucwords($_POST['bank_name']),
            "chequeNo" => $_POST['cheqNumber'],
            "deposit" => $deposit,
            "depositAmount" => $depositAmount,
            "depositDate" => $depositDate,
            "toBank" => $toBank,
            "comments" => ucwords($_POST['comments']),
            "decGst" => $gst,
            "texFreeAmt" => $taxFreeAmt,
            "strIP" => $_SERVER['REMOTE_ADDR']
        );
        $dealer_res = $connect->insertrecord($dbconn, 'studentfee', $data);

        echo $_REQUEST['stud_id'];
        break;

    case "AddTarget":

        $month = $_POST['targetMonth'];
        $year = $_POST['targetYear'];
        $branch = $_POST['branch'];
        //print_r($_POST);exit;
        $filterTarget = mysqli_query($dbconn, "select * from target where month = " . $month . " and year = " . $year . " and iBranchId = " . $branch . " and isDelete = 0 and iStatus=1");
        if (mysqli_num_rows($filterTarget) == 0) {
            $data = array(
                'targetBooking' => $_POST['targetBooking'],
                'targetEnroll' => $_POST['targetEnroll'],
                'targetCollection' => $_POST['targetCollection'],
                'targetInquiry' => $_POST['targetInquiry'],
                'targetFPS' => $_POST['targetFPS'],
                'month' => $month,
                'year' => $year,
                'iBranchId' => $branch,
                // 'iempId' => $_SESSION['EmployeeId'],
                // 'isFreeze' => 1,
                'strEntryDate' => date('d-m-Y H:i:s'),
                'strIP' => $_SERVER['REMOTE_ADDR']
            );
            $dealer_res = $connect->insertrecord($dbconn, 'target', $data);

            echo $dealer_res;
        } else {
            echo 0;
        }

        break;

    case "EditTarget":
        $data = array(
            'targetBooking' => $_POST['targetBooking'],
            'targetEnroll' => $_POST['targetEnroll'],
            'targetCollection' => $_POST['targetCollection'],
            'targetInquiry' => $_POST['targetInquiry'],
            'targetFPS' => $_POST['targetFPS'],
            'iempId' => $_SESSION['EmployeeId'],
            'strEntryDate' => date('d-m-Y H:i:s'),
            'strIP' => $_SERVER['REMOTE_ADDR']
        );
        $where = ' where  itargetId =' . $_POST['itargetId'] . ' ';
        $dealer = $connect->updaterecord($dbconn, 'target', $data, $where);

        echo $dealer;
        break;

    case "UpdateStudentStatus":

        $data = array(
            'iStudentStatus' => $_POST['iStudentStatus']
        );
        $where = ' where  stud_id =' . $_POST['stud_id'] . ' ';
        $dealer = $connect->updaterecord($dbconn, 'studentadmission', $data, $where);
        echo $dealer;
        break;
    
    /*********************************************************************/
    case "AddCompany":
		$data = array(
			"strCompanyName" => ucfirst($_POST['strCompanyName']),
			"strContactPerson" => ucfirst($_POST['strContactPerson']),
			"strContactNumber" => $_POST['strContactNumber'],
			"strEmail" => $_POST['strEmail'],
			"strDesgination" => ucfirst($_POST['strDesgination']),
			"strWebsite" => $_POST['strWebsite'],
			"strAddress" => $_POST['strAddress'],
			"strEntryDate" => date('d-m-Y H:i:s'),
			"strIP" => $_SERVER['REMOTE_ADDR']
		);
		$dealer_res = $connect->insertrecord($dbconn, 'company', $data);
		echo $statusMsg = $dealer_res ? '1' : '0';
		break;

	case "GetAdminCompany":
		$filterstr = "SELECT * FROM `company`  where  isDelete='0'  and  iStatus='1' and  id=" . $_REQUEST['ID'] . "";
		$result = mysqli_query($dbconn, $filterstr);
		$row = mysqli_fetch_array($result);
		print_r(json_encode($row));
		break;

	case "EditCompany":
		$data = array(
			"strCompanyName" => ucfirst($_POST['strCompanyName']),
			"strContactPerson" => ucfirst($_POST['strContactPerson']),
			"strContactNumber" => $_POST['strContactNumber'],
			"strEmail" => $_POST['strEmail'],
			"strDesgination" => ucfirst($_POST['strDesgination']),
			"strWebsite" => $_POST['strWebsite'],
			"strAddress" => $_POST['strAddress'],
			"strEntryDate" => date('d-m-Y H:i:s'),
			"strIP" => $_SERVER['REMOTE_ADDR']
		);
		$where = ' where  id=' . $_REQUEST['id'];
		$dealer_res = $connect->updaterecord($dbconn, 'company', $data, $where);
		echo $statusMsg = $dealer_res ? '2' : '0';
		break;

	case "AddJob":
// 		$data = array(
// 			"iCompanyId" => $_POST['iCompanyId'],
// 			"strJobTitle" => ucfirst($_POST['strJobTitle']),
// 			"strExperience" => $_POST["strExperience"],
// 			"strJobDescription" => $_POST['strJobDescription'],
// 			"iPosition" => $_POST['iPosition'],
// 			"strEntryDate" => date('d-m-Y H:i:s'),
// 			"iJobCategoryId" => $_POST['iJobCategoryId'],
// 			"strEndDate" => date('Y-m-d',strtotime($_POST['strEndDate'])),
// 			"strIP" => $_SERVER['REMOTE_ADDR']
// 		);
// 		$dealer_res = $connect->insertrecord($dbconn, 'jobmaster', $data);
// 		echo $statusMsg = $dealer_res ? '1' : '0';
        $dealer_res = 0;
		for ($iCounter = 0; $iCounter < count($_POST['strJobTitle']); $iCounter++) {
			$data = array(
				"iCompanyId" => $_POST['iCompanyId'],
				"strJobTitle" => ucfirst($_POST['strJobTitle'][$iCounter]),
				"strExperience" => $_POST["strExperience"][$iCounter],
				"strJobDescription" => $_POST['strJobDescription'][$iCounter],
				"iPosition" => $_POST['iPosition'][$iCounter],
				"strEntryDate" => date('d-m-Y H:i:s'),
				"iJobCategoryId" => $_POST['iJobCategoryId'][$iCounter],
				"strEndDate" => date('Y-m-d', strtotime($_POST['strEndDate'][$iCounter])),
				"strIP" => $_SERVER['REMOTE_ADDR']
			);
			$dealer_res = $connect->insertrecord($dbconn, 'jobmaster', $data);
		}
		echo $statusMsg = $dealer_res ? '1' : '0';
		break;

	case "GetAdminJob":
		$filterstr = "SELECT * FROM `jobmaster`  where  isDelete='0'  and  iJobId=" . $_REQUEST['ID'] . "";
		$result = mysqli_query($dbconn, $filterstr);
		$row = mysqli_fetch_array($result);
		print_r(json_encode($row));
		break;

	case "EditJob":
		$data = array(
			"iCompanyId" => $_POST['iCompanyId'],
			"strJobTitle" => ucfirst($_POST['strJobTitle']),
			"strExperience" => $_POST["strExperience"],
			"strJobDescription" => $_POST['strJobDescription'],
			"iPosition" => $_POST['iPosition'],
			"strEntryDate" => date('d-m-Y H:i:s'),
			"strIP" => $_SERVER['REMOTE_ADDR']
		);
		$where = ' where  iJobId=' . $_REQUEST['iJobId'];
		$dealer_res = $connect->updaterecord($dbconn, 'jobmaster', $data, $where);
		echo $statusMsg = $dealer_res ? '2' : '0';
		break;

	case "EditJob":
		$data = array(
			"iCompanyId" => $_POST['iCompanyId'],
			"strJobTitle" => ucfirst($_POST['strJobTitle']),
			"strExperience" => $_POST["strExperience"],
			"strJobDescription" => $_POST['strJobDescription'],
			"iPosition" => $_POST['iPosition'],
			"strEntryDate" => date('d-m-Y H:i:s'),
			"strIP" => $_SERVER['REMOTE_ADDR']
		);
		$where = ' where  iJobId=' . $_REQUEST['iJobId'];
		$dealer_res = $connect->updaterecord($dbconn, 'jobmaster', $data, $where);
		echo $statusMsg = $dealer_res ? '2' : '0';
		break;

	case "JobStatus":
		//print_r($_POST);exit;
		$dealer_res = 0;
		foreach ($_POST['check_list'] as $iJobId) {
			$data = array(
				"iStatus" => '0',
				"strEntryDate" => date('d-m-Y H:i:s')
			);
			$where = ' where iJobId=' . $iJobId;
			$dealer_res = $connect->updaterecord($dbconn, 'jobmaster', $data, $where);
		}
		echo $dealer_res ? 1 : 0;
	break;

	case "AddStudentJobEntry":
		$iSalary = 0;
		if($_POST['iStatus'] == 1){
			$iSalary = $_POST['iSalary'];
		}
		$jobData = array(
			"iJobStatus" => $_POST['iStatus'],
			"strRemarks" => $_POST['strRemarks'],
			"iSalary" => $iSalary,
			"strPlacementDate" => date('d-m-Y'),
			"strEntryDate" => date('d-m-Y H:i:s'),
			"strIP" => $_SERVER['REMOTE_ADDR']
		);
		$whereJob = ' where iJobSubmissionId =' . $_POST['iJobSubmissionId'] . ' ';
		$dealer_res = $connect->updaterecord($dbconn, 'studentjobsubmission', $jobData,$whereJob);
		if($_POST['iStatus'] == 1){
			$data = array(
				'iJobStatus' => 2
			);
			$where = ' where  stud_id =' . $_POST['stud_id'] . ' ';
			$connect->updaterecord($dbconn, 'studentadmission', $data, $where);
		} else {
			$data = array(
				'iJobStatus' => 0
			);
			$where = ' where  stud_id =' . $_POST['stud_id'] . ' ';
			$connect->updaterecord($dbconn, 'studentadmission', $data, $where);
		}

		echo $statusMsg = $dealer_res ? '1' : '0';
	break;
	
	case "GetPlacedStudentJob":
		$filterstr = "SELECT c.strCompanyName,jb.strJobTitle,jb.iPosition,jb.strExperience,jb.strJobDescription,ss.iSalary,ss.strRemarks  FROM `studentjobsubmission` ss inner join jobmaster jb on ss.iJobId=jb.iJobId inner join company c on c.id=ss.iCompanyId  where iJobSubmissionId=" . $_REQUEST['iJobSubmissionId'] . " and iStudId=".$_REQUEST['StudId']."";
		$result = mysqli_query($dbconn, $filterstr);
		$row = mysqli_fetch_array($result);
		
		$html = "";
		$html .='<table class="table table-bordered table-hover center table-responsive dt-responsive" width="100%" id="tableC">
				<tr>
					<th class="desktop">Compnay Name</th>
					<td>'.$row['strCompanyName'].'</td>
				</tr>
				<tr>
					<th class="desktop">Job Title </th>
					<td>'.$row['strJobTitle'].'</td>
				</tr>
				<tr>
					<th class="desktop">Job Experience</th>
					<td>'.$row['strExperience'].'</td>
				</tr>
				<tr>
					<th class="desktop">Job Postion</th>
					<td>'.$row['iPosition'].'</td>
				</tr>
				<tr>
					<th class="desktop">Job Description</th>
					<td>'.$row['strJobDescription'].'</td>
				</tr>
				<tr>
					<th class="desktop">Salary</th>
					<td>'.$row['iSalary'].'</td>
				</tr>
				<tr>
					<th class="desktop">Remarks</th>
					<td>'.$row['strRemarks'].'</td>
				</tr>
			</table>';
		echo $html;
	break;

    case "MultiArrangeInterview":
		
		$dealer_res = 0;
		$filterstr = "SELECT iCompanyId FROM `jobmaster`  where  isDelete='0'  and  iJobId=" . $_POST['iJobId'] . "";
		$result = mysqli_query($dbconn, $filterstr);
		$row = mysqli_fetch_array($result);
	
		foreach ($_POST['check_list'] as $stud_id) {
		    $data = array(
				"iJobId" => $_POST['iJobId'],
				"iCompanyId" => $row['iCompanyId'],
				"iJobCategoryId" => $row['iJobCategoryId'],
				"iStudId" => $stud_id,
				"strInterviewDate" => date('d-m-Y'),
				"strEntryDate" => date('d-m-Y H:i:s'),
				"strIP" => $_SERVER['REMOTE_ADDR']
			);
			$dealer_res = $connect->insertrecord($dbconn, 'studentjobsubmission', $data);
		}
		echo $dealer_res ? 1 : 0;
	break;
	
	case "UpdateJobStudentStatus":
		$data = array(
			'iJobStatus' => $_POST['iJobStatus']
		);
		$where = ' where  stud_id =' . $_POST['stud_id'] . ' ';
		$dealer = $connect->updaterecord($dbconn, 'studentadmission', $data, $where);
		echo $dealer;
	break;

	case "MultiUpdateJobStudentStatus":
		
		foreach ($_POST['check_list'] as $stud_id) {
			$data = array(
				'iJobStatus' => $_POST['iUpdatedJobStatus']
			);
			$where = ' where stud_id =' . $stud_id . '';
			$dealer_res = $connect->updaterecord($dbconn, 'studentadmission', $data,$where);
		}
		echo $dealer_res ? 1 : 0;
	break;
	
	case "UpdatePlaceJobStudentStatus":
	    $filterstr = "SELECT id FROM company  where strCompanyName like '%".$_POST['iCompanyId']."%' and isDelete=0 and iStatus=1 order by id desc limit 1";
		$result = mysqli_query($dbconn, $filterstr);
		$row = mysqli_fetch_array($result);
		$jobData = array(
			"iCompanyId" => $row['id'],
			"iJobCategoryId" => $_POST['iJobCategoryId'],
			"iStudId" => $_POST['iStudId'],
			"iJobStatus" => $_POST['iStatus'],
			"iSalary" => isset($_POST['iSalary']) && $_POST['iSalary'] != "" ? $_POST['iSalary'] : 0,
			"strRemarks" => $_POST['strRemarks'],
			"strPlacementDate" => $_POST['strPlacementDate'],
			"strEntryDate" => date('d-m-Y H:i:s'),
			"strIP" => $_SERVER['REMOTE_ADDR']
		);
		$dealer_res = $connect->insertrecord($dbconn, 'studentjobsubmission', $jobData);
		$data = array(
			'iJobStatus' => 2
		);
		$where = ' where  stud_id =' . $_POST['iStudId'] . ' ';
		$connect->updaterecord($dbconn, 'studentadmission', $data, $where);
		
// 		$jobData = array(
// 			"iCompanyId" => $_POST['iCompanyId'],
// 			"iJobCategoryId" => $_POST['iJobCategoryId'],
// 			"iStudId" => $_POST['stud_id'],
// 			"iJobStatus" => $_POST['iJobStatus'],
// 			"iSalary" => isset($_POST['iSalary']) && $_POST['iSalary'] != "" ? $_POST['iSalary'] : 0,
// 			"strPlacementDate" => $_POST['strPlacementDate'],
// 			"strRemarks" => $_POST['strRemarks'],
// 			"strEntryDate" => date('d-m-Y H:i:s'),
// 			"strIP" => $_SERVER['REMOTE_ADDR']
// 		);
// 		$dealer_res = $connect->insertrecord($dbconn, 'studentjobsubmission', $jobData);
// 		$data = array(
// 			'iJobStatus' => 2
// 		);
// 		$where = ' where  stud_id =' . $_POST['stud_id'] . ' ';
// 		$connect->updaterecord($dbconn, 'studentadmission', $data, $where);
	
		echo $statusMsg = $dealer_res ? '1' : '0';
	break;
	
    default:
# code...
        echo "Page not Found";
        break;
}
?>
