<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check

/**
* Model controller for the table tbl_phonebook
* @author Jacques Cilliers <2618315@uwc.ac.za>  
* @copyright 2007 University of the Western Cape
*/

class phonebook extends controller
{
				public $objLanguage;
				public $objConfig;
        public $objDbContacts;
        public $objUser;

    /**
     * Constructor method to instantiate objects and get variables
     */
    public function init()
    {
        try {
            $this->objUser = $this->getObject('user', 'security');
            $this->objDbContacts = $this->getObject('dbContacts', 'phonebook');
            $this->objLanguage = $this->getObject('language', 'language');
            $this->objConfig = $this->getObject('altconfig', 'config');
        }
        catch(customException $e) {
            echo customException::cleanUp();
            die();
        }
    }
    /**
     * Method to process actions to be taken
     *
     * @param string $action String indicating action to be taken
     */
    public function dispatch($action = Null)
    {
        switch ($action) {
            default:
           case 'default':
//var_dump($this->objDbContacts->listAll($this->objUser->userId()));
                $userId = $this->objUser->userId();
                $records=$this->objDbContacts->listAll($userId);
		$this->setVarByRef('records', $records);                
		return 'view_tpl.php';
                break;

           case 'link':
		return 'addentry_tpl.php';
	        break;

           case 'addentry';	
                $firstname = $this->getParam('firstname');
                $lastname = $this->getParam('lastname');
		$emailaddress = $this->getParam('emailaddress');
		$cellnumber = $this->getParam('cellnumber');
		$landlinenumber = $this->getParam('landlinenumber'); 
		$this->objDbContacts->insertRecord($userid, $firstname, $lastname, $emailaddress, $cellnumber, $landlinenumber); 
		return 'view_tpl.php';         
            	  break;
            	
           case 'editentry':
     	          $id = $this->getParam('id');
                $this->objDbContacts->updateRec($id);
                break;
            	
           case 'deleteentry':
	   			     $id = $this->getParam('id');
               $this->objDbContacts->deleteRec($id);
               break;
        }
    }
}
?>