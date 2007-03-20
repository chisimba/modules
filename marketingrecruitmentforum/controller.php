<?php
/* -------------------- marketingrecruitmentforum class extends controller ----------------*/                                                                    
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])     
{
	die("You cannot view this page directly");
}
/*******************************************************************************/
/**
* Marketing Controler                                                                 
* This class controls all functionality to run the marketingrecruitmentforum module.
* @author Colleen Tinker
* @copyright (c) 2005 University of the Western Cape
* @package marketing
* @version 1
*/ 
/********************************************************************************/
class marketingrecruitmentforum extends controller
{
    /**
      * @var string $school used to hold school information retrieved from the schoollist table 
      * @public 
      */  
   
    public $school = ' ';
   
    /**
      * @var string $submitmsg is used to display msg indicating information has been submited into db
      * @public 
      */
    public $submitmsg = ' ';

    /**
      * @var array $qualify is used to hold info about all students that qualify for entry into the university
      * @public 
      */
    
    public $qualify = array();
    
    
    
/**
  * Standard init function
  * @param void
  * @return void
  */
     
function init()
{
    try {
            $this->objLanguage =& $this->getObject('language', 'language');
            $this->objUser =& $this->getObject('user', 'security');
            
            $this->objfaculty = & $this->getObject('faculty','marketingrecruitmentforum');
            $this->dbstudentcard  = & $this->getObject('dbstudentcard','marketingrecruitmentforum');
            $this->objsearchinfo = & $this->getObject('searchinfo','marketingrecruitmentforum');
            $this->dbsluactivities  = & $this->getObject('dbsluactivities','marketingrecruitmentforum');
            $this->dbschoollist   = & $this->getObject('dbschoollist','marketingrecruitmentforum');
          
            $this->objSemsSecurity =& $this->getObject('semssecurity','semsutilities');
            $this->setLayoutTemplate('default_layout_tpl.php');

    }catch (Exception $e){
           		echo 'Caught exception: ',  $e->getMessage();
            	exit();
    }     
                  
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
                if (!$this->objSemsSecurity->inGroup('MRSF Student View')) {
                      return "noaccess_tpl.php";
                }
                  $this->unsetSession('idno');
                  $this->unsetSession('changeIDnumber');
                  $this->unsetSession('studentdata');
                  $this->unsetSession('studentdetails');
                  $this->unsetSession('studentfaccrse');
                  $this->unsetSession('studentinfo'); 
                  $this->unsetSession('schoolvalues');
                  $this->unsetSession('sluactivitydata');
                  $this->unsetSession('nameschool');
                return 'intro_tpl.php';
            break;
            
            case 'activitylist':
                if (!$this->objSemsSecurity->inGroup('MRSF Full')) {
                      return "noaccess_tpl.php";
                }
                  $this->unsetSession('idno');
                  $this->unsetSession('changeIDnumber');
                  $this->unsetSession('studentdata');
                  $this->unsetSession('studentdetails');
                  $this->unsetSession('studentfaccrse');
                  $this->unsetSession('studentinfo'); 
                  $this->unsetSession('schoolvalues');
                  $this->unsetSession('sluactivitydata');
                  $this->unsetSession('nameschool');
                return 'sluactivities_tpl.php';
            break;
            
            case 'studentcard':
                if (!$this->objSemsSecurity->inGroup('MRSF Student View')) {
                      return "noaccess_tpl.php";
                }            
                  $this->unsetSession('idno');
                  $this->unsetSession('changeIDnumber');
                  $this->unsetSession('studentdata');
                  $this->unsetSession('studentdetails');
                  $this->unsetSession('studentfaccrse');
                  $this->unsetSession('studentinfo'); 
                  $this->unsetSession('schoolvalues');
                  $this->unsetSession('sluactivitydata');
                  $this->unsetSession('nameschool');
               return 'idnumber_tpl.php';
            break;
            
            case  'searchidnumber':
                if (!$this->objSemsSecurity->inGroup('MRSF Student View')) {
                      return "noaccess_tpl.php";
                }
                $idnumber  = $this->getParam('idnumber');
                var_dump($idnumber);
                $firstname = ucfirst($this->getParam('firstname'));
                $lastname  = ucfirst($this->getParam('lastname'));
                $this->setSession('idno',$idnumber);
                $this->setSession('name',$firstname);
                $this->setSession('surname',$lastname);
                $idsearch  = $this->dbstudentcard->getstudbyid($idnumber, $field = 'IDNUMBER', $firstname, $field2 = 'NAME', $lastname, $field3 = 'SURNAME', $start = 0, $limit = 0);
                $this->setVarByRef('idsearch', $idsearch);
                return 'idnumberform_tpl.php';
           break;
            
            case  'capturestudcard':
                if (!$this->objSemsSecurity->inGroup('MRSF Student View')) {
                      return "noaccess_tpl.php";
                }
                return 'studentcards_tpl.php'; 
            break;
            
            case  'showsallearchslu':
               if (!$this->objSemsSecurity->inGroup('MRSF Full')) {
                      return "noaccess_tpl.php";
                }
               return 'studcardresults_tpl.php';
            break;
            
            case 'nextpgschoolsearch':
               if (!$this->objSemsSecurity->inGroup('MRSF Full')) {
                      return "noaccess_tpl.php";
                }
               return 'searchschools_tpl.php';
            break;
            
            case 'nextpgsearchactivity':
                if (!$this->objSemsSecurity->inGroup('MRSF Full')) {
                      return "noaccess_tpl.php";
                }
               return 'searchactivities_tpl.php';
            break;
            
            case 'shoollist':
               if (!$this->objSemsSecurity->inGroup('MRSF Full')) {
                      return "noaccess_tpl.php";
                }
                  $this->unsetSession('idno');
                  $this->unsetSession('studentdata');
                  $this->unsetSession('studentdetails');
                  $this->unsetSession('studentfaccrse');
                  $this->unsetSession('studentinfo'); 
                  $this->unsetSession('schoolvalues');
                  $this->unsetSession('sluactivitydata');
                  $this->unsetSession('nameschool');
               return 'schoolsearch_tpl.php';
            break;
            
            case  'searchschool':
               if (!$this->objSemsSecurity->inGroup('MRSF Full')) {
                      return "noaccess_tpl.php";
                }
                $namevalue  = $this->getParam('schoolname',NULL);
                $this->setSession('nameschool',$namevalue);
                $schoolbyname = $this->dbschoollist->getschoolbyname($namevalue, $field = 'schoolname', 0, 0);
                $this->setVarByRef('schoolbyname', $schoolbyname);
                return 'schoolform_tpl.php';
            break;
            
            case  'captureschool':
                if (!$this->objSemsSecurity->inGroup('MRSF Full')) {
                      return "noaccess_tpl.php";
                }
                return 'schoollist_tpl.php';
            break;
            
            case 'continue':
              if (!$this->objSemsSecurity->inGroup('MRSF Student View')) {
                      return "noaccess_tpl.php";
              }
              $this->getStudentDetails();
              return  'studfacultydata_tpl.php';
            break;

            case  'showschoolist':
                if (!$this->objSemsSecurity->inGroup('MRSF Full')) {
                      return "noaccess_tpl.php";
                }
                $this->getSLUActivties();
                return  'output_tpl.php';
            break;
            
            case  'showoutput':
               if (!$this->objSemsSecurity->inGroup('MRSF Student View')) {
                      return "noaccess_tpl.php";
                }
                $changeIDnumber = $this->getParam('studentidnumber');
                $this->setSession('changeIDnumber',$changeIDnumber);
                $this->getStudentDetails();
                return  'studentdetailsdboutput_tpl.php';
            break;
            
            case 'showeditsubjectoutput' :
                if (!$this->objSemsSecurity->inGroup('MRSF Student View')) {
                      return "noaccess_tpl.php";
                }
                $this->getStudSubjInfo();
                return  'studentdetailsdboutput_tpl.php';
            break; 
            
            case  'showschooloutput':
               if (!$this->objSemsSecurity->inGroup('MRSF Full')) {
                      return "noaccess_tpl.php";
                }
                $this->getSchoolist();
                return  'schooloutput_tpl.php';
            break; 
            
            case 'editedschooldetails':
              if (!$this->objSemsSecurity->inGroup('MRSF Full')) {
                      return "noaccess_tpl.php";
                }
                $this->getSchoolist();
                return  'schooloutput_tpl.php';
            break;
            
            case  'showdboutput':
              if($this->objSemsSecurity->inGroup('MRSF Full')) {
                  $this->studentInfoDetails();
                  return  'studentdetailsdboutput_tpl.php';
              }elseif($this->objSemsSecurity->inGroup('MRSF Student View')) {
                        $this->studentInfoDetails();
                        return  'studdatacapoutput_tpl.php';
              }else{
                        return "noaccess_tpl.php";
              }
                
            break;
            
            case  'showeditedinfooutput':
                if (!$this->objSemsSecurity->inGroup('MRSF Student View')) {
                      return "noaccess_tpl.php";
                }
                $this->getFacAndCrse();
               return  'studentdetailsdboutput_tpl.php';
            break;
//edit links to each studcard section            
            case  'editstudcard':
               if (!$this->objSemsSecurity->inGroup('MRSF Student View')) {
                      return "noaccess_tpl.php";
                }
        		    return 'editstudcard_tpl.php';	
            break;
            
            case  'editsubjects':
                if (!$this->objSemsSecurity->inGroup('MRSF Student View')) {
                      return "noaccess_tpl.php";
                }
                
        		    return  'editstudsubjects_tpl.php';
            break;
            
            case  'editfacultycrse':
                if (!$this->objSemsSecurity->inGroup('MRSF Student View')) {
                          return "noaccess_tpl.php";
                    }
            	  return  'editfaccrseoutput_tpl.php';
            break;
            
            case 'showfaccrseditoutput' :
                if (!$this->objSemsSecurity->inGroup('MRSF Student View')) {
                          return "noaccess_tpl.php";
                    }
                $this->getFacAndCrse();    
            	  return  'studentdatacaptured_tpl.php';
            break;
            
            case  'editextra':
                if ($this->objSemsSecurity->inGroup('MRSF Student View')) {
                   return  'editstudinfopg_tpl.php';
                }elseif($this->objSemsSecurity->inGroup('MRSF Full')) {
             	    return  'editstudinfopg_tpl.php';
             	  }else{
                  return "noaccess_tpl.php";
                }
            break;
            
            case 'showinfoeditoutput' :
            if (!$this->objSemsSecurity->inGroup('MRSF Student View')) {
                          return "noaccess_tpl.php";
                    }
                $this->studentInfoDetails();
            	  return  'studentdatacaptured_tpl.php';
            break;
//done with edit links of studcard            
            case  'editsluactivity':
               if (!$this->objSemsSecurity->inGroup('MRSF Full')) {
                      return "noaccess_tpl.php";
                }
               return 'sluactivities_tpl.php';
            break;
            
            case  'editschool':
               if (!$this->objSemsSecurity->inGroup('MRSF Full')) {
                      return "noaccess_tpl.php";
                }
                return 'editschooldetails_tpl.php';
            break;
            
            case 'schooleditoutput' :
            if (!$this->objSemsSecurity->inGroup('MRSF Full')) {
                      return "noaccess_tpl.php";
                }
                $this->getSchoolist();
                return 'schooleditdboutput_tpl.php';
            break;
            
            case  'showsearchslu':
               if (!$this->objSemsSecurity->inGroup('MRSF Full')) {
                      return "noaccess_tpl.php";
                }
               return 'studcardresults_tpl.php';
            break;
            
            case  'editoutput':
               if (!$this->objSemsSecurity->inGroup('MRSF Student View')) {
                      return "noaccess_tpl.php";
                }
               return  'studentdbdetailsedit_tpl.php';
            break;

            case  'submitinfo':
               //if (!$this->objSemsSecurity->inGroup('MRSF Student View')) {
                 //     return "noaccess_tpl.php";
               // }
                  $submitmsg = $this->getParam('submitmsg', 'no');
                  $this->setVarByRef('submitmsg', $submitmsg);
                  //submit studcard info -- personal data
                  $firstname  = $this->getSession('name');
                  $lastname = $this->getSession('surname');
                  $idsearch = $this->getSession('idno');
                  $idexist  = $this->dbstudentcard->getstudbyid($idsearch, $field = 'IDNUMBER', $firstname, $field2 = 'NAME', $lastname, $field3 = 'SURNAME', $start = 0, $limit = 0);
                  $username  = $this->objUser->fullname();
                  $newIDNum = $this->getSession('changeIDnumber');
                                    
                  $studcarddata []   = $this->getSession('studentdata');
              if(!empty($studcarddata) && ($studcarddata[0] != NULL)){  
                      foreach($studcarddata as $resdata){
                            $createdby  = ucfirst($username);
                            $datecreate = date('Y-m-d');
                            $date = $resdata['date'];
                            $studentidnumber  = $idsearch; // CHANGE TO SESSION IDNUMBER captured when capturing personal details $this->getSession('changeIDnumber'); HOW
                            $surname  = ucfirst($resdata['surname']);
                            $name = ucfirst($resdata['name']);
                            $grade =  $resdata['grade'];
                            $schoolname = ucfirst($resdata['schoolname']);
                            $postaddress  = ucfirst($resdata['postaddress']);
                            $postcode = ucfirst($resdata['postcode']); 
                            $areastud = ucfirst($resdata['area']);
                            $telnumber  = ucfirst($resdata['telnumber']);
                            $telcode  = $resdata['telcode'];
                            $cellnumber = $resdata['cellnumber'];
                            $studemail = ucfirst($resdata['studemail']);
                            $changedIDnum = $newIDNum;
                      }
            }elseif(!empty($idexist)){
                  for($i=0; $i< count($idexist); $i++){
                            $createdby  = ucfirst($username);
                            $datecreate = date('Y-m-d');
                            $date = $idexist[$i]->ENTRYDATE;
                            $studentidnumber  = $idsearch;  // leave as is or use id in db ?
                            $surname  = ucfirst($idexist[$i]->SURNAME);
                            $name = ucfirst($idexist[$i]->NAME);
                            $grade =  ucfirst($idexist[$i]->GRADE);
                            $schoolname = ucfirst($idexist[$i]->SCHOOLNAME);
                            $postaddress  = ucfirst($idexist[$i]->POSTADDRESS);
                            $postcode = ucfirst($idexist[$i]->POSTCODE); 
                            $areastud = ucfirst($idexist[$i]->AREA);
                            $telnumber  = ucfirst($idexist[$i]->TELNUMBER);
                            $telcode  = ucfirst($idexist[$i]->TELCODE);
                            $cellnumber = ucfirst($idexist[$i]->CELLNUMBER);
                            $studemail = ucfirst($idexist[$i]->STUDEMAIL);
                 }
          }
          $studsubjectdetail [] =  $this->getSession('studentdetails');
          if(!empty($studsubjectdetail) && ($studsubjectdetail[0] != NULL)){
                     foreach($studsubjectdetail as $sess){
                             $subject1 = ucfirst($sess['subject1']);
                             $subject2 = ucfirst($sess['subject2']);
                             $subject3 = ucfirst($sess['subject3']);
                             $subject4 = ucfirst($sess['subject4']);
                             $subject5 = ucfirst($sess['subject5']);
                             $subject6 = ucfirst($sess['subject6']);
                             $subject7 = ucfirst($sess['subject7']);
                             $gradetype1 = strtoupper($sess['gradetype1']);
                             $gradetype2 = strtoupper($sess['gradetype2']);
                             $gradetype3 = strtoupper($sess['gradetype3']);
                             $gradetype4 = strtoupper($sess['gradetype4']);
                             $gradetype5 = strtoupper($sess['gradetype5']);
                             $gradetype6 = strtoupper($sess['gradetype6']);
                             $gradetype7 = strtoupper($sess['gradetype7']);
                    }       
         }elseif(!empty($idexist)){
                  for($i=0; $i< count($idexist); $i++){
                             $subject1 = ucfirst($idexist[$i]->SUBJECT1);
                             $subject2 = ucfirst($idexist[$i]->SUBJECT2);
                             $subject3 = ucfirst($idexist[$i]->SUBJECT3);
                             $subject4 = ucfirst($idexist[$i]->SUBJECT4);
                             $subject5 = ucfirst($idexist[$i]->SUBJECT5);
                             $subject6 = ucfirst($idexist[$i]->SUBJECT6);
                             $subject7 = ucfirst($idexist[$i]->SUBJECT7);
                             $gradetype1 = strtoupper($idexist[$i]->GRADETYPE1);
                             $gradetype2 = strtoupper($idexist[$i]->GRADETYPE2);
                             $gradetype3 = strtoupper($idexist[$i]->GRADETYPE3);
                             $gradetype4 = strtoupper($idexist[$i]->GRADETYPE4);
                             $gradetype5 = strtoupper($idexist[$i]->GRADETYPE5);
                             $gradetype6 = strtoupper($idexist[$i]->GRADETYPE6);
                             $gradetype7 = strtoupper($idexist[$i]->GRADETYPE7);
                  
                  }
        }
        //course and faculty info
        $studfacdetails []  = $this->getSession('studentfaccrse');
        if(!empty($studfacdetails) && ($studfacdetails[0] != NULL)){
                     foreach($studfacdetails as $sess1){
                             $faculty = ucfirst($sess1['1stfaculty']);
                             $course = ucfirst($sess1['1stcourse']);
                             $faculty2 = ucfirst($sess1['2ndfaculty']);
                             $course2 = ucfirst($sess1['2ndcourse']);
                     }       
               
        }elseif(!empty($idexist)){
                  for($i=0; $i< count($idexist); $i++){
                              $faculty = ucfirst($idexist[$i]->FACULTY);
                              $course = ucfirst($idexist[$i]->COURSE);
                              $faculty2 = ucfirst($idexist[$i]->FACULTY2);
                              $course2 = ucfirst($idexist[$i]->COURSE2);
                  }
        }
        $studmoreinfo[] = $this->getSession('studentinfo');  
        if(!empty($studmoreinfo) && ($studmoreinfo[0] != NULL)){
                  foreach($studmoreinfo as $sess2){
                             $info = ucfirst($sess2['info']);
                             $residence = $sess2['residence'];
                             $exemption  = $sess2['exemption'];
                             $sdcase = $sess2['sdcase'];
                  }       
               
       }elseif(!empty($idexist)){
                  for($i=0; $i< count($idexist); $i++){
                            $info = ucfirst($idexist[$i]->INFODEPARTMENT);
                            $residence = ucfirst($idexist[$i]->RESIDENCE);
                            $exemption  = ucfirst($idexist[$i]->EXEMPTION);
                            $sdcase = ucfirst($idexist[$i]->SDCASE);
                  }
       }
               //more info details   
                      /**
                       *get student details with matching id no
                       *if student exist, update student details with data in session variable
                       *else insert data in session variable into db                                                                    
                       */   
                  //    $firstname  = $this->getSession('name');
                  //    $lastname = $this->getSession('surname');
                  //    $idsearch = $this->getSession('idno');                   
                  //    $idexist  = $this->dbstudentcard->getstudbyid($idsearch, $field = 'IDNUMBER', $firstname, $field2 = 'NAME', $lastname, $field3 = 'SURNAME', $start = 0, $limit = 0);
       if(!empty($idexist)){
                          $this->dbstudentcard->updatestudinfo($createdby,$datecreate,$idsearch,$date,$surname,$name,$schoolname,$postaddress,$postcode,$telnumber,$telcode,$exemption,$faculty,$course,$sdcase,$areastud,$grade,$cellnumber,$studemail,$subject1,$subject2,$subject3,$subject4,$subject5,$subject6,$subject7,$info,$faculty2,$course2,$residence,$gradetype1,$gradetype2,$gradetype3,$gradetype4,$gradetype5,$gradetype6,$gradetype7,$newIDNum);
                      /*}else{
                            //if(!empty($studcarddata)){
                              foreach($studcarddata as $resdata){
                              $createdby  = $resdata['createdby'];
                              $datecreate = $resdata['datecreated'];
                              $id = $resdata['idnumber'];
                              $date = $resdata['date'];
                              $surname  = strtoupper($resdata['surname']);
                              $name = strtoupper($resdata['name']);
                              $grade =  $resdata['grade'];
                              $schoolname = strtoupper($resdata['schoolname']);
                              $postaddress  = strtoupper($resdata['postaddress']);
                              $postcode = strtoupper($resdata['postcode']); 
                              $areastud = strtoupper($resdata['area']);
                              $telnumber  = strtoupper($resdata['telnumber']);
                              $telcode  = $resdata['telcode'];
                              $cellnumber = $resdata['cellnumber'];
                              $studemail = strtoupper($resdata['studemail']);
                          }
                          //subject details
                           $studsubjectdetail [] =  $this->getSession('studentdetails');
                           if(!empty($studsubjectdetail)){
                                 foreach($studsubjectdetail as $sess){
                                         $subject1 = strtoupper($sess['subject1']);
                                         $subject2 = strtoupper($sess['subject2']);
                                         $subject3 = strtoupper($sess['subject3']);
                                         $subject4 = strtoupper($sess['subject4']);
                                         $subject5 = strtoupper($sess['subject5']);
                                         $subject6 = strtoupper($sess['subject6']);
                                         $subject7 = strtoupper($sess['subject7']);
                                         $gradetype1 = strtoupper($sess['gradetype1']);
                                         $gradetype2 = strtoupper($sess['gradetype2']);
                                         $gradetype3 = strtoupper($sess['gradetype3']);
                                         $gradetype4 = strtoupper($sess['gradetype4']);
                                         $gradetype5 = strtoupper($sess['gradetype5']);
                                         $gradetype6 = strtoupper($sess['gradetype6']);
                                         $gradetype7 = strtoupper($sess['gradetype7']);
                                 }       
                           
                           }
                         //course and faculty info
                         $studfacdetails []  = $this->getSession('studentfaccrse');
                         if(!empty($studfacdetails)){
                               foreach($studfacdetails as $sess1){
                                       $faculty = strtoupper($sess1['1stfaculty']);
                                       $course = strtoupper($sess1['1stcourse']);
                                       $faculty2 = strtoupper($sess1['2ndfaculty']);
                                       $course2 = strtoupper($sess1['2ndcourse']);
                               }       
                         
                         }
                         $studmoreinfo [] = $this->getSession('studentinfo');  
                         if(!empty($studmoreinfo)){
                               foreach($studmoreinfo as $sess2){
                                       $info = strtoupper($sess2['info']);            //PROBLEM
                                       $residence = strtoupper($sess2['residence']);
                                       $exemption  = strtoupper($sess2['exemption']);
                                       $sdcase = strtoupper($sess2['sdcase']);         //PROBLEM
                               }       
                         
                         }*/  
                                          
        }elseif($studcarddata[0]!= NULL && $studsubjectdetail[0] != NULL && $studfacdetails[0] != NULL && $studmoreinfo[0] != NULL ){
                  $this->dbstudentcard->addstudcard($createdby,$datecreate,$idsearch,$date,$surname,$name,$schoolname,$postaddress,$postcode,$telnumber,$telcode,$exemption,$faculty,$course,$sdcase,$areastud,$grade,$cellnumber,$studemail,$subject1,$subject2,$subject3,$subject4,$subject5,$subject6,$subject7,$info,$faculty2,$course2,$residence,$gradetype1,$gradetype2,$gradetype3,$gradetype4,$gradetype5,$gradetype6,$gradetype7,$keys = NULL);
        }
        //            }
                           
       //       }
              // }
                  $this->unsetSession('idno');
                  $this->unsetSession('studentdata');
                  $this->unsetSession('studentdetails');
                  $this->unsetSession('studentfaccrse');
                  $this->unsetSession('studentinfo'); 
                  $this->unsetSession('schoolvalues');
                  $this->unsetSession('sluactivitydata');
                  return  'submitmsg_tpl.php';
              break;
 /////////////////////////////////////////////////////////////////////////////////////////////
                case 'submitactivitydata' :             
                  //submit slu activities
                  $submitmsg = $this->getParam('submitmsg', 'no');
                 $this->setVarByRef('submitmsg', $submitmsg);
                 $sluactivity[] = $this->getSession('sluactivitydata');
                 if(!empty($sluactivity)){
                      foreach($sluactivity as $sessActiv){
                            $cdate = ucfirst($sessActiv['createdby']);
                            $ddate  = $sessActiv['datecreated'];
                            $date = ucfirst($sessActiv['date']);
                            $activity = ucfirst($sessActiv['activity']);
                            $schoolname = ucfirst($sessActiv['schoolname']);
                            $area = ucfirst($sessActiv['area']);
                            $province = ucfirst($sessActiv['province']);
                      }
                      if($sluactivity[0] != NULL){
                      $this->dbsluactivities->addsluactivity($cdate,$ddate,$date,$activity,$schoolname,$area,$province);
                     }
                 }
                  $this->unsetSession('idno');
                  $this->unsetSession('studentdata');
                  $this->unsetSession('studentdetails');
                  $this->unsetSession('studentfaccrse');
                  $this->unsetSession('studentinfo'); 
                  $this->unsetSession('schoolvalues');
                  $this->unsetSession('sluactivitydata');
                  return  'submitmsg_tpl.php';
                 break;
 /////////////////////////////////////////////////////////////////////////////////////////////
                case 'submitschooldata' :
                 $submitmsg = $this->getParam('submitmsg', 'no');
                 $this->setVarByRef('submitmsg', $submitmsg);
                
                //submit all schoolist information
                $schname = $this->getSession('nameschool');
                $schoolinfodata [] = $this->getSession('schoolvalues');
                if(!empty($schoolinfodata)){ 
                      foreach($schoolinfodata as $data){  
                                  $createdby  =   $data['createdby'];
                                  $datecreate =   $data['datecreated'];
                                  $schooladdress    =  ucfirst($data['schooladdress']);
                                  $scharea          =  ucfirst($data['area']);
                                  $schprovince      =  ucfirst($data['province']);
                                  $txttelcode       =  ucfirst($data['telcode']);
                                  $telnumber        =  ucfirst($data['telnumber']);
                                  $txtfaxcode       =  ucfirst($data['faxcode']);
                                  $faxnumber        =  ucfirst($data['faxnumber']);
                                  $email            =  ucfirst($data['email']);
                                  $principal        =  ucfirst($data['principal']);
                                  $guidanceteacher  =  ucfirst($data['guidanceteacher']);
                                  $principalemailaddy =ucfirst($data['principalemail']);
                                  $principalcellno  = ucfirst($data['principalCellno']);
                                  $guidanceteacheamil =ucfirst($data['guidanceteachemail']);
                                  $guidanceteachcellno  = ucfirst($data['guidanceteachcellno']);
                                  $schcodeval =     $data['schoolcode'];
                     }
                     $namefound  = $this->dbschoollist->getschoolbyname($schname, $field = 'schoolname', $start = 0, $limit = 0);
                     if(!empty($namefound)){
                             $this->dbschoollist->updateschoollist($createdby,$datecreate,$schname,$schooladdress,$scharea,$schprovince,$txttelcode,$telnumber,$txtfaxcode,$faxnumber,$email,$principal,$guidanceteacher,$principalemailaddy,$principalcellno,$guidanceteacheamil,$guidanceteachcellno,$schcodeval);
                     }else{
                         /* foreach($schoolinfodata as $data){  
                                $createdby  =   ucfirst($data['createdby']);
                                $datecreate =   $data['datecreated'];
                                $schnameval  =  ucfirst($data['schoolname']);
                                $schooladdress    =  ucfirst($data['schooladdress']);
                                $scharea          =  ucfirst($data['area']);
                                $schprovince      =  ucfirst($data['province']);
                                $telnumber        =  ucfirst($data['telnumber']);
                                $faxnumber        =  ucfirst($data['faxnumber']);
                                $email            =  ucfirst($data['email']);
                                $principal        =  ucfirst($data['principal']);
                                $guidanceteacher  =  ucfirst($data['guidanceteacher']); 
                                $guidanceteacher  =  ucfirst($data['guidanceteacher']);
                                $principalemailaddy = ucfirst($data['principalemail']);
                                $principalcellno  =  ucfirst($data['principalCellno']);
                                $guidanceteacheamil = ucfirst($data['guidanceteachemail']);
                                $guidanceteachcellno  = ucfirst($data['guidanceteachcellno']);
                                $schcodeval =     $data['schoolcode'];    
                          }*/
                      
                          if(!empty($schname)){
                              $namevalue = $schname;
                          }else{
                              $namevalue = $schnameval;
                          }
                          //
                          if($schoolinfodata[0] != NULL){ 
                             $this->dbschoollist->addsschoollist($createdby,$datecreate,$namevalue,$schooladdress,$scharea,$schprovince,$txttelcode,$telnumber,$txtfaxcode,$faxnumber,$email,$principal,$guidanceteacher,$principalemailaddy,$principalcellno,$guidanceteacheamil,$guidanceteachcellno,$schcodeval);
                          }
                       }
          }
                  $this->unsetSession('idno');
                  $this->unsetSession('studentdata');
                  $this->unsetSession('studentdetails');
                  $this->unsetSession('studentfaccrse');
                  $this->unsetSession('studentinfo'); 
                  $this->unsetSession('schoolvalues');
                  $this->unsetSession('sluactivitydata');
                  return  'submitmsg_tpl.php';
            break;
            
            case  'studcardfaculty':
               if (!$this->objSemsSecurity->inGroup('MRSF Full')) {
                      return "noaccess_tpl.php";
                }
                $facultynameval = $this->getParam('facultynameval');
                $facultyval = $this->dbstudentcard->allstudfaculty($facultynameval);
                $facultyexmp  = $this->dbstudentcard->facultyexempted($facultynameval);
                $facsubj  = $this->dbstudentcard->facsubject($facultynameval);
                $faccourse  =  $this->dbstudentcard->faccourse($facultynameval, $field = 'faculty', $start = 0, $limit = 0);
                $facsdcase  = $this->dbstudentcard->facultysdcase($facultynameval);
                $this->setVarByRef('facultyval', $facultyval);
                $this->setVarByRef('facultyexmp', $facultyexmp);
                $this->setVarByRef('facsubj', $facsubj);
                $this->setVarByRef('faccourse', $faccourse);
                $this->setVarByRef('facsdcase', $facsdcase);
                $this->setVarByRef('facultynameval12',$facultynameval);
                return 'searchstudcardfac_tpl.php';
            break;
            
            case  'studcardfacultydetails':
               if (!$this->objSemsSecurity->inGroup('MRSF Full')) {
                      return "noaccess_tpl.php";
                }
                $facultynameval2 = $this->getParam('facultynameval2nd');
                //var_dump($facultynameval2);
                $facultyval2 = $this->dbstudentcard->allstudfaculty2($facultynameval2);
                $facultyexmp2  = $this->dbstudentcard->facultyexempted2($facultynameval2);
                $facsubj2  = $this->dbstudentcard->facsubject2($facultynameval2);
                $faccourse2  =  $this->dbstudentcard->faccourse2($facultynameval2, $field = 'faculty2', $start = 0, $limit = 0);
                $facsdcase2  = $this->dbstudentcard->facultysdcase2($facultynameval2);
                $this->setVarByRef('facultyval2', $facultyval2);
                $this->setVarByRef('facultyexmp2', $facultyexmp2);
                $this->setVarByRef('facsubj2', $facsubj2);
                $this->setVarByRef('faccourse2', $faccourse2);
                $this->setVarByRef('facsdcase2', $facsdcase2);
                $this->setVarByRef('facultynameval2',$facultynameval2);
                return 'searchstudcardfac2_tpl.php';
            break;
            
            case  'showsearchfac':
               if (!$this->objSemsSecurity->inGroup('MRSF Full')) {
                      return "noaccess_tpl.php";
                }
               return  'searchstudcardfac_tpl.php';
            break;
            
            case  'showsearchfac2':
               if (!$this->objSemsSecurity->inGroup('MRSF Full')) {
                      return "noaccess_tpl.php";
                }
               return  'searchstudcardfac2_tpl.php';
            break;
            
            case  'searchfac2':
               if (!$this->objSemsSecurity->inGroup('MRSF Full')) {
                      return "noaccess_tpl.php";
                }
               return  'searchstudcardfac2_tpl.php';
            break;
            case  'showsearchactiities':
               if (!$this->objSemsSecurity->inGroup('MRSF Full')) {
                      return "noaccess_tpl.php";
                }
               return  'searchactivities_tpl.php';
            break;
            
            case  'showsearchschool':
               if (!$this->objSemsSecurity->inGroup('MRSF Full')) {
                      return "noaccess_tpl.php";
               }
               return 'searchschools_tpl.php';
            break;
                       
            case  'totalsd':
               if (!$this->objSemsSecurity->inGroup('MRSF Full')) {
                      return "noaccess_tpl.php";
                }
                return 'reportsd_tpl.php';
            break;
            
            case  'totalentry':
               if (!$this->objSemsSecurity->inGroup('MRSF Full')) {
                      return "noaccess_tpl.php";
                }
                return  'entryqualify_tpl.php';
            break;
            
            case  'totalfaculty':
              if (!$this->objSemsSecurity->inGroup('MRSF Full')) {
                        return "noaccess_tpl.php";
              }
              return  'facultyinterest_tpl.php';
            break;
            
            case  'reportdropdown':
               if (!$this->objSemsSecurity->inGroup('MRSF Full')) {
                      return "noaccess_tpl.php";
                }
                    $facultyname  = $this->getParam('facnames');
                    $faculty11 = $this->dbstudentcard->facultycount($facultyname);
                    $this->setVarByRef('faculty11', $faculty11);
                    $this->setVarByRef('facultyname',$facultyname);
                    return  'facultyinterest_tpl.php';
            break;
            
            case  'showstudschool':
               if (!$this->objSemsSecurity->inGroup('MRSF Full')) {
                      return "noaccess_tpl.php";
                }
              $go = $this->getParam('searchbutton');  
              if(isset($go)){  
                $useToPopTbl  = $this->getParam('schoollistnames',NULL);  
                $school = $this->dbstudentcard->getstudschool($useToPopTbl, $field = 'schoolname', $start = 0, $limit = 0);
                $this->setVarByRef('school', $school);
                return 'studcardresults_tpl.php';
              }else{
                return 'studcardresults_tpl.php';
              }
            break;
            
            case  'showschoolbyname':
               if (!$this->objSemsSecurity->inGroup('MRSF Full')) {
                      return "noaccess_tpl.php";
                }
              $name = $this->getParam('searchgo');  
              if(isset($name)){
                $namevalue  = $this->getParam('namevalues',NULL);
                $schoolbyname = $this->dbschoollist->getschoolbyname($namevalue);
                $this->setVarByRef('schoolbyname', $schoolbyname);
              return 'searchschools_tpl.php';
              }
            break;
            
            case  'showstudschoolactivity':
               if (!$this->objSemsSecurity->inGroup('MRSF Full')) {
                      return "noaccess_tpl.php";
                }
                $schooldropdown = $this->getParam('schoollistnames');//list
                $searchactivity = $this->getParam('searchactiv');//button
                $go = $this->getParam('searchgo');//button
                
                if(isset($searchactivity)){
                      $begindate  = $this->getParam('fromdate');
                      $enddate  = $this->getParam('todate');
                      $activitydate = $this->dbsluactivities->getactivitydate($begindate,$enddate);
                      $this->setVarByRef('activitydate',$activitydate); 
                      return 'searchactivities_tpl.php';
               }elseif(isset($go)){
                      $useToPopTbl  = $this->getParam('schoollistnames',NULL);    //get the value of school selected
                      $activschool  = $this->dbsluactivities->getactivityschool($useToPopTbl, $field = 'schoolname', $start = 0, $limit = 0);
                      $this->setVarByRef('activschool',$activschool);
                      return 'searchactivities_tpl.php';  
               }else{
                      return 'searchactivities_tpl.php';
               }
           break;
            
           case  'showaddressgen':
               if (!$this->objSemsSecurity->inGroup('MRSF Full')) {
                      return "noaccess_tpl.php";
                }
                return 'addressgen_tpl.php';
           break;
          
          case 'studentdetailsoutput':
              if (!$this->objSemsSecurity->inGroup('MRSF Student View')) {
                  return "noaccess_tpl.php";    
              }
              $this->getStudSubjInfo();
              return 'studentfaccrse_tpl.php';
           break;
          
          case 'studentfinal':
            if (!$this->objSemsSecurity->inGroup('MRSF Student View')) {
                            return "noaccess_tpl.php";
            }
            $facultylist  = ' ';
            $facultylist  = $this->getParam('faculty');
            $faculty2ndchoice  = $this->getParam('faculty2nd');
            //$course1    = $this->getParam('course');
            //$course2    = $this->getParam('course2nd');
            /*var_dump($course1);
            var_dump($course2);*/
            //$this->setSession('course1',$course1);
            //$this->setSession('course2',$course2);
            $this->setSession('facultylist',$facultylist);
            $this->setSession('faculty2ndchoice',$faculty2ndchoice);
            $this->setVarByRef('facultylist',$facultylist);
            $this->setVarByRef('faculty2ndchoice',$faculty2ndchoice);
            return 'studentfaccrse_tpl.php';
           break;    
           
           case 'studentfinaledit' :
              if (!$this->objSemsSecurity->inGroup('MRSF Student View')) {
                            return "noaccess_tpl.php";
            }
            $facultylist  = ' ';
            $facultylist  = $this->getParam('faculty');
            $faculty2ndchoice  = $this->getParam('faculty2nd');
            //$course1    = $this->getParam('course');
            //$course2    = $this->getParam('course2nd');
            /*var_dump($course1);
            var_dump($course2);*/
            //$this->setSession('course1',$course1);
            //$this->setSession('course2',$course2);
            $this->setSession('facultylist',$facultylist);
            $this->setSession('faculty2ndchoice',$faculty2ndchoice);
            $this->setVarByRef('facultylist',$facultylist);
            $this->setVarByRef('faculty2ndchoice',$faculty2ndchoice);
            return 'editfaccrseoutput_tpl.php';
           break;
           
           case 'lastinfo':
              if ($this->objSemsSecurity->inGroup('MRSF Full')) {
                      $this->getFacAndCrse();
                      return 'studentinfopg_tpl.php';
              }elseif($this->objSemsSecurity->inGroup('MRSF Student View')){
                      $this->getFacAndCrse();
                      return 'studfaccrsedetails_tpl.php';                 
              }else{
                      return 'noaccess_tpl.php';    
              }
           break;
            
           default:
                return $this->nextAction('introduction', array(NULL));
        }
}

