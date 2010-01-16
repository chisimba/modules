<?php
$this->loadClass('form', 'htmlelements');
$this->loadClass('dropdown', 'htmlelements');
$this->loadClass('radio', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('textarea', 'htmlelements');
$this->loadClass('label', 'htmlelements');
$this->loadClass('htmlheading', 'htmlelements');

$extbase = '<script language="JavaScript" src="'.$this->getResourceUri('ext-3.0-rc2/adapter/ext/ext-base.js','htmlelements').'" type="text/javascript"></script>';
$extalljs = '<script language="JavaScript" src="'.$this->getResourceUri('ext-3.0-rc2/ext-all.js','htmlelements').'" type="text/javascript"></script>';
$formBjs = '<script language="JavaScript" src="'.$this->getResourceUri('js/formbjs.js','ads').'" type="text/javascript"></script>';
$extallcss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('ext-3.0-rc2/resources/css/ext-all.css','htmlelements').'"/>';
$buttonscss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('css/buttons.css','ads').'"/>';

$this->appendArrayVar('headerParams', $extbase);
$this->appendArrayVar('headerParams', $extalljs);
$this->appendArrayVar('headerParams', $extallcss);
$this->appendArrayVar('headerParams', $buttonscss);
$this->appendArrayVar('headerParams', $formBjs);

$header = new htmlheading($this->objLanguage->languageText('mod_ads_titleB','ads'), 2);

$required = '<span class="warning"> * '.$this->objLanguage->languageText('word_required', 'system', 'Required').'</span>';

$form = new form ('rules', $this->submitAction);

$question1comment = "<div id = 'question1comment'></div>";
$question2comment = "<div id = 'question2comment'></div>";
$question3acomment = "<div id = 'question3acomment'></div>";
$question3bcomment = "<div id = 'question3bcomment'></div>";
$question4acomment = "<div id = 'question4acomment'></div>";
$question4bcomment = "<div id = 'question4bcomment'></div>";
$question4ccomment = "<div id = 'question4ccomment'></div>";
$question5acomment = "<div id = 'question5acomment'></div>";
$question5bcomment = "<div id = 'question5bcomment'></div>";
$question6acomment = "<div id = 'question6acomment'></div>";
$question6bcomment = "<div id = 'question6bcomment'></div>";
$courseid=$this->getParam('courseid');
$myscript = " Ext.onReady(function(){
loadFormBJS('".$courseid."');

})";

$table = $this->newObject('htmltable', 'htmlelements');
$table->cellspacing = "10";
$table->startRow();

$changetype = new textarea('B1', NULL, 10, 75);
$changetypeLabel = new label("<b>".$this->objLanguage->languageText('mod_ads_b1', 'ads')."</b>".'&nbsp;', 'change_type');
$changetype->value = $this->formValue->getValue("B1");

$table->addCell($changetypeLabel->show(), 600, NULL, 'left');
$table->endRow();
$table->startRow();
$table->addCell($changetype->show() . "<br />" . $this->formError->getError('B1'));
$table->endRow();
$table->startRow();
$table->addCell($question1comment);
$table->endRow();


$coursedesc = new textarea('B2', NULL, 10, 75);
$coursedescLabel = new label("<b>".$this->objLanguage->languageText('mod_ads_b2', 'ads')."</b>".'&nbsp;', 'course_desc');
$coursedesc->value = $this->formValue->getValue("B2");


$table->addCell($coursedescLabel->show(), 600, NULL, 'left');
$table->endRow();
$table->startRow();
$table->addCell($coursedesc->show() . "<br />" . $this->formError->getError('B2'));
$table->endRow();
$table->startRow();
$table->addCell($question2comment);
$table->endRow();


$prereq = new textarea('B3a', NULL, 10, 75);
$prereqLabel = new label("<b>".$this->objLanguage->languageText('mod_ads_b3a', 'ads')."</b>".'&nbsp;', 'pre_req');
$prereq->value = $this->formValue->getValue("B3a");

$table->addCell($prereqLabel->show(), 600, NULL, 'left');
$table->endRow();
$table->startRow();
$table->addCell($prereq->show() . "<br />" . $this->formError->getError('B3a'));
$table->endRow();
$table->startRow();
$table->addCell($question3acomment);
$table->endRow();


$coreq = new textarea('B3b', NULL, 10, 75);
$coreqLabel = new label("<b>".$this->objLanguage->languageText('mod_ads_b3b', 'ads')."</b>".'&nbsp;', 'co_req');
$coreq->value = $this->formValue->getValue("B3b");

