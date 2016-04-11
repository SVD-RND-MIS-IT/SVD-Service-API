<?php
/**
 * Class to handle all the operational uder details
 * This class will have CRUD methods for operational_user, user_category tables
 *
 * @author Hasitha Lakmal
 *
 */
define('USER_CATEGORY_CREATED_SUCCESSFULLY', 0);
define('USER_CATEGORY_CREATE_FAILED', 1);
define('USER_CATEGORY_ALREADY_EXISTED', 2);
define('USER_CATEGORY_NOT_EXISTED', 3);
define('USER_CATEGORY_UPDATE_SUCCESSFULLY', 4);
define('USER_CATEGORY_UPDATE_FAILED', 5);
define('USER_CATEGORY_DELETE_SUCCESSFULLY', 6);
define('USER_CATEGORY_DELETE_FAILED', 7);


class UserCategoryManagement {

    private $conn;

    function __construct() {
        require_once '../../model/commen/DbConnect.php';
        // opening db connection
        $db = new DbConnect();
        $this->conn = $db->connect();
    }
	
	
/**
*
*
*
* ------------- `user_category` table methods ------------------
*
*
*
*/
	
    /**
     * Creating new user_category
     *
     * @param String $usr_name User name for the system
     * @param String $usr_pwd User login password
     * @param String $usr_full_name User full name
     * @param String $usr_email User email adress
     * @param String $usr_phone_number User phone number
     * @param String $usr_category User usercatogary id
     *
     * @return database transaction status
     */
    public function createUserCategory($ucat_name, $ucat_description, $recode_added_by) {

		
        $response = array();
		
        // First check if user already existed in db
        if (!$this->isUserCategoryExists($ucat_name)) {
            			
            // insert query
			 $stmt = $this->conn->prepare("INSERT INTO user_category(ucat_name, ucat_description,recode_added_by) values(?, ?,?)");
			 $stmt->bind_param("ssi", $ucat_name, $ucat_description, $recode_added_by);
			 $result = $stmt->execute();

			 $stmt->close();

        } else {
            // User is not already existed in the db
            return USER_CATEGORY_ALREADY_EXISTED;
        }
		
         

        // Check for successful insertion
        if ($result) {
			// User successfully inserted
            return USER_CATEGORY_CREATED_SUCCESSFULLY;
        } else {
            // Failed to create user
            return USER_CATEGORY_CREATE_FAILED;
        }
        
		return $response;

    }
	
	/**
     * Update operational_user
     *
     * @param String $usr_name User name for the system
     * @param String $usr_pwd User login password
     * @param String $usr_full_name User full name
     * @param String $usr_email User email adress
     * @param String $usr_phone_number User phone number
     * @param String $usr_category User usercatogary id
     *
     * @return database transaction status
     */
    public function updateOperationalUser($usr_name, $usr_pwd, $usr_full_name, $usr_email, $usr_phone_number, $usr_category, $recode_added_by) {

		
        $response = array();
        // First check if user already existed in db
        if ($this->isOperationalUserExists($usr_name)) {
            // Generating password hash
            $password_hash = PassHash::hash($usr_pwd);

            // Generating API key
            $usr_api_key = $this->generateApiKey();
			
			//
			$stmt = $this->conn->prepare("UPDATE operational_user set status = 2,  recode_modified_at = now() , recode_modified_by = ? where usr_name = ?");
			$stmt->bind_param("is", $recode_added_by, $usr_name);
			$result = $stmt->execute();
			
            // insert updated recode
			 $stmt = $this->conn->prepare("INSERT INTO operational_user(usr_name, usr_pwd, usr_full_name, usr_email, usr_phone_number, usr_category, usr_api_key, recode_added_by) values(?, ?, ?,  ?, ?, ?, ?, ?)");
			 $stmt->bind_param("sssssisi", $usr_name, $password_hash, $usr_full_name, $usr_email, $usr_phone_number, $usr_category, $usr_api_key,$recode_added_by );
			 $result = $stmt->execute();

			 $stmt->close();

        } else {
            // User with same email already existed in the db
            return USER_NOT_EXISTED;
        }
		
         

        // Check for successful insertion
        if ($result) {
			// User successfully inserted
            return USER_UPDATE_SUCCESSFULLY;
        } else {
            // Failed to create user
            return USER_UPDATE_FAILED;
        }
        
		return $response;

    }
	
/**
     * Delete operational_user
     *
     * @param String $usr_name User name for the system
	 * @param int $user_id User ID for the system
     *
     * @return database transaction status
     */
    public function deleteOperationalUser($user_id, $usr_name) {

		
        $response = array();
        // First check if user already existed in db
        if ($this->isOperationalUserExists($usr_name)) {
           			
			//
			$stmt = $this->conn->prepare("UPDATE operational_user set status = 3, recode_modified_at = now() , recode_modified_by = ? where usr_name = ? and status=1");
			$stmt->bind_param("is",$user_id, $usr_name);
			$result = $stmt->execute();
			
            $stmt->close();

        } else {
            // User is not already existed in the db
            return USER_NOT_EXISTED;
        }
		
         

        // Check for successful insertion
        if ($result) {
			// User successfully inserted
            return USER_DELETE_SUCCESSFULLY;
        } else {
            // Failed to create user
            return USER_DELETE_FAILED;
        }
        
		return $response;

    }
	  
