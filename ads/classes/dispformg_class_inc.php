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
		} // end loadElements()
		
		
		public function getForm() {
  		//put all form related stuff in here
  		
			$form = new form("signinForm", $this->submitAction);
			
			$textDescription = $this->objLanguage->languageText('mod_ads_g1a','ads');
      $text = new textarea('G1a',$this->formValue->getValue('G1a'),5,70);
      $form->addToForm($textDescription . "<br />" . $text->show() . "<br />" . $this->formError->getError('G1a') . "<br />");
      
      $textDescription = $this->objLanguage->languageText('mod_ads_g1b','ads');
      $text = new textarea('G1b',$this->formValue->getValue('G1b'),5,70);
      $form->addToForm($textDescription . "<br />" . $text->show() . "<br />" . $this->formError->getError('G1b') . "<br />");
      
      $textDescription = $this->objLanguage->languageText('mod_ads_g2a','ads');
      $text = new textarea('G2a',$this->formValue->getValue('G2a'),5,70);
      $form->addToForm($textDescription . "<br />" . $text->show() . "<br />" . $this->formError->getError('G2a') . "<br />");
      
      $textDescription = $this->objLanguage->languageText('mod_ads_g2b','ads');
      $text = new textarea('G2b',$this->formValue->getValue('G2b'),5,70);
      $form->addToForm($textDescription . "<br />" . $text->show() . "<br />" . $this->formError->getError('G2b') . "<br />");
      
      $textDescription = $this->objLanguage->languageText('mod_ads_g3a','ads');
      $text = new textarea('G3a',$this->formValue->getValue('G3a'),5,70);
      $form->addToForm($textDescription . "<br />" . $text->show() . "<br />" . $this->formError->getError('G3a') . "<br />");
      
      $textDescription = $this->objLanguage->languageText('mod_ads_g3b','ads');
      $text = new textarea('G3b',$this->formValue->getValue('G3b'),5,70);
      $form->addToForm($textDescription . "<br />" . $text->show() . "<br />" . $this->formError->getError('G3b') . "<br />");
      
      $textDescription = $this->objLanguage->languageText('mod_ads_g4a','ads');
      $text = new textarea('G4a',$this->formValue->getValue('G4a'),5,70);
      $form->addToForm($textDescription . "<br />" . $text->show() . "<br />" . $this->formError->getError('G4a') . "<br />");
      
      $textDescription = $this->objLanguage->languageText('mod_ads_g4b','ads');
      $text = new textarea('G4b',$this->formValue->getValue('G4b'),5,70);
      $form->addToForm($textDescription . "<br />" . $text->show() . "<br />" . $this->formError->getError('G4b') . "<br />");

      $saveButton = new button ('submitform', 'Next');
      $saveButton->setToSubmit();


      $form->addToForm($saveButton->show());

			return $form->show();
		}// end getSectionEForm()
}

?>
