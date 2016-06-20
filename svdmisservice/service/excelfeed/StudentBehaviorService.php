<?php
require_once '../../model/user_management/OperationalUserManagement.php';
require_once '../../model/excelfeed/StudentBehaviorManagement.php';
require '../.././config/libs/Slim/Slim.php';

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();

// User id from db - Global Variable
$currunt_user_id = NULL;

/**
 * Adding Middle Layer to authenticate every request
 * Checking if the request has valid api key in the 'Authorization' header
 */
function authenticate(\Slim\Route $route) {
    // Getting request headers
    $headers = apache_request_headers();
    $response = array();
    $app = \Slim\Slim::getInstance();

   // Verifying Authorization Header
    if (isset($headers['Authorization'])) {
        $operationalUserManagement = new OperationalUserManagement();

        // get the api key
        $api_key = $headers['Authorization'];
        // validating api key
        if (!$operationalUserManagement->isValidApiKey($api_key)) {
            // api key is not present in users table
            $response["error"] = true;
            $response["message"] = "Access Denied. Invalid Api key";
            echoRespnse(401, $response);
            $app->stop();
        } else {
            global $currunt_user_id;
            // get user primary key id
            $currunt_user_id = $operationalUserManagement->getUserId($api_key);
        }
    } else {
        // api key is missing in header
        $response["error"] = true;
        $response["message"] = "Api key is misssing";
        echoRespnse(400, $response);
        $app->stop();
    }
}

/*
 * ------------------------ OCCUPATION TYPE TABLE METHODS ------------------------
 */
 
/**
 * Occupation_type Registration
 * url - /occupation_type_register
 * method - POST
 * params - occ_type_name, 	occ_type_description
 */
