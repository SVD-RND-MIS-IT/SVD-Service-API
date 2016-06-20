<?php
/**
 * Class to handle all the exam details
 * This class will have CRUD methods for exam
 *
 * @author Chathuri Gunarathna
 *
 */

class StudentBehaviorManagement {

    private $conn;

    function __construct() {
        require_once '../../model/commen/DbConnect.php';
        // opening db connection
        $db = new DbConnect();
        $this->conn = $db->connect();
    }
	
	
/*
 * ------------------------ OCCUPATION TYPE TABLE METHODS ------------------------
 */

    /**
     * Creating new Occupation_type
     *
     * @param String $occ_type_name Occupation_type name
     * @param String $occ_type_description Occupation_type Discription
	 * @param String $recode_added_by 
     *
     * @return database transaction status
     */
    public function createStudentBehavior($stu_admission_number, $year, $clz_evaluation_cri_1, $clz_evaluation_cri_2, $clz_evaluation_cri_3, $clz_evaluation_cri_4, $clz_evaluation_cri_5, $clz_evaluation_cri_6, $clz_evaluation_cri_7, $clz_evaluation_cri_8, $clz_evaluation_cri_9, $clz_evaluation_cri_10, $clz_evaluation_cri_11, $clz_evaluation_cri_12, $clz_evaluation_cri_13, $clz_evaluation_cri_14, $clz_evaluation_cri_15, $clz_evaluation_cri_16, $clz_evaluation_cri_17, $clz_evaluation_cri_18, $clz_evaluation_cri_19, $clz_evaluation_cri_20, $clz_evaluation_cri_21, $clz_evaluation_cri_22, $clz_evaluation_cri_23, $clz_evaluation_cri_24, $clz_evaluation_cri_25, $clz_evaluation_cri_26, $clz_evaluation_cri_27, $clz_evaluation_cri_28, $clz_evaluation_cri_29, $clz_evaluation_cri_30, $clz_evaluation_cri_31, $clz_evaluation_cri_32, $clz_evaluation_cri_33, $clz_evaluation_cri_34, $clz_evaluation_cri_35, $clz_evaluation_cri_36, $clz_evaluation_cri_37, $clz_evaluation_cri_38, $clz_evaluation_cri_39, $clz_evaluation_cri_40, $clz_evaluation_cri_41, $clz_evaluation_cri_42, $clz_evaluation_cri_43, $clz_evaluation_cri_44, $clz_evaluation_cri_45, $clz_evaluation_cri_46, $clz_evaluation_cri_47, $clz_evaluation_cri_48, $clz_evaluation_cri_49, $clz_evaluation_cri_50, $clz_evaluation_cri_51, $clz_evaluation_cri_52, $clz_evaluation_cri_53, $clz_evaluation_cri_54, $clz_evaluation_cri_55, $clz_evaluation_cri_56, $clz_evaluation_cri_57, $clz_evaluation_cri_58, $clz_evaluation_cri_59, $clz_evaluation_cri_60, $clz_evaluation_cri_61, $clz_evaluation_cri_62, $clz_evaluation_cri_63, $clz_evaluation_cri_64, $clz_evaluation_cri_65, $clz_evaluation_cri_66, $clz_evaluation_cri_67, $clz_evaluation_cri_68, $clz_evaluation_cri_69, $clz_evaluation_cri_70, $clz_evaluation_cri_71, $clz_evaluation_cri_72, $clz_evaluation_cri_73, $clz_evaluation_cri_74, $clz_evaluation_cri_75, $clz_evaluation_cri_76, $clz_evaluation_cri_77, $clz_evaluation_cri_78, $clz_evaluation_cri_79, $clz_evaluation_cri_80, $clz_evaluation_cri_1_copy1, $clz_evaluation_cri_2_copy1, $clz_evaluation_cri_3_copy1, $clz_evaluation_cri_4_copy1, $clz_evaluation_cri_5_copy1, $clz_evaluation_cri_6_copy1, $clz_evaluation_cri_7_copy1, $clz_evaluation_cri_8_copy1, $clz_evaluation_cri_9_copy1, $clz_evaluation_cri_10_copy1, $clz_evaluation_cri_11_copy1, $clz_evaluation_cri_12_copy1, $clz_evaluation_cri_13_copy1, $clz_evaluation_cri_14_copy1, $clz_evaluation_cri_15_copy1, $clz_evaluation_cri_16_copy1, $clz_evaluation_cri_17_copy1, $clz_evaluation_cri_18_copy1, $clz_evaluation_cri_19_copy1, $clz_evaluation_cri_20_copy1, $clz_evaluation_cri_41_copy1, $clz_evaluation_cri_42_copy1, $clz_evaluation_cri_43_copy1, $clz_evaluation_cri_44_copy1, $clz_evaluation_cri_45_copy1, $clz_evaluation_cri_46_copy1, $clz_evaluation_cri_47_copy1, $clz_evaluation_cri_48_copy1, $clz_evaluation_cri_49_copy1, $clz_evaluation_cri_50_copy1, $clz_evaluation_cri_51_copy1, $clz_evaluation_cri_52_copy1, $clz_evaluation_cri_53_copy1, $clz_evaluation_cri_54_copy1, $clz_evaluation_cri_55_copy1, $clz_evaluation_cri_56_copy1, $clz_evaluation_cri_57_copy1, $clz_evaluation_cri_58_copy1, $clz_evaluation_cri_59_copy1, $clz_evaluation_cri_60_copy1,  $recode_added_by ) {

		
        
        $response = array();

        // insert query
		$stmt = $this->conn->prepare("call insert_classReport(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
		$stmt->bind_param("siiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiissssssssssssssssssssssssssssssssssssssssiiiiiiiiiiiiiiiiiiiissssssssssssssssssssi", $stu_admission_number, $year,  $clz_evaluation_cri_1, $clz_evaluation_cri_2, $clz_evaluation_cri_3, $clz_evaluation_cri_4, $clz_evaluation_cri_5, $clz_evaluation_cri_6, $clz_evaluation_cri_7, $clz_evaluation_cri_8, $clz_evaluation_cri_9, $clz_evaluation_cri_10, $clz_evaluation_cri_11, $clz_evaluation_cri_12, $clz_evaluation_cri_13, $clz_evaluation_cri_14, $clz_evaluation_cri_15, $clz_evaluation_cri_16, $clz_evaluation_cri_17, $clz_evaluation_cri_18, $clz_evaluation_cri_19, $clz_evaluation_cri_20, $clz_evaluation_cri_21, $clz_evaluation_cri_22, $clz_evaluation_cri_23, $clz_evaluation_cri_24, $clz_evaluation_cri_25, $clz_evaluation_cri_26, $clz_evaluation_cri_27, $clz_evaluation_cri_28, $clz_evaluation_cri_29, $clz_evaluation_cri_30, $clz_evaluation_cri_31, $clz_evaluation_cri_32, $clz_evaluation_cri_33, $clz_evaluation_cri_34, $clz_evaluation_cri_35, $clz_evaluation_cri_36, $clz_evaluation_cri_37, $clz_evaluation_cri_38, $clz_evaluation_cri_39, $clz_evaluation_cri_40, $clz_evaluation_cri_41, $clz_evaluation_cri_42, $clz_evaluation_cri_43, $clz_evaluation_cri_44, $clz_evaluation_cri_45, $clz_evaluation_cri_46, $clz_evaluation_cri_47, $clz_evaluation_cri_48, $clz_evaluation_cri_49, $clz_evaluation_cri_50, $clz_evaluation_cri_51, $clz_evaluation_cri_52, $clz_evaluation_cri_53, $clz_evaluation_cri_54, $clz_evaluation_cri_55, $clz_evaluation_cri_56, $clz_evaluation_cri_57, $clz_evaluation_cri_58, $clz_evaluation_cri_59, $clz_evaluation_cri_60, $clz_evaluation_cri_61, $clz_evaluation_cri_62, $clz_evaluation_cri_63, $clz_evaluation_cri_64, $clz_evaluation_cri_65, $clz_evaluation_cri_66, $clz_evaluation_cri_67, $clz_evaluation_cri_68, $clz_evaluation_cri_69, $clz_evaluation_cri_70, $clz_evaluation_cri_71, $clz_evaluation_cri_72, $clz_evaluation_cri_73, $clz_evaluation_cri_74, $clz_evaluation_cri_75, $clz_evaluation_cri_76, $clz_evaluation_cri_77, $clz_evaluation_cri_78, $clz_evaluation_cri_79, $clz_evaluation_cri_80, $clz_evaluation_cri_1_copy1, $clz_evaluation_cri_2_copy1, $clz_evaluation_cri_3_copy1, $clz_evaluation_cri_4_copy1, $clz_evaluation_cri_5_copy1, $clz_evaluation_cri_6_copy1, $clz_evaluation_cri_7_copy1, $clz_evaluation_cri_8_copy1, $clz_evaluation_cri_9_copy1, $clz_evaluation_cri_10_copy1, $clz_evaluation_cri_11_copy1, $clz_evaluation_cri_12_copy1, $clz_evaluation_cri_13_copy1, $clz_evaluation_cri_14_copy1, $clz_evaluation_cri_15_copy1, $clz_evaluation_cri_16_copy1, $clz_evaluation_cri_17_copy1, $clz_evaluation_cri_18_copy1, $clz_evaluation_cri_19_copy1, $clz_evaluation_cri_20_copy1, $clz_evaluation_cri_41_copy1, $clz_evaluation_cri_42_copy1, $clz_evaluation_cri_43_copy1, $clz_evaluation_cri_44_copy1, $clz_evaluation_cri_45_copy1, $clz_evaluation_cri_46_copy1, $clz_evaluation_cri_47_copy1, $clz_evaluation_cri_48_copy1, $clz_evaluation_cri_49_copy1, $clz_evaluation_cri_50_copy1, $clz_evaluation_cri_51_copy1, $clz_evaluation_cri_52_copy1, $clz_evaluation_cri_53_copy1, $clz_evaluation_cri_54_copy1, $clz_evaluation_cri_55_copy1, $clz_evaluation_cri_56_copy1, $clz_evaluation_cri_57_copy1, $clz_evaluation_cri_58_copy1, $clz_evaluation_cri_59_copy1, $clz_evaluation_cri_60_copy1,  $recode_added_by );
		
		if ($stmt->execute()) {
            $stmt->bind_result($resultset);
            $stmt->fetch();
            $behavior_report_state = $resultset;
            $stmt->close();
            return $behavior_report_state;
        } else {
            return NULL;
        }
		
		
		$result = $stmt->execute();

		$stmt->close();

        
        // Check for successful insertion
        if ($result) {
			// project successfully inserted
            return CREATED_SUCCESSFULLY;
        } else {
            // Failed to create project
            return CREATE_FAILED;
        }
        
		return $response;

    }
	
	/**
     * Update  Occupation_type
     *
     * @param String $occ_type_name Occupation_type name
	 * @param String $occ_type_description Occupation_type Discription 
	 * @param String $recode_added_by 
     *
     * @return database transaction status
     */
    public function updateOccupation_type($occ_type_name, $occ_type_description, $recode_added_by) {

		
        $response = array();
        // First check if project already existed in db
        if ($this->isOccupation_typeExists($occ_type_name)) {
            
			//
			$stmt = $this->conn->prepare("UPDATE occupation_type set status = 2,  recode_modified_at = now() , recode_modified_by = ?, occ_type_description= ? where occ_type_name= ? and (status = 1 or status = 2)");
			$stmt->bind_param("iss", $recode_added_by, $occ_type_description, $occ_type_name);
			
			
			
			
			$result = $stmt->execute();
			

			$stmt->close();

        } else {
            // project is not already existed in the db
            return NOT_EXISTED;
        }
		
         

        // Check for successful update
        if ($result) {
			// project successfully update
            return UPDATE_SUCCESSFULLY;
        } else {
            // Failed to update project
            return UPDATE_FAILED;
        }
        
		return $response;

    }
	
/**
     * Delete Occupation_type 
     *
     * @param String $occ_type_name for the system
	 * @param String $recode_added_by
     *
     * @return database transaction status
     */
    public function deleteOccupationType($occ_type_name, $recode_added_by) {

		
        $response = array();
        // First check if project already existed in db
        if ($this->isProjectExists($occ_type_name)) {
           			
			//
			$stmt = $this->conn->prepare("UPDATE occupation_type set status = 3, recode_modified_at = now() , recode_modified_by = ? where occ_type_name = ? and (status = 1 or status = 2)");
			$stmt->bind_param("is", $recode_added_by,$occ_type_name);
			$result = $stmt->execute();
			
            $stmt->close();

        } else {
            // Project is not already existed in the db
            return NOT_EXISTED;
        }
		
         

        // Check for successful insertion
        if ($result) {
			// exam successfully deleted
            return DELETE_SUCCESSFULLY;
        } else {
            // Failed to delete exam
            return DELETE_FAILED;
        }
        
		return $response;

    }
	  
	/**
     * Fetching occupation_type by occ_type_name
	 *
     * @param String $occ_type_name  occupation_type name
	 *
	 *@return Project object only needed data
     */
    public function getOccTypeNametByProjectName($occ_type_name) {
        $stmt = $this->conn->prepare("SELECT occ_type_name, occ_type_description, status, recode_added_at, recode_added_by FROM occupation_type WHERE occ_type_name = ? and (status = 1 or status = 2)");
        $stmt->bind_param("s", $occ_type_name);
        if ($stmt->execute()) {
            $stmt->bind_result($occ_type_name, $cc_type_description, $status, $recode_added_at, $recode_added_by);
            $stmt->fetch();
            $occupation_type = array();
            $occupation_type["occ_type_name"] = $occ_type_name;
            $occupation_type["occ_type_description"] = $occ_type_description;
            $occupation_type["status"] = $status;
            $occupation_type["recode_added_at"] = $recode_added_at;
			$occupation_type["recode_added_by"] = $recode_added_by;

            $stmt->close();
            return $occupation_type;
        } else {
            return NULL;
        }
    }
  
  
	/**
     * Fetching all occupation_types
	 *
     * @return $occupation_types object set of all occupation_types
     */
    public function getAllProjects() {
        $stmt = $this->conn->prepare("SELECT * FROM occupation_type WHERE (status = 1 or status = 2) ORDER BY occ_type_name");
        $stmt->execute();
        $occupation_types = $stmt->get_result();
        $stmt->close();
        return $occupation_types;
    }
	
  
  
  
  
  
/*
 * ------------------------ SUPPORTIVE METHODS ------------------------
 */

	/**
     * Checking for duplicate occupation type by occ_type_name
     *
     * @param String $occ_type_name occupation type name to check in db
     *
     * @return boolean
     */
    private function isSiblingsExists($stu_id, $slib_id) {
		$stmt = $this->conn->prepare("SELECT stu_id from siblings WHERE (status = 1 or status = 2)  and stu_id = ?  and slib_id = ?");
        $stmt->bind_param("ii",$stu_id, $slib_id);
        $stmt->execute();
		$stmt->store_result();
        $num_rows = $stmt->num_rows;
        $stmt->close();
        return ($num_rows > 0); //if it has more than zero number of rows; then  it sends true
    }

}

?>
