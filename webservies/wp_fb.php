<?php
ob_start();
header('Content-Type: application/json');
include_once '../common.php';

$connect = new connect();

$actions = isset($_REQUEST['action']) ? strtolower(trim($_REQUEST['action'])) : '';
extract($_REQUEST);

$output = array();


// ------------------------------------------
// CHECK REQUEST METHOD
// ------------------------------------------
$method = $_SERVER['REQUEST_METHOD'];
file_put_contents('fb_webhook.log', date('Y-m-d H:i:s') . " RAW: " . file_get_contents('php://input') . "\n", FILE_APPEND);

if ($method === 'GET') {
    // -----------------------------
    // FACEBOOK VERIFY MODE
    // -----------------------------
    fb_verify($dbconn);
    exit;
} elseif ($method === 'POST') {
    // -----------------------------
    // FACEBOOK LEAD RECEIVE MODE
    // -----------------------------
    fb_receive($dbconn,$connect);
    exit;
}
// invalid
$output['success'] = "0";
$output['message'] = "Invalid request method";

function fb_verify($dbconn)
{
    file_put_contents('fb_webhook.log', date('Y-m-d H:i:s') . " RAW: " . file_get_contents('php://input') . "\n", FILE_APPEND);

    $verify_token = "mycustom80"; // static OR fetch from DB using GUID
    
    // Accept both naming styles
    $mode      = $_GET['hub.mode'] ?? $_GET['hub_mode'] ?? null;
    $token     = $_GET['hub.verify_token'] ?? $_GET['hub_verify_token'] ?? null;
    $challenge = $_GET['hub.challenge'] ?? $_GET['hub_challenge'] ?? null;

    if ($mode === 'subscribe' && $token === $verify_token) {
        $output['message'] = $challenge;
        $output['success'] = '1';
        header("Content-Type: text/plain");
        echo $challenge;
        exit;
    }
    $output['message'] = 'Invalid verify token';
    $output['success'] = '0';
    //echo "Invalid verify token";
     echo "Invalid verify token";
    exit;
}

function fb_receive($dbconn,$connect) {
    file_put_contents('fb_webhook_recived.log', date('Y-m-d H:i:s') . " RAW: " . file_get_contents('php://input') . "\n", FILE_APPEND);
    


    $raw = file_get_contents("php://input");
    $payload = json_decode($raw, true);

    // Extract leadgen ID properly
    //$leadgenId = $payload['entry'][0]['changes'][0]['value']['leadgen_id'] ?? null;
    // if (isset($payload['entry'][0]['changes'][0]['value']['leadgen_id'])) {
    //     $leadgenId = $payload['entry'][0]['changes'][0]['value']['leadgen_id'];
    // } elseif (isset($payload['entry'][0]['changes'][0]['value']['lead_id'])) {
    //     $leadgenId = $payload['entry'][0]['changes'][0]['value']['lead_id'];
    // } elseif (isset($payload['leadgen_id'])) {
    //     $leadgenId = $payload['leadgen_id'];
    // } elseif (isset($payload['lead_id'])) {
    //     $leadgenId = $payload['lead_id'];
    // } elseif (isset($payload['id'])) {
    //     $leadgenId = $payload['id'];
    // }
    
    $leadgenId = null;

    if (isset($payload['entry'][0]['changes'][0]['value']['leadgen_id'])) {
        $leadgenId = $payload['entry'][0]['changes'][0]['value']['leadgen_id'];
    } elseif (isset($payload['entry'][0]['changes'][0]['value']['lead_id'])) {
        $leadgenId = $payload['entry'][0]['changes'][0]['value']['lead_id'];
    } elseif (isset($payload['leadgen_id'])) {
        $leadgenId = $payload['leadgen_id'];
    } elseif (isset($payload['lead_id'])) {
        $leadgenId = $payload['lead_id'];
    } elseif (isset($payload['id'])) {
        $leadgenId = $payload['id'];
    }
    file_put_contents('fb_webhook_leadgenId.log', "Extracted LeadgenId: " . ($leadgenId ?? 'NULL') . "\n", FILE_APPEND);
    if (!$leadgenId) {
        echo json_encode(["success" => 0, "message" => "leadgen_id missing"]);
        exit;
    }

    // Fetch lead details
    fb_fetch_lead_details($leadgenId,$dbconn,$connect);

    echo json_encode(["success" => 1, "message" => "received"]);
    exit;
}


