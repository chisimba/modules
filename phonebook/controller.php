<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check

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
            $this->objDbContacts = $this->getObject('dbcontacts', 'phonebook');
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
            	$method=$this->_getMethod();
 		break;

           case 'addentry':
                $firstname = $this->getParam('firstname');
                $lastname = $this->getParam('lastname');
		$emailaddress = $this->getParam('emailaddress');
		$cellnumber = $this->getParam('cellnumber');
		$landlinenumber = $this->getParam('landlinenumber'); 
		$this->objDbContacts->insertRec($fields);          
                return 'addentry_tpl.php';
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
