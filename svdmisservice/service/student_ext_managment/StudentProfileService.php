<?php
require_once '../../model/user_management/OperationalUserManagement.php';
require_once '../../model/student_ext_managment/StudentProfileManagement.php';
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
 * get one talant
 * method GET
 * url /talant/:talantsName       
 */
$app->post('/student_horizontal_analisis',  function() use ($app)  {
            
			// check for required params
            verifyRequiredParams(array('repotYear', 'greads', 'classes'));
			
			 $response = array();
			
			// reading post params
            $repotYear = $app->request->post('repotYear');
			$greads = $app->request->post('greads');
			$classes = $app->request->post('classes');
			
			$studentProfileManagement = new StudentProfileManagement();
			$res1 = $studentProfileManagement->getStudentAnalisis_Behavior_Details($repotYear, $greads, $classes);
			$res2 = $studentProfileManagement->getStudentAnalisis_Recomendations_Details($repotYear, $greads, $classes);
			$res3 = $studentProfileManagement->getStudentAnalisis_Recomendations_count_Details($repotYear, $greads, $classes);
			$res4 = $studentProfileManagement->getStudentAnalisis_Desies_Details($repotYear, $greads, $classes);
			$res5 = $studentProfileManagement->getStudentAnalisis_Desies_count_Details($repotYear, $greads, $classes);
			$res6 = $studentProfileManagement->getStudentAnalisis_School_Details($repotYear, $greads, $classes);
			$res7 = $studentProfileManagement->getStudentAnalisis_City_Details($repotYear, $greads, $classes);
			$res8 = $studentProfileManagement->getStudentAnalisis_Distanse_Details($repotYear, $greads, $classes);
			$res9 = $studentProfileManagement->getStudentAnalisis_Far_Occupation_Details($repotYear, $greads, $classes);
			$res10 = $studentProfileManagement->getStudentAnalisis_Mot_Occupation_Details($repotYear, $greads, $classes);
			$res11 = $studentProfileManagement->getStudentAnalisis_Gur_Occupation_Details($repotYear, $greads, $classes);
			$res12 = $studentProfileManagement->getStudentAnalisis_No_Father_Details($repotYear, $greads, $classes);
			$res13 = $studentProfileManagement->getStudentAnalisis_No_Mother_Details($repotYear, $greads, $classes);
			$res14 = $studentProfileManagement->getStudentAnalisis_No_Parent_Details($repotYear, $greads, $classes);

            $response["error"] = true;
            $response["behavior"] = null;
			$response["reccomendation"] = array();
			$response["reccomendation_count"] = null;
			$response["desies"] = array();
			$response["desies_count"] = array();
			$response["schools"] = array();
			$response["city"] = array();
			$response["distence"] = null;
			$response["far_occ"] = array();
			$response["mot_occ"] = array();
			$response["gur_occ"] = array();
			$response["no_far"] = array();
			$response["no_mot"] = array();
			$response["no_gur"] = array();
			
			while ($behavior = $res1->fetch_assoc()) {
				$response["behavior"] = $behavior;
            }
			
			while ($reccomendation = $res2->fetch_assoc()) {
				array_push($response["reccomendation"], $reccomendation);
            }
			
			while ($reccomendation_count = $res3->fetch_assoc()) {
				$response["reccomendation_count"] = $reccomendation_count;
            }
			
			while ($desies = $res4->fetch_assoc()) {
                
				$response["error"] = false;
				array_push($response["desies"], $desies);
            }
			
			while ($desies_count = $res5->fetch_assoc()) {
				array_push($response["desies_count"], $desies_count);
            }
			
			while ($schools = $res6->fetch_assoc()) {
				array_push($response["schools"], $schools);
            }
			
			while ($city = $res7->fetch_assoc()) {
				array_push($response["city"], $city);
            }
			
			while ($distence = $res8->fetch_assoc()) {
				$response["distence"] = $distence;
            }
			
			while ($far_occ = $res9->fetch_assoc()) {
				array_push($response["far_occ"], $far_occ);
            }
			while ($mot_occ = $res10->fetch_assoc()) {
				array_push($response["mot_occ"], $mot_occ);
            }
			while ($gur_occ = $res11->fetch_assoc()) {
				array_push($response["gur_occ"], $gur_occ);
            }
			
			while ($no_far = $res12->fetch_assoc()) {
				array_push($response["no_far"], $no_far);
            }
			while ($no_mot = $res13->fetch_assoc()) {
				array_push($response["no_mot"], $no_mot);
            }
			while ($no_gur = $res14->fetch_assoc()) {
				array_push($response["no_gur"], $no_gur);
            }
            

            echoRespnse(200, $response);
        });

		
