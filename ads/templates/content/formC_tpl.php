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


/*declare objects*/
$CH = new htmlheading($this->objLanguage->languageText('mod_formC_Heading','ads', 2));

$C1  = new textarea('C1',$this->formValue->getValue("C1"),5,25);
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
$C2b = new textarea("C2b",$this->formValue->getValue("C2b"),5,25);
$C3  = new textarea('C3',$this->formValue->getValue("C3"),5,25);
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
$C4b_1 = new textinput('C4b_1',$this->formValue->getValue("C4b_1"),'',15);
$C4b_2 = new textinput('C4b_2','','',15);

$tbl4b = new htmltable();
$tbl4b->width = "55%";
$tbl4b->startRow();
$tbl4b->addCell($this->objLanguage->languageText('mod_formC_C4b_1','ads'));
$tbl4b->addCell($C4b_1->show());
$tbl4b->endRow();
$tbl4b->startRow();
$tbl4b->addCell($this->objLanguage->languageText('mod_formC_C4b_2','ads'));
$tbl4b->addCell($C4b_2->show());
$tbl4b->endRow();

$btnSubmit = new button('submitbutton', 'Next'); //originally submit
$btnSubmit->setToSubmit();

$formC = new form('formC',$this->submitAction);
$formC->addToForm($CH->show().$this->formError->getError("CH")."<br>");
$formC->addToForm("<b>".$this->objLanguage->languageText('mod_formC_C1','ads')."</b><br>" .$C1->show()."<br>".$this->formError->getError("C1"). "<br>");
$formC->addToForm("<b>".$this->objLanguage->languageText('mod_formC_C2a','ads')."</b><br>".$C2a->showTable()."<br>".$this->formError->getError("C2a")."<br>");
$formC->addToForm("<b>".$this->objLanguage->languageText('mod_formC_C2b','ads')."</b><br>".$C2b->show()."<br>".$this->formError->getError("C2b"));
$formC->addToForm("<b>".$this->objLanguage->languageText('mod_formC_C3','ads')."</b><br>".$C3->show()."<br>".$this->formError->getError("C3")."<br>");
$formC->addToForm("<b>".$this->objLanguage->languageText('mod_formC_C4a','ads')."</b><br>".$C4a->showTable()."<br>");
$formC->addToForm($tbl4b->show());
//$formC->addToForm("<b>".$this->objLanguage->languageText('mod_formC_C4b_1','ads')."</b>&nbsp;&nbsp".$C4b_1->show()."<br>");
//$formC->addToForm("<b>".$this->objLanguage->languageText('mod_formC_C4b_2','ads')."</b>&nbsp;&nbsp".$C4b_2->show());

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
$formC->addToForm("<br>".$btnSubmit->show());

$header = new htmlheading();
$header->type = 3;
$header->str = 'Subsidy';//;$this->objLanguage->languageText('mod_ads_section_a_overview', 'ads');


$cssLayout = & $this->newObject('csslayout', 'htmlelements');// Set columns to 2
$cssLayout->setNumColumns(2);

$nav = $this->getObject('nav', 'ads');
$toSelect=$this->objLanguage->languageText('mod_ads_section_a_overview', 'ads');
$leftSideColumn = $nav->getLeftContent($toSelect, $this->getParam('action'), $this->getParam('courseid'));
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
?>