$app->post('/student_behavior_register',   function() use ($app) {
	
            // check for required params
            verifyRequiredParams(array('stu_admission_number', 'year' ));
			
			global $currunt_user_id;

            $response = array();

            // reading post params
            $stu_admission_number = $app->request->post('stu_admission_number');
            $year = $app->request->post('year');
			$clz_evaluation_cri_1 =  $app->request->post('clz_evaluation_cri_1');
			$clz_evaluation_cri_2 =  $app->request->post('clz_evaluation_cri_2');
			$clz_evaluation_cri_3 =  $app->request->post('clz_evaluation_cri_3');
			$clz_evaluation_cri_4 =  $app->request->post('clz_evaluation_cri_4');
			$clz_evaluation_cri_5 =  $app->request->post('clz_evaluation_cri_5');
			$clz_evaluation_cri_6 =  $app->request->post('clz_evaluation_cri_6');
			$clz_evaluation_cri_7 =  $app->request->post('clz_evaluation_cri_7');
			$clz_evaluation_cri_8 =  $app->request->post('clz_evaluation_cri_8');
			$clz_evaluation_cri_9 =  $app->request->post('clz_evaluation_cri_9');
			$clz_evaluation_cri_10 =  $app->request->post('clz_evaluation_cri_10');
			$clz_evaluation_cri_11 =  $app->request->post('clz_evaluation_cri_11');
			$clz_evaluation_cri_12 =  $app->request->post('clz_evaluation_cri_12');
			$clz_evaluation_cri_13 =  $app->request->post('clz_evaluation_cri_13');
			$clz_evaluation_cri_14 =  $app->request->post('clz_evaluation_cri_14');
			$clz_evaluation_cri_15 =  $app->request->post('clz_evaluation_cri_15');
			$clz_evaluation_cri_16 =  $app->request->post('clz_evaluation_cri_16');
			$clz_evaluation_cri_17 =  $app->request->post('clz_evaluation_cri_17');
			$clz_evaluation_cri_18 =  $app->request->post('clz_evaluation_cri_18');
			$clz_evaluation_cri_19 =  $app->request->post('clz_evaluation_cri_19');
			$clz_evaluation_cri_20 =  $app->request->post('clz_evaluation_cri_20');
			$clz_evaluation_cri_21 =  $app->request->post('clz_evaluation_cri_21');
			$clz_evaluation_cri_22 =  $app->request->post('clz_evaluation_cri_22');
			$clz_evaluation_cri_23 =  $app->request->post('clz_evaluation_cri_23');
			$clz_evaluation_cri_24 =  $app->request->post('clz_evaluation_cri_24');
			$clz_evaluation_cri_25 =  $app->request->post('clz_evaluation_cri_25');
			$clz_evaluation_cri_26 =  $app->request->post('clz_evaluation_cri_26');
			$clz_evaluation_cri_27 =  $app->request->post('clz_evaluation_cri_27');
			$clz_evaluation_cri_28 =  $app->request->post('clz_evaluation_cri_28');
			$clz_evaluation_cri_29 =  $app->request->post('clz_evaluation_cri_29');
			$clz_evaluation_cri_30 =  $app->request->post('clz_evaluation_cri_30');
			$clz_evaluation_cri_31 =  $app->request->post('clz_evaluation_cri_31');
			$clz_evaluation_cri_32 =  $app->request->post('clz_evaluation_cri_32');
			$clz_evaluation_cri_33 =  $app->request->post('clz_evaluation_cri_33');
			$clz_evaluation_cri_34 =  $app->request->post('clz_evaluation_cri_34');
			$clz_evaluation_cri_35 =  $app->request->post('clz_evaluation_cri_35');
			$clz_evaluation_cri_36 =  $app->request->post('clz_evaluation_cri_36');
			$clz_evaluation_cri_37 =  $app->request->post('clz_evaluation_cri_37');
			$clz_evaluation_cri_38 =  $app->request->post('clz_evaluation_cri_38');
			$clz_evaluation_cri_39 =  $app->request->post('clz_evaluation_cri_39');
			$clz_evaluation_cri_40 =  $app->request->post('clz_evaluation_cri_40');
			
			
			$clz_evaluation_cri_41 =  $app->request->post('clz_evaluation_cri_41');
			$clz_evaluation_cri_42 =  $app->request->post('clz_evaluation_cri_42');
			$clz_evaluation_cri_43 =  $app->request->post('clz_evaluation_cri_43');
			$clz_evaluation_cri_44 =  $app->request->post('clz_evaluation_cri_44');
			$clz_evaluation_cri_45 =  $app->request->post('clz_evaluation_cri_45');
			$clz_evaluation_cri_46 =  $app->request->post('clz_evaluation_cri_46');
			$clz_evaluation_cri_47 =  $app->request->post('clz_evaluation_cri_47');
			$clz_evaluation_cri_48 =  $app->request->post('clz_evaluation_cri_48');
			$clz_evaluation_cri_49 =  $app->request->post('clz_evaluation_cri_49');
			$clz_evaluation_cri_50 =  $app->request->post('clz_evaluation_cri_50');
			$clz_evaluation_cri_51 =  $app->request->post('clz_evaluation_cri_51');
			$clz_evaluation_cri_52 =  $app->request->post('clz_evaluation_cri_52');
			$clz_evaluation_cri_53 =  $app->request->post('clz_evaluation_cri_53');
			$clz_evaluation_cri_54 =  $app->request->post('clz_evaluation_cri_54');
			$clz_evaluation_cri_55 =  $app->request->post('clz_evaluation_cri_55');
			$clz_evaluation_cri_56 =  $app->request->post('clz_evaluation_cri_56');
			$clz_evaluation_cri_57 =  $app->request->post('clz_evaluation_cri_57');
			$clz_evaluation_cri_58 =  $app->request->post('clz_evaluation_cri_58');
			$clz_evaluation_cri_59 =  $app->request->post('clz_evaluation_cri_59');
			$clz_evaluation_cri_60 =  $app->request->post('clz_evaluation_cri_60');
			$clz_evaluation_cri_61 =  $app->request->post('clz_evaluation_cri_61');
			$clz_evaluation_cri_62 =  $app->request->post('clz_evaluation_cri_62');
			$clz_evaluation_cri_63 =  $app->request->post('clz_evaluation_cri_63');
			$clz_evaluation_cri_64 =  $app->request->post('clz_evaluation_cri_64');
			$clz_evaluation_cri_65 =  $app->request->post('clz_evaluation_cri_65');
			$clz_evaluation_cri_66 =  $app->request->post('clz_evaluation_cri_66');
			$clz_evaluation_cri_67 =  $app->request->post('clz_evaluation_cri_67');
			$clz_evaluation_cri_68 =  $app->request->post('clz_evaluation_cri_68');
			$clz_evaluation_cri_69 =  $app->request->post('clz_evaluation_cri_69');
			$clz_evaluation_cri_70 =  $app->request->post('clz_evaluation_cri_70');
			$clz_evaluation_cri_71 =  $app->request->post('clz_evaluation_cri_71');
			$clz_evaluation_cri_72 =  $app->request->post('clz_evaluation_cri_72');
			$clz_evaluation_cri_73 =  $app->request->post('clz_evaluation_cri_73');
			$clz_evaluation_cri_74 =  $app->request->post('clz_evaluation_cri_74');
			$clz_evaluation_cri_75 =  $app->request->post('clz_evaluation_cri_75');
			$clz_evaluation_cri_76 =  $app->request->post('clz_evaluation_cri_76');
			$clz_evaluation_cri_77 =  $app->request->post('clz_evaluation_cri_77');
			$clz_evaluation_cri_78 =  $app->request->post('clz_evaluation_cri_78');
			$clz_evaluation_cri_79 =  $app->request->post('clz_evaluation_cri_79');
			$clz_evaluation_cri_80 =  $app->request->post('clz_evaluation_cri_80');
			
			$clz_evaluation_cri_1_copy1  =  $app->request->post('clz_evaluation_cri_1_copy1'); 
			$clz_evaluation_cri_2_copy1  =  $app->request->post('clz_evaluation_cri_2_copy1'); 
			$clz_evaluation_cri_3_copy1  =  $app->request->post('clz_evaluation_cri_3_copy1'); 
			$clz_evaluation_cri_4_copy1  =  $app->request->post('clz_evaluation_cri_4_copy1'); 
			$clz_evaluation_cri_5_copy1  =  $app->request->post('clz_evaluation_cri_5_copy1'); 
			$clz_evaluation_cri_6_copy1  =  $app->request->post('clz_evaluation_cri_6_copy1'); 
			$clz_evaluation_cri_7_copy1  =  $app->request->post('clz_evaluation_cri_7_copy1'); 
			$clz_evaluation_cri_8_copy1  =  $app->request->post('clz_evaluation_cri_8_copy1'); 
			$clz_evaluation_cri_9_copy1  =  $app->request->post('clz_evaluation_cri_9_copy1'); 
			$clz_evaluation_cri_10_copy1  =  $app->request->post('clz_evaluation_cri_10_copy1'); 
			$clz_evaluation_cri_11_copy1  =  $app->request->post('clz_evaluation_cri_11_copy1'); 
			$clz_evaluation_cri_12_copy1  =  $app->request->post('clz_evaluation_cri_12_copy1'); 
			$clz_evaluation_cri_13_copy1  =  $app->request->post('clz_evaluation_cri_13_copy1'); 
			$clz_evaluation_cri_14_copy1  =  $app->request->post('clz_evaluation_cri_14_copy1'); 
			$clz_evaluation_cri_15_copy1  =  $app->request->post('clz_evaluation_cri_15_copy1'); 
			$clz_evaluation_cri_16_copy1  =  $app->request->post('clz_evaluation_cri_16_copy1'); 
			$clz_evaluation_cri_17_copy1  =  $app->request->post('clz_evaluation_cri_17_copy1'); 
			$clz_evaluation_cri_18_copy1  =  $app->request->post('clz_evaluation_cri_18_copy1'); 
			$clz_evaluation_cri_19_copy1  =  $app->request->post('clz_evaluation_cri_19_copy1'); 
			$clz_evaluation_cri_20_copy1  =  $app->request->post('clz_evaluation_cri_20_copy1'); 

			$clz_evaluation_cri_41_copy1  =  $app->request->post('clz_evaluation_cri_41_copy1'); 
			$clz_evaluation_cri_42_copy1  =  $app->request->post('clz_evaluation_cri_42_copy1'); 
			$clz_evaluation_cri_43_copy1  =  $app->request->post('clz_evaluation_cri_43_copy1'); 
			$clz_evaluation_cri_44_copy1  =  $app->request->post('clz_evaluation_cri_44_copy1'); 
			$clz_evaluation_cri_45_copy1  =  $app->request->post('clz_evaluation_cri_45_copy1'); 
			$clz_evaluation_cri_46_copy1  =  $app->request->post('clz_evaluation_cri_46_copy1'); 
			$clz_evaluation_cri_47_copy1  =  $app->request->post('clz_evaluation_cri_47_copy1'); 
			$clz_evaluation_cri_48_copy1  =  $app->request->post('clz_evaluation_cri_48_copy1'); 
			$clz_evaluation_cri_49_copy1  =  $app->request->post('clz_evaluation_cri_49_copy1'); 
			$clz_evaluation_cri_50_copy1  =  $app->request->post('clz_evaluation_cri_50_copy1'); 
			$clz_evaluation_cri_51_copy1  =  $app->request->post('clz_evaluation_cri_51_copy1'); 
			$clz_evaluation_cri_52_copy1  =  $app->request->post('clz_evaluation_cri_52_copy1'); 
			$clz_evaluation_cri_53_copy1  =  $app->request->post('clz_evaluation_cri_53_copy1'); 
			$clz_evaluation_cri_54_copy1  =  $app->request->post('clz_evaluation_cri_54_copy1'); 
			$clz_evaluation_cri_55_copy1  =  $app->request->post('clz_evaluation_cri_55_copy1'); 
			$clz_evaluation_cri_56_copy1  =  $app->request->post('clz_evaluation_cri_56_copy1'); 
			$clz_evaluation_cri_57_copy1  =  $app->request->post('clz_evaluation_cri_57_copy1'); 
			$clz_evaluation_cri_58_copy1  =  $app->request->post('clz_evaluation_cri_58_copy1'); 
			$clz_evaluation_cri_59_copy1  =  $app->request->post('clz_evaluation_cri_59_copy1'); 
			$clz_evaluation_cri_60_copy1  =  $app->request->post('clz_evaluation_cri_60_copy1'); 
           
            $studentBehaviorManagement = new StudentBehaviorManagement();
			$res = $studentBehaviorManagement->createStudentBehavior($stu_admission_number, $year, $clz_evaluation_cri_1, $clz_evaluation_cri_2, $clz_evaluation_cri_3, $clz_evaluation_cri_4, $clz_evaluation_cri_5, $clz_evaluation_cri_6, $clz_evaluation_cri_7, $clz_evaluation_cri_8, $clz_evaluation_cri_9, $clz_evaluation_cri_10, $clz_evaluation_cri_11, $clz_evaluation_cri_12, $clz_evaluation_cri_13, $clz_evaluation_cri_14, $clz_evaluation_cri_15, $clz_evaluation_cri_16, $clz_evaluation_cri_17, $clz_evaluation_cri_18, $clz_evaluation_cri_19, $clz_evaluation_cri_20, $clz_evaluation_cri_21, $clz_evaluation_cri_22, $clz_evaluation_cri_23, $clz_evaluation_cri_24, $clz_evaluation_cri_25, $clz_evaluation_cri_26, $clz_evaluation_cri_27, $clz_evaluation_cri_28, $clz_evaluation_cri_29, $clz_evaluation_cri_30, $clz_evaluation_cri_31, $clz_evaluation_cri_32, $clz_evaluation_cri_33, $clz_evaluation_cri_34, $clz_evaluation_cri_35, $clz_evaluation_cri_36, $clz_evaluation_cri_37, $clz_evaluation_cri_38, $clz_evaluation_cri_39, $clz_evaluation_cri_40, $clz_evaluation_cri_41, $clz_evaluation_cri_42, $clz_evaluation_cri_43, $clz_evaluation_cri_44, $clz_evaluation_cri_45, $clz_evaluation_cri_46, $clz_evaluation_cri_47, $clz_evaluation_cri_48, $clz_evaluation_cri_49, $clz_evaluation_cri_50, $clz_evaluation_cri_51, $clz_evaluation_cri_52, $clz_evaluation_cri_53, $clz_evaluation_cri_54, $clz_evaluation_cri_55, $clz_evaluation_cri_56, $clz_evaluation_cri_57, $clz_evaluation_cri_58, $clz_evaluation_cri_59, $clz_evaluation_cri_60, $clz_evaluation_cri_61, $clz_evaluation_cri_62, $clz_evaluation_cri_63, $clz_evaluation_cri_64, $clz_evaluation_cri_65, $clz_evaluation_cri_66, $clz_evaluation_cri_67, $clz_evaluation_cri_68, $clz_evaluation_cri_69, $clz_evaluation_cri_70, $clz_evaluation_cri_71, $clz_evaluation_cri_72, $clz_evaluation_cri_73, $clz_evaluation_cri_74, $clz_evaluation_cri_75, $clz_evaluation_cri_76, $clz_evaluation_cri_77, $clz_evaluation_cri_78, $clz_evaluation_cri_79, $clz_evaluation_cri_80, $clz_evaluation_cri_1_copy1, $clz_evaluation_cri_2_copy1, $clz_evaluation_cri_3_copy1, $clz_evaluation_cri_4_copy1, $clz_evaluation_cri_5_copy1, $clz_evaluation_cri_6_copy1, $clz_evaluation_cri_7_copy1, $clz_evaluation_cri_8_copy1, $clz_evaluation_cri_9_copy1, $clz_evaluation_cri_10_copy1, $clz_evaluation_cri_11_copy1, $clz_evaluation_cri_12_copy1, $clz_evaluation_cri_13_copy1, $clz_evaluation_cri_14_copy1, $clz_evaluation_cri_15_copy1, $clz_evaluation_cri_16_copy1, $clz_evaluation_cri_17_copy1, $clz_evaluation_cri_18_copy1, $clz_evaluation_cri_19_copy1, $clz_evaluation_cri_20_copy1, $clz_evaluation_cri_41_copy1, $clz_evaluation_cri_42_copy1, $clz_evaluation_cri_43_copy1, $clz_evaluation_cri_44_copy1, $clz_evaluation_cri_45_copy1, $clz_evaluation_cri_46_copy1, $clz_evaluation_cri_47_copy1, $clz_evaluation_cri_48_copy1, $clz_evaluation_cri_49_copy1, $clz_evaluation_cri_50_copy1, $clz_evaluation_cri_51_copy1, $clz_evaluation_cri_52_copy1, $clz_evaluation_cri_53_copy1, $clz_evaluation_cri_54_copy1, $clz_evaluation_cri_55_copy1, $clz_evaluation_cri_56_copy1, $clz_evaluation_cri_57_copy1, $clz_evaluation_cri_58_copy1, $clz_evaluation_cri_59_copy1, $clz_evaluation_cri_60_copy1, 1);
			
            $response["error"] = false;
            $response["behavior_report_state"] = $res;
            // echo json response
            echoRespnse(201, $response);
        });

