<?php
//template for creating sports admin pg

/**
  *load all classes and create all objects
  */
$this->loadClass('button','htmlelements');  
$this->loadClass('tabbedbox', 'htmlelements');
$this->loadClass('link', 'htmlelements');
$this->loadClass('radio', 'htmlelements');
$this->loadClass('textinput','htmlelements');

/**
 *create form heading
 */
$this->objMainheading =& $this->getObject('htmlheading','htmlelements');
$this->objMainheading->type=1;
$this->objMainheading->str=$objLanguage->languageText('mod_marketingrecruitmentforum_sportedit','marketingrecruitmentforum');

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
 *create dropdown list for achievement level
 */
$a  = array('1' =>  'Junior',
            '2' =>  'Senior',
            '3' =>  'Local',
            '4' =>  'Provincial',
            '5' =>  'National',
            '6' =>  'International',
            '7' =>  'Community',
            '8' =>  'Schools',
            );
$list = new dropdown('listA');
$list->multiple = true;
foreach($a as $a1){
   $list->addOption(NULL, ''.'Please select an option'); 
   $list->addOption($a1,$a1);
}
$list->setMultiSelected($a);

/**
 *create dropdown list for leadership position
 */
$b  = array('1' =>  'Head girl or Head boy',
            '2' =>  'Captain',
            '3' =>  'prefect',
            //'4' =>  'Other',
            );
$list2 = new dropdown('listB');
$list2->multiple = true;
foreach($b as $b1){
   $list2->addOption(NULL, ''.''); 
   $list2->addOption($b1,$b1);
}
$list->addOption('Other','Other');
$list2->setMultiSelected($b);
$list2->setSelected($this->getParam('listB','Please select an option'));
$list2->extra = ' onChange="document.sportdata.submit()"';

/**
 *create dropdown list for sport codes
 */
$c  = array('1' =>  'Athletics',
            '2' =>  'Rugby',
            '3' =>  'Aquatics',
            '4' =>  'Cricket',
            );
$list3 = new dropdown('listC');
$list3->multiple = true;
$list3->addOption(NULL, ''.'Please select an option'); 
$list3->addOption('Athletics','Athletics');
$list3->addOption('Rugby','Rugby');
$list3->addOption('Aquatics','Aquatics');
$list3->addOption('Cricket','Cricket');
$list3->addOption('Football','Football');
$list3->addOption('Basketball','Basketball');
$list3->addOption('Karate','Karate');
$list3->addOption('Chess','Chess');
$list3->addOption('Netball','Netball');
$list3->addOption('Volleyball','Volleyball');
$list3->addOption('Dance Sport','Dance Sport');
$list3->addOption('Boxing','Boxing');
$list3->addOption('Golf','Golf');
$list3->addOption('Pool','Pool');
$list3->addOption('Other','Other');
$list3->setMultiSelected($c);
$list3->setSelected($this->getParam('listC','Please select an option'));
$list3->extra = ' onChange="document.sportdata.submit()"';

/** 
 *CREATE text fields
 */
$this->objOtherCode = new textinput('otherCode'); 
$this->objOtherCode->value  = " ";

$this->objleadership = new textinput('leadership'); 
$this->objleadership->value  = " ";

  
/**
  *create a next button
  */
$this->objButtonNext  = new button('next', 'Next');
$this->objButtonNext->setToSubmit();

if($leadership = 'Other'){
  $leadOther  = $this->objleadership->show();
}

if($sportcode = 'Other'){
 $SportOther  = $this->objOtherCode->show();
}
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
$myTable->addCell('Are you in any leadership positions?');
$myTable->addCell($list2->show().' '.$leadOther);
$myTable->endRow();

$myTable->startRow();
$myTable->addCell('Which sporting codes have you been involved in?');
$myTable->addCell($list3->show().' '.$SportOther);
$myTable->endRow();


$myTable->startRow();
$myTable->addCell('What achievement level did you obtain?');
$myTable->addCell($list->show());
$myTable->endRow();

$myTable->startRow();
$myTable->addCell('Would you like to apply for a sports bursary?');
$myTable->addCell($objsportbursary->show());
$myTable->endRow();


$myTable->startRow();
$myTable->addCell($this->objButtonNext->show());
$myTable->endRow();

/**
  *display content to screen -- add to form
  */
  
$objForm = new form('sportdata',$this->uri(array('action'=>'sportoutputshow')));
$objForm->displayType = 3;
$objForm->addToForm($this->objMainheading->show().'<br/>'.$myTable->show());

echo  $objForm->show();               
?>
