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
    /**
      * $submitmsg is a variable to display msg indicating information has been submited into db
      * @public 
      
    */
    public $submitmsg = ' ';
    
    
     
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
      
    //  $this->objFaculties =& $this->getObject('dbacademicprogrammefaculties','academicprogramme');
    //  $this->objCourses =& $this->getObject('dbacademicprogrammecourses','academicprogramme');
   //   $this->objLincClient =& $this->getObject('lincclient');
      
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
            
              $this->dbstudentcard->addfaculties();
              $this->dbstudentcard->addcoursevalues();
                   return 'intro_tpl.php';
            break;
            
            case 'activitylist':
                  //$this->setLayoutTemplate('datacapture_layout_tpl.php');
                  return 'sluactivities_tpl.php';
            break;
            
            case 'studentcard':
                  //$this->setLayoutTemplate('datacapture_layout_tpl.php');
                  //return 'studentcards_tpl.php';
                  return 'idnumber_tpl.php';
            break;
            
            case  'searchidnumber':
                 $submit  = $this->getParam('search');
                 $continue  = $this->getParam('continue');
               if(isset($submit) ){ 
                     $idnumber  = $this->getParam('idnumber');
                     $search  = $this->getParam('search'); 
                     $this->setSession('idno',$idnumber);
                     $idsearch  = $this->dbstudentcard->getstudbyid($idnumber);
                    
                     $this->setVarByRef('idsearch', $idsearch);
                     $this->setVarByRef('search', $search);
                     return 'idnumberform_tpl.php';
                } else {
                
                    return 'studentcards_tpl.php';
                
                }
            break;
            
            case 'selectfaculty':
                $facname  = $this->getParam('faculty');
                $this->setSession('faculty',$facname);
                //$this->setVarByRef('search', $search);
                /*$this->getStudentDetails();         
                $faculty  = $this->getParam('faculty');
				        $this->setSession('faculty',$faculty);
				        $this->setSession('course',NULL);
				        $this->setSession('type',NULL);*/
				        return 'studentcards_tpl.php';
				   break;
			     
           case 'selectcourse':
              $course = $this->getParam('course');
				      $this->setSession('course',$course);
				      $this->setSession('type',NULL);
				      return 'studentcards_tpl.php';
				   break;
            
            case  'capturestudcard';
                 return 'studentcards_tpl.php'; 
            break;
            
                               
            break;
            
            case 'shoollist':
                  //$this->setLayoutTemplate('datacapture_layout_tpl.php');
                  //return 'schoollist_tpl.php';
                  return 'schoolsearch_tpl.php';
            break;
            
            case  'searchschool':
                  $namevalue  = $this->getParam('schoolname',NULL);
                  $this->setSession('nameschool',$namevalue);
                  $schoolbyname = $this->dbschoollist->getschoolbyname($namevalue);
                  $this->setVarByRef('schoolbyname', $schoolbyname);
                  
                  return 'schoolform_tpl.php';
            break;
            
            case  'captureschool':
                  return 'schoollist_tpl.php';
            break;
            
            case 'showsluactivities':
                $next = $this->getParam('next');
                if(isset($next)){
                    //$details = $this->getSession('studentdata');
                    //if(!empty($details)){
                        $this->getStudentDetails(); //set session
                        return  'output_tpl.php';
                    //}else{
                      //  $this->getStudentDetails();
                      //  return 'editdbstudent_tpl.php';
                    //}
                } else {
                
                    return 'studentcards_tpl.php';
                }
            break;
            
            case  'showschoolist':
                $this->getSLUActivties();
                return  'output_tpl.php';
            break;
            
            case  'showoutput':
              $this->getSchoolist();
              //$this->setLayoutTemplate('datacapture_layout_tpl.php');             
              return  'output_tpl.php';
            break;
            
            case  'showdboutput':
              return  'editdbstudent_tpl.php';
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
              // $this->setLayoutTemplate('search_layout_tpl.php'); 
               return 'studcardresults_tpl.php';
            break;
            
            case  'editoutput':
                  return  'editdbstudent_tpl.php';
            break;
            
            case  'updateinfo':
                $res  = $this->getSession('idsearch');
                $date = " ";
                $surname  = " "; 
                $name = " ";
                $schoolname = " ";
                $postaddress  = " ";
                $postcode = " ";  
                $telnumber  = " ";
                $telcode  = " ";
                $faculty  = " ";
                $course = " ";
                
                foreach($res as $resdata){
                    
                    $date = $resdata['date'];
                    $surname  = $resdata['surname'];
                    $name = $resdata['name'];
                    $schoolname = $resdata['schoolname'];
                    $postaddress  = $resdata['postaddress'];
                    $postcode = $resdata['postcode']; 
                    $telnumber  = $resdata['telnumber'];
                    $telcode  = $resdata['telcode'];
                    $faculty  = $resdata['faculty'];
                    $course = $resdata['course'];
                
                
                }
                $id = $this->getSession('idno');
                $this->dbstudentcard->updatestudinfo($id,$date,$surname,$name,$schoolname,$postaddress,$postcode,$telnumber,$telcode,$faculty,$course);
                echo 'update sucessfull';
                die;
                //var_dump($id);
                //die;
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
                  return  'editdbstudent_tpl.php';
            break;
            
            case  'submitinfo':
                  
                  $submitmsg = $this->getParam('submitmsg', 'no');
                  $this->setVarByRef('submitmsg', $submitmsg);
       /*-------------------------------------------------------------------------------------------*/
                  //submit studcard info
                  $studcarddata    = $this->getSession('studentdata');
                  $idsearch = $this->getSession('idno');
                  $date = " ";
                  $surname  = " "; 
                  $name = " ";
                  $schoolname = " ";
                  $postaddress  = " ";
                  $postcode = " ";  
                  $telnumber  = " ";
                  $telcode  = " ";
                  $faculty  = " ";
                  $course = " ";
              if(!empty($studcarddata)){  
              foreach($studcarddata as $resdata){
                    
                    $date = $resdata['date'];
                    //echo $date;
                    $surname  = $resdata['surname'];
                    $name = $resdata['name'];
                    $schoolname = $resdata['schoolname'];
                    $postaddress  = $resdata['postaddress'];
                    $postcode = $resdata['postcode']; 
                    $telnumber  = $resdata['telnumber'];
                    $telcode  = $resdata['telcode'];
                    $faculty  = $resdata['faculty'];
                    $course = $resdata['course'];
                
                
                }
                $idexist = $this->dbstudentcard->getstudbyid($idsearch);
                if(!empty($idexist)){
                      
                      $this->dbstudentcard->updatestudinfo($idsearch,$date,$surname,$name,$schoolname,$postaddress,$postcode,$telnumber,$telcode,$faculty,$course);
                      //echo 'update sucessfull';
                      //$this->dbstudentcard->updatestudinfo($idsearch,$studcarddata);
                      
                      
                } else {
                
                      //if(!empty($studcarddata)){
                        $this->dbstudentcard->addstudcard($studcarddata);
                     // }
               }
               }
      /*-------------------------------------------------------------------------------------------*/            
                  //submit slu activities
                  $sluactivity = $this->getSession('sluactivitydata');
                  if(!empty($sluactivity)){
                      $this->dbsluactivities->addsluactivity($sluactivity);
                  }
     /*-------------------------------------------------------------------------------------------*/ 
                  //submit all schoolist information
                $result = $this->getSession('nameschool');
                //$schoolinfodata = array();
                 $schoolinfodata  = $this->getSession('schoolvalues');
                  if(!empty($schoolinfodata)){
                  
                      foreach($schoolinfodata as $data){  
                          //      $schoolname       =  $data['schoolname'];
                                $schooladdress    =  $data['schooladdress'];
                                $telnumber        =  $data['telnumber'];
                                $faxnumber        =  $data['faxnumber'];
                                $email            =  $data['email'];
                                $principal        =  $data['principal'];
                                $guidanceteacher  =  $data['guidanceteacher'];    
                      }
                    $namefound  = $this->dbschoollist->getschoolbyname($result);
                    if(!empty($namefound)){
                       
                      $this->dbschoollist->updateschoollist($result,$schooladdress,$telnumber,$faxnumber,$email,$principal,$guidanceteacher);
                      
                    }else{
                        if(!empty($schoolinfodata)){
                          $this->dbschoollist->addsschoollist($schoolinfodata);
                        }
                    }
                    
                  }
     /*-------------------------------------------------------------------------------------------*/             
                  $this->unsetSession('idno');
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
                
              //  $this->setLayoutTemplate('search_layout_tpl.php');
                return 'searchstudcardfac_tpl.php';
            break;
            
            case  'showsearchfac':
              //  $this->setLayoutTemplate('search_layout_tpl.php');
                return  'searchstudcardfac_tpl.php';
            break;
            
            case  'showsearchactiities':
              //  $this->setLayoutTemplate('search_layout_tpl.php');
                return  'searchactivities_tpl.php';
            break;
            
            case  'showsearchschool':
              //  $this->setLayoutTemplate('search_layout_tpl.php');
                return 'searchschools_tpl.php';
            break;
            
                       
            case  'totalsd':
              //  $this->setLayoutTemplate('reports_layout_tpl.php');
                return 'reportsd_tpl.php';
            break;
            
            case  'totalentry':
             //   $this->setLayoutTemplate('reports_layout_tpl.php');
                return  'entryqualify_tpl.php';
            break;
            
            case  'totalfaculty':
             //   $this->setLayoutTemplate('reports_layout_tpl.php');
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
              //  $this->setLayoutTemplate('reports_layout_tpl.php');
                 return  'facultyinterest_tpl.php';
                
            break;
