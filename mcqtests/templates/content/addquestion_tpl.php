<?php
/**
 * Template for adding (or editing) a question to (or in) a test.
 * @package mcqtests
 * @param string $mode Add / Edit
 * @param array $test The details of the current test.
 * @param array $data The details of the question to be edited.
 * @param array $answers The details of the answers in the question.
 */
// set up layout template
$this->setLayoutTemplate('mcqtests_layout_tpl.php');

// set up html elements
$objHead = $this->loadClass('htmlheading', 'htmlelements');
$objTable = $this->loadClass('htmltable', 'htmlelements');
$objTableButtons = $this->loadClass('htmltable', 'htmlelements');
$objForm = $this->loadClass('form', 'htmlelements');
$objRadio = $this->loadClass('radio', 'htmlelements');
$objCheck = $this->loadClass('checkbox', 'htmlelements');
$objInput = $this->loadClass('textinput', 'htmlelements');
$objText = $this->loadClass('textarea', 'htmlelements');
$objButton = $this->loadClass('button', 'htmlelements');
$objLink = $this->loadClass('link', 'htmlelements');
$objIcon = $this->newObject('geticon', 'htmlelements');
$objImage = $this->loadClass('image', 'htmlelements');
$objMsg = $this->newObject('timeoutmessage', 'htmlelements');
$objEditor = $this->newObject('htmlarea', 'htmlelements');
$this->objStepMenu = $this->newObject('stepmenu', 'navigation');

$answers_tab = $this->newObject('tabbedbox', 'htmlelements');
$questions_tab = $this->newObject('tabbedbox', 'htmlelements');

$tabcontent = $this->newObject('tabcontent', 'htmlelements');

// set up language items
$addHead = $this->objLanguage->languageText('mod_mcqtests_addaquestion', 'mcqtests');
$editHead = $this->objLanguage->languageText('mod_mcqtests_editquestion', 'mcqtests');
$testLabel = $this->objLanguage->languageText('mod_mcqtests_test', 'mcqtests');
$totalLabel = $this->objLanguage->languageText('mod_mcqtests_totalmarks', 'mcqtests');
$addqestionslabel = $this->objLanguage->languageText('mod_mcqtests_addquestions', 'mcqtests');
$questionLabel = $this->objLanguage->languageText('mod_mcqtests_question', 'mcqtests');
$answersLabel = $this->objLanguage->languageText('mod_mcqtests_answers', 'mcqtests');
$addanswersLabel = $this->objLanguage->languageText('mod_mcqtests_addanswers', 'mcqtests');
$actionsLabel = $this->objLanguage->languageText('mod_mcqtests_actions', 'mcqtests');
$answerLabel = $this->objLanguage->languageText('mod_mcqtests_answer', 'mcqtests');
$commentLabel = $this->objLanguage->languageText('mod_mcqtests_comment', 'mcqtests');
$hintLabel = $this->objLanguage->languageText('mod_mcqtests_hint', 'mcqtests');
$addhintLabel = $this->objLanguage->languageText('mod_mcqtests_hintenable', 'mcqtests');
$markLabel = $this->objLanguage->languageText('mod_mcqtests_mark', 'mcqtests');
$correctLabel = $this->objLanguage->languageText('mod_mcqtests_correctans', 'mcqtests');
$selectLabel = $this->objLanguage->languageText('mod_mcqtests_selectcorrect', 'mcqtests');
$saveLabel = $this->objLanguage->languageText('word_save');
$exitLabel = $this->objLanguage->languageText('word_cancel');
$saveaddLabel = $this->objLanguage->languageText('mod_mcqtests_saveaddanotherquestion', 'mcqtests');
$noansLabel = $this->objLanguage->languageText('mod_mcqtests_noansinquestion', 'mcqtests');
$imageLabel = $this->objLanguage->languageText('mod_mcqtests_image', 'mcqtests');
$addImageLabel = $this->objLanguage->languageText('mod_mcqtests_uploadimage', 'mcqtests');
$removeImageLabel = $this->objLanguage->languageText('mod_mcqtests_removeimage', 'mcqtests');
$includeImageLabel = $this->objLanguage->languageText('mod_mcqtests_includeimage', 'mcqtests');
$lnPlain = $this->objLanguage->languageText('mod_mcqtests_plaintexteditor', 'mcqtests');
$lnWysiwyg = $this->objLanguage->languageText('mod_mcqtests_wysiwygeditor', 'mcqtests');
$errQuestion = $this->objLanguage->languageText('mod_mcqtests_questionrequired', 'mcqtests');
$errMark = $this->objLanguage->languageText('mod_mcqtests_numericmark', 'mcqtests');
$errMarkReq = $this->objLanguage->languageText('mod_mcqtests_markrequired', 'mcqtests');
$errSelect = $this->objLanguage->languageText('mod_mcqtests_selectanswer', 'mcqtests');
$lbYes = $this->objLanguage->languageText('word_yes');
$lbNo = $this->objLanguage->languageText('word_no');
$lbEnable = $this->objLanguage->languageText('word_enable');
$lbDisable = $this->objLanguage->languageText('word_disable');




