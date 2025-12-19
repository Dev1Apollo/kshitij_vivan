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
        $result = mysqli_query($dbconn,$existsmail);
        $num_rows = mysqli_num_rows($result);
        $row = mysqli_fetch_array($result);

        if ($num_rows >= 1) {
            $good_hash = PBKDF2_HASH_ALGORITHM . ":" . PBKDF2_ITERATIONS . ":" . $row['salt'] . ":" . $row['password'];
            $oldpassword = mysql_real_escape_string($_REQUEST['oldpassword']);
            if (validate_password($_REQUEST['oldpassword'], $good_hash)) {
                $hash_result = create_hash($_REQUEST['password']);
                $hash_params = explode(":", $hash_result);
                $salt = $hash_params[HASH_SALT_INDEX];
                $hash = $hash_params[HASH_PBKDF2_INDEX];
                $getItems1 = mysqli_query($dbconn,"update employeemaster SET password = '" . $hash . "', salt = '" . $salt . "' where employeeMasterId='" . $_SESSION['EmployeeId'] . "'");
                echo "Sucess";
            } else {
                echo "OldNot";
            }
        } else {
            echo "ID not found";
        }
        break;

    case "AddCustomerEntry":

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

        $dealer_res = $connect->insertrecord($dbconn,'customerentry', $data);


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
        $dealer_res = $connect->updaterecord($dbconn,'customerentry', $data, $where);

        echo $_REQUEST['customerEntryId'];
        break;


    case "AddCreateInquiry":
        $inqdetail1 = '';
        $inqdetail2 = $_POST['InquiryDetail'];
        if ($_POST['InquiryStatus'] == '6') {
            $walkin_datetime = date('d-m-Y H:i:s');
        } else {
            $walkin_datetime = '';
        }

        $data = array(
            "customerEntryId" => $_POST['customerEntryId'],
            "inquiryfor" => $_POST['InquiryFor'],
            "inquiryForDetail1" => $inqdetail1,
            "inquiryForDetail2" => $inqdetail2,
            "noOfAdult" => $_POST['NoOfAdult'],
            "noOfChildWithBed" => $_POST['NoOfChildWithBed'],
            "noOfchildNobed" => $_POST['NoOfChildNoBed'],
            "infant" => $_POST['NoOfInfant'],
            "destination" => $_POST['Destination'],
            "noOfNights" => $_POST['NoOfNights'],
            "remarks" => $_POST['Remarks'],
            "walkin_datetime" => $walkin_datetime,
            "inquiryEnterDate" => date('d-m-Y H:i:s'),
            "employeeMasterId" => $_SESSION['EmployeeId'],
            "statusId" => $_POST['InquiryStatus'],
            "categoryOfInquiry" => $_POST['CategoryOfInquiry'],
            "isNewInquiry" => '1',
            "dateOfTravelFrom" => $_POST['FormDate'],
            "dateOfTravelTo" => $_POST['ToDate'],
            "strEntryDate" => date('d-m-Y H:i:s'),
            "strIP" => $_SERVER['REMOTE_ADDR']
        );
        $dealer_res = $connect->insertrecord($dbconn,'lead', $data);

        if ($_SESSION['branchid'] == 1) {
            $branch_name = 'ST';
        } else if ($_SESSION['branchid'] == 2) {
            $branch_name = 'CG';
        }

        $data_uniqueid = array(
            'leaduniqueid' => 'KV/' . $branch_name . '/' . $dealer_res . '/' . date('m') . '/' . date('Y')
        );
        $where = ' where  leadId=' . $dealer_res;
        $dealer_lead_uniqueid = $connect->updaterecord($dbconn,'lead', $data_uniqueid, $where);

        $rowfilterInq = mysqli_fetch_array(mysqli_query($dbconn,"SELECT Count(*) as count  FROM `lead`  where  customerEntryId='" . $_REQUEST['customerEntryId'] . "'"));
        $noOfInquiry = $rowfilterInq['count'];
        $rowfilterBooked = mysqli_fetch_array(mysqli_query($dbconn,"SELECT Count(*) as count  FROM `lead`  where  customerEntryId='" . $_REQUEST['customerEntryId'] . "' and statusId='3'"));
        $noOfBookedInquiry = $rowfilterBooked['count'];

        $dataI = array(
            "noOfInquiry" => $noOfInquiry,
            "noOfBookedInquiry" => $noOfBookedInquiry,
            "lastInquiryDate" => date('d-m-Y H:i:s')
        );
        $where = ' where  customerEntryId=' . $_REQUEST['customerEntryId'];
        $dealer_upd = $connect->updaterecord($dbconn,'customerentry', $dataI, $where);

        echo $dealer_res;
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


        $rowfilterLF = mysqli_fetch_array(mysqli_query($dbconn,"SELECT count(*) as count FROM `leadfollowup`  where leadId='" . $_POST['leadId'] . "'"));
        $statusCount = $rowfilterLF['count'];
        $rowfilterinquirystatus = mysqli_fetch_array(mysqli_query($dbconn,"SELECT * FROM `lead`  where leadId='" . $_POST['leadId'] . "'"));
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




    case "AddTransferInquiry":

        $CheckList = $_POST['check_list'];
        foreach ($CheckList as $key => $value) {
            $dataLead = array(
                "employeeMasterId" => $_POST['TransferEmployee'],
                "employeeConverted" => '1',
            );
            $where = ' where leadId=' . trim($value);
            $update = $connect->updaterecord($dbconn,'lead', $dataLead, $where);

            $data = array(
                "leadId" => trim($value),
                "employeeMasterId" => $_SESSION['EmployeeId'],
                "transferEmployeeId" => $_POST['TransferEmployee'],
                "transferDate" => date('d-m-Y H:i:s'),
                "strIP" => $_SERVER['REMOTE_ADDR']
            );
            $dealer_res = $connect->insertrecord($dbconn,'transferinquiry', $data);
        }

        break;


    case "AddRegistredStudent":
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
            "isRegister" => '1',
            "isAdmission" => '0',
            "employeeMasterId" => $_SESSION['EmployeeId']
        );


        $dealer_res = $connect->insertrecord($dbconn,'studentadmission', $data);

        $dataLead = array(
            "isRegister" => '1'
        );
        $where = ' where  leadId=' . $_POST['leadId'];
        $dealer_res = $connect->updaterecord($dbconn,'lead', $dataLead, $where);

        echo $dealer_res;
        break;


    case "EditRegisteredStudent":
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
            "isRegister" => '1',
            "isAdmission" => '0',
            "employeeMasterId" => $_SESSION['EmployeeId']
        );

        $where = ' where  stud_id=' . $_REQUEST['stud_id'];
        $dealer_res = $connect->updaterecord($dbconn,'studentadmission', $data, $where);

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


        $dealer_res = $connect->insertrecord($dbconn,'studentadmission', $data);
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
        $dealer_res = $connect->updaterecord($dbconn,'studentadmission', $data, $where);

        echo $_REQUEST['stud_id'];
        break;

    case "AddStudentCourse":

        $data = array(
            "courseId" => $_POST['cid'],
            "stud_id" => $_POST['stud_id'],
            "fee" => $_POST['fee'],
            "offeredfee" => $_POST['offeredfee'],
            "dateOfJoining" => $_POST['dateOfJoining'],
            "emiType" => $_POST['emiId'],
            "noOfEmi" => $_POST['noOfEmi'],
            "emiAmount" => $_POST['emiAmount'],
            "emiStartDate" => $_POST['emiStartDate'],
            "booking_amount" => $_POST['registeredAmount'],
            "studentStatus" => 1,
            "strIP" => $_SERVER['REMOTE_ADDR']
        );

        $dealer_res = $connect->insertrecord($dbconn,'studentcourse', $data);

        $query = mysqli_query($dbconn,"select * from studentcourse where courseId=" . $_POST['cid'] . " and stud_id =" . $_POST['stud_id'] . "");
        $row = mysqli_fetch_array($query);

        $noOfEmi = $_POST['noOfEmi'];
        $emiStartDate = strtotime($_POST['emiStartDate']);
        $type = $_POST['emiId'];
        if ($type == 1) {
            $dataEmi = array(
                "studentcourseId" => $row['studentcourseId'],
                "stud_id" => ucwords($_POST['stud_id']),
                "emiAmount" => $_POST['emiAmount'],
                "totalOfEmiAmt" => $totalOfEmiAmt,
                "joinAmount" => $_POST['joinAmount'],
                "booking_amount" => $_POST['registeredAmount'],
                "emiDate" => $emiDate,
                "strIP" => $_SERVER['REMOTE_ADDR']
            );

            $dealer_res2 = $connect->insertrecord($dbconn,'studentemidetail', $dataEmi);
        }
        if ($type == 2) {
            $totalOfEmiAmt = 0;
            for ($i = 0; $i < $noOfEmi; $i++) {
                $emiDate = date('d-m-Y', strtotime("+$i month", $emiStartDate));

                $totalOfEmiAmt = $totalOfEmiAmt + $_POST['emiAmount'];

                $dataEmi = array(
                    "studentcourseId" => $row['studentcourseId'],
                    "stud_id" => ucwords($_POST['stud_id']),
                    "emiAmount" => $_POST['emiAmount'],
                    "totalOfEmiAmt" => $totalOfEmiAmt,
                    "joinAmount" => $_POST['joinAmount'],
                    "booking_amount" => $_POST['registeredAmount'],
                    "emiDate" => $emiDate,
                    "strIP" => $_SERVER['REMOTE_ADDR']
                );

                $dealer_res2 = $connect->insertrecord($dbconn,'studentemidetail', $dataEmi);
            }
        }
        if ($type == 3) {
            $noOfEmi = $noOfEmi * 3;
            $totalOfEmiAmt = 0;
            for ($i = 0; $i < $noOfEmi; $i++) {
                $emiDate = date('d-m-Y', strtotime("+$i month", $emiStartDate));
                $totalOfEmiAmt = $totalOfEmiAmt + $_POST['emiAmount'];
                $Emidata = array(
                    "studentcourseId" => $row['studentcourseId'],
                    "stud_id" => ucwords($_POST['stud_id']),
                    "emiAmount" => $_POST['emiAmount'],
                    "totalOfEmiAmt" => $totalOfEmiAmt,
                    "joinAmount" => $_POST['joinAmount'],
                    "booking_amount" => $_POST['registeredAmount'],
                    "emiDate" => $emiDate,
                    "strIP" => $_SERVER['REMOTE_ADDR']
                );
                $i = $i + 2;
                $dealer_res2 = $connect->insertrecord($dbconn,'studentemidetail', $Emidata);
            }
        }

        $sqlquery = mysqli_fetch_array(mysqli_query($dbconn,"SELECT * FROM studentcourse where stud_id='" . $_POST['stud_id'] . "' and courseId='" . $_POST['cid'] . "'"));
        $sql = "select * from software where courseId=" . $_POST['cid'];

        $res = mysqli_query($dbconn,$sql);
        while ($sqlres = mysqli_fetch_array($res)) {
            $dataSoftware = array(
                "courseId" => $_POST['cid'],
                "stud_id" => ucwords($_POST['stud_id']),
                "studentcourseId" => $sqlquery['studentcourseId'],
                "softwareId" => ucwords($sqlres['softwareId']),
                "strIP" => $_SERVER['REMOTE_ADDR']
            );

            $dealer_res1 = $connect->insertrecord($dbconn,'studentcoursedetail', $dataSoftware);
        }

        $filetrStudId = "SELECT * from studentfee where stud_id ='" . $_POST['stud_id'] . "' and studentcourseId = '0'";
        $rowstudId = mysqli_query($dbconn,$filetrStudId);
        while ($dataStudId = mysqli_fetch_array($rowstudId)) {
            $data = array(
                "studentcourseId" => $dealer_res
            );

            $where = ' where  stud_id=' . $_POST['stud_id'];
            $dealer_res = $connect->updaterecord($dbconn,'studentfee', $data, $where);
        }


        echo $_REQUEST['stud_id'];
        break;

    case "EditStudentCourse":

        $data = array(
            "courseId" => $_POST['cid'],
            "offeredfee" => $_POST['offeredfee'],
            "dateOfJoining" => $_POST['dateOfJoining'],
            "emiType" => $_POST['emiId'],
            "noOfEmi" => $_POST['noOfEmi'],
            "emiAmount" => $_POST['emiAmount'],
            "emiStartDate" => $_POST['emiStartDate'],
            "booking_amount" => $_POST['registeredAmount'],
            "studentStatus" => 1,
            "lastPaymentDate" => date('d-m-Y H:i:s'),
            "strIP" => $_SERVER['REMOTE_ADDR']
        );

        $where = ' where  studentcourseId=' . $_POST['studentcourseId'];
        $dealer_res = $connect->updaterecord($dbconn,'studentcourse', $data, $where);

        $sql = mysqli_query($dbconn,"select * from studentemidetail where stud_id='" . $_POST['stud_id'] . "' and studentcourseId ='" . $_POST['studentcourseId'] . "'");
        if (mysqli_num_rows($sql) > 0) {
            $q = mysqli_query($dbconn,"delete from studentemidetail where stud_id='" . $_POST['stud_id'] . "' and studentcourseId ='" . $_POST['studentcourseId'] . "'");
        }


        $noOfEmi = $_POST['noOfEmi'];

        $emiStartDate = strtotime($_POST['emiStartDate']);


        $type = $_POST['emiId'];
        if ($type == 1) {
            $dataemi = array(
                "studentcourseId" => $_POST['studentcourseId'],
                "stud_id" => ucwords($_POST['stud_id']),
                "emiAmount" => $_POST['emiAmount'],
                "totalOfEmiAmt" => $totalOfEmiAmt,
                "joinAmount" => $_POST['joinAmount'],
                "booking_amount" => $_POST['registeredAmount'],
                "emiDate" => $emiDate,
                "strIP" => $_SERVER['REMOTE_ADDR']
            );

            $dealer_res2 = $connect->insertrecord($dbconn,'studentemidetail', $dataemi);
        }
        if ($type == 2) {
            $totalOfEmiAmt = 0;
            for ($i = 0; $i < $noOfEmi; $i++) {
                $emiDate = date('d-m-Y', strtotime("+$i month", $emiStartDate));
                $totalOfEmiAmt = $totalOfEmiAmt + $_POST['emiAmount'];
                $dataemi = array(
                    "studentcourseId" => $_POST['studentcourseId'],
                    "stud_id" => ucwords($_POST['stud_id']),
                    "emiAmount" => $_POST['emiAmount'],
                    "totalOfEmiAmt" => $totalOfEmiAmt,
                    "joinAmount" => $_POST['joinAmount'],
                    "booking_amount" => $_POST['registeredAmount'],
                    "emiDate" => $emiDate,
                    "strIP" => $_SERVER['REMOTE_ADDR']
                );

                $dealer_res2 = $connect->insertrecord($dbconn,'studentemidetail', $dataemi);
            }
        }

        if ($type == 3) {
            $noOfEmi = $noOfEmi * 3;
            $totalOfEmiAmt = 0;
            for ($i = 0; $i < $noOfEmi; $i++) {
                $emiDate = date('d-m-Y', strtotime("+$i month", $emiStartDate));
                $totalOfEmiAmt = $totalOfEmiAmt + $_POST['emiAmount'];
                $dataEMI = array(
                    "studentcourseId" => $_POST['studentcourseId'],
                    "stud_id" => $_POST['stud_id'],
                    "emiAmount" => $_POST['emiAmount'],
                    "totalOfEmiAmt" => $totalOfEmiAmt,
                    "joinAmount" => $_POST['joinAmount'],
                    "booking_amount" => $_POST['registeredAmount'],
                    "emiDate" => $emiDate,
                    "strIP" => $_SERVER['REMOTE_ADDR']
                );
                $i = $i + 2;
                $dealer_res = $connect->insertrecord($dbconn,'studentemidetail', $dataEMI);
            }
        }

        $sql = "select * from software inner join studentcourse on studentcourse.courseId=software.courseId where software.courseId=" . $_POST['cid'] . " and studentcourse.stud_id=" . $_POST['stud_id'] . " ";
        $sqlquery = mysqli_query($dbconn,"select * from studentcoursedetail where stud_id='" . $_POST['stud_id'] . "' and studentcourseId ='" . $_POST['studentcourseId'] . "'");
        if (mysqli_num_rows($sqlquery) > 0) {
            $q = mysqli_query($dbconn,"delete from studentcoursedetail where stud_id='" . $_POST['stud_id'] . "' and studentcourseId ='" . $_POST['studentcourseId'] . "'");
        }

        $res = mysqli_query($dbconn,$sql);
        while ($sqlres = mysqli_fetch_array($res)) {
            $datasoftware = array(
                "courseId" => $_POST['cid'],
                "stud_id" => ucwords($_POST['stud_id']),
                "softwareId" => ucwords($sqlres['softwareId']),
                "studentcourseId" => $_POST['studentcourseId'],
                "strIP" => $_SERVER['REMOTE_ADDR']
            );

            $dealer_res = $connect->insertrecord($dbconn,'studentcoursedetail', $datasoftware);
        }


        echo $_POST['stud_id'];
        break;


    case "EditEMi":
        $dataEditEmi = array(
            "emiReceivedDate" => $_POST['emiReceivedDate'],
            "actualReceivedAmount" => $_POST['actualReceivedAmount'],
            "comments" => ucwords($_POST['comments']),
            "strIP" => $_SERVER['REMOTE_ADDR']
        );
        $where = ' where  studemiId=' . $_POST['studemiId'];
        $dealer_res = $connect->updaterecord($dbconn,'studentemidetail', $dataEditEmi, $where);

        echo $_REQUEST['stud_id'];
        break;

    case "AddStudentfees":


        $amount = $_POST['amount'];
        $taxFreeAmt = round($amount / 1.18, 2);
        $gst = round($amount - $taxFreeAmt, 2);

        $cid = $_POST['cid'];


        $filtercourseid = "SELECT * FROM `studentcourse` where courseId =" . $cid . " and stud_id =" . $_POST['stud_id'] . "";
        $sqlsourseid = mysqli_query($dbconn,$filtercourseid);
        $studcourseId = mysqli_fetch_array($sqlsourseid);


        $data = array(
            "studentcourseId" => $studcourseId['studentcourseId'],
            "receiptNo" => $_POST['receiptNo'],
            "stud_id" => $_POST['stud_id'],
            "feetype" => $_POST['feetype'],
            "amount" => $_POST['amount'],
            "payDate" => $_POST['payDate'],
            "paymentMode" => $_POST['paymentMode'],
            "bankName" => ucwords($_POST['bank_name']),
            "chequeNo" => $_POST['cheqNumber'],
            "toBank" => ucwords($_POST['toBank']),
            "deposit" => $_POST['deposit'],
            "depositAmount" => $_POST['depositAmount'],
            "comments" => ucwords($_POST['comments']),
            "depositDate" => $_POST['depositDate'],
            "decGst" => $gst,
            "texFreeAmt" => $taxFreeAmt,
            "strIP" => $_SERVER['REMOTE_ADDR']
        );

        $dealer_res = $connect->insertrecord($dbconn,'studentfee', $data);

        if ($_POST['feetype'] != NULL && $_POST['feetype'] == 'Emi_Amount') {
            $type = $_POST['studemiId'];
            $ReceivedAmount = $_POST['amount'];
            $diffAmt = 0;


            for ($iCounter = 0; $iCounter < sizeof($type); $iCounter++) {

                $filteremiDate = mysqli_query($dbconn,"SELECT * FROM studentemidetail WHERE studemiId='" . $type[$iCounter] . "' ORDER BY studemiId ASC");
                while ($studemiid = mysqli_fetch_array($filteremiDate)) {
                    $unpaid = $studemiid['emiAmount'] - $studemiid['actualReceivedAmount'];

                    if (mysqli_num_rows($filteremiDate) > 0) {

                        if ($ReceivedAmount == $unpaid) {
                            $dataEmi = array(
                                "emiReceivedDate" => $_POST['payDate'],
                                "actualReceivedAmount" => $ReceivedAmount,
                                "comments" => ucwords($_POST['comments']),
                                "isPaid" => 1,
                                "studentFeeId" => $dealer_res,
                                "strIP" => $_SERVER['REMOTE_ADDR']
                            );

                            $where = ' where  studemiId =' . $type[$iCounter] . ' ';
                            $dealer_emi = $connect->updaterecord($dbconn,'studentemidetail', $dataEmi, $where);
                        } else if ($unpaid <= $ReceivedAmount) {
                            $dueAmt = $studemiid['emiAmount'] - $studemiid['actualReceivedAmount'];

                            $diffAmt = $ReceivedAmount - $dueAmt;

                            $totalreceivedAmt = $studemiid['actualReceivedAmount'] + $dueAmt;

                            $dataEmi = array(
                                "emiReceivedDate" => $_POST['payDate'],
                                "actualReceivedAmount" => $totalreceivedAmt,
                                "comments" => ucwords($_POST['comments']),
                                "isPaid" => 1,
                                "studentFeeId" => $dealer_res,
                                "strIP" => $_SERVER['REMOTE_ADDR']
                            );

                            $where = ' where  studemiId =' . $type[$iCounter] . ' ';
                            $dealer_emi = $connect->updaterecord($dbconn,'studentemidetail', $dataEmi, $where);

                            $ReceivedAmount = $diffAmt;
                        } if ($unpaid >= $ReceivedAmount) {

                            if ($studemiid['actualReceivedAmount'] != 0) {
                                $dueAmt = $studemiid['emiAmount'] - $studemiid['actualReceivedAmount'];
                                $totalreceivedAmt = $studemiid['actualReceivedAmount'] + $dueAmt;
                                $dataEmi = array(
                                    "emiReceivedDate" => $_POST['payDate'],
                                    "actualReceivedAmount" => $totalreceivedAmt,
                                    "comments" => ucwords($_POST['comments']),
                                    "isPaid" => 1,
                                    "studentFeeId" => $dealer_res,
                                    "strIP" => $_SERVER['REMOTE_ADDR']
                                );

                                $where = ' where  studemiId =' . $type[$iCounter] . ' ';
                                $dealer_emi = $connect->updaterecord($dbconn,'studentemidetail', $dataEmi, $where);
                            }
                            if ($studemiid['actualReceivedAmount'] == 0) {
                                $dueAmt = $studemiid['emiAmount'] - $studemiid['actualReceivedAmount'];
                                $diff = $studemiid['emiAmount'] - $ReceivedAmount;
                                $totalreceivedAmt = $studemiid['actualReceivedAmount'] + $ReceivedAmount;
                                if ($totalreceivedAmt == $studemiid['emiAmount']) {
                                    $dataEmi = array(
                                        "emiReceivedDate" => $_POST['payDate'],
                                        "actualReceivedAmount" => $totalreceivedAmt,
                                        "comments" => ucwords($_POST['comments']),
                                        "isPaid" => 1,
                                        "studentFeeId" => $dealer_res,
                                        "strIP" => $_SERVER['REMOTE_ADDR']
                                    );

                                    $where = ' where  studemiId =' . $type[$iCounter] . ' ';
                                    $dealer_emi = $connect->updaterecord($dbconn,'studentemidetail', $dataEmi, $where);
                                }
                            } else {
                                $dataEmi = array(
                                    "emiReceivedDate" => $_POST['payDate'],
                                    "actualReceivedAmount" => $totalreceivedAmt,
                                    "comments" => ucwords($_POST['comments']),
                                    "isPaid" => 0,
                                    "studentFeeId" => $dealer_res,
                                    "strIP" => $_SERVER['REMOTE_ADDR']
                                );

                                $where = ' where  studemiId =' . $type[$iCounter] . ' ';
                                $dealer_emi = $connect->updaterecord($dbconn,'studentemidetail', $dataEmi, $where);
                            }
                        }
                    }
                }
            }
        }


        echo $_REQUEST['stud_id'];
        break;


    case "EditStudentfees":
        $amount = $_POST['amount'];
        $taxFreeAmt = round($amount / 1.18, 2);
        $gst = round($amount - $taxFreeAmt, 2);

        $datafee = array(
            "receiptNo" => $_POST['receiptNo'],
            "feetype" => $_POST['feetype'],
            "amount" => $_POST['amount'],
            "payDate" => $_POST['payDate'],
            "paymentMode" => $_POST['paymentMode'],
            "bankName" => ucwords($_POST['bank_name']),
            "chequeNo" => $_POST['cheqNumber'],
            "toBank" => ucwords($_POST['toBank']),
            "deposit" => ucwords($_POST['deposit']),
            "depositAmount" => $_POST['depositAmount'],
            "comments" => ucwords($_POST['comments']),
            "depositDate" => $_POST['depositDate'],
            "decGst" => $gst,
            "texFreeAmt" => $taxFreeAmt,
            "strIP" => $_SERVER['REMOTE_ADDR']
        );

        $where = ' where  studentfeeid=' . $_POST['studentfeeid'];
        $dealer_res = $connect->updaterecord($dbconn,'studentfee', $datafee, $where);

        echo $_REQUEST['stud_id'];
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

        $data = array(
            "payFor" => ucwords($_POST['payFor']),
            "studentcourseId" => $studentcourseId,
            "receiptNo" => $_POST['receiptNo'],
            "stud_id" => $_POST['stud_id'],
            "feetype" => 'On_Account_Fee',
            "amount" => $_POST['amount'],
            "payDate" => $_POST['payDate'],
            "paymentMode" => $_POST['paymentMode'],
            "bankName" => ucwords($_POST['bank_name']),
            "chequeNo" => $_POST['cheqNumber'],
            "toBank" => ucwords($_POST['toBank']),
            "deposit" => $_POST['deposit'],
            "depositAmount" => $_POST['depositAmount'],
            "comments" => ucwords($_POST['comments']),
            "depositDate" => $_POST['depositDate'],
            "decGst" => $gst,
            "texFreeAmt" => $taxFreeAmt,
            "strIP" => $_SERVER['REMOTE_ADDR']
        );

        $dealer_res = $connect->insertrecord($dbconn,'studentfee', $data);
        echo $_REQUEST['stud_id'];
        break;


    case "AddRegistrationfees":
        $amount = $_POST['amount'];
        $taxFreeAmt = round($amount / 1.18, 2);
        $gst = round($amount - $taxFreeAmt, 2);

        $data = array(
            "studentcourseId" => '0',
            "receiptNo" => $_POST['receiptNo'],
            "stud_id" => $_POST['stud_id'],
            "feetype" => 'Registration_Amount',
            "amount" => $_POST['amount'],
            "payDate" => $_POST['payDate'],
            "paymentMode" => $_POST['paymentMode'],
            "bankName" => ucwords($_POST['bank_name']),
            "chequeNo" => $_POST['cheqNumber'],
            "toBank" => ucwords($_POST['toBank']),
            "deposit" => $_POST['deposit'],
            "depositAmount" => $_POST['depositAmount'],
            "comments" => ucwords($_POST['comments']),
            "depositDate" => $_POST['depositDate'],
            "decGst" => $gst,
            "texFreeAmt" => $taxFreeAmt,
            "strIP" => $_SERVER['REMOTE_ADDR']
        );

        $dealer_res = $connect->insertrecord($dbconn,'studentfee', $data);
        echo $_REQUEST['stud_id'];
        break;

    //            $diffAmt = $studemiid['emiAmount'] - $studemiid['actualReceivedAmount'];
