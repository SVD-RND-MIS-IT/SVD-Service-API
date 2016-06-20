<?php
require_once '../../model/user_management/OperationalUserManagement.php';
require_once '../../model/thurunu_saviya_managment/Grade9ThurunuSaviyaManagement.php';
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
 * ------------------------ PROJECT TABLE METHODS ------------------------
 */
 
/**
 * Project Registration
 * url - /project_register
 * method - POST
 * params - pro_name, pro_discription, pro_PDF_path, pro_supervisor_id
 */
$app->post('/grade_9_thurunu_saviya_register',  'authenticate', function() use ($app) {
	
            // check for required params
            verifyRequiredParams(array('stu_admission_number', 'year', 'ts9_group' ));
			
			global $currunt_user_id;

            $response = array();

            // reading post params
            $stu_admission_number = $app->request->post('stu_admission_number');
            $year = $app->request->post('year');
			$ts9_group = $app->request->post('ts9_group');
			$ts9_daily_attendance = $app->request->post('ts9_daily_attendance');
			$ts9_poya_attendance = $app->request->post('ts9_poya_attendance');
			$ts9_recomendation = $app->request->post('ts9_recomendation');
			$ts9_evaluation_cri_1 = $app->request->post('ts9_evaluation_cri_1');
			$ts9_evaluation_cri_2 = $app->request->post('ts9_evaluation_cri_2');
			$ts9_evaluation_cri_3 = $app->request->post('ts9_evaluation_cri_3');
			$ts9_evaluation_cri_4 = $app->request->post('ts9_evaluation_cri_4');
			$ts9_evaluation_cri_5 = $app->request->post('ts9_evaluation_cri_5');
			$ts9_evaluation_cri_6 = $app->request->post('ts9_evaluation_cri_6');
			$ts9_evaluation_cri_7 = $app->request->post('ts9_evaluation_cri_7');
			$ts9_evaluation_cri_8 = $app->request->post('ts9_evaluation_cri_8');
			$ts9_evaluation_cri_9 = $app->request->post('ts9_evaluation_cri_9');
			$ts9_evaluation_cri_10 = $app->request->post('ts9_evaluation_cri_10');
			$ts9_evaluation_cri_11 = $app->request->post('ts9_evaluation_cri_11');
			$ts9_evaluation_cri_12 = $app->request->post('ts9_evaluation_cri_12');
			$ts9_evaluation_cri_13 = $app->request->post('ts9_evaluation_cri_13');
			$ts9_evaluation_cri_14 = $app->request->post('ts9_evaluation_cri_14');
			$ts9_evaluation_cri_15 = $app->request->post('ts9_evaluation_cri_15');
			$ts9_evaluation_cri_16 = $app->request->post('ts9_evaluation_cri_16');
			$ts9_evaluation_cri_17 = $app->request->post('ts9_evaluation_cri_17');
			$ts9_evaluation_cri_18 = $app->request->post('ts9_evaluation_cri_18');
			$ts9_evaluation_cri_19 = $app->request->post('ts9_evaluation_cri_19');
			$ts9_evaluation_cri_20 = $app->request->post('ts9_evaluation_cri_20');
           
            $grade9ThurunuSaviyaManagement = new Grade9ThurunuSaviyaManagement();
			$res = $grade9ThurunuSaviyaManagement->createGrade9ThurunuSaviya($stu_admission_number, $year, $ts9_group, $ts9_daily_attendance, $ts9_poya_attendance, $ts9_recomendation, $ts9_evaluation_cri_1, $ts9_evaluation_cri_2, $ts9_evaluation_cri_3, $ts9_evaluation_cri_4, $ts9_evaluation_cri_5, $ts9_evaluation_cri_6, $ts9_evaluation_cri_7, $ts9_evaluation_cri_8, $ts9_evaluation_cri_9, $ts9_evaluation_cri_10, $ts9_evaluation_cri_11, $ts9_evaluation_cri_12, $ts9_evaluation_cri_13, $ts9_evaluation_cri_14, $ts9_evaluation_cri_15, $ts9_evaluation_cri_16, $ts9_evaluation_cri_17, $ts9_evaluation_cri_18, $ts9_evaluation_cri_19, $ts9_evaluation_cri_20,1);
			
            if ($res == CREATED_SUCCESSFULLY) {
                $response["error"] = false;
                $response["message"] = "Project is successfully registered";
            } else if ($res == CREATE_FAILED) {
                $response["error"] = true;
                $response["message"] = "Oops! An error occurred while registereing project";
            } else if ($res == ALREADY_EXISTED) {
                $response["error"] = true;
                $response["message"] = "Sorry, this project already exist";
            }
            // echo json response
            echoRespnse(201, $response);
        });

