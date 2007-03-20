<?php
/**template used to display and capture student crse and faculty info**/

/**
  * load all classes
  */
  $this->loadClass('datepicker','htmlelements');
  $this->loadClass('dropdown','htmlelements');
  $this->loadClass('textinput','htmlelements');
  $this->loadClass('button','htmlelements');
  $this->loadClass('radio','htmlelements');
  $this->loadClass('windowpop','htmlelements');
 
  $this->window =& $this->getObject('submodalwindow','htmlelements');   
  $this->objfaculties =& $this->getObject('dbstudentcard','marketingrecruitmentforum');
  
/**
  *create form heading
  */
  $this->objMainheading =& $this->getObject('htmlheading','htmlelements');
  $this->objMainheading->type=1;
  $this->objMainheading->str=$objLanguage->languageText('mod_marketingrecruitmentforum_editfacultescourses1','marketingrecruitmentforum');

/**
  *create all language elements
  */
  $facultyselect = $this->objLanguage->languageText('mod_marketingrecruitmentforum_facultymsg1','marketingrecruitmentforum');
  $courseselect = $this->objLanguage->languageText('mod_marketingrecruitmentforum_coursemsg','marketingrecruitmentforum');    
  $content = ' ';  
  $content .=  $this->objMainheading->show(); 
  
/**
  *create links to faculty homepages and open un in new window
  */
//submodalwindow method
$optionType = 'link';
$text = 'faculty homepage';
$url =  "http://www.uwc.ac.za/portal/faculty/community_health/index.htm";   
$communityHS = $this->window->show($text, $url, $optionType, $width=800, $height=600);

$optionType = 'link';
$text = 'faculty homepage';
$url =  "http://www.uwc.ac.za/portal/faculty/economic_management/index.htm";   
$economics = $this->window->show($text, $url, $optionType, $width=800, $height=600);

$optionType = 'link';
$text = 'faculty homepage';
$url =  "http://www.uwc.ac.za/portal/faculty/arts/index.htm";   
$arts = $this->window->show($text, $url, $optionType, $width=800, $height=600);

$optionType = 'link';
$text = 'faculty homepage';
$url =  "http://www.uwc.ac.za/portal/faculty/dentistry/index.htm";   
$dentistry = $this->window->show($text, $url, $optionType, $width=800, $height=600);

$optionType = 'link';
$text = 'faculty homepage';
$url =  "http://www.uwc.ac.za/portal/faculty/law/index.htm";   
$law = $this->window->show($text, $url, $optionType, $width=800, $height=600);

$optionType = 'link';
$text = 'faculty homepage';
$url =  "http://www.uwc.ac.za/portal/faculty/natural_science/index.htm";   
$science = $this->window->show($text, $url, $optionType, $width=800, $height=600);

$optionType = 'link';
$text = 'faculty homepage';
$url =  "http://www.uwc.ac.za/portal/faculty/education/index.htm";   
$education = $this->window->show($text, $url, $optionType, $width=800, $height=600);
/**
  * create dropdown list
  */
  
/**faculty and course list**/
  //call functions to the retrieve the faculty and course values
  $faculty = $this->objfaculties->getFaculties();
  $course = $this->objfaculties->getcourse();
  $fac = array();
  $crse = array();
          
  //store faculty values into an array
  for($i=0; $i < count($faculty); $i++){
    $fac[$i]= $faculty[$i]->NAME;
  }
  //store course values into an array
  for($i=0; $i < count($course); $i++){
    $crse[$i]=$course[$i]->NAME;
  }
  //create dropdown for faculty values and populate with array $fac data
  $objDropdown = new dropdown('faculty');  
  sort($fac);   
  foreach($fac as $sessf){
    $objDropdown->addOption(NULL, ''.$facultyselect); 
    $objDropdown->addOption($sessf,$sessf); 
  }
  $objDropdown->setSelected($this->getParam('faculty'));
  $objDropdown->extra = ' onChange="document.studendtfaccrse.submit()"';
  //create dropdown for course values and populate with array $crse data 
  $objDropdown1 = new dropdown('course');
  sort($crse); 
  foreach($crse as $sessC){
    $objDropdown1->addOption(NULL, ''.$courseselect);
    $objDropdown1->addOption($sessC,$sessC); 
  } 

switch($facultylist){
   
   case 'COMMUNITY AND HEALTH SCIENCES' :
         $link  = $communityHS; 
   break; 
   
   case 'ECONOMIC & MANAGEMENT SCIENCES' :
         $link  = $economics;
   break;
   
   case 'FACULTY OF ARTS' :
         $link  = $arts; 
   break;
   
   case 'FACULTY OF DENTISTRY' :
         $link  = $dentistry; 
   break;
   
   case 'FACULTY OF EDUCATION' :
         $link  = $education; 
   break;
   
   case 'FACULTY OF LAW' :
         $link  = $law; 
   break;
   
   case 'FACULTY OF SCIENCE' :
         $link  = $science; 
   break;
   
   default :
         $link  = ' ';
   break;
   
}

