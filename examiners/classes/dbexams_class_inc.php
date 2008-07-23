<?php
/* ------ data class extends dbTable for all examiners database tables ------*/

// security check - must be included in all scripts
if(!$GLOBALS['kewl_entry_point_run']){
    die("You cannot view this page directly");
}

/**
* Model class for the examiners database tables
* @author Kevin Cyster
*/

class dbexams extends dbTable
{
    /**
    * @var object $objLanguage: The language class in the language module
    * @access private
    */
    private $objLanguage;
    
    /**
    * @var object $objUser: The user class in the security module
    * @access private
    */
    private $objUser;

    /**
    * @var bool $isLoggedIn: TRUE if a user is logged in | FALSE if not
    * @access private
    */
    private $isLoggedIn;

    /**
    * @var string $userId: The user id of the current user
    * @access private
    */
    private $userId;

    /**
    * @var string $table: The the name of the current table
    * @access private
    */
    private $table;

    /**
    * Method to construct the class
    *
    * @access public
    * @return
    */
    public function init()
    {
        $this->objLanguage = $this->getObject('language', 'language');
        $this->objUser = $this->getObject('user', 'security');
        $this->userId = $this->objUser->userId();
    }
    
/* ----- Functions for changeing tables ----- */

	/**
	* Method to dynamically switch tables
	*
	* @access private
	* @param string $table: The name of the table
	* @return boolean: TRUE on success FALSE on failure
	*/
	private function _changeTable($table)
	{
		try{
		 	$this->table = $table;
			parent::init($table);
			return TRUE;
		}catch(customException $e){
			customException::cleanUp();
			return FALSE;
		}
	}
	
	/**
	* Method to set the examiners users table
	*
	* @access private
	* @return boolean: TRUE on success FALSE on failure
	*/
	private function _setUsers()
	{
        return $this->_changeTable('tbl_examiners_users');
    }

	/**
	* Method to set the examiners department table
	*
	* @access private
	* @return boolean: TRUE on success FALSE on failure
	*/
	private function _setDepartments()
	{
        return $this->_changeTable('tbl_examiners_departments');
    }

	/**
	* Method to set the examiners subjects table
	*
	* @access private
	* @return boolean: TRUE on success FALSE on failure
	*/
	private function _setSubjects()
	{
        return $this->_changeTable('tbl_examiners_subjects');
    }

	/**
	* Method to set the examiners first table
	*
	* @access private
	* @return boolean: TRUE on success FALSE on failure
	*/
	private function _setFirst()
	{
        return $this->_changeTable('tbl_examiners_first');
    }

	/**
	* Method to set the examiners second table
	*
	* @access private
	* @return boolean: TRUE on success FALSE on failure
	*/
	private function _setSecond()
	{
        return $this->_changeTable('tbl_examiners_second');
    }

	/**
	* Method to set the examiners moderate table
	*
	* @access private
	* @return boolean: TRUE on success FALSE on failure
	*/
	private function _setModerate()
	{
        return $this->_changeTable('tbl_examiners_moderate');
    }

	/**
	* Method to set the examiners alternate table
	*
	* @access private
	* @return boolean: TRUE on success FALSE on failure
	*/
	private function _setAlternate()
	{
        return $this->_changeTable('tbl_examiners_alternate');
    }

	/**
	* Method to set the examiners remark table
	*
	* @access private
	* @return boolean: TRUE on success FALSE on failure
	*/
	private function _setRemarking()
	{
        return $this->_changeTable('tbl_examiners_remarking');
    }

	/**
	* Method to set the examiners audit table
	*
	* @access private
	* @return boolean: TRUE on success FALSE on failure
	*/
	private function _setAudit()
	{
        return $this->_changeTable('tbl_examiners_audit');
    }

/* ----- Cross-table functions ----- */

    /**
    * Method to return the examiner matrix for a subject for a year
    *
    * @access public
    * @param string $depId: The id of the department
    * @param string $subjId: The id of the subject
    * @param string $year: The year of the matrix
    * @return array|bool $data: The matrix data on success | FALSE on failure
    */
    public function getMatrixByYear($depId, $subjId, $year)
    {
        $first = $this->getFirstByYear($depId, $subjId, $year);
        $second = $this->getSecondByYear($depId, $subjId, $year);
        $moderate = $this->getModerateByYear($depId, $subjId, $year);
        $alternate = $this->getAlternateByYear($depId, $subjId, $year);
        $remarking = $this->getRemarkingByYear($depId, $subjId, $year);
        
        if($first == FALSE && $second == FALSE && $moderate == FALSE && $alternate == FALSE && $remarking == FALSE){
            return FALSE;
        }        
        $data = array();
        $data[] = $first;
        $data[] = $second;
        $data[] = $moderate;
        $data[] = $alternate;
        $data[] = $remarking;
        
        return $data;
    }
    
