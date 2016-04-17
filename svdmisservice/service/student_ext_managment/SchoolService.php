<?php
require_once '../../model/user_management/OperationalUserManagement.php';
require_once '../../model/student_ext_managment/SchoolManagement.php';
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
$app->post('/school_register',  function() use ($app) {
	
            // check for required params
            verifyRequiredParams(array('sch_name', 'sch_situated_in' ));
			
			global $currunt_user_id;

            $response = array();

            // reading post params
            $sch_name = $app->request->post('sch_name');
			$sch_situated_in = $app->request->post('sch_situated_in');
         
           
            $schoolManagement = new SchoolManagement();
			$res = $schoolManagement->createSchool($sch_name, $sch_situated_in, 1);
			
            if ($res == CREATED_SUCCESSFULLY) {
                $response["error"] = false;
                $response["message"] = "School is successfully registered";
            } else if ($res == CREATE_FAILED) {
                $response["error"] = true;
                $response["message"] = "Oops! An error occurred while registereing school";
            } else if ($res == ALREADY_EXISTED) {
                $response["error"] = true;
                $response["message"] = "Sorry, this school already exist";
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
			
            if ($res == DELETE_SUCCESSFULLY) {
                $response["error"] = false;
                $response["message"] = "Talant is successfully deleted";
            } else if ($res == DELETE_FAILED) {
                $response["error"] = true;
                $response["message"] = "Oops! An error occurred while deleting talant";
            } else if ($res == NOT_EXISTED) {
                $response["error"] = true;
                $response["message"] = "Sorry, this talant is not exist";
            }
            // echo json response
            echoRespnse(201, $response);
        });


		
/**
 * get one talant
 * method GET
 * url /talant/:talantsName       
 */
$app->get('/talant/:tal_name', 'authenticate', function($tal_name) {
            global $currunt_user_id;
            $response = array();
            
			$talantsManagement = new TalantsManagement();
			$res = $talantsManagement->getTalantByTalantName($tal_name);

            $response["error"] = false;
            $response["talant"] = $res;

            

            echoRespnse(200, $response);
        });

/**
 * Listing all talants
 * method GET
 * url /talants        
 */
$app->get('/schools', function() {
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