<?php
$this->loadClass('form', 'htmlelements');
$this->loadClass('dropdown', 'htmlelements');
$this->loadClass('radio', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('textarea', 'htmlelements');
$this->loadClass('label', 'htmlelements');
$this->loadClass('htmlheading', 'htmlelements');


// scripts
    $extbase = '<script language="JavaScript" src="'.$this->getResourceUri('ext-3.0-rc2/adapter/ext/ext-base.js','htmlelements').'" type="text/javascript"></script>';
    $extalljs = '<script language="JavaScript" src="'.$this->getResourceUri('ext-3.0-rc2/ext-all.js','htmlelements').'" type="text/javascript"></script>';
    $extallcss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('ext-3.0-rc2/resources/css/ext-all.css','htmlelements').'"/>';
   // $tip = '<div id="tip1"><img src="'.$this->getResourceUri('ext-3.0-rc2/resources/images/default/window/icon-question.gif','htmlelements').'" ></div>';
    
    $this->appendArrayVar('headerParams', $extbase);
    $this->appendArrayVar('headerParams', $extalljs);
    $this->appendArrayVar('headerParams', $extallcss);
 //end of scripts
$header = new htmlheading($this->objLanguage->languageText('mod_ads_titleA','ads'), 2);


$required = '<span class="warning"> * '.$this->objLanguage->languageText('word_required', 'system', 'Required').'</span>';
$form = new form ('overview', $this->submitAction);
$messages = array();


$table = $this->newObject('htmltable', 'htmlelements');
$table->cellspacing = '20';
$table->startRow();

$coursename = $this->objCourseProposals->getTitle($this->getParam('courseid'));
$this->formValue->setValue('A1', $coursename);
$unitname = new textinput('A1',$this->formValue->getValue('A1'),NULL,75);

//the tips
$tip1 = "<div id = 'tip1'><h3>[?]</h3></div>";
$tip2 = "<div id = 'tip2'><h3>[?]</h3></div>";
$tip3 = "<div id = 'tip3'><h3>[?]</h3></div>";

$myscript = "Ext.onReady(function(){
                        new Ext.ToolTip({
                                title: '<a href=\"#\">Rich Content Tooltip</a>',
                                id: 'content-anchor-tip',
                                target: 'tip1',
                                anchor: 'bottom',
                                html: null,
                                width: 100,
                                autoHide: true,
                                closable: false,
                                //contentEl: 'content-tip', // load content from the page
                                listeners: {
                                    'render': function(){
                                        this.header.on('click', function(e){
                                            e.stopEvent();
                                            Ext.Msg.alert('Link', 'Link to something interesting.');
                                            Ext.getCmp('content-anchor-tip').hide();
                                        }, this, {delegate:'a'});
                                    }
                                }
                        });

                                                 new Ext.ToolTip({
                                title: '<a href=\"#\">Rich Content Tooltip</a>',
                                id: 'content-anchor-tip',
                                target: 'tip2',
                                anchor: 'bottom',
                                html: null,
                                width: 100,
                                autoHide: true,
                                closable: false,
                                //contentEl: 'content-tip', // load content from the page
                                listeners: {
                                    'render': function(){
                                        this.header.on('click', function(e){
                                            e.stopEvent();
                                            Ext.Msg.alert('Link', 'Link to something interesting.');
                                            Ext.getCmp('content-anchor-tip').hide();
                                        }, this, {delegate:'a'});
                                    }
                                }
                        });


                                                 new Ext.ToolTip({
                                title: '<a href=\"#\">Rich Content Tooltip</a>',
                                id: 'content-anchor-tip',
                                target: 'tip3',
                                anchor: 'bottom',
                                html: null,
                                width: 100,
                                autoHide: true,
                                closable: false,
                                //contentEl: 'content-tip', // load content from the page
                                listeners: {
                                    'render': function(){
                                        this.header.on('click', function(e){
                                            e.stopEvent();
                                            Ext.Msg.alert('Link', 'Link to something interesting.');
                                            Ext.getCmp('content-anchor-tip').hide();
                                        }, this, {delegate:'a'});
                                    }
                                }
                        });


                         Ext.QuickTips.init();
                })";