    /**
    * Method to delete a matrix
    *
    * @access public
    * @param string $depId: The id of the department
    * @param string $subjId: The id of the subject
    * @param string $year: The year to delete
    * @return bool $success: TRUE on success | FALSE on failure
    */
    public function deleteMatrix($depId, $subjId, $year)
    {
        $first = $this->deleteFirst($depId, $subjId, $year);
        $second = $this->deleteSecond($depId, $subjId, $year);
        $moderate = $this->deleteModerate($depId, $subjId, $year);
        $alternate = $this->deleteAlternate($depId, $subjId, $year);
        $remarking = $this->deleteRemarking($depId, $subjId, $year);
        
        if($first != FALSE && $second != FALSE && $moderate != FALSE && $alternate != FALSE && $remarking != FALSE){
            return TRUE;
        }
        return FALSE;
    }

/* ----- Methods for tbl_examiners_audit ----- */

    /** 
    * Method to add an audit record
    *
    * @access private
    * @param string $table: The name of the table affected
    * @param string $id: The id of the affected record
    * @param string $field: The name of the affected field
    * @param string $oldValue: The old value of the field
    * @param string $newValue: The new value of the field
    * @param string $transType: The type of transaction
    * @return string|bool $auditId: The auditId on success | FALSE on failure
    */
    function _addAuditRecord($table, $id, $field, $oldValue, $newValue, $transType)
    {
        $this->_setAudit();
        
        $fields = array();
        $fields['table_name'] = $table;
        $fields['record_id'] = $id;
        $fields['field_name'] = $field;
        $fields['old_value'] = $oldValue;
        $fields['new_value'] = $newValue;
        $fields['trans_type'] = $transType;
        $fields['modifier_id'] = $this->userId;
        $fields['date_modified'] = date('Y-m-d H:i:s');
        $fields['updated'] = date('Y-m-d H:i:s');
        
        $auditId = $this->insert($fields);
        
        return $auditId;
    }
    
/* ----- Functions for tbl_examiners_users ----- */

    /**
    * Method to add an examinating user
    *
    * @access public
    * @param string $depId: The id of the department
    * @param string $title: The title of the examiner
    * @param string $name: The name of the examiner
    * @param string $surname: The surname of the examiner
    * @param string $org: The organisation the examiner belongs to
    * @param string $email: The email address of the examiner
    * @param string $tel: The telephone number of the examiner
    * @param string $ext: The telephone extension of the examiner
    * @param string $cell: The cell phone number of the examiner
    * @param string $address: The address of the examiner
    * @return string|bool $userId: The user id on success | FALSE on failure
    */
    public function addUser($depId, $title, $name, $surname, $org, $email, $tel, $ext, $cell, $address)
    {
        $this->_setUsers();
        $table = $this->table;
        
        $fields = array();
        $fields['dep_id'] = $depId;
        $fields['title'] = $title;
        $fields['first_name'] = $name;
        $fields['surname'] = $surname;
        $fields['organisation'] = $org;
        $fields['email_address'] = $email;
        $fields['tel_no'] = $tel;
        $fields['extension'] = $ext;
        $fields['cell_no'] = $cell;
        $fields['address'] = $address;
        $fields['deleted'] = 0;
        $fields['updated'] = date('Y-m-d H:i:s');
        $userId = $this->insert($fields);
        if($userId != FALSE){
         	$comment = $this->objLanguage->languageText('word_add');
            foreach($fields as $key=>$value){
                $this->_addAuditRecord($table, $userId, $key, NULL, $value, $comment);
            }
	        return $userId;
		}
		return FALSE;
    }
    
    /**
    * Method to delete a user
    *
    * @access public
    * @param string $userId: The id of the examining user
    * @return string|bool $userId: The user id on success | FALSE on failure
    */
    public function deleteUser($userId)
    {
        $this->_setUsers();
        $table = $this->table;
        
        $data = $this->getUserById($userId);
        if($data != FALSE){        
            $fields = array();
            $fields['deleted'] = 1;
            $fields['updated'] = date('Y-m-d H:i:s');
            $updated = $this->update('id', $userId, $fields);
            
            if($updated != FALSE){
                $comment = $this->objLanguage->languageText('word_delete');
                foreach($fields as $key=>$value){
                    $this->_addAuditRecord($table, $userId, $key, $data[$key], $value, $comment);
                }
	            return $userId;
		    }
		    return FALSE;
		}
		return FALSE;
    }
    
    /**
    * Method to get users by department
    *
    * @access public
    * @param string $depId: The id of the department
    * @return array|bool $data: The user data on success | FALSE on failure
    */
    public function getUsersByDepartment($depId)
    {
        $this->_setUsers();
        
        $sql = "SELECT * FROM ".$this->table;
        $sql .= " WHERE dep_id='".$depId."'";
        $sql .= " AND deleted='0'";
        
        $data = $this->getArray($sql);
        if($data != FALSE){
            return $data;
        }
        return FALSE;
    }