/**
 * Occupation_type Update
 * url - /occupation_type_updates
 * method - PUT
 * params - occ_type_name, occ_type_description
 */
$app->put('/occupation_type_updates','authenticate', function() use ($app) {
	
             // check for required params
            verifyRequiredParams(array('occ_type_name', 'occ_type_description' ));
			
			global $currunt_user_id;

            $response = array();

            // reading put params
			$occ_type_name = $app->request->put('occ_type_name'); 
            $occ_type_description = $app->request->put('occ_type_description'); 
			
            $occupationTypeManagement = new OccupationTypeManagement();
			$res = $occupationTypeManagement->updateOccupation_type($occ_type_name, $occ_type_description, $currunt_user_id);
			
            if ($res == UPDATE_SUCCESSFULLY) {
                $response["error"] = false;
                $response["message"] = "Occupation type is Updated";
            } else if ($res == UPDATE_FAILED) {
                $response["error"] = true;
                $response["message"] = "Oops! An error occurred while updating occupation type ";
            } else if ($res == NOT_EXISTED) {
                $response["error"] = true;
                $response["message"] = "Sorry,this occupation type not exist";
            }
            // echo json response
            echoRespnse(201, $response);
        });


/**
 * Occupation_type Delete
 * url - /occupation_type_delete
 * method - DELETE
 * params -occ_type_name
 */
