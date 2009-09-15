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
            $question1acomment = "<div id = 'question1acomment'></div>";
            $question1bcomment = "<div id = 'question1bcomment'></div>";
            $question2acomment = "<div id = 'question2acomment'></div>";
            $question2bcomment = "<div id = 'question2bcomment'></div>";
            $question2ccomment = "<div id = 'question2ccomment'></div>";
            $question3acomment = "<div id = 'question3acomment'></div>";
            $question3bcomment = "<div id = 'question3bcomment'></div>";
            $question3ccomment = "<div id = 'question3ccomment'></div>";
            $question4comment = "<div id = 'question4comment'></div>";
            $question5acomment = "<div id = 'question5acomment'></div>";
            $question5bcomment = "<div id = 'question5bcomment'></div>";

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
            $q2b_input = $this->getTextArea("E2b") ;
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
            $saveButton = new button('saveform', 'Save');
            $saveButton->setId("saveBtn");
            $saveMsg = "<span id='saveMsg' style='padding-left: 10px;color:#F00;font-size: 12pt;'></span>";


            // add elememts to the form
            $myForm->addToForm($header->show() . "<br/>");
            $myForm->addToForm($q1a_heading);
            $myForm->addToForm("<br>");
            $myForm->addToForm($q1a_input);
            $myForm->addToForm("");
            $myForm->addToForm($question1acomment);
            $myForm->addToForm($q1b_heading);
            $myForm->addToForm("<br>");
            $myForm->addToForm($q1b_input);
            $myForm->addToForm($question1bcomment);
            $myForm->addToForm($q2a_heading);
            $myForm->addToForm("<br>");
            $myForm->addToForm($q2a_input);
            $myForm->addToForm($question2acomment);
            $myForm->addToForm($q2b_heading);
            $myForm->addToForm("<br>");
            $myForm->addToForm($q2b_input);
            $myForm->addToForm($question2bcomment);
            $myForm->addToForm($q2c_heading);
            $myForm->addToForm("<br>");
            $myForm->addToForm($q2c_input);
            $myForm->addToForm($question2ccomment);
            $myForm->addToForm($q3a_heading);
            $myForm->addToForm("<br>");
            $myForm->addToForm($q3a_input);
            $myForm->addToForm($question3acomment);
            $myForm->addToForm($q3b_heading);
            $myForm->addToForm("<br>");
            $myForm->addToForm($q3b_input);
            $myForm->addToForm($question3bcomment);
            $myForm->addToForm($q3c_heading);
            $myForm->addToForm("<br>");
            $myForm->addToForm($q3c_input);
            $myForm->addToForm($question3ccomment);
            $myForm->addToForm($q4_heading);
            $myForm->addToForm("<br>");
            $myForm->addToForm($q4_input);
            $myForm->addToForm($question4comment);
            $myForm->addToForm($q5a_heading);
            $myForm->addToForm("<br>");
            $myForm->addToForm($q5a_input);
            $myForm->addToForm($question5acomment);
            $myForm->addToForm($q5b_heading);
            $myForm->addToForm("<br>");
            $myForm->addToForm($q5b_input);
            $myForm->addToForm($question5bcomment);
            $myForm->addToForm("<br>$mySubmit");
            $myForm->addToForm("&nbsp;".$saveButton->show());
            $myForm->addToForm($saveMsg);

      
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
				$textinput->size = 75;
			}
			else {
				$textinput->size = 75;
        		}
			
			return  $textinput->show()."<br />" . $this->formError->getError($inputName) . "<br />";
		}// end getq1a_input()

		public function getTextArea($inputName) {
			
			$myTextArea = new textarea($inputName);
			$myTextArea->value = $this->formValue->getValue($inputName);
			$myTextArea->setRows(10);
			$myTextArea->setColumns('75');

			return $myTextArea->show()."<br />" . $this->formError->getError($inputName) . "<br />";
		} // end getTextArea()

		public function getButton($inputName) {
            $saveButton = new button ('submitform', 'Next');
            $saveButton->setToSubmit();
			return $saveButton->show();
		}

	} 
?>
