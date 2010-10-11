<?php

class formmanager extends object {

    function init() {
        $this->loadClass('form', 'htmlelements');
        $this->loadClass('htmlarea', 'htmlelements');
        $this->loadClass('textinput', 'htmlelements');
        $this->loadClass('label', 'htmlelements');
        $this->loadClass('radio', 'htmlelements');
        $this->loadClass('button', 'htmlelements');
        $this->loadClass('htmlheading', 'htmlelements');
        $this->loadClass('htmltable', 'htmlelements');
        $this->objLanguage = $this->getObject('language', 'language');
    }

    public function createAddFreeForm($test) {
        $addFreeform = $this->objLanguage->languageText('mod_mcqtests_addfreeformlabel', 'mcqtests');
        $editFreeform = $this->objLanguage->languageText('mod_mcqtests_editfreeformlabel', 'mcqtests');
        $addquestion = $this->objLanguage->languageText('mod_mcqtests_addaquestion', 'mcqtests');
        $testLabel = $this->objLanguage->languageText('mod_mcqtests_test', 'mcqtests');
        $totalLabel = $this->objLanguage->languageText('mod_mcqtests_totalmarks', 'mcqtests');

        $freeForm = $this->objLanguage->languageText('mod_mcqtests_freeform', 'mcqtests');
        $markLabel = $this->objLanguage->languageText('mod_mcqtests_mark', 'mcqtests');
        $saveLabel = $this->objLanguage->languageText('word_save');
        $exitLabel = $this->objLanguage->languageText('word_cancel');
        $hintLabel = $this->objLanguage->languageText('mod_mcqtests_hint', 'mcqtests');
        $addhintLabel = $this->objLanguage->languageText('mod_mcqtests_hintenable', 'mcqtests');
        $lbEnable = $this->objLanguage->languageText('mod_mcqtests_hintaddenable', 'mcqtests');
        $lbDisable = $this->objLanguage->languageText('mod_mcqtests_hintadddisable', 'mcqtests');
        //Reference the heading to the layout template
        // Display information on the test to be set
        $headStr = '<h1>Free form question<h1/><hr>';
        $headStr.= '<b>' . 'Test' . ':</b>&nbsp;&nbsp;' . $test['name'] . '<br />';
        $headStr.= '<b>' . 'Total Marks' . ':</b>&nbsp;&nbsp;' . $test['totalmark'] . '<br />&nbsp;';

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
            $num = $test['count'] + 1;
            $questionType = '';
            $questId = '';
        }


        //create a hidden field tostore  question type
        $objInput = new textinput('type', $freeForm);
        $objInput->fldType = 'hidden';
        $headStr.= $objInput->show();


        //Heading for Question and number
        $objHead = new htmlheading();
        $objHead->str = 'Question' . ' ' . $num . ':';
        $objHead->type = 3;
        $headStr.= $objHead->show();


        //Create an instance of the htmlarea class
        $objEditor = $this->newObject('htmlarea', 'htmlelements');
        //initialise the fckeditor
        $objEditor->init('freeformquestion', $question, '300px', '500px');
        $objEditor->setDefaultToolBarSetWithoutSave();
        //$simplebox = $num ;
        //$textfield = new textinput ('simplebox',$stdanswer,'hidden');

        $headStr.=$objEditor->show() . '<br /><br />';

        //create mark textfield
        $objMark = new textinput('mark', $mark);
        $objMark->size = 10;
        $headStr.= '<b>' . $markLabel . '</b>:&nbsp;&nbsp;&nbsp;&nbsp;';
        $headStr.= $objMark->show();


        //create hint field
        $headStr.= '<p><b>' . $hintLabel . ':</b></p><p>' . $addhintLabel . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>';
        $objRadio = new radio('enablehint');
        $objRadio->setBreakSpace('&nbsp;&nbsp;/&nbsp;&nbsp;');
        $objRadio->addOption('yes', $lbEnable);
        $objRadio->addOption('no', $lbDisable);
        $objRadio->setSelected('no');
        if (!empty($hint)) {
            $objRadio->setSelected('yes');
        }
        $headStr.= '<p>' . $objRadio->show() . '</p>';
        $objInput = new textinput('hint', $hint);
        $objInput->size = 83;
        $headStr.= $objInput->show() . '<p>&nbsp;</p>';