//           if ($ReceivedAmount > 0 && $diffAmt > 0) {
//                
//                $totalreceivedAmt = $studemiid['actualReceivedAmount'] + $diffAmt;
//                if ($totalreceivedAmt == $ReceivedAmount){
//                    $dataFee = array(
//                        "emiReceivedDate" => $_POST['payDate'],
//                        "actualReceivedAmount" => $totalreceivedAmt,
//                        "comments" => ucwords($_POST['comments']),
//                        "isPaid" => 1,
//                        "studentFeeId" => $dealer_res,
//                        "strIP" => $_SERVER['REMOTE_ADDR']
//                    );
//                    
//                    $where = ' where  stud_id =' . $_POST['stud_id'] . ' and studentcourseId= ' . $studcourseId['studentcourseId'] . ' and studemiId = '.$studemiid['studemiId'].' ';
//                    $dealer_emi = $connect->updaterecord($dbconn,'studentemidetail', $dataFee, $where);
//                    
//                }else if($totalreceivedAmt <= $ReceivedAmount)
//                {
//                    $total = $ReceivedAmount - $diffAmt;
//                    $dataFee = array(
//                        "emiReceivedDate" => $_POST['payDate'],
//                        "actualReceivedAmount" => $totalreceivedAmt,
//                        "comments" => ucwords($_POST['comments']),
//                        "isPaid" => 1,
//                        "studentFeeId" => $dealer_res,
//                        "strIP" => $_SERVER['REMOTE_ADDR']
//                    );
//
//                    $where = ' where  stud_id =' . $_POST['stud_id'] . ' and studentcourseId= ' . $studcourseId['studentcourseId'] . ' and studemiId = '.$studemiid['studemiId'].' ';
//                    $dealer_emi = $connect->updaterecord($dbconn,'studentemidetail', $dataFee, $where);
//                    $ReceivedAmount = $total;
//                }else if($totalreceivedAmt >= $ReceivedAmount)
//                {
//                    $totalreceivedAmt = $ReceivedAmount + $studemiid['actualReceivedAmount'];
//                    $dataFee = array(
//                        "emiReceivedDate" => $_POST['payDate'],
//                        "actualReceivedAmount" => $totalreceivedAmt,
//                        "comments" => ucwords($_POST['comments']),
//                        "isPaid" => 0,
//                        "studentFeeId" => $dealer_res,
//                        "strIP" => $_SERVER['REMOTE_ADDR']
//                    );
//                   
//                    $where = ' where  stud_id =' . $_POST['stud_id'] . ' and studentcourseId= ' . $studcourseId['studentcourseId'] . ' and studemiId = '.$studemiid['studemiId'].' ';
//                    $dealer_emi = $connect->updaterecord($dbconn,'studentemidetail', $dataFee, $where);
//                    $ReceivedAmount = 0;
//                }
//                
//            }
//        }  
        
            
        
