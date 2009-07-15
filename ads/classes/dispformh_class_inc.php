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
		} // end loadElements()
		
		
		public function getForm() {
  		//put all form related stuff in here
  		
			$form = new form("signinForm", $this->submitAction);
			
			$textDescription = $this->objLanguage->languageText('mod_ads_h1','ads');
      $text = new textarea('H1',$this->formValue->getValue('H1'),5,70);
      $form->addToForm($textDescription . "<br />" . $text->show() . "<br />" . $this->formError->getError('H1') . "<br /><br />");
      
      $textDescription = $this->objLanguage->languageText('mod_ads_h2a','ads');
      $text = new textarea('H2a',$this->formValue->getValue('H2a'),5,70);
      $form->addToForm($textDescription . "<br />" . $text->show() . "<br />" . $this->formError->getError('H2a') . "<br /><br />");
      
      $textDescription = $this->objLanguage->languageText('mod_ads_h2b','ads');
      $text = new textarea('H2b',$this->formValue->getValue('H2b'),5,70);
      $form->addToForm($textDescription . "<br />" . $text->show() . "<br />" . $this->formError->getError('H2b') . "<br /><br />");
      
      $textDescription = $this->objLanguage->languageText('mod_ads_h3a','ads');
      $text = new textarea('H3a',$this->formValue->getValue('H3a'),5,70);
      $form->addToForm($textDescription . "<br />" . $text->show() . "<br />" . $this->formError->getError('H3a') . "<br /><br />");
      
      $textDescription = $this->objLanguage->languageText('mod_ads_h3b','ads');
      $text = new textarea('H3b',$this->formValue->getValue('H3b'),5,70);
      $form->addToForm($textDescription . "<br />" . $text->show() . "<br />" . $this->formError->getError('H3b') . "<br /><br />");

      $saveButton = new button ('submitform', 'Next');
      $saveButton->setToSubmit();


      $form->addToForm($saveButton->show());

			return $form->show();
		}// emd getSectionEForm()
}

?>