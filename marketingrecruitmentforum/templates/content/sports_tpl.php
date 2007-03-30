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
$this->objMainheading->str=$objLanguage->languageText('mod_marketingrecruitmentforum_sportheading','marketingrecruitmentforum');

/**
  *create radio buttons
  */
$objsport = new radio('sportParticipated');
$objsport->addOption('Yes','Yes');
$objsport->addOption('No','No');

$objlead = new radio('leadPos');
$objlead->addOption('Yes','Yes');
$objlead->addOption('No','No');
    
$objsportbursary = new radio('sportBursary');
$objsportbursary->addOption('Yes','Yes');
$objsportbursary->addOption('No','No');

$leadership  = array('1' =>  'Head Girl or Head Boy',
                      '2' =>  'Prefect',
                      '3' =>  'Captain',
                      '4' =>  'Other',
                      '5' =>  'None',
                    );
$listleadership = new dropdown('leadership');
$listleadership->multiple = true;
$listleadership->size = 3;
foreach($leadership as $lead){
   $listleadership->addOption(NULL, ''.'Please select an option'); 
   $listleadership->addOption($lead,$lead);
}

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
$list = new dropdown('listB');
foreach($a as $a1){
   $list->addOption(NULL, ''.'Please select an option'); 
   $list->addOption($a1,$a1);
}

$list22 = new dropdown('listB1');
foreach($a as $a1){
   $list22->addOption(NULL, ''.'Please select an option'); 
   $list22->addOption($a1,$a1);
}

$list33 = new dropdown('listB2');
foreach($a as $a1){
   $list33->addOption(NULL, ''.'Please select an option'); 
   $list33->addOption($a1,$a1);
}

$list44 = new dropdown('listB3');
foreach($a as $a1){
   $list44->addOption(NULL, ''.'Please select an option'); 
   $list44->addOption($a1,$a1);
}

$list55 = new dropdown('listB4');
foreach($a as $a1){
   $list55->addOption(NULL, ''.'Please select an option'); 
   $list55->addOption($a1,$a1);
}

$list66 = new dropdown('listB5');
foreach($a as $a1){
   $list66->addOption(NULL, ''.'Please select an option'); 
   $list66->addOption($a1,$a1);
}


/**
 *create dropdown list for sport codes
 */
$c  = array('1' =>  'Athletics',
            '2' =>  'Rugby',
            '3' =>  'Aquatics',
            '4' =>  'Cricket',
            '5' =>  'Football',
            '6' =>  'Basketball',
            '7' =>  'Karate',
            '8' =>  'Karate',
            '9' =>  'Chess',
            '10' =>  'Netball',
            '11' =>  'Volleyball',
            '12' =>  'Dance Sport',
            '13' =>  'Boxing',
            '14' =>  'Golf',
            '15' =>  'Pool',
            );
$list3 = new dropdown('listC');
foreach($c as $cc){
  $list3->addOption(NULL, ''.'Please select an option');
  $list3->addOption($cc,$cc);
}
            
$list4 = new dropdown('listC2');
foreach($c as $cc2){
  $list4->addOption(NULL, ''.'Please select an option');
  $list4->addOption($cc2,$cc2);
}

$list5 = new dropdown('listC3');
foreach($c as $cc3){
  $list5->addOption(NULL, ''.'Please select an option');
  $list5->addOption($cc3,$cc3);
}

$list6 = new dropdown('listC4');
foreach($c as $cc4){
  $list6->addOption(NULL, ''.'Please select an option');
  $list6->addOption($cc4,$cc4);
}

$list7 = new dropdown('listC5');
foreach($c as $cc5){
  $list7->addOption(NULL, ''.'Please select an option');
  $list7->addOption($cc5,$cc5);
}

$list8 = new dropdown('listC6');
foreach($c as $cc6){
  $list8->addOption(NULL, ''.'Please select an option');
  $list8->addOption($cc6,$cc6);
}
   
//list for other option
$other = array( '0' =>  'Other',
                );   
$otherOption = new dropdown('other');
foreach($other as $othersess){
  $otherOption->addOption(NULL, ''.'Please select an option');
  $otherOption->addOption($othersess,$othersess);
}

/** 
 *CREATE text fields
 */
$this->objOtherCode = new textinput('otherCode'); 
$this->objOtherCode->value  = " ";

$this->objleadershipother = new textinput('txtleadership'); 
$this->objleadershipother->value  = " ";

  
/**
  *create a next button
  */
$this->objButtonNext  = new button('next', 'Next');
$this->objButtonNext->setToSubmit();

/**
  *create table to place form elements in
  */
$myTable=$this->newObject('htmltable','htmlelements');
$myTable->width='100%';
$myTable->border='0';
$myTable->cellspacing='6';
$myTable->cellpadding='10';
    
    
$myTable->startRow();
$myTable->addCell('Have you participated in sports?');
$myTable->addCell($objsport->show());
$myTable->endRow();

$myTable->startRow();
$myTable->addCell('Are you in any leadership positions?');
$myTable->addCell($objlead->show());
$myTable->endRow();

$myTable->startRow();
$myTable->addCell('');
$myTable->addCell('Please select the position(s) you held');
$myTable->endRow();

$myTable->startRow();
$myTable->addCell('');
$myTable->addCell($listleadership->show().' '.' '.'Please specify' .' '.' '.$this->objleadershipother->show());
$myTable->endRow();

$myTable->startRow();

$myTable->addCell('Which sporting codes have you been involved in?');
$myTable->addCell($list3->show().' '.' '.'At what level' .' '.' '."&nbsp"."&nbsp"."&nbsp".$list->show());
$myTable->endRow();

$myTable->startRow();
$myTable->addCell('');
$myTable->addCell($list4->show().' '.' '.'At what level' .' '.' '."&nbsp"."&nbsp"."&nbsp".$list22->show());
$myTable->endRow();

$myTable->startRow();
$myTable->addCell('');
$myTable->addCell($list5->show().' '.' '.'At what level' .' '.' '."&nbsp"."&nbsp"."&nbsp".$list33->show());
$myTable->endRow();

$myTable->startRow();
$myTable->addCell('');
$myTable->addCell($list6->show().' '.' '.'At what level' .' '.' '."&nbsp"."&nbsp"."&nbsp".$list44->show());
$myTable->endRow();

$myTable->startRow();
$myTable->addCell('');
$myTable->addCell($list7->show().' '.' '.'At what level' .' '.' '."&nbsp"."&nbsp"."&nbsp".$list55->show());
$myTable->endRow();

$myTable->startRow();
$myTable->addCell('');
$myTable->addCell($list8->show().' '.' '.'At what level' .' '.' '."&nbsp"."&nbsp"."&nbsp".$list66->show());
$myTable->endRow();

$myTable->startRow();
$myTable->addCell('');
$myTable->addCell($otherOption->show().' '.' '.'Please specify' .' '.$this->objOtherCode->show());
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
  
$objForm = new form('sportdata',$this->uri(array('action'=>'studentdetailsoutput')));
$objForm->displayType = 3;
$objForm->addToForm($this->objMainheading->show().'<br/>'.$myTable->show());

echo  $objForm->show();              
?>