//        if ($_POST['feetype'] != NULL && $_POST['feetype'] == 'Emi_Amount') {
//            $type = $_POST['studemiId'];
//
//            $ReceivedAmount = $_POST['amount'];
//            $diffAmt = 0;
//            for ($iCounter = 0; $iCounter < sizeof($type); $iCounter++) {
//
//                $filteremiDate = mysqli_query($dbconn,"SELECT * FROM studentemidetail WHERE studemiId='" . $type[$iCounter] . "'");
//                while ($studemiid = mysqli_fetch_array($filteremiDate)) {
//                    $studemiid['emiAmount'];
//                    
//                    if (mysqli_num_rows($filteremiDate) > 0) {
//                        
//                        if ($ReceivedAmount == $studemiid['emiAmount']) {
//                            $dataEmi = array(
//                                "emiReceivedDate" => $_POST['payDate'],
//                                "actualReceivedAmount" => $ReceivedAmount,
//                                "comments" => ucwords($_POST['comments']),
//                                "isPaid" => 1,
//                                "studentFeeId"=>$dealer_res,
//                                "strIP" => $_SERVER['REMOTE_ADDR']
//                            );
//
//                            $where = ' where  studemiId =' . $type[$iCounter] . ' ';
//                            $dealer_emi = $connect->updaterecord($dbconn,'studentemidetail', $dataEmi, $where);
//                        } else if ($studemiid['emiAmount'] <= $ReceivedAmount) {
//                            $dueAmt = $studemiid['emiAmount'] - $studemiid['actualReceivedAmount'];
//
//                            $diffAmt = $ReceivedAmount - $dueAmt;
//
//                            $totalreceivedAmt = $studemiid['actualReceivedAmount'] + $dueAmt;
//
//                            $dataEmi = array(
//                                "emiReceivedDate" => $_POST['payDate'],
//                                "actualReceivedAmount" => $totalreceivedAmt,
//                                "comments" => ucwords($_POST['comments']),
//                                "isPaid" => 1,
//                                "studentFeeId"=>$dealer_res,
//                                "strIP" => $_SERVER['REMOTE_ADDR']
//                            );
//                            print_r($dataEmi);
//                            $where = ' where  studemiId =' . $type[$iCounter] . ' ';
//                            $dealer_emi = $connect->updaterecord($dbconn,'studentemidetail', $dataEmi, $where);
//
//                            $ReceivedAmount = $diffAmt;
//                            
//                        } if ($studemiid['emiAmount'] >= $ReceivedAmount){
//                            $diff = $studemiid['emiAmount'] - $ReceivedAmount;
//                            $totalreceivedAmt = $studemiid['actualReceivedAmount'] + $diff;
//                            if($totalreceivedAmt == $studemiid['emiAmount']){
//                            $dataEmi = array(
//                                "emiReceivedDate" => $_POST['payDate'],
//                                "actualReceivedAmount" => $totalreceivedAmt,
//                                "comments" => ucwords($_POST['comments']),
//                                "isPaid" => 1,
//                                "studentFeeId"=>$dealer_res,
//                                "strIP" => $_SERVER['REMOTE_ADDR']
//                            );
//                            
//                            $where = ' where  studemiId =' . $type[$iCounter] . ' ';
//                            $dealer_emi = $connect->updaterecord($dbconn,'studentemidetail', $dataEmi, $where);
//                           }else{
//                               $dataEmi = array(
//                                "emiReceivedDate" => $_POST['payDate'],
//                                "actualReceivedAmount" => $totalreceivedAmt,
//                                "comments" => ucwords($_POST['comments']),
//                                "isPaid" => 0,
//                                "studentFeeId"=>$dealer_res,
//                                "strIP" => $_SERVER['REMOTE_ADDR']
//                            );
//                            
//                            $where = ' where  studemiId =' . $type[$iCounter] . ' ';
//                            $dealer_emi = $connect->updaterecord($dbconn,'studentemidetail', $dataEmi, $where);
//                               
//                           }
//                           
//                        }
//                    }
//                }
//            }
//        }

    
    default:
# code...
        echo "Page not Found";
        break;
}
?>