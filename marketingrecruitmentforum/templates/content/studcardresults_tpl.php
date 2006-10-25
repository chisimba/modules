<?php
//template that displays all students that completed student information cards

    /**
     *load all form classes
     */
     $this->loadClass('textinput','htmlelements');
     $this->loadClass('textarea','htmlelements');
     $this->loadClass('tabbedbox', 'htmlelements');
    
     $this->objstudresults  = & $this->newObject('searchstudcard','marketingrecruitmentforum');
     $this->objschoolname = & $this->getObject('schoolnames', 'marketingrecruitmentforum');
     
    
/*------------------------------------------------------------------------------*/
    /**
     *create form heading
     */
     $this->objMainheading =& $this->getObject('htmlheading','htmlelements');
     $this->objMainheading->type=1;
     $this->objMainheading->str=$objLanguage->languageText('mod_marketingrecruitmentforum_heading','marketingrecruitmentforum');
/*------------------------------------------------------------------------------*/
    /**
     *create all language items
     */
     $instruction = $this->objLanguage->languageText('mod_marketingrecruitmentforum_instruction','marketingrecruitmentforum');
     $click = $this->objLanguage->languageText('mod_marketingrecruitmentforum_click','marketingrecruitmentforum');
    
/*------------------------------------------------------------------------------*/      
    /**
     *create a link to print the information selected by user
     */
     $PrintCardLink = array('params' => array("action" => "NULL"), 'module' => 'marketingrecruitmentforum', 'linktext' => 'Print');         
/*------------------------------------------------------------------------------*/        
    
    /**
     *create dropdwonlist with all schoolnames
     */
       //create an object of the schoolnames class
       //call the function that sets the session
       //call the session
       //populate list with values in the session array 
       
       $this->objschoolname->readfiledata();
       
       $schoollist  = new dropdown('schoollistnames');
       $shoolvalues = $this->getSession('schoolnames');
       sort($shoolvalues);
       foreach($shoolvalues as $sessschool){
          $schoollist->addOption($sessschool,$sessschool);
//          $schoollist->extra = 'onchange=" return changeDetails() "';
//          $schoollist->extra = sprintf(' onChange ="javascript: %s"', $onchange );
       }
       $schoollist->extra = ' onChange="document.searchslu.submit()"'; 

      
/*------------------------------------------------------------------------------*/    
    /**
     *call to all functions from class searchstudcard
     */         
      $results =  $this->objstudresults->getAllstudents();   
      $schoolresults  = $this->objstudresults->allstudschool();
      $exemption  = $this->objstudresults->allwithexemption(); 
      $relsubject = $this->objstudresults->allwithrelsub();
      $faculty  = $this->objstudresults->studfaculty();
      $course = $this->objstudresults->studcourse();
      $area = $this->objstudresults->studarea();
      $sdcase = $this->objstudresults->studsdcase();
/*------------------------------------------------------------------------------*/
    /**
     *create tabpan and display search info
     */         
    $Studcardinfo = & $this->newObject('tabbox','marketingrecruitmentforum');
    $Studcardinfo->tabName = 'OutputInfo';
    
    //. $PrintCardLink->show()
    $Studcardinfo->addTab('studcard', 'All students completed information cards',"<div align=\"right\">" .'<br />' . "</div>" .$results);
    $Studcardinfo->addTab('studschool', 'Students from a certain school','Please select a school to search by' . ' ' .$schoollist->show() . ' <br />'. '<br />' . $schoolresults);
    $Studcardinfo->addTab('studexemption', 'Students that Qualify for exemption',$exemption);
    $Studcardinfo->addTab('relsub', 'Students with relevant subjects',$relsubject);
    $Studcardinfo->addTab('studfac', 'Students by Faculty',$faculty);
    $Studcardinfo->addTab('studcourse', 'Students By Course',$course);
    $Studcardinfo->addTab('studarea', 'Students by Area',$area);
    $Studcardinfo->addTab('studsdcase', 'All SD Cases',$sdcase);
    
/*-------------------------------------------------------------------------------*/
    /**
     *create a form to place all elements on
     */
   $objForm = new form('searchslu',$this->uri(array('action'=>'showstudschool')));
   $objForm->displayType = 3;
   $objForm->addToForm($this->objMainheading->show() . '<br />' . '<br />'.$instruction . '<br />'. $click . '<br />'.'<br />'. $Studcardinfo->show() . '<br />');
/*-------------------------------------------------------------------------------*/         
    /**
     *display all info on screen
     */                            
     //echo    $this->objMainheading->show(); 
     echo $objForm->show();                
?>
	