function fb_fetch_lead_details($leadgenId,$dbconn,$connect) {
    $accessToken = "EAAP8fZCd5jzYBQO45CyFF5epqB3JERmv5oUC28lFsc5YoWtY09IlmmgZCRk7nmC8hH7FXHjWtCGunjxCGkJtYw63IOyeSmbdG59TvPOamwBI48ixCkXZCneGP1AZCSNpFfGAwrGOM0UZCLbRp0x5ZBnwwteaPrVZBiXzirKvkZCUYiBV93LbFKMZBB78FUEvbaXtGznFGYXiAdZAfvNTdD";
    $url = "https://graph.facebook.com/v21.0/{$leadgenId}";
    // $params = http_build_query([
    //     'access_token' => $accessToken,
    //     'fields' => 'field_data,created_time,form_id,ad_id,platform'
    // ]);
    // $fetchUrl = $url . '?' . $params;
    
    $url = "https://graph.facebook.com/v21.0/".$leadgenId;
    $params = http_build_query([
        'access_token' => $accessToken,
        'fields' => 'field_data,created_time,form_id,ad_id,platform'
    ]);
    $fetchUrl = $url . '?' . $params;
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $fetchUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // enable only for debugging
    $resp = curl_exec($ch);
    $curlErr = curl_error($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    file_put_contents('fb_webhook_resp.log', "Graph API Resp: " . $resp . "\n", FILE_APPEND);

    // fb_log("Graph response HTTP {$httpCode}: {$resp} (err: {$curlErr})");
    if ($httpCode < 200 || $httpCode >= 300) {
        echo json_encode(['success'=>$httpCode,'message'=>'Graph API call failed for lead='.$leadgenId]);
        // fb_log("Graph API call failed for lead={$leadgenId}");
        // return;
        $output['message'] = 'Graph API call failed for lead='.$leadgenId;
        $output['success'] = '0';
    }

    $leadData = json_decode($resp, true);
    
    if (!$leadData) {
        // fb_log("Invalid JSON from Graph API for lead={$leadgenId}");
        // return;
        echo json_encode(['success'=>$httpCode,'message'=>'Invalid JSON from Graph API for lead='.$leadgenId]);
        $output['message'] = 'Invalid JSON from Graph API for lead='.$leadgenId;
        $output['success'] = '0';
    }

    // if (empty($leadData['field_data']) || !is_array($leadData['field_data'])) {

    //     // fb_log("Empty or invalid field_data for lead={$leadgenId}");
    //     // return;
    //     echo json_encode(['success'=>$httpCode,'message'=>'Empty or invalid field_data for lead='.$leadgenId]);
    //     $output['message'] = 'Empty or invalid field_data for lead='.$leadgenId;
    //     $output['success'] = '0';
    // }
    if (!isset($leadData['field_data']) || !is_array($leadData['field_data'])) {
        file_put_contents('fb_webhook_fielddata_missing.log',
            "Missing field_data for lead {$leadgenId}. Raw Response: " . json_encode($leadData) . "\n",
            FILE_APPEND
        );
        return;   // ⛔ STOP EXECUTION
    }

    //print_r($leadData);exit;
    // $leadInfo = [];
    // foreach ($leadData['field_data'] as $f) {
    //     $name = $f['name'] ?? null;
    //     $val = $f['values'][0] ?? null;
    //     if ($name) $leadInfo[$name] = $val;
    // }
    // $leadInfo = [];
    // foreach ($leadData['field_data'] as $f) {
    //     if (!empty($f['name']) && !empty($f['values'][0])) {
    //         $leadInfo[strtolower(trim($f['name']))] = trim($f['values'][0]);
    //     }
    // }
    
    $leadInfo = [];
    foreach ($leadData['field_data'] as $f) {
        if (!empty($f['name']) && !empty($f['values'][0])) {
            $leadInfo[strtolower(trim($f['name']))] = trim($f['values'][0]);
        }
    }
    
    if (empty($leadInfo)) {
        $leadData = cleanKeys($leadData);
        function cleanKeys($array) {
            $clean = [];
            foreach ($array as $key => $value) {
                $newKey = trim($key);
                if (is_array($value)) {
                    $clean[$newKey] = cleanKeys($value);
                } else {
                    $clean[$newKey] = $value;
                }
            }
            return $clean;
        }
    }
    
    // Try common variants
    // $full_name = $leadInfo['full_name'] ?? $leadInfo['name'] ?? '';
    // $email = $leadInfo['email'] ?? $leadInfo['Email'] ?? '';
    // $phone = $leadInfo['phone_number'] ?? $leadInfo['phone'] ?? $leadInfo['Phone'] ?? '';
    // $course = $leadInfo['course'] ?? $leadInfo['which_program_are_you_interested_in_learning_more_about?'] ?? $leadInfo['interest'] ?? '';
    // $city = $leadInfo['city'] ?? $leadInfo['City']  ?? '';
    
    $full_name = $leadInfo['full_name'] ?? '';
    $email     = $leadInfo['email'] ?? '';
    $phone     = $leadInfo['phone'] ?? '';
    $course = $leadInfo['which_program_are_you_interested_in_learning_more_about?'] 
           ?? $leadInfo['course'] 
           ?? '';
    
    $city = $leadInfo['city'] ?? '';
    // $remarks = 'Name : '. $full_name . 'Email : '. $email . ' Phone : '. $phone . ' Course : ' . $course . ' City : ' .$city ; 
    // // build remarks from any other non-basic fields
    // $skip = ['full_name','name','email','phone_number','phone','course','inquiry_for','interest','created_time'];
    // $extraLines = [];
    // foreach ($leadInfo as $k=>$v) {
    //     if (in_array($k, $skip)) continue;
    //     $label = ucwords(str_replace('_',' ',$k));
    //     $extraLines[] = "{$label}: {$v}";
    // }
    
    $remarks = "Name: $full_name, Email: $email, Phone: $phone, Course: $course, City: $city";

    $extraLines = [];
    foreach ($leadInfo as $k => $v) {
        if (!in_array($k, ['full_name','email','phone','city','which_program_are_you_interested_in_learning_more_about?'])) {
            $label = ucwords(str_replace('_',' ', $k));
            $extraLines[] = "$label: $v";
        }
    }
    
    if (!empty($extraLines)) {
        $remarks .= "\n" . implode("\n", $extraLines);
    }

    if (!empty($extraLines)) $remarks = implode("\n", $extraLines);

    // Clean phone: remove +91 prefix, spaces, dashes
    $cleanPhone = null;
    if (!empty($phone)) {
        $cleanPhone = preg_replace('/^\+?91[-\s]?/', '', trim($phone));
        $cleanPhone = preg_replace('/\D+/', '', $cleanPhone);
    }

    // 5) Insert or reuse customerentry (avoid duplicates)
    $customerEntryId = 0;
    $emailEsc = mysqli_real_escape_string($dbconn, $email);
    $phoneEsc = mysqli_real_escape_string($dbconn, $cleanPhone);

    // First try exact mobile match
    if (!empty($phoneEsc)) {
        $q = mysqli_query($dbconn, "SELECT customerEntryId FROM customerentry WHERE mobileNo = '".$phoneEsc."' LIMIT 1");
        if ($q && mysqli_num_rows($q)) {
            $r = mysqli_fetch_assoc($q);
            $customerEntryId = intval($r['customerEntryId']);
        }
    }
    // Then try email
    if (empty($customerEntryId) && !empty($emailEsc)) {
        $q = mysqli_query($dbconn, "SELECT customerEntryId FROM customerentry WHERE email = '".$emailEsc."' LIMIT 1");
        if ($q && mysqli_num_rows($q)) {
            $r = mysqli_fetch_assoc($q);
            $customerEntryId = intval($r['customerEntryId']);
        }
    }

    // If still not found, insert
    if (empty($customerEntryId)) {
        $firstName = $full_name ? mysqli_real_escape_string($dbconn, ucwords(strtolower($full_name))) : '';
        $dataCust = array(
            "title" => '',
            "firstName" => $firstName,
            "MiddleName" => '',
            "lastName" => '',
            "mobileNo" => $cleanPhone,
            "email" => $email,
            "companyName" => '',
            "stateId" => 0,
            "cityId" => 0,
            "inquirySourceId" => 6, // Facebook
            "categoryOfCustomer" => '',
            "strEntryDate" => date('Y-m-d H:i:s'),
            "strIP" => $_SERVER['REMOTE_ADDR'] ?? '',
            "employeeMasterId" => 0
        );
        // Use your $connect helper if available
        if (is_object($connect) && method_exists($connect, 'insertrecord')) {
            $customerEntryId = $connect->insertrecord($dbconn, 'customerentry', $dataCust);
        } else {
            // fallback raw insert
            $cols = implode(',', array_keys($dataCust));
            $vals = array_map(function($v) use ($dbconn) { return "'" . mysqli_real_escape_string($dbconn, $v) . "'"; }, array_values($dataCust));
            $valsStr = implode(',', $vals);
            $sqlIns = "INSERT INTO customerentry ({$cols}) VALUES ({$valsStr})";
            if (mysqli_query($dbconn, $sqlIns)) {
                $customerEntryId = mysqli_insert_id($dbconn);
            } else {
                // fb_log("Failed insert customerentry: " . mysqli_error($dbconn));
                // return;
                echo json_encode(['success'=>$httpCode,'message'=>'Failed insert customerentry: " . mysqli_error($dbconn)']);
                $output['message'] = 'Failed insert customerentry: " . mysqli_error($dbconn)';
                $output['success'] = '0';
            }
        }
    }

    // 6) Map InquiryFor to same short codes used in addlmslead
    $InquiryFor = "Graphics";
    if (stripos($course, 'Visual') !== false || stripos($course, 'VFX') !== false) {
        $InquiryFor = "VFX";
    } elseif (stripos($course, 'Animation') !== false || stripos($course, '3D') !== false) {
        $InquiryFor = "Animation";
    } elseif (stripos($course, 'Game') !== false || stripos($course, 'Gaming') !== false) {
        $InquiryFor = "Gaming";
    } else {
        $InquiryFor = "Graphics";
    }

    // 7) Insert lead record
    $dataLead = array(
        "customerEntryId" => $customerEntryId,
        "inquiryfor" => $InquiryFor,
        "remarks" => $remarks,
        "walkin_datetime" => '',
        "inquiryEnterDate" => date('Y-m-d H:i:s'),
        "employeeMasterId" => 0,
        "support_employee" => 0,
        "statusId" => 1,
        "categoryOfInquiry" => 2,
        "isNewInquiry" => '1',
        "strEntryDate" => date('Y-m-d H:i:s'),
        "strIP" => $_SERVER['REMOTE_ADDR'] ?? '',
        "month" => date('m'),
        "year" => date('Y')
    );

    if (is_object($connect) && method_exists($connect, 'insertrecord')) {
        $leadId = $connect->insertrecord($dbconn, 'lead', $dataLead);
    } else {
        $cols = implode(',', array_keys($dataLead));
        $vals = array_map(function($v) use ($dbconn) { return "'" . mysqli_real_escape_string($dbconn, $v) . "'"; }, array_values($dataLead));
        $valsStr = implode(',', $vals);
        $sqlIns = "INSERT INTO lead ({$cols}) VALUES ({$valsStr})";
        if (mysqli_query($dbconn, $sqlIns)) {
            $leadId = mysqli_insert_id($dbconn);
        } else {
            echo json_encode(['success'=>$httpCode,'message'=>'Failed insert lead: " . mysqli_error($dbconn)']);
            $output['message'] = 'Failed insert lead: " . mysqli_error($dbconn)';
            $output['success'] = '0';
            
            // fb_log("Failed insert lead: " . mysqli_error($dbconn));
            // return;
        }
    }
    
    $EmpBranch = mysqli_fetch_array(mysqli_query($dbconn, "Select * from employeemaster where isDelete=0 and istatus=1 and branchid=0 order by employeeMasterId asc limit 1"));
    $branchAbbName = mysqli_fetch_array(mysqli_query($dbconn, "Select * from branchmaster where branchid =" . $EmpBranch['branchid'] . " "));
        
    $uniqueNo = mysqli_fetch_array(mysqli_query($dbconn, "Select count(*) as cnt from lead where month =" . date('m') . " and year =" . date('Y') . " and employeeMasterId=0"));
    if($EmpBranch['iEmployeeType'] == 2){
        $data_uniqueid = array(
            'leaduniqueid' => 'KV/SUV/' . $uniqueNo['cnt'] . '/' . date('m') . '/' . date('Y')
        );
        $where = ' where  leadId=' . $leadId;
        $dealer_lead_uniqueid = $connect->updaterecord($dbconn, 'lead', $data_uniqueid, $where);
    } else{
        $data_uniqueid = array(
            'leaduniqueid' => 'KV/' . $branchAbbName['AbbreviationName'] . '/' . $uniqueNo['cnt'] . '/' . date('m') . '/' . date('Y')
        );
        $where = ' where  leadId=' . $leadId;
        $dealer_lead_uniqueid = $connect->updaterecord($dbconn, 'lead', $data_uniqueid, $where);
    }
    // fb_log("Inserted leadId={$leadId} for customerEntryId={$customerEntryId}");

    // 8) Update customerentry counts (same as addlmslead)
    $rowfilterInq = mysqli_fetch_array(mysqli_query($dbconn, "SELECT Count(*) as count FROM lead WHERE customerEntryId='" . intval($customerEntryId) . "'"));
    $noOfInquiry = intval($rowfilterInq['count'] ?? 0);
    $rowfilterBooked = mysqli_fetch_array(mysqli_query($dbconn, "SELECT Count(*) as count FROM lead WHERE customerEntryId='" . intval($customerEntryId) . "' and statusId='3'"));
    $noOfBookedInquiry = intval($rowfilterBooked['count'] ?? 0);

    $dataI = array(
        "noOfInquiry" => $noOfInquiry,
        "noOfBookedInquiry" => $noOfBookedInquiry,
        "lastInquiryDate" => date('Y-m-d H:i:s')
    );
    $where = ' where customerEntryId=' . intval($customerEntryId);
    // use $connect->updaterecord if exists
    if (is_object($connect) && method_exists($connect, 'updaterecord')) {
        $connect->updaterecord($dbconn, 'customerentry', $dataI, $where);
    } else {
        $setParts = [];
        foreach ($dataI as $k=>$v) $setParts[] = "{$k}='" . mysqli_real_escape_string($dbconn, $v) . "'";
        mysqli_query($dbconn, "UPDATE customerentry SET " . implode(',', $setParts) . " {$where}");
    }

    // 9) Optionally: send admin email or whatsapp (left as TODO)
    //fb_log("fb_fetch_lead_details finished for lead={$leadgenId}");
    echo json_encode(['success'=>$httpCode,'message'=>'fb_fetch_lead_details finished for lead='.$leadgenId]);
    $output['message'] = 'fb_fetch_lead_details finished for lead='.$leadgenId;
    $output['success'] = '1';
}


print(json_encode($output));


// function fb_receive($dbconn)
// {
    
//     $raw = file_get_contents('php://input');
//     $payload = json_decode($raw, true);
    
//     // Extract lead id
//     $leadgenId = null;

//     if (isset($payload['entry'][0]['changes'][0]['value']['leadgen_id'])) {
//         $leadgenId = $payload['entry'][0]['changes'][0]['value']['leadgen_id'];
//     } elseif (isset($payload['entry'][0]['changes'][0]['value']['lead_id'])) {
//         $leadgenId = $payload['entry'][0]['changes'][0]['value']['lead_id'];
//     } elseif (isset($payload['leadgen_id'])) {
//         $leadgenId = $payload['leadgen_id'];
//     } elseif (isset($payload['lead_id'])) {
//         $leadgenId = $payload['lead_id'];
//     } elseif (isset($payload['id'])) {
//         $leadgenId = $payload['id'];
//     }

//     if (!$leadgenId) {
//         echo json_encode(['success'=>0,'message'=>'No lead ID found']);
//         exit;
//     }
    
//     // normalize
//     $normalized = preg_replace('/\D/', '', $leadgenId);
//     $toFetch = !empty($normalized) ? $normalized : $leadgenId;
    
//     // Call fb_fetch
//     fb_fetch_lead_details($toFetch,$dbconn);

//     $output['message'] = 'ok';
//     $output['success'] = '1';
//     echo json_encode(['success'=>1,'message'=>'OK']);
//     exit;
// }
