<?php
/**template used to display and capture student edu details**/

/**
  * load all classes
  */
  $this->loadClass('datepicker','htmlelements');
  $this->loadClass('dropdown','htmlelements');
  $this->loadClass('textinput','htmlelements');
  $this->loadClass('button','htmlelements');
  $this->loadClass('radio','htmlelements');  

  $this->objfaculties =& $this->getObject('dbstudentcard','marketingrecruitmentforum');

/**
  *create form heading
  */
  $this->objMainheading =& $this->getObject('htmlheading','htmlelements');
  $this->objMainheading->type=1;
  $this->objMainheading->str=$objLanguage->languageText('mod_marketingrecruitmentforum_subjectstaken12','marketingrecruitmentforum');
  
   $this->objheading =& $this->newObject('htmlheading','htmlelements');
   $this->objheading->type=5;
   $this->objheading->str=$objLanguage->languageText('mod_marketingrecruitmentforum_warning','marketingrecruitmentforum');
/**
  *create all language elements
  */
  $facultyselect = $this->objLanguage->languageText('mod_marketingrecruitmentforum_facultymsg1','marketingrecruitmentforum');
  $courseselect = $this->objLanguage->languageText('mod_marketingrecruitmentforum_coursemsg','marketingrecruitmentforum');    
  $content = ' ';  
  $content .=  $this->objMainheading->show() . '<br />'."<span class=error>".'<i>'.$this->objheading->show().'</i>'."</span>";
         
/**
  * create dropdown list
  */
  
  /** HS | SG**/
  $this->objgradelist1  = new dropdown('grade');
  $this->objgradelist1->addOption(NULL,'');
  $this->objgradelist1->addOption('HG','HG');
  $this->objgradelist1->addOption('SG','SG');
  
  $this->objgradelist2  = new dropdown('grade2');
  $this->objgradelist2->addOption(NULL,'');
  $this->objgradelist2->addOption('HG','HG');
  $this->objgradelist2->addOption('SG','SG');
  
  $this->objgradelist3  = new dropdown('grade3');
  $this->objgradelist3->addOption(NULL,'');
  $this->objgradelist3->addOption('HG','HG');
  $this->objgradelist3->addOption('SG','SG');
  
  $this->objgradelist4  = new dropdown('grade4');
  $this->objgradelist4->addOption(NULL,'');
  $this->objgradelist4->addOption('HG','HG');
  $this->objgradelist4->addOption('SG','SG');
  
  $this->objgradelist5  = new dropdown('grade5');
  $this->objgradelist5->addOption(NULL,'');
  $this->objgradelist5->addOption('HG','HG');
  $this->objgradelist5->addOption('SG','SG');
  
  $this->objgradelist6  = new dropdown('grade6');
  $this->objgradelist6->addOption(NULL,'');
  $this->objgradelist6->addOption('HG','HG');
  $this->objgradelist6->addOption('SG','SG');
  
  $this->objgradelist7  = new dropdown('grade7');
  $this->objgradelist7->addOption(NULL,'');
  $this->objgradelist7->addOption('HG','HG');
  $this->objgradelist7->addOption('SG','SG');

