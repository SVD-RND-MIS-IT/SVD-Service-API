<?php
require_once '../../model/user_management/OperationalUserManagement.php';
require_once '../../model/students_class_managment/StudentClassManagement.php';
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
 * ------------------------ CLASS TABLE METHODS ------------------------
 */
 
/**
 * Class Registration
 * url - /class_register
 * method - POST
 * params - clz_grade, clz_class
 */
$app->post('/student_class_register',  function() use ($app) {
	
            // check for required params
            verifyRequiredParams(array('year', 'stu_id', 'clz_id'));
			
			global $currunt_user_id;

            $response = array();

            // reading post params
            $year = $app->request->post('year');
            $stu_id = $app->request->post('stu_id');
			$clz_id = $app->request->post('clz_id');
           
            $studentClassManagement = new StudentClassManagement();
			$res = $studentClassManagement->createStudentClass($year, $stu_id, $clz_id, 1);
			
            if ($res == CREATED_SUCCESSFULLY) {
                $response["error"] = false;
                $response["message"] = "StudentClass is successfully registered";
            } else if ($res == CREATE_FAILED) {
                $response["error"] = true;
                $response["message"] = "Oops! An error occurred while registereing StudentClass";
            } else if ($res == ALREADY_EXISTED) {
                $response["error"] = true;
                $response["message"] = "Sorry, this StudentClass already exist";
            }
            // echo json response
            echoRespnse(201, $response);
        });

/**
 * Exam Update
 * url - /exam_update/:examName
 * method - PUT
 * params - clz_grade, exm_discription
 */
$app->put('/exam_update/:examName',  'authenticate', function($clz_grade) use ($app) {
	
            // check for required params
            verifyRequiredParams(array( 'exm_discription'));
			
			global $currunt_user_id;

            $response = array();

            // reading put params
            $exm_discription = $app->request->put('exm_discription');

            $examManagement = new ExamManagement();
			$res = $examManagement->updateExam($exm_name, $exm_discription,$currunt_user_id);
			
            if ($res == UPDATE_SUCCESSFULLY) {
                $response["error"] = false;
                $response["message"] = "You are successfully updated exam";
            } else if ($res == UPDATE_FAILED) {
                $response["error"] = true;
                $response["message"] = "Oops! An error occurred while updating exam";
            } else if ($res == NOT_EXISTED) {
                $response["error"] = true;
                $response["message"] = "Sorry, this exam is not exist";
            }
            // echo json response
            echoRespnse(201, $response);
        });


/**
 * Exam Delete
 * url - /exam_delete
 * method - DELETE
 * params - exm_name/:examName
 */
$app->delete('/exam_delete/:examName', 'authenticate', function($clz_grade) use ($app) {
	
            
			global $currunt_user_id;

            $response = array();

			
            $examManagement = new ExamManagement();
			$res = $examManagement->deleteExam($exm_name, $currunt_user_id);
			
            if ($res == DELETE_SUCCESSFULLY) {
                $response["error"] = false;
                $response["message"] = "Exam is successfully deleted";
            } else if ($res == DELETE_FAILED) {
                $response["error"] = true;
                $response["message"] = "Oops! An error occurred while deleting exam";
            } else if ($res == NOT_EXISTED) {
                $response["error"] = true;
                $response["message"] = "Sorry, this exam is not exist";
            }
            // echo json response
            echoRespnse(201, $response);
        });


		
/**
 * get one exam
 * method GET
 * url /exam/:examName          
 */
$app->get('/exam/:examName', 'authenticate', function($clz_grade) {
            global $currunt_user_id;
            $response = array();
            
			$examManagement = new ExamManagement();
			$res = $examManagement->getExamByExamName($exm_name);

            $response["error"] = false;
            $response["exam"] = $res;

            

            echoRespnse(200, $response);
        });

/**
 * Listing all exams
 * method GET
 * url /exams        
 */
$app->get('/classes', 'authenticate', function() {
            global $user_id;
			
            $response = array();
			
            $classManagement = new ClassManagement();
			$res = $classManagement->getAllClasses();

            $response["error"] = false;
            $response["classes"] = array();

            // looping through result and preparing classes array
            while ($classes = $res->fetch_assoc()) {
                $tmp = array();
				
                $tmp["clz_id"] = $classes["clz_id"];
                $tmp["clz_grade"] = $classes["clz_grade"];
                $tmp["clz_class"] = $classes["clz_class"];

                array_push($response["classes"], $tmp);
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