    /**
    * Method to get a user by Id
    *
    * @access public
    * @param string $userId: The id of the user to get
    * @return array|bool $data: The user data on success | FALSE on failure
    */
    public function getUserById($userId)
    {
        $this->_setUsers();
        
        $sql = "SELECT * FROM ".$this->table;
        $sql .= " WHERE id='".$userId."'";
        $sql .= " AND deleted='0'";
        
        $data = $this->getArray($sql);
        if($data != FALSE){
            return $data[0];
        }
        return FALSE;
    }

    /**
    * Method to list all users
    *
    * @access public
    * @return array|bool $data: The user data on success | FALSE on failure
    */
    public function getAllUsers()
    {
        $this->_setUsers();
        
        $sql = "SELECT * FROM ".$this->table;
        $sql .= " WHERE deleted='0'";
        
        $data = $this->getArray($sql);
        if($data != FALSE){
            return $data;
        }
        return FALSE;
    }

    /**
    * Method to edit a user
    *
    * @access public
    * @param string $userId: The id of the examining user
    * @param string $title: The title of the examiner
    * @param string $name: The name of the examiner
    * @param string $surname: The surname of the examiner
    * @param string $org: The organisation the examiner belongs to
    * @param string $email: The email address of the examiner
    * @param string $tel: The telephone number of the examiner
    * @param string $ext: The telephone extension of the examiner
    * @param string $cell: The cell phone number of the examiner
    * @param string $address: The address of the examiner
    * @return string|bool $userId: The user id on success | FALSE on failure
    */
    public function editUser($userId, $title, $name, $surname, $org, $email, $tel, $ext, $cell, $address)
    {
        $this->_setUsers();
        $table = $this->table;
        
        $data = $this->getUserById($userId);
        if($data != FALSE){        
            $fields = array();
            $fields['title'] = $title;
            $fields['first_name'] = $name;
            $fields['surname'] = $surname;
            $fields['organisation'] = $org;
            $fields['email_address'] = $email;
            $fields['tel_no'] = $tel;
            $fields['extension'] = $ext;
            $fields['cell_no'] = $cell;
            $fields['address'] = $address;
            $fields['updated'] = date('Y-m-d H:i:s');
            $updated = $this->update('id', $userId, $fields);
            
            if($updated != FALSE){
                $comment = $this->objLanguage->languageText('word_edit');
                foreach($fields as $key=>$value){
                    if($data[$key] != $value){
                        $this->_addAuditRecord($table, $userId, $key, $data[$key], $value, $comment);
                    }
                }
	            return $userId;
		    }
		    return FALSE;
		}
		return FALSE;
    }    

/* ----- Functions for tbl_examiners_departments ----- */

    /**
    * Method to add a department
    *
    * @access public
    * @param string $name: The name of the department
    * @return string|bool $depId: The department id on success | FALSE on failure
    */
    public function addDepartment($name)
    {
        $this->_setDepartments();
        $table = $this->table;
        
        $fields = array();
        $fields['department_name'] = $name;
        $fields['deleted'] = 0;
        $fields['updated'] = date('Y-m-d H:i:s');
        $depId = $this->insert($fields);
        
        if($depId != FALSE){
         	$comment = $this->objLanguage->languageText('word_add');
            foreach($fields as $key=>$value){
                $this->_addAuditRecord($table, $depId, $key, NULL, $value, $comment);
            }
	        return $depId;
		}
		return FALSE;
    }
    
    /**
    * Method to delete a department
    *
    * @access public
    * @param string $depId: The id of the department
    * @return string|bool $depId: The department id on success | FALSE on failure
    */
    public function deleteDepartment($depId)
    {
        $this->_setDepartments();
        $table = $this->table;
        
        $data = $this->getDepartmentById($depId);
        if($data != FALSE){        
            $fields = array();
            $fields['deleted'] = 1;
            $fields['updated'] = date('Y-m-d H:i:s');
            $updated = $this->update('id', $depId, $fields);
            
            if($updated != FALSE){
                $comment = $this->objLanguage->languageText('word_delete');
                foreach($fields as $key=>$value){
                    $this->_addAuditRecord($table, $depId, $key, $data[$key], $value, $comment);
                }
	            return $depId;
		    }
		    return FALSE;
		}
		return FALSE;
    }
    
    /**
    * Method to list all departments
    *
    * @access public
    * @return array|bool $data: The department data on success | FALSE on failure
    */
    public function getAllDepartments()
    {
        $this->_setDepartments();
        
        $sql = "SELECT * FROM ".$this->table;
        $sql .= " WHERE deleted='0'";
        
        $data = $this->getArray($sql);
        if($data != FALSE){
            return $data;
        }
        return FALSE;
    }

