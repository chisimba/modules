<?php
	class dispformf extends object{
		public function init() {
			$this->objLanguage = $this->getObject("language", "language");
			$this->loadElements();
		}// end init()
	
		public function loadElements() {
			$this->loadClass("textinput", "htmlelements");
			$this->loadClass("textarea", "htmlelements");
			$this->loadClass("radio", "htmlelements");
			$this->loadClass("button", "htmlelements");
			$this->loadClass('htmlheading', 'htmlelements');
		} // end loadElements()

	  public function setValues($formError, $formValue, $submitAction){
		  $this->formError = $formError;
		  $this->formValue = $formValue;
		  $this->submitAction = $submitAction;
	  }
		
		public function getForm() {
			$myForm = new form("signinForm", $this->submitAction);		
      $header = new htmlheading($this->objLanguage->languageText('mod_ads_titleF','ads'), 2);
			$q1a_heading = $this->getHeading("f1a");
			$q1a_radio = $this->getRadio("F1a");
			$q1b_heading = $this->getHeading("f1b");
                        $q1b_input = $this->getTextArea("F1b");
			$q2a_heading = $this->getHeading("f2a");
                        $q2a_radio = $this->getRadio("F2a");
			$q2b_heading = $this->getHeading("f2b");
                        $q2b_input = $this->getTextArea("F2b");
			$q3a_heading = $this->getHeading("f3a");
                        $q3a_input = $this->getTextArea("F3a");
			$q3b_heading = $this->getHeading("f3b");
                        $q3b_input = $this->getTextArea("F3b");
			$q4_heading = $this->getHeading("f4");
                        $q4_input = $this->getTextArea("F4");
			$mySubmit = $this->getButton("submit");
                        $saveButton = new button('saveform', 'Save');
                        $saveButton->setId("saveBtn");
                        $saveMsg = "<span id='saveMsg' style='padding-left: 10px;color:#F00;font-size: 12pt;'></span>";
			
			$myForm->addToForm($header->show() . "<br />");
			$myForm->addToForm($q1a_heading);
			$myForm->addToForm("<br>");
			$myForm->addToForm($q1a_radio);
			$myForm->addToForm("<br>");
			$myForm->addToForm("<br />");
			$myForm->addToForm($q1b_heading);
                        $myForm->addToForm("<br>");
                        $myForm->addToForm($q1b_input);
			$myForm->addToForm($q2a_heading);
                        $myForm->addToForm("<br>");
                        $myForm->addToForm($q2a_radio);
                        $myForm->addToForm("<br />");
                        $myForm->addToForm("<br />");
			$myForm->addToForm($q2b_heading);
                        $myForm->addToForm("<br>");
                        $myForm->addToForm($q2b_input);
			$myForm->addToForm($q3a_heading);
                        $myForm->addToForm("<br>");
                        $myForm->addToForm($q3a_input);
			$myForm->addToForm($q3b_heading);
                        $myForm->addToForm("<br>");
                        $myForm->addToForm($q3b_input);
			$myForm->addToForm($q4_heading);
                        $myForm->addToForm("<br />");
                        $myForm->addToForm($q4_input);
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
			return  $textinput->show()  . "<br />" . $this->formError->getError($inputName) . "<br />";
		}// end getq1a_input()

		public function getTextArea($inputName) {
			
			$myTextArea = new textarea($inputName);
			$myTextArea->value = $this->formValue->getValue($inputName);
			$myTextArea->setRows(5);
			$myTextArea->setColumns('50');

			return $myTextArea->show()  . "<br />" . $this->formError->getError($inputName) . "<br />";
		} // end getTextArea()
		
		public function getRadio($radioName) {

			//Radio button Group
			$objElement = new radio($radioName);
	                $objElement->addOption('y','Yes');
		        $objElement->addOption('n','No');
		        $objElement->setTableColumns(1);
		        if ($this->formValue->getValue($radioName) == "") {
              $objElement->setSelected('y');
            }
            else {
              $objElement->setSelected($this->formValue->getValue($radioName));
            }
			return $objElement->show();
		} // end getRadio()

		public function getButton($inputName) {
      $saveButton = new button ('submitform', 'Next');
      $saveButton->setToSubmit();
			return $saveButton->show();
		}
	} 
?>
