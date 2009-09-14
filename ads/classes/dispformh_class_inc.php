<?php
	class dispformh extends object{
		public function init() {
                    $this->objLanguage = $this->getObject("language", "language");
                    $this->loadElements();
		}// end init()
	
		public function setValues($formError, $formValue, $submitAction){
                    $this->formError = $formError;
                    $this->formValue = $formValue;
                    $this->submitAction = $submitAction;
                }
		
		public function loadElements() {
                    $this->loadClass("textinput", "htmlelements");
                    $this->loadClass("textarea", "htmlelements");
                    $this->loadClass("button", "htmlelements");
                    $this->loadClass('htmlheading', 'htmlelements');
		} // end loadElements()
		
		
		public function getForm() {
                    //put all form related stuff in here
                    $question1comment = "<div id = 'question1comment'></div>";
                    $question2acomment = "<div id = 'question2acomment'></div>";
                    $question2bcomment = "<div id = 'question2bcomment'></div>";
                    $question3acomment = "<div id = 'question3acomment'></div>";
                    $question3bcomment = "<div id = 'question3bcomment'></div>";
                    
                    $form = new form("signinForm", $this->submitAction);
                    $header = new htmlheading($this->objLanguage->languageText('mod_ads_titleH','ads'), 2);
                    $form->addToForm($header->show() . "<br />");
                    $textDescription = "<b>" . $this->objLanguage->languageText('mod_ads_h1','ads') . "</b>";
                    $text = new textarea('H1',$this->formValue->getValue('H1'),10, 75);
                    $form->addToForm($textDescription . "<br />" . $text->show() . "<br />" . $this->formError->getError('H1') . "<br />");
                    $form->addToForm($question1comment);

                    $textDescription = "<b>" . $this->objLanguage->languageText('mod_ads_h2a','ads') . "</b>";
                    $text = new textarea('H2a',$this->formValue->getValue('H2a'),10, 75);
                    $form->addToForm($textDescription . "<br />" . $text->show() . "<br />" . $this->formError->getError('H2a') . "<br />");
                    $form->addToForm($question2acomment);

                    $textDescription = "<b>" . $this->objLanguage->languageText('mod_ads_h2b','ads') . "</b>";
                    $text = new textarea('H2b',$this->formValue->getValue('H2b'),10, 75);
                    $form->addToForm($textDescription . "<br />" . $text->show() . "<br />" . $this->formError->getError('H2b') . "<br />");
                    $form->addToForm($question2bcomment);

                    $textDescription = "<b>" . $this->objLanguage->languageText('mod_ads_h3a','ads') . "</b>";
                    $text = new textarea('H3a',$this->formValue->getValue('H3a'),10, 75);
                    $form->addToForm($textDescription . "<br />" . $text->show() . "<br />" . $this->formError->getError('H3a') . "<br />");
                    $form->addToForm($question3acomment);

                    $textDescription ="<b>" . $this->objLanguage->languageText('mod_ads_h3b','ads') . "</b>";
                    $text = new textarea('H3b',$this->formValue->getValue('H3b'),10, 75);
                    $form->addToForm($textDescription . "<br />" . $text->show() . "<br />" . $this->formError->getError('H3b') . "<br />");
                    $form->addToForm($question3bcomment);
                    $form->addToForm("<br />");

                    $endButton = new button ('submitform', 'End');
                    $endButton->setToSubmit();
                    $saveButton = new button('saveform', 'Save');
                    $saveButton->setId("saveBtn");
                    $saveMsg = "<span id='saveMsg' style='padding-left: 10px;color:#F00;font-size: 12pt;'></span>";


                    $form->addToForm($endButton->show());
                    $form->addToForm("&nbsp;".$saveButton->show());
                    $form->addToForm($saveMsg);

                    return $form->show();
		}// end getSectionEForm()
}

?>