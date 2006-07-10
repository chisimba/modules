<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
	die("You cannot view this page directly");
}
// end security check

/**
* Module class to display a splashscreen - a prelogin module

*/


class serverlist extends controller 
{
    /**
     * 
     */
    protected  $objDBServerList;
    
  /**
   * Constructor
   */
  public function init()
  {
      try {
        $this->objDBServerList = & $this->newObject('dbserverlist', 'serverlist');
      }
      catch (customException $e)
        {
        	echo customException::cleanUp($e);
        	die();
        }
      
  }
    
    /**
     * The dispatch method
     */
    public function dispatch()
    {
        try {
            $action = $this->getParam('action');
            
            switch ($action) 
            {    
                default :
                
                case 'getxml':
                    
                    $this->setPageTemplate('xml_page_layout.php');
                    
                    $this->setLayoutTemplate('xml_layout_tpl.php');
                    
                    $this->setVar('xml', $this->objDBServerList->generateXML());
                    
                    return 'xml_tpl.php';
                    
            }
        }
        catch (customException $e)
        {
        	echo customException::cleanUp($e);
        	die();
        }
    }
    
    	/** 
	* This is a method to determine if the user has to be logged in or not
    */
     public function requiresLogin() // overides that in parent class
     {

         return FALSE;

     }
}
?>