if ($mode == 'edit') {
    $this->setVarByRef('heading', $editHead);
} else {
    $this->setVarByRef('heading', $addHead);
}

// Display test info
$topStr = '<b>'.$testLabel.':</b>&nbsp;&nbsp;'.$test['name'].'<br />';
$topStr.= '<b>'.$totalLabel.':</b>&nbsp;&nbsp;'.$test['totalmark'].'<br />&nbsp;';
if (!empty($data)) {
    $question = $data['question'];
    $mark = $data['mark'];
    $hint = $data['hint'];
    $num = $data['questionorder'];
} else {
    $question = '';
    $mark = 0;
    $hint = '';
    $num = $test['count']+1;
}

// Display question for editing.
$objHead = new htmlheading();
$objHead->str = $questionLabel.' '.$num.':';
$objHead->type = 3;
$topStr.= $objHead->show();





/*
$type = $this->getParam('editor', 'ww');
if($type == 'plaintext'){
// Hidden element for the editor type
$objInput = new textinput('editor', 'ww', 'hidden');

$objText = new textarea('question', $question, 4, 80);
$topStr .= $objText->show();

$objLink = new link("javascript:document.getElementById('form_addquestion').action.value = 'changeeditor';document.getElementById('form_addquestion').submit();");
$objLink->link = $lnWysiwyg;
$topStr .= '<br />'.$objLink->show().$objInput->show().'<br /><br />';
}else{

// Hidden element for the editor type
$objInput = new textinput('editor', 'plaintext', 'hidden');
*/
$objEditor->init('question', $question, '300px', '500px');
$objEditor->setDefaultToolBarSetWithoutSave();
$topStr.= $objEditor->show();
/*
$objLink = new link("javascript:document.getElementById('form_addquestion').action.value = 'changeeditor';document.getElementById('form_addquestion').submit();");
$objLink->link = $lnPlain;
$topStr .= '<br />'.$objLink->show().$objInput->show().'<br /><br />';
}
*/
$objInput = new textinput('mark', $mark);
$objInput->size = 10;
$topStr.= '<p><b>'.$markLabel.':</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
$topStr.= $objInput->show() .'</p>';
$topStr.= '<p><b>'.$hintLabel.':</b></p><p>'.$addhintLabel.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>';
$objRadio = new radio('enablehint');
$objRadio->setBreakSpace('&nbsp;&nbsp;/&nbsp;&nbsp;');
$objRadio->addOption('yes', $lbEnable);
$objRadio->addOption('no', $lbDisable);
$objRadio->setSelected('no');
if (!empty($hint)) {
    $objRadio->setSelected('yes');
}
$topStr.= '<p>'.$objRadio->show() .'</p>';
$objInput = new textinput('hint', $hint);
$objInput->size = 83;
$topStr.= $objInput->show() .'<p>&nbsp;</p>';

