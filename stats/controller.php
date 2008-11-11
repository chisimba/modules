<?php
/* -------------------- stats class extends controller ----------------*/
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
    die("You cannot view this page directly");
}
// end security check
                                                                                                                                             
/**
* Controller class for the stats module
* @copyright 2004 KEWL.NextGen
* @author James Scoble
*
* $Id: controller.php
*/

class stats extends controller
{

    public function init()
    {
        $this->objConfig= $this->getObject('altconfig', 'config');
        $this->objUser= $this->getObject('user', 'security');
        $this->userId=$this->objUser->userId();
        $this->objLanguage=  $this->getObject('language','language');
    }
    /**
    * This is the main method in the class
    * It calls other functions depending on the value of $action
    * @param string $action
    */
    public function dispatch($action=Null)
    {
        switch ($action)
        {

        default:
            return ('default_tpl.php');
        }
    }


    /** 
    * Method to determine if the user has to be logged in or not
    */
    public function requiresLogin() // overides that in parent class
    {
        $action=$this->getParam('action','NULL');
        if ($action===NULL){
            return FALSE;
        }
        return TRUE;
    }
    
}
?>
