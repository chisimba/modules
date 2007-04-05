<?php
    
    //template that displays all students that completed student information cards (SLU)

    /**
     *load all form classes
     */
     $this->loadClass('textinput','htmlelements');
     $this->loadClass('textarea','htmlelements');
     $this->loadClass('tabbedbox', 'htmlelements');
     $this->loadClass('button', 'htmlelements');
    
     $this->objstudresults  = & $this->newObject('searchstudcard','marketingrecruitmentforum');
     $this->objschoolname = & $this->getObject('schoolnames', 'marketingrecruitmentforum');
     
    
/*------------------------------------------------------------------------------*/
    /**
     *create form heading
     */
     $this->objMainheading =& $this->getObject('htmlheading','htmlelements');
     $this->objMainheading->type=1;
     $this->objMainheading->str=$objLanguage->languageText('mod_marketingrecruitmentforum_heading1','marketingrecruitmentforum');
/*------------------------------------------------------------------------------*/
    /**
     *create all language items
     */
     $instruction = $this->objLanguage->languageText('mod_marketingrecruitmentforum_instruction','marketingrecruitmentforum');
     $click = $this->objLanguage->languageText('mod_marketingrecruitmentforum_click','marketingrecruitmentforum');
     $schoolselect = $this->objLanguage->languageText('mod_marketingrecruitmentforum_schoolselect','marketingrecruitmentforum');
      
     $this->objfaculties =& $this->getObject('dbstudentcard','marketingrecruitmentforum');
     $this->schoolnames =& $this->getObject('schoolnames','marketingrecruitmentforum');
/*------------------------------------------------------------------------------*/      
    /**
     *create dropdwonlist with all schoolnames
     *create an object of the schoolnames class
     *call the function that sets the session
     *call the session
     *populate list with values in the session array
     */ 
       
     /**
       *create form button -- go
       */
                    
      $this->objButtonGo  = new button('searchbutton', 'Go');
      $this->objButtonGo->setToSubmit();
       
      
       $schoolnames = $this->schoolnames->readfiledata();

        for($i=0; $i < count($schoolnames); $i++){
            $schoolvalues[$i]=$schoolnames[$i];
        }
       //create dropdown list
       $schoollist  = new dropdown('schoollistnames');
     //  $schoollist->size = 50;
       
       sort($schoolvalues);
       foreach($schoolvalues as $sessschool){
          $schoollist->addOption(NULL, ''.$schoolselect);
          $schoollist->addOption($sessschool,$sessschool);
       }
       $schoollist->setSelected($this->getParam('schoollistnames'));
       //$schoollist->extra = ' onChange="document.searchresults.submit()"';
      
/*------------------------------------------------------------------------------*/    
    /**
     *call to all functions from class searchstudcard
     *used to set-up the content of the tabpane showing all results for searched values     
     */
              
      $results =  $this->objstudresults->getAllstudents();   
      $schoolresults  = $this->objstudresults->allstudschool($school);
      $exemption  = $this->objstudresults->allwithexemption(); 
      //$relsubject = $this->objstudresults->allwithrelsub();
      $faculty  = $this->objstudresults->studfaculty();
      $faculty2  = $this->objstudresults->studfaculty2ndchoice();
      $course = $this->objstudresults->studcourse();
      $course2 = $this->objstudresults->studcourse2ndchoice();
      $area = $this->objstudresults->studarea();
      $sdcase = $this->objstudresults->studsdcase();
/*------------------------------------------------------------------------------*/
    /**
     *create tabpane and display search info
     */         
    $Studcardinfo = & $this->newObject('tabcontent','htmlelements');
    $Studcardinfo->name = 'studcarddata';
    $Studcardinfo->width = "760px";
    
    $Studcardinfo->addTab('Info Cards',$results,false);
    $Studcardinfo->addTab('School','<b>'.'Please select a school to search by'.'</b>' . ' ' .$schoollist->show() .' '.$this->objButtonGo->show(). ' <br />'. '<br />' . $schoolresults,false);
    $Studcardinfo->addTab('Exemption',$exemption,false);
    $Studcardinfo->addTab('Faculty 1',$faculty,false);
    $Studcardinfo->addTab('Course 1' ,$course,false);
    $Studcardinfo->addTab('Faculty 2',$faculty2,false);
    $Studcardinfo->addTab('Course 2',$course2,false);
    $Studcardinfo->addTab('SD Cases',$sdcase,false);
    $Studcardinfo->addTab('Area',$area,false);
    
    
/*-------------------------------------------------------------------------------*/
    /**
     *create a form to place all elements on
     */
   $objForm = new form('searchresults',$this->uri(array('action'=>'showstudschool')));
   $objForm->displayType = 3;
   $objForm->addToForm("<center>".$this->objMainheading->show() . '<br />' . '<br />'.'<b>'.'<i>'.$instruction . '<br />'. $click.'</i>'.'</b>' ."</center>". '<br />'.'<br />'. $Studcardinfo->show() . '<br />' . '<br />');
/*-------------------------------------------------------------------------------*/         
    /**
     *display all info on screen
     */                            
    
     echo $objForm->show();                
?>