/*
// Image Section - upload image for question / remove image
$topStr .= '<p><b>'.$addImageLabel.':</b></p>';

$objRadio = new radio('imageconfirm');
$objRadio->setBreakSpace('&nbsp;&nbsp;/&nbsp;&nbsp;');
$objRadio->addOption('yes', $lbYes);
$objRadio->addOption('no', $lbNo);

$imageStr = '';
if(empty($data['imageName'])){
$objRadio->setSelected('no');

$objInput->textinput('imagefile');
$objInput->fldType = 'file';
$objInput->size = 57;

$imageBtn = '<p>'.$objInput->show().'</p>';

$objButton = new button('save', $includeImageLabel);
$objButton->setOnClick("javascript:document.getElementById('form_addquestion').action.value = 'addimage';document.getElementById('form_addquestion').submit();");
$imageBtn .= $objButton->show();
}

if(!empty($data['imageName'])){
$objRadio->setSelected('yes');

$objButton = new button('removeimage', $removeImageLabel);
$objButton->setOnClick("javascript:document.getElementById('form_addquestion').action.value = 'removeimage';document.getElementById('form_addquestion').submit();");
$imageBtn = $objButton->show();

$objImage = new image();
$objImage->src = $this->uri(array('action'=>'viewimage', 'fileid'=>$data['imageId']),'test');

$imageStr .= '<p><b>'.$imageLabel.':</b> '.$data['imageName'].'</p>';

$imageStr .= '<p>'.$objImage->show().'</p>';

$objInput = new textinput('fileId',$data['imageId']);
$objInput->fldType = 'hidden';
$objInput->size = 5;
$imageStr .= $objInput->show();
}

$topStr .= '<p>'.$objRadio->show().'</p>';
$topStr .= '<p>'.$imageBtn.'</p>';
$topStr .= $imageStr.'<br />';
*/




// Create form and add the table
$objFormEdit = new form('addquestion', $this->uri(''));
$objFormEdit->addToForm($topStr);
// $objForm->addRule('question', $errQuestion, 'required');
$objFormEdit->addRule('mark', $errMark, 'numeric');
$objFormEdit->addRule('mark', $errMarkReq, 'required');

$topStr = null;


//-----------------------------------------------------------------------SpLIT------------------------------------------------------ 

// Answers Section
$addIcon = $objIcon->getAddIcon("javascript:document.getElementById('form_addquestion').action.value = 'addanswer';document.getElementById('form_addquestion').submit();");
$ansCount = 0;
if (!empty($answers)) {
    $ansCount = count($answers);
}
$objHead = new htmlheading();
$objHead->str = $answersLabel.' ('.$ansCount.'):&nbsp;&nbsp;&nbsp;'.$addIcon;
$objHead->type = 3;
$topStr.= $objHead->show();
// Confirmation message on saving answers
$confirm = $this->getParam('confirm');
if ($confirm == 'yes') {
    $msg = $this->getSession('confirm');
    $this->unsetSession('confirm');
    $objMsg->setMessage($msg.'&nbsp;&nbsp;'.date('d/m/Y H:i'));
    $topStr.= '<p>'.$objMsg->show() .'</p>';
}
$topStr.= '<b>'.$selectLabel.'</b><br />';
// Set up table to display answers for editing and deleting

