<?php
ob_start();
error_reporting(E_ALL);
include('../common.php');
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
        $existsmail = "SELECT * FROM admin where id='" . $_SESSION['AdminId'] . "'";
        $result = mysqli_query($dbconn,$existsmail);
        $num_rows = mysqli_num_rows($result);
        $row = mysqli_fetch_array($result);

        if ($num_rows >= 1) {
            $good_hash = PBKDF2_HASH_ALGORITHM . ":" . PBKDF2_ITERATIONS . ":" . $row['salt'] . ":" . $row['password'];
            $oldpassword = mysqli_real_escape_string($_REQUEST['oldpassword']);
            if (validate_password($_REQUEST['oldpassword'], $good_hash)) {
                $hash_result = create_hash($_REQUEST['password']);
                $hash_params = explode(":", $hash_result);
                $salt = $hash_params[HASH_SALT_INDEX];
                $hash = $hash_params[HASH_PBKDF2_INDEX];
                $getItems1 = mysqli_query($dbconn,"update admin SET password = '" . $hash . "', salt = '" . $salt . "' where id='" . $_SESSION['AdminId'] . "'");
                echo "Sucess";
            } else {
                echo "OldNot";
            }
        } else {
            echo "ID not found";
        }
        break;

    case "AddInquirySource":
        $data = array(
            "inquirySourceName" => $_POST['InquirySource'],
            "strEntryDate" => date('d-m-Y H:i:s'),
            "strIP" => $_SERVER['REMOTE_ADDR']
        );
        $dealer_res = $connect->insertrecord($dbconn,'inquirysource', $data);
        echo $statusMsg = $dealer_res ? '1' : '0';
        break;

    case "GetAdminInquirySource":
        $filterstr = "SELECT * FROM `inquirysource`  where  isDelete='0'  and  istatus='1' and  inquirySourceId=" . $_REQUEST['ID'] . "";
        $result = mysqli_query($dbconn,$filterstr);
        $row = mysqli_fetch_array($result);
        print_r(json_encode($row));
        break;

    case "EditInquirySource":

        $data = array(
            "inquirySourceName" => $_POST['InquirySource'],
            "strEntryDate" => date('d-m-Y H:i:s'),
            "strIP" => $_SERVER['REMOTE_ADDR']
        );
        $where = ' where  inquirySourceId=' . $_REQUEST['inquirySourceId'];
        $dealer_res = $connect->updaterecord($dbconn,'inquirysource', $data, $where);
        echo $statusMsg = $dealer_res ? '2' : '0';
        break;

    case "AddInquiryfor":
        $data = array(
            "inquiryforName" => $_POST['InquiryFor'],
            "strEntryDate" => date('d-m-Y H:i:s'),
            "strIP" => $_SERVER['REMOTE_ADDR']
        );
        $dealer_res = $connect->insertrecord($dbconn,'inquiryformaster', $data);
        echo $statusMsg = $dealer_res ? '1' : '0';
        break;

    case "GetAdminInquiryFor":
        $filterstr = "SELECT * FROM `inquiryformaster`  where  isDelete='0'  and  istatus='1' and  inquiryforId=" . $_REQUEST['ID'] . "";
        $result = mysqli_query($dbconn,$filterstr);
        $row = mysqli_fetch_array($result);
        print_r(json_encode($row));
        break;

    case "EditInquiryFor":

        $data = array(
            "inquiryforName" => $_POST['InquiryFor'],
            "strEntryDate" => date('d-m-Y H:i:s'),
            "strIP" => $_SERVER['REMOTE_ADDR']
        );
        $where = ' where  inquiryforId=' . $_REQUEST['inquiryforId'];
        $dealer_res = $connect->updaterecord($dbconn,'inquiryformaster', $data, $where);
        echo $statusMsg = $dealer_res ? '2' : '0';
        break;

    case "AddStatus":
        $data = array(
            "statusName" => $_POST['Status'],
            "strEntryDate" => date('d-m-Y H:i:s'),
            "strIP" => $_SERVER['REMOTE_ADDR']
        );
        $dealer_res = $connect->insertrecord($dbconn,'status', $data);
        echo $statusMsg = $dealer_res ? '1' : '0';
        break;

    case "GetAdminStatus":
        $filterstr = "SELECT * FROM `status`  where  isDelete='0'  and  istatus='1' and  statusId=" . $_REQUEST['ID'] . "";
        $result = mysqli_query($dbconn,$filterstr);
        $row = mysqli_fetch_array($result);
        print_r(json_encode($row));
        break;

    case "EditStatus":

        $data = array(
            "statusName" => $_REQUEST['Status'],
            "strEntryDate" => date('d-m-Y H:i:s'),
            "strIP" => $_SERVER['REMOTE_ADDR']
        );
        $where = ' where  statusId=' . $_REQUEST['statusId'];
        $dealer_res = $connect->updaterecord($dbconn,'status', $data, $where);
        echo $statusMsg = $dealer_res ? '2' : '0';
        break;

    case "AddExpense":
        $data = array(
            "expenseName" => $_POST['Expense'],
            "date" => $_POST['Date'],
            "amount" => $_POST['Amount'],
            "strEntryDate" => date('d-m-Y H:i:s'),
            "strIP" => $_SERVER['REMOTE_ADDR']
        );
        $dealer_res = $connect->insertrecord($dbconn,'expense', $data);
        echo $statusMsg = $dealer_res ? '1' : '0';
        break;

    case "GetAdminExpense":
        $filterstr = "SELECT * FROM `expense`  where  isDelete='0'  and  istatus='1' and  expenseId=" . $_REQUEST['ID'] . "";
        $result = mysqli_query($dbconn,$filterstr);
        $row = mysqli_fetch_array($result);
        print_r(json_encode($row));
        break;

    case "EditExpense":

        $data = array(
            "expenseName" => $_POST['Expense'],
            "date" => $_POST['Date'],
            "amount" => $_POST['Amount'],
            "strEntryDate" => date('d-m-Y H:i:s'),
            "strIP" => $_SERVER['REMOTE_ADDR']
        );
        $where = ' where  expenseId=' . $_REQUEST['expenseId'];
        $dealer_res = $connect->updaterecord($dbconn,'expense', $data, $where);
        echo $statusMsg = $dealer_res ? '2' : '0';
        break;

    case "AddState":
        $data = array(
            "stateName" => $_POST['State'],
            "strEntryDate" => date('d-m-Y H:i:s'),
            "strIP" => $_SERVER['REMOTE_ADDR']
        );
        $dealer_res = $connect->insertrecord($dbconn,'state', $data);
        echo $statusMsg = $dealer_res ? '1' : '0';
        break;

    case "GetAdminState":
        $filterstr = "SELECT * FROM `state`  where  isDelete='0'  and  istatus='1' and  stateId=" . $_REQUEST['ID'] . "";
        $result = mysqli_query($dbconn,$filterstr);
        $row = mysqli_fetch_array($result);
        print_r(json_encode($row));
        break;

    case "EditState":

        $data = array(
            "stateName" => $_REQUEST['State'],
            "strEntryDate" => date('d-m-Y H:i:s'),
            "strIP" => $_SERVER['REMOTE_ADDR']
        );
        $where = ' where  stateId=' . $_REQUEST['stateId'];
        $dealer_res = $connect->updaterecord($dbconn,'state', $data, $where);
        echo $statusMsg = $dealer_res ? '2' : '0';
        break;

    case "AddCity":
        $data = array(
            "sId" => $_POST['State'],
            "name" => $_POST['City'],
            "strEntryDate" => date('d-m-Y H:i:s'),
            "strIP" => $_SERVER['REMOTE_ADDR']
        );
        $dealer_res = $connect->insertrecord($dbconn,'city', $data);
        echo $statusMsg = $dealer_res ? '1' : '0';
        break;

    case "GetAdminCity":
        $filterstr = "SELECT * FROM `city`  where  isDelete='0'  and  istatus='1' and  cityid=" . $_REQUEST['ID'] . "";
        $result = mysqli_query($dbconn,$filterstr);
        $row = mysqli_fetch_array($result);
        print_r(json_encode($row));
        break;

    case "EditCity":

        $data = array(
            "sId" => $_POST['State'],
            "name" => $_POST['City'],
            "strEntryDate" => date('d-m-Y H:i:s'),
            "strIP" => $_SERVER['REMOTE_ADDR']
        );
        $where = ' where  cityid=' . $_REQUEST['cityid'];
        $dealer_res = $connect->updaterecord($dbconn,'city', $data, $where);
        echo $statusMsg = $dealer_res ? '2' : '0';
        break;

    case "AddAdminEmployee":
        $hash_result = create_hash($_REQUEST['Password']);
        $hash_params = explode(":", $hash_result);
        $salt = $hash_params[HASH_SALT_INDEX];
        $hash = $hash_params[HASH_PBKDF2_INDEX];

        $data = array(
            "employeeName" => $_POST['Employee'],
            "email" => $_POST['Email'],
            "phoneNo" => $_POST['Phone'],
            "mobileNo" => $_POST['Mobile'],
            "loginId" => $_POST['LoginID'],
            "password" => $hash,
            "branchid" => $_POST['branchid'],
            "salt" => $salt,
            "gender" => $_POST['Gender'],
            "strEntryDate" => date('d-m-Y H:i:s'),
            "strIP" => $_SERVER['REMOTE_ADDR'],
            "employeeReportTo" => $_POST['ReportTo'],
            "iEmployeeType" => $_POST['iEmployeeType']
        );
        $dealer_res = $connect->insertrecord($dbconn,'employeemaster', $data);

        echo $dealer_res;
        break;

    case "EditAdminEmployee":
        $data = array(
            "loginId" => $_POST['LoginID'],
            "employeeName" => $_POST['Employee'],
            "email" => $_POST['Email'],
            "phoneNo" => $_POST['Phone'],
            "mobileNo" => $_POST['Mobile'],
            "gender" => $_POST['Gender'],
            "strEntryDate" => date('d-m-Y H:i:s'),
            "strIP" => $_SERVER['REMOTE_ADDR'],
            "employeeReportTo" => $_POST['ReportTo'],
            "iEmployeeType" => $_POST['iEmployeeType']
        );
        $where = ' where  employeeMasterId=' . $_REQUEST['employeeMasterId'];
        $dealer_res = $connect->updaterecord($dbconn,'employeemaster', $data, $where);


        echo $_REQUEST['adminemployeeId'];
        break;

    case "EmployeeChangePassword":
        $hash_result = create_hash($_REQUEST['password']);
        $hash_params = explode(":", $hash_result);
        $salt = $hash_params[HASH_SALT_INDEX];
        $hash = $hash_params[HASH_PBKDF2_INDEX];
        $getItems1 = mysqli_query($dbconn,"update employeemaster SET password = '" . $hash . "', salt = '" . $salt . "' where employeeMasterId='" . $_POST['employeeMasterId'] . "'");
        echo "Sucess";

        break;

    case "AddCustomerEntry":

        $data = array(
            "title" => $_POST['Title'],
            "firstName" => $_POST['FirstName'],
            "MiddleName" => $_POST['MiddleName'],
            "lastName" => $_POST['LastName'],
            "mobileNo" => $_POST['Mobile'],
            "email" => $_POST['Email'],
            "companyName" => $_POST['CompanyName'],
            "stateId" => $_POST['State'],
            "cityId" => $_POST['City'],
            "inquirySourceId" => $_POST['InquirySource'],
            "categoryOfCustomer" => $_POST['CategoryOfCustomer'],
            "strEntryDate" => date('d-m-Y H:i:s'),
            "strIP" => $_SERVER['REMOTE_ADDR']
        );
        $dealer_res = $connect->insertrecord($dbconn,'customerentry', $data);

        echo $dealer_res;
        break;

    case "EditCustomerEntry":

        $data = array(
            "title" => $_POST['Title'],
            "firstName" => $_POST['FirstName'],
            "MiddleName" => $_POST['MiddleName'],
            "lastName" => $_POST['LastName'],
            "mobileNo" => $_POST['Mobile'],
            "email" => $_POST['Email'],
            "companyName" => $_POST['CompanyName'],
            "stateId" => $_POST['State'],
            "cityId" => $_POST['City'],
            "inquirySourceId" => $_POST['InquirySource'],
            "categoryOfCustomer" => $_POST['CategoryOfCustomer'],
            "strEntryDate" => date('d-m-Y H:i:s'),
            "strIP" => $_SERVER['REMOTE_ADDR']
        );

        $where = ' where  customerEntryId=' . $_REQUEST['customerEntryId'];
        $dealer_res = $connect->updaterecord($dbconn,'customerentry', $data, $where);

        echo $_REQUEST['customerEntryId'];
        break;

    case "AddCourse":
        $data = array(
            "courseName" => $_POST['courseName'],
            "fee" => $_POST['fee'],
            "strEntryDate" => date('d-m-Y H:i:s'),
            "strIP" => $_SERVER['REMOTE_ADDR']
        );
        $dealer_res = $connect->insertrecord($dbconn,'course', $data);
        echo $statusMsg = $dealer_res ? '1' : '0';
        break;

    case "GetAdminCourse":
        $filterstr = "SELECT * FROM `course`  where  isDelete='0'  and  istatus='1' and  courseId=" . $_REQUEST['ID'] . "";
        $result = mysqli_query($dbconn,$filterstr);
        $row = mysqli_fetch_array($result);
        print_r(json_encode($row));
        break;

    case "EditCourse":

        $data = array(
            "courseName" => $_REQUEST['courseName'],
            "fee" => $_REQUEST['fee'],
            "strEntryDate" => date('d-m-Y H:i:s'),
            "strIP" => $_SERVER['REMOTE_ADDR']
        );
        $where = ' where  courseId=' . $_REQUEST['courseId'];
        $dealer_res = $connect->updaterecord($dbconn,'course', $data, $where);
        echo $statusMsg = $dealer_res ? '2' : '0';
        break;

    case "AddSoftware":
        $data = array(
            "courseId" => $_POST['courseId'],
            "softwareName" => $_POST['softwareName'],
            "strEntryDate" => date('d-m-Y H:i:s'),
            "strIP" => $_SERVER['REMOTE_ADDR']
        );
        $dealer_res = $connect->insertrecord($dbconn,'software', $data);
        echo $statusMsg = $dealer_res ? '1' : '0';
        break;

    case "GetAdminSoftware":
        $filterstr = "SELECT * FROM `software`  where  isDelete='0'  and  istatus='1' and  softwareId=" . $_REQUEST['ID'] . "";
        $result = mysqli_query($dbconn,$filterstr);
        $row = mysqli_fetch_array($result);
        print_r(json_encode($row));
        break;

    case "EditSoftware":

        $data = array(
            "courseId" => $_POST['courseId'],
            "softwareName" => $_POST['softwareName'],
            "strEntryDate" => date('d-m-Y H:i:s'),
            "strIP" => $_SERVER['REMOTE_ADDR']
        );
        $where = ' where  softwareId=' . $_REQUEST['softwareId'];
        $dealer_res = $connect->updaterecord($dbconn,'software', $data, $where);
        echo $statusMsg = $dealer_res ? '2' : '0';
        break;

    case "AddStudentStatus":
        $data = array(
            "studentStatusName" => $_POST['studentStatusName'],
            "strEntryDate" => date('d-m-Y H:i:s'),
            "strIP" => $_SERVER['REMOTE_ADDR']
        );
        $dealer_res = $connect->insertrecord($dbconn,'studentstatus', $data);
        echo $statusMsg = $dealer_res ? '1' : '0';
        break;

    case "GetAdminStudentStatus":
        $filterstr = "SELECT * FROM `studentstatus`  where  isDelete='0'  and  istatus='1' and  studstatusid=" . $_REQUEST['ID'] . "";
        $result = mysqli_query($dbconn,$filterstr);
        $row = mysqli_fetch_array($result);
        print_r(json_encode($row));
        break;

    case "EditStudentStatus":

        $data = array(
            "studentStatusName" => $_REQUEST['studentStatusName'],
            "strEntryDate" => date('d-m-Y H:i:s'),
            "strIP" => $_SERVER['REMOTE_ADDR']
        );
        $where = ' where  studstatusid=' . $_REQUEST['studstatusid'];
        $dealer_res = $connect->updaterecord($dbconn,'studentstatus', $data, $where);
        echo $statusMsg = $dealer_res ? '2' : '0';
        break;

    case "AddInquiryFollowUp":
        if (isset($_POST['booking_amount'])) {
            $_POST['booking_amount'] = $_POST['booking_amount'];
        } else {
            $_POST['booking_amount'] = '';
        }
        $data = array(
            "leadId" => $_POST['leadId'],
            "customerEntryId" => $_POST['customerEntryId'],
            "employeeMasterId" => $_SESSION['EmployeeId'],
            "nextFollowupDate" => $_POST['Date'],
            "statusId" => $_POST['InquiryStatus'],
            "comment" => $_POST['comment'],
            "strEntryDate" => date('d-m-Y H:i:s'),
            "strIP" => $_SERVER['REMOTE_ADDR']
        );
        $dealer_res = $connect->insertrecord($dbconn,'leadfollowup', $data);


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
            "walkin_datetime" => $walkindate,
            "comment" => $_POST['comment'],
            "booking_amount" => $_POST['booking_amount'],
            "statusCount" => $statusCount,
        );
        $where = ' where  leadId=' . $_REQUEST['leadId'];
        $update = $connect->updaterecord($dbconn,'lead', $dataLead, $where);

        $rowfilterBooked = mysqli_fetch_array(mysqli_query($dbconn,"SELECT Count(*) as count  FROM `lead`  where  customerEntryId='" . $_REQUEST['customerEntryId'] . "' and statusId='3'"));
        $noOfBookedInquiry = $rowfilterBooked['count'];

        $dataI = array(
            "noOfBookedInquiry" => $noOfBookedInquiry,
            "lastInquiryDate" => date('d-m-Y H:i:s')
        );
        $where = ' where  customerEntryId=' . $_REQUEST['customerEntryId'];
        $dealer_upd = $connect->updaterecord($dbconn,'customerentry', $dataI, $where);

        echo $dealer_res;
        break;

    case "sendsms":

