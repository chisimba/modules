<?php
class formmanager extends object {
    function init() {
        $this->loadClass('form','htmlelements');
        $this->loadClass('htmlarea','htmlelements');
        $this->loadClass('textinput','htmlelements');
        $this->loadClass('label','htmlelements');
        $this->loadClass('radio','htmlelements');
        $this->loadClass('button','htmlelements');
        $this->loadClass('htmlheading','htmlelements');
        $this->loadClass('htmltable','htmlelements');
        $this->objLanguage=$this->getObject('language','language');
    }
    public function createAddFreeForm($test) {
        $addFreeform = $this->objLanguage->languageText('mod_mcqtests_addfreeformlabel','mcqtests');
        $editFreeform =$this->objLanguage->languageText('mod_mcqtests_editfreeformlabel','mcqtests');
        $addquestion=$this->objLanguage->languageText('mod_mcqtests_addaquestion','mcqtests');
        $testLabel=$this->objLanguage->languageText('mod_mcqtests_test','mcqtests');
        $totalLabel = $this->objLanguage->languageText('mod_mcqtests_totalmarks','mcqtests');

        $freeForm = $this->objLanguage->languageText('mod_mcqtests_freeform','mcqtests');
        $markLabel = $this->objLanguage->languageText('mod_mcqtests_mark', 'mcqtests');
        $saveLabel = $this->objLanguage->languageText('word_save');
        $exitLabel = $this->objLanguage->languageText('word_cancel');
        $hintLabel = $this->objLanguage->languageText('mod_mcqtests_hint', 'mcqtests');
        $addhintLabel = $this->objLanguage->languageText('mod_mcqtests_hintenable', 'mcqtests');
        $lbEnable = $this->objLanguage->languageText('mod_mcqtests_hintaddenable','mcqtests');
        $lbDisable = $this->objLanguage->languageText('mod_mcqtests_hintadddisable','mcqtests');
        //Reference the heading to the layout template

        // Display information on the test to be set
        $headStr = '<h1>Free form question<h1/><hr>';
        $headStr.= '<b>'.'Test'.':</b>&nbsp;&nbsp;'.$test['name'].'<br />';
        $headStr.= '<b>'.'Total Marks'.':</b>&nbsp;&nbsp;'.$test['totalmark'].'<br />&nbsp;';

        //Build the forms for adding cloze questions

        if (!empty($data)) {
            $question = $data['question'];
            $mark = $data['mark'];
            $hint = $data['hint'];
            $num = $data['questionorder'];
            $questType = $data['questiontype'];
            $questId = $data['id'];
        } else {
            $question = '';
            $mark = 0;
            $hint = '';
            $num = $test['count']+1;
            $questionType = '';
            $questId = '';
        }


        //create a hidden field tostore  question type
        $objInput = new textinput('type',$freeForm);
        $objInput->fldType = 'hidden';
        $headStr.= $objInput->show();


        //Heading for Question and number
        $objHead = new htmlheading();
        $objHead->str = 'Question'.' '.$num.':';
        $objHead->type = 3;
        $headStr.= $objHead->show();


        //Create an instance of the htmlarea class
        $objEditor = $this->newObject('htmlarea','htmlelements');
        //initialise the fckeditor
        $objEditor->init('freeformquestion',$question, '300px', '500px');
        $objEditor->setDefaultToolBarSetWithoutSave();
        //$simplebox = $num ;
        //$textfield = new textinput ('simplebox',$stdanswer,'hidden');

        $headStr.=$objEditor->show().'<br /><br />';

        //create mark textfield
        $objMark=new textinput('mark',$mark);
        $objMark->size=10;
        $headStr.= '<b>'.$markLabel.'</b>:&nbsp;&nbsp;&nbsp;&nbsp;';
        $headStr.= $objMark->show();


        //create hint field
        $headStr.= '<p><b>'.$hintLabel.':</b></p><p>'.$addhintLabel.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>';
        $objRadio = new radio('enablehint');
        $objRadio->setBreakSpace('&nbsp;&nbsp;/&nbsp;&nbsp;');
        $objRadio->addOption('yes', $lbEnable);
        $objRadio->addOption('no', $lbDisable);
        $objRadio->setSelected('no');
        if (!empty($hint)) {
            $objRadio->setSelected('yes');
        }
        $headStr.= '<p>'.$objRadio->show() .'</p>';
        $objInput = new textinput('hint', $hint);
        $objInput->size = 83;
        $headStr.= $objInput->show() .'<p>&nbsp;</p>';



        //Create Submit of the form
        $objButton=new button('save',$saveLabel);
        $objButton->setToSubmit();
        $btn =$objButton->show().'&nbsp;&nbsp;&nbsp;&nbsp;';
        $objButton=new button('save',$exitLabel);
        $objButton->setToSubmit();
        $btn.=$objButton->show();

        //create a hidden field to store test id

        $objTextHid = new textinput('testId', $test['id'], 'hidden');
        $objTableButtons = new htmltable();
        $objTableButtons->startRow();
        $objTableButtons->addCell($objTextHid->show());
        $objTableButtons->addCell($btn, '', '', '', '', 'colspan="2"');
        $objTableButtons->endRow();

        //create a hidden fields to store question order and question id
        $objInput = new textinput('qOrder', $num);
        $objInput->fldType = 'hidden';
        $headStr.= $objInput->show();
        $objInput = new textinput('questionId', $questId);
        $objInput->fldType = 'hidden';
        $headStr.= $objInput->show();



        //Create form
        $objForm = new form('addfreeformquestion', $this->uri(array('action'=>'applyaddquestion','formtype'=>'freeform')));
        $objForm->addToForm($headStr);
        $objForm->addToForm($objTableButtons->show());
        return $objForm->show();
    }

