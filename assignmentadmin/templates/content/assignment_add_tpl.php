<?php
/**
* Template for adding or editing an assignment that is not an essay, worksheet or quiz
* @package assignmentadmin
*/

/**
* Template for adding or editing an assignment that is not an essay, worksheet or quiz
* @param array $data The details of the assignment being edited.
*/
$this->setLayoutTemplate('assignmentadmin_layout_tpl.php');

// set up html elements 
$this->loadClass('textinput','htmlelements');
$this->loadClass('textarea','htmlelements');
$this->loadClass('radio','htmlelements');
$this->loadClass('button','htmlelements');
$this->loadClass('link','htmlelements');
$this->loadClass('label','htmlelements');
$this->loadClass('layer','htmlelements');
$this->loadClass('form','htmlelements');
$this->loadClass('htmltable','htmlelements');
$this->loadClass('dropdown','htmlelements');
$objIcon = $this->newObject('geticon','htmlelements');

// set up language items
$head1 = $this->objLanguage->languageText('mod_assignmentadmin_createassignment','assignmentadmin');
$head2 = $this->objLanguage->languageText('mod_assignmentadmin_editassignment','assignmentadmin');
$assignmentLabel = $this->objLanguage->languageText('mod_assignmentadmin_assignment','assignmentadmin');
$nameLabel = $this->objLanguage->languageText('mod_assignmentadmin_wordname','assignmentadmin');
$descriptionLabel = $this->objLanguage->languageText('mod_assignmentadmin_description','assignmentadmin');
$typeLabel = $this->objLanguage->languageText('mod_assignmentadmin_assignmenttype','assignmentadmin');
$resubmitLabel = $this->objLanguage->languageText('mod_assignmentadmin_allowresubmit','assignmentadmin');
$markLabel = $this->objLanguage->languageText('mod_assignmentadmin_mark','assignmentadmin');
$closingLabel = $this->objLanguage->languageText('mod_assignmentadmin_closingdate','assignmentadmin');
$yesLabel = $this->objLanguage->languageText('word_yes');
$noLabel = $this->objLanguage->languageText('word_no');
$onlineLabel = $this->objLanguage->languageText('mod_assignmentadmin_online','assignmentadmin');
$uploadLabel = $this->objLanguage->languageText('mod_assignmentadmin_upload','assignmentadmin');
$isreflectionLabel = $this->objLanguage->languageText('mod_assignmentadmin_isreflection','assignmentadmin');
$saveLabel = $this->objLanguage->languageText('word_save');
$exitLabel = $this->objLanguage->languageText('word_cancel');
$selectLabel = $this->objLanguage->languageText('mod_assignmentadmin_selectdate','assignmentadmin');
$percentLabel = $this->objLanguage->languageText('mod_assignmentadmin_percentyrmark','assignmentadmin');

$lnPlain = $this->objLanguage->languageText('mod_testadmin_plaintexteditor');
$lnWysiwyg = $this->objLanguage->languageText('mod_testadmin_wysiwygeditor');

// Error Messages - form validation
$errName = $this->objLanguage->languageText('mod_assignmentadmin_namerequired');
$errMark = $this->objLanguage->languageText('mod_assignmentadmin_marknumeric','assignmentadmin');
$errDate = $this->objLanguage->languageText('mod_assignmentadmin_dateformat');
$errMarkReq = $this->objLanguage->languageText('mod_assignmentadmin_markrequired','assignmentadmin');
$errPercent = $this->objLanguage->languageText('mod_assignmentadmin_numericpercent');

// javascript
$javascript = "<script language=\"javascript\" type=\"text/javascript\">
    function submitExitForm(){
        document.exit.submit();
    }

    function submitForm(val){
        document.add.action.value=val;
        document.add.submit();
    }

</script>";

echo $javascript;

if(!empty($data)){
    $heading = $head2;
    $id = $data[0]['id'];
    $name = $data[0]['name'];
    $format = $data[0]['format'];
    $resubmit = $data[0]['resubmit'];
    $mark = $data[0]['mark'];
    $percent = $data[0]['percentage'];
    $date = $data[0]['closing_date'];
    $description = $data[0]['description'];
}else{
    $format = $this->getParam('type', 0);

    $heading = $head1;
    $id = '';
    $name = '';
    $resubmit = 0;
    $mark = '';
    $percent = 0;
    $date = date('Y-m-d');
    $description = '';
}

$this->setVarByRef('heading', $heading);

$objTable = new htmltable();
$objTable->cellpadding=5;
$objTable->cellspacing=2;
$objTable->width='99%';

$objLabel = new label($assignmentLabel.' '.$nameLabel.':', 'input_name');
$objInput = new textinput('name', $name);
$objInput->size = 60;

$objTable->startRow();
$objTable->addCell($objLabel->show());
$objTable->addCell($objInput->show());
$objTable->endRow();

$objLabel = new label($typeLabel.':', 'input_format');
$objRadio = new radio('format');
$objRadio->setBreakSpace('&nbsp;&nbsp;&nbsp;&nbsp;');
$objRadio->addOption(0, $onlineLabel);
$objRadio->addOption(1, $uploadLabel);
$objRadio->setSelected($format);

$objTable->startRow();
$objTable->addCell($objLabel->show());
$objTable->addCell($objRadio->show());
$objTable->endRow();

