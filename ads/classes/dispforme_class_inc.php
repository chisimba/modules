<?php
	class dispforme extends object{
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
      $header = new htmlheading($this->objLanguage->languageText('mod_ads_titleE','ads'), 2);
			// prepare headings and input boxes
			$myForm = new form("signinForm", $this->submitAction);
			$q1a_heading = $this->getHeading("e1a");
			$q1a_input = $this->getInput("E1a");
			$q1b_heading = $this->getHeading("e1b");
                        $q1b_input = $this->getInput("E1b");
 			$q2a_heading = $this->getHeading("e2a");
                        $q2a_input = $this->getInput("E2a");
			

			// textarea
 			$q2b_heading = $this->getHeading("e2b");
                        $q2b_input = $this->getTextArea("E2b");
			$q2c_heading = $this->getHeading("e2c");
                        $q2c_input = $this->getTextArea("E2c");
                        

			// input text
			$q3a_heading = $this->getHeading("e3a");
                        $q3a_input = $this->getInput("E3a");
 			
			// textarea
			$q3b_heading = $this->getHeading("e3b");
                        $q3b_input = $this->getTextArea("E3b");
 			$q3c_heading = $this->getHeading("e3c");
                        $q3c_input = $this->getTextArea("E3c");
					

			$q4_heading = $this->getHeading("e4");
                        $q4_input = $this->getInput("E4");
                        $q5a_heading = $this->getHeading("e5a");
                        $q5a_input = $this->getInput("E5a");
			$q5b_heading = $this->getHeading("e5b");
                        $q5b_input = $this->getInput("E5b");

                        $mySubmit = $this->getButton("submit");


			// add elememts to the form
			$myForm->addToForm($header->show() . "<br />");
			$myForm->addToForm($q1a_heading);
			$myForm->addToForm("<br>");
			$myForm->addToForm($q1a_input);
			$myForm->addToForm($q1b_heading);
                        $myForm->addToForm("<br>");
                        $myForm->addToForm($q1b_input); 
			$myForm->addToForm($q2a_heading);
                        $myForm->addToForm("<br>");
                        $myForm->addToForm($q2a_input);
			$myForm->addToForm($q2b_heading);
                        $myForm->addToForm("<br>");
                        $myForm->addToForm($q2b_input);
			$myForm->addToForm($q2c_heading);
                        $myForm->addToForm("<br>");
                        $myForm->addToForm($q2c_input); 
                        $myForm->addToForm($q3a_heading);
                        $myForm->addToForm("<br>");
                        $myForm->addToForm($q3a_input);
                        $myForm->addToForm($q3b_heading);
                        $myForm->addToForm("<br>");
                        $myForm->addToForm($q3b_input);
                        $myForm->addToForm($q3c_heading);
                        $myForm->addToForm("<br>");
                        $myForm->addToForm($q3c_input);
			$myForm->addToForm($q4_heading);
                        $myForm->addToForm("<br>");
                        $myForm->addToForm($q4_input); 
                        $myForm->addToForm($q5a_heading);
                        $myForm->addToForm("<br>");
                        $myForm->addToForm($q5a_input);
                        $myForm->addToForm($q5b_heading);
                        $myForm->addToForm("<br>");
                        $myForm->addToForm($q5b_input);
                        $myForm->addToForm("<br>$mySubmit");
      
			return $myForm->show();
		}// emd getSectionEForm()
	
		public function getHeading($heading) {
			$myLabel = "mod_task2_".$heading;
			return "<b>" . $this->objLanguage->languageText($myLabel,"ads") . "</b>";
		}// end getq1a_heading()

		public function getInput($inputName) {
			$textinput = new textinput($inputName, $this->formValue->getValue($inputName));
        		// check if the input name is  2b, 2c, 3b, 3c
			if(strstr($inputName, "E2b") || strstr($inputName, "E2c")  || strstr($inputName, "E3b")  || strstr($inputName, "E3c")) {
				$textinput->size = 50;
			}
			else {
				$textinput->size = 15; 
        		}
			
			return  $textinput->show()  . "<br />" . $this->formError->getError($inputName) . "<br />";
		}// end getq1a_input()

		public function getTextArea($inputName) {
			
			$myTextArea = new textarea($inputName);
			$myTextArea->value = $this->formValue->getValue($inputName);
			$myTextArea->setRows(5);
			$myTextArea->setColumns('50');

			return $myTextArea->show()  . "<br />" . $this->formError->getError($inputName) . "<br />";
		} // end getTextArea()

		public function getButton($inputName) {
      $saveButton = new button ('submitform', 'Next');
      $saveButton->setToSubmit();
			return $saveButton->show();
		}

	} 
?>