//        echo $CheckList = $_POST['check_list'];
        $message = $_POST['smssend'];
        $sendername = $_POST['sendername'];

        $i = 0;
        foreach ($_POST['check_list'] as $key => $value) {
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
    
    case "AddTarget":
        $time = strtotime(date('d-m-Y'));
        $month = date("m", $time);
        $year = date("Y", $time);

        $filterTarget = mysqli_query($dbconn,"select * from target where month = " . $month . " and year = " . $year . " and iBranchId = ".$_POST['branch']." and isDelete = 0 and iStatus=1");
        if (mysqli_num_rows($filterTarget) == 0) {
            $data = array(
                'targetBooking' => $_POST['targetBooking'],
                'targetEnroll' => $_POST['targetEnroll'],
                'targetCollection' => $_POST['targetCollection'],
                'targetInquiry' => $_POST['targetInquiry'],
                'targetFPS' => $_POST['targetFPS'],
                'month' => $month,
                'year' => $year,
                'iBranchId' => $_POST['branch'],
                'strEntryDate' => date('d-m-Y H:i:s'),
                'strIP' => $_SERVER['REMOTE_ADDR']
            );
            $dealer_res = $connect->insertrecord($dbconn,'target', $data);
            
//            $filterInquiry = mysql_fetch_array(mysqli_query("SELECT count(*) as count FROM lead where  MONTH(STR_TO_DATE(walkin_datetime,'%d-%m-%Y'))=MONTH(CURRENT_DATE()) and YEAR(STR_TO_DATE(walkin_datetime,'%d-%m-%Y'))=YEAR(CURRENT_DATE()) and employeeMasterId='" . $_POST['branch'] . "'"));
//            $filterCollection = mysql_fetch_array(mysqli_query("select sum(amount) as collection from studentfee INNER JOIN studentadmission ON studentadmission.stud_id = studentfee.stud_id where MONTH(STR_TO_DATE(studentfee.payDate,'%d-%m-%Y'))=MONTH(CURRENT_DATE()) and YEAR(STR_TO_DATE(studentfee.payDate,'%d-%m-%Y'))=YEAR(CURRENT_DATE()) and studentadmission.branchId='" . $_POST['branch'] . "'"));
//            $filterBooking = mysql_fetch_array(mysqli_query("SELECT sum(booking_amount) as TotalRow FROM lead where  MONTH(STR_TO_DATE(nextFollowupModifyDate,'%d-%m-%Y'))=MONTH(CURRENT_DATE()) and YEAR(STR_TO_DATE(nextFollowupModifyDate,'%d-%m-%Y'))=YEAR(CURRENT_DATE())  and statusId='3' and employeeMasterId='" . $_POST['branch'] . "'"));
//            $filterEnroll = mysql_fetch_array(mysqli_query("SELECT count(*) as TotalRow FROM lead where  MONTH(STR_TO_DATE(nextFollowupModifyDate,'%d-%m-%Y'))=MONTH(CURRENT_DATE()) and YEAR(STR_TO_DATE(nextFollowupModifyDate,'%d-%m-%Y'))=YEAR(CURRENT_DATE())  and statusId='3' and employeeMasterId='" . $_POST['branch'] . "'"));
//
//            $updateTarget = array(
//                "achieveBooking" => $filterBooking['TotalRow'],
//                "achieveCollection" => $filterCollection['collection'],
//                "achieveInquiry" => $filterInquiry['count'],
//                "achieveEnroll" => $filterEnroll['TotalRow']
//            );
//            $where = ' where itargetId = ' . $dealer_res;
//            $update = $connect->updaterecord('target', $updateTarget, $where);
            
            echo $dealer_res;
        }else{
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
            'strEntryDate' => date('d-m-Y H:i:s'),
            'strIP' => $_SERVER['REMOTE_ADDR']
        );
        $where = ' where  itargetId =' . $_POST['itargetId'] . ' ';
        $dealer = $connect->updaterecord($dbconn,'target', $data, $where);
        
//        $filterInquiry = mysql_fetch_array(mysqli_query("SELECT count(*) as count FROM lead where  MONTH(STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y'))=MONTH(CURRENT_DATE()) and YEAR(STR_TO_DATE(inquiryEnterDate,'%d-%m-%Y'))=YEAR(CURRENT_DATE())  and employeeMasterId='" . $_POST['iBranchId'] . "'"));
//        $filterCollection = mysql_fetch_array(mysqli_query("select sum(amount) as collection from studentfee INNER JOIN studentadmission ON studentadmission.stud_id = studentfee.stud_id where MONTH(STR_TO_DATE(studentfee.payDate,'%d-%m-%Y'))=MONTH(CURRENT_DATE()) and YEAR(STR_TO_DATE(studentfee.payDate,'%d-%m-%Y'))=YEAR(CURRENT_DATE()) and studentadmission.branchId='" . $_POST['iBranchId'] . "'"));
//        $filterBooking = mysql_fetch_array(mysqli_query("SELECT sum(booking_amount) as TotalRow FROM lead where  MONTH(STR_TO_DATE(nextFollowupModifyDate,'%d-%m-%Y'))=MONTH(CURRENT_DATE()) and YEAR(STR_TO_DATE(nextFollowupModifyDate,'%d-%m-%Y'))=YEAR(CURRENT_DATE())  and statusId='3' and employeeMasterId='" . $_POST['iBranchId'] . "'"));
//        $filterEnroll = mysql_fetch_array(mysqli_query("SELECT count(*) as TotalRow FROM lead where  MONTH(STR_TO_DATE(nextFollowupModifyDate,'%d-%m-%Y'))=MONTH(CURRENT_DATE()) and YEAR(STR_TO_DATE(nextFollowupModifyDate,'%d-%m-%Y'))=YEAR(CURRENT_DATE()) and statusId='3' and employeeMasterId='" . $_POST['iBranchId'] . "'"));
//
//        $updateTarget = array(
//            "achieveBooking" => $filterBooking['TotalRow'],
//            "achieveCollection" => $filterCollection['collection'],
//            "achieveInquiry" => $filterInquiry['count'],
//            "achieveEnroll" => $filterEnroll['TotalRow']
//        );
//        $where = ' where itargetId = ' . $dealer;
//        $update = $connect->updaterecord('target', $updateTarget, $where);
        
        echo $dealer;
        break;
    
    case "LeadWalkinChane":
        $data = array(
            "walkin_datetime" => $_POST['walkin_datetime']
        );
        $where = ' where  leadId =' . $_POST['token'] . ' ';
        $dealer = $connect->updaterecord($dbconn,'lead', $data, $where);
        echo $dealer;
        break;
    
    case "AddPaymentMaster":
        $data = array(
            "paymentName" => ucfirst($_POST['PaymentMaster']),
            "strEntryDate" => date('d-m-Y H:i:s'),
            "strIP" => $_SERVER['REMOTE_ADDR']
        );
        $dealer_res = $connect->insertrecord($dbconn,'paymentmode', $data);
        echo $statusMsg = $dealer_res ? '1' : '0';
        break;

    case "GetAdminPaymentMaster":
        $filterstr = "SELECT * FROM `paymentmode`  where  isDelete='0'  and  iStatus='1' and  paymentId=" . $_REQUEST['ID'] . "";
        $result = mysqli_query($dbconn,$filterstr);
        $row = mysqli_fetch_array($result);
        print_r(json_encode($row));
        break;

    case "EditPaymentMaster":

        $data = array(
            "paymentName" => ucfirst($_POST['PaymentMaster']),
            "strEntryDate" => date('d-m-Y H:i:s'),
            "strIP" => $_SERVER['REMOTE_ADDR']
        );
        $where = ' where  paymentId=' . $_REQUEST['paymentId'];
        $dealer_res = $connect->updaterecord($dbconn,'paymentmode', $data, $where);
        echo $statusMsg = $dealer_res ? '2' : '0';
        break;

        case "AddFeePayFor":
        $data = array(
            "FeePayForName" => ucfirst($_POST['FeePayFor']),
            "strEntryDate" => date('d-m-Y H:i:s'),
            "strIP" => $_SERVER['REMOTE_ADDR']
        );
        $dealer_res = $connect->insertrecord($dbconn,'feepayfor', $data);
        echo $statusMsg = $dealer_res ? '1' : '0';
        break;

         case "GetAdminFeePayFor":
        $filterstr = "SELECT * FROM `feepayfor`  where  isDelete='0'  and  iStatus='1' and  FeePayForId=" . $_REQUEST['ID'] . "";
        $result = mysqli_query($dbconn,$filterstr);
        $row = mysqli_fetch_array($result);
        print_r(json_encode($row));
        break;

    case "EditFeePayFor":

        $data = array(
            "FeePayForName" => ucfirst($_POST['FeePayFor']),
            "strEntryDate" => date('d-m-Y H:i:s'),
            "strIP" => $_SERVER['REMOTE_ADDR']
        );
        $where = ' where  FeePayForId=' . $_REQUEST['FeePayForId'];
        $dealer_res = $connect->updaterecord($dbconn,'feepayfor', $data, $where);
        echo $statusMsg = $dealer_res ? '2' : '0';
        break;

         case "AddBankMaster":
        $data = array(
            "bankName" => ucfirst($_POST['bankName']),
            "strEntryDate" => date('d-m-Y H:i:s'),
            "strIP" => $_SERVER['REMOTE_ADDR']
        );
        $dealer_res = $connect->insertrecord($dbconn,'bankmaster', $data);
        echo $statusMsg = $dealer_res ? '1' : '0';
        break;

         case "GetAdminBankMaster":
        $filterstr = "SELECT * FROM `bankmaster`  where  isDelete='0'  and  iStatus='1' and  bankMasterId=" . $_REQUEST['ID'] . "";
        $result = mysqli_query($dbconn,$filterstr);
        $row = mysqli_fetch_array($result);
        print_r(json_encode($row));
        break;

    case "EditBankMaster":

        $data = array(
             "bankName" => ucfirst($_POST['bankName']),
            "strEntryDate" => date('d-m-Y H:i:s'),
            "strIP" => $_SERVER['REMOTE_ADDR']
        );
        $where = ' where  bankMasterId=' . $_REQUEST['bankMasterId'];
        $dealer_res = $connect->updaterecord($dbconn,'bankmaster', $data, $where);
        echo $statusMsg = $dealer_res ? '2' : '0';
        break;

         case "AddBank":
        $data = array(
            "bankName" => ucfirst($_POST['bankName']),
            "strEntryDate" => date('d-m-Y H:i:s'),
            "strIP" => $_SERVER['REMOTE_ADDR']
        );
        $dealer_res = $connect->insertrecord($dbconn,'bank', $data);
        echo $statusMsg = $dealer_res ? '1' : '0';
        break;

         case "GetAdminBank":
        $filterstr = "SELECT * FROM `bank`  where  isDelete='0'  and  iStatus='1' and  bankId=" . $_REQUEST['ID'] . "";
        $result = mysqli_query($dbconn,$filterstr);
        $row = mysqli_fetch_array($result);
        print_r(json_encode($row));
        break;

    case "EditBank":

        $data = array(
             "bankName" => ucfirst($_POST['bankName']),
            "strEntryDate" => date('d-m-Y H:i:s'),
            "strIP" => $_SERVER['REMOTE_ADDR']
        );
        $where = ' where  bankId=' . $_REQUEST['bankId'];
        $dealer_res = $connect->updaterecord($dbconn,'bank', $data, $where);
        echo $statusMsg = $dealer_res ? '2' : '0';
        break;


    /******************************************************* */
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
		$data = array(
			"iCompanyId" => $_POST['iCompanyId'],
			"strJobTitle" => ucfirst($_POST['strJobTitle']),
			"strExperience" => $_POST["strExperience"],
			"strJobDescription" => $_POST['strJobDescription'],
			"iPosition" => $_POST['iPosition'],
			"iJobCategoryId" => $_POST['iJobCategoryId'],
			"strEndDate" => date('Y-m-d',strtotime($_POST['strEndDate'])),
			"strEntryDate" => date('d-m-Y H:i:s'),
			"strIP" => $_SERVER['REMOTE_ADDR']
		);
		$dealer_res = $connect->insertrecord($dbconn, 'jobmaster', $data);
		echo $statusMsg = $dealer_res ? '1' : '0';
		break;

	case "GetAdminJob":
		$filterstr = "SELECT * FROM `jobmaster`  where  isDelete='0'  and  iStatus='1' and  iJobId=" . $_REQUEST['ID'] . "";
		$result = mysqli_query($dbconn, $filterstr);
		$row = mysqli_fetch_array($result);
		$data = array(
			"iJobId" => $row['iJobId'],
			"iCompanyId" => $row['iCompanyId'],
			"strJobTitle" => $row['strJobTitle'],
			"strExperience" => $row["strExperience"],
			"strJobDescription" => $row['strJobDescription'],
			"iPosition" => $row['iPosition'],
			"iJobCategoryId" => $row['iJobCategoryId'],
			"strEndDate" => date('d-m-Y',strtotime($row['strEndDate']))
		);
		print_r(json_encode($data));
	break;

	case "EditJob":
		$data = array(
			"iCompanyId" => $_POST['iCompanyId'],
			"strJobTitle" => ucfirst($_POST['strJobTitle']),
			"strExperience" => $_POST["strExperience"],
			"strJobDescription" => $_POST['strJobDescription'],
			"iPosition" => $_POST['iPosition'],
			"iJobCategoryId" => $_POST['iJobCategoryId'],
			"strEndDate" => date('Y-m-d',strtotime($_POST['strEndDate'])),
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
		foreach($_POST['check_list'] as $iJobId){
			$data = array(
				"iStatus" => '0',
				"strEntryDate" => date('d-m-Y H:i:s')
			);
			$where = ' where iJobId=' . $iJobId;
			$dealer_res = $connect->updaterecord($dbconn, 'jobmaster', $data, $where);
		}
		echo $dealer_res ? 1 : 0;
	break;

	case "CourseStatus":
		//print_r($_POST);exit;
		$dealer_res = 0;
		foreach($_POST['check_list'] as $iJobId){
			$data = array(
				"istatus" => '0',
				"strEntryDate" => date('d-m-Y H:i:s')
			);
			$where = ' where courseId=' . $iJobId;
			$dealer_res = $connect->updaterecord($dbconn, 'course', $data, $where);
		}
		echo $dealer_res ? 1 : 0;
	break;
    
    case "InquirySourceStatus":
		//print_r($_POST);exit;
		$dealer_res = 0;
		foreach($_POST['check_list'] as $iJobId){
			$data = array(
				"iStatus" => '0',
				"strEntryDate" => date('d-m-Y H:i:s')
			);
			$where = ' where inquirySourceId=' . $iJobId;
			$dealer_res = $connect->updaterecord($dbconn, 'inquirysource', $data, $where);
		}
		echo $dealer_res ? 1 : 0;
	break;
	
	case "AddSupportEmployeeMaster":
        $data = array(
            "support_emp_name" => $_POST['support_emp_name'],
            "strEntryDate" => date('d-m-Y H:i:s'),
            "strIP" => $_SERVER['REMOTE_ADDR']
        );
        $dealer_res = $connect->insertrecord($dbconn,'support_employee', $data);
        echo $statusMsg = $dealer_res ? '1' : '0';
        break;

    case "GetSupportEmployeeMaster":
        $filterstr = "SELECT * FROM `support_employee`  where  isDelete='0'  and  istatus='1' and  id=" . $_REQUEST['ID'] . "";
        $result = mysqli_query($dbconn,$filterstr);
        $row = mysqli_fetch_array($result);
        print_r(json_encode($row));
        break;

    case "EditSupportEmployeeMaster":

        $data = array(
            "support_emp_name" => $_POST['support_emp_name'],
            "strEntryDate" => date('d-m-Y H:i:s'),
            "strIP" => $_SERVER['REMOTE_ADDR']
        );
        $where = ' where  id=' . $_REQUEST['id'];
        $dealer_res = $connect->updaterecord($dbconn,'support_employee', $data, $where);
        echo $statusMsg = $dealer_res ? '2' : '0';
        break;
        
    case "supportEmployeeMaster":
// 		print_r($_POST);exit;
		$dealer_res = 0;
		foreach($_POST['check_list'] as $iJobId){
			$data = array(
				"istatus" => '0',
				"strEntryDate" => date('d-m-Y H:i:s')
			);
			$where = ' where id=' . $iJobId;
			$dealer_res = $connect->updaterecord($dbconn, 'support_employee', $data, $where);
		}
		echo $dealer_res ? 1 : 0;
	break;
    
    default:
# code...
        echo "Page not Found";
        break;
}
?>