    public function createAddQuestionForm($test) {

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
        $this->loadClass('htmlarea', 'htmlelements');
        $this->objStepMenu = $this->newObject('stepmenu', 'navigation');
        $this->loadClass('dropdown', 'htmlelements');


        // set up language items
        $addHead = $this->objLanguage->languageText('mod_mcqtests_addaquestion', 'mcqtests');
        $editHead = $this->objLanguage->languageText('mod_mcqtests_editquestion', 'mcqtests');
        $testLabel = $this->objLanguage->languageText('mod_mcqtests_test', 'mcqtests');
        $totalLabel = $this->objLanguage->languageText('mod_mcqtests_totalmarks', 'mcqtests');
        $addqestionslabel = $this->objLanguage->languageText('mod_mcqtests_addquestions', 'mcqtests');
        $questionLabel = $this->objLanguage->languageText('mod_mcqtests_question', 'mcqtests');
        $answersLabel = $this->objLanguage->languageText('mod_mcqtests_answers', 'mcqtests');
        $addanswersLabel = $this->objLanguage->languageText('mod_mcqtests_addanswers', 'mcqtests');
        //$actionsLabel = $this->objLanguage->languageText('mod_mcqtests_actions', 'mcqtests');
        //$answerLabel = $this->objLanguage->languageText('mod_mcqtests_answer', 'mcqtests');
        //$commentLabel = $this->objLanguage->languageText('mod_mcqtests_comment', 'mcqtests');
        $hintLabel = $this->objLanguage->languageText('mod_mcqtests_hint', 'mcqtests');
        $addhintLabel = $this->objLanguage->languageText('mod_mcqtests_hintenable', 'mcqtests');
        $markLabel = $this->objLanguage->languageText('mod_mcqtests_mark', 'mcqtests');
        //$correctLabel = $this->objLanguage->languageText('mod_mcqtests_correctans', 'mcqtests');
        //$selectLabel = $this->objLanguage->languageText('mod_mcqtests_selectcorrect', 'mcqtests');
        $saveLabel = $this->objLanguage->languageText('word_save');
        $exitLabel = $this->objLanguage->languageText('word_cancel');
        //$saveaddLabel = $this->objLanguage->languageText('mod_mcqtests_saveaddanotherquestion', 'mcqtests');
        //$noansLabel = $this->objLanguage->languageText('mod_mcqtests_noansinquestion', 'mcqtests');
        $imageLabel = $this->objLanguage->languageText('mod_mcqtests_image', 'mcqtests');
        $addImageLabel = $this->objLanguage->languageText('mod_mcqtests_uploadimage', 'mcqtests');
        $removeImageLabel = $this->objLanguage->languageText('mod_mcqtests_removeimage', 'mcqtests');
        $includeImageLabel = $this->objLanguage->languageText('mod_mcqtests_includeimage', 'mcqtests');
        $lnPlain = $this->objLanguage->languageText('mod_mcqtests_plaintexteditor', 'mcqtests');
        $lnWysiwyg = $this->objLanguage->languageText('mod_mcqtests_wysiwygeditor', 'mcqtests');
        $errQuestion = $this->objLanguage->languageText('mod_mcqtests_questionrequired', 'mcqtests');
        $errMark = $this->objLanguage->languageText('mod_mcqtests_numericmark', 'mcqtests');
        $errMarkReq = $this->objLanguage->languageText('mod_mcqtests_markrequired', 'mcqtests');
        //$errSelect = $this->objLanguage->languageText('mod_mcqtests_selectanswer', 'mcqtests');
        $lbYes = $this->objLanguage->languageText('word_yes');
        $lbNo = $this->objLanguage->languageText('word_no');
        $lbEnable = $this->objLanguage->languageText('word_enable');
        $lbDisable = $this->objLanguage->languageText('word_disable');
        $lbMcq = $this->objLanguage->languageText('mod_mcqtests_multipleoptions', 'mcqtests');
        $lbTF = $this->objLanguage->languageText('mod_mcqtests_truefalse', 'mcqtests');
        $lbType = $this->objLanguage->languageText('mod_mcqtests_typeofquest', 'mcqtests');
        $lbNumOpt = $this->objLanguage->languageText('mod_mcqtests_numoptions', 'mcqtests');

  /*      if ($mode == 'edit') {
            $this->setVarByRef('heading', $editHead);
        } else {
            $this->setVarByRef('heading', $addHead);
        }
*/
        // Display test info
        $topStr = '<b>'.$testLabel.':</b>&nbsp;&nbsp;'.$test['name'].'<br />';
        $topStr.= '<b>'.$totalLabel.':</b>&nbsp;&nbsp;'.$test['totalmark'].'<br />&nbsp;';
        if (!empty($data)) {
            $question = $data['question'];
            $mark = $data['mark'];
            $hint = $data['hint'];
            $num = $data['questionorder'];
            $typeQ = $data['questiontype'];
            $questId = $data['id'];
        } else {
            $question = '';
            $mark = 1;
            $hint = '';
            $num = $test['count']+1;
            $typeQ = '';
            $questId = '';
        }

        $objRadioType = new radio('type');
        $objRadioType->setBreakSpace('&nbsp;&nbsp;/&nbsp;&nbsp;');
        $objRadioType->addOption('mcq', $lbMcq);
        $objRadioType->addOption('tf', $lbTF);

        if(empty($typeQ)) {
            $objRadioType->setSelected('mcq');
        }else {
            $objRadioType->setSelected($typeQ);
        }

        $objDropNum = new dropdown('options');
        $objDropNum->addOption(2, $this->objLanguage->languageText('mod_mcqtests_two', 'mcqtests'));
        $objDropNum->addOption(3, $this->objLanguage->languageText('mod_mcqtests_three', 'mcqtests'));
        $objDropNum->addOption(4, $this->objLanguage->languageText('mod_mcqtests_four', 'mcqtests'));
        $objDropNum->addOption(5, $this->objLanguage->languageText('mod_mcqtests_five', 'mcqtests'));
        $objDropNum->addOption(6, $this->objLanguage->languageText('mod_mcqtests_six', 'mcqtests'));


        if($mode == 'edit') {
            if ($numAnswers == 0) {
                $objDropNum->setSelected(4);
            } else {
                $objDropNum->setSelected($numAnswers);
            }

        }else {
            $objDropNum->setSelected(4);
        }

        $objTableType = new htmltable('tabletype');
        $objTableType->startRow();
        $objTableType->addCell('<b>'.$lbType.'</b>', '20%');
        $objTableType->addCell($objRadioType->show(), '80%');
        $objTableType->endRow();
        $objTableType->startRow();
        $objTableType->addCell('<b>'.$lbNumOpt.'</b>', '20%');
        $objTableType->addCell($objDropNum->show(), '80%');
        $objTableType->endRow();

        $topStr .= $objTableType->show().'<br />';

        // Display question for editing.
        $objHead = new htmlheading();
        $objHead->str = $questionLabel.' '.$num.':';
        $objHead->type = 3;
        $topStr.= $objHead->show();

        $type = $this->getParam('editor', 'ww');
        if($type == 'plaintext') {
        // Hidden element for the editor type
            $objInput = new textinput('editor', 'ww', 'hidden');

            $objText = new textarea('question', $question, 4, 80);
            $topStr .= $objText->show();

            $objLink = new link("javascript:document.getElementById('form_addquestion').action.value = 'changeeditor';document.getElementById('form_addquestion').submit();");
            $objLink->link = $lnWysiwyg;
            $topStr .= '<br />'.$objLink->show().$objInput->show().'<br /><br />';
        }else {
        // Hidden element for the editor type
            $objInput = new textinput('editor', 'plaintext', 'hidden');
            $objAddEditor =$this->getObject('htmlarea','htmlelements');
            $objAddEditor->init('question', $question, '300px', '500px');
            //$objEditor->setDefaultToolBarSetWithoutSave();
            $topStr.= $objAddEditor->show();

            $objLink = new link("javascript:document.getElementById('form_addquestion').action.value = 'changeeditor';document.getElementById('form_addquestion').submit();");
            $objLink->link = $lnPlain;

            // Hide link to plain text
            //$topStr .= '<br />'.$objLink->show().$objInput->show().'<br /><br />';
            $topStr .= '<br />'.$objInput->show().'<br /><br />';
        }

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

        // Save and exit buttons
        $objButton = new button('save', $saveLabel);
        $objButton->setToSubmit();
        $btn = $objButton->show();
        $objButton = new button('save', $exitLabel);
        $objButton->setToSubmit();
        $btn.= '&nbsp;&nbsp;&nbsp;&nbsp;'.$objButton->show();

        $objTextHid = new textinput('testId', $test['id'], 'hidden');

        $objTableButtons = new htmltable();
        $objTableButtons->startRow();
        $objTableButtons->addCell($objTextHid->show());
        $objTableButtons->addCell($btn, '', '', '', '', 'colspan="2"');
        $objTableButtons->endRow();

        $objInput = new textinput('qOrder', $num);
        $objInput->fldType = 'hidden';
        $topStr.= $objInput->show();
        $objInput = new textinput('questionId', $questId);
        $objInput->fldType = 'hidden';
        $topStr.= $objInput->show();

        // Create form and add the table
        $objFormEdit = new form('addquestion', $this->uri(array('action'=>'applyaddquestion')));
        $objFormEdit->addToForm($topStr);
        $objFormEdit->addToForm($objTableButtons->show());
        //$objFormEdit->addRule('question', $errQuestion, 'required');
        $objFormEdit->addRule('mark', $errMark, 'numeric');
        $objFormEdit->addRule('mark', $errMarkReq, 'required');

        return $objFormEdit->show();
    }

    public function createDatabaseQuestions($oldQuestions) {

        $gridjs =
        "<script type='text/javascript' language='javascript'>
            //<![CDATA[
            Ext.onReady(function(){
                showQuestionDB(".$oldQuestions.");
                Ext.get('mcqGrid').hide();
            });

        </script>";

        return $gridjs;
    }
}
?>
