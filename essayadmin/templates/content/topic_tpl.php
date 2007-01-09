<?php
/*
* Template for adding / editing a topic
* @package essayadmin
*/

/**
* @param array $data Array containing the details of the topic for editing.
* @param string $head The heading for the page.
*/

/**************** Set Layout template ***************************/
$this->setLayoutTemplate('essayadmin_layout_tpl.php');

// set up html elements
$objTable=$this->objTable;
$objLayer=$this->objLayer;
$objDrop =& $this->newObject('dropdown', 'htmlelements');

// Set up language items
$topicArea=$this->objLanguage->languageText('mod_essayadmin_topicarea', 'essayadmin');
$description=$this->objLanguage->languageText('mod_essayadmin_description', 'essayadmin');
$instructions=ucwords($this->objLanguage->code2Txt('mod_essayadmin_instructions','essayadmin'));
$closeDate=$this->objLanguage->languageText('mod_essayadmin_closedate','essayadmin');
$bypass=$this->objLanguage->languageText('mod_essayadmin_bypass','essayadmin').' '.$closeDate;
$force=ucwords($this->objLanguage->code2Txt('mod_essayadmin_force','essayadmin'));
$save=$this->objLanguage->languageText('word_save');
$reset=$this->objLanguage->languageText('word_reset');
$exit=$this->objLanguage->languageText('word_cancel');
$percentLbl=$this->objLanguage->languageText('mod_essayadmin_percentyrmark', 'essayadmin');
$errPercent=$this->objLanguage->languageText('mod_essayadmin_numericpercent');
$help=$this->objLanguage->LanguageText('help_essayadmin_overview_addtopic', 'essayadmin');

$errTopic = $this->objLanguage->languageText('mod_essayadmin_entertopic');

// javascript
$javascript = "<script language=\"javascript\" type=\"text/javascript\">
    function submitExitForm(){
        document.exit.submit();
    }

</script >";

echo $javascript;

// Set up data, passed as a variable from controller
if(!empty($data)){
// put date in correct format
    
    $did=$data[0]['id'];
    $dTopic=$data[0]['name'];
    $dDescription=$data[0]['description'];
    $dInstructions=$data[0]['instructions'];
    $dDate=$this->objDateFormat->formatDate($data[0]['closing_date']);
    $dBypass=$data[0]['bypass'];
    $dForce=$data[0]['forceone'];
    $dPercent=$data[0]['percentage'];
}else {
    $did='';
    $dTopic='';
    $dDescription='';
    $dInstructions='';
    $dDate=date('Y-m-d');
    $dBypass='';
    $dForce='';
    $dPercent=0;
}
$head.='&nbsp;&nbsp;&nbsp;&nbsp;'.$this->objHelp->show($help);
$this->setVarByRef('heading',$head);

$objTable->row_attributes=' height="10"';
$objTable->startRow();
$objTable->addCell('');
$objTable->endRow();

/************** Build form elements **********************/

// topic area
$this->objInput = new textinput('topicarea', $dTopic, '', 88);
$this->objInput->extra=' wrap="soft"';

$objTable->startRow();
$objTable->addCell('<b>'.$topicArea.':</b>','','center','center','',' colspan="5"');
$objTable->endRow();

$objTable->row_attributes=' height="5"';
$objTable->startRow();
$objTable->addCell('');
$objTable->endRow();

$objTable->startRow();
$objTable->addCell($this->objInput->show(),'','center','center','',' colspan="5"');
$objTable->endRow();

$objTable->row_attributes=' height="10"';
$objTable->startRow();
$objTable->addCell('');
$objTable->endRow();

// topic description
$this->objText->textarea('description',$dDescription,3,85);
$this->objText->extra=' wrap="soft"';

$objTable->startRow();
$objTable->addCell('<b>'.$description.':</b>','','center','center','',' colspan="5"');
$objTable->endRow();

$objTable->row_attributes=' height="5"';
$objTable->startRow();
$objTable->addCell('');
$objTable->endRow();

$objTable->startRow();
$objTable->addCell($this->objText->show(),'','center','center','',' colspan="5"');
$objTable->endRow();

$objTable->row_attributes=' height="10"';
$objTable->startRow();
$objTable->addCell('');
$objTable->endRow();

// learner instructions
$this->objText->textarea('instructions',$dInstructions,3,85);
$this->objText->extra=' wrap="soft"';

$objTable->startRow();
$objTable->addCell('<b>'.$instructions.':</b>','','center','center','',' colspan="5"');
$objTable->endRow();

$objTable->row_attributes=' height="5"';
$objTable->startRow();
$objTable->addCell('');
$objTable->endRow();

$objTable->startRow();
$objTable->addCell($this->objText->show(),'','center','center','',' colspan="5"');
$objTable->endRow();