$objTable = new htmltable();
$objTable->cellpadding = 5;
$objTable->cellspacing = 2;
$objTable->width = '99%';
$tableHd = array();
$tableHd[] = '';
$tableHd[] = $correctLabel;
$tableHd[] = $answerLabel;
$tableHd[] = $actionsLabel;
$objTable->addHeader($tableHd, 'heading');
$objTable->startRow();
$objTable->addCell('', '2%');
$objTable->addCell('', '5%');
$objTable->addCell('');
$objTable->addCell('', '8%');
$objTable->endRow();
if (!empty($answers)) {
    $i = 0;
    $noCorrect = TRUE;
    foreach($answers as $line) {
        $class = (($i++%2) == 0) ? 'odd' : 'even';
        // edit & delete
        $editUrl = $this->uri(array(
            'action' => 'editanswer',
            'answerId' => $line['id'],
            'questionId' => $data['id']
        ));
        $icons = $objIcon->getEditIcon($editUrl);
        $icons.= $objIcon->getDeleteIconWithConfirm($line['id'], array(
            'action' => 'deleteanswer',
            'answerId' => $line['id'],
            'questionId' => $data['id']
        ) , 'mcqtests');
        $objLink = new link($editUrl);
        $objLink->link = $line['answer'];
        $answerLink = $objLink->show();
        // radio buttons for the correct answer
        $objRadio = new radio('correctans');
        $objRadio->addOption($line['id'], '');
        $hidden = '';
        // Set the correct answer and post its id as a hidden element
        if ($line['correct']) {
            $noCorrect = FALSE;
            $objRadio->setSelected($line['id']);
            $objInput->textinput('correctId', $line['id']);
            $objInput->fldType = 'hidden';
            $hidden = $objInput->show();
        }
        $tableRow = array();
        $tableRow[] = $i;
        $tableRow[] = $objRadio->show() .$hidden;
        $tableRow[] = $answerLink;
        $tableRow[] = $icons;
        $objTable->addRow($tableRow, $class);
    }
    if ($noCorrect) {
        $objInput = new textinput('firstans', $answers[0]['id']);
        $objInput->fldType = 'hidden';
        $hiddenAns = $objInput->show();
        $objTable->addRow(array(
            $hiddenAns
        ));
    }
} else {
    $objTable->startRow();
    $objTable->addCell('', '', '', '', 'odd');
    $objTable->addCell($noansLabel, '', '', '', 'odd', 'colspan="3"');
    $objTable->endRow();
}
// hidden elements
$objInput = new textinput('id', $test['id']);
$objInput->fldType = 'hidden';
$hidden = $objInput->show();
$objInput = new textinput('action', 'applyaddquestion');
$objInput->fldType = 'hidden';
$hidden.= $objInput->show();
$objInput = new textinput('qOrder', $num);
$objInput->fldType = 'hidden';
$hidden.= $objInput->show();
if ($mode == 'edit') {
    $objInput = new textinput('questionId', $data['id']);
    $objInput->fldType = 'hidden';
    $hidden.= $objInput->show();
    $objInput = new textinput('count', $ansCount);
    $objInput->fldType = 'hidden';
    $hidden.= $objInput->show();
    $objInput = new textinput('total', $mark);
    $objInput->fldType = 'hidden';
    $hidden.= $objInput->show();
}
// Save and exit buttons
$objButton = new button('save', $saveLabel);
$objButton->setToSubmit();
$btn = $objButton->show();
$objButton = new button('save', $exitLabel);
$objButton->setToSubmit();
$btn.= '&nbsp;&nbsp;&nbsp;&nbsp;'.$objButton->show();
$objTable->startRow();
$objTable->addCell($hidden);
$objTable->addCell($btn, '', '', '', '', 'colspan="2"');
$objTable->endRow();

// Create form and add the table
$objForm = new form('addquestion', $this->uri(''));
//$objForm->extra=" enctype='multipart/form-data'";
$objForm->addToForm($topStr);
$objForm->addToForm($objTable->show());
// $objForm->addRule('question', $errQuestion, 'required');
$objForm->addRule('mark', $errMark, 'numeric');
$objForm->addRule('mark', $errMarkReq, 'required');

$objTableButtons = new htmltable();
$objTableButtons->cellpadding = 5;
$objTableButtons->cellspacing = 2;
$objTableButtons->width = '99%';
$objTableButtons->startRow();
$objTableButtons->addCell($hidden);
$objTableButtons->addCell($btn, '', '', '', '', 'colspan="2"');
$objTableButtons->endRow();
	
$objFormEdit->addToForm($objTableButtons->show());
// $objForm->addRule('question', $errQuestion, 'required');
$objFormEdit->addRule('mark', $errMark, 'numeric');
$objFormEdit->addRule('mark', $errMarkReq, 'required');

//==========Adding the new boxes here==================/

	$answers_tab->addTabLabel("Add");
	$answers_tab->addBoxContent($objForm->show());



	$tabcontent->addTab($addanswersLabel,$answers_tab->show());

	$questions_tab->addBoxContent($objFormEdit->show());
	$questions_tab->addTabLabel("Edit");
	$tabcontent->addTab($addqestionslabel,$questions_tab->show());
	$tabcontent->width = '90%';
	echo  $tabcontent->show();





?>
