<?php
require_once '../../model/user_management/OperationalUserManagement.php';
require_once '../../model/student_ext_managment/StudentManagement.php';
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
 * ------------------------ SCHOOL METHODS ------------------------
 */
 
/**
 * School Registration
 * url - /school_register
 * method - POST
 * params - sch_name, sch_situated_in
 */
$app->post('/student_register', function() use ($app) {
	
            // check for required params
            verifyRequiredParams(array('stu_admission_number', 'stu_full_name' , 'stu_name_with_initisals', 'stu_gender', 'stu_date_of_birth'));
			
			global $currunt_user_id;

            $response = array();

            // reading post params
            $stu_admission_number = $app->request->post('stu_admission_number');
			$stu_full_name = $app->request->post('stu_full_name');
			$stu_name_with_initisals = $app->request->post('stu_name_with_initisals');
			$stu_gender = $app->request->post('stu_gender');
			$stu_date_of_birth = $app->request->post('stu_date_of_birth');
			$stu_land_phone_number = $app->request->post('stu_land_phone_number');
			$stu_mobile_number = $app->request->post('stu_mobile_number');
			$stu_address = $app->request->post('stu_address');
			$stu_city = $app->request->post('stu_city');
			$distance_to_home = $app->request->post('distance_to_home');
			$father_id = $app->request->post('father_id');
			$mother_id = $app->request->post('mother_id');
			$guardian_id = $app->request->post('guardian_id');
			$stu_email_address = $app->request->post('stu_email_address');
			$stu_nic_number = $app->request->post('stu_nic_number');
           
            $studentManagement = new StudentManagement();
			$res = $studentManagement->createStudent($stu_admission_number, $stu_full_name, $stu_name_with_initisals, $stu_gender, $stu_date_of_birth, $stu_land_phone_number, $stu_mobile_number, $stu_address, $stu_city, $distance_to_home, $father_id, $mother_id, $guardian_id, $stu_email_address, $stu_nic_number, 1);
			
            if ($res == CREATED_SUCCESSFULLY) {
                $response["error"] = false;
                $response["message"] = "Student is successfully registered";
            } else if ($res == CREATE_FAILED) {
                $response["error"] = true;
                $response["message"] = "Oops! An error occurred while registereing Student";
            } else if ($res == ALREADY_EXISTED) {
                $response["error"] = true;
                $response["message"] = "Sorry, this Student already exist";
            }else{
				$response["error"] = false;
                $response["message"] = $res;
			}
            // echo json response
            echoRespnse(201, $response);
        });




/**
 * Talants Delete
 * url - /talants_delete
 * method - DELETE
 * params - tal_name
 */
$app->delete('/talants_delete', 'authenticate', function() use ($app) {
	
            // check for required params
            verifyRequiredParams(array('tal_name'));
			
			global $currunt_user_id;

            $response = array();

			// reading post params
            $tal_name = $app->request->delete('tal_name');
			
            $talantsManagement = new TalantsManagement();
			$res = $talantsManagement->deleteTalant($tal_name, $currunt_user_id);
			
            if ($res == DELETE_FAILED) {
                $response["error"] = true;
                $response["message"] = "Oops! An error occurred while deleting talant";
            } else if ($res == NOT_EXISTED) {
                $response["error"] = true;
                $response["message"] = "Sorry, this talant is not exist";
            }else {
				$response["error"] = false;
				$response["message"] = $res;
			}
            // echo json response
            echoRespnse(201, $response);
        });


		
/**
 * get one talant
 * method GET
 * url /talant/:talantsName       
 */
$app->get('/student/:stu_admission_number',  function($stu_admission_number) {

            $response = array();
            
			$studentManagement = new StudentManagement();
			$res = $studentManagement->getStudentByStudentAdmissionNumber($stu_admission_number);

            $response["error"] = false;
            $response["student"] = $res;

            

            echoRespnse(200, $response);
        });
		
/**
 * get one talant
 * method GET
 * url /talant/:talantsName       
 */
$app->get('/student_by_name/:stu_full_name',  function($stu_full_name) {

            $response = array();
            
			$studentManagement = new StudentManagement();
			$res = $studentManagement->getStudentByStudentFullName($stu_full_name);

            $response["error"] = false;
            $response["student"] = array();

            // looping through result and preparing student array
            while ($student = $res->fetch_assoc()) {
                $tmp = array();
				
                $tmp["stu_id"] = $student["stu_id"];
                $tmp["stu_admission_number"] = $student["stu_admission_number"];
                $tmp["stu_full_name"] = $student["stu_full_name"];
				$tmp["stu_name_with_initisals"] = $student["stu_name_with_initisals"];
				$tmp["name1"] = $student["name1"];
				$tmp["name2"] = $student["name2"];
				$tmp["name3"] = $student["name3"];
				$tmp["stu_gender"] = $student["stu_gender"];
				$tmp["stu_date_of_birth"] = $student["stu_date_of_birth"];
				$tmp["stu_address"] = $student["stu_address"];
				$tmp["stu_city"] = $student["stu_city"];
				
                array_push($response["student"], $tmp);
            }

            echoRespnse(200, $response);
        });

/**
 * Listing all talants
 * method GET
 * url /talants        
 */
$app->get('/schools', 'authenticate', function() {
            global $user_id;
			
            $response = array();
			
            $schoolManagement = new SchoolManagement();
			$res = $schoolManagement->getAllSchools();

            $response["error"] = false;
            $response["schools"] = array();

            // looping through result and preparing schools array
            while ($schools = $res->fetch_assoc()) {
                $tmp = array();
				
                $tmp["sch_id"] = $schools["sch_id"];
                $tmp["sch_name"] = $schools["sch_name"];
                $tmp["sch_situated_in"] = $schools["sch_situated_in"];
				$tmp["status"] = $schools["status"];
				
                array_push($response["schools"], $tmp);
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
	// Handling PUT request params
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