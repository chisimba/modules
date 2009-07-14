<?php
	class dispforme extends object{
		public function init() {
  		echo $this->submitAction;
			$this->objLanguage = $this->getObject("language", "language");
			$this->loadElements();
		}// end init()
	
		public function loadElements() {
			$this->loadClass("textinput", "htmlelements");
			$this->loadClass("textarea", "htmlelements");
			$this->loadClass("button", "htmlelements");
		} // end loadElements()

	
		public function getForm() {

			// prepare headings and input boxes
			$myForm = new form("signinForm", $this->uri(array("action"=>"submitForm")));
			$q1a_heading = $this->getHeading("e1a");
			$q1a_input = $this->getInput("q1a");
			$q1b_heading = $this->getHeading("e1b");
                        $q1b_input = $this->getInput("q1b");
 			$q2a_heading = $this->getHeading("e2a");
                        $q2a_input = $this->getInput("q2a");
			

			// textarea
 			$q2b_heading = $this->getHeading("e2b");
                        $q2b_input = $this->getTextArea("q2b");
			$q2c_heading = $this->getHeading("e2c");
                        $q2c_input = $this->getTextArea("q2c");
                        

			// input text
			$q3a_heading = $this->getHeading("e3a");
                        $q3a_input = $this->getInput("q3a");
 			
			// textarea
			$q3b_heading = $this->getHeading("e3b");
                        $q3b_input = $this->getTextArea("q3b");
 			$q3c_heading = $this->getHeading("e3c");
                        $q3c_input = $this->getTextArea("q3c");
					

			$q4_heading = $this->getHeading("e4");
                        $q4_input = $this->getInput("q4");
                        $q5a_heading = $this->getHeading("e5a");
                        $q5a_input = $this->getInput("q5a");
			$q5b_heading = $this->getHeading("e5b");
                        $q5b_input = $this->getInput("q5b");

			// get the form buttons
			$cancel = $this->getButton("cancel");
                        $mySubmit = $this->getButton("submit");


			// add elememts to the form
			$myForm->addToForm($q1a_heading);
			$myForm->addToForm("<br>");
			$myForm->addToForm($q1a_input);
			$myForm->addToForm("<br><br>");
			$myForm->addToForm($q1b_heading);
                        $myForm->addToForm("<br>");
                        $myForm->addToForm($q1b_input); 
                        $myForm->addToForm("<br><br>");
			$myForm->addToForm($q2a_heading);
                        $myForm->addToForm("<br>");
                        $myForm->addToForm($q2a_input);
			$myForm->addToForm("<br><br>");
			$myForm->addToForm($q2b_heading);
                        $myForm->addToForm("<br>");
                        $myForm->addToForm($q2b_input);
			$myForm->addToForm("<br><br>");
			$myForm->addToForm($q2c_heading);
                        $myForm->addToForm("<br>");
                        $myForm->addToForm($q2c_input); 
			$myForm->addToForm("<br><br>");
                        $myForm->addToForm($q3a_heading);
                        $myForm->addToForm("<br>");
                        $myForm->addToForm($q3a_input);
			$myForm->addToForm("<br><br>");
                        $myForm->addToForm($q3b_heading);
                        $myForm->addToForm("<br>");
                        $myForm->addToForm($q3b_input);
			$myForm->addToForm("<br><br>");
                        $myForm->addToForm($q3c_heading);
                        $myForm->addToForm("<br>");
                        $myForm->addToForm($q3c_input);
			$myForm->addToForm("<br><br>");
			$myForm->addToForm($q4_heading);
                        $myForm->addToForm("<br>");
                        $myForm->addToForm($q4_input); 
			$myForm->addToForm("<br><br>");
                        $myForm->addToForm($q5a_heading);
                        $myForm->addToForm("<br>");
                        $myForm->addToForm($q5a_input);
			$myForm->addToForm("<br><br>");
                        $myForm->addToForm($q5b_heading);
                        $myForm->addToForm("<br>");
                        $myForm->addToForm($q5b_input);
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
                                $myButton->setValue('Next '); //originally submit
                               // $myButton->setOnClick('alert(\'Hello Submit\')');
                                $myButton->setToSubmit();  //If you want to make the button a submit button
                        }
                        else if(strstr($inputName, "cancel")) {
                                $myButton->setValue('cancel');
                                //$myButton->setOnClick('alert(\'Hello Cancel\')');
                        }
                        return $myButton->show();
                }

	} 
?>