    /**
    * Method to get a department by Id
    *
    * @access public
    * @param string $depId: The id of the department to get
    * @return array|bool $data: The department data on success | FALSE on failure
    */
    public function getDepartmentById($depId)
    {
        $this->_setDepartments();
        
        $sql = "SELECT * FROM ".$this->table;
        $sql .= " WHERE id='".$depId."'";
        $sql .= " AND deleted='0'";
        
        $data = $this->getArray($sql);
        if($data != FALSE){
            return $data[0];
        }
        return FALSE;
    }

    /**
    * Method to edit a department
    *
    * @access public
    * @param string $depId: The id of the department
    * @param string $name: The name of the department
    * @return string|bool $depId: The department id on success | FALSE on failure
    */
    public function editDepartment($depId, $name)
    {
        $this->_setDepartments();
        $table = $this->table;
        
        $data = $this->getDepartmentById($depId);
        if($data != FALSE){        
            $fields = array();
            $fields['department_name'] = $name;
            $fields['updated'] = date('Y-m-d H:i:s');
            $updated = $this->update('id', $depId, $fields);
            
            if($updated != FALSE){
                $comment = $this->objLanguage->languageText('word_edit');
                foreach($fields as $key=>$value){
                    if($data[$key] != $value){
                        $this->_addAuditRecord($table, $depId, $key, $data[$key], $value, $comment);
                    }
                }
	            return $depId;
		    }
		    return FALSE;
		}
		return FALSE;
    }    

/* ----- Functions for tbl_examiners_subjects ----- */

    /**
    * Method to add a subject
    *
    * @access public
    * @param string $depId: The id of the department
    * @param string $code: The code of the course
    * @param string $name: The name of the course
    * @param string $level: The level of the course
    * @return string|bool $subjId: The subject id on success | FALSE on failure
    */
    public function addSubject($depId, $code, $name, $level)
    {
        $this->_setSubjects();
        $table = $this->table;
        
        $fields = array();
        $fields['dep_id'] = $depId;
        $fields['course_code'] = $code;
        $fields['course_name'] = $name;
        $fields['course_level'] = $level;
        $fields['course_status'] = 0;
        $fields['deleted'] = 0;
        $fields['updated'] = date('Y-m-d H:i:s');
        $subjId = $this->insert($fields);
        
        if($subjId != FALSE){
         	$comment = $this->objLanguage->languageText('word_add');
            foreach($fields as $key=>$value){
                $this->_addAuditRecord($table, $subjId, $key, NULL, $value, $comment);
            }
	        return $subjId;
		}
		return FALSE;
    }
    
    /**
    * Method to delete a subject
    *
    * @access public
    * @param string $subjId: The id of the subject
    * @return string|bool $subjId: The subject id on success | FALSE on failure
    */
    public function deleteSubject($subjId)
    {
        $this->_setSubjects();
        $table = $this->table;
        
        $data = $this->getSubjectById($subjId);
        if($data != FALSE){        
            $fields = array();
            $fields['deleted'] = 1;
            $fields['updated'] = date('Y-m-d H:i:s');
            $updated = $this->update('id', $subjId, $fields);
            
            if($updated != FALSE){
                $comment = $this->objLanguage->languageText('word_delete');
                foreach($fields as $key=>$value){
                    $this->_addAuditRecord($table, $subjId, $key, $data[$key], $value, $comment);
                }
	            return $subjId;
		    }
		    return FALSE;
		}
		return FALSE;
    }
    
    /**
    * Method to get a subject by Id
    *
    * @access public
    * @param string $subjId: The id of the subject to get
    * @return array|bool $data: The subject data on success | FALSE on failure
    */
    public function getSubjectById($subjId)
    {
        $this->_setSubjects();
        
        $sql = "SELECT * FROM ".$this->table;
        $sql .= " WHERE id='".$subjId."'";
        $sql .= " AND deleted='0'";
        
        $data = $this->getArray($sql);
        if($data != FALSE){
            return $data[0];
        }
        return FALSE;
    }

    /**
    * Method to get subjects by department
    *
    * @access public
    * @param string $depId: The id of the department
    * @return array|bool $data: The subject data on success | FALSE on failure
    */
    public function getSubjectsByDepartment($depId)
    {
        $this->_setSubjects();
        
        $sql = "SELECT * FROM ".$this->table;
        $sql .= " WHERE dep_id='".$depId."'";
        $sql .= " AND deleted='0'";
        
        $data = $this->getArray($sql);
        if($data != FALSE){
            return $data;
        }
        return FALSE;
    }

