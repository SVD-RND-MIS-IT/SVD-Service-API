<?php
require_once '../../model/commen/PassHash.php';
/**
 * Class to handle all the talant details
 * This class will have CRUD methods for talant
 *
 * @author Bagya
 *
 */

class StudentProfileManagement {

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
     * Fetching talants by tal_name
	 *
     * @param String $tal_name tal name
	 *
	 *@return talant object only needed data
     */
    public function getStudentAnalisis_Behavior_Details($repotYear, $greads, $classes) {
        $stmt = $this->conn->prepare("call student_class_report_calculation (?, ?, ?);");
		$stmt->bind_param("iss", $repotYear, $greads, $classes);
        $stmt->execute();
        $behavior = $stmt->get_result();
        $stmt->close();
        return $behavior;
    }
	
	/**
     * Fetching talants by tal_name
	 *
     * @param String $tal_name tal name
	 *
	 *@return talant object only needed data
     */
    public function getStudentAnalisis_Recomendations_Details($repotYear, $greads, $classes) {
        $stmt = $this->conn->prepare("select
										r.rec_id, s.stu_admission_number, s.stu_full_name, r.rec_year ,rt.rec_type_name, r.rec_topic, r.rec_discription
									from 
										recommendation r left join recommendation_type rt on r. rec_type_id = rt.rec_type_id
										left join student s on r.rec_stu_id = s.stu_id 
									where
										r.rec_stu_id in (select stu_id from student_class sc where sc.year=? 
										and sc.clz_id in ( select clz_id from class where FIND_IN_SET(clz_grade, ?) and  FIND_IN_SET(clz_class, ?) ));");
		$stmt->bind_param("iss", $repotYear, $greads, $classes);
        $stmt->execute();
        $res = $stmt->get_result();
        $stmt->close();
        return $res;
    }
	
	
	
	/**
     * Fetching talants by tal_name
	 *
     * @param String $tal_name tal name
	 *
	 *@return talant object only needed data
     */
    public function getStudentAnalisis_Recomendations_count_Details($repotYear, $greads, $classes) {
        $stmt = $this->conn->prepare("select
										count(*) as Numberof_recodes,
										sum(IF(r.rec_type_id = '1', 1,0)) as t1rec,
										sum(IF(r.rec_type_id = '2', 1,0)) as t2rec,
										sum(IF(r.rec_type_id = '3', 1,0)) as t3rec,
										sum(IF(r.rec_type_id = '4', 1,0)) as t4rec,
										sum(IF(r.rec_type_id = '5', 1,0)) as t5rec
									from 
										recommendation r left join recommendation_type rt on r. rec_type_id = rt.rec_type_id
									where
										r.rec_stu_id in (select stu_id from student_class sc where sc.year=?
										and sc.clz_id in ( select clz_id from class where FIND_IN_SET(clz_grade, ?) and  FIND_IN_SET(clz_class, ?) ));");
		$stmt->bind_param("iss", $repotYear, $greads, $classes);
        $stmt->execute();
        $res = $stmt->get_result();
        $stmt->close();
        return $res;
    }


	/**
     * Fetching talants by tal_name
	 *
     * @param String $tal_name tal name
	 *
	 *@return talant object only needed data
     */
    public function getStudentAnalisis_Desies_Details($repotYear, $greads, $classes) {
        $stmt = $this->conn->prepare("select
										s.stu_admission_number as stu_admission_number,
										s.stu_full_name as stu_full_name,
										d.dis_name as dis_name,
										d.dis_explanation as dis_explanation,
										sd.dis_found_year as dis_found_year
									from 
										student_diseases sd left join diseases d on sd.dis_id = d.dis_id
										left join student s on sd.stu_id = s.stu_id 
									where
										sd.stu_id in (select stu_id from student_class sc where sc.year=?
										and sc.clz_id in ( select clz_id from class where FIND_IN_SET(clz_grade, ?) and  FIND_IN_SET(clz_class, ?) ));");
		$stmt->bind_param("iss", $repotYear, $greads, $classes);
        $stmt->execute();
        $res = $stmt->get_result();
        $stmt->close();
        return $res;
    }
	
	
	
	/**
     * Fetching talants by tal_name
	 *
     * @param String $tal_name tal name
	 *
	 *@return talant object only needed data
     */
    public function getStudentAnalisis_Desies_count_Details($repotYear, $greads, $classes) {
        $stmt = $this->conn->prepare("select
										d.dis_name as dis_name,
										d.dis_explanation as dis_explanation,
										count(*) as number_of_student_falls_ill
									from 
										student_diseases sd left join diseases d on sd.dis_id = d.dis_id
										left join student s on sd.stu_id = s.stu_id 
									where
										sd.stu_id in (select stu_id from student_class sc where sc.year=?
										and sc.clz_id in ( select clz_id from class where FIND_IN_SET(clz_grade, ?) and  FIND_IN_SET(clz_class, ?) ))
									group by sd.dis_id
									order by number_of_student_falls_ill desc;");
		$stmt->bind_param("iss", $repotYear, $greads, $classes);
        $stmt->execute();
        $res = $stmt->get_result();
        $stmt->close();
        return $res;
    }
	
	/**
     * Fetching talants by tal_name
	 *
     * @param String $tal_name tal name
	 *
	 *@return talant object only needed data
     */
    public function getStudentAnalisis_School_Details($repotYear, $greads, $classes) {
        $stmt = $this->conn->prepare("select
										s.sch_name as sch_name,
										s.sch_situated_in as sch_situated_in,
										count(*) as number_of_student_attend
									from 
										student_school ss left join school s on ss.sch_id = s.sch_id  
									where
										ss.stu_id in (select stu_id from student_class sc where sc.year=?
										and sc.clz_id in ( select clz_id from class where FIND_IN_SET(clz_grade, ?) and  FIND_IN_SET(clz_class, ?) ))
									group by ss.sch_id
									order by number_of_student_attend desc;");
		$stmt->bind_param("iss", $repotYear, $greads, $classes);
        $stmt->execute();
        $res = $stmt->get_result();
        $stmt->close();
        return $res;
    }
	
	/**
     * Fetching talants by tal_name
	 *
     * @param String $tal_name tal name
	 *
	 *@return talant object only needed data
     */
    public function getStudentAnalisis_City_Details($repotYear, $greads, $classes) {
        $stmt = $this->conn->prepare("select
										s.stu_city as stu_city,
										count(*) as number_of_student_livesin
									from 
										student s 
									where
										s.stu_id in (select stu_id from student_class sc where sc.year=?
										and sc.clz_id in ( select clz_id from class where FIND_IN_SET(clz_grade, ?) and  FIND_IN_SET(clz_class, ?) ))
									group by s.stu_city
									order by number_of_student_livesin desc;");
		$stmt->bind_param("iss", $repotYear, $greads, $classes);
        $stmt->execute();
        $res = $stmt->get_result();
        $stmt->close();
        return $res;
    }
	
	
	/**
     * Fetching talants by tal_name
	 *
     * @param String $tal_name tal name
	 *
	 *@return talant object only needed data
     */
    public function getStudentAnalisis_Distanse_Details($repotYear, $greads, $classes) {
        $stmt = $this->conn->prepare("select
										max(distance_to_home) as Max_distance_to_home,
										min(distance_to_home) as Min_distance_to_home,
										avg(distance_to_home) as Avg_distance_to_home
									from 
										student s 
									where
										s.stu_id in (select stu_id from student_class sc where sc.year=?
										and sc.clz_id in ( select clz_id from class where FIND_IN_SET(clz_grade, ?) and  FIND_IN_SET(clz_class, ?) ));");
		$stmt->bind_param("iss", $repotYear, $greads, $classes);
        $stmt->execute();
        $res = $stmt->get_result();
        $stmt->close();
        return $res;
    }
	
	/**
     * Fetching talants by tal_name
	 *
     * @param String $tal_name tal name
	 *
	 *@return talant object only needed data
     */
    public function getStudentAnalisis_Far_Occupation_Details($repotYear, $greads, $classes) {
        $stmt = $this->conn->prepare("select
										ot.occ_type_name as occ_type_name_far,
										ot.occ_type_description as occ_type_description_far,
										count(*) as number_of_workers_far
									from 
										student s left join father f on s.father_id = f.far_id
										left join occupation_type ot on f.far_occupation_type = ot.occ_type_id
									where
										s.stu_id in (select stu_id from student_class sc where sc.year=?
										and sc.clz_id in ( select clz_id from class where FIND_IN_SET(clz_grade, ?) and  FIND_IN_SET(clz_class, ?) ))
									group by f.far_occupation_type
									order by number_of_workers_far desc;");
		$stmt->bind_param("iss", $repotYear, $greads, $classes);
        $stmt->execute();
        $res = $stmt->get_result();
        $stmt->close();
        return $res;
    }
	
	
	/**
     * Fetching talants by tal_name
	 *
     * @param String $tal_name tal name
	 *
	 *@return talant object only needed data
     */
    public function getStudentAnalisis_Mot_Occupation_Details($repotYear, $greads, $classes) {
        $stmt = $this->conn->prepare("select
										ot.occ_type_name as occ_type_name1_mot,
										ot.occ_type_description as occ_type_description1_mot,
										count(*) as number_of_workers_mot
									from 
										student s left join mother m on s.mother_id = m.mot_id
										left join occupation_type ot on m.mot_occupation_type = ot.occ_type_id
									where
										s.stu_id in (select stu_id from student_class sc where sc.year=?
										and sc.clz_id in ( select clz_id from class where FIND_IN_SET(clz_grade, ?) and  FIND_IN_SET(clz_class, ?) ))
									group by m.mot_occupation_type
									order by number_of_workers_mot desc;");
		$stmt->bind_param("iss", $repotYear, $greads, $classes);
        $stmt->execute();
        $res = $stmt->get_result();
        $stmt->close();
        return $res;
    }
	
	
	/**
     * Fetching talants by tal_name
	 *
     * @param String $tal_name tal name
	 *
	 *@return talant object only needed data
     */
    public function getStudentAnalisis_Gur_Occupation_Details($repotYear, $greads, $classes) {
        $stmt = $this->conn->prepare("SELECT 
										ot.occ_type_name AS occ_type_name_gur,
										ot.occ_type_description AS occ_type_description_gur,
										COUNT(*) AS number_of_workers_gur
									FROM
										student s
											LEFT JOIN
										guardian g ON s.guardian_id = g.gur_id
											LEFT JOIN
										occupation_type ot ON g.gur_occupation_type = ot.occ_type_id
									WHERE
										s.stu_id IN (SELECT 
												stu_id
											FROM
												student_class sc
											WHERE
												sc.year = ?
													AND sc.clz_id IN (SELECT 
														clz_id
													FROM
														class
													WHERE
														FIND_IN_SET(clz_grade, ?)
															AND FIND_IN_SET(clz_class, ?)))
									GROUP BY g.gur_occupation_type
									ORDER BY number_of_workers_gur DESC;");
		$stmt->bind_param("iss", $repotYear, $greads, $classes);
        $stmt->execute();
        $res = $stmt->get_result();
        $stmt->close();
        return $res;
    }
	
	/**
     * Fetching talants by tal_name
	 *
     * @param String $tal_name tal name
	 *
	 *@return talant object only needed data
     */
    public function getStudentAnalisis_No_Father_Details($repotYear, $greads, $classes) {
        $stmt = $this->conn->prepare("select 
										s.stu_admission_number as stu_admission_number,
										s.stu_full_name as stu_full_name,
										s.stu_gender as stu_gender,
										s.stu_date_of_birth as stu_date_of_birth,
										s.stu_address as stu_address
									from 
										student_class sc left join class_report cr on sc.clz_report_id = cr.clz_repo_id
										left join student s on sc.stu_id = s.stu_id
									where
										sc.year= ? and
										sc.clz_id in ( select clz_id from class where FIND_IN_SET(clz_grade, ?) and  FIND_IN_SET(clz_class, ?)) and
										cr.clz_evaluation_cri_41_copy1 = 'No';");
		$stmt->bind_param("iss", $repotYear, $greads, $classes);
        $stmt->execute();
        $res = $stmt->get_result();
        $stmt->close();
        return $res;
    }
	
	/**
     * Fetching talants by tal_name
	 *
     * @param String $tal_name tal name
	 *
	 *@return talant object only needed data
     */
    public function getStudentAnalisis_No_Mother_Details($repotYear, $greads, $classes) {
        $stmt = $this->conn->prepare("select 
										s.stu_admission_number as stu_admission_number,
										s.stu_full_name as stu_full_name,
										s.stu_gender as stu_gender,
										s.stu_date_of_birth as stu_date_of_birth,
										s.stu_address as stu_address
									from 
										student_class sc left join class_report cr on sc.clz_report_id = cr.clz_repo_id
										left join student s on sc.stu_id = s.stu_id
									where
										sc.year= ? and
										sc.clz_id in ( select clz_id from class where FIND_IN_SET(clz_grade, ?) and  FIND_IN_SET(clz_class, ?)) and
										cr.clz_evaluation_cri_42_copy1 = 'No';");
		$stmt->bind_param("iss", $repotYear, $greads, $classes);
        $stmt->execute();
        $res = $stmt->get_result();
        $stmt->close();
        return $res;
    }
	

	
	/**
     * Fetching talants by tal_name
	 *
     * @param String $tal_name tal name
	 *
	 *@return talant object only needed data
     */
    public function getStudentAnalisis_No_Parent_Details($repotYear, $greads, $classes) {
        $stmt = $this->conn->prepare("select 
										s.stu_admission_number as stu_admission_number,
										s.stu_full_name as stu_full_name,
										s.stu_gender as stu_gender,
										s.stu_date_of_birth as stu_date_of_birth,
										s.stu_address as stu_address
									from 
										student_class sc left join class_report cr on sc.clz_report_id = cr.clz_repo_id
										left join student s on sc.stu_id = s.stu_id
									where
										sc.year= ? and
										sc.clz_id in ( select clz_id from class where FIND_IN_SET(clz_grade, ?) and  FIND_IN_SET(clz_class, ?)) and
										cr.clz_evaluation_cri_41_copy1 = 'No' and 
										 cr.clz_evaluation_cri_42_copy1 = 'No'");
		$stmt->bind_param("iss", $repotYear, $greads, $classes);
        $stmt->execute();
        $res = $stmt->get_result();
        $stmt->close();
        return $res;
    }
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	/**
     * Fetching talants by tal_name
	 *
     * @param String $tal_name tal name
	 *
	 *@return talant object only needed data
     */
    public function getStudentPersonalDetails($stu_admission_number) {
        $stmt = $this->conn->prepare("SELECT * FROM student_personal_detail_view WHERE stu_admission_number = ?");
		$stmt->bind_param("s", $stu_admission_number);
        $stmt->execute();
        $student = $stmt->get_result();
        $stmt->close();
        return $student;
    }
	
	  
	/**
     * Fetching talants by tal_name
	 *
     * @param String $tal_name tal name
	 *
	 *@return talant object only needed data
     */
    public function getStudentSchools($stu_admission_number) {
        $stmt = $this->conn->prepare("SELECT * FROM school_view WHERE stu_admission_number = ?");
		$stmt->bind_param("s", $stu_admission_number);
        $stmt->execute();
        $student_schools = $stmt->get_result();
        $stmt->close();
        return $student_schools;
    }
	
  
  
  /**
     * Fetching talants by tal_name
	 *
     * @param String $tal_name tal name
	 *
	 *@return talant object only needed data
     */
    public function getStudentDesises($stu_admission_number) {
        $stmt = $this->conn->prepare("SELECT * FROM desies_view WHERE stu_admission_number = ?");
		$stmt->bind_param("s", $stu_admission_number);
        $stmt->execute();
        $student_diseases = $stmt->get_result();
        $stmt->close();
        return $student_diseases;
    }
	
	
  /**
     * Fetching talants by tal_name
	 *
     * @param String $tal_name tal name
	 *
	 *@return talant object only needed data
     */
    public function getStudentClass($stu_admission_number) {
        $stmt = $this->conn->prepare("SELECT * FROM student_class_view WHERE stu_admission_number = ?");
		$stmt->bind_param("s", $stu_admission_number);
        $stmt->execute();
        $student_class_prefect = $stmt->get_result();
        $stmt->close();
        return $student_class_prefect;
    }
	
	

	/**
     * Fetching talants by tal_name
	 *
     * @param String $tal_name tal name
	 *
	 *@return talant object only needed data
     */
    public function getStudentReccomendation($stu_admission_number) {
        $stmt = $this->conn->prepare("SELECT * FROM student_reccomendation_view WHERE stu_admission_number = ?");
		$stmt->bind_param("s", $stu_admission_number);
        $stmt->execute();
        $student_reccomendations = $stmt->get_result();
        $stmt->close();
        return $student_reccomendations;
    }
	
	
	/**
     * Fetching talants by tal_name
	 *
     * @param String $tal_name tal name
	 *
	 *@return talant object only needed data
     */
    public function getStudentExamResults($stu_admission_number) {
        $stmt = $this->conn->prepare("SELECT * FROM student_examresult_view WHERE stu_admission_number = ?");
		$stmt->bind_param("s", $stu_admission_number);
        $stmt->execute();
        $student_exam_results = $stmt->get_result();
        $stmt->close();
        return $student_exam_results;
    }
	
	
}

?>
