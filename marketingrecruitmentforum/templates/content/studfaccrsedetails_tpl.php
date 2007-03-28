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
  $this->objMainheading->str=$objLanguage->languageText('mod_marketingrecruitmentforum_addinformation','marketingrecruitmentforum');

/**
  *create all language elements
  */
  $facultyselect = $this->objLanguage->languageText('mod_marketingrecruitmentforum_facultymsg1','marketingrecruitmentforum');
  $courseselect = $this->objLanguage->languageText('mod_marketingrecruitmentforum_coursemsg','marketingrecruitmentforum');    
  $content = ' ';  
  $content .=  $this->objMainheading->show(); 
         
/**
  * create dropdown list
  */
/**more info required from**/
    $deptnames  = array('1' =>  'Financial Aid',
                        '2' =>  'Student Credit Management',
                        '3' =>  'Residence and Catering', 
                        '4' =>  'Edu Loan',
                        '5' =>  'Sports Admin',
                   );
     $objdeptnames = new dropdown('moreinfo');
     $objdeptnames->multiple = true;
     $objdeptnames->addOption(NULL ,  'Please select a department');
     $objdeptnames->addOption('Financial Aid','Financial Aid');
     $objdeptnames->addOption('Student Credit Management' ,'Student Credit Management');
     $objdeptnames->addOption('Residence and Catering' ,'Residence and Catering');
     $objdeptnames->addOption('Edu Loan' ,'Edu Loan');
     $objdeptnames->addOption('Sports Admin' ,'Sports Admin');
//     $objdeptnames->setMultiSelected($deptnames);
//$objdeptnames->extra = ' onChange="document.studedudata.submit()"';
   
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
    $myTable->width='60%';
    $myTable->border='0';
    $myTable->cellspacing='6';
    $myTable->cellpadding='10';
    
    $myTable->startRow();
    $myTable->addCell('More information required from');
    $myTable->addCell($objdeptnames->show());
    $myTable->endRow();
    
    $myTable->startRow();
    $myTable->addCell('Will you need Residence?');
    $myTable->addCell($objResidence->show());
    $myTable->endRow();
    
    /*$myTable->startRow();
    $myTable->addCell('Do you Qualify for an Exemption');
    $myTable->addCell($objElement->show());
    $myTable->endRow();*/
    
/*    $myTable->startRow();
    $myTable->addCell('Senate Discretionary (SD) Case');
    $myTable->addCell($objsdcase->show());
    $myTable->endRow();*/
    
    $myTable->startRow();
    $myTable->addCell($this->objButtonNext->show());
    $myTable->endRow();

/**
  *display content to screen -- add to form
  */
  
$objForm = new form('studedudata',$this->uri(array('action'=>'showdboutput')));
$objForm->displayType = 3;
$objForm->addToForm($content . '<br/>'.$myTable->show());
$objForm->addRule('residence','Please complete residence field ','required');

echo  $objForm->show();                  
?>