$app->delete('/occupation_type_delete', 'authenticate', function() use ($app) {
	
            // check for required params
            verifyRequiredParams(array('occ_type_name'));
			
			global $currunt_user_id;

			// reading put params
			$occ_type_name = $app->request->delete('occ_type_name'); 
			
            $response = array();

			
			$occupationTypeManagement = new OccupationTypeManagement();
			$res = $occupationTypeManagement->deleteOccupationType($occ_type_name,$currunt_user_id);
			
            if ($res == DELETE_SUCCESSFULLY) {
                $response["error"] = false;
                $response["message"] = "Occupation_type is successfully deleted";
            } else if ($res == DELETE_FAILED) {
                $response["error"] = true;
                $response["message"] = "Oops! An error occurred while deleting Occupation_type";
            } else if ($res == NOT_EXISTED) {
                $response["error"] = true;
                $response["message"] = "Sorry, this Occupation_type is not exist";
            }
            // echo json response
            echoRespnse(201, $response);
        });


		
/**
 * get one occupation_type
 * method GET
 * url /occupation_type/:projectName          
 */
$app->get('/occupation_type/:projectName', 'authenticate', function($occ_type_name) {
            global $currunt_user_id;
            $response = array();
            
			$occupationTypeManagement = new OccupationTypeManagement();
			$res = $occupationTypeManagement->getOccupationTypeByProjectName($occ_type_name);

            $response["error"] = false;
            $response["occupation_type"] = $res;

            

            echoRespnse(200, $response);
        });

