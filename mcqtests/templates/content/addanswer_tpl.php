<?
/**
* @package mcqtests
*/

/**
* Template for adding / editing an answer to / in a question in a test.
* @param array $data The question being answered.
* @param array $answer The answer being edited.
* @param string $mode Add / Edit.
*/
$this->setLayoutTemplate('mcqtests_layout_tpl.php');

// set up html elements
$objTable =& $this->newObject('htmltable','htmlelements');
$objForm =& $this->newObject('form','htmlelements');
$objInput =& $this->newObject('textinput','htmlelements');
$objText =& $this->newObject('textarea','htmlelements');
$objButton =& $this->newObject('button','htmlelements');

// set up language items
$heading = $this->objLanguage->languageText('mod_mcqtests_addanswers', 'mcqtests');
$editHeading = $this->objLanguage->languageText('mod_mcqtests_editanswer', 'mcqtests');
$questionLabel = $this->objLanguage->languageText('mod_mcqtests_question', 'mcqtests');
$answerLabel = $this->objLanguage->languageText('mod_mcqtests_answer', 'mcqtests');
$commentLabel = $this->objLanguage->languageText('mod_mcqtests_comment', 'mcqtests');
$correctLabel = $this->objLanguage->languageText('mod_mcqtests_selectcorrect', 'mcqtests');
$saveLabel = $this->objLanguage->languageText('word_save');
$exitLabel = $this->objLanguage->languageText('word_cancel');

if($mode == 'edit'){
    $this->setVarByRef('heading', $editHeading);
}else{
    $this->setVarByRef('heading', $heading);
}

if($mode == 'edit' && !empty($answer)){
    $dAnswer = $answer['answer'];
    $dComment = $answer['commenttext'];
    $num = $answer['answerorder'];
}else{
    $dAnswer = '';
    $dComment = '';
    $num = $data['count']+1;
}
$aOrder = $num;

// Display test info
$str = '<br /><font size="4"><b>'.$questionLabel.':</b>&nbsp;&nbsp;'.$data['question']
    .'</font>';

$objTable->cellpadding = 5;
$objTable->width = '99%';

$objTable->startRow();
$objTable->addCell('<b>'.$answerLabel.' '.$num++.':</b>','','','','','colspan="3"');
$objTable->endRow();

$objText->textarea('answer1', $dAnswer, 2, 80);

$objTable->startRow();
$objTable->addCell($objText->show(),'','','','','colspan="2"');
$objTable->endRow();

$objInput->textinput('comment1', $dComment);
$objInput->size = 70;

$objTable->startRow();
$objTable->addCell('<b>'.$commentLabel.':</b>','7%','center','','');
$objTable->addCell($objInput->show(),'','','','');
$objTable->endRow();

$objTable->row_attributes = 'height="15"';
$objTable->startRow();
$objTable->addCell('','','','','','colspan="3"');
$objTable->endRow();

if($mode == 'add'){
    $objTable->startRow();
    $objTable->addCell('<b>'.$answerLabel.' '.$num++.':</b>','','','','','colspan="3"');
    $objTable->endRow();

    $objText->textarea('answer2','',2,80);

    $objTable->startRow();
    $objTable->addCell($objText->show(),'','','','','colspan="2"');
    $objTable->endRow();

    $objInput->textinput('comment2');
    $objInput->size = 70;

    $objTable->startRow();
    $objTable->addCell('<b>'.$commentLabel.':</b>','','center','','');
    $objTable->addCell($objInput->show(),'','','','');
    $objTable->endRow();

    $objTable->row_attributes = 'height="15"';
    $objTable->startRow();
    $objTable->addCell('','','','','','colspan="2"');
    $objTable->endRow();

    $objTable->startRow();
    $objTable->addCell('<b>'.$answerLabel.' '.$num++.':</b>','','','','','colspan="3"');
    $objTable->endRow();

    $objText->textarea('answer3','',2,80);

    $objTable->startRow();
    $objTable->addCell($objText->show(),'','','','','colspan="2"');
    $objTable->endRow();

    $objInput->textinput('comment3');
    $objInput->size = 70;

    $objTable->startRow();
    $objTable->addCell('<b>'.$commentLabel.':</b>','','center','','');
    $objTable->addCell($objInput->show(),'','','','');
    $objTable->endRow();

    $objTable->row_attributes = 'height="15"';
    $objTable->startRow();
    $objTable->addCell('','','','','','colspan="2"');
    $objTable->endRow();

    $objTable->startRow();
    $objTable->addCell('<b>'.$answerLabel.' '.$num++.':</b>','','','','','colspan="3"');
    $objTable->endRow();

    $objText->textarea('answer4','',2,80);

    $objTable->startRow();
    $objTable->addCell($objText->show(),'','','','','colspan="2"');
    $objTable->endRow();

    $objInput->textinput('comment4');
    $objInput->size = 70;

    $objTable->startRow();
    $objTable->addCell('<b>'.$commentLabel.':</b>','','center','','');
    $objTable->addCell($objInput->show(),'','','','');
    $objTable->endRow();

    $objTable->row_attributes = 'height="15"';
    $objTable->startRow();
    $objTable->addCell('','','','','','colspan="2"');
    $objTable->endRow();
}

// hidden elements
$objInput->textinput('testId', $data['testid']);
$objInput->fldType='hidden';
$hidden = $objInput->show();

$objInput->textinput('questionId', $data['id']);
$objInput->fldType='hidden';
$hidden .= $objInput->show();

$objInput->textinput('answerorder', $aOrder);
$objInput->fldType='hidden';
$hidden .= $objInput->show();

if($mode == 'edit'){
    $objInput->textinput('answerId', $answer['id']);
    $objInput->fldType='hidden';
    $hidden .= $objInput->show();
}
// Save and exit buttons
$objButton->button('save',$saveLabel);
$objButton->setToSubmit();
$btn = $objButton->show();
$objButton->button('save',$exitLabel);
$objButton->setToSubmit();
$btn .= '&nbsp;&nbsp;&nbsp;&nbsp;'.$objButton->show();

$objTable->startRow();
$objTable->addCell($hidden);
$objTable->addCell($btn,'','','','','colspan="2"');
$objTable->endRow();

// Create form and add the table
$objForm->form('addanswer', $this->uri(array('action'=>'applyaddanswer')));
$objForm->addToForm($objTable->show());
$str .= $objForm->show();

echo $str;
?>