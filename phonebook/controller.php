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

           case 'addentry';	

						// Get Details from Form
            $firstname = $this->getParam('firstname');
            $lastname = $this->getParam('lastname');
		        $emailaddress = $this->getParam('emailaddress');
		        $cellnumber = $this->getParam('cellnumber');
		        $landlinenumber = $this->getParam('landlinenumber');
		        $address = $this->getParam('address');
		        $this->objDbContacts->insertRecord($userid, $firstname, $lastname, $emailaddress, $cellnumber, $landlinenumber, $address); 
            $this->nextAction('');
            	  break;
            
		case 'link':
		return 'addentry_tpl.php';
	        break;	
           
        case 'editentry':
            $id = $this->getParam('id');
						$oldrec = $this->objDbContacts->listSingle($id);
            $this->setVarByRef('oldrec', $oldrec);
            return 'editentry_tpl.php';
						
           case 'updateentry':
						// Get Details from Form
            $id = $this->getParam('id');
						$firstname = $this->getParam('firstname');
            $lastname = $this->getParam('lastname');
		        $emailaddress = $this->getParam('emailaddress');
		        $cellnumber = $this->getParam('cellnumber');
		        $landlinenumber = $this->getParam('landlinenumber');
		        $address = $this->getParam('address');
            
            $this->objUser = $this -> getObject('user', 'security');
						$arrayOfRecords = array('userid' =>$this ->objUser ->userId(), 
                                    'firstname' => $firstname, 
                                    'lastname' => $lastname, 
                                    'emailaddress' => $emailaddress, 
                                    'cellnumber' => $cellnumber, 
                                    'landlinenumber' => $landlinenumber,
                                    'address' => $address, 
                                    'created_by' =>$this ->objDbContacts->now());

            $this->objDbContacts->updateRec($id, $arrayOfRecords);
						//$records=$this->objDbContacts->listAll($userId);
		        //$this->setVarByRef('records', $records);                
						//print_r($arrayOfRecords); die();
            return $this->nextAction('view_tpl.php'); 
					  break;
						
					
						
           

     //return 'editentry_tpl.php';
                //$this->objDbContacts->updateRec($this->getParam('id'));
						//return $this->nextAction('view_tpl.php');
                //break;
            	
           case 'deleteentry':
          // var_dump($this->objDbContacts->listAll($this->getParam('id')));
	   		   $this->objDbContacts->deleteRec($this->getParam('id'));
	   		   return $this->nextAction('view_tpl.php'); 
	           break;
        }
    }
}
?>
