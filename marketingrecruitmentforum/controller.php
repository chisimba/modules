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
    public $school = ' ';
    
    
     
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
      
      //webservice class 
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
                  $this->setLayoutTemplate('datacapture_layout_tpl.php');
                  return 'sluactivities_tpl.php';
            break;
            
            case 'studentcard':
                  $this->setLayoutTemplate('datacapture_layout_tpl.php');
                  return 'studentcards_tpl.php';
            break;
            
            case 'shoollist':
                  $this->setLayoutTemplate('datacapture_layout_tpl.php');
                  return 'schoollist_tpl.php';
            break;
            
            case 'showsluactivities':
                  $this->getStudentDetails(); //set session
                  return  'output_tpl.php';
            break;
            
            case  'showschoolist':
                $this->getSLUActivties();
                return  'output_tpl.php';
            break;
            
            case  'showoutput':
              $this->getSchoolist();
              $this->setLayoutTemplate('datacapture_layout_tpl.php');             
              return  'output_tpl.php';
            break;
            
            case  'editstudcard':
               return 'studentcards_tpl.php';
            break;
            
            case  'editsluactivity':
               return 'sluactivities_tpl.php';
            break;
            
            case  'editschool':
               return 'schoollist_tpl.php';
            break;
            
            case  'showsearchslu':
               $this->setLayoutTemplate('search_layout_tpl.php'); 
               return 'studcardresults_tpl.php';
            break;
            
            case  'submitinfo':
                  $submitdatesmsg = $this->getParam('submitdatesmsg', 'no');
                  $this->setVarByRef('submitdatesmsg', $submitdatesmsg);
                  ///////////////////////////////////////////////////////
                  //submit studcard info
                  $studcarddata = $this->getSession('studentdata');
                  if(!empty($studcarddata)){
                      $this->dbstudentcard->addstudcard($studcarddata);
                  }
                  //submit slu activities
                  $sluactivity = $this->getSession('sluactivitydata');
                  if(!empty($sluactivity)){
                      $this->dbsluactivities->addsluactivity($sluactivity);
                  }
                  //////////////////////////////////////////////////////// 
                  //submit all schoolist information
                 $schoolinfodata = $this->getSession('schoolvalues');
                  if(!empty($schoolinfodata)){
                    $this->dbschoollist->addsschoollist($schoolinfodata);
                  }
                   
                  $this->unsetSession('studentdata');
                  $this->unsetSession('sluactivitydata');
                  $this->unsetSession('schoolvalues');
                  return  'output_tpl.php';
            break;
            
            case  'studcardfaculty':
                //get faculty name selected
                $facultynameval = $this->getParam('facultynameval');
                //use name selected, pass to db function and extract data on that
                $facultyval = $this->dbstudentcard->allstudfaculty($facultynameval);
                $facultyexmp  = $this->dbstudentcard->facultyexempted($facultynameval);
                $facsubj  = $this->dbstudentcard->facsubject($facultynameval);
                $faccourse  =  $this->dbstudentcard->faccourse($facultynameval);
                $facsdcase  = $this->dbstudentcard->facultysdcase($facultynameval);
                //send all values to template / class searchfaculty
                $this->setVarByRef('facultyval', $facultyval);
                $this->setVarByRef('facultyexmp', $facultyexmp);
                $this->setVarByRef('facsubj', $facsubj);
                $this->setVarByRef('faccourse', $faccourse);
                $this->setVarByRef('facsdcase', $facsdcase);
                
                $this->setLayoutTemplate('search_layout_tpl.php');
                return 'searchstudcardfac_tpl.php';
            break;
            
            case  'showsearchfac':
                $this->setLayoutTemplate('search_layout_tpl.php');
                return  'searchstudcardfac_tpl.php';
            break;
            
            case  'showsearchactiities':
                $this->setLayoutTemplate('search_layout_tpl.php');
                return  'searchactivities_tpl.php';
            break;
            
            case  'showsearchschool':
                $this->setLayoutTemplate('search_layout_tpl.php');
                return 'searchschools_tpl.php';
            break;
            
                       
            case  'totalsd':
                $this->setLayoutTemplate('reports_layout_tpl.php');
                return 'reportsd_tpl.php';
            break;
            
            case  'totalentry':
                $this->setLayoutTemplate('reports_layout_tpl.php');
                return  'entryqualify_tpl.php';
            break;
            
            case  'totalfaculty':
                $this->setLayoutTemplate('reports_layout_tpl.php');
                return  'facultyinterest_tpl.php';
            break;
            
            case  'reportdropdown':
              
                //determine which faculty count to show
                 $facultyname  = $this->getParam('names',NULL);  
                 //use name selected to retrieve values in the db matching the faculty name
                 //call the function in dbstudcard and pass the faculty name to it
                 $faculty = $this->dbstudentcard->facultycount($facultyname);
                 //send the array data retrieved from the db to template / searchstudclass
                 $this->setVarByRef('faculty', $faculty);
              //   $this->setVarByRef('faculty', $facultyname);
                $this->setLayoutTemplate('reports_layout_tpl.php');
                 return  'facultyinterest_tpl.php';
                
            break;
