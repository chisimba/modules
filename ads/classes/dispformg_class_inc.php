<?php
	class dispformg extends object{
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
  		$header = new htmlheading($this->objLanguage->languageText('mod_ads_titleG','ads'), 2);
			$form = new form("signinForm", $this->submitAction);
			$form->addToForm($header->show() . "<br />");
			$textDescription = "<b>" . $this->objLanguage->languageText('mod_ads_g1a','ads') . "</b>";
      $text = new textarea('G1a',$this->formValue->getValue('G1a'),10, 75);
      $form->addToForm($textDescription . "<br />" . $text->show() . "<br />" . $this->formError->getError('G1a') . "<br />");
      
      $textDescription = "<b>" . $this->objLanguage->languageText('mod_ads_g1b','ads') . "</b>";
      $text = new textarea('G1b',$this->formValue->getValue('G1b'),10, 75);
      $form->addToForm($textDescription . "<br />" . $text->show() . "<br />" . $this->formError->getError('G1b') . "<br />");
      
      $textDescription = "<b>" . $this->objLanguage->languageText('mod_ads_g2a','ads') . "</b>";
      $text = new textarea('G2a',$this->formValue->getValue('G2a'),10, 75);
      $form->addToForm($textDescription . "<br />" . $text->show() . "<br />" . $this->formError->getError('G2a') . "<br />");
      
      $textDescription = "<b>" . $this->objLanguage->languageText('mod_ads_g2b','ads') . "</b>";
      $text = new textarea('G2b',$this->formValue->getValue('G2b'),10, 75);
      $form->addToForm($textDescription . "<br />" . $text->show() . "<br />" . $this->formError->getError('G2b') . "<br />");
      
      $textDescription = "<b>" . $this->objLanguage->languageText('mod_ads_g3a','ads') . "</b>";
      $text = new textarea('G3a',$this->formValue->getValue('G3a'),10, 75);
      $form->addToForm($textDescription . "<br />" . $text->show() . "<br />" . $this->formError->getError('G3a') . "<br />");
      
      $textDescription = "<b>" . $this->objLanguage->languageText('mod_ads_g3b','ads') . "</b>";
      $text = new textarea('G3b',$this->formValue->getValue('G3b'),10, 75);
      $form->addToForm($textDescription . "<br />" . $text->show() . "<br />" . $this->formError->getError('G3b') . "<br />");
      
      $textDescription = "<b>" . $this->objLanguage->languageText('mod_ads_g4a','ads') . "</b>";
      $text = new textarea('G4a',$this->formValue->getValue('G4a'),10, 75);
      $form->addToForm($textDescription . "<br />" . $text->show() . "<br />" . $this->formError->getError('G4a') . "<br />");
      
      $textDescription = "<b>" . $this->objLanguage->languageText('mod_ads_g4b','ads') . "</b>";
      $text = new textarea('G4b',$this->formValue->getValue('G4b'),10, 75);
      $form->addToForm($textDescription . "<br />" . $text->show() . "<br />" . $this->formError->getError('G4b') . "<br />");

      $nextButton = new button ('submitform', 'Next');
      $nextButton->setToSubmit();
      $saveButton = new button('saveform', 'Save');
      $saveButton->setId("saveBtn");
      $saveMsg = "<span id='saveMsg' style='padding-left: 10px;color:#F00;font-size: 12pt;'></span>";


      $form->addToForm($nextButton->show());
      $form->addToForm("&nbsp;".$saveButton->show());
      $form->addToForm($saveMsg);

			return $form->show();
		}// end getSectionEForm()
}

?>