/** 2nd choice fac and crse info**/ 
    $fac2 = array();
    $crse2 = array();
            
    //store faculty values into an array
    for($j=0; $j < count($faculty); $j++){
      $fac2[$j]= $faculty[$j]->NAME;
    }
    //store course values into an array
    for($j=0; $j < count($course); $j++){
      $crse2[$j]=$course[$j]->NAME;
    }
    //create dropdown for faculty values and populate with array $fac data
    $objDropdown2 = new dropdown('faculty2nd');  
    $objDropdown2->length = 100;
    sort($fac2);   
    foreach($fac2 as $sessf){
      $objDropdown2->addOption(NULL, ''.$facultyselect); 
      $objDropdown2->addOption($sessf,$sessf); 
    }
    $objDropdown2->setSelected($this->getParam('faculty2nd'));
    $objDropdown2->extra = ' onChange="document.studendtfaccrse.submit()"'; 
    
    //create dropdown for course values and populate with array $crse data 
    $objDropdown22 = new dropdown('course2nd');
    sort($crse2); 
    foreach($crse2 as $sessC){
      $objDropdown22->addOption(NULL, ''.$courseselect);
      $objDropdown22->addOption($sessC,$sessC); 
    } 
  

switch($faculty2ndchoice){
   
 case 'COMMUNITY AND HEALTH SCIENCES' :
         //$link  = '<a href="http://www.uwc.ac.za/portal/faculty/community_health/index.htm">View COMMUNITY AND HEALTH SCIENCES homepage</a>';
         $linkval  = $communityHS; 
   break; 
   
   case 'ECONOMIC & MANAGEMENT SCIENCES' :
         $linkval  = $economics;
   break;
   
   case 'FACULTY OF ARTS' :
         $linkval  = $arts; 
   break;
   
   case 'FACULTY OF DENTISTRY' :
         $linkval  = $dentistry; 
   break;
   
   case 'FACULTY OF EDUCATION' :
         $linkval  = $education; 
   break;
   
   case 'FACULTY OF LAW' :
         $linkval  = $law; 
   break;
   
   case 'FACULTY OF SCIENCE' :
         $linkval  = $science; 
   break;
   
   default :
         $linkval  = ' ';
   break;
   
}     
/**
  *create a next button
  */
    $this->objButtonNext  = new button('next', 'Next');
    $this->objButtonNext->setToSubmit();

        
/**
  *create all radio groups
  */
    $objElement = new radio('exemptionqualification');
    $objElement->addOption('1','Yes');
    $objElement->addOption('2','No');
            
    $objsdcase = new radio('sdcase');
    $objsdcase->addOption('1','Yes');
    $objsdcase->addOption('2','No');
    
    $objResidence = new radio('residence');
    $objResidence->addOption('1','Yes');
    $objResidence->addOption('2','No');


/**
  *create table to place form elements in
  */
    $myTable=$this->newObject('htmltable','htmlelements');
    $myTable->width='80%';
    $myTable->border='0';
    $myTable->cellspacing='6';
    $myTable->cellpadding='10';
    
    $myTable->startRow();
    $myTable->addCell('Please select your 1st choice faculty');
    $myTable->addCell($objDropdown->show().' '.' '.$link );
    //$myTable->addCell($link);
    $myTable->endRow();
    
    $myTable->startRow();
    $myTable->addCell('Please select your 2nd choice faculty');
    $myTable->addCell($objDropdown2->show().' '.' '.$linkval);
    //$myTable->addCell();
    $myTable->endRow();
    
    $myNextButTable=$this->newObject('htmltable','htmlelements');
    $myNextButTable->width='80%';
    $myNextButTable->border='0';
    $myNextButTable->cellspacing='6';
    $myNextButTable->cellpadding='10';
        
    $myNextButTable->startRow();
    $myNextButTable->addCell('Please select your 1st choice course');
    $myNextButTable->addCell($objDropdown1->show());
    $myNextButTable->endRow();
    
    $myNextButTable->startRow();
    $myNextButTable->addCell('Please select your 2nd choice course');
    $myNextButTable->addCell($objDropdown22->show());
    $myNextButTable->endRow();
    
    $myNextButTable->startRow();
    $myNextButTable->addCell($this->objButtonNext->show());
    $myNextButTable->endRow();

/**
  *display content to screen -- add to form
  */
  
$objForm = new form('studendtfaccrse',$this->uri(array('action'=>'studentfinaledit')));
$objForm->displayType = 3;
$objForm->addToForm($content . '<br/>'.$myTable->show());

$objForm1 = new form('faccrsebutton',$this->uri(array('action'=>'showeditedinfooutput')));
$objForm1->displayType = 3;
$objForm1->addToForm($myNextButTable->show());

echo  $objForm->show() . $objForm1->show() ;                  
?>
