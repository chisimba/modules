<?php
	class editform extends object{
		public $objLanguage;		
		
		public function init(){
			//instantiate the laguage object
			$this->objLanguage = $this->getObject("language","language");
		}

		private function loadElements(){
			//Load the form class 
    			$this->loadClass("form","htmlelements");
    			//Load the textinput class 
		    	$this->loadClass("textinput","htmlelements");
			
			//Load the textarea class 
		    	$this->loadClass("textarea","htmlelements");
			//Load the text label class 
		    	$this->loadClass("label","htmlelements");
			//Load the button object 
		    	$this->loadClass("button","htmlelements");
			

		}

		private function buildForm(){
			//load form elements
			$this->loadElements();
			//create a form
			$objForm = new form("essay",$this->getFormAction());	
			        
		        //----------TEXT INPUT--------------
        		//Create a new textinput for the title of the essay
        		$objTitle = new textinput('essaytitle');
        		//Create a new label for the text labels
        		$essaytitleLabel = new label($this->objLanguage->languageText("mod_EFL_Essaytitle","EFL"),"essaytitle");
        		$objForm->addToForm($essaytitleLabel->show() . "<br />");
        		$objForm->addToForm($objTitle->show() . "<br />");


			//----------TEXTAREA--------------
        		//Create a new textarea for the essay text
				$objEssay = new textarea('essay','',150,140);
        		$essayLabel = new label($this->objLanguage->languageText("mod_EFL_Essaytxt","EFL"),"essay");
        		$objForm->addToForm($essayLabel->show() . "<br />");
        		$objForm->addToForm($objEssay->show() . "<br />");

			//----------SUBMIT BUTTON--------------
    			//Create a button for submitting the form
       			 $objButton = new button('save');
        		// Set the button type to submit
        		$objButton->setToSubmit();
        		// Use the language object to label button
        		// with the word save
        		$objButton->setValue(' '.$this->objLanguage->languageText("mod_EFL_saveessay", "EFL").' ');
        		$objForm->addToForm($objButton->show());
        
			return $objForm->show();


	
		}

		private function getFormAction(){

    			$action = $this->getParam("action", "add");
    			if ($action == "edit") {
        		$formAction = $this->uri(array("action" => "update"), "EFL");
    			} else {
        		$formAction = $this->uri(array("action" => "add"), "EFL");
    			 }
    			return $formAction;
		}

		public function show(){
		    return $this->buildForm();
		}


		
	}
?>
