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
$formAjs = '<script language="JavaScript" src="'.$this->getResourceUri('js/formajs.js','ads').'" type="text/javascript"></script>';
$extallcss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('ext-3.0-rc2/resources/css/ext-all.css','htmlelements').'"/>';
$buttonscss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('css/buttons.css','ads').'"/>';

$searchfieldjs = '<script language="JavaScript" src="'.$this->getResourceUri('js/SearchField.js').'" type="text/javascript"></script>';
$gridsearchjs = '<script language="JavaScript" src="'.$this->getResourceUri('js/LiveSearch.js').'" type="text/javascript"></script>';
$proposaldetailsjs = '<script language="JavaScript" src="'.$this->getResourceUri('js/proposaldetails.js','ads').'" type="text/javascript"></script>';

$this->appendArrayVar('headerParams', $extbase);
$this->appendArrayVar('headerParams', $extalljs);
$this->appendArrayVar('headerParams', $extallcss);
$this->appendArrayVar('headerParams', $buttonscss);
$this->appendArrayVar('headerParams', $formAjs);

$this->appendArrayVar('headerParams', $searchfieldjs);
$this->appendArrayVar('headerParams', $gridsearchjs);
$this->appendArrayVar('headerParams', $proposaldetailsjs);

$header = new htmlheading($this->objLanguage->languageText('mod_ads_titleA','ads'), 2);


$required = '<span class="warning"> * '.$this->objLanguage->languageText('word_required', 'system', 'Required').'</span>';
$form = new form ('overview', $this->submitAction);
$messages = array();


$table = $this->newObject('htmltable', 'htmlelements');
$table->cellspacing = '20';

$forwardButton = new button('forwardBtn', 'Forward');
$forwardButton->setId("forwardBtn");




$table->startRow();

$coursename = $this->objCourseProposals->getTitle($this->getParam('courseid'));
$this->formValue->setValue('A1', $coursename);
$unitname = new textinput('A1',$this->formValue->getValue('A1'),NULL,75);

$motivationUri=new link($this->uri(array("action"=>"help","formnumber"=>"A","questionnumber"=>"2A")));
$motivationUri->link='[?]';

$question1comment = "<div id = 'question1comment'></div>";
$question2comment = "<div id = 'question2comment'></div>";
$question3comment = "<div id = 'question3comment'></div>";
$question4comment = "<div id = 'question4comment'></div>";
$question5comment = "<div id = 'question5comment'></div>";
$courseid=$this->getParam('courseid');
$sendProposalUrl = $this->uri(array('action'=>'sendproposal'));
$myscript = " Ext.onReady(function(){
loadFormAJS('".$courseid."');
    
})";


$ccount=$this->objQuestionComment->getCommentsCount($courseid, "A","A1");
$commentsAdded=$ccount > 0 ? "<font color=\"red\">&nbsp;[Comments Added!]</font>":"";
$unitnameLabel = new label("<b>" . $this->objLanguage->languageText('mod_ads_unit_name', 'ads'). "</b>".$commentsAdded.".&nbsp;", 'input_unitname');
$table->addCell($unitnameLabel->show(), 600, NULL, 'left');
$table->startRow();
$table->addCell($unitname->show(). "<br />" . $this->formError->getError('A1'));
$table->endRow();
$table->endRow();
$table->startRow();
$table->addCell($question1comment);
$table->endRow();

$unitType = new radio ('A2');
$unitType->addOption('new', $this->objLanguage->languageText('mod_ads_newunit', 'ads'));
$unitType->addOption('edit', $this->objLanguage->languageText('mod_ads_changeunit', 'ads'));
$unitType->setTableColumns(1);
if ($this->formValue->getValue("A2") != "") {
  $unitType->setSelected($this->formValue->getValue("A2"));
}
else {
  $unitType->setSelected('new');
}

$ccount=$this->objQuestionComment->getCommentsCount($courseid, "A","A2");
$commentsAdded=$ccount > 0 ? "<font color=\"red\">&nbsp;[Comments Added!]</font>":"";
$table->startRow();
$table->addCell("<b>" . $this->objLanguage->languageText('mod_ads_thisisa','ads').$commentsAdded. "</b>".'&nbsp;', 600, NULL, 'left');
$table->endRow();
$table->startRow();
$table->addCell($unitType->showTable());
$table->endRow();

$table->startRow();
$table->addCell($question2comment);
$table->endRow();

$table->startRow();
$motivation = new textarea('A3', NULL, 10, 75);
$ccount=$this->objQuestionComment->getCommentsCount($courseid, "A","A3");
$commentsAdded=$ccount > 0 ? "<font color=\"red\">&nbsp;[Comments Added!]</font>":"";
$motivationLabel = new label("<b>" . $this->objLanguage->languageText('mod_ads_motiv', 'ads').$commentsAdded. "</b>".'&nbsp;', 'input_motivation');
$motivation->value = $this->formValue->getValue("A3");

