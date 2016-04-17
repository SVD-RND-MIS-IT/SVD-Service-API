<?php
require_once '../../model/commen/PassHash.php';
/**
 * Class to handle all the talant details
 * This class will have CRUD methods for talant
 *
 * @author Bagya
 *
 */

class StudentManagement {

    private $conn;

    function __construct() {
        require_once '../../model/commen/DbConnect.php';
        // opening db connection
        $db = new DbConnect();
        $this->conn = $db->connect();
    }
	
	
/*
 * ------------------------ SCHOOL TABLE METHODS ------------------------
 */

    /**
     * Creating new school
     *
     * @param String $sch_name School name
	 * @param String $sch_situated_in town of the school
	 * @param String $recode_added_by 
     *
     * @return database transaction status
     */
    public function createStudent($stu_admission_number, $stu_full_name, $stu_name_with_initisals, $stu_gender, $stu_date_of_birth, $stu_land_phone_number, $stu_mobile_number, $stu_address, $stu_city, $distance_to_home, $father_id, $mother_id, $guardian_id, $stu_email_address, $stu_nic_number, $recode_added_by) {

        $response = array();
		
        // First check if Talant already existed in db
        if (!$this->isStudentExists($stu_admission_number)) {
  
            // insert query
			 $stmt = $this->conn->prepare("INSERT INTO student(stu_admission_number, stu_full_name, stu_name_with_initisals, stu_gender, stu_date_of_birth, stu_land_phone_number, stu_mobile_number, stu_address, stu_city, distance_to_home, father_id, mother_id, guardian_id, stu_email_address, stu_nic_number, recode_added_by) values(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
			 $stmt->bind_param("sssssssssiiiissi", $stu_admission_number, $stu_full_name, $stu_name_with_initisals, $stu_gender, $stu_date_of_birth, $stu_land_phone_number, $stu_mobile_number, $stu_address, $stu_city, $distance_to_home, $father_id, $mother_id, $guardian_id, $stu_email_address, $stu_nic_number, $recode_added_by );
			 $result = $stmt->execute();
			 $stmt->close();
        } else {
            // School is already existed in the db
            return ALREADY_EXISTED;
        }
		
         

        // Check for successful insertion
        if ($result) {
			$stmt = $this->conn->prepare("SELECT LAST_INSERT_ID();");
			if ($stmt->execute()) {
				$stmt->bind_result($LAST_INSERT_ID);
				$stmt->fetch();
				$stmt->close();
				return $LAST_INSERT_ID;
			} else {
				return CREATE_FAILED;
			}
        } else {
            // Failed to create talant
            return CREATE_FAILED;
        }
        
		return $response;

    }
	
	
	
/**
     * Delete talant
     *
     * @param String $tal_name Talant name for the system
	 * @param String $recode_added_by
     *
     * @return database transaction status
     */
    public function deleteTalant($tal_name, $recode_added_by) {

		
        $response = array();
        // First check if talant already existed in db
        if ($this->isTalantExists($tal_name)) {
           			
			//
			$stmt = $this->conn->prepare("UPDATE talants set status = 3, recode_modified_at = now() , recode_modified_by = ? where tal_name = ? and (status=1 or  status=2)");
			$stmt->bind_param("is",$recode_added_by, $tal_name);
			$result = $stmt->execute();
			
            $stmt->close();

        } else {
            // Talant is not already existed in the db
            return NOT_EXISTED;
        }
		
         

        // Check for successful insertion
        if ($result) {
			// talant successfully deleted
            return DELETE_SUCCESSFULLY;
        } else {
            // Failed to delete talant
            return DELETE_FAILED;
        }
        
		return $response;

    }
	  
	/**
     * Fetching talants by tal_name
	 *
     * @param String $tal_name tal name
	 *
	 *@return talant object only needed data
     */
    public function getStudentByStudentAdmissionNumber($stu_admission_number) {
        $stmt = $this->conn->prepare("SELECT stu_id, stu_admission_number, stu_full_name, stu_name_with_initisals, stu_gender, stu_date_of_birth, stu_land_phone_number, stu_mobile_number, stu_address, stu_city, distance_to_home, father_id, mother_id, guardian_id, stu_email_address, stu_nic_number, ts_grade9_id, ts_grade10_id, ts_grade11_id, ts_np_id, lib_mem_id, status, recode_added_at, recode_added_by FROM student WHERE stu_admission_number = ? and (status=1 or status=2)");
        $stmt->bind_param("s", $stu_admission_number);
        if ($stmt->execute()) {
            $stmt->bind_result($stu_id, $stu_admission_number, $stu_full_name, $stu_name_with_initisals, $stu_gender, 
			$stu_date_of_birth, $stu_land_phone_number, $stu_mobile_number, $stu_address, $stu_city, $distance_to_home, $father_id,
			$mother_id, $guardian_id, $stu_email_address, $stu_nic_number, $ts_grade9_id, $ts_grade10_id, $ts_grade11_id, 
			$ts_np_id, $lib_mem_id, $status, $recode_added_at, $recode_added_by);
            $stmt->fetch();
            $student = array();
            $student["stu_id"] = $stu_id;
            $student["stu_admission_number"] = $stu_admission_number;
            $student["stu_full_name"] = $stu_full_name;
			$student["stu_name_with_initisals"] = $stu_name_with_initisals;
			$student["stu_gender"] = $stu_gender;
			$student["stu_date_of_birth"] = $stu_date_of_birth;
			$student["stu_land_phone_number"] = $stu_land_phone_number;
			$student["stu_mobile_number"] = $stu_mobile_number;
			$student["stu_address"] = $stu_address;
			$student["stu_city"] = $stu_city;
			$student["distance_to_home"] = $distance_to_home;
			$student["father_id"] = $father_id;
			$student["mother_id"] = $mother_id;
			$student["guardian_id"] = $guardian_id;
			$student["stu_email_address"] = $stu_email_address;
			$student["stu_nic_number"] = $stu_nic_number;
			$student["ts_grade9_id"] = $ts_grade9_id;
			$student["ts_grade10_id"] = $ts_grade10_id;
			$student["ts_grade11_id"] = $ts_grade11_id;
			$student["ts_np_id"] = $ts_np_id;
			$student["lib_mem_id"] = $lib_mem_id;
			$student["status"] = $status;
			$student["recode_added_at"] = $recode_added_at;
			$student["recode_added_by"] = $recode_added_by;

            $stmt->close();
            return $student;
        } else {
            return NULL;
        }
    }
  
  
	/**
     * Fetching all talants
	 *
     * @return $talant object set of all talants
     */
    public function getAllSchools() {
        $stmt = $this->conn->prepare("SELECT * FROM school WHERE (status = 1 or  status = 2) ORDER BY sch_name");
        $stmt->execute();
        $talants = $stmt->get_result();
        $stmt->close();
        return $talants;
    }
	
  
  
  
  
  
/*
 * ------------------------ SUPPORTIVE METHODS ------------------------
 */

	/**
     * Checking for duplicate schools by sch_name
     *
     * @param String $sch_name School name to check in db
     *
     * @return boolean
     */
    private function isStudentExists($stu_admission_number) {
		$stmt = $this->conn->prepare("SELECT stu_admission_number from student WHERE (status = 1 or status = 2)  and stu_admission_number = ?  ");
        $stmt->bind_param("s",$stu_admission_number);
        $stmt->execute();
		$stmt->store_result();
        $num_rows = $stmt->num_rows;
        $stmt->close();
        return ($num_rows > 0); //if it has more than zero number of rows; then  it sends true
    }

}

?>
