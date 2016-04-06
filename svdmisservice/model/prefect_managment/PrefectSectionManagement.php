<?php
require_once '../../model/commen/PassHash.php';
/**
 * Class to handle all the prefect section details
 * This class will have CRUD methods for prefect_section
 *
 * @author Bagya
 *
 */

class PrefectSectionManagement {

    private $conn;

    function __construct() {
        require_once '../../model/commen/DbConnect.php';
        // opening db connection
        $db = new DbConnect();
        $this->conn = $db->connect();
    }
	
	
/*
 * ------------------------ PREFECT_SECTION TABLE METHODS ------------------------
 */

    /**
     * Creating new prefect sections
     *
     * @param String $sec_name section name for the system
	 * @param String $recode_added_by 
     *
     * @return database transaction status
     */
    public function createPrefectSection($sec_name,$recode_added_by ) {

		
        $response = array();
		
        // First check if section already existed in db
        if (!$this->isSectionExists($sec_name)) {
  
            // insert query
			 $stmt = $this->conn->prepare("INSERT INTO prefect_section	 (sec_name, recode_added_by) values(?, ?)");
			 $stmt->bind_param("si", $sec_name, $recode_added_by );
			 $result = $stmt->execute();

			 $stmt->close();

        } else {
            // section is not already existed in the db
            return ALREADY_EXISTED;
        }
		
         

        // Check for successful insertion
        if ($result) {
			// section successfully inserted
            return CREATED_SUCCESSFULLY;
        } else {
            // Failed to create section
            return CREATE_FAILED;
        }
        
		return $response;

    }
	
	
	
/**
     * Delete prefect section
     *
     * @param String $sec_name Section name for the system
	 * @param String $recode_added_by
     *
     * @return database transaction status
     */
    public function deletePrefectSection($sec_name, $recode_added_by) {

		
        $response = array();
        // First check if section already existed in db
        if ($this->isSectionExists($sec_name)) {
           			
			//
			$stmt = $this->conn->prepare("UPDATE prefect_section set status = 3, recode_modified_at = now() , recode_modified_by = ? where sec_name = ? and (status=1 or  status=2)");
			$stmt->bind_param("is",$recode_added_by, $sec_name);
			$result = $stmt->execute();
			
            $stmt->close();

        } else {
            //Section is not already existed in the db
            return NOT_EXISTED;
        }
		
         

        // Check for successful insertion
        if ($result) {
			// section successfully deleted
            return DELETE_SUCCESSFULLY;
        } else {
            // Failed to delete section
            return DELETE_FAILED;
        }
        
		return $response;

    }
	  
	/**
     * Fetching prefect possition by pos_name
	 *
     * @param String $pos_name possition name
	 *
	 *@return prefect possition object only needed data
     */
    public function getPossitionByPossitionName($pos_name) {
        $stmt = $this->conn->prepare("SELECT pos_name, status, recode_added_at, recode_added_by FROM prefect_possition WHERE pos_name = ? and (status=1 or status=2)");
        $stmt->bind_param("s", $pos_name);
        if ($stmt->execute()) {
            $stmt->bind_result($pos_name,$status, $recode_added_at, $recode_added_by);
            $stmt->fetch();
            $possition = array();
            $possition["pos_name"] = $pos_name;
            $possition["status"] = $status;
            $possition["recode_added_at"] = $recode_added_at;
			$possition["recode_added_by"] = $recode_added_by;

            $stmt->close();
            return $possition;
        } else {
            return NULL;
        }
    }
  
  
	/**
     * Fetching all prefect possitions
	 *
     * @return $possition object set of all possitions
     */
    public function getAllPossitions() {
        $stmt = $this->conn->prepare("SELECT * FROM prefect_possition WHERE status = 1 or  status = 2");
        $stmt->execute();
        $possitions = $stmt->get_result();
        $stmt->close();
        return $possitions;
    }
	
  
  
  
  
  
/*
 * ------------------------ SUPPORTIVE METHODS ------------------------
 */

	/**
     * Checking for duplicate sections by sec_name
     *
     * @param String $sec_name Possition name to check in db
     *
     * @return boolean
     */
    private function isSectionExists($sec_name) {
		$stmt = $this->conn->prepare("SELECT sec_name from prefect_section WHERE (status = 1 or status = 2)  and sec_name = ?  ");
        $stmt->bind_param("s",$sec_name);
        $stmt->execute();
		$stmt->store_result();
        $num_rows = $stmt->num_rows;
        $stmt->close();
        return ($num_rows > 0); //if it has more than zero number of rows; then  it sends true
    }

}

?>
