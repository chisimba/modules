<?php

if (!$GLOBALS['kewl_entry_point_run'])     
{
	die("You cannot view this page directly");
}

/*******************************************************************************
/**
* Marketing Controller                                                                 
* This class controls all functionality to run the marketingrecruitmentforum module.
* @author Colleen Tinker
* @copyright (c) 2004 University of the Western Cape
* @package invoice
* @version 1
********************************************************************************/

class marketingrecruitmentforum extends controller
{
    /** 
     *declare variable used 
     *@param public and private
     */
     
    function init()
    {
      //initialise all class objects
      $this->objLanguage =& $this->getObject('language', 'language');
      $this->objUser =& $this->getObject('user', 'security');
      $this->setLayoutTemplate('default_layout_tpl.php');
    }
    
    
    /**
    	* Method to process actions to be taken
      * @param string $action String indicating action to be taken
    	*/
	  function dispatch($action)
    {
        
        $this->setVar('pageSuppressXML',true);
             
        switch($action){
            
            case 'introduction' :
                   return 'intro_tpl.php';
            break;
            
            case 'activitylist':
                  return 'sluactivities_tpl.php';
            break;
            
            case 'studentcard':
                  return 'studentcards_tpl.php';
            break;
            
            case 'shoollist':
                  return 'schoollist_tpl.php';
            break;
            
            case 'showsluactivities':
                $this->getStudentDetails();
                //$results  = $this->getSession('studentdata');
                //var_dump($results);
                return 'sluactivities_tpl.php';
            break;
            
            case  'showschoolist':
                $this->getSLUActivties();
               // $results  = $this->getSession('studentdata');
               // var_dump($results);
                return 'schoollist_tpl.php';
            break;
            
            case  'showoutput':
              $this->getschoolist();            
              return  'output_tpl.php';
            break;
            
            
            default:
                return $this->nextAction('introduction', array(NULL));
                
       }
    }
    
   private function getStudentDetails(){
      /**
       *create an array - $studentdata to store the information captured on student cards
       *create a session variable to store the array data in       
       */
       $username  = $this->objUser->fullname();
       $studentdata  = array('createdby'        =>  $username,
                             'datecreated'      =>  date('Y-m-d'),
                             'modifiedby'       =>  $this->objUser->fullname(),
                             'datemodified'     =>  date('Y-m-d'),
                             'updated'          =>  date('Y-m-d'),
                             'date'             =>  $this->getParam('txtdate'),
                             'schoolname'       =>  $this->getParam('txtschoolname'),
                             'surname'          =>  $this->getParam('txtsurname'),
                             'name'             =>  $this->getParam('txtname'),
                             'postaddress'      =>  $this->getParam('postaladdress'),
                             'postcode'         =>  $this->getParam('txtpostalcode'),
                             'telnumber'        =>  $this->getParam('txttelnumber'),
                             'telcode'          =>  $this->getParam('txttelcode'),
                             'exemption'        =>  $this->getParam('exemptionqualification'),
                             'relevantsubject'  =>  $this->getParam('relevantsubject'),
                             'sdcase'           =>  $this->getParam('sdcase'),
                        );
                        
        $this->setSession('studentdata',$studentdata);
      
    }
    
  private function getSLUActivties(){
     
     $username  = $this->objUser->fullname();
     $sluactivitiesdata  = array('createdby'        =>  $username,
                                 'datecreated'      =>  date('Y-m-d'),
                                  'modifiedby'       =>  $this->objUser->fullname(),
                                  'datemodified'     =>  date('Y-m-d'),
                                  'updated'          =>  date('Y-m-d'),
                                  'date'             =>  $this->getParam('txtdate'), 
                                  'activity'         =>  $this->getParam('activityvalues'),
                                  'schoolname'       =>  $this->getParam('txtschoolname'),
                                  'area'             =>  $this->getParam('area'),
                                  'province'         =>  $this->getParam('province'),
                           );
        $this->setSession('sluactivitydata',$sluactivitiesdata);                                  
                                  
  }
  
  private function getschoolist(){
    $username  = $this->objUser->fullname();
    $schoolistdata  = array('createdby'        =>  $username,
                             'datecreated'      =>  date('Y-m-d'),
                             'modifiedby'       =>  $this->objUser->fullname(),
                             'datemodified'     =>  date('Y-m-d'),
                             'updated'          =>  date('Y-m-d'),
                             'schoolname'       =>  $this->getParam('txtschoolname'),
                             'schooladdress'    =>  $this->getParam('schooladdress'),
                             'telnumber'        =>  $this->getParam('txttelnumber'),
                             'faxnumber'        =>  $this->getParam('txtfaxnumber'),
                             'email'            =>  $this->getParam('txtemail'),
                             'principal'        =>  $this->getParam('txtprincipal'),
                             'guidanceteacher'  =>  $this->getParam('txtteacher'),
                        );
   $this->setSession('schoolistdata',$schoolistdata);
  
  }
    
    
}               
           
    
?>
