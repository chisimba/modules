<?php
/**
* dbregister class extends dbtable
* @package conferenceprelogin
* @filesource
*/

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']){
    die("You cannot view this page directly");
}

/**
* Additional user details - extends tbl_users
*
* @author Megan Watson
* @copyright (c) 2004-2006 UWC
* @version 0.1
*/

class dbregister extends dbtable
{
    /**
    * Constructor method
    */
    function init()
    {
        parent::init('tbl_conference_register');
        $this->table = 'tbl_conference_register';
        
        $this->objUser = $this->getObject('user', 'security');
        $this->objGroup =  $this->getObject('groupadminmodel', 'groupadmin');

    }
    
    /**
    * Get a users conference details
    *
    * @access public
    * @return array $data
    */
    function getDetails($userId)
    {
        
        $sql = 'SELECT * FROM '.$this->table.' AS conf, tbl_users AS user ';
        $sql .= "WHERE conf.userId = user.userId AND conf.userId = '{$userId}'   AND contextCode = '{$this->contextCode}' 
        ORDER by user.surname";
        
        $data = $this->getArray($sql);
        
        if(!empty($data)){
            return $data[0];
        }
        return array();
    }
    
    /**
    * Insert a new user registration 
    *
    * @access public
    * @param string $id The configuration id if exists
    * @return string $id The configuration id
    */
    function addDetails($userId, $id = NULL)
    {
        $fields = array();
        $fields['initials'] = $this->getParam('initials');
        $fields['organisation'] = $this->getParam('organisation');
        $fields['nameBadge'] = $this->getParam('badge');
        $fields['tel'] = $this->getParam('tel');
        $fields['fax'] = $this->getParam('fax');
        $fields['regType'] = $this->getParam('regType');
        $fields['flights'] = 'no';
        $fields['transfers'] = 'no';
        $fields['carhire'] = 'no';
	
        
        $add = $this->getParam('transfers');
        if(isset($add) && !empty($add)){
            foreach($add as $item){
                $fields[$item] = 'yes';
            }
        }
    
	 
        if(!empty($id)){
            $fields['modifierId'] = $this->objUser->userId();
            $fields['dateModified'] = date('Y-m-d H:i');
            
            $this->update('id', $id, $fields);
        }else{
            $fields['userId'] = $userId;
            $fields['dateCreated'] = date('Y-m-d H:i');
            $fields['contextCode'] = $this->contextCode;
            
            $id = $this->insert($fields);
        }	
	
        return $id; 

    }
    
    /**
    * Set a flag to indicate that the delegate has paid.
    *
    * @access public
    * @param string $id The row id of the delegate in the register table
    * @param bool $yes Set as paid, if False, set as not paid
    * @return void
    */
    function setAsPaid($id, $yes = TRUE)
    {
        $fields['modifierId'] = $this->objUser->userId();
        $fields['dateModified'] = date('Y-m-d H:i');
        if($yes){
            $fields['paid'] = 'yes';
        }else{
            $fields['paid'] = 'no';
        }
        
        $this->update('id', $id, $fields);
    }
    
    /**
    * Set a flag to indicate that the delegate has been invoiced.
    *
    * @access public
    * @param string $id The row id of the delegate in the register table
    * @return void
    */
    function setAsInvoiced($id)
    {
        $fields['modifierId'] = $this->objUser->userId();
        $fields['dateModified'] = date('Y-m-d H:i');
        $fields['invoiced'] = 'yes';
        
        $this->update('id', $id, $fields);
    }
    
    /**
    * Cancellation of a delegate - set status to cancelled and supply a reason
    *
    * @access public
    * @param string $id The row id of the delegate in the register table
    * @return void
    */
    function setAsCancelled($id)
    {
        $fields['modifierId'] = $this->objUser->userId();
        $fields['dateModified'] = date('Y-m-d H:i');
        $fields['status'] = 'cancelled';
        $fields['reason'] = $this->getParam('reason');
        
        $this->update('id', $id, $fields);
    }

    /**
    * Get a list of delegates for the conference - join the tbl_users table
    *
    * @access public
    * @param string $filter Filter to apply to the delegate list
    * @return array The list
    */
    function getDelegateList($filter = NULL)
    {
        $groupId = $this->objGroup->getLeafId(array($this->contextCode, 'Students'));
        
        $sql = 'SELECT *, conf.id AS confId FROM tbl_groupadmin_groupuser AS tgroup ';
        $sql .= 'LEFT JOIN tbl_users AS user ON (user.id = tgroup.user_id) ';
        $sql .= "LEFT JOIN {$this->table} AS conf ON (conf.userId = user.userId) ";
        $sql .= "WHERE conf.contextCode = '{$this->contextCode}' AND tgroup.group_id = '{$groupId}'
        ORDER BY user.surname";
        
        if(isset($filter) && !empty($filter)){
            $sql .= " AND conf.status = '{$filter}'";
        }
        
        $data = $this->getArray($sql);
        return $data;
    }
}
?>