    /**
    * Method to edit a subject
    *
    * @access public
    * @param string $subjId: The id of the subject
    * @param string $code: The code of the course
    * @param string $name: The name of the course
    * @param string $level: The level of the course
    * @param string $status: The status of the course
    * @return string|bool: The subject id on success | FALSE on failure
    */
    public function editSubject($subjId, $code, $name, $level, $status)
    {
        $this->_setSubjects();
        $table = $this->table;
        
        $data = $this->getSubjectById($subjId);
        if($data != FALSE){        
            $fields = array();
            $fields['course_code'] = $code;
            $fields['course_name'] = $name;
            $fields['course_level'] = $level;
            $fields['course_status'] = $status;
            $fields['updated'] = date('Y-m-d H:i:s');
            $updated = $this->update('id', $subjId, $fields);
            
            if($updated != FALSE){
                $comment = $this->objLanguage->languageText('word_edit');
                foreach($fields as $key=>$value){
                    if($data[$key] != $value){
                        $this->_addAuditRecord($table, $subjId, $key, $data[$key], $value, $comment);
                    }
                }
	            return $subjId;
		    }
		    return FALSE;
		}
		return FALSE;
    }    

/* ----- Functions for tbl_examiners_first ----- */

    /**
    * Method to add a first examiner
    *
    * @access public
    * @param string $depId: The id of the department
    * @param string $subjId: The id of the subject
    * @param string $examId: The id of the examiner
    * @param string $year: The year of the examination
    * @param string $comment: Any comments
    * @return string|bool $firstId: The first examiner id on success | FALSE on failure
    */
    public function updateFirst($depId, $subjId, $year, $examId, $comment)
    {
        $data = $this->getFirstByYear($depId, $subjId, $year);

        $this->_setFirst();
        $table = $this->table;
                
        $fields = array();
        $fields['dep_id'] = $depId;
        $fields['subj_id'] = $subjId;
        $fields['exam_id'] = $examId;
        $fields['year'] = $year;
        $fields['remarks'] = $comment;
        $fields['deleted'] = 0;
        $fields['updated'] = date('Y-m-d H:i:s');
        
        if($data == FALSE){
            $firstId = $this->insert($fields);        
            if($firstId != FALSE){
             	$comment = $this->objLanguage->languageText('word_add');
                foreach($fields as $key=>$value){
                    $this->_addAuditRecord($table, $firstId, $key, NULL, $value, $comment);
                }
	            return $firstId;
            }
            return FALSE;
        }else{
            $updated = $this->update('id', $data['eid'], $fields);
            if($updated != FALSE){
                 $comment = $this->objLanguage->languageText('word_edit');
                foreach($fields as $key=>$value){
                    if($data[$key] != $value){
                        $this->_addAuditRecord($table, $data['eid'], $key, $data[$key], $value, $comment);
                    }
                }
	            return $data['eid'];
		    }
		    return FALSE;
        }
        return FALSE;
    }
    
    /**
    * Method to delete first examiner
    *
    * @access public
    * @param string $depId: The id of the department
    * @param string $subjId: The id of the subject
    * @param string $year: The year
    * @return string|bool $first: The first examiner id on success | FALSE on failure
    */
    public function deleteFirst($depId, $subjId, $year)
    {
        $this->_setFirst();
        $table = $this->table;
        
        $data = $this->getFirstByYear($depId, $subjId, $year);
        if($data != FALSE){        
            $fields = array();
            $fields['deleted'] = 1;
            $fields['updated'] = date('Y-m-d H:i:s');
            $updated = $this->update('id', $data['eid'], $fields);
            
            if($updated != FALSE){
                $comment = $this->objLanguage->languageText('word_delete');
                foreach($fields as $key=>$value){
                    $this->_addAuditRecord($table, $data['eid'], $key, $data[$key], $value, $comment);
                }
	            return $data['eid'];
		    }
		    return FALSE;
		}
		return FALSE;
    }
    
    /**
    * Method to get examiners by year
    *
    * @access public
    * @param string $depId: The id of the department
    * @param string $subjId: The id of the subject
    * @param string $year: The year of the matrix
    * @return array|bool $data: The subject data on success | FALSE on failure
    */
    public function getFirstByYear($depId, $subjId, $year)
    {
        $this->_setFirst();
        
        $sql = "SELECT *, examiner.id AS eid FROM ".$this->table." AS examiner";
        $sql .= " LEFT JOIN tbl_examiners_users AS users";
        $sql .= " ON examiner.exam_id=users.id";
        $sql .= " WHERE examiner.dep_id='".$depId."'";
        $sql .= " AND examiner.subj_id='".$subjId."'";
        $sql .= " AND year='".$year."'";
        $sql .= " AND examiner.deleted='0'";
        
        $data = $this->getArray($sql);
        if($data != FALSE){
            return $data[0];
        }
        return FALSE;
    }

/* ----- Functions for tbl_examiners_second ----- */

