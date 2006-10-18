<?php
//class used to populate all search dropdown list
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

/*------------------------------------------------------------------------------*/	
	public function slusearchlist(){
          
        $searchlist  = new dropdown('searchlist');
        /**
        *create a dropdown list containg all search options for information cards captured by the student
        */
        
        $searchstudslu[0] = '****Please Select an Option****';
        $searchstudslu[1] = 'All matriculants that completed information cards';
        $searchstudslu[2] = 'All matriculants from a certain school';
        $searchstudslu[3] = 'All matriculants qualifying for an exemption';
        $searchstudslu[4] = 'All matriculants that have the relevant subjects';
        $searchstudslu[5] = 'All prospective students by faculty';
        $searchstudslu[6] = 'All prospective students by course'; 
        $searchstudslu[7] = 'All prospective students by area';
        $searchstudslu[8] = 'All SD Cases';
        
        foreach($searchstudslu as $sesstud){
           $searchlist->addOption($sesstud,$sesstud);
             
        }
        return $searchlist->show();
        
  }
/*------------------------------------------------------------------------------*/

  public function facultysearchlist(){
        
        $searchfaculty  = new dropdown('searchfaculty');
        //check if this infor is more faculty related therefore searches info per faculty
        $searchstudfaculty[0] = '****Please Select an Option****';
        $searchstudfaculty[1] = 'All matriculants entered for that faculty by SLU'; //CHECK
        $searchstudfaculty[2] = 'All matriculants entered for that faculty by SLU'; //CHECK
        $searchstudfaculty[3] = 'All matriculants that qualify for an exemption'; // in a particulat faculty
        $searchstudfaculty[4] = 'All matriculants that have the relevant subjects';// in a particulat faculty
        $searchstudfaculty[5] = 'All prospective students per course';
        $searchstudfaculty[6] = 'All SD Cases';
        
        foreach($searchstudfaculty as $sessfac){
           $searchfaculty->addOption($sessfac,$sessfac);
             
        }
        return $searchfaculty->show();
  }	
/*------------------------------------------------------------------------------*/  
  public function activitysearch(){
      
       $searchactivity  = new dropdown('searchactivity');
       
       $searchstudactivity[0] = '****Please Select an Option****';
       $searchstudactivity[1] = 'All activities'; //CHECK
       $searchstudactivity[2] = 'All activities btween two dates'; //CHECK
       $searchstudactivity[3] = 'All activites by types'; // in a particulat faculty
       $searchstudactivity[4] = 'All activites by province';// in a particulat faculty
       $searchstudactivity[5] = 'All activites by area';
       $searchstudactivity[6] = 'All activities by area'; 
       
        
        foreach($searchstudactivity as $sessactivity){
           $searchactivity->addOption($sessactivity,$sessactivity);
             
        }
        return $searchactivity->show();
  }
/*------------------------------------------------------------------------------*/  
  public function schoolsearch(){
      $searchschool  = new dropdown('searchactivity');
       
      $searchstudschool[0] = '****Please Select an Option****';
      $searchstudschool[1] = 'All schools'; //CHECK
      $searchstudschool[2] = 'All schools by names'; //CHECK
      $searchstudschool[3] = 'All schools by area'; // in a particulat faculty
      $searchstudschool[4] = 'All schools by province';// in a particulat faculty
        
        foreach($searchstudschool as $sessschool){
           $searchschool->addOption($sessschool,$sessschool);
             
        }
        return $searchschool->show();
  } 
}	
?>
