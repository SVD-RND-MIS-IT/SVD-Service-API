<?php
require_once '../../model/user_management/OperationalUserManagement.php';
require_once '../../model/prefect_managment/PrefectSectionManagement.php';
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
 * ------------------------ PREFECT_SECTION TABLE METHODS ------------------------
 */
 
/**
 * Prefect Section Registration
	 
 * method - POST
 * params - sec_name
 */
$app->post('/prefect_section_register',  'authenticate', function() use ($app) {
	
            // check for required params
            verifyRequiredParams(array('sec_name'));
			
			global $currunt_user_id;

            $response = array();

            // reading post params
            $sec_name = $app->request->post('sec_name');
         
           
            $prefectSectionManagement = new PrefectSectionManagement();
			$res = $prefectSectionManagement->createPrefectSection($sec_name,$currunt_user_id);
			
            if ($res == CREATED_SUCCESSFULLY) {
                $response["error"] = false;
                $response["message"] = "Prefect section is successfully registered";
            } else if ($res == CREATE_FAILED) {
                $response["error"] = true;
                $response["message"] = "Oops! An error occurred while registereing prefect section";
            } else if ($res == ALREADY_EXISTED) {
                $response["error"] = true;
                $response["message"] = "Sorry, this prefect section already exist";
            }
            // echo json response
            echoRespnse(201, $response);
        });




/**
 * Prefect Section Delete
 * url - /prefect_section_delete
 * method - DELETE
 * params - sec_name
 */
$app->delete('/prefect_section_delete', 'authenticate', function() use ($app) {
	
            // check for required params
            verifyRequiredParams(array('sec_name'));
			
			global $currunt_user_id;

            $response = array();

			// reading post params
            $sec_name = $app->request->delete('sec_name');
			
            $prefectSectionManagement= new PrefectSectionManagement();
			$res = $prefectSectionManagement->deletePrefectSection($sec_name, $currunt_user_id);
			
            if ($res == DELETE_SUCCESSFULLY) {
                $response["error"] = false;
                $response["message"] = "Section is successfully deleted";
            } else if ($res == DELETE_FAILED) {
                $response["error"] = true;
                $response["message"] = "Oops! An error occurred while deleting section";
            } else if ($res == NOT_EXISTED) {
                $response["error"] = true;
                $response["message"] = "Sorry, this section is not exist";
            }
            // echo json response
            echoRespnse(201, $response);
        });


		
/**
 * get one prefect possition
 * method GET
 * url /prefectPossition/:possitionsName       
 */
$app->get('/prefectPossition/:pos_name', 'authenticate', function($pos_name) {
            global $currunt_user_id;
            $response = array();
            
			$prefectPossitionManagement = new PrefectPossitionManagement();
			$res = $prefectPossitionManagement->getPossitionByPossitionName($pos_name);

            $response["error"] = false;
            $response["possition"] = $res;

            

            echoRespnse(200, $response);
        });

/**
 * Listing all prefect possitions
 * method GET
 * url /prefectPossitions        
 */
$app->get('/prefectPossitions', 'authenticate', function() {
            global $user_id;
			
            $response = array();
			
            $prefectPossitionManagement = new PrefectPossitionManagement();
			$res = $prefectPossitionManagement->getAllPossitions();

            $response["error"] = false;
            $response["possitions"] = array();

            // looping through result and preparing possitions array
            while ($possitions = $res->fetch_assoc()) {
                $tmp = array();
				
                $tmp["pos_name"] = $possitions["pos_name"];
                $tmp["status"] = $possitions["status"];
                $tmp["recode_added_at"] = $possitions["recode_added_at"];
				$tmp["recode_added_by"] = $possitions["recode_added_by"];
				
                array_push($response["possitions"], $tmp);
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