	/**
     * Fetching user by usr_name
	 *
     * @param String $usr_name_in User name
	 *
	 *@return user object only needed data
     */
    public function getUserByUserName($usr_name_in) {
        $stmt = $this->conn->prepare("SELECT usr_name, usr_full_name, usr_email, usr_phone_number, usr_api_key, ou_status,ou_recode_added_at,ucat_name,ucat_description FROM operational_user_view_release WHERE usr_name = ? and (ou_status = 1 or ou_status = 2)");
        $stmt->bind_param("s", $usr_name_in);
        if ($stmt->execute()) {
            // $user = $stmt->get_result()->fetch_assoc();
            $stmt->bind_result($usr_name,  $usr_full_name, $usr_email, $usr_phone_number, $usr_api_key, $ou_status, $ou_recode_added_at, $ucat_name, $ucat_description);
            $stmt->fetch();
            $user = array();
            $user["usr_name"] = $usr_name;
            $user["usr_full_name"] = $usr_full_name;
            $user["usr_email"] = $usr_email;
            $user["usr_phone_number"] = $usr_phone_number;
			$user["usr_api_key"] = $usr_api_key;
            $user["ou_status"] = $ou_status;
			$user["ou_recode_added_at"] = $ou_recode_added_at;
            $user["ucat_name"] = $ucat_name;
            $user["ucat_description"] = $ucat_description;

            $stmt->close();
            return $user;
        } else {
            return NULL;
        }
    }
  
  
	/**
     * Fetching all users
	 *
     * @return $users result set of all users
     */
    public function getAllUserCategory() {
        $stmt = $this->conn->prepare("SELECT * FROM user_category WHERE status = 1 or status = 2 ORDER BY ucat_name");
        $stmt->execute();
        $users = $stmt->get_result();
        $stmt->close();
        return $users;
    }
	
  
  
  
  
  
/**
*
*
*
* ------------- Supportive methods ------------------
*
*
*
*/

  
	/**
     * Checking for duplicate UserCategory by ucat_name
     *
     * @param String $ucat_name user catogary name to check in db
     *
     * @return boolean
     */
    private function isUserCategoryExists($ucat_name) {
		$stmt = $this->conn->prepare("SELECT ucat_name from user_category WHERE ucat_name = ? and (status=1 or status=2) ");
        $stmt->bind_param("s", $ucat_name);
        $stmt->execute();
		$stmt->store_result();
        $num_rows = $stmt->num_rows;
        $stmt->close();
        return !($num_rows == 0); //if no any user number of rows ==0; then get negative of it to send false
    }

}

?>
