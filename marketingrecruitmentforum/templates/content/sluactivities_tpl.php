<?php
/**
 *create a tempate for the SLU activities interface
 */ 
 
/**
  *load all classes
  */
  
  $this->loadClass('datepicker','htmlelements');
  $this->loadClass('dropdown','htmlelements');
  $this->loadClass('textinput','htmlelements');
  
  
/**
  *create all language elements
  */
  
  $date = $this->objLanguage->languageText('word_date');
  $activity = $this->objLanguage->languageText('word_activity');
  $school = $this->objLanguage->languageText('word_school');
  $area = $this->objLanguage->languageText('word_area');
  $province = $this->objLanguage->languageText('word_province');
  
/**
  *create all textinputs
  */
 
  $this->objtxtschoolname = $this->newObject('textinput','htmlelements'); 
  $this->objtxtschoolname->name   = "txtschoolname";
  $this->objtxtschoolname->value  = "";
 
/**
  *create all date selection elements
  */
 
$this->objdate = $this->newObject('datepicker','htmlelements');
$name = 'txtdate';
$datevalue = date('Y-m-d');
$format = 'YYYY-MM-DD';
$this->objdate->setName($name);
$this->objdate->setDefaultDate($datevalue);
$this->objdate->setDateFormat($format); 
 
/**
  *create all dropdown list
  */
        
   //$activityvals  = 'activityvalues';           
   $this->objactivitydropdown  = new dropdown('activityvalues');//$this->newObject('dropdown','htmlelements');
   $this->objactivitydropdown->addOption('Activity 1','Activity 1') ;
   $this->objactivitydropdown->addOption('Activity 2','Activity 2') ;
   $this->objactivitydropdown->addOption('Activity 3','Activity 3') ;
   $this->objactivitydropdown->addOption('Activity 4','Activity 4') ;
   $this->objactivitydropdown->addOption('Activity 5','Activity 5') ;
   $this->objactivitydropdown->size = 50;
   
   $this->objareadropdown  = new dropdown('area');//$this->newObject('dropdown','htmlelements');
   $this->objareadropdown->addOption('Area 1','Area 1') ;
   $this->objareadropdown->addOption('Area 2','Area 2') ;
   $this->objareadropdown->addOption('Area 3','Area 3') ;
   $this->objareadropdown->addOption('Area 4','Area 4') ;
   $this->objareadropdown->addOption('Area 5','Area 5') ;
   $this->objareadropdown->size = 50;
   
   $this->objprovincedropdown  = new dropdown('area');//$this->newObject('dropdown','htmlelements');
   $this->objprovincedropdown->addOption('Western Cape','Western Cape') ;
   $this->objprovincedropdown->addOption('Eastern Cape','Eastern Cape') ;
   $this->objprovincedropdown->addOption('Northern Cape','Northern Cape') ;
   $this->objprovincedropdown->addOption('Gauteng','Gauteng') ;
   $this->objprovincedropdown->addOption('Kwazulu Natal','Kwazulu Natal') ;
   $this->objprovincedropdown->addOption('Free State','Free State') ;
   $this->objprovincedropdown->addOption('Mpumalanga','Mpumalanga') ;
   $this->objprovincedropdown->addOption('Limpopo Province','Limpopo Province') ;
   $this->objprovincedropdown->addOption('North-West Province','North-West Province') ;
   $this->objprovincedropdown->size = 50;
   
/**
 *create a table to place all elements in
 */
  
  $myTable=$this->newObject('htmltable','htmlelements');
  $myTable->width='80%';
  $myTable->border='0';
  $myTable->cellspacing='2';
  $myTable->cellpadding='10';
           
  $myTable->startRow();
  $myTable->addCell(ucfirst($date));
  $myTable->addCell($this->objdate->show());
  $myTable->endRow();
         
  $myTable->startRow();
  $myTable->addCell(ucfirst($activity));
  $myTable->addCell($this->objactivitydropdown->show());
  $myTable->endRow();
  
  $myTable->startRow();
  $myTable->addCell(ucfirst($school));
  $myTable->addCell($this->objtxtschoolname->show());
  $myTable->endRow();
  
  $myTable->startRow();
  $myTable->addCell(ucfirst($area));
  $myTable->addCell($this->objareadropdown->show());
  $myTable->endRow();
  
  $myTable->startRow();
  $myTable->addCell(ucfirst($province));
  $myTable->addCell($this->objprovincedropdown->show());
  $myTable->endRow();       
  
/**
  *create a form to place all elements in
  */
  $this->loadClass('form','htmlelements');
  $objForm = new form('sluactivities',$this->uri(array('action'=>'null')));
  $objForm->displayType = 3;
  $objForm->addToForm($myTable->show());
          
  /**
    *display the slu activity interface
    */
                                
    echo  $objForm->show();   
 
?>
