<?php
//class used to read all schoolnames form a file and then populate dropdown list
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check
/**
* This object generates data used to populate all dropdwon list 
* @package 
* @category sems
* @copyright 2004, University of the Western Cape & AVOIR Project
* @license GNU GPL
* @version
* @author Colleen Tinker
*/
class searchinfo extends object{ 

  protected $_objUser;
 
	public function init()
	{
	 try {
      //Load Classes
      $this->loadClass('dropdown', 'htmlelements');
      //$this->_objUser = & $this->newObject('user', 'security');
			$this->objLanguage = &$this->newObject('language', 'language');
			
		}catch (Exception $e){
       		echo 'Caught exception: ',  $e->getMessage();
        	exit();
    }
	}
	
	public function readfile(){
    
    //open file
    //$file_to_read = @fopen("schoolnames.exl")
  }

}
?>