/**
 *create an array - $studentdata to store the information captured on student cards
 *create a session variable to store the array data in
 *@param private            
 */
   private function getStudentDetails(){
    $idnum = $this->getSession('idno');
           if(!empty($idnum)){
              $id = $idnum;
           }else{
              $id = "No id number";
           }
           
           $username  = $this->objUser->fullname();
           $studentdata  = array('createdby'        =>  $username,
                                 'datecreated'      =>  date('d-m-Y'),
                                 'idnumber'         =>  $this->getParam('studentidnumber'),
                                 'date'             =>  $this->getParam('datestud'),
                                 'surname'          =>  $this->getParam('txtsurname'),
                                 'name'             =>  $this->getParam('txtname'),
                                 'grade'            =>  $this->getParam('grade'), 
                                 'schoolname'       =>  $this->getParam('schoollist'),
                                 'postaddress'      =>  $this->getParam('postaladdress'),
                                 'postcode'         =>  $this->getParam('txtpostalcode'),
                                 'area'             =>  $this->getParam('areaschool'),
                                 'telnumber'        =>  $this->getParam('txttelnumber'),
                                 'telcode'          =>  $this->getParam('txttelcode'),
                                 'cellnumber'       =>  $this->getParam('txtcellno'),//new field to be created
                                 'studemail'        =>  $this->getParam('txtemail'),//new field to be created
           );
           $this->setSession('studentdata',$studentdata);
}
/**
 *create an array - $studentdetails to store the information captured on student cards
 *create a session variable to store the array data in
 *@param private            
 */
  private function getStudSubjInfo(){                                           

           
           $username  = $this->objUser->fullname();
           $studentdetails = array( 
                                'subject1'         =>  $this->getParam('subjlist1'),
                                'gradetype1'       =>  $this->getParam('grade'),      
                                'subject2'         =>  $this->getParam('subjlist2'),
                                'gradetype2'       =>  $this->getParam('grade2'),
                                'subject3'         =>  $this->getParam('subjlist3'),
                                'gradetype3'       =>  $this->getParam('grade3'),
                                'subject4'         =>  $this->getParam('subjlist4'),
                                'gradetype4'       =>  $this->getParam('grade4'),
                                'subject5'         =>  $this->getParam('subjlist5'),
                                'gradetype5'       =>  $this->getParam('grade5'),
                                'subject6'         =>  $this->getParam('subjlist6'),
                                'gradetype6'       =>  $this->getParam('grade6'),
                                'subject7'         =>  $this->getParam('subjlist7'),
                                'gradetype7'       =>  $this->getParam('grade7'),
          );
        $this->setSession('studentdetails',$studentdetails);
  } 
  
  private function getFacAndCrse(){
  $faculty1 = $this->getSession('facultylist');
  $faculty2 = $this->getSession('faculty2ndchoice');
  //$course1st  = $this->getSession('course1');
  //$course2nd  = $this->getSession('course2');
  $course1st    = $this->getParam('course');
  $course2nd    = $this->getParam('course2nd');
        
          $studentfaccrse = array('1stfaculty'       =>  $faculty1,
                                  '1stcourse'        =>  $course1st,  
                                  '2ndfaculty'       =>  $faculty2,
                                  '2ndcourse'        =>  $course2nd,
          );
          $this->setSession('studentfaccrse',$studentfaccrse);
  }
   
  private function studentInfoDetails(){
           $exemp = $this->getParam('exemptionqualification');
           $sdval = $this->getParam('sdcase');
           $res   = $this->getParam('residence');
           $result  = 2;  
           $val = 2;
           $resval  = 2;
           
           
           if($sdval == '1'){
              $result = 1;
           }else{
              $result = 0;
           }
           
           
           if($exemp == '1'){
             $val = 1;
           }else{
             $val = 0;
           }
           
           if($res == '1'){
             $resval = 1;
           }else{
             $resval = 0;
           }
        $studentinfo  = array('info'             =>  $this->getParam('moreinfo'),
                              'residence'        =>  $resval,
                              'exemption'        =>  $val,
                              'sdcase'           =>  $result,
                        );
        $this->setSession('studentinfo',$studentinfo);
   }    
  /**
   *  Method to create an array to store SLU activities captured
   *  store array data in a session variable
   *  @param public        
   */      
  private function getSLUActivties(){
     
         $username  = $this->objUser->fullname();
         $sluactivitiesdata  = array('createdby'        =>  strtoupper($username),
                                     'datecreated'      =>  date('d-m-Y'),
                                     'date'             =>  $this->getParam('txtdate'), 
                                     'activity'         =>  strtoupper($this->getParam('activityvalues')),
                                     'schoolname'       =>  strtoupper($this->getParam('schoollistactivity')),
                                     'area'             =>  strtoupper($this->getParam('area')),
                                     'province'         =>  strtoupper($this->getParam('province')),
                               );
         $this->setSession('sluactivitydata',$sluactivitiesdata);                                  
                                  
  }
  
  /**
   *  Method to create an array to store school information captured
   *  store array data in a session variable    
   */    
  private function getSchoolist(){
         $schcode = $this->getSession('schoolcodeval'); 
        $username  = $this->objUser->fullname();
        $schoolistdata  = array( 'createdby'        =>  $username,
                                 'datecreated'      =>  date('d-m-Y'),
                                 'schoolname'       =>  $this->getParam('schoollistactivity'),
                                 'schooladdress'    =>  $this->getParam('schooladdress'),
                                 'area'             =>  $this->getParam('areaschool'),
                                 'province'         =>  $this->getParam('provinceschool'),
                                 'telcode'             =>  $this->getParam('txttelcode'),
                                 'telnumber'        =>  $this->getParam('txttelnumber'),
                                 'faxcode'          =>  $this->getParam('txtfaxcode'),
                                 'faxnumber'        =>  $this->getParam('txtfaxnumber'),
                                 'email'            =>  $this->getParam('txtemail'),//school email
                                 'principal'        =>  $this->getParam('txtprincipal'),
                                 'principalemail'   =>  $this->getParam('txtprincemailaddy'),
                                 'principalCellno'  =>  $this->getParam('txtprinccellno'),
                                 'guidanceteacher'  =>  $this->getParam('txtteacher'),
                                 'guidanceteachemail'=> $this->getParam('txtteacheremail'),
                                 'guidanceteachcellno'=> $this->getParam('txtteachercellno'),
                                 'schoolcode'         => $schcode
                            );
       $this->setSession('schoolvalues',$schoolistdata);
  }
    
}//end of class               
?>