/**
 * Project Update
 * url - /project_update
 * method - PUT
 * params - pro_name, pro_discription, pro_PDF_path, pro_supervisor_id
 */
$app->put('/project_update',  'authenticate', function() use ($app) {
	
            // check for required params
            verifyRequiredParams(array( 'pro_name','pro_discription', 'pro_PDF_path', 'pro_supervisor_id'));
			
			global $currunt_user_id;

            $response = array();

            // reading put params
			$pro_name = $app->request->put('pro_name'); 
            $pro_discription = $app->request->put('pro_discription'); 
			$pro_PDF_path = $app->request->put('pro_PDF_path'); 
			$pro_supervisor_id = $app->request->put('pro_supervisor_id');

            $projectManagement = new ProjectManagement();
			$res = $projectManagement->updateProject($pro_name, $pro_discription, $pro_PDF_path, $pro_supervisor_id, $currunt_user_id);
			
            if ($res == UPDATE_SUCCESSFULLY) {
                $response["error"] = false;
                $response["message"] = "You are successfully updated Project";
            } else if ($res == UPDATE_FAILED) {
                $response["error"] = true;
                $response["message"] = "Oops! An error occurred while updating Project";
            } else if ($res == NOT_EXISTED) {
                $response["error"] = true;
                $response["message"] = "Sorry, this Project is not exist";
            }
            // echo json response
            echoRespnse(201, $response);
        });


/**
 * Project Delete
 * url - /project_delete
 * method - DELETE
 * params - pro_name
 */
$app->delete('/project_delete', 'authenticate', function() use ($app) {
	
            // check for required params
            verifyRequiredParams(array( 'pro_name'));
			
			global $currunt_user_id;

			// reading put params
			$pro_name = $app->request->delete('pro_name'); 
			
            $response = array();

			
            $projectManagement = new ProjectManagement();
			$res = $projectManagement->deleteProject($pro_name, $currunt_user_id);
			
            if ($res == DELETE_SUCCESSFULLY) {
                $response["error"] = false;
                $response["message"] = "Project is successfully deleted";
            } else if ($res == DELETE_FAILED) {
                $response["error"] = true;
                $response["message"] = "Oops! An error occurred while deleting project";
            } else if ($res == NOT_EXISTED) {
                $response["error"] = true;
                $response["message"] = "Sorry, this project is not exist";
            }
            // echo json response
            echoRespnse(201, $response);
        });


		
/**
 * get one project
 * method GET
 * url /project/:projectName          
 */
$app->get('/project/:projectName', 'authenticate', function($pro_name) {
            global $currunt_user_id;
            $response = array();
            
			$projectManagement = new ProjectManagement();
			$res = $projectManagement->getProjectByProjectName($pro_name);

            $response["error"] = false;
            $response["project"] = $res;

            

            echoRespnse(200, $response);
        });

/**
 * Listing all projects
 * method GET
 * url /projects        
 */
$app->get('/projects', 'authenticate', function() {
            global $user_id;
			
            $response = array();
			
            $projectManagement = new ProjectManagement();
			$res = $projectManagement->getAllProjects();

            $response["error"] = false;
            $response["project"] = array();

            // looping through result and preparing projects array
            while ($project = $res->fetch_assoc()) {
                $tmp = array();
				
                $tmp["pro_name"] = $project["pro_name"];
                $tmp["pro_discription"] = $project["pro_discription"];
				$tmp["pro_PDF_path"] = $project["pro_PDF_path"];
				$tmp["pro_supervisor_id"] = $project["pro_supervisor_id"];
                $tmp["status"] = $project["status"];
                $tmp["recode_added_at"] = $project["recode_added_at"];
				$tmp["recode_added_by"] = $project["recode_added_by"];
				
                array_push($response["project"], $tmp);
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