    /**
    * Method to add a second examiner
    *
    * @access public
    * @param string $depId: The id of the department
    * @param string $subjId: The id of the subject
    * @param string $examId: The id of the examiner
    * @param string $year: The year of the examination
    * @param string $comment: Any comments
    * @return string|bool $secondId: The second examiner id on success | FALSE on failure
    */
    public function updateSecond($depId, $subjId, $year, $examId, $comment)
    {
        $data = $this->getSecondByYear($depId, $subjId, $year);

        $this->_setSecond();
        $table = $this->table;
                
        $fields = array();
        $fields['dep_id'] = $depId;
        $fields['subj_id'] = $subjId;
        $fields['exam_id'] = $examId;
        $fields['year'] = $year;
        $fields['remarks'] = $comment;
        $fields['deleted'] = 0;
        $fields['updated'] = date('Y-m-d H:i:s');
        
        if($data == FALSE){
            $secondId = $this->insert($fields);        
            if($secondId != FALSE){
             	$comment = $this->objLanguage->languageText('word_add');
                foreach($fields as $key=>$value){
                    $this->_addAuditRecord($table, $secondId, $key, NULL, $value, $comment);
                }
	            return $secondId;
            }
            return FALSE;
        }else{
            $updated = $this->update('id', $data['eid'], $fields);
            if($updated != FALSE){
                $comment = $this->objLanguage->languageText('word_edit');
                foreach($fields as $key=>$value){
                    if($data[$key] != $value){
                        $this->_addAuditRecord($table, $data['eid'], $key, $data[$key], $value, $comment);
                    }
                }
    	            return $data['eid'];
		    }
		    return FALSE;
        }
        return FALSE;
    }
    
    /**
    * Method to delete second examiner
    *
    * @access public
    * @param string $depId: The id of the department
    * @param string $subjId: The id of the subject
    * @param string $year: The year
    * @return string|bool $first: The first examiner id on success | FALSE on failure
    */
    public function deleteSecond($depId, $subjId, $year)
    {
        $this->_setSecond();
        $table = $this->table;
        
        $data = $this->getSecondByYear($depId, $subjId, $year);
        if($data != FALSE){        
            $fields = array();
            $fields['deleted'] = 1;
            $fields['updated'] = date('Y-m-d H:i:s');
            $updated = $this->update('id', $data['eid'], $fields);
            
            if($updated != FALSE){
                $comment = $this->objLanguage->languageText('word_delete');
                foreach($fields as $key=>$value){
                    $this->_addAuditRecord($table, $data['eid'], $key, $data[$key], $value, $comment);
                }
	            return $data['eid'];
		    }
		    return FALSE;
		}
		return FALSE;
    }
    
    /**
    * Method to get examiners by year
    *
    * @access public
    * @param string $depId: The id of the department
    * @param string $subjId: The id of the subject
    * @param string $year: The year of the matrix
    * @return array|bool $data: The subject data on success | FALSE on failure
    */
    public function getSecondByYear($depId, $subjId, $year)
    {
        $this->_setSecond();
        
        $sql = "SELECT *, examiner.id AS eid FROM ".$this->table." AS examiner";
        $sql .= " LEFT JOIN tbl_examiners_users AS users";
        $sql .= " ON examiner.exam_id=users.id";
        $sql .= " WHERE examiner.dep_id='".$depId."'";
        $sql .= " AND examiner.subj_id='".$subjId."'";
        $sql .= " AND year='".$year."'";
        $sql .= " AND examiner.deleted='0'";
        
        $data = $this->getArray($sql);
        if($data != FALSE){
            return $data[0];
        }
        return FALSE;
    }

/* ----- Functions for tbl_examiners_moderate ----- */

    /**
    * Method to add a moderation examiner
    *
    * @access public
    * @param string $depId: The id of the department
    * @param string $subjId: The id of the subject
    * @param string $examId: The id of the examiner
    * @param string $year: The year of the examination
    * @param string $comment: Any comments
    * @return string|bool $modId: The moderating examiner id on success | FALSE on failure
    */
    public function updateModerate($depId, $subjId, $year, $examId, $comment)
    {
        $data = $this->getModerateByYear($depId, $subjId, $year);

        $this->_setModerate();
        $table = $this->table;
                
        $fields = array();
        $fields['dep_id'] = $depId;
        $fields['subj_id'] = $subjId;
        $fields['exam_id'] = $examId;
        $fields['year'] = $year;
        $fields['remarks'] = $comment;
        $fields['deleted'] = 0;
        $fields['updated'] = date('Y-m-d H:i:s');
        
        if($data == FALSE){
            $modId = $this->insert($fields);        
            if($modId != FALSE){
             	$comment = $this->objLanguage->languageText('word_add');
                foreach($fields as $key=>$value){
                    $this->_addAuditRecord($table, $modId, $key, NULL, $value, $comment);
                }
	            return $modId;
            }
            return FALSE;
        }else{
            $updated = $this->update('id', $data['eid'], $fields);
            if($updated != FALSE){
                $comment = $this->objLanguage->languageText('word_edit');
                foreach($fields as $key=>$value){
                    if($data[$key] != $value){
                        $this->_addAuditRecord($table, $data['eid'], $key, $data[$key], $value, $comment);
                    }
                }
	            return $data['eid'];
		    }
		    return FALSE;
        }
        return FALSE;
    }
    