$table->addCell($coreqLabel->show(), 600, NULL, 'left');
$table->endRow();
$table->startRow();
$table->addCell($coreq->show() . "<br />" . $this->formError->getError('B3b'));
$table->endRow();
$table->startRow();
$table->addCell($question3bcomment);
$table->endRow();


$b4a = new radio ('B4a');
$b4a->addOption('b4a1', $this->objLanguage->languageText('mod_ads_b4a1', 'ads'));
$b4a->addOption('b4a2', $this->objLanguage->languageText('mod_ads_b4a2', 'ads'));
$b4a->addOption('b4a3', $this->objLanguage->languageText('mod_ads_b4a3', 'ads'));
$b4a->setTableColumns(1);
if($this->formValue->getValue("B4a")=='')
$b4a->setSelected('b4a1');
else
$b4a->setSelected($this->formValue->getValue("B4a"));

$table->startRow();
$table->addCell("<b>".$this->objLanguage->languageText('mod_ads_b4a','ads')."</b>".'&nbsp;', 600, NULL, 'left');
$table->endRow();
$table->startRow();
$table->addCell($b4a->showTable());
$table->endRow();
$table->startRow();
$table->addCell($question4acomment);
$table->endRow();


$b4b = new textarea('B4b', NULL, 10, 75);
$b4bLabel = new label("<b>".$this->objLanguage->languageText('mod_ads_b4b', 'ads')."</b>".'&nbsp;', 'b4_b');
$b4b->value = $this->formValue->getValue("B4b");

$table->addCell($b4bLabel->show(), 600, NULL, 'left');
$table->endRow();
$table->startRow();
$table->addCell($b4b->show() . "<br />" . $this->formError->getError('B4b'));
$table->endRow();
$table->startRow();
$table->addCell($question4bcomment);
$table->endRow();



$b4c = new textarea('B4c', NULL, 10, 75);
$b4cLabel = new label("<b>".$this->objLanguage->languageText('mod_ads_b4c', 'ads')."</b>".'&nbsp;', 'b4_c');
$b4c->value = $this->formValue->getValue("B4c");

$table->addCell($b4cLabel->show(), 600, NULL, 'left');
$table->endRow();
$table->startRow();
$table->addCell($b4c->show() . "<br />" . $this->formError->getError('B4c'));
$table->endRow();
$table->startRow();
$table->addCell($question4ccomment);
$table->endRow();

$b5a = new radio ('B5a');
$b5a->addOption('b5_a1', $this->objLanguage->languageText('mod_ads_b5a1', 'ads'));
$b5a->addOption('b5_a2', $this->objLanguage->languageText('mod_ads_b5a2', 'ads'));
$b5a->addOption('b5_a3', $this->objLanguage->languageText('mod_ads_b5a3', 'ads'));
$b5a->addOption('b5_a4', $this->objLanguage->languageText('mod_ads_b5a4', 'ads'));
$b5a->addOption('b5_a5', $this->objLanguage->languageText('mod_ads_b5a5', 'ads'));
$b5a->addOption('b5_a6', $this->objLanguage->languageText('mod_ads_b5a6', 'ads'));
$b5a->addOption('b5_a7', $this->objLanguage->languageText('mod_ads_b5a7', 'ads'));
$b5a->addOption('b5_a8', $this->objLanguage->languageText('mod_ads_b5a8', 'ads'));
$b5a->addOption('b5_a9', $this->objLanguage->languageText('mod_ads_b5a9', 'ads'));
$b5a->setTableColumns(1);
if($this->formValue->getValue("B5a")=='') {
  $b5a->setSelected('b5_a1');
}
else {
  $b5a->setSelected($this->formValue->getValue("B5a"));
}

$table->startRow();
$table->addCell("<b>".$this->objLanguage->languageText('mod_ads_b5a','ads')."</b>".'&nbsp;', 600, NULL, 'left');
$table->endRow();
$table->startRow();
$table->addCell($b5a->showTable());
$table->endRow();
$table->startRow();
$table->addCell($question5acomment);
$table->endRow();


$b5b = new textarea('B5b', NULL, 10, 75);
$b5bLabel = new label("<b>".$this->objLanguage->languageText('mod_ads_b5b', 'ads')."</b>".'&nbsp;', 'b5_b');
$b5b->value = $this->formValue->getValue("B5b");


$table->addCell($b5bLabel->show(), 600, NULL, 'left');
$table->endRow();
$table->startRow();
$table->addCell($b5b->show() . "<br />" . $this->formError->getError('B5b'));
$table->endRow();
$table->startRow();
$table->addCell($question5bcomment);
$table->endRow();