/**
 * get one talant
 * method GET
 * url /talant/:talantsName       
 */
$app->get('/student_personal_and_thurunusaviya/:stu_admission_number',  function($stu_admission_number) {

            $response = array();
            
			$studentProfileManagement = new StudentProfileManagement();
			$res = $studentProfileManagement->getStudentPersonalDetails($stu_admission_number);

            $response["error"] = true;
            $response["student"] = null;
			
			while ($student = $res->fetch_assoc()) {
                
				$response["error"] = false;
				$response["student"] = $student;
            }
            

            echoRespnse(200, $response);
        });
		
/**
 * get one talant
 * method GET
 * url /talant/:talantsName       
 */
$app->get('/student_schools/:stu_admission_number',  function($stu_admission_number) {

           $response = array();
            
			$studentProfileManagement = new StudentProfileManagement();
			$res = $studentProfileManagement->getStudentSchools($stu_admission_number);

            $response["error"] = false;
            $response["student_schools"] = array();
			
			while ($student_schools = $res->fetch_assoc()) {

				array_push($response["student_schools"], $student_schools);
            }
            

            echoRespnse(200, $response);
        });

		
/**
 * get one talant
 * method GET
 * url /talant/:talantsName       
 */
$app->get('/student_diseases/:stu_admission_number',  function($stu_admission_number) {

           $response = array();
            
			$studentProfileManagement = new StudentProfileManagement();
			$res = $studentProfileManagement->getStudentDesises($stu_admission_number);

            $response["error"] = false;
            $response["student_diseases"] = array();
			
			while ($student_diseases = $res->fetch_assoc()) {

				array_push($response["student_diseases"], $student_diseases);
            }
            

            echoRespnse(200, $response);
        });


/**
 * get one talant
 * method GET
 * url /talant/:talantsName       
 */
$app->get('/student_class_prefect/:stu_admission_number',  function($stu_admission_number) {

           $response = array();
            
			$studentProfileManagement = new StudentProfileManagement();
			$res = $studentProfileManagement->getStudentClass($stu_admission_number);

            $response["error"] = false;
            $response["student_class_prefect"] = array();
			
			while ($student_class_prefect = $res->fetch_assoc()) {

				array_push($response["student_class_prefect"], $student_class_prefect);
            }
            

            echoRespnse(200, $response);
        });

		
/**
 * get one talant
 * method GET
 * url /talant/:talantsName       
 */
$app->get('/student_reccomendations/:stu_admission_number',  function($stu_admission_number) {

           $response = array();
            
			$studentProfileManagement = new StudentProfileManagement();
			$res = $studentProfileManagement->getStudentReccomendation($stu_admission_number);

            $response["error"] = false;
            $response["student_reccomendations"] = array();
			
			while ($student_reccomendations = $res->fetch_assoc()) {

				array_push($response["student_reccomendations"], $student_reccomendations);
            }
            

            echoRespnse(200, $response);
        });

/**
 * get one talant
 * method GET
 * url /talant/:talantsName       
 */
$app->get('/student_exam_results/:stu_admission_number',  function($stu_admission_number) {

           $response = array();
            
			$studentProfileManagement = new StudentProfileManagement();
			$res = $studentProfileManagement->getStudentExamResults($stu_admission_number);

            $response["error"] = false;
            $response["student_exam_results"] = array();
			
			while ($student_exam_results = $res->fetch_assoc()) {

				array_push($response["student_exam_results"], $student_exam_results);
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