    /**
    * Method to delete moderation examiner
    *
    * @access public
    * @param string $depId: The id of the department
    * @param string $subjId: The id of the subject
    * @param string $year: The year
    * @return string|bool $first: The first examiner id on success | FALSE on failure
    */
    public function deleteModerate($depId, $subjId, $year)
    {
        $this->_setModerate();
        $table = $this->table;
        
        $data = $this->getModerateByYear($depId, $subjId, $year);
        if($data != FALSE){        
            $fields = array();
            $fields['deleted'] = 1;
            $fields['updated'] = date('Y-m-d H:i:s');
            $updated = $this->update('id', $data['eid'], $fields);
            
            if($updated != FALSE){
                $comment = $this->objLanguage->languageText('word_delete');
                foreach($fields as $key=>$value){
                    $this->_addAuditRecord($table, $data['eid'], $key, $data[$key], $value, $comment);
                }
	            return $data['eid'];
		    }
		    return FALSE;
		}
		return FALSE;
    }
    
    /**
    * Method to get examiners by year
    *
    * @access public
    * @param string $depId: The id of the department
    * @param string $subjId: The id of the subject
    * @param string $year: The year of the matrix
    * @return array|bool $data: The subject data on success | FALSE on failure
    */
    public function getModerateByYear($depId, $subjId, $year)
    {
        $this->_setModerate();
        
        $sql = "SELECT *, examiner.id AS eid FROM ".$this->table." AS examiner";
        $sql .= " LEFT JOIN tbl_examiners_users AS users";
        $sql .= " ON examiner.exam_id=users.id";
        $sql .= " WHERE examiner.dep_id='".$depId."'";
        $sql .= " AND examiner.subj_id='".$subjId."'";
        $sql .= " AND year='".$year."'";
        $sql .= " AND examiner.deleted='0'";
        
        $data = $this->getArray($sql);
        if($data != FALSE){
            return $data[0];
        }
        return FALSE;
    }

/* ----- Functions for tbl_examiners_alternate ----- */

    /**
    * Method to add a alternate examiner
    *
    * @access public
    * @param string $depId: The id of the department
    * @param string $subjId: The id of the subject
    * @param string $examId: The id of the examiner
    * @param string $year: The year of the examination
    * @param string $comment: Any comments
    * @return string|bool $altId: The alternate examiner id on success | FALSE on failure
    */
    public function updateAlternate($depId, $subjId, $year, $examId, $comment)
    {
        $data = $this->getAlternateByYear($depId, $subjId, $year);

        $this->_setAlternate();
        $table = $this->table;
                
        $fields = array();
        $fields['dep_id'] = $depId;
        $fields['subj_id'] = $subjId;
        $fields['exam_id'] = $examId;
        $fields['year'] = $year;
        $fields['remarks'] = $comment;
        $fields['deleted'] = 0;
        $fields['updated'] = date('Y-m-d H:i:s');
        
        if($data == FALSE){
            $altId = $this->insert($fields);        
            if($altId != FALSE){
             	$comment = $this->objLanguage->languageText('word_add');
                foreach($fields as $key=>$value){
                    $this->_addAuditRecord($table, $altId, $key, NULL, $value, $comment);
                }
	            return $altId;
            }
            return FALSE;
        }else{
            $updated = $this->update('id', $data['eid'], $fields);
            if($updated != FALSE){
                $comment = $this->objLanguage->languageText('word_edit');
                foreach($fields as $key=>$value){
                    if($data[$key] != $value){
                        $this->_addAuditRecord($table, $data['eid'], $key, $data[$key], $value, $comment);
                    }
                }
	            return $data['eid'];
		    }
		    return FALSE;
        }
        return FALSE;
    }
    
    /**
    * Method to delete alternate examiner
    *
    * @access public
    * @param string $depId: The id of the department
    * @param string $subjId: The id of the subject
    * @param string $year: The year
    * @return string|bool $first: The first examiner id on success | FALSE on failure
    */
    public function deleteAlternate($depId, $subjId, $year)
    {
        $this->_setAlternate();
        $table = $this->table;
        
        $data = $this->getAlternateByYear($depId, $subjId, $year);
        if($data != FALSE){        
            $fields = array();
            $fields['deleted'] = 1;
            $fields['updated'] = date('Y-m-d H:i:s');
            $updated = $this->update('id', $data['eid'], $fields);
            
            if($updated != FALSE){
                $comment = $this->objLanguage->languageText('word_delete');
                foreach($fields as $key=>$value){
                    $this->_addAuditRecord($table, $data['eid'], $key, $data[$key], $value, $comment);
                }
	            return $data['eid'];
		    }
		    return FALSE;
		}
		return FALSE;
    }
    
