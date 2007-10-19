<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check

/**
* Model controller for the table tbl_phonebook
* @authors:Godwin Du Plessis, Ewan Burns, Helio Rangeiro, Jacques Cilliers, Luyanda Mgwexa, George Amabeoku, Charl Daniels, and Qoane Seitlheko.  
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
    }//end of init function
		
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

                $userId = $this->objUser->userId();
                $records=$this->objDbContacts->listAll($userId);
		        $this->setVarByRef('records', $records);                
				return 'view_tpl.php';
           break;

           case 'addentry';	
// Get Details from Form
				$userId = $this->objUser->userId();
				$firstname = htmlentities($this->getParam('firstname'), ENT_QUOTES);
				$lastname = htmlentities($this->getParam('lastname'), ENT_QUOTES);
	       	    $emailaddress = htmlentities($this->getParam('emailaddress'), ENT_QUOTES);
				$cellnumber = htmlentities($this->getParam('cellnumber'), ENT_QUOTES);
	       	    $landlinenumber = htmlentities($this->getParam('landlinenumber'), ENT_QUOTES);
	        	$address = htmlentities($this->getParam('address'), ENT_QUOTES);
				$this->objDbContacts->insertRecord($userId, $firstname, $lastname, $emailaddress, $cellnumber,	 			$landlinenumber, $address); 
		$this->nextAction('');
	   break;
            
	   case 'link':
	   return 'addentry_tpl.php';
	   break;	
           
           case 'editentry':
		$id = html_entity_decode($this->getParam('id'));
		$oldrec = $this->objDbContacts->listSingle($id);
		$this->setVarByRef('oldrec', $oldrec);
          return 'editentry_tpl.php';
// Get Details from Form						
          case 'updateentry':
		$id = $this->getParam('id');
		$firstname = htmlentities($this->getParam('firstname'));
		$lastname = htmlentities($this->getParam('lastname'));
	        $emailaddress = htmlentities($this->getParam('emailaddress'));
	        $cellnumber = htmlentities($this->getParam('cellnumber'));
	        $landlinenumber = htmlentities($this->getParam('landlinenumber'));
	        $address = htmlentities($this->getParam('address'));
            
		$this->objUser = $this -> getObject('user', 'security');
		$arrayOfRecords = array('userid' =>$this ->objUser ->userId(), 
                                        'firstname' => $firstname, 
                                        'lastname' => $lastname, 
                                        'emailaddress' => $emailaddress, 
                                        'cellnumber' => $cellnumber, 
                                        'landlinenumber' => $landlinenumber,
                                        'address' => $address, 
                                        'modified' =>$this ->objDbContacts->now());

		$this->objDbContacts->updateRec($id, $arrayOfRecords);
		return $this->nextAction('view_tpl.php'); 
	  break;
						
// delete entry	
          case 'deleteentry':
		$this->objDbContacts->deleteRec($this->getParam('id'));
		return $this->nextAction('view_tpl.php'); 
	  break;
        }//end of switch
    }//end of dispatch function
}//end of class
?>
