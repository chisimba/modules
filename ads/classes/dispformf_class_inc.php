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
		} // end loadElements()

	
		public function getForm() {
			$myForm = new form("signinForm", $this->uri(array("action"=>"submitForm")));		

			$q1a_heading = $this->getHeading("f1a");
			$q1a_radio = $this->getRadio("q1a");
			$q1b_heading = $this->getHeading("f1b");
                        $q1b_input = $this->getTextArea("q1b");
			$q2a_heading = $this->getHeading("f2a");
                        $q2a_radio = $this->getRadio("q2a");
			$q2b_heading = $this->getHeading("f2b");
                        $q2b_input = $this->getTextArea("q2b");
			$q3a_heading = $this->getHeading("f3a");
                        $q3a_input = $this->getTextArea("q3a");
			$q3b_heading = $this->getHeading("f3b");
                        $q3b_input = $this->getTextArea("q3b");
			$q4_heading = $this->getHeading("f4");
                        $q4_input = $this->getTextArea("q4");
			$cancel = $this->getButton("cancel");
			$mySubmit = $this->getButton("submit");
			
			$myForm->addToForm($q1a_heading);
			$myForm->addToForm("<br>");
			$myForm->addToForm($q1a_radio);
			$myForm->addToForm("<br /><br />");			
			$myForm->addToForm($q1b_heading);
                        $myForm->addToForm("<br>");
                        $myForm->addToForm($q1b_input);
                        $myForm->addToForm("<br><br>");
			$myForm->addToForm($q2a_heading);
                        $myForm->addToForm("<br>");
                        $myForm->addToForm($q2a_radio);
			$myForm->addToForm("<br /><br />");
			$myForm->addToForm($q2b_heading);
                        $myForm->addToForm("<br>");
                        $myForm->addToForm($q2b_input);
			$myForm->addToForm("<br /><br />");
			$myForm->addToForm($q3a_heading);
                        $myForm->addToForm("<br>");
                        $myForm->addToForm($q3a_input);
			$myForm->addToForm("<br /><br />");
			$myForm->addToForm($q3b_heading);
                        $myForm->addToForm("<br>");
                        $myForm->addToForm($q3b_input);
			$myForm->addToForm("<br /><br />");
			$myForm->addToForm($q4_heading);
                        $myForm->addToForm("<br />");
                        $myForm->addToForm($q4_input);
			$myForm->addToForm("<br /><br />".$cancel."&nbsp;");
			$myForm->addToForm($mySubmit);
			
			return $myForm->show();
		}// emd getSectionEForm()
	
		public function getHeading($heading) {
			$myLabel = "mod_task2_".$heading;
			return $this->objLanguage->languageText($myLabel,"ads");
		}// end getq1a_heading()

		public function getInput($inputName) {

			$inputName .= "_input";
			$textinput = new textinput($inputName);
        		// check if the input name is  2b, 2c, 3b, 3c
			if(strstr($inputName, "2b") || strstr($inputName, "2c")  || strstr($inputName, "3b")  || strstr($inputName, "3c")) {
				$textinput->size = 50;
			}
			else {
				$textinput->size = 15; 
        		}
			
			return  $textinput->show();
		}// end getq1a_input()
		
		public function getRadio($radioName) {
			$radioName .= "_radio";

			//Radio button Group
			$objElement = new radio($radioName);
	                $objElement->addOption('y','Yes');
		        $objElement->addOption('n','No');
                	$objElement->setSelected('y');
	               	
			return $objElement->show();
		} // end getRadio()


		public function getTextArea($inputName) {
			$inputName .= "_textarea";
			
			$myTextArea = new textarea($inputName);
			$myTextArea->setRows(5);
			$myTextArea->setColumns('50');

			return $myTextArea->show();
		} // end getTextArea()

		public function getButton($inputName) {
			$myButton = new button($inputName);
             		
			if(strstr($inputName, "submit")) {
				$myButton->setValue('Submit');
				$myButton->setOnClick('alert(\'Hello Submit\')');
				$myButton->setToSubmit();  //If you want to make the button a submit button
			}
			else if(strstr($inputName, "cancel")) {
				$myButton->setValue('cancel');
				$myButton->setOnClick('alert(\'Hello Cancel\')');
			}
			return $myButton->show();
		}
	} 
?>