/**subjects dropdown list**/
  $data = $this->objfaculties->getSubjects();
  for($i=0; $i < count($data); $i++){
    $subj[$i]= $data[$i]->LNGDSC;
  }
  $objsubjlist1 = new dropdown('subjlist1');
  sort($subj);
  foreach($subj as $subject){
    $objsubjlist1->addOption(NULL, ''.'Please select a subject'); 
    $objsubjlist1->addOption($subject,$subject);
  } 
  
  //2
  $objsubjlist2 = new dropdown('subjlist2');
  sort($subj);
  foreach($subj as $subject){
    $objsubjlist2->addOption(NULL, ''.'Please select a subject'); 
    $objsubjlist2->addOption($subject,$subject);
  } 
  
  //3
  $objsubjlist3 = new dropdown('subjlist3');
  sort($subj);
  foreach($subj as $subject){
    $objsubjlist3->addOption(NULL, ''.'Please select a subject'); 
    $objsubjlist3->addOption($subject,$subject);
  } 
  
  //4
  $objsubjlist4 = new dropdown('subjlist4');
  sort($subj);
  foreach($subj as $subject){
    $objsubjlist4->addOption(NULL, ''.'Please select a subject'); 
    $objsubjlist4->addOption($subject,$subject);
  } 
  
  //5
  $objsubjlist5 = new dropdown('subjlist5');
  sort($subj);
  foreach($subj as $subject){
    $objsubjlist5->addOption(NULL, ''.'Please select a subject'); 
    $objsubjlist5->addOption($subject,$subject);
  } 
  
  //6
  $objsubjlist6 = new dropdown('subjlist6');
  sort($subj);
  foreach($subj as $subject){
    $objsubjlist6->addOption(NULL, ''.'Please select a subject'); 
    $objsubjlist6->addOption($subject,$subject);
  } 
  
  //7
  $objsubjlist7 = new dropdown('subjlist7');
  sort($subj);
  foreach($subj as $subject){
    $objsubjlist7->addOption(NULL, ''.'Please select a subject'); 
    $objsubjlist7->addOption($subject,$subject);
  } 
  //echo "<pre>";
  //print_r($data);die;
        
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
  //create dropdown for course values and populate with array $crse data 
  $objDropdown1 = new dropdown('course');
  sort($crse); 
  foreach($crse as $sessC){
    $objDropdown1->addOption(NULL, ''.$courseselect);
    $objDropdown1->addOption($sessC,$sessC); 
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
    //create dropdown for course values and populate with array $crse data 
    $objDropdown22 = new dropdown('course2nd');
    sort($crse2); 
    foreach($crse2 as $sessC){
      $objDropdown22->addOption(NULL, ''.$courseselect);
      $objDropdown22->addOption($sessC,$sessC); 
    } 
          
/**more info required from**/
    $deptnames  = array('1' =>  'Financial Aid',
                        '2' =>  'Student Credit Management',
                        '3' =>  'Residence and Catering', 
                   );
    $objdeptnames = new dropdown('moreinfo');
    foreach($deptnames as $dept){
      $objdeptnames->addOption(NULL, ''.'Please select a department'); 
      $objdeptnames->addOption($dept,$dept);
    } 
//$objdeptnames->extra = ' onChange="document.studedudata.submit()"';



/**
  *create a next button
  */
    $this->objButtonNext  = new button('next', 'Next');
    $this->objButtonNext->setToSubmit();

/**
  * create text inputs
  */         
       $this->objtxtpercentage = new textinput("txtPercentage1");
       //$this->objtxtpercentage->value  = " ";
       $this->objtxtpercentage->size  = 3;
       
       $this->objtxtpercentage2 = new textinput("txtPercentage2");
       //$this->objtxtpercentage2->value  = " ";
       $this->objtxtpercentage2->size  = 3;
       
       $this->objtxtpercentage3 = new textinput("txtPercentage3");
       //$this->objtxtpercentage3->value  = " ";
       $this->objtxtpercentage3->size  = 3;
       
       $this->objtxtpercentage4 = new textinput("txtPercentage4");
       //$this->objtxtpercentage4->value  = " ";
       $this->objtxtpercentage4->size  = 3;
       
       $this->objtxtpercentage5 = new textinput("txtPercentage5");
       //$this->objtxtpercentage5->value  = " ";
       $this->objtxtpercentage5->size  = 3;
       
       $this->objtxtpercentage6 = new textinput("txtPercentage6");
       //$this->objtxtpercentage6->value  = " ";
       $this->objtxtpercentage6->size  = 3;
       
       $this->objtxtpercentage7 = new textinput("txtPercentage7");
       //$this->objtxtpercentage7->value  = " ";
       $this->objtxtpercentage7->size  = 3;
       
      $this->objgradedropdown  = new dropdown('gradeval');
      $this->objgradedropdown->addOption(NULL,' ');
      $this->objgradedropdown->addOption('11','11');                 
      $this->objgradedropdown->addOption('12','12');

$phrase = '<i>'.'(Please complete subjects & mark obtained corresponding to grade selected)'.'</i>';
$v = "Grade";

/**
  *create table to place form elements in
  */
    $myTable=$this->newObject('htmltable','htmlelements');
    $myTable->width='80%';
    $myTable->border='0';
    $myTable->cellspacing='6';
    $myTable->cellpadding='10';
    
    
    $myTable->startRow();
    $myTable->addCell('Results captured for grade');
    $myTable->addCell("<span class=error>" .'<b>'.'*'."</span>".'</b>'.$this->objgradedropdown->show().$phrase);
    $myTable->endRow();
    
    $myTable->startRow();
    $myTable->addCell('Subject 1');
    $myTable->addCell("<span class=error>" .'<b>'.'*'."</span>".'</b>'.$objsubjlist1->show().' '.$this->objgradelist1->show().' '.$this->objtxtpercentage->show()."<span class=error>" .'<b>'."%"."</span>".'</b>');
    $myTable->endRow();
    
    $myTable->startRow();
    $myTable->addCell('Subject 2');
    $myTable->addCell("<span class=error>" .'<b>'.'*'."</span>".'</b>'.$objsubjlist2->show().' '.$this->objgradelist2->show().' '.$this->objtxtpercentage2->show()."<span class=error>" .'<b>'."%"."</span>".'</b>');
    $myTable->endRow();
    
    $myTable->startRow();
    $myTable->addCell('Subject 3');
    $myTable->addCell("<span class=error>" .'<b>'.'*'."</span>".'</b>'.$objsubjlist3->show().' '.$this->objgradelist3->show().' '.$this->objtxtpercentage3->show()."<span class=error>" .'<b>'."%"."</span>".'</b>');
    $myTable->endRow();
    
    $myTable->startRow();
    $myTable->addCell('Subject 4');
    $myTable->addCell("<span class=error>" .'<b>'.'*'."</span>".'</b>'.$objsubjlist4->show().' '.$this->objgradelist4->show().' '.$this->objtxtpercentage4->show()."<span class=error>" .'<b>'."%"."</span>".'</b>');
    $myTable->endRow();
    
    $myTable->startRow();
    $myTable->addCell('Subject 5');
    $myTable->addCell("<span class=error>" .'<b>'.'*'."</span>".'</b>'.$objsubjlist5->show().' '.$this->objgradelist5->show().' '.$this->objtxtpercentage5->show()."<span class=error>" .'<b>'."%"."</span>".'</b>');
    $myTable->endRow();
    
    $myTable->startRow();
    $myTable->addCell('Subject 6');
    $myTable->addCell("<span class=error>" .'<b>'.'*'."</span>".'</b>'.$objsubjlist6->show().' '.$this->objgradelist6->show().' '.$this->objtxtpercentage6->show()."<span class=error>" .'<b>'."%"."</span>".'</b>');
    $myTable->endRow();
    
    $myTable->startRow();
    $myTable->addCell('Subject 7');
    $myTable->addCell("&nbsp".$objsubjlist7->show().' '.$this->objgradelist7->show().' '.$this->objtxtpercentage7->show()."<span class=error>" .'<b>'."%"."</span>".'</b>');
    $myTable->endRow();
    
    $myTable->startRow();
    $myTable->addCell($this->objButtonNext->show());
    $myTable->endRow();

/**
  *display content to screen -- add to form
  */
    
$objForm = new form('studedudata',$this->uri(array('action'=>'studentdetailsoutput')));
$objForm->displayType = 3;
$objForm->addToForm($content . '<br/>'.$myTable->show());
$objForm->addRule('gradeval','Please select school grade','required');
$objForm->addRule('subjlist1','Please select subject','required');
$objForm->addRule('grade','Please select grade type','required');
$objForm->addRule('txtPercentage1','Please enter mark percentage','required');
$objForm->addRule('txtPercentage1','Must be numeric','numeric');
$objForm->addRule(array('name'=>'txtPercentage1','length'=>2), 'Maximum size 2', 'maxlength');
$objForm->addRule('subjlist2','Please select subject','required');
$objForm->addRule('grade2','Please select grade type','required');
$objForm->addRule('txtPercentage2','Please enter mark percentage','required');
$objForm->addRule('txtPercentage2','Must be nueric','numeric');
$objForm->addRule(array('name'=>'txtPercentage2','length'=>2), 'Maximum size 2', 'maxlength');
$objForm->addRule('subjlist3','Please select subject','required');
$objForm->addRule('grade3','Please select grade type','required');
$objForm->addRule('txtPercentage3','Please enter mark percentage','required');
$objForm->addRule('txtPercentage3','Must be nueric','numeric');
$objForm->addRule(array('name'=>'txtPercentage3','length'=>2), 'Maximum size 2', 'maxlength');
$objForm->addRule('subjlist4','Please select subject','required');
$objForm->addRule('grade4','Please select grade type','required');
$objForm->addRule('txtPercentage4','Please enter mark percentage','required');
$objForm->addRule('txtPercentage4','Must be nueric','numeric');
$objForm->addRule(array('name'=>'txtPercentage4','length'=>2), 'Maximum size 2', 'maxlength');
$objForm->addRule('subjlist5','Please select subject','required');
$objForm->addRule('grade5','Please select grade type','required');
$objForm->addRule('txtPercentage5','Please enter mark percentage','required');
$objForm->addRule('txtPercentage5','Must be nueric','numeric');
$objForm->addRule(array('name'=>'txtPercentage5','length'=>2), 'Maximum size 2', 'maxlength');
$objForm->addRule('subjlist6','Please select subject','required');
$objForm->addRule('grade6','Please select grade type','required');
$objForm->addRule('txtPercentage6','Please enter mark percentage','required');
$objForm->addRule('txtPercentage6','Must be nueric','numeric');
$objForm->addRule(array('name'=>'txtPercentage6','length'=>2), 'Maximum size 2', 'maxlength');
echo  $objForm->show();                  
?>
