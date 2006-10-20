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

  public function displaycourses(){
  
      $facultydata  = $this->getSession('faculty');
      
      $course  = new dropdown('course');
      
      foreach($facultydata as $sessfacdata){
        
        $sessfacdata = 0;
        switch($sessfacdata){
        
            case 1:
                $course->addOption('B.Sc Computer Science','B.Sc Computer Science');
                $course->addOption('B.Pharm','B.Pharm');
            break;
            
            case 2:
                $course->addOption('art','art');
                $course->addOption('B.Pharm','B.Pharm');
            break;
            
            case 3:
                $course->addOption('community','community');
                $course->addOption('B.Pharm','B.Pharm');
            break;
            
            case 4:
                $course->addOption('Education','Education');
                $course->addOption('B.Pharm','B.Pharm');
            break;
            
            case 5:
                $course->addOption('dentistry','dentistry');
                $course->addOption('B.Pharm','B.Pharm');
            break;
            
            case 6:
                $course->addOption('Law','Law');
                $course->addOption('B.Pharm','B.Pharm');
            break;
            
            case 7:
                $course->addOption('Economics & Managemet of Sciences','Economics & Managemet of Sciences');
                $course->addOption('B.Pharm','B.Pharm');
            break;
            
            default:
                $course->addOption('');
              
        }
        
      }
      return $course->show();
  
  }
/*------------------------------------------------------------------------------*/
  public function facultycourselist(){
  
  }  
}//end of class	
?>
