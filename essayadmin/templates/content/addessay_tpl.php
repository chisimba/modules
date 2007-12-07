<?php
/*
* Template for adding / editing an essay.
* @package essayadmin
*/

/**************** Set Layout template ***************************/
$this->setLayoutTemplate('essayadmin_layout_tpl.php');

// set up html elements
//$objTable=$this->objTable;
$this->loadClass('htmltable','htmlelements');
$this->loadClass('layer','htmlelements');
$this->loadClass('textarea','htmlelements');
$objText = new textarea;
$objLayer = new layer;

// javascript
$javascript = "<script language=\"javascript\" type=\"text/javascript\">
    function submitExitForm(){
        document.exit.submit();
    }

</script>";

echo $javascript;

/*************** Get data for editing *******************/

// check if data array is empty, if not populate form
if(!empty($data)){
    $dtopic=$data[0]['topic'];
    $dnotes=$data[0]['notes'];
    $did=$data[0]['id'];
}else{
    $dtopic='';
    $dnotes='';
    $did='';
}

// Set up language items
$head.=' '.$this->objLanguage->languageText('mod_essayadmin_in','essayadmin');
$topic=$this->objLanguage->languageText('mod_essayadmin_topic','essayadmin');
$head.=' '.$topic.': '.$topicname;
$notes=$this->objLanguage->languageText('mod_essayadmin_notes','essayadmin');
$code=$this->objLanguage->languageText('mod_essayadmin_code','essayadmin');
$closeDate=$this->objLanguage->languageText('mod_essayadmin_closedate','essayadmin');
$save=$this->objLanguage->languageText('word_save');
$reset=$this->objLanguage->languageText('word_reset','Reset');
$exit=$this->objLanguage->languageText('word_cancel');
$help='mod_essayadmin_helpcreateessay';

$errEssay = $this->objLanguage->languageText('mod_essayadmin_enteressay');

$head.='&nbsp;&nbsp;&nbsp;&nbsp;'.$this->objHelp->show($help);
$this->setVarByRef('heading',$head);

$objTable = new htmltable();
$objTable->row_attributes=' height="10"';
$objTable->startRow();
$objTable->addCell('');
$objTable->endRow();

/************** Build form elements **********************/

// topic area
$this->objInput = new textinput('essaytopic',$dtopic, '', 88);
$this->objInput->extra=' wrap="soft"';

$objTable->startRow();
$objTable->addCell('<b>'.$topic.':</b>','','center','center','',' colspan="3"');
$objTable->endRow();

$objTable->row_attributes=' height="5"';
$objTable->startRow();
$objTable->addCell('');
$objTable->endRow();

$objTable->startRow();
$objTable->addCell($this->objInput->show(),'','center','center','',' colspan="3"');
$objTable->endRow();

$objTable->row_attributes=' height="10"';
$objTable->startRow();
$objTable->addCell('');
$objTable->endRow();

// topic description
$objText->textarea('notes',$dnotes,3,85);
$objText->extra=' wrap="soft"';

$objTable->startRow();
$objTable->addCell('<b>'.$notes.':</b>','','center','center','',' colspan="3"');
$objTable->endRow();

$objTable->row_attributes=' height="5"';
$objTable->startRow();
$objTable->addCell('');
$objTable->endRow();

$objTable->startRow();
$objTable->addCell($objText->show(),'','center','center','',' colspan="3"');
$objTable->endRow();

$objTable->row_attributes=' height="10"';
$objTable->startRow();
$objTable->addCell('');
$objTable->endRow();

// submit buttons
$this->objButton = new button('save',$save);
$this->objButton->setToSubmit();
$buttons=$this->objButton->show();/*
$this->objInput = new textinput('reset',$reset);
$this->objInput->fldType='reset';
$this->objInput->setCss('button');
$buttons.='&nbsp;&nbsp;&nbsp;'.$this->objInput->show();*/
$this->objButton = new button('cancel',$exit);
$this->objButton->setOnClick('javascript:submitExitForm()');
$buttons.='&nbsp;&nbsp;&nbsp;'.$this->objButton->show();

$objTable->startRow();
$objTable->addCell($buttons,'','center','center','',' colspan="3"');
$objTable->endRow();

/************* Hidden Elements *******************/
$this->objInput = new textinput('essay',$did);
$this->objInput->fldType='hidden';
$hidden=$this->objInput->show();

$this->objInput = new textinput('id',$topicid);
$this->objInput->fldType='hidden';
$hidden.=$this->objInput->show();

$objTable->row_attributes=' height="10"';
$objTable->startRow();
$objTable->addCell($hidden);
$objTable->endRow();

/************** Build form **********************/

$this->objForm = new form('essay',$this->uri(array('action'=>'saveessay')));
$this->objForm->addToForm($objTable->show());
$this->objForm->addRule('essaytopic',$errEssay, 'required');


/************** Display page ********************/

// add form to layer
$objLayer->cssClass='odd';
$objLayer->str = $this->objForm->show();

// Display layer
echo $objLayer->show();

// exit form
$objForm = new form('exit',$this->uri(array('action' => 'saveessay')));
$objInput = new textinput('save', $exit);
$objInput->fldType = 'hidden';
$objForm->addToForm($objInput->show());
$objForm->addToForm($hidden);

echo $objForm->show();
?>