        //Create Submit of the form
        $objButton = new button('save', $saveLabel);
        $objButton->setToSubmit();
        $btn = $objButton->show() . '&nbsp;&nbsp;&nbsp;&nbsp;';
        $objButton = new button('save', $exitLabel);
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
        $objForm = new form('addfreeformquestion', $this->uri(array('action' => 'applyaddquestion', 'formtype' => 'freeform')));
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
        $topStr = '<b>' . $testLabel . ':</b>&nbsp;&nbsp;' . $test['name'] . '<br />';
        $topStr.= '<b>' . $totalLabel . ':</b>&nbsp;&nbsp;' . $test['totalmark'] . '<br />&nbsp;';
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
            $num = $test['count'] + 1;
            $typeQ = '';
            $questId = '';
        }

        $objRadioType = new radio('type');
        $objRadioType->setBreakSpace('&nbsp;&nbsp;/&nbsp;&nbsp;');
        $objRadioType->addOption('mcq', $lbMcq);
        $objRadioType->addOption('tf', $lbTF);

        if (empty($typeQ)) {
            $objRadioType->setSelected('mcq');
        } else {
            $objRadioType->setSelected($typeQ);
        }

        $objDropNum = new dropdown('options');
        $objDropNum->addOption(2, $this->objLanguage->languageText('mod_mcqtests_two', 'mcqtests'));
        $objDropNum->addOption(3, $this->objLanguage->languageText('mod_mcqtests_three', 'mcqtests'));
        $objDropNum->addOption(4, $this->objLanguage->languageText('mod_mcqtests_four', 'mcqtests'));
        $objDropNum->addOption(5, $this->objLanguage->languageText('mod_mcqtests_five', 'mcqtests'));
        $objDropNum->addOption(6, $this->objLanguage->languageText('mod_mcqtests_six', 'mcqtests'));


        if ($mode == 'edit') {
            if ($numAnswers == 0) {
                $objDropNum->setSelected(4);
            } else {
                $objDropNum->setSelected($numAnswers);
            }
        } else {
            $objDropNum->setSelected(4);
        }

        $objTableType = new htmltable('tabletype');
        $objTableType->startRow();
        $objTableType->addCell('<b>' . $lbType . '</b>', '20%');
        $objTableType->addCell($objRadioType->show(), '80%');
        $objTableType->endRow();
        $objTableType->startRow();
        $objTableType->addCell('<b>' . $lbNumOpt . '</b>', '20%');
        $objTableType->addCell($objDropNum->show(), '80%');
        $objTableType->endRow();

        $topStr .= $objTableType->show() . '<br />';

        // Display question for editing.
        $objHead = new htmlheading();
        $objHead->str = $questionLabel . ' ' . $num . ':';
        $objHead->type = 3;
        $topStr.= $objHead->show();

        $type = $this->getParam('editor', 'ww');
        if ($type == 'plaintext') {
            // Hidden element for the editor type
            $objInput = new textinput('editor', 'ww', 'hidden');

            $objText = new textarea('question', $question, 4, 80);
            $topStr .= $objText->show();

            $objLink = new link("javascript:document.getElementById('form_addquestion').action.value = 'changeeditor';document.getElementById('form_addquestion').submit();");
            $objLink->link = $lnWysiwyg;
            $topStr .= '<br />' . $objLink->show() . $objInput->show() . '<br /><br />';
        } else {
            // Hidden element for the editor type
            $objInput = new textinput('editor', 'plaintext', 'hidden');
            $objAddEditor = $this->getObject('htmlarea', 'htmlelements');
            $objAddEditor->init('question', $question, '300px', '500px');
            $objAddEditor->setDefaultToolBarSetWithoutSave();
            $topStr.= $objAddEditor->show();

            $objLink = new link("javascript:document.getElementById('form_addquestion').action.value = 'changeeditor';document.getElementById('form_addquestion').submit();");
            $objLink->link = $lnPlain;

            // Hide link to plain text
            //$topStr .= '<br />'.$objLink->show().$objInput->show().'<br /><br />';
            $topStr .= '<br />' . $objInput->show() . '<br /><br />';
        }

        $objInput = new textinput('mark', $mark);
        $objInput->size = 10;
        $topStr.= '<p><b>' . $markLabel . ':</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
        $topStr.= $objInput->show() . '</p>';
        $topStr.= '<p><b>' . $hintLabel . ':</b></p><p>' . $addhintLabel . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>';
        $objRadio = new radio('enablehint');
        $objRadio->setBreakSpace('&nbsp;&nbsp;/&nbsp;&nbsp;');
        $objRadio->addOption('yes', $lbEnable);
        $objRadio->addOption('no', $lbDisable);
        $objRadio->setSelected('no');
        if (!empty($hint)) {
            $objRadio->setSelected('yes');
        }
        $topStr.= '<p>' . $objRadio->show() . '</p>';
        $objInput = new textinput('hint', $hint);
        $objInput->size = 83;
        $topStr.= $objInput->show() . '<p>&nbsp;</p>';

        // Save and exit buttons
        $objButton = new button('save', $saveLabel);
        $objButton->setToSubmit();
        $btn = $objButton->show();
        $objButton = new button('save', $exitLabel);
        $objButton->setToSubmit();
        $btn.= '&nbsp;&nbsp;&nbsp;&nbsp;' . $objButton->show();

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
        $objFormEdit = new form('addquestion', $this->uri(array('action' => 'applyaddquestion')));
        $objFormEdit->addToForm($topStr);
        $objFormEdit->addToForm($objTableButtons->show());
        //$objFormEdit->addRule('question', $errQuestion, 'required');
        $objFormEdit->addRule('mark', $errMark, 'numeric');
        $objFormEdit->addRule('mark', $errMarkReq, 'required');

        return $objFormEdit->show();
    }

    public function createDatabaseQuestions($oldQuestions, $id) {
        $this->dbTestadmin = $this->newObject('dbtestadmin');

        $gridjs =
                "<script type='text/javascript' language='javascript'>
            //<![CDATA[
            var submitUrl = '" . str_replace("amp;", "", $this->uri(array('action' => 'submitdbquestions'))) . "',
                nextUrl = '" . str_replace("amp;", "", $this->uri(array('action' => 'view', 'id' => $id))) . "',
                courseID = '" . $this->getParam('id') . "',
                myUrl = '" . str_replace("amp;", "", $this->uri(array('action' => 'formattedquestions', 'id' => $id))) . "',
                courseName = '" . $this->dbTestadmin->getTestName($id) . "',
                calcQFormUrl = '" . str_replace("amp;", "", $this->uri(array('action' => 'calcqform', 'id' => $id))) . "';//]]>
        </script>";

        return $gridjs;
    }

    /**
     * Method to create add description form
     *
     * @access private
     * @param  array $test Contains test data
     * @param  string $id Contains the test id
     * @author Paul Mungai
     */
    public function createAddDescriptionForm($test, $id) {
        //Load Classes
        $this->loadClass("textinput", "htmlelements");
        $this->loadClass("form", "htmlelements");
        $this->loadClass("textarea", "htmlelements");

        //Form text
        $addDescform = $this->objLanguage->languageText('mod_mcqtests_addDescription', 'mcqtests');
        $wordCategory = $this->objLanguage->languageText('mod_mcqtests_wordcategory', 'mcqtests');
        $wordGeneral = $this->objLanguage->languageText('mod_mcqtests_wordgeneral', 'mcqtests');
        $phraseQnName = $this->objLanguage->languageText('mod_mcqtests_qnname', 'mcqtests');
        $phraseQnText = $this->objLanguage->languageText('mod_mcqtests_qntext', 'mcqtests');
        $wordFeedback = $this->objLanguage->languageText('mod_mcqtests_generalfeedback', 'mcqtests');
        $wordTags = $this->objLanguage->languageText('mod_mcqtests_wordtags', 'mcqtests');
        $phraseOfficialTags = $this->objLanguage->languageText('mod_mcqtests_officialtags', 'mcqtests');
        $phraseMngOfficialTags = $this->objLanguage->languageText('mod_mcqtests_mngofficialtags', 'mcqtests');
        $phraseOtherTags = $this->objLanguage->languageText('mod_mcqtests_othertags', 'mcqtests');
        $phraseOtherTagsDesc = $this->objLanguage->languageText('mod_mcqtests_othertagsdesc', 'mcqtests');

        //Form Object
        $form = new form("adddescription", $this->uri(array(
                            'module' => 'mcqtest',
                            'action' => 'adddescconfirm',
                            'id' => $id
                        )));

        //Form Heading/Title
        $objHeading = &$this->getObject('htmlheading', 'htmlelements');
        $objHeading->type = 1;
        $objHeading->str = $addDescform;

        //Add heading/title to form
        $form->addToForm($objHeading->show());

        //Create table to hold the general stuff
        $objTable = new htmltable();
        $objTable->width = '800px';
        $objTable->attributes = " align='center' border='0'";
        $objTable->cellspacing = '12';

        //category text box
        $category = new textinput("categoryid", "");
        $category->size = 60;
        //Add Category to the table
        $objTable->startRow();
        $objTable->addCell($wordCategory, '20%');
        $objTable->addCell($wordGeneral, '80%');
        $objTable->endRow();

        //question name text box
        $qnname = new textinput("qnname", "");
        $qnname->size = 60;
        $form->addRule('qnname', $this->objLanguage->languageText('mod_mcqtests_qnnamerequired', 'mcqtests'), 'required');
        //Add Category to the table
        $objTable->startRow();
        $objTable->addCell($phraseQnName, '20%');
        $objTable->addCell($qnname->show(), '80%');
        $objTable->endRow();

        //qn text
        $editor = $this->newObject('htmlarea', 'htmlelements');
        $editor->name = 'qntext';
        $editor->height = '100px';
        $editor->width = '550px';
        $editor->setMCQToolBar();
        $qntext = '';
        $editor->setContent($qntext);
        //Add Category to the table
        $objTable->startRow();
        $objTable->addCell($phraseQnText, '20%');
        $objTable->addCell($editor->show(), '80%');
        $objTable->endRow();

        //general feedback
        $editor = $this->newObject('htmlarea', 'htmlelements');
        $editor->name = 'genfeedback';
        $editor->height = '100px';
        $editor->width = '550px';
        $editor->setMCQToolBar();
        $genfeedback = '';
        $editor->setContent($genfeedback);
        //Add General Feedback to the table
        $objTable->startRow();
        $objTable->addCell($wordFeedback, '20%');
        $objTable->addCell($editor->show(), '80%');
        $objTable->endRow();

        //Add fieldset to hold general stuff
        $objFieldset = &$this->getObject('fieldset', 'htmlelements');
        //$objFieldset->width = '800px';
        //$objFieldset->align = 'center';
        $objFieldset->setLegend($wordGeneral);

        //Add table to General Fieldset
        $objFieldset->addContent($objTable->show());

        //Add General Fieldset to form
        $form->addToForm($objFieldset->show());

        //Reset Fieldset
        $objFieldset->reset();
        //Create table to hold the tags
        $objTable2 = new htmltable();
        $objTable2->width = '800px';
        $objTable2->attributes = " align='center' border='0'";
        $objTable2->cellspacing = '12';

        //tags text box
        $officialtags = new textinput("officialtags", "");
        $officialtags->size = 60;

        //Add Tags to the table
        $objTable2->startRow();
        $objTable2->addCell($wordTags, '20%');
        $objTable2->addCell($phraseOfficialTags . " ( " . $phraseMngOfficialTags . " ) " . "<br />" . $officialtags->show(), '80%');
        $objTable2->endRow();

        //tags text box
        $othertags = "";
        $othertagsTA = new textarea();
        $othertagsTA->setName("othertags");
        $othertagsTA->setValue($othertags);
        $othertagsTA->setRows('4');
        $othertagsTA->setColumns('70');

        //Add Tags to the table
        $objTable2->startRow();
        $objTable2->addCell('&nbsp;');
        $objTable2->addCell($phraseOtherTags . " ( " . $phraseOtherTagsDesc . " ) " . "<br />" . $othertagsTA->show(), '80%');
        $objTable2->endRow();

        //Add fieldset to hold tags
        $objFieldset->setLegend($wordTags);

        //Add table to Tags Fieldset
        $objFieldset->addContent($objTable2->show());

        // Create Save Button
        $form->addToForm($objFieldset->show());
        $button = new button("submit", $this->objLanguage->languageText("word_save"));
        $button->setToSubmit();
        $btnSave = $button->showSexy();
        // Create Cancel Button
        $buttonCancel = new button("submit", $this->objLanguage->languageText("word_cancel"));
        $objCancel = &$this->getObject("link", "htmlelements");
        $objCancel->link($this->uri(array(
                    'module' => 'mcqtests',
                    'action' => 'view',
                    'id' => $id
                )));
        $objCancel->link = $buttonCancel->showSexy();
        $btnCancel = $objCancel->show();

        //Add Save and Cancel Buttons to form
        $form->addToForm("<br />" . $btnSave . " " . $btnCancel . "<br />");

        return "<div>" . $form->show() . "</div>";
    }

    /**
     * Method to create add short answer form
     *
     * @access private
     * @param  array $test Contains test data
     * @param  string $id Contains the answer id
     * @author Paul Mungai
     */
    public function createAddShortAnswerForm($test, $id) {
        //Load Classes
        $this->loadClass("textinput", "htmlelements");
        $this->loadClass("form", "htmlelements");
        $this->loadClass("textarea", "htmlelements");
        $this->loadClass("dropdown", "htmlelements");

        //Form texts
        $addDescform = $this->objLanguage->languageText('mod_mcqtests_addingshortanswerqn', 'mcqtests');
        $wordCategory = $this->objLanguage->languageText('mod_mcqtests_wordcategory', 'mcqtests');
        $wordGeneral = $this->objLanguage->languageText('mod_mcqtests_wordgeneral', 'mcqtests');
        $phraseQnName = $this->objLanguage->languageText('mod_mcqtests_qnname', 'mcqtests');
        $phraseQnText = $this->objLanguage->languageText('mod_mcqtests_qntext', 'mcqtests');
        $wordFeedback = $this->objLanguage->languageText('mod_mcqtests_generalfeedback', 'mcqtests');
        $wordTags = $this->objLanguage->languageText('mod_mcqtests_wordtags', 'mcqtests');
        $phraseOfficialTags = $this->objLanguage->languageText('mod_mcqtests_officialtags', 'mcqtests');
        $phraseMngOfficialTags = $this->objLanguage->languageText('mod_mcqtests_mngofficialtags', 'mcqtests');
        $phraseOtherTags = $this->objLanguage->languageText('mod_mcqtests_othertags', 'mcqtests');
        $phraseOtherTagsDesc = $this->objLanguage->languageText('mod_mcqtests_othertagsdesc', 'mcqtests');
        $phraseQnGrade = $this->objLanguage->languageText('mod_mcqtests_defaultQnGrade', 'mcqtests');
        $phrasePenaltyFactor = $this->objLanguage->languageText('mod_mcqtests_penaltylabel', 'mcqtests');
        $phraseCaseUnimportant = $this->objLanguage->languageText('mod_mcqtests_nocaseunimportant', 'mcqtests');
        $phraseCaseImportant = $this->objLanguage->languageText('mod_mcqtests_yescaseimportant', 'mcqtests');
        $phraseCaseSensitivity = $this->objLanguage->languageText('mod_mcqtests_caseSensitivity', 'mcqtests');
        $wordAnswer = $this->objLanguage->languageText('mod_mcqtests_wordanswer', 'mcqtests');
        $wordGrade = $this->objLanguage->languageText('mod_mcqtests_wordgrade', 'mcqtests');

        //Form Object
        $form = new form("adddescription", $this->uri(array(
                            'module' => 'mcqtest',
                            'action' => 'addshortansconfirm',
                            'id' => $id
                        )));

        //Form Heading/Title
        $objHeading = &$this->getObject('htmlheading', 'htmlelements');
        $objHeading->type = 1;
        $objHeading->str = $addDescform;

        //Add heading/title to form
        $form->addToForm($objHeading->show());

        //Create table to hold the general stuff
        $objTable = new htmltable();
        $objTable->width = '800px';
        $objTable->attributes = " align='center' border='0'";
        $objTable->cellspacing = '12';

        //category text box
        $category = new textinput("categoryid", "");
        $category->size = 60;
        //Add Category to the table
        $objTable->startRow();
        $objTable->addCell($wordCategory, '20%');
        $objTable->addCell($wordGeneral, '80%');
        $objTable->endRow();

        //question name text box
        $qnname = new textinput("qnname", "");
        $qnname->size = 60;
        $form->addRule('qnname', $this->objLanguage->languageText('mod_mcqtests_qnnamerequired', 'mcqtests'), 'required');
        //Add Category to the table
        $objTable->startRow();
        $objTable->addCell($phraseQnName, '20%');
        $objTable->addCell($qnname->show(), '80%');
        $objTable->endRow();

        //qn text
        $editor = $this->newObject('htmlarea', 'htmlelements');
        $editor->name = 'questiontext';
        $editor->height = '100px';
        $editor->width = '550px';
        $editor->setMCQToolBar();
        $qntext = '';
        $editor->setContent($qntext);
        //Add Category to the table
        $objTable->startRow();
        $objTable->addCell($phraseQnText, '20%');
        $objTable->addCell($editor->show(), '80%');
        $objTable->endRow();

        //Add default qn grade
        $qngrade = new textinput("qngrade", "");
        $qngrade->size = 60;
        $form->addRule('qngrade', $phraseQnGrade . " " . $this->objLanguage->languageText('mod_mcqtests_isrequired', 'mcqtests'), 'required');
        //Add qn grade to the table
        $objTable->startRow();
        $objTable->addCell($phraseQnGrade, '20%');
        $objTable->addCell($qngrade->show(), '80%');
        $objTable->endRow();

        //Add Penalty factor
        $pfactor = new textinput("penaltyfactor", "");
        $pfactor->size = 60;
        $form->addRule('penaltyfactor', $phrasePenaltyFactor . " " . $this->objLanguage->languageText('mod_mcqtests_isrequired', 'mcqtests'), 'required');
        //Add penalty factor field to the table
        $objTable->startRow();
        $objTable->addCell($phrasePenaltyFactor, '20%');
        $objTable->addCell($pfactor->show(), '80%');
        $objTable->endRow();

        //general feedback
        $editor = $this->newObject('htmlarea', 'htmlelements');
        $editor->name = 'generalfeedback';
        $editor->height = '100px';
        $editor->width = '550px';
        $editor->setMCQToolBar();
        $genfeedback = '';
        $editor->setContent($genfeedback);
        //Add General Feedback to the table
        $objTable->startRow();
        $objTable->addCell($wordFeedback, '20%');
        $objTable->addCell($editor->show(), '80%');
        $objTable->endRow();

        $sensitivitydropdown = new dropdown("sensitivity");
        $sensitivitydropdown->addOption("0", $phraseCaseUnimportant);
        $sensitivitydropdown->addOption("1", $phraseCaseImportant);
        $sensitivitydropdown->setSelected("0");

        //Add Sensitivity dropdown to the table
        $objTable->startRow();
        $objTable->addCell($phraseCaseSensitivity, '20%');
        $objTable->addCell($sensitivitydropdown->show(), '80%');
        $objTable->endRow();

        //Add fieldset to hold general stuff
        $objFieldset = &$this->getObject('fieldset', 'htmlelements');
        //$objFieldset->width = '800px';
        //$objFieldset->align = 'center';
        $objFieldset->setLegend($wordGeneral);

        //Add table to General Fieldset
        $objFieldset->addContent($objTable->show());

        //Add General Fieldset to form
        $form->addToForm($objFieldset->show());

        //Reset Fieldset
        $objFieldset->reset();

        //Create table to hold the answers
        $objTable3 = new htmltable();
        $objTable3->width = '800px';
        $objTable3->attributes = " align='center' border='0'";
        $objTable3->cellspacing = '12';

        //answer1 text box
        $answer = new textinput("answer1", "");
        $answer->size = 60;

        //Add Answer1 to the table
        $objTable3->startRow();
        $objTable3->addCell($wordAnswer, '20%');
        $objTable3->addCell($answer->show(), '80%');
        $objTable3->endRow();

        //Add Answer1 grade to the table
        $objTable3->startRow();
        $objTable3->addCell($wordGrade, '20%');
        $objTable3->addCell("", '80%');
        $objTable3->endRow();

        //answer1 feedback htmlarea
        $editor = $this->newObject('htmlarea', 'htmlelements');
        $editor->name = 'feedback1';
        $editor->height = '100px';
        $editor->width = '550px';
        $editor->setMCQToolBar();
        $qntext = '';
        $editor->setContent($qntext);

        //Add Answer1 feedback to the table
        $objTable3->startRow();
        $objTable3->addCell($wordFeedback, '20%');
        $objTable3->addCell($editor->show(), '80%');
        $objTable3->endRow();

        //Add fieldset to hold tags
        $objFieldset->setLegend($wordAnswer . " 1");

        //Add table to Tags Fieldset
        $objFieldset->addContent($objTable3->show());

        $form->addToForm($objFieldset->show());
        //Reset Fieldset
        $objFieldset->reset();

        //Create table to hold the answers
        $objTable3 = new htmltable();
        $objTable3->width = '800px';
        $objTable3->attributes = " align='center' border='0'";
        $objTable3->cellspacing = '12';

        //answer text box
        $answer = new textinput("answer2", "");
        $answer->size = 60;

        //Add Answer to the table
        $objTable3->startRow();
        $objTable3->addCell($wordAnswer, '20%');
        $objTable3->addCell($answer->show(), '80%');
        $objTable3->endRow();

        //Add Answer grade to the table
        $objTable3->startRow();
        $objTable3->addCell($wordGrade, '20%');
        $objTable3->addCell("", '80%');
        $objTable3->endRow();

        //answer feedback htmlarea
        $editor = $this->newObject('htmlarea', 'htmlelements');
        $editor->name = 'feedback2';
        $editor->height = '100px';
        $editor->width = '550px';
        $editor->setMCQToolBar();
        $qntext = '';
        $editor->setContent($qntext);

        //Add Answer feedback to the table
        $objTable3->startRow();
        $objTable3->addCell($wordFeedback, '20%');
        $objTable3->addCell($editor->show(), '80%');
        $objTable3->endRow();

        //Add fieldset to hold answer
        $objFieldset->setLegend($wordAnswer . " 2");

        //Add table to Tags Fieldset
        $objFieldset->addContent($objTable3->show());

        $form->addToForm($objFieldset->show());
        //Reset Fieldset
        $objFieldset->reset();

        //Create table to hold the answers
        $objTable3 = new htmltable();
        $objTable3->width = '800px';
        $objTable3->attributes = " align='center' border='0'";
        $objTable3->cellspacing = '12';

        //answer text box
        $answer = new textinput("answer3", "");
        $answer->size = 60;

        //Add Answer to the table
        $objTable3->startRow();
        $objTable3->addCell($wordAnswer, '20%');
        $objTable3->addCell($answer->show(), '80%');
        $objTable3->endRow();

        //Add Answer grade to the table
        $objTable3->startRow();
        $objTable3->addCell($wordGrade, '20%');
        $objTable3->addCell("", '80%');
        $objTable3->endRow();

        //answer feedback htmlarea
        $editor = $this->newObject('htmlarea', 'htmlelements');
        $editor->name = 'feedback3';
        $editor->height = '100px';
        $editor->width = '550px';
        $editor->setMCQToolBar();
        $qntext = '';
        $editor->setContent($qntext);

        //Add Answer feedback to the table
        $objTable3->startRow();
        $objTable3->addCell($wordFeedback, '20%');
        $objTable3->addCell($editor->show(), '80%');
        $objTable3->endRow();

        //Add fieldset to hold answer
        $objFieldset->setLegend($wordAnswer . " 3");

        //Add table to Tags Fieldset
        $objFieldset->addContent($objTable3->show());

        $form->addToForm($objFieldset->show());
        //Reset Fieldset
        $objFieldset->reset();

        //Create table to hold the tags
        $objTable2 = new htmltable();
        $objTable2->width = '800px';
        $objTable2->attributes = " align='center' border='0'";
        $objTable2->cellspacing = '12';

        //tags text box
        $officialtags = new textinput("officialtags", "");
        $officialtags->size = 60;

        //Add Tags to the table
        $objTable2->startRow();
        $objTable2->addCell($wordTags, '20%');
        $objTable2->addCell($phraseOfficialTags . " ( " . $phraseMngOfficialTags . " ) " . "<br />" . $officialtags->show(), '80%');
        $objTable2->endRow();

        //tags text box
        $othertags = "";
        $othertagsTA = new textarea();
        $othertagsTA->setName("othertags");
        $othertagsTA->setValue($othertags);
        $othertagsTA->setRows('4');
        $othertagsTA->setColumns('70');

        //Add Tags to the table
        $objTable2->startRow();
        $objTable2->addCell('&nbsp;');
        $objTable2->addCell($phraseOtherTags . " ( " . $phraseOtherTagsDesc . " ) " . "<br />" . $othertagsTA->show(), '80%');
        $objTable2->endRow();

        //Add fieldset to hold tags
        $objFieldset->setLegend($wordTags);

        //Add table to Tags Fieldset
        $objFieldset->addContent($objTable2->show());

        $form->addToForm($objFieldset->show());
        //Reset Fieldset
        $objFieldset->reset();

        // Create Save Button
        $button = new button("submit", $this->objLanguage->languageText("word_save"));
        $button->setToSubmit();
        $btnSave = $button->showSexy();
        // Create Cancel Button
        $buttonCancel = new button("submit", $this->objLanguage->languageText("word_cancel"));
        $objCancel = &$this->getObject("link", "htmlelements");
        $objCancel->link($this->uri(array(
                    'module' => 'mcqtests',
                    'action' => 'view',
                    'id' => $id
                )));
        $objCancel->link = $buttonCancel->showSexy();
        $btnCancel = $objCancel->show();

        //Add Save and Cancel Buttons to form
        $form->addToForm("<br />" . $btnSave . " " . $btnCancel . "<br />");

        return "<div>" . $form->show() . "</div>";
    }

    /**
     * Method to create add short answer form
     *
     * @access private
     * @param  array $test Contains test data
     * @param  string $id Contains the answer id
     * @author Paul Mungai
     */
    public function createRandomShortAnsForm($test, $id) {
        //Load Classes
        $this->loadClass("textinput", "htmlelements");
        $this->loadClass("form", "htmlelements");
        $this->loadClass("textarea", "htmlelements");
        $this->loadClass("dropdown", "htmlelements");
        $this->loadClass("checkbox", "htmlelements");

        //Form texts
        $addDescform = $this->objLanguage->languageText('mod_mcqtests_addDescription', 'mcqtests');
        $wordCategory = $this->objLanguage->languageText('mod_mcqtests_wordcategory', 'mcqtests');
        $wordGeneral = $this->objLanguage->languageText('mod_mcqtests_wordgeneral', 'mcqtests');
        $phraseQnName = $this->objLanguage->languageText('mod_mcqtests_qnname', 'mcqtests');
        $phraseQnText = $this->objLanguage->languageText('mod_mcqtests_qntext', 'mcqtests');
        $wordFeedback = $this->objLanguage->languageText('mod_mcqtests_generalfeedback', 'mcqtests');
        $wordTags = $this->objLanguage->languageText('mod_mcqtests_wordtags', 'mcqtests');
        $phraseOfficialTags = $this->objLanguage->languageText('mod_mcqtests_officialtags', 'mcqtests');
        $phraseMngOfficialTags = $this->objLanguage->languageText('mod_mcqtests_mngofficialtags', 'mcqtests');
        $phraseOtherTags = $this->objLanguage->languageText('mod_mcqtests_othertags', 'mcqtests');
        $phraseOtherTagsDesc = $this->objLanguage->languageText('mod_mcqtests_othertagsdesc', 'mcqtests');
        $phraseQnGrade = $this->objLanguage->languageText('mod_mcqtests_defaultQnGrade', 'mcqtests');
        $phrasePenaltyFactor = $this->objLanguage->languageText('mod_mcqtests_penaltylabel', 'mcqtests');
        $phraseNoQnsToSelect = $this->objLanguage->languageText('mod_mcqtests_noqnstoselect', 'mcqtests');
        $phraseSaveInCategory = $this->objLanguage->languageText('mod_mcqtests_saveincategory', 'mcqtests');
        $phraseCurrentCategory = $this->objLanguage->languageText('mod_mcqtests_currentcategory', 'mcqtests');
        $phraseUseCategory = $this->objLanguage->languageText('mod_mcqtests_usecategory', 'mcqtests');
        $phraseRandomShortAns = $this->objLanguage->languageText('mod_mcqtests_randomshortans', 'mcqtests');
        $phraseAddingA = $this->objLanguage->languageText('mod_mcqtests_addinga', 'mcqtests');
        $phraseEditingA = $this->objLanguage->languageText('mod_mcqtests_editinga', 'mcqtests');
        $phraseLastSaved = $this->objLanguage->languageText('mod_mcqtests_lastsaved', 'mcqtests');
        $phraseCreatedOrSaved = $this->objLanguage->languageText('mod_mcqtests_createdorsaved', 'mcqtests');
        $phraseCreated = $this->objLanguage->languageText('mod_mcqtests_created', 'mcqtests');
        $phrasePermissions = $this->objLanguage->languageText("mod_mcqtests_permissionsto", 'mcqtests');
        $phraseSaveChanges = $this->objLanguage->languageText("mod_mcqtests_savechanges", 'mcqtests');
        $phraseSaveAsNewQn = $this->objLanguage->languageText("mod_mcqtests_saveasnewqn", 'mcqtests');

        //Form Object
        $form = new form("adddescription", $this->uri(array(
                            'module' => 'mcqtest',
                            'action' => 'addrandomshortansconfirm',
                            'id' => $id
                        )));

        //Form Heading/Title
        $objHeading = &$this->getObject('htmlheading', 'htmlelements');
        $objHeading->type = 1;
        $objHeading->str = $phraseAddingA . " " . $phraseRandomShortAns;

        //Add heading/title to form
        $form->addToForm($objHeading->show());

        //Create table to hold the permissions
        $objTable = new htmltable();
        $objTable->width = '800px';
        $objTable->attributes = " align='center' border='0'";
        $objTable->cellspacing = '12';

        //permissions listing to the table
        $objTable->startRow();
        $objTable->addCell("&nbsp;", '80%');
        $objTable->endRow();

        //Add fieldset to hold permissions listing
        $objFieldset = &$this->getObject('fieldset', 'htmlelements');
        //$objFieldset->width = '800px';
        //$objFieldset->align = 'center';
        $objFieldset->setLegend($phrasePermissions);

        //Add table to General Fieldset
        $objFieldset->addContent($objTable->show());

        //Add General Fieldset to form
        $form->addToForm($objFieldset->show());

        //Reset Fieldset
        $objFieldset->reset();

        //Create table to hold the general stuff
        $objTable = new htmltable();
        $objTable->width = '800px';
        $objTable->attributes = " align='center' border='0'";
        $objTable->cellspacing = '12';

        //use-category text box
        $usecategory = new checkbox('usecategory');
        $usecategory->setChecked(0);
        $usecategory->setValue(1);

        //Add Use-Category to the table
        $objTable->startRow();
        $objTable->addCell($phraseCurrentCategory, '20%');
        $objTable->addCell($usecategory->show() . " " . $phraseUseCategory, '80%');
        $objTable->endRow();

        //category text box
        $category = new textinput("categoryid", "");
        $category->size = 60;
        //Add Category to the table
        $objTable->startRow();
        $objTable->addCell($phraseSaveInCategory, '20%');
        $objTable->addCell($wordGeneral, '80%');
        $objTable->endRow();

        //question name text box
        $qnname = new textinput("qnname", "");
        $qnname->size = 60;
        $form->addRule('qnname', $this->objLanguage->languageText('mod_mcqtests_qnnamerequired', 'mcqtests'), 'required');
        //Add Category to the table
        $objTable->startRow();
        $objTable->addCell($phraseQnName, '20%');
        $objTable->addCell($qnname->show(), '80%');
        $objTable->endRow();

        //qn text
        $editor = $this->newObject('htmlarea', 'htmlelements');
        $editor->name = 'qntext';
        $editor->height = '100px';
        $editor->width = '550px';
        $editor->setMCQToolBar();
        $qntext = '';
        $editor->setContent($qntext);
        //Add Category to the table
        $objTable->startRow();
        $objTable->addCell($phraseQnText, '20%');
        $objTable->addCell($editor->show(), '80%');
        $objTable->endRow();

        //Add default qn grade
        $qngrade = new textinput("qngrade", "");
        $qngrade->size = 60;
        $form->addRule('qngrade', $phraseQnGrade . " " . $this->objLanguage->languageText('mod_mcqtests_isrequired', 'mcqtests'), 'required');
        //Add qn grade to the table
        $objTable->startRow();
        $objTable->addCell($phraseQnGrade, '20%');
        $objTable->addCell($qngrade->show(), '80%');
        $objTable->endRow();

        //Add Penalty factor
        $pfactor = new textinput("penaltyfactor", "");
        $pfactor->size = 60;
        $form->addRule('penaltyfactor', $phrasePenaltyFactor . " " . $this->objLanguage->languageText('mod_mcqtests_isrequired', 'mcqtests'), 'required');
        //Add penalty factor field to the table
        $objTable->startRow();
        $objTable->addCell($phrasePenaltyFactor, '20%');
        $objTable->addCell($pfactor->show(), '80%');
        $objTable->endRow();

        //general feedback
        $editor = $this->newObject('htmlarea', 'htmlelements');
        $editor->name = 'genfeedback';
        $editor->height = '100px';
        $editor->width = '550px';
        $editor->setMCQToolBar();
        $genfeedback = '';
        $editor->setContent($genfeedback);
        //Add General Feedback to the table
        $objTable->startRow();
        $objTable->addCell($wordFeedback, '20%');
        $objTable->addCell($editor->show(), '80%');
        $objTable->endRow();

        $noofqnsdropdown = new dropdown("sensitivity");
        $noofqnsdropdown->addOption("2", "2");
        $noofqnsdropdown->addOption("3", "3");
        $noofqnsdropdown->addOption("4", "4");
        $noofqnsdropdown->addOption("5", "5");
        $noofqnsdropdown->addOption("6", "6");
        $noofqnsdropdown->addOption("7", "7");
        $noofqnsdropdown->addOption("8", "8");
        $noofqnsdropdown->addOption("9", "9");
        $noofqnsdropdown->addOption("10", "10");
        $noofqnsdropdown->setSelected("0");

        //Add Sensitivity dropdown to the table
        $objTable->startRow();
        $objTable->addCell($phraseNoQnsToSelect, '20%');
        $objTable->addCell($noofqnsdropdown->show(), '80%');
        $objTable->endRow();

        //Add fieldset to hold general stuff
        $objFieldset = &$this->getObject('fieldset', 'htmlelements');
        //$objFieldset->width = '800px';
        //$objFieldset->align = 'center';
        $objFieldset->setLegend($wordGeneral);

        //Add table to General Fieldset
        $objFieldset->addContent($objTable->show());

        //Add General Fieldset to form
        $form->addToForm($objFieldset->show());

        //Reset Fieldset
        $objFieldset->reset();
        //Create table to hold the tags
        $objTable2 = new htmltable();
        $objTable2->width = '800px';
        $objTable2->attributes = " align='center' border='0'";
        $objTable2->cellspacing = '12';

        //tags text box
        $officialtags = new textinput("officialtags", "");
        $officialtags->size = 60;

        //Add Tags to the table
        $objTable2->startRow();
        $objTable2->addCell($wordTags, '20%');
        $objTable2->addCell($phraseOfficialTags . " ( " . $phraseMngOfficialTags . " ) " . "<br />" . $officialtags->show(), '80%');
        $objTable2->endRow();

        //tags text box
        $othertags = "";
        $othertagsTA = new textarea();
        $othertagsTA->setName("othertags");
        $othertagsTA->setValue($othertags);
        $othertagsTA->setRows('4');
        $othertagsTA->setColumns('70');

        //Add Tags to the table
        $objTable2->startRow();
        $objTable2->addCell('&nbsp;');
        $objTable2->addCell($phraseOtherTags . " ( " . $phraseOtherTagsDesc . " ) " . "<br />" . $othertagsTA->show(), '80%');
        $objTable2->endRow();

        //Add fieldset to hold tags
        $objFieldset->setLegend($wordTags);

        //Add table to Tags Fieldset
        $objFieldset->addContent($objTable2->show());

        $form->addToForm($objFieldset->show());
        //Reset Fieldset
        $objFieldset->reset();

        //Create table to hold the tags
        $objTable3 = new htmltable();
        $objTable3->width = '800px';
        $objTable3->attributes = " align='center' border='0'";
        $objTable3->cellspacing = '12';

        //Add Last Saved to the table
        $objTable3->startRow();
        $objTable3->addCell($phraseCreated, '20%');
        $objTable3->addCell("&nbsp;", '80%');
        $objTable3->endRow();

        //Add Last Saved to the table
        $objTable3->startRow();
        $objTable3->addCell($phraseLastSaved, '20%');
        $objTable3->addCell("&nbsp;", '80%');
        $objTable3->endRow();

        //Add fieldset to hold last-saved
        $objFieldset->setLegend($phraseCreatedOrSaved);

        //Add table to Tags Fieldset
        $objFieldset->addContent($objTable3->show());

        $form->addToForm($objFieldset->show());
        //Reset Fieldset
        $objFieldset->reset();

        // Create Save Button
        $button = new button("submit", $phraseSaveChanges);
        $button->setValue('savechanges');
        $button->setToSubmit();
        $btnSave = $button->showSexy();

        $button1 = new button("submit", $phraseSaveAsNewQn);
        $button1->setValue('saveasnew');
        //$button1->setToSubmit();
        $btnSaveAsnew = $button1->showSexy();

        // Create Cancel Button
        $buttonCancel = new button("submit", $this->objLanguage->languageText("word_cancel"));
        $objCancel = &$this->getObject("link", "htmlelements");
        $objCancel->link($this->uri(array(
                    'module' => 'mcqtests',
                    'action' => 'view',
                    'id' => $id
                )));
        $objCancel->link = $buttonCancel->showSexy();
        $btnCancel = $objCancel->show();

        //Add Save and Cancel Buttons to form
        $form->addToForm("<br />" . $btnSave . " " . $btnSaveAsnew . " " . $btnCancel . "<br />");

        return "<div>" . $form->show() . "</div>";
    }

    /**
     * Method to create add short answer form
     *
     * @access private
     * @param  array $test Contains test data
     * @param  string $id Contains the answer id
     * @author Paul Mungai
     */
    public function createSimpleCalculatedQnForm($test, $id, $answerCount=2, $unitCount=1) {
        //Load Classes
        $this->loadClass("textinput", "htmlelements");
        $this->loadClass("form", "htmlelements");
        $this->loadClass("textarea", "htmlelements");
        $this->loadClass("dropdown", "htmlelements");
        $this->loadClass("checkbox", "htmlelements");
        $this->loadClass("radio", "htmlelements");

        //Form texts
        $simplecalcqn = $this->objLanguage->languageText('mod_mcqtests_simplecalcqn', 'mcqtests');
        $wordCategory = $this->objLanguage->languageText('mod_mcqtests_wordcategory', 'mcqtests');
        $wordGeneral = $this->objLanguage->languageText('mod_mcqtests_wordgeneral', 'mcqtests');
        $phraseQnName = $this->objLanguage->languageText('mod_mcqtests_qnname', 'mcqtests');
        $phraseQnText = $this->objLanguage->languageText('mod_mcqtests_qntext', 'mcqtests');
        $wordFeedback = $this->objLanguage->languageText('mod_mcqtests_generalfeedback', 'mcqtests');
        $wordTags = $this->objLanguage->languageText('mod_mcqtests_wordtags', 'mcqtests');
        $phraseOfficialTags = $this->objLanguage->languageText('mod_mcqtests_officialtags', 'mcqtests');
        $phraseMngOfficialTags = $this->objLanguage->languageText('mod_mcqtests_mngofficialtags', 'mcqtests');
        $phraseOtherTags = $this->objLanguage->languageText('mod_mcqtests_othertags', 'mcqtests');
        $phraseOtherTagsDesc = $this->objLanguage->languageText('mod_mcqtests_othertagsdesc', 'mcqtests');
        $phraseQnGrade = $this->objLanguage->languageText('mod_mcqtests_defaultQnGrade', 'mcqtests');
        $phrasePenaltyFactor = $this->objLanguage->languageText('mod_mcqtests_penaltylabel', 'mcqtests');
        $phraseNoQnsToSelect = $this->objLanguage->languageText('mod_mcqtests_noqnstoselect', 'mcqtests');
        $phraseSaveInCategory = $this->objLanguage->languageText('mod_mcqtests_saveincategory', 'mcqtests');
        $phraseCurrentCategory = $this->objLanguage->languageText('mod_mcqtests_currentcategory', 'mcqtests');
        $phraseUseCategory = $this->objLanguage->languageText('mod_mcqtests_usecategory', 'mcqtests');
        $phraseRandomShortAns = $this->objLanguage->languageText('mod_mcqtests_randomshortans', 'mcqtests');
        $phraseAddingA = $this->objLanguage->languageText('mod_mcqtests_addinga', 'mcqtests');
        $phraseEditingA = $this->objLanguage->languageText('mod_mcqtests_editinga', 'mcqtests');
        $phraseLastSaved = $this->objLanguage->languageText('mod_mcqtests_lastsaved', 'mcqtests');
        $phraseCreatedOrSaved = $this->objLanguage->languageText('mod_mcqtests_createdorsaved', 'mcqtests');
        $phraseCreated = $this->objLanguage->languageText('mod_mcqtests_created', 'mcqtests');
        $phrasePermissions = $this->objLanguage->languageText("mod_mcqtests_permissionsto", 'mcqtests');
        $phraseSaveChanges = $this->objLanguage->languageText("mod_mcqtests_savechanges", 'mcqtests');
        $phraseSaveAsNewQn = $this->objLanguage->languageText("mod_mcqtests_saveasnewqn", 'mcqtests');
        $phraseCorrectAnsFormula = $this->objLanguage->languageText("mod_mcqtest_corranswerlabel", 'mcqtests');
        $wordNone = $this->objLanguage->languageText("word_none", 'system');
        $wordGrade = $this->objLanguage->languageText("mod_mcqtests_wordgrade", 'mcqtests');
        $phraseTolerance = $this->objLanguage->languageText("mod_mcqtest_tolerancelabel", 'mcqtests');
        $wordRelative = $this->objLanguage->languageText("mod_mcqtest_relative", 'mcqtests');
        $wordNominal = $this->objLanguage->languageText("mod_mcqtest_nominal", 'mcqtests');
        $wordNominal = $this->objLanguage->languageText("mod_mcqtest_nominal", 'mcqtests');
        $phraseCorrectAnsShows = $this->objLanguage->languageText("mod_mcqtests_correctAnswerShows", 'mcqtests');
        $phraseToleranceType = $this->objLanguage->languageText("mod_mcqtests_tolerancetype", 'mcqtests');
        $wordDecimals = $this->objLanguage->languageText("mod_mcqtests_decimals", 'mcqtests');
        $phraseSigFigs = $this->objLanguage->languageText("mod_mcqtests_sigfigs", 'mcqtests');
        $wordFormat = $this->objLanguage->languageText("mod_mcqtests_formatlabel", 'mcqtests');

        //Form Object
        $form = new form("adddescription", $this->uri(array(
                            'module' => 'mcqtest',
                            'action' => 'addrandomshortansconfirm',
                            'id' => $id
                        )));

        //Form Heading/Title
        $objHeading = &$this->getObject('htmlheading', 'htmlelements');
        $objHeading->type = 1;
        $objHeading->str = $phraseAddingA . " " . $simplecalcqn;

        //Add heading/title to form
        $form->addToForm($objHeading->show());

        //Create table to hold the permissions
        $objTable = new htmltable();
        $objTable->width = '800px';
        $objTable->attributes = " align='center' border='0'";
        $objTable->cellspacing = '12';

        //permissions listing to the table
        $objTable->startRow();
        $objTable->addCell("&nbsp;", '80%');
        $objTable->endRow();

        //Add fieldset to hold permissions listing
        $objFieldset = &$this->getObject('fieldset', 'htmlelements');
        //$objFieldset->width = '800px';
        //$objFieldset->align = 'center';
        $objFieldset->setLegend($phrasePermissions);

        //Add table to General Fieldset
        $objFieldset->addContent($objTable->show());

        //Add General Fieldset to form
        $form->addToForm($objFieldset->show());

        //Reset Fieldset
        $objFieldset->reset();

        //Create table to hold the general stuff
        $objTable = new htmltable();
        $objTable->width = '800px';
        $objTable->attributes = " align='center' border='0'";
        $objTable->cellspacing = '12';


        //Add Use-Category to the table
        $objTable->startRow();
        $objTable->addCell($wordCategory, '20%');
        $objTable->addCell(Null, '80%');
        $objTable->endRow();

        //question name text box
        $qnname = new textinput("qnname", "");
        $qnname->size = 60;
        $form->addRule('qnname', $this->objLanguage->languageText('mod_mcqtests_qnnamerequired', 'mcqtests'), 'required');
        //Add Category to the table
        $objTable->startRow();
        $objTable->addCell($phraseQnName, '20%');
        $objTable->addCell($qnname->show(), '80%');
        $objTable->endRow();

        //qn text
        $editor = $this->newObject('htmlarea', 'htmlelements');
        $editor->name = 'qntext';
        $editor->height = '100px';
        $editor->width = '550px';
        $editor->setMCQToolBar();
        $qntext = '';
        $editor->setContent($qntext);
        //Add Category to the table
        $objTable->startRow();
        $objTable->addCell($phraseQnText, '20%');
        $objTable->addCell($editor->show(), '80%');
        $objTable->endRow();

        //Add default qn grade
        $qngrade = new textinput("qngrade", "");
        $qngrade->size = 60;
        $form->addRule('qngrade', $phraseQnGrade . " " . $this->objLanguage->languageText('mod_mcqtests_isrequired', 'mcqtests'), 'required');
        //Add qn grade to the table
        $objTable->startRow();
        $objTable->addCell($phraseQnGrade, '20%');
        $objTable->addCell($qngrade->show(), '80%');
        $objTable->endRow();

        //Add Penalty factor
        $pfactor = new textinput("penaltyfactor", "");
        $pfactor->size = 60;
        $form->addRule('penaltyfactor', $phrasePenaltyFactor . " " . $this->objLanguage->languageText('mod_mcqtests_isrequired', 'mcqtests'), 'required');
        //Add penalty factor field to the table
        $objTable->startRow();
        $objTable->addCell($phrasePenaltyFactor, '20%');
        $objTable->addCell($pfactor->show(), '80%');
        $objTable->endRow();

        //general feedback
        $editor = $this->newObject('htmlarea', 'htmlelements');
        $editor->name = 'genfeedback';
        $editor->height = '100px';
        $editor->width = '550px';
        $editor->setMCQToolBar();
        $genfeedback = '';
        $editor->setContent($genfeedback);
        //Add General Feedback to the table
        $objTable->startRow();
        $objTable->addCell($wordFeedback, '20%');
        $objTable->addCell($editor->show(), '80%');
        $objTable->endRow();

        //Add fieldset to hold general stuff
        $objFieldset = &$this->getObject('fieldset', 'htmlelements');
        //$objFieldset->width = '800px';
        //$objFieldset->align = 'center';
        $objFieldset->setLegend($wordGeneral);

        //Add table to General Fieldset
        $objFieldset->addContent($objTable->show());

        //Add General Fieldset to form
        $form->addToForm($objFieldset->show());

        //Reset Fieldset
        $objFieldset->reset();
        $count = 1;
        if ($answerCount < 2)
            $answerCount = 2;
        while ($count < $answerCount) {
            //increment count
            $count++;
            //Create table to hold the answer1
            $objTable3 = new htmltable();
            $objTable3->width = '800px';
            $objTable3->attributes = " align='center' border='0'";
            $objTable3->cellspacing = '12';

            //correct-answer-formula1 text box
            $answerformula = new textinput("simpleformula" . $count, "");
            $answerformula->size = 60;

            //Add correct-answer-formula1 to the table
            $objTable3->startRow();
            $objTable3->addCell($phraseCorrectAnsFormula, '20%');
            $objTable3->addCell($answerformula->show(), '80%');
            $objTable3->endRow();

            //grade dropdown
            $gradedropdown = new dropdown("gradeans" . $count);
            $gradedropdown->addOption("1", "100 %");
            $gradedropdown->addOption("0.9", "90%");
            $gradedropdown->addOption("0.8333333", "83.33333 %");
            $gradedropdown->addOption("0.8", "80 %");
            $gradedropdown->addOption("0.75", "75 %");
            $gradedropdown->addOption("0.7", "70 %");
            $gradedropdown->addOption("0.6666667", "66.66667 %");
            $gradedropdown->addOption("0.6", "60 %");
            $gradedropdown->addOption("0.5", "50 %");
            $gradedropdown->addOption("0.4", "40 %");
            $gradedropdown->addOption("0.3333333", "33.33333 %");
            $gradedropdown->addOption("0.3", "30 %");
            $gradedropdown->addOption("0.25", "25 %");
            $gradedropdown->addOption("0.2", "20 %");
            $gradedropdown->addOption("0.1666667", "16.66667 %");
            $gradedropdown->addOption("0.1428571", "14.28571 %");
            $gradedropdown->addOption("0.125", "12.5 %");
            $gradedropdown->addOption("0.1111111", "11.11111 %");
            $gradedropdown->addOption("0.1", "10 %");
            $gradedropdown->addOption("0.05", "5 %");
            $gradedropdown->addOption("0", $wordNone);
            $gradedropdown->setSelected("0");

            //Add grade dropdown to the table
            $objTable3->startRow();
            $objTable3->addCell($wordGrade, '20%');
            $objTable3->addCell($gradedropdown->show(), '80%');
            $objTable3->endRow();

            //simple-tolerance1 text box
            $simpletolerance1 = new textinput("simpletolerance" . $count, "");
            $simpletolerance1->size = 60;

            //Add simple-tolerance1 to the table
            $objTable3->startRow();
            $objTable3->addCell($phraseTolerance, '20%');
            $objTable3->addCell($simpletolerance1->show(), '80%');
            $objTable3->endRow();

            //tolerance-type dropdown
            $tolerancetype1 = new dropdown("tolerancetype" . $count);
            $tolerancetype1->addOption("1", $wordRelative);
            $tolerancetype1->addOption("2", $wordNominal);
            $tolerancetype1->setSelected("1");

            //Add tolerance-type dropdown to the table
            $objTable3->startRow();
            $objTable3->addCell($phraseToleranceType, '20%');
            $objTable3->addCell($tolerancetype1->show(), '80%');
            $objTable3->endRow();

            //correct-answer-shows dropdown
            $correctanswershows1 = new dropdown("correctanswershows" . $count);
            $correctanswershows1->addOption("1", "1");
            $correctanswershows1->addOption("2", "2");
            $correctanswershows1->addOption("3", "3");
            $correctanswershows1->addOption("4", "4");
            $correctanswershows1->addOption("5", "5");
            $correctanswershows1->addOption("6", "6");
            $correctanswershows1->addOption("7", "7");
            $correctanswershows1->addOption("8", "8");
            $correctanswershows1->addOption("9", "9");
            $correctanswershows1->setSelected("2");

            //Add correct-answer-shows dropdown to the table
            $objTable3->startRow();
            $objTable3->addCell($phraseCorrectAnsShows, '20%');
            $objTable3->addCell($correctanswershows1->show(), '80%');
            $objTable3->endRow();

            //Format dropdown
            $format1 = new dropdown("format" . $count);
            $format1->addOption("1", $wordDecimals);
            $format1->addOption("2", $phraseSigFigs);
            $format1->setSelected("1");

            //Add Format dropdown to the table
            $objTable3->startRow();
            $objTable3->addCell($wordFormat, '20%');
            $objTable3->addCell($format1->show(), '80%');
            $objTable3->endRow();

            //answer1 feedback htmlarea
            $editor = $this->newObject('htmlarea', 'htmlelements');
            $editor->name = 'feedback' . $count;
            $editor->height = '100px';
            $editor->width = '550px';
            $editor->setMCQToolBar();
            $qntext = '';
            $editor->setContent($qntext);

            //Add Answer1 feedback to the table
            $objTable3->startRow();
            $objTable3->addCell($wordFeedback, '20%');
            $objTable3->addCell($editor->show(), '80%');
            $objTable3->endRow();

            //Add fieldset to hold tags
            $objFieldset->setLegend($wordAnswer . " " . $count);

            //Add table to Tags Fieldset
            $objFieldset->addContent($objTable3->show());

            $form->addToForm($objFieldset->show());
            //Reset Fieldset
            $objFieldset->reset();
        }
        //Create table to hold unit-handling
        $objTable4 = new htmltable();
        $objTable4->width = '800px';
        $objTable4->attributes = " align='center' border='0'";
        $objTable4->cellspacing = '12';

        //unit-graded text box
        $unitgraded = new radio("unitgraded");
        $unitgraded->addOption("1", $phraseNumericalUnitGraded);
        $unitgraded->setSelected("0");

        //Add unit-graded to the table
        $objTable4->startRow();
        $objTable4->addCell($phraseUnitGraded, '20%');
        $objTable4->addCell($unitgraded->show(), '80%');
        $objTable4->endRow();

        //penalty-bad-unit text box
        $penaltybadunit = new textinput("penaltybadunit", "");
        $penaltybadunit->size = 20;

        //decimal-fraction dropdown
        $decimalfractionddown = new dropdown("decimalfraction");
        $decimalfractionddown->addOption("1", $phraseQuestionGrade);
        $decimalfractionddown->addOption("2", $phraseUnitHandling);
        $decimalfractionddown->setSelected("1");

        //Add penalty-bad-unit to the table
        $objTable4->startRow();
        $objTable4->addCell($phrasePenaltyBadUnit, '20%');
        $objTable4->addCell($penaltybadunit->show() . " " . $decimalfractionddown->show(), '80%');
        $objTable4->endRow();

        //instructions htmlarea
        $editor = $this->newObject('htmlarea', 'htmlelements');
        $editor->name = 'instructions';
        $editor->height = '100px';
        $editor->width = '550px';
        $editor->setMCQToolBar();
        $instructions = '';
        $editor->setContent($instructions);

        //Add instructions to the table
        $objTable4->startRow();
        $objTable4->addCell($wordInstructions, '20%');
        $objTable4->addCell($editor->show(), '80%');
        $objTable4->endRow();

        //unit-not-graded dropdown
        $unitnotgraded = new dropdown("unitnotgraded");
        $unitnotgraded->addOption("1", $phraseRightAs);
        $unitnotgraded->setSelected("0");

        //Add unit-not-graded dropdown to the table
        $objTable4->startRow();
        $objTable4->addCell($phraseNumericalAnsGraded, '20%');
        $objTable4->addCell($unitnotgraded->show(), '80%');
        $objTable4->endRow();

        //display-unit dropdown
        $displayunit = new dropdown("displayunit");
        $displayunit->addOption("0", $wordNo);
        $displayunit->addOption("1", $wordYes);
        $displayunit->setSelected("0");

        //Add display-unit dropdown to the table
        $objTable4->startRow();
        $objTable4->addCell($phraseDisplayUnit, '20%');
        $objTable4->addCell($displayunit->show(), '80%');
        $objTable4->endRow();

        //unit-position dropdown
        $unitpostion = new dropdown("unitpostion");
        $unitpostion->addOption("1", $phraseRightAs);
        $unitpostion->addOption("2", $phraseLeftAs);
        $unitpostion->setSelected("1");

        //Add tolerance-type dropdown to the table
        $objTable4->startRow();
        $objTable4->addCell($phraseUnitPosition, '20%');
        $objTable4->addCell($unitposition->show(), '80%');
        $objTable4->endRow();

        //Add fieldset to hold tags
        $objFieldset->setLegend($wordAnswer . " 1");

        //Add table to Tags Fieldset
        $objFieldset->addContent($objTable4->show());

        $form->addToForm($objFieldset->show());
        //Reset Fieldset
        $objFieldset->reset();

        $count = 1;
        while ($count < $unitCount) {
            $count++;
            //Create table to hold units
            $objTable5 = new htmltable();
            $objTable5->width = '800px';
            $objTable5->attributes = " align='center' border='0'";
            $objTable5->cellspacing = '12';

            //unit text box
            $unitxt = new textinput("unit".$count, "");
            $unitxt->size = 60;
            $form->addRule('unit'.$count, $wordUnit . " " . $phraseIsRequired, 'required');
            //Add Unit to the table
            $objTable5->startRow();
            $objTable5->addCell($wordUnit, '20%');
            $objTable5->addCell($unitxt->show(), '80%');
            $objTable5->endRow();

            //Add Multiplier to the table
            $objTable5->startRow();
            $objTable5->addCell($wordUnit, '20%');
            $objTable5->addCell($unitxt->show(), '80%');
            $objTable5->endRow();

            //Add fieldset to hold units
            $objFieldset->setLegend($wordUnit." ".$count);

            //Add table to Tags Fieldset
            $objFieldset->addContent($objTable5->show());

            $form->addToForm($objFieldset->show());
            //Reset Fieldset
            $objFieldset->reset();
        }
        //Create table to hold the tags
        $objTable2 = new htmltable();
        $objTable2->width = '800px';
        $objTable2->attributes = " align='center' border='0'";
        $objTable2->cellspacing = '12';

        //tags text box
        $officialtags = new textinput("officialtags", "");
        $officialtags->size = 60;

        //Add Tags to the table
        $objTable2->startRow();
        $objTable2->addCell($wordTags, '20%');
        $objTable2->addCell($phraseOfficialTags . " ( " . $phraseMngOfficialTags . " ) " . "<br />" . $officialtags->show(), '80%');
        $objTable2->endRow();

        //tags text box
        $othertags = "";
        $othertagsTA = new textarea();
        $othertagsTA->setName("othertags");
        $othertagsTA->setValue($othertags);
        $othertagsTA->setRows('4');
        $othertagsTA->setColumns('70');

        //Add Tags to the table
        $objTable2->startRow();
        $objTable2->addCell('&nbsp;');
        $objTable2->addCell($phraseOtherTags . " ( " . $phraseOtherTagsDesc . " ) " . "<br />" . $othertagsTA->show(), '80%');
        $objTable2->endRow();

        //Add fieldset to hold tags
        $objFieldset->setLegend($wordTags);

        //Add table to Tags Fieldset
        $objFieldset->addContent($objTable2->show());

        $form->addToForm($objFieldset->show());
        //Reset Fieldset
        $objFieldset->reset();

        //Create table to hold the tags
        $objTable3 = new htmltable();
        $objTable3->width = '800px';
        $objTable3->attributes = " align='center' border='0'";
        $objTable3->cellspacing = '12';

        //Add Last Saved to the table
        $objTable3->startRow();
        $objTable3->addCell($phraseCreated, '20%');
        $objTable3->addCell("&nbsp;", '80%');
        $objTable3->endRow();

        //Add Last Saved to the table
        $objTable3->startRow();
        $objTable3->addCell($phraseLastSaved, '20%');
        $objTable3->addCell("&nbsp;", '80%');
        $objTable3->endRow();

        //Add fieldset to hold last-saved
        $objFieldset->setLegend($phraseCreatedOrSaved);

        //Add table to Tags Fieldset
        $objFieldset->addContent($objTable3->show());

        $form->addToForm($objFieldset->show());
        //Reset Fieldset
        $objFieldset->reset();

        // Create Save Button
        $button = new button("submit", $phraseSaveChanges);
        $button->setValue('savechanges');
        $button->setToSubmit();
        $btnSave = $button->showSexy();

        $button1 = new button("submit", $phraseSaveAsNewQn);
        $button1->setValue('saveasnew');
        //$button1->setToSubmit();
        $btnSaveAsnew = $button1->showSexy();

        // Create Cancel Button
        $buttonCancel = new button("submit", $this->objLanguage->languageText("word_cancel"));
        $objCancel = &$this->getObject("link", "htmlelements");
        $objCancel->link($this->uri(array(
                    'module' => 'mcqtests',
                    'action' => 'view',
                    'id' => $id
                )));
        $objCancel->link = $buttonCancel->showSexy();
        $btnCancel = $objCancel->show();

        //Add Save and Cancel Buttons to form
        $form->addToForm("<br />" . $btnSave . " " . $btnSaveAsnew . " " . $btnCancel . "<br />");

        return "<div>" . $form->show() . "</div>";
    }

}

?>