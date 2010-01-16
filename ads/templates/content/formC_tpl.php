<?php

/*impoting classes*/
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('dropdown', 'htmlelements');
$this->loadClass('radio', 'htmlelements');
$this->loadClass('form', 'htmlelements');
$this->loadClass('textarea', 'htmlelements');
$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('label', 'htmlelements');

$extbase = '<script language="JavaScript" src="'.$this->getResourceUri('ext-3.0-rc2/adapter/ext/ext-base.js','htmlelements').'" type="text/javascript"></script>';
$extalljs = '<script language="JavaScript" src="'.$this->getResourceUri('ext-3.0-rc2/ext-all.js','htmlelements').'" type="text/javascript"></script>';
$formCjs = '<script language="JavaScript" src="'.$this->getResourceUri('js/formcjs.js','ads').'" type="text/javascript"></script>';
$extallcss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('ext-3.0-rc2/resources/css/ext-all.css','htmlelements').'"/>';
$buttonscss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('css/buttons.css','ads').'"/>';

$this->appendArrayVar('headerParams', $extbase);
$this->appendArrayVar('headerParams', $extalljs);
$this->appendArrayVar('headerParams', $extallcss);
$this->appendArrayVar('headerParams', $buttonscss);
$this->appendArrayVar('headerParams', $formCjs);

$question1comment = "<div id = 'question1comment'></div>";
$question2acomment = "<div id = 'question2acomment'></div>";
$question2bcomment = "<div id = 'question2bcomment'></div>";
$question3comment = "<div id = 'question3comment'></div>";
$question4acomment = "<div id = 'question4acomment'></div>";
$question4b1comment = "<div id = 'question4b1comment'></div>";
$question4b2comment = "<div id = 'question4b2comment'></div>";

$courseid=$this->getParam('courseid');
$myscript = " Ext.onReady(function(){
loadFormCJS('".$courseid."');

})";
/*declare objects*/
//$CH = new htmlheading($this->objLanguage->languageText('mod_ads_titleC','ads', 2));
$header = new htmlheading($this->objLanguage->languageText('mod_ads_titleC','ads'), 2);

$C1  = new textarea('C1',$this->formValue->getValue("C1"), 10, 75);
$C2a = new radio('C2a');
$C2a->addOption('On-campus',$this->objLanguage->languageText('mod_formC_C2a_R1','ads'));
$C2a->addOption('Off-campus',$this->objLanguage->languageText('mod_formC_C2a_R2','ads'));
$C2a->setTableColumns(1);
if ($this->formValue->getValue("C2a") != "") {
  $C2a->setSelected($this->formValue->getValue("C2a"));
}
else {
  $C2a->setSelected('On-campus');
}
$C2b = new textarea("C2b",$this->formValue->getValue("C2b"), 10, 75);
$C3  = new textarea('C3',$this->formValue->getValue("C3"), 10, 75);
$C4a = new radio('C4a');
$C4a->addOption('yes',$this->objLanguage->languageText('mod_formC_C4a_R1','ads'));
$C4a->addOption('no',$this->objLanguage->languageText('mod_formC_C4a_R2','ads'));
$C4a->setTableColumns(1);
if ($this->formValue->getValue("C4a") != "") {
  $C4a->setSelected($this->formValue->getValue("C4a"));
}
else {
  $C4a->setSelected('yes');
}
$C4b_1 = new textinput('C4b_1',$this->formValue->getValue("C4b_1"),'',25);
$C4b_2 = new textinput('C4b_2','','',25);

$tbl4b = new htmltable();
$tbl4b->width = "55%";
$tbl4b->startRow();
$tbl4b->addCell($this->objLanguage->languageText('mod_formC_C4b_1','ads'));
$tbl4b->addCell($C4b_1->show());
$tbl4b->addCell($question4b1comment);
$tbl4b->endRow();
$tbl4b->startRow();
$tbl4b->addCell($this->objLanguage->languageText('mod_formC_C4b_2','ads'));
$tbl4b->addCell($C4b_2->show());
$tbl4b->addcell($question4b2comment);
$tbl4b->endRow();

$formC = new form('formC',$this->submitAction);
$currentEditor=$this->objDocumentStore->getCurrentEditor($this->getParam('courseid'));
$editable=$currentEditor == $this->objUser->email() ? 'true':'false';
$readonlyWarn=$editable == 'false' ?"<font color=\"red\"><h1>This is a read-only document</h1></font>":"";

$formC->addToForm($header->show(). "<br />".$readonlyWarn);
$formC->addToForm("<b>".$this->objLanguage->languageText('mod_formC_C1','ads')."</b><br>" .$C1->show().$question1comment."<br>".$this->formError->getError("C1"). "<br>");
$formC->addToForm("<b>".$this->objLanguage->languageText('mod_formC_C2a','ads')."</b><br>".$C2a->showTable().$question2acomment."<br>".$this->formError->getError("C2a")."<br>");
$formC->addToForm("<b>".$this->objLanguage->languageText('mod_formC_C2b','ads')."</b><br>".$C2b->show().$question2bcomment."<br>".$this->formError->getError("C2b"));
$formC->addToForm("<b>".$this->objLanguage->languageText('mod_formC_C3','ads')."</b><br>".$C3->show().$question3comment."<br>".$this->formError->getError("C3")."<br>");
$formC->addToForm("<b>".$this->objLanguage->languageText('mod_formC_C4a','ads')."</b><br>".$C4a->showTable().$question4acomment."<br>");
$formC->addToForm($tbl4b->show());

if(strcmp($this->formError->getError("C4b_1"),$this->formError->getError("C4b_2")) == 0 and strlen($this->formError->getError("C4b_1")) > 0)
{
  $formC->addToForm($this->formError->getError("C4b_1"));
}
elseif(strlen($this->formError->getError("C4b_1")) > 0)
{
  $formC->addToForm($this->formError->getError("C4b_1"));
}
elseif(strlen($this->formError->getError("C4b_2")) > 0)
{
  $formC->addToForm($this->formError->getError("C4b_2"));
}

$nextButton = new button ('submitform', 'Next');
$nextButton->setToSubmit();
$saveButton = new button('saveform', 'Save');
$saveButton->setId("saveBtn");
$saveMsg = "<span id='saveMsg' style='padding-left: 10px;color:#F00;font-size: 12pt;'></span>";

$formC->addToForm("<br>".$nextButton->show());
if($this->editable !='false'){
$formC->addToForm("&nbsp;".$saveButton->show());
$formC->addToForm($saveMsg);
}

$header = new htmlheading();
$header->type = 3;
$header->str = 'Subsidy';//;$this->objLanguage->languageText('mod_ads_section_a_overview', 'ads');


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
$rightSideColumn .= $formC->show();
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