/****************************************************************************************************************/            
            case  'showstudschool':
                  
                  $useToPopTbl  = $this->getParam('schoollistnames',NULL);  
                  $school  = $this->dbstudentcard->getstudschool($useToPopTbl);
                  $this->setVarByRef('school', $school);
             //     $this->setLayoutTemplate('search_layout_tpl.php'); 
                  return 'studcardresults_tpl.php';
            break;
            
            case  'showschoolbyname':
                  $namevalue  = $this->getParam('namevalues',NULL);
                  $schoolbyname = $this->dbschoollist->getschoolbyname($namevalue);
                  $this->setVarByRef('schoolbyname', $schoolbyname);
            //      $this->setLayoutTemplate('search_layout_tpl.php');
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
              //        $this->setLayoutTemplate('search_layout_tpl.php');  
                      return 'searchactivities_tpl.php';
                }else{
                      $useToPopTbl  = $this->getParam('schoollistnames',NULL);    //get the value of school selected
                      $activschool  = $this->dbsluactivities->getactivityschool($useToPopTbl);
                      $this->setVarByRef('activschool',$activschool);
             //         $this->setLayoutTemplate('search_layout_tpl.php');
                      return 'searchactivities_tpl.php';  
                }
            break;
            
            case  'showaddressgen':
                  return 'addressgen_tpl.php';
            break;
            
            case  'followupletter':
                  return  'followupletter_tpl.php';
            break;
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////      
            case  'postletter':
                $selected = $this->getParam('post');
                //var_dump($selected);
                //if($selected){
                  
                  $val = $this->getSession('results');
                  //$selected = $val[$check];
                  //var_dump($val);
                  //die;
                  //if($selected){
                  foreach($val as $v){
                  
                  if($selected == true && $val[0]){
                    
                    $surname  = $v['surname'];
                    $name     = $v['name'];
                    $addy     = $v['postaddress'];
                    //$postcode = $v['postcode'];
                  }
                  elseif($selected == true && $val[1]){
                    
                    $surname  = $v['surname'];
                    $name     = $v['name'];
                    $addy     = $v['postaddress'];
                    //$postcode = $v['postcode'];
                  }
                  elseif($selected == true && $val[2]){
                    
                    $surname  = $v['surname'];
                    $name     = $v['name'];
                    $addy     = $v['postaddress'];
                   // $postcode = $v['postcode'];
                  }
                  
                }
                $this->setVarByRef('surname',$surname);
                  $this->setVarByRef('name',$name);
                  $this->setVarByRef('addy',$addy);
                  $this->setVarByRef('postcode',$postcode);
                //$this->setVarByRef('selected',$selected);
                //var_dump($selected);
                return  'followupletter_tpl.php';
            break;
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////       
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
       $idnum = $this->getSession('idno');
       if(!empty($idnum)){
          $id = $idnum;
       }else{
          $id = "No id number";
       }
       
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
      // $facname = $this->getSession('faculty');
      // $coursename = $this->getSession('faculty');
       
       $studentdata  = array('createdby'        =>  $username,
                             'datecreated'      =>  date('Y-m-d'),
                             'idnumber'         =>  $id,
                             'date'             =>  $this->getParam('datestud'),
                             'surname'          =>  $this->getParam('txtsurname'),
                             'name'             =>  $this->getParam('txtname'),
                             'schoolname'       =>  $this->getParam('schoollist'),
                             'postaddress'      =>  $this->getParam('postaladdress'),
                             'postcode'         =>  $this->getParam('txtpostalcode'),
                             'telnumber'        =>  $this->getParam('txttelnumber'),
                             'telcode'          =>  $this->getParam('txttelcode'),
                             'exemption'        =>  $val,
                             'faculty'          =>  $this->getParam('faculty'),
                             'course'           =>  $this->getParam('course'),
                             'relevantsubject'  =>  $result,
                             'sdcase'           =>  $this->getParam('sdcase'),
                        );
                        
     $this->setSession('studentdata',$studentdata);
    // $this->setSession('faculty',$this->getParam('faculty'));
     }
     
/*------------------------------------------------------------------------------*/    
  private function getSLUActivties(){
     
     $username  = $this->objUser->fullname();
     $sluactivitiesdata  = array('createdby'        =>  $username,
                                 'datecreated'      =>  date('Y-m-d'),
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