$objLabel = new label($resubmitLabel.':', 'input_resubmit');
$objRadio = new radio('resubmit');
$objRadio->setBreakSpace('&nbsp;&nbsp;&nbsp;&nbsp;');
$objRadio->addOption(1,$yesLabel);
$objRadio->addOption(0,$noLabel);
$objRadio->setSelected($resubmit);

$objTable->startRow();
$objTable->addCell($objLabel->show());
$objTable->addCell($objRadio->show());
$objTable->endRow();

$objLabel = new label($isreflectionLabel.'? :', 'input_assesmentype');
$objRadio = new radio('assesment_type');
$objRadio->setBreakSpace('&nbsp;&nbsp;&nbsp;&nbsp;');
$objRadio->addOption(1,$yesLabel);
$objRadio->addOption(0,$noLabel);
$objRadio->setSelected($assesmentype);

$objTable->startRow();
$objTable->addCell($objLabel->show());
$objTable->addCell($objRadio->show());
$objTable->endRow();

$objLabel = new label($markLabel.':', 'input_mark');
$objInput = new textinput('mark', $mark);
$objInput->size = 3;

$objTable->startRow();
$objTable->addCell($objLabel->show());
$objTable->addCell($objInput->show());
$objTable->endRow();

$objLabel = new label($percentLabel.':', 'input_percentage');
$objDrop = new dropdown('percentage');
for($x=0; $x<=100; $x++){
    $objDrop->addOption($x, $x);
}
$objDrop->setSelected($percent);

$objTable->startRow();
$objTable->addCell($objLabel->show());
$objTable->addCell($objDrop->show().'&nbsp;%');
$objTable->endRow();

$objLabel = new label($closingLabel.':', 'input_date');
$objInput = new textinput('date', $date);
$objInput->size = 15;
$objInput->extra = 'readonly="readonly"';
$objIcon->setIcon('select_date');
$objIcon->title = $selectLabel;

$this->objPopupcal = &$this->getObject('datepickajax', 'popupcalendar');
$dateField = $this->objPopupcal->show('date', 'yes', 'no', $date);
/*$url = "javascript:show_calendar('document.add.date', document.add.date.value);";

$url = $this->uri(array('action'=>'', 'field'=>'document.add.date', 'fieldvalue'=>$date), 'popupcalendar');
$onclick = "javascript:window.open('" .$url."', 'popupcal', 'width=320, height=410, scrollbars=1, resize=yes')";
*/
/*$objLink = new link('#');
$objLink->extra = "onclick=\"$onclick\"";
$objLink->link = $objIcon->show().' '.$selectLabel;
*/
$objTable->startRow();
$objTable->addCell($objLabel->show());
$objTable->addCell($dateField);
$objTable->endRow();

$objLabel = new label($assignmentLabel.':', 'input_description');

$objTable->startRow();
$objTable->addCell($objLabel->show());
$objTable->endRow();

$type = $this->getParam('editor', 'ww');
if($type == 'plaintext'){
    // Hidden element for the editor type
    $objInput = new textinput('editor', 'ww', 'hidden');

    $objText = new textarea('description', $description, 10, 100);
    $objLink = new link("javascript:submitForm('changeeditor')");
    $objLink->link = $lnWysiwyg;

    $objTable->startRow();
    $objTable->addCell($objText->show().'<br />'.$objLink->show().$objInput->show(), '','','','','colspan="2"');
    $objTable->endRow();

}else{
    // Hidden element for the editor type
    $objInput = new textinput('editor', 'plaintext', 'hidden');

    $objEditor = $this->newObject('htmlarea', 'htmlelements');
    $objEditor->init('description', $description, '500px', '500px');
    $objEditor->setDefaultToolBarSetWithoutSave();

    $objLink = new link("javascript:submitForm('changeeditor')");
    $objLink->link = $lnPlain;

    $objTable->startRow();
    $objTable->addCell($objEditor->show().'<br />'.$objLink->show().$objInput->show(), '','','','','colspan="2"');
    $objTable->endRow();
}

// hidden fields
$objInput = new textinput('id', $id, 'hidden');
$hidden = $objInput->show();

$objInput = new textinput('action', 'saveassign', 'hidden');
$hidden .= $objInput->show();

$objTable->startRow();
$objTable->addCell($hidden);
$objTable->endRow();

// submit buttons
$objButton = new button('save', $saveLabel);
$objButton->setToSubmit();
$btn1 = $objButton->show().'&nbsp;&nbsp;&nbsp;';

$objButton = new button('save', $exitLabel);
$objButton->setOnClick('javascript:submitExitForm()');
$btn2 = '&nbsp;&nbsp;&nbsp;'.$objButton->show();

$objTable->startRow();
$objTable->addCell($btn1,'','','right');
$objTable->addCell($btn2);
$objTable->endRow();

if($this->getParam('id')!=NULL)
$objForm = new form('edit',$this->uri(array('action' => 'saveassign','id' => $this->getParam('id'))));
else
$objForm = new form('add',$this->uri(array('action' => 'saveassign')));

$objForm->addRule('name', $errName, 'required');
$objForm->addRule('mark', $errMarkReq, 'required');
$objForm->addRule('mark', $errMark, 'numeric');
$objForm->addToForm($objTable->show());

$objLayer = new layer();
$objLayer->str = $objForm->show();
$objLayer->cssClass = 'odd';

echo $objLayer->show();

// exit form
$objForm = new form('exit',$this->uri(''));
$objInput = new textinput('save', $exitLabel);
$objInput->fldType = 'hidden';
$objForm->addToForm($objInput->show());

echo $objForm->show();
?>
