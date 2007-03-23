<?php
//template for creating sports admin pg

/**
  *load all classes and create all objects
  */
$this->loadClass('button','htmlelements');  
$this->loadClass('tabbedbox', 'htmlelements');
$this->loadClass('link', 'htmlelements');
$this->loadClass('checkbox', 'htmlelements');

/**
 *create form heading
 */
$this->objMainheading =& $this->getObject('htmlheading','htmlelements');
$this->objMainheading->type=1;
$this->objMainheading->str=$objLanguage->languageText('mod_marketingrecruitmentforum_sportheading','marketingrecruitmentforum');

/**
 *create all checkboxes
 */
$objElement1 = new checkbox('Athletics','Athletics'); //this will not be checked
$check1 .= $objElement1->show(); 

$objElement2 = new checkbox('Rugby','Rugby'); //this will not be checked
$check2 .= $objElement2->show(); 

$objElement3 = new checkbox('Aquatics','Aquatics'); //this will not be checked
$check3 .= $objElement3->show(); 

$objElement4 = new checkbox('Cricket','Cricket'); //this will not be checked
$check4 .= $objElement4->show(); 

$objElement5 = new checkbox('Football','Football'); //this will not be checked
$check5 .= $objElement5->show(); 

$objElement6 = new checkbox('Basketball','Basketball'); //this will not be checked
$check6 .= $objElement6->show(); 

$objElement7 = new checkbox('Karate','Karate'); //this will not be checked
$check7 .= $objElement7->show(); 

$objElement8 = new checkbox('Chess','Chess'); //this will not be checked
$check8 .= $objElement8->show(); 

$objElement9 = new checkbox('Netball','Netball'); //this will not be checked
$check9 .= $objElement9->show(); 

$objElement10 = new checkbox('Volleyball','Volleyball'); //this will not be checked
$check10 .= $objElement10->show(); 

$objElement11 = new checkbox('Dance Sport','Dance Sport'); //this will not be checked
$check11 .= $objElement11->show(); 

$objElement12 = new checkbox('Boxing','Boxing'); //this will not be checked
$check12 .= $objElement12->show(); 

$objElement13 = new checkbox('Golf','Golf'); //this will not be checked
$check13 .= $objElement13->show(); 

$objElement14 = new checkbox('Pool','Pool'); //this will not be checked
$check14 .= $objElement14->show(); 

$objElement15 = new checkbox('Other','Other'); //this will not be checked
$check15 .= $objElement15->show(); 

/**
  *create radio buttons
  */
$objsport = new radio('sportParticipated');
$objsport->addOption('Yes','Yes');
$objsport->addOption('No','No');
    
$objsportbursary = new radio('sportBursary');
$objsportbursary->addOption('Yes','Yes');
$objsportbursary->addOption('No','No');

/**
 *create dropdown list
 */
$a  = array('1' =>  'Junior',
            '2' =>  'Senior',
            );
$list = new dropdown('listA');
foreach($a as $dept){
   $list->addOption(NULL, ''.''); 
   $list->addOption($dept,$dept);
}

$b  = array('1' =>  'Local',
            '2' =>  'Provincial',
            '3' =>  'National',
            '4' =>  'International',
            );
$list1 = new dropdown('listB');
foreach($a as $ab){
   $list1->addOption(NULL, ''.''); 
   $list1->addOption($ab,$ab);
}  
  
$c  = array('1' =>  'Community',
            '2' =>  'Schools',
           );
$list2 = new dropdown('listC');
foreach($c as $ac){
   $list1->addOption(NULL, ''.''); 
   $list1->addOption($ac,$ac);
}  

/**
  *create a next button
  */
$this->objButtonNext  = new button('next', 'Next');
$this->objButtonNext->setToSubmit();

/**
  *create table to place form elements in
  */
$myTable=$this->newObject('htmltable','htmlelements');
$myTable->width='80%';
$myTable->border='0';
$myTable->cellspacing='6';
$myTable->cellpadding='10';
    
    
$myTable->startRow();
$myTable->addCell('Have you participated in sports?');
$myTable->addCell($objsport->show());
$myTable->endRow();

$myTable->startRow();
$myTable->addCell('Indicate which sport code(s)');
$myTable->addCell($check1);
$myTable->addCell($check2);
$myTable->addCell($check3);
$myTable->addCell($check4);
$myTable->endRow();

$myTable->startRow();
$myTable->addCell($check5);
$myTable->addCell($check6);
$myTable->addCell($check7);
$myTable->addCell($check8);
$myTable->endRow();

$myTable->startRow();
$myTable->addCell($check9);
$myTable->addCell($check10);
$myTable->addCell($check11);
$myTable->addCell($check12);
$myTable->endRow();

$myTable->startRow();
$myTable->addCell($check13);
$myTable->addCell($check14);
$myTable->addCell($check15);
$myTable->endRow();

$myTable->startRow();
$myTable->addCell('What achievement level did you obtain?');
$myTable->addCell($list);
$myTable->endRow();

$myTable->startRow();
$myTable->addCell(' ');
$myTable->addCell($list);
$myTable->endRow();

$myTable->startRow();
$myTable->addCell(' ');
$myTable->addCell($list1);
$myTable->endRow();

$myTable->startRow();
$myTable->addCell(' ');
$myTable->addCell($list2);
$myTable->endRow();

$myTable->startRow();
$myTable->addCell('Would you like to apply for a sports bursary?');
$myTable->addCell($objsportbursary->show());
$myTable->endRow();

$myTable->startRow();
$myTable->addCell('Are you in any leadership positions?');
$myTable->addCell();
$myTable->endRow();

$myTable->startRow();
$myTable->addCell($this->objButtonNext->show());
$myTable->endRow();

/**
  *display content to screen -- add to form
  */
  
$objForm = new form('sportdata',$this->uri(array('action'=>'?')));
$objForm->displayType = 3;
$objForm->addToForm($this->objMainheading->show().'<br/>'.$myTable->show());

echo  $objForm->show();                  

?>
