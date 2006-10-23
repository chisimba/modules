<?php

if (!$GLOBALS['kewl_entry_point_run'])     
{
	die("You cannot view this page directly");
}

/*******************************************************************************/
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
     public $submitdatesmsg = '';
     
    function init()
    {
      //initialise all class objects
      $this->objLanguage =& $this->getObject('language', 'language');
      $this->objUser =& $this->getObject('user', 'security');
      $this->setLayoutTemplate('default_layout_tpl.php');
      
      $this->objfaculty = & $this->getObject('faculty','marketingrecruitmentforum');
      $this->dbstudentcard  = & $this->getObject('dbstudentcard','marketingrecruitmentforum');
      $this->objsearchinfo = & $this->getObject('searchinfo','marketingrecruitmentforum');
      $this->dbsluactivities  = & $this->getObject('dbsluactivities','marketingrecruitmentforum');
      $this->dbschoollist   = & $this->getObject('dbschoollist','marketingrecruitmentforum');
      
       //$this->objdbperdiem = & $this->getObject('dbperdiem','onlineinvoice');
      $this->objstudinfo  = & $this->getObject('dbmarketing','marketingrecruitmentforum');
    }
    
/*------------------------------------------------------------------------------*/    
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
            
                  $this->getStudentDetails(); //set session
                  return  'output_tpl.php';
                //return 'sluactivities_tpl.php';
            
            break;
            
            case  'showschoolist':
                $this->getSLUActivties();
                return  'output_tpl.php';
                //return 'schoollist_tpl.php';
            break;
            
            case  'showoutput':
              $this->getSchoolist();             
              return  'output_tpl.php';
            break;
            
            case  'editstudcard':
                //$this->unsetSession('studentdata');
                return 'studentcards_tpl.php';
            break;
            
            case  'editsluactivity':
                 return 'sluactivities_tpl.php';
            break;
            
            case  'editschool':
                return 'schoollist_tpl.php';
            break;
            
            case  'showsearchslu':
                return 'studcardresults_tpl.php';
            break;
            
            
            case  'submitinfo':
                  $submitdatesmsg = $this->getParam('submitdatesmsg', 'no');
                  $this->setVarByRef('submitdatesmsg', $submitdatesmsg);
                  ///////////////////////////////////////////////////////
                  //submit studcard info
                  $studcarddata = $this->getSession('studentdata');
                  //$faccoursedata  = $this->getSession('faccoursedata');
                  $this->dbstudentcard->addstudcard($studcarddata);
                  //$this->dbstudentcard->addfaccourse($faccourse);
                  //submit slu activities
                  //$sluactivity = $this->getSession('sluactivitydata');
                  //$this->dbsluactivities->addsluactivity($sluactivity);
                  //////////////////////////////////////////////////////// 
                  //submit all school information
                  $schoolinfodata = $this->getSession('sluactivitydata');
                  $this->dbschoollist->addsschoollist($schoolinfodata);
                   
                  $this->unsetSession('studentdata');
                  $this->unsetSession('sluactivitydata');
                  $this->unsetSession('schoolistdata');
                  return  'output_tpl.php';
            break;
            
            case  'showsearchfac':
                return  'searchstudcardfac_tpl.php';
            break;
            
            case  'showsearchactiities':
                return  'searchactivities_tpl.php';
            break;
            
            case  'showsearchschool':
                return 'searchschools_tpl.php';
            break;
/****************************************************************************************************************/            
            case  'showstudschool': 
                  $schooval = $this->getParam('schoollistnames');
                  $this->setVarByRef('schoollistnames', $schooval);
            break;
/****************************************************************************************************************/            
            default:
               ///"CRSCDE","2",0,1
               /*$field = "ARECDE";
                $value  = "7785";
                $start = 0;
                $end  = 10;
               // $vals = $this->objstudinfo->getlimitCRSRE($field,$value,$start,$end);
                $vals = $this->objstudinfo->getlimitSTAUX($field,$value,$start,$end);
                var_dump($vals);*/
                
                return $this->nextAction('introduction', array(NULL));
                
       }
    }
/*------------------------------------------------------------------------------*/    
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
                             'date'         =>  $this->getParam('txtdate'),
                             'surname'          =>  $this->getParam('txtsurname'),
                             'name'             =>  $this->getParam('txtname'),
                             'schoolname'   =>  $this->getParam('$schoollist'),
                             'postaddress'      =>  $this->getParam('postaladdress'),
                             'postcode'         =>  $this->getParam('txtpostalcode'),
                             'telnumber'        =>  $this->getParam('txttelnumber'),
                             'telcode'          =>  $this->getParam('txttelcode'),
                             'exemption'        =>  $this->getParam('exemptionqualification'),
                             'faculty'          =>  $this->getParam('facultylist'),
                             'course'   =>  $this->getParam('txtcourse'),
                             'relevantsubject'  =>  $this->getParam('relevantsubject'),
                             'sdcase'           =>  $this->getParam('sdcase'),
                        );
                        
     $this->setSession('studentdata',$studentdata);
     }
     
/*------------------------------------------------------------------------------*/
    /**
     *create an array -- $facoursedata to store the info captured for student
     *create a session variable to store the array info in
     */
  /*private function getfaccourse(){
  
     $faccoursedata = array( 'faculty'          =>  $this->getParam('facultylist'),
                             'course'   =>  $this->getParam('txtcourse'),
                             'relevantsubject'  =>  $this->getParam('relevantsubject'),
                             'sdcase'           =>  $this->getParam('sdcase'),
                      );
                        
     $this->setSession('faccoursedata',$faccoursedata);
  } */  
      
/*------------------------------------------------------------------------------*/    
  private function getSLUActivties(){
     
     $username  = $this->objUser->fullname();
     $sluactivitiesdata  = array('createdby'        =>  $username,
                                 'datecreated'      =>  date('Y-m-d'),
                                  'modifiedby'       =>  $this->objUser->fullname(),
                                  'datemodified'     =>  date('Y-m-d'),
                                  'updated'          =>  date('Y-m-d'),
                                  'date'             =>  $this->getParam('txtdate'), 
                                  'activity'         =>  $this->getParam('activityvalues'),
                                  'schoolname'       =>  $this->getParam('schoollistactivity'),
                                  'area'             =>  $this->getParam('area'),
                                  'province'         =>  $this->getParam('province'),
                           );
        $this->setSession('sluactivitydata',$sluactivitiesdata);                                  
                                  
  }
/*------------------------------------------------------------------------------*/  
  private function getSchoolist(){
  
    $username  = $this->objUser->fullname();
    $schoolistdata  = array( 'createdby'        =>  $username,
                             'datecreated'      =>  date('Y-m-d'),
                             'modifiedby'       =>  $this->objUser->fullname(),
                             'datemodified'     =>  date('Y-m-d'),
                             'updated'          =>  date('Y-m-d'),
                             'schoolname'       =>  $this->getParam('schoollistactivity'),
                             'schooladdress'    =>  $this->getParam('schooladdress'),
                             'telnumber'        =>  $this->getParam('txttelnumber'),
                             'faxnumber'        =>  $this->getParam('txtfaxnumber'),
                             'email'            =>  $this->getParam('txtemail'),
                             'principal'        =>  $this->getParam('txtprincipal'),
                             'guidanceteacher'  =>  $this->getParam('txtteacher'),
                        );
   $this->setSession('schoolistdata',$schoolistdata);
  
  }
/*------------------------------------------------------------------------------*/    
    
}//end of class               
           
    
?>