/**
 * Listing all projects
 * method GET
 * url /occupation_type     
 */
$app->get('/occupation_types', 'authenticate', function() {
            global $user_id;
			
            $response = array();
			
            $occupationTypeManagement = new OccupationTypeManagement();
			$res = $occupationTypeManagement->getAllProjects();

            $response["error"] = false;
            $response["occupation_type"] = array();

            // looping through result and preparing projects array
            while ($occ_type = $res->fetch_assoc()) {
                $tmp = array();
				$tmp["occ_type_id"] = $occ_type["occ_type_id"];
                $tmp["occ_type_name"] = $occ_type["occ_type_name"];
                $tmp["occ_type_description"] = $occ_type["occ_type_description"];
                $tmp["status"] = $occ_type["status"];
                $tmp["recode_added_at"] = $occ_type["recode_added_at"];
				$tmp["recode_added_by"] = $occ_type["recode_added_by"];
				
                array_push($response["occupation_type"], $tmp);
            }

            echoRespnse(200, $response);
        });		
				

/*
 * ------------------------ SUPPORTIVE METHODS ------------------------
 */				
				
/**
 * Verifying required params posted or not
 */
function verifyRequiredParams($required_fields) {
    $error = false;
    $error_fields = "";
    $request_params = array();
    $request_params = $_REQUEST;
    // Handling PUT request params
    if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
        $app = \Slim\Slim::getInstance();
        parse_str($app->request()->getBody(), $request_params);
    }
	if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
        $app = \Slim\Slim::getInstance();
        parse_str($app->request()->getBody(), $request_params);
    }
    foreach ($required_fields as $field) {
        if (!isset($request_params[$field]) || strlen(trim($request_params[$field])) <= 0) {
            $error = true;
            $error_fields .= $field . ', ';
        }
    }

    if ($error) {
        // Required field(s) are missing or empty
        // echo error json and stop the app
        $response = array();
        $app = \Slim\Slim::getInstance();
        $response["error"] = true;
        $response["message"] = 'Required field(s) ' . substr($error_fields, 0, -2) . ' is missing or empty';
        echoRespnse(400, $response);
        $app->stop();
    }
}

/**
 * Echoing json response to client
 * @param String $status_code Http response code
 * @param Int $response Json response
 */
function echoRespnse($status_code, $response) {
    $app = \Slim\Slim::getInstance();
    // Http response code
    $app->status($status_code);

    // setting response content type to json
    $app->contentType('application/json');

    echo json_encode($response);
}

$app->run();
?>