$objTable->row_attributes=' height="10"';
$objTable->startRow();
$objTable->addCell('');
$objTable->endRow();

// closing date & force 1 essay per student
/*$this->objInput = new textinput('timestamp', $dDate);
$this->objInput->extra = 'readonly=" readonly"';
$this->objIcon->setIcon('select_date');
$this->objIcon->alt=$this->objLanguage->languageText('mod_essayadmin_datepick','essayadmin');*/

$this->objpopcal =&$this->getObject('datepickajax','popupcalendar');
$this->objpopcal->show('closing_date','yes','no',$dDate); 

// $this->objessaydate = $this->newObject('datepicker','htmlelements');
//$name = 'closing_date';
//$date = date('Y-m-d');
//$format = 'YYYY-MM-DD';
//$this->objessaydate->setName($name);
//$this->objessaydate->setDefaultDate($date);
//$this->objessaydate->setDateFormat($format);


// $url = "javascript:show_calendar('document.topic.timestamp', document.topic.timestamp.value);";

//$url = $this->uri(array('action'=>'', 'field'=>'document.topic.timestamp', 'fieldvalue'=>$dDate, 'showtime'=>'no'), 'popupcalendar');
//$onclick = "javascript:window.open('" .$url."', 'popupcal', 'width="320", height="410", scrollbars="1", resize=yes')";

$this->objLink = new link('#');
$this->objLink->extra = "onclick=\"$onclick\"";
$this->objLink->link = $this->objIcon->show();

$this->objCheck = new checkbox('bypass','',$dBypass);
$bycheck=$this->objCheck->show();
$this->objCheck = new checkbox('force','',$dForce);
$fcheck=$this->objCheck->show();

$objTable->row_attributes=' height="25"';
$objTable->startRow();
$objTable->addCell('&nbsp;&nbsp;&nbsp;<b>'.$closeDate.'</b>','25%','center');
$objTable->addCell($this->objpopcal->show('closing_date','yes','no',$dDate));
$objTable->addCell('','3%');
$objTable->addCell('&nbsp;&nbsp;<b>'.$force.'</b>','35%','center');
$objTable->addCell($fcheck,'12%','center','left');
$objTable->endRow();

$objTable->startRow();
$objTable->addCell('&nbsp;&nbsp;&nbsp;<b>'.$bypass.'</b>','','center');
$objTable->addCell($bycheck,'','center');
$objTable->addCell('');

$objDrop = new dropdown('percentage');
for($x=0; $x<=100; $x++){
    $objDrop->addOption($x, $x);
}
$objDrop->setSelected($dPercent);
$percent = $objDrop->show();

$objTable->addCell('&nbsp;&nbsp;&nbsp;<b>'.$percentLbl.'</b>','','center');
$objTable->addCell($percent.'%','','left');
$objTable->endRow();


/*********************** hidden elements ************************/

$this->objInput = new textinput('id',$did);
$this->objInput->fldType='hidden';
$hidden=$this->objInput->show();

$objTable->row_attributes=' height="10"';
$objTable->startRow();
$objTable->addCell($hidden);
$objTable->endRow();

/********************* submit buttons **************************/

$this->objButton = new button('save',$save);
$this->objButton->setToSubmit();
$buttons=$this->objButton->show();/*
$this->objInput = new textinput('reset',$reset);
$this->objInput->fldType='reset';
$this->objInput->setCss('button');
$buttons.='&nbsp;&nbsp;&nbsp;'.$this->objInput->show();*/
$this->objButton1 = new button('exit',$exit);
$this->objButton1->setToSubmit();
$buttons.='&nbsp;&nbsp;&nbsp;'.$this->objButton1->show();

$objTable->startRow();
$objTable->addCell($buttons,'','center','center','',' colspan="5"');
$objTable->endRow();

$objTable->row_attributes=' height="10"';
$objTable->startRow();
$objTable->addCell('');
$objTable->endRow();

/************** Build form **********************/

$this->objForm = new form('topic',$this->uri(array('action'=>'savetopic')));
$this->objForm->addToForm($objTable->show());
$this->objForm->addRule('percentage', $errPercent, 'numeric');
$this->objForm->addRule('topicarea', $errTopic, 'required');


/************** Display page ********************/

// add form to layer
$objLayer->cssClass='odd';
$objLayer->str=$this->objForm->show();

// Display layer
echo $objLayer->show();


// exit form
$this->objForm = new form('exit',$this->uri(array('action' => 'savetopic')));
$this->objInput = new textinput('save', $exit);
$this->objInput->fldType = 'hidden';
$this->objForm->addToForm($this->objInput->show());
$this->objForm->addToForm($hidden);

echo $this->objForm->show();
?>