$table->addCell($motivationLabel->show(), 600, NULL, 'left');
$table->endRow();
$table->startRow();
$table->addCell($motivation->show() . "<br />" . $this->formError->getError('A3'));
$table->addCell($motivationtip, 150, NULL, 'left');
$table->endRow();

$table->startRow();
$table->addCell($question3comment);
$table->endRow();

$table->startRow();
$qualification = new textarea('A4', NULL, 10, 75);
$ccount=$this->objQuestionComment->getCommentsCount($courseid, "A","A4");
$commentsAdded=$ccount > 0 ? "<font color=\"red\">&nbsp;[Comments Added!]</font>":"";
$qualificationLabel = new label("<b>" . $this->objLanguage->languageText('mod_ads_unit_qual', 'ads').$commentsAdded. "</b>".'&nbsp;', 'input_motivation');

$qualification->value = $this->formValue->getValue("A4");

$table->addCell($qualificationLabel->show(), 600, NULL, 'left');
$table->endRow();
$table->startRow();
$table->addCell($qualification->show() . "<br />" . $this->formError->getError('A4'));
$table->addCell($tip3);
$table->endRow();
$table->startRow();
$table->addCell($question4comment);
$table->endRow();

$unitType2 = new radio ('A5');
$unitType2->addOption('changetype1', $this->objLanguage->languageText('mod_ads_changetype1', 'ads'));
$unitType2->addOption('changetype2', $this->objLanguage->languageText('mod_ads_changetype2', 'ads'));
$unitType2->addOption('changetype3', $this->objLanguage->languageText('mod_ads_changetype3', 'ads'));
$unitType2->addOption('changetype4', $this->objLanguage->languageText('mod_ads_changetype4', 'ads'));
$unitType2->addOption('changetype5', $this->objLanguage->languageText('mod_ads_changetype5', 'ads'));
$unitType2->setTableColumns(1);

if($this->formValue->getValue("A5")=='')
$unitType2->setSelected('changetype5');
else
$unitType2->setSelected($this->formValue->getValue("A5"));
$ccount=$this->objQuestionComment->getCommentsCount($courseid, "A","A5");
$commentsAdded=$ccount > 0 ? "<font color=\"red\">&nbsp;[Comments Added!]</font>":"";
$table->startRow();
$table->addCell("<b>" . $this->objLanguage->languageText('mod_ads_proposaltype','ads').$commentsAdded. "</b>" .'&nbsp;', 600, NULL, 'left');
$table->endRow();
$table->startRow();
$table->addCell($unitType2->showTable());
$table->endRow();

$table->startRow();
$table->addCell($question5comment);
$table->endRow();

$form->addToForm($header->show(). "<br />");
$form->addToForm($table->show());

$nextButton = new button ('submitform', 'Next Page');
$nextButton->setToSubmit();

$saveButton = new button('saveform', 'Save');
$saveButton->setId("saveBtn");

$forwardButton = new button('forwardForm', 'Forward Proposal');
$forwardButton->setId("forwardBtn");

$buttons=$nextButton->show();
$saveMsg = "<span id='saveMsg' style='padding-left: 10px;color:#F00;font-size: 12pt;'></span>";

$form->addToForm("<br>".$buttons);
$form->addToForm("&nbsp;".$saveButton->show());
$form->addToForm("&nbsp;".$forwardButton->show());
$form->addToForm($saveMsg);

// Create an instance of the css layout class
$cssLayout = & $this->newObject('csslayout', 'htmlelements');// Set columns to 2
$cssLayout->setNumColumns(2);

$nav = $this->getObject('nav', 'ads');
$toSelect=$this->objLanguage->languageText('mod_ads_section_a_overview', 'ads');
$leftSideColumn = $nav->getLeftContent($toSelect, $this->getParam('action'), $this->getParam('courseid'));
$cssLayout->setLeftColumnContent($leftSideColumn);
//$rightSideColumn='<h1>'.$coursedata['title'].'</h1>';
$rightSideColumn='<div id="gtx"></div><div style="padding:10px;">';

//Add the table to the centered layer
$rightSideColumn .= $form->show();
$rightSideColumn.= '</div>';
// Add Right Column
$cssLayout->setMiddleColumnContent($rightSideColumn);

echo "<script type=\"text/javascript\">".$myscript."</script>";
$sendProposalUrl = $this->uri(array('action'=>'sendproposal'));
$searchusers =$this->uri(array('action'=>'searchusers'));
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

               jQuery("#forwardBtn").click(function() {
           showSearchWinX(\''.$courseid.'\',\''.$sendProposalUrl.'\',\'Forward\',\'forwardProposal\',\'search-xwin\',\''.str_replace("amp;", "", $searchusers).'\');                  });

     });';

echo "<div id=\"search-xwin\"><script type='text/javascript'>".$saveFormJS."</script></div>";
//Output the content to the page
echo $cssLayout->show();
?>
