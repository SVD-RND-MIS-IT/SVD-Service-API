<?php
require_once '../../model/user_management/UserCategoryManagement.php';
require_once '../../model/user_management/OperationalUserManagement.php';
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

/**
 * ----------- METHODS WITHOUT AUTHENTICATION ---------------------------------
 */
 
 //still no methods found

/*
 * ------------------------ METHODS WITH AUTHENTICATION ------------------------
 */
/**
 * Operational User Type Registration
 * url - /user_category_register
 * method - POST
 * params - ucat_name, ucat_description
 */
$app->post('/user_category_register',  'authenticate', function() use ($app) {

            // check for required params
            verifyRequiredParams(array('ucat_name', 'ucat_description'));
			
			global $currunt_user_id;

            $response = array();

            // reading post params
            $ucat_name = $app->request->post('ucat_name');
            $ucat_description = $app->request->post('ucat_description');
			

            $userCategoryManagement = new UserCategoryManagement();
			$res = $userCategoryManagement->createUserCategory($ucat_name, $ucat_description,$currunt_user_id);
			
            if ($res == USER_CATEGORY_CREATED_SUCCESSFULLY) {
                $response["error"] = false;
                $response["message"] = "User category successfully registered";
            } else if ($res == USER_CATEGORY_CREATE_FAILED) {
                $response["error"] = true;
                $response["message"] = "Oops! An error occurred while registereing user category ";
            } else if ($res == USER_CATEGORY_ALREADY_EXISTED) {
                $response["error"] = true;
                $response["message"] = "Sorry, this user category  already exist";
            }
            // echo json response
            echoRespnse(201, $response);
        });

/**
 * Operational User Update
 * url - /operational_user_update
 * method - PUT
 * params - usr_name, usr_pwd, usr_full_name, usr_email, usr_phone_number, usr_category
 */
$app->put('/operational_user_update/:userName',  'authenticate', function($usr_name) use ($app) {
	
			require_once '../model/commen/Validations.php';
            // check for required params
            verifyRequiredParams(array( 'usr_pwd', 'usr_full_name','usr_email', 'usr_phone_number', 'usr_category'));
			
			global $currunt_user_id;

            $response = array();

            // reading put params
            //$usr_name = $app->request->put('usr_name');
            $usr_pwd = $app->request->put('usr_pwd');
            $usr_full_name = $app->request->put('usr_full_name');
			$usr_email = $app->request->put('usr_email');
            $usr_phone_number = $app->request->put('usr_phone_number');
			$usr_category = $app->request->put('usr_category');
			
			$validations = new Validations();

            // validating email address
            $validations->validateEmail($usr_email);

            $operationalUserManagement = new OperationalUserManagement();
			$res = $operationalUserManagement->updateOperationalUser($usr_name, $usr_pwd, $usr_full_name,$usr_email, $usr_phone_number, $usr_category,$currunt_user_id);
			
            if ($res == USER_UPDATE_SUCCESSFULLY) {
                $response["error"] = false;
                $response["message"] = "You are successfully updated";
            } else if ($res == USER_UPDATE_FAILED) {
                $response["error"] = true;
                $response["message"] = "Oops! An error occurred while updating";
            } else if ($res == USER_NOT_EXISTED) {
                $response["error"] = true;
                $response["message"] = "Sorry, this user is not exist";
            }
            // echo json response
            echoRespnse(201, $response);
        });


/**
 * Operational User Delete
 * url - /operational_user_delete
 * method - DELETE
 * params - usr_name
 */
$app->delete('/operational_user_delete/:userName', 'authenticate', function($usr_name) use ($app) {
	
            
			global $currunt_user_id;

            $response = array();

			
            $operationalUserManagement = new OperationalUserManagement();
			$res = $operationalUserManagement->deleteOperationalUser($currunt_user_id,$usr_name);
			
            if ($res == USER_DELETE_SUCCESSFULLY) {
                $response["error"] = false;
                $response["message"] = "You are successfully deleted";
            } else if ($res == USER_DELETE_FAILED) {
                $response["error"] = true;
                $response["message"] = "Oops! An error occurred while deleting";
            } else if ($res == USER_NOT_EXISTED) {
                $response["error"] = true;
                $response["message"] = "Sorry, this user is not exist";
            }
            // echo json response
            echoRespnse(201, $response);
        });

		
/**
 * Listing all tasks of particual user
 * method GET
 * url /tasks          
 */
$app->get('/operational_user/:userName', 'authenticate', function($usr_name) {
            global $currunt_user_id;
            $response = array();
            
			$operationalUserManagement = new OperationalUserManagement();
			$res = $operationalUserManagement->getUserByUserName($usr_name);

            $response["error"] = false;
            $response["user"] = $res;

            

            echoRespnse(200, $response);
        });

/**
 * Listing all tasks of particual user
 * method GET
 * url /tasks          
 */
$app->get('/user_categories', 'authenticate', function() {
            global $user_id;
			
            $response = array();
			
            $userCategoryManagement = new UserCategoryManagement();

            // fetching all users
            $result = $userCategoryManagement->getAllUserCategory();

            $response["error"] = false;
            $response["user_category"] = array();

            // looping through result and preparing tasks array
            while ($user_category = $result->fetch_assoc()) {
                $tmp = array();
				
                $tmp["ucat_id"] = $user_category["ucat_id"];
                $tmp["ucat_name"] = $user_category["ucat_name"];
                $tmp["ucat_description"] = $user_category["ucat_description"];
                $tmp["status"] = $user_category["status"];
				$tmp["recode_added_at"] = $user_category["recode_added_at"];
                $tmp["recode_added_by"] = $user_category["recode_added_by"];

				
				
                array_push($response["user_category"], $tmp);
            }

            echoRespnse(200, $response);
        });		
				

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
 * Validating email address
 */
function validateEmail($email) {
    $app = \Slim\Slim::getInstance();
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response["error"] = true;
        $response["message"] = 'Email address is not valid';
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