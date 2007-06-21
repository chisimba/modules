<?php
/**
* dbusers class extends object
* @package hivaids
* @filesource
*/

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']){
    die("You cannot view this page directly");
}

/**
* dbusers class
* @author Megan Watson
* @copyright (c) 2007 UWC
* @version 0.1
*/

class dbusers extends dbtable
{
    /**
    * Constructor method
    */
    public function init()
    {
        parent::init('tbl_hivaids_users');
        $this->table = 'tbl_hivaids_users';
        $this->tblLogin = 'tbl_userloginhistory';
        
        $this->objUser = $this->getObject('user', 'security');
        $this->userId = $this->objUser->userId();
    }
    
    /**
    * Method to add a new user
    *
    * @access public
    * @param string $id The row id of the user
    * @return void
    */
    public function addUser($userId = NULL, $id = NULL)
    {
        $fields = array();
        $fields['staff_student'] = $this->getParam('staff_student');
        $fields['course'] = $this->getParam('course');
        $fields['study_year'] = $this->getParam('yearstudy');
        $fields['updated'] = $this->now();
        
        if(!empty($id)){
            $fields['modifierid'] = $this->userId;
            $this->update('id', $id, $fields);
        }else{
            $fields['user_id'] = $userId;
            $fields['datecreated'] = $this->now();
            $id = $this->insert($fields);
        }
    }
    
    /**
    * Method to get the login history count from the database along with the users details
    *
    * @access public
    * @return array $data
    */
    public function getLoginHistory()
    {
        $order= $this->getParam('order', 'surname');
        $sql="SELECT count(tbl_userloginhistory.userid) 
          AS logins, max(lastlogindatetime) 
          AS lastOn, tbl_users.title, tbl_users.firstname,
          tbl_users.surname, tbl_users.country, 
          tbl_users.emailaddress, tbl_users.userid FROM  tbl_userloginhistory
          LEFT JOIN tbl_users  ON tbl_userloginhistory.userid = tbl_users.userid
          GROUP BY tbl_userloginhistory.userid
          ORDER BY " . $order;
        return $this->getArray($sql);
    }
}
?>