$b6a = new radio ('B6a');
$b6a->addOption('b6_a1', $this->objLanguage->languageText('mod_ads_b6a1', 'ads'));
$b6a->addOption('b6_a2', $this->objLanguage->languageText('mod_ads_b6a2', 'ads'));
$b6a->addOption('b6_a3', $this->objLanguage->languageText('mod_ads_b6a3', 'ads'));
$b6a->addOption('b6_a4', $this->objLanguage->languageText('mod_ads_b6a4', 'ads'));
$b6a->addOption('b6_a5', $this->objLanguage->languageText('mod_ads_b6a5', 'ads'));
$b6a->setTableColumns(1);

if ($this->formValue->getValue("B6a") == "") {
    $b6a->setSelected('b6_a1');
} 
else {
    $b6a->setSelected($this->formValue->getValue("B6a"));
}
$table->startRow();
$table->addCell("<b>".$this->objLanguage->languageText('mod_ads_b6a','ads')."</b>".'&nbsp;', 600, NULL, 'left');
$table->endRow();
$table->startRow();
$table->addCell($b6a->showTable());
$table->endRow();
$table->startRow();
$table->addCell($question6acomment);
$table->endRow();


$b6b = new textarea('B6b', NULL, 10, 75);
$b6bLabel = new label("<b>".$this->objLanguage->languageText('mod_ads_b6b', 'ads')."</b>".'&nbsp;', 'b6_b');

    $b6b->value = $this->formValue->getValue("B6b");



$table->addCell($b6bLabel->show(), 600, NULL, 'left');
$table->endRow();
$table->startRow();
$table->addCell($b6b->show() . "<br />" . $this->formError->getError('B6b'));
$table->endRow();
$table->startRow();
$table->addCell($question6bcomment);
$table->endRow();

$currentEditor=$this->objDocumentStore->getCurrentEditor($this->getParam('courseid'));
$editable=$currentEditor == $this->objUser->email() ? 'true':'false';
$readonlyWarn=$editable == 'false' ?"<font color=\"red\"><h1>This is a read-only document</h1></font>":"";

$form->addToForm($header->show(). "<br />".$readonlyWarn);
$form->addToForm($table->show());

$nextButton = new button ('submitform', 'Next');
$nextButton->setToSubmit();
$saveButton = new button('saveform', 'Save');
$saveButton->setId("saveBtn");
$saveMsg = "<span id='saveMsg' style='padding-left: 10px;color:#F00;font-size: 12pt;'></span>";

$form->addToForm("<br>".$nextButton->show());
if($this->editable !='false'){
$form->addToForm("&nbsp;".$saveButton->show());
$form->addToForm($saveMsg);
}

// Create an instance of the css layout class
$cssLayout = & $this->newObject('csslayout', 'htmlelements');// Set columns to 2
$cssLayout->setNumColumns(2);

$nav = $this->getObject('nav', 'ads');
$toSelect=$this->objLanguage->languageText('mod_ads_section_a_overview', 'ads');
$currentEditor=$this->objDocumentStore->getCurrentEditor($this->getParam('courseid'));
$editable=$currentEditor == $this->objUser->email() ? 'true':'false';
$leftSideColumn = $nav->getLeftContent($toSelect, $this->getParam('action'), $this->getParam('courseid'),$editable);
$cssLayout->setLeftColumnContent($leftSideColumn);
//$rightSideColumn='<h1>'.$coursedata['title'].'</h1>';
$rightSideColumn='<div style="padding:10px;">';

//Add the table to the centered layer
$rightSideColumn .= $form->show();
$rightSideColumn.= '</div>';
// Add Right Column
$cssLayout->setMiddleColumnContent($rightSideColumn);

//Output the content to the page
echo $cssLayout->show();

echo "<script type=\"text/javascript\">".$myscript."</script>";

$saveUrl = $this->submitAction;
$saveFormJS = 'jQuery(document).ready(function() {
                    jQuery("#saveMsg").hide();

                    jQuery("#saveBtn").click(function() {
                           data = jQuery("form").serialize();
                           url = "'.str_replace("amp;", "", $saveUrl).'";

                           jQuery.ajax({
                                type: "POST",
                                url: url,
                                data: data,
                                success: function(msg) {
                                    jQuery("#saveMsg").show();
                                    jQuery("#saveMsg").text("Data saved successfully");
                                    jQuery("#saveMsg").fadeOut(5000);
                                }
                           });
                    });
              });';

echo "<script type='text/javascript'>".$saveFormJS."</script>";
?>