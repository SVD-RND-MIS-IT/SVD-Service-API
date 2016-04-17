<?php
require_once '../../model/commen/PassHash.php';
/**
 * Class to handle all the talant details
 * This class will have CRUD methods for talant
 *
 * @author Bagya
 *
 */

class StudentDiseasesManagement {

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
    public function createStudentDiseases($stu_id, $dis_id, $dis_found_year, $recode_added_by) {

        $response = array();
		
        // First check if Talant already existed in db
        if (!$this->isStudentDiseasesExists($stu_id, $dis_id)) {
  
            // insert query
			 $stmt = $this->conn->prepare("INSERT INTO student_diseases(stu_id, dis_id, dis_found_year, recode_added_by) values(?, ?, ?, ?)");
			 $stmt->bind_param("iiii", $stu_id, $dis_id, $dis_found_year, $recode_added_by );
			 $result = $stmt->execute();

			 $stmt->close();

        } else {
            // School is already existed in the db
            return ALREADY_EXISTED;
        }
		
         

        // Check for successful insertion
        if ($result) {
			// talant successfully inserted
            return CREATED_SUCCESSFULLY;
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
    public function getTalantByTalantName($tal_name) {
        $stmt = $this->conn->prepare("SELECT tal_name, status, recode_added_at, recode_added_by FROM talants WHERE tal_name = ? and (status=1 or status=2)");
        $stmt->bind_param("s", $tal_name);
        if ($stmt->execute()) {
            $stmt->bind_result($tal_name,$status, $recode_added_at, $recode_added_by);
            $stmt->fetch();
            $talant = array();
            $talant["tal_name"] = $tal_name;
            $talant["status"] = $status;
            $talant["recode_added_at"] = $recode_added_at;
			$talant["recode_added_by"] = $recode_added_by;

            $stmt->close();
            return $talant;
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
    private function isStudentDiseasesExists($stu_id, $dis_id) {
		$stmt = $this->conn->prepare("SELECT stu_id from student_diseases WHERE (status = 1 or status = 2)  and stu_id = ? and dis_id = ? ");
        $stmt->bind_param("ii", $stu_id, $dis_id);
        $stmt->execute();
		$stmt->store_result();
        $num_rows = $stmt->num_rows;
        $stmt->close();
        return ($num_rows > 0); //if it has more than zero number of rows; then  it sends true
    }

}

?>