/****************************************************************************************************************/            
            case  'showstudschool':
                  
                  $useToPopTbl  = $this->getParam('schoollistnames',NULL);  
                  $school = $this->dbstudentcard->getstudschool($useToPopTbl);
                  $this->setVarByRef('school', $school);
                  $this->setLayoutTemplate('search_layout_tpl.php'); 
                  return 'studcardresults_tpl.php';
            break;
            
            case  'showschoolbyname':
                  $namevalue  = $this->getParam('namevalues',NULL);
                  $schoolbyname = $this->dbschoollist->getschoolbyname($namevalue);
                  $this->setVarByRef('schoolbyname', $schoolbyname);
                  $this->setLayoutTemplate('search_layout_tpl.php');
                  return 'searchschools_tpl.php';
            break;
            
            case  'showstudschoolactivity':
                $schooldropdown = $this->getParam('schoollistnames');
                $searchactivity = $this->getParam('searchactiv');
                
                if(isset($searchactivity)){
                      $begindate  = $this->getParam('fromdate');
                      $enddate  = $this->getParam('todate');
                      $activitydate = $this->dbsluactivities->getactivitydate($begindate,$enddate);
                      //var_dump($activitydate);
                      $this->setVarByRef('activitydate',$activitydate); 
                      $this->setLayoutTemplate('search_layout_tpl.php');  
                      return 'searchactivities_tpl.php';
                }else{
                      $useToPopTbl  = $this->getParam('schoollistnames',NULL);    //get the value of school selected
                      $activschool  = $this->dbsluactivities->getactivityschool($useToPopTbl);
                      $this->setVarByRef('activschool',$activschool);
                      $this->setLayoutTemplate('search_layout_tpl.php');
                      return 'searchactivities_tpl.php';  
                }
            break;
            
            case  'showaddressgen':
                  return 'addressgen_tpl.php';
            break;
            
            case  'followupletter':
                  return  'followupletter_tpl.php';
            break;
/****************************************************************************************************************/            
            default:
               ///"CRSCDE","2",0,1
              /* $field = "Medium Descrip";
               // $value  = "7785";
                $start = 0;
                $end  = 10;//?*/
              
                //$vals = $this->objstudinfo->getlimitFCLTY($field,$start,$end);
                //$vals = $this->objstudinfo->getlimitSTAUX($field,$value,$start,$end);
                //var_dump($vals);//*/
                
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
       $exemp = $this->getParam('exemptionqualification');
       $relsubject = $this->getParam('relevantsubject');
       $result  = 0;  
       $val = 0;
       //case
       if($relsubject){
          $result = 1;
       }
       
       if($exemp){
         $val = 1;
       }
       
       $studentdata  = array('createdby'        =>  $username,
                             'datecreated'      =>  date('Y-m-d'),
                             'modifiedby'       =>  $this->objUser->fullname(),
                             'datemodified'     =>  date('Y-m-d'),
                             'updated'          =>  date('Y-m-d'),
                             'date'             =>  $this->getParam('datestud'),
                             'surname'          =>  $this->getParam('txtsurname'),
                             'name'             =>  $this->getParam('txtname'),
                             'schoolname'       =>  $this->getParam('schoollist'),
                             'postaddress'      =>  $this->getParam('postaladdress'),
                             'postcode'         =>  $this->getParam('txtpostalcode'),
                             'telnumber'        =>  $this->getParam('txttelnumber'),
                             'telcode'          =>  $this->getParam('txttelcode'),
                             'exemption'        =>  $val,
                             'faculty'          =>  $this->getParam('facultylist'),
                             'course'   =>  $this->getParam('txtcourse'),
                             'relevantsubject'  =>  $result,
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
   $this->setSession('schoolvalues',$schoolistdata);
  
  }
/*------------------------------------------------------------------------------*/    
    
}//end of class               
           
    
?>
