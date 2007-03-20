<?php
/**template used to display and capture student edu details**/

/**
  * load all classes
  */
$this->loadClass('datepicker','htmlelements');
$this->loadClass('dropdown','htmlelements');
$this->loadClass('textinput','htmlelements');
$this->loadClass('button','htmlelements');

$this->objfaculties =& $this->getObject('dbstudentcard','marketingrecruitmentforum');

/**
  *create form heading
  */
$this->objMainheading =& $this->getObject('htmlheading','htmlelements');
$this->objMainheading->type=1;
$this->objMainheading->str=$objLanguage->languageText('mod_marketingrecruitmentforum_studentcardinterface','marketingrecruitmentforum');
  
$content .=  $this->objMainheading->show(); 
         
/**
  * create dropdown list
  */
  
/** HS | SG**/
$this->objgradelist  = new dropdown('grade');
$this->objgradelist->addOption(NULL,'Please select a grade');
$this->objgradelist->addOption('HG','HG');
$this->objgradelist->addOption('SG','SG');

/**subjects -- get list of all subjects**/
        
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
foreach($deptnames as $dept){
  $objdeptnames->addOption(NULL, ''.'Please select a department'); 
  $objdeptnames->addOption($dept,$dept);
}  
$objdeptnames->extra = ' onChange="document.studedudata.submit()"';

switch($departname){
      
      case  'Financial Aid':
          $linkname = 'www.finaaid.com';
      break;
      
      case  'Student Credit Management':
          $linkname = 'www.finaaid.com';
      break;
      
      case  'Residence and Catering':
          $linkname = 'www.finaaid.com';//open in new window obv
      break;

}

/**
  *create a next button
  */
$this->objButtonNext  = new button('next', $str1);
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
$myTable->addCell('Please Select your 1st Choice Faculty');
$myTable->addCell($objDropdown->show());
$myTable->endRow();

$myTable->startRow();
$myTable->addCell('Please Select your 1st Choice Course');
$myTable->addCell($objDropdown1->show());
$myTable->endRow();

$myTable->startRow();
$myTable->addCell('Please Select your 2nd Choice Course');
$myTable->addCell($objDropdown2->show());
$myTable->endRow();  

$myTable->startRow();
$myTable->addCell('Please Select your 2st Choice Course');
$myTable->addCell($objDropdown22->show());
$myTable->endRow();

$myTable->startRow();
$myTable->addCell('Will you need Residence?');
$myTable->addCell($objResidence->show());
$myTable->endRow();

$myTable->startRow();
$myTable->addCell('More information required from');
$myTable->addCell('');
$myTable->endRow();

$myTable->startRow();
$myTable->addCell('Do you Qualify for an Exemption');
$myTable->addCell($objElement->show());
$myTable->endRow();

$myTable->startRow();
$myTable->addCell('Senate Discretionary (SD) Case');
$myTable->addCell($objsdcase->show());
$myTable->endRow();

$myTable->startRow();
$myTable->addCell($this->objButtonNext->show());
$myTable->endRow();

/**
  *display content to screen -- add to form
  */
  
$objForm = new form('studedudata',$this->uri(array('action'=>'showdepartments')));
$objForm->displayType = 3;
$objForm->addToForm($content . '<br/>'.$myTable->show());                   
?>
