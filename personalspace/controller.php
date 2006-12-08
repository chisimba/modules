<?php
// security check - must be included in all scripts
//if (!$GLOBALS['kewl_entry_point_run'])
//{
//	die("You cannot view this page directly");
//}
// end security check

/**
* Controller for Personal Space module - This module missing most of extra functionality (just basic page)
* @author PHP4 module by Jeremy O'Connor (ported to PHP 5 by Alastair Pursch)
* @copyright 2006 University of the Western Cape
* $Id$
*/
class personalspace extends controller
{
    /*User object for security
    @var object $objUser */
    public $objUser;
    //var $dbPersonalInfoImport;

    /**
    * The Init function
    */
    public function init()
    {
        $this->objUser =& $this->getObject('user', 'security');
        //$this->objHelp=& $this->getObject('helplink','help');
        //$this->objHelp->rootModule="helloworld";
        //$this->dbPersonalInfoImport =& $this->getObject('dbPersonalInfoImport');
        //Get the activity logger class
        $this->objLog=$this->newObject('logactivity', 'logger');
        //Set it to log once per session
        //$this->objLog->logOncePerSession = TRUE;
        //Log this module call
        $this->objLog->log();
    }

    /**
    * The dispatch funtion
    * @param string $action The action
    * @return string The content template file
    */
    public function dispatch($action=Null)
    {
    	try{
	        //$this->objConfig = &$this->getObject('altconfig','config');
	        //$systemType = $this->objConfig->getValue("SYSTEM_TYPE", "contextabstract");
	        //$isAlumni = ($systemType == "alumni");
	        //$this->setVar('isAlumni',$isAlumni);
	        // 1. ignore action at moment as we only do one thing - say hello
	        // 2. load the data object (calls the magical getObject which finds the
	        //    appropriate file, includes it, and either instantiates the object,
	        //    or returns the existing instance if there is one. In this case we
	        //    are not actually getting a data object, just a helper to the
	        //    controller.
	        // 3. Pass variables to the template
	        $this->setVarByRef('objUser', $this->objUser);
	        //$this->setVarByRef('objHelp', $this->objHelp);
	        // return the name of the template to use  because it is a page content template
	        // the file must live in the templates/content subdir of the module directory
			/*
	        if ($isAlumni) {
	            $list = $this->dbPersonalInfoImport->listSingle($this->objUser->userId());
	            $element = $list[0];
	            $this->setVarByRef('element',$element);
	        }
			*/

			// Get # of unread emails
	        $objModules =& $this->newObject('modules','modulecatalogue');
			$email = $objModules->checkIfRegistered('email');
			$this->setVarByRef("email", $email);
			$this->setLayoutTemplate("user_layout_tpl.php");
	        // Added by Tohir - Test User Page
	        //$this->setPageTemplate("user_page_tpl.php");
	        //$this->setLayoutTemplate("user_layout_tpl.php");
	        //$this->setPageTemplate("user_page_tpl.php");
	        //switch($action){

				// Get list of buddies
				//default:
			//} // switch

			// Get Emails
	        if ($email) {
/*
	    		$objDbEmail =& $this->getObject('dbemail');
	    		$new = $objDbEmail->listAll($this->objUser->userId());
	    		$unread = $new[0]['count(sender_id)'];
	    		$this->setVarByRef("unread", $unread);
	    		$kngmail =& $this->getObject('email', 'mail');
	    		$emails = $kngmail->send();
	    		$emails = array_reverse($emails);

*/
                $this -> objDbEmail = & $this->getObject('dbrouting','email');
                $emails=$this -> objDbEmail->getAllMail('init_1',array(1=>3,2=>'DESC'),NULL);
                $this->setVarByRef('emails', $emails);
	        }

	        $objModules =& $this->newObject('modules','modulecatalogue');
	        if($objModules->checkIfRegistered('homepage')){
	            $homepage = true;
	        } else {
	            $homepage = false;
	        }
			$this->setVarByRef("homepage", $homepage);

			return "main_tpl.php";
    	}//end try

    	catch (Exception $e){
    		echo customException::cleanUp('Caught Exception: '.$e->getMessage());
    		exit();
    	}
    }
}
?>
