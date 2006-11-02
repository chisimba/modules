<?php
//create a class to display the faculties and related courses
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check
/**
* This object generates the various faculties and their associated courses
* @package 
* @category sems
* @copyright 2004, University of the Western Cape & AVOIR Project
* @license GNU GPL
* @version
* @author Colleen Tinker
*/
class faculty extends object{ 

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
/*------------------------------------------------------------------------------*/

  public function displayfaculty(){
  
        //$facultylist = new dropdown('searchlist');
    
        $faculty[0] = '****Please Select an Option****';
        $faculty[1] = 'Natural Science';
        $faculty[2] = 'Arts';
        $faculty[3] = 'Community & Health Sciences';
        $faculty[4] = 'Education';
        $faculty[5] = 'Dentistry';
        $faculty[6] = 'Law';
        $faculty[7] = 'Economics & Managemet of Sciences';
        
    
        $this->setSession('faculty',$faculty);
    
    }
/*------------------------------------------------------------------------------*/

 
}//end of class	
?>