    /**
    * Method to get examiners by year
    *
    * @access public
    * @param string $depId: The id of the department
    * @param string $subjId: The id of the subject
    * @param string $year: The year of the matrix
    * @return array|bool $data: The subject data on success | FALSE on failure
    */
    public function getAlternateByYear($depId, $subjId, $year)
    {
        $this->_setAlternate();
        
        $sql = "SELECT *, examiner.id AS eid FROM ".$this->table." AS examiner";
        $sql .= " LEFT JOIN tbl_examiners_users AS users";
        $sql .= " ON examiner.exam_id=users.id";
        $sql .= " WHERE examiner.dep_id='".$depId."'";
        $sql .= " AND examiner.subj_id='".$subjId."'";
        $sql .= " AND year='".$year."'";
        $sql .= " AND examiner.deleted='0'";
        
        $data = $this->getArray($sql);
        if($data != FALSE){
            return $data[0];
        }
        return FALSE;
    }

/* ----- Functions for tbl_examiners_remarking ----- */

    /**
    * Method to add a remarking examiner
    *
    * @access public
    * @param string $depId: The id of the department
    * @param string $subjId: The id of the subject
    * @param string $examId: The id of the examiner
    * @param string $year: The year of the examination
    * @param string $comment: Any comments
    * @return string|bool $remId: The remarking examiner id on success | FALSE on failure
    */
    public function updateRemarking($depId, $subjId, $year, $examId, $comment)
    {
        $data = $this->getRemarkingByYear($depId, $subjId, $year);

        $this->_setRemarking();
        $table = $this->table;
                
        $fields = array();
        $fields['dep_id'] = $depId;
        $fields['subj_id'] = $subjId;
        $fields['exam_id'] = $examId;
        $fields['year'] = $year;
        $fields['remarks'] = $comment;
        $fields['deleted'] = 0;
        $fields['updated'] = date('Y-m-d H:i:s');
        
        if($data == FALSE){
            $remId = $this->insert($fields);        
            if($remId != FALSE){
             	$comment = $this->objLanguage->languageText('word_add');
                foreach($fields as $key=>$value){
                    $this->_addAuditRecord($table, $remId, $key, NULL, $value, $comment);
                }
	            return $remId;
            }
            return FALSE;
        }else{
            $updated = $this->update('id', $data['eid'], $fields);
            if($updated != FALSE){
                $comment = $this->objLanguage->languageText('word_edit');
                foreach($fields as $key=>$value){
                    if($data[$key] != $value){
                        $this->_addAuditRecord($table, $data['eid'], $key, $data[$key], $value, $comment);
                    }
                }
	            return $data['eid'];
		    }
		    return FALSE;
        }
        return FALSE;
    }
    
    /**
    * Method to delete remarking examiner
    *
    * @access public
    * @param string $depId: The id of the department
    * @param string $subjId: The id of the subject
    * @param string $year: The year
    * @return string|bool $first: The first examiner id on success | FALSE on failure
    */
    public function deleteRemarking($depId, $subjId, $year)
    {
        $this->_setRemarking();
        $table = $this->table;
        
        $data = $this->getRemarkingByYear($depId, $subjId, $year);
        if($data != FALSE){        
            $fields = array();
            $fields['deleted'] = 1;
            $fields['updated'] = date('Y-m-d H:i:s');
            $updated = $this->update('id', $data['eid'], $fields);
            
            if($updated != FALSE){
                $comment = $this->objLanguage->languageText('word_delete');
                foreach($fields as $key=>$value){
                    $this->_addAuditRecord($table, $data['eid'], $key, $data[$key], $value, $comment);
                }
	            return $data['eid'];
		    }
		    return FALSE;
		}
		return FALSE;
    }
    
    /**
    * Method to get examiners by year
    *
    * @access public
    * @param string $depId: The id of the department
    * @param string $subjId: The id of the subject
    * @param string $year: The year of the matrix
    * @return array|bool $data: The subject data on success | FALSE on failure
    */
    public function getRemarkingByYear($depId, $subjId, $year)
    {
        $this->_setRemarking();
        
        $sql = "SELECT *, examiner.id AS eid FROM ".$this->table." AS examiner";
        $sql .= " LEFT JOIN tbl_examiners_users AS users";
        $sql .= " ON examiner.exam_id=users.id";
        $sql .= " WHERE examiner.dep_id='".$depId."'";
        $sql .= " AND examiner.subj_id='".$subjId."'";
        $sql .= " AND year='".$year."'";
        $sql .= " AND examiner.deleted='0'";
        
        $data = $this->getArray($sql);
        if($data != FALSE){
            return $data[0];
        }
        return FALSE;
    }
}
?>