$unitnameLabel = new label("<b>" . $this->objLanguage->languageText('mod_ads_unit_name', 'ads'). "</b>".'&nbsp;', 'input_unitname');

$table->addCell($unitnameLabel->show(), 100, NULL, 'left');

$table->endRow();
$table->startRow();
$table->addCell($unitname->show(). "<br />" . $this->formError->getError('A1'));
$table->addCell($tip1, 100, NULL, 'left');
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
$table->startRow();
$table->addCell("<b>" . $this->objLanguage->languageText('mod_ads_thisisa','ads'). "</b>".'&nbsp;', 150, NULL, 'left');
$table->endRow();
$table->startRow();
$table->addCell($unitType->showTable());
$table->endRow();


$table->startRow();
$motivation = new textarea('A3', NULL, 10, 75);
$motivationLabel = new label("<b>" . $this->objLanguage->languageText('mod_ads_motiv', 'ads'). "</b>".'&nbsp;', 'input_motivation');
$motivation->value = $this->formValue->getValue("A3");

$table->addCell($motivationLabel->show(), 150, NULL, 'left');
$table->endRow();
$table->startRow();
$table->addCell($motivation->show() . "<br />" . $this->formError->getError('A3'));
$table->addCell($tip2, 150, NULL, 'left');
$table->endRow();


$table->startRow();
$qualification = new textarea('A4', NULL, 10, 75);
$qualificationLabel = new label("<b>" . $this->objLanguage->languageText('mod_ads_unit_qual', 'ads'). "</b>".'&nbsp;', 'input_motivation');

    $qualification->value = $this->formValue->getValue("A4");



$table->addCell($qualificationLabel->show(), 150, NULL, 'left');
$table->endRow();
$table->startRow();
$table->addCell($qualification->show() . "<br />" . $this->formError->getError('A4'));
$table->addCell($tip3);
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
$table->startRow();
$table->addCell("<b>" . $this->objLanguage->languageText('mod_ads_proposaltype','ads'). "</b>" .'&nbsp;', 150, NULL, 'left');
$table->endRow();
$table->startRow();
$table->addCell($unitType2->showTable());
$table->endRow();

$form->addToForm($header->show(). "<br />");
$form->addToForm($table->show());

$nextButton = new button ('submitform', 'Next');
$nextButton->setToSubmit();
$saveButton = new button('saveform', 'Save');
$saveButton->setId("saveBtn");

$buttons=$nextButton->show();
$saveMsg = "<span id='saveMsg' style='padding-left: 10px;color:#F00;font-size: 12pt;'></span>";

$form->addToForm("<br>".$buttons);
$form->addToForm("&nbsp;".$saveButton->show());
$form->addToForm($saveMsg);

// Create an instance of the css layout class
$cssLayout = & $this->newObject('csslayout', 'htmlelements');// Set columns to 2
$cssLayout->setNumColumns(2);

$nav = $this->getObject('nav', 'ads');
$toSelect=$this->objLanguage->languageText('mod_ads_section_a_overview', 'ads');
$leftSideColumn = $nav->getLeftContent($toSelect, $this->getParam('action'), $this->getParam('courseid'));
$cssLayout->setLeftColumnContent($leftSideColumn);
//$rightSideColumn='<h1>'.$coursedata['title'].'</h1>';
$rightSideColumn='<div style="padding:10px;">';

//Add the table to the centered layer
$rightSideColumn .= $form->show();
$rightSideColumn.= '</div>';
// Add Right Column
$cssLayout->setMiddleColumnContent($rightSideColumn);

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
//Output the content to the page
echo $cssLayout->show();
?>
