<?php

    class editform extends object
    {
        public $objLanguage;
        
        public function init()
        {
            // language object.
            $this->objLanguage = $this->getObject('language', 'language');
        }
        
        private function loadElements()
        {
            //Load the form class
            #                 class, module
            $this->loadClass('form','htmlelements');
            //Load the textinput class
            $this->loadClass('textinput','htmlelements');            
            //Load the label class
            $this->loadClass('label', 'htmlelements');
            //Load the textarea class
            $this->loadClass('textarea','htmlelements');
            //Load the button object
            $this->loadClass('button', 'htmlelements');
            //Load dropdown class
            $this->loadClass('dropdown', 'htmlelements');
        }
        
        private function buildForm()
        {
            //Load the required form elements that we need
            $this->loadElements();
            //Create the form
            #------------------------------------------------------
            # another way to create a form and set the action
            # $form = new form ('register', $this->uri(array('action'=>'register')));
            #------------------------------------------------------
            #$objForm = new form('comments', $this->getFormAction());
            $objForm = new form('comments', $this->uri(array("action" => "add")));
            # form action will be           $this->uri(array("action" => "add"),            
            $objHeadingLabel = new label($this->objLanguage->languageText("mod_computerscience_heading","computerscience"), "heading");
            $objForm->addToForm("&nbsp;<h1>".$objHeadingLabel->show() . "</h1>");
            
            # description labels
            $objDescriptionOne = new label($this->objLanguage->languageText("mod_computerscience_descriptionone","computerscience"), "DecriptionOne");
            $objDescriptionTwo = new label($this->objLanguage->languageText("mod_computerscience_descriptiontwo","computerscience"), "DecriptionTwo");
            $objForm->addToForm("&nbsp;". $objDescriptionOne->show() . "<br />");            
            $objForm->addToForm("&nbsp;". $objDescriptionTwo->show() . "<br /><br />");
            
            # Category One
            $lblCategoryOne = new label($this->objLanguage->languageText("mod_computerscience_categoryone","computerscience"), "categoryOne");
            $objForm->addToForm("&nbsp;<b>". $lblCategoryOne->show() . "</b><br />");
            $lblPattern = new label($this->objLanguage->languageText("mod_computerscience_pattern","computerscience"), "pattern");
            $lblTemplate = new label($this->objLanguage->languageText("mod_computerscience_template","computerscience"), "template");
            $lblThat = new label($this->objLanguage->languageText("mod_computerscience_that","computerscience"), "that");
            $objForm->addToForm("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;". $lblPattern->show());
            $txtPatternOne = new textinput('txtPatternOne');
            $txtPatternOne->value = $this->getParam('txtPatternOne');
            $objForm->addToForm("&nbsp;&nbsp;&nbsp;&nbsp;". $txtPatternOne->show() . "<br />");
            $objForm->addToForm("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;". $lblThat->show());
            $txtThatOne = new textinput('txtThatOne');
            $txtThatOne->value = $this->getParam('txtThatOne');
            $objForm->addToForm("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;". $txtThatOne->show(). "<br />");   
            $objForm->addToForm("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;". $lblTemplate->show());
            $txtTemplateOne = new textarea('txtTemplateOne');
            $txtTemplateOne->value = $this->getParam('txtTemplateOne');
            $objForm->addToForm("&nbsp;". $txtTemplateOne->show(). "<br /><br /><hr width=60% />");            
            
            # Category Two
            $lblCategoryTwo = new label($this->objLanguage->languageText("mod_computerscience_categorytwo","computerscience"), "categoryTwo");
            $objForm->addToForm("&nbsp;<b>".  $lblCategoryTwo->show() . "</b><br />");
            $objForm->addToForm("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;". $lblPattern->show());
            $txtPatternTwo = new textinput('txtPatternTwo');
            $txtPatternTwo->value = $this->getParam('txtPatternTwo');
            $objForm->addToForm("&nbsp;&nbsp;&nbsp;&nbsp;". $txtPatternTwo->show() . "<br />");
            $objForm->addToForm("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;". $lblThat->show());
            $txtThatTwo = new textinput('txtThatTwo');
            $txtThatTwo->value = $this->getParam('txtThatTwo');
            $objForm->addToForm("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;". $txtThatTwo->show(). "<br />");
            $objForm->addToForm("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;". $lblTemplate->show());
            $txtTemplateTwo = new textarea('txtTemplateTwo');
            $txtTemplateTwo->value = $this->getParam('txtTemplateTwo');
            $objForm->addToForm("&nbsp;". $txtTemplateTwo->show()."<br /><br /><hr width=60% />");
            
            # Category Three
            $lblCategoryThree = new label($this->objLanguage->languageText("mod_computerscience_categorythree","computerscience"), "categoryThree");
            $objForm->addToForm("&nbsp;<b>". $lblCategoryThree->show() . "</b><br />");
            $objForm->addToForm("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;". $lblPattern->show());
            $txtPatternThree = new textinput('txtPatternThree');
            $txtPatternThree->value = $this->getParam('txtPatternThree');
            $objForm->addToForm("&nbsp;&nbsp;&nbsp;&nbsp;". $txtPatternThree->show() . "<br />");
            $objForm->addToForm("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;". $lblThat->show());
            $txtThatThree = new textinput('txtThatThree');
            $txtThatThree->value = $this->getParam('txtThatThree');
            $objForm->addToForm("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;". $txtThatThree->show(). "<br />");
            $objForm->addToForm("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;". $lblTemplate->show());
            $txtTemplateThree = new textarea('txtTemplateThree');
            $txtTemplateThree->value = $this->getParam('txtTemplateThree');
            $objForm->addToForm("&nbsp;". $txtTemplateThree->show(). "<br /><br /><hr width=60% />");
            
            # Category Four
            $lblCategoryFour = new label($this->objLanguage->languageText("mod_computerscience_categoryfour","computerscience"), "categoryfour");
            $objForm->addToForm("&nbsp;<b>". $lblCategoryFour->show() . "</b><br />");
            $objForm->addToForm("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;". $lblPattern->show());
            $txtPatternFour = new textinput('txtPatternFour');
            $txtPatternFour->value = $this->getParam('txtPatternFour');
            $objForm->addToForm("&nbsp;&nbsp;&nbsp;&nbsp;". $txtPatternFour->show() . "<br />");
            $objForm->addToForm("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;". $lblThat->show());
            $txtThatFour = new textinput('txtThatFour');
            $txtThatFour->value = $this->getParam('txtThatFour');
            $objForm->addToForm("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;". $txtThatFour->show(). "<br />");
            $objForm->addToForm("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;". $lblTemplate->show());
            $txtTemplateFour = new textarea('txtTemplateFour');
            $txtTemplateFour->value = $this->getParam('txtTemplateFour');
            $objForm->addToForm("&nbsp;". $txtTemplateFour->show(). "<br /><br /><hr width=60% />");
            
            # Category Five
            $lblCategoryFive = new label($this->objLanguage->languageText("mod_computerscience_categoryfive","computerscience"), "categoryfive");
            $objForm->addToForm("&nbsp;<b>". $lblCategoryFive->show() . "</b><br />");
            $objForm->addToForm("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;". $lblPattern->show());
            $txtPatternFive = new textinput('txtPatternFive');
            $txtPatternFive->value = $this->getParam('txtPatternFive');
            $objForm->addToForm("&nbsp;&nbsp;&nbsp;&nbsp;". $txtPatternFive->show() . "<br />");
            $objForm->addToForm("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;". $lblThat->show());
            $txtThatFive = new textinput('txtThatFive');
            $txtThatFive->value = $this->getParam('txtThatFive');
            $objForm->addToForm("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;". $txtThatFive->show(). "<br />");
            $objForm->addToForm("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;". $lblTemplate->show());
            $txtTemplateFive = new textarea('txtTemplateFive');
            $txtTemplateFive->value = $this->getParam('txtTemplateFive');
            $objForm->addToForm("&nbsp;". $txtTemplateFive->show(). "<br /><br /><hr width=60% />");
            
            # Category Six
            $lblCategorySix = new label($this->objLanguage->languageText("mod_computerscience_categorysix","computerscience"), "categorysix");
            $objForm->addToForm("&nbsp;<b>". $lblCategorySix->show() . "</b><br />");
            $objForm->addToForm("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;". $lblPattern->show());
            $txtPatternSix = new textinput('txtPatternSix');
            $txtPatternSix->value = $this->getParam('txtPatternSix');
            $objForm->addToForm("&nbsp;&nbsp;&nbsp;&nbsp;". $txtPatternSix->show() . "<br />");
            $objForm->addToForm("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;". $lblThat->show());
            $txtThatSix = new textinput('txtThatSix');
            $txtThatSix->value = $this->getParam('txtThatSix');
            $objForm->addToForm("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;". $txtThatSix->show(). "<br />");
            $objForm->addToForm("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;". $lblTemplate->show());
            $txtTemplateSix = new textarea('txtTemplateSix');
            $txtTemplateSix->value = $this->getParam('txtTemplateSix');
            $objForm->addToForm("&nbsp;". $txtTemplateSix->show(). "<br /><br />");
            

            //----------SUBMIT BUTTON--------------
            //Create a button for submitting the form
            $objButton = new button('save');
            // Set the button type to submit
            $objButton->setToSubmit();
            // Use the language object to label button
            // with the word save
            $objButton->setValue(' '.$this->objLanguage->languageText("mod_computerscience_savecomment", "computerscience").' ');
            $objForm->addToForm("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".
            "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".
            "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;". $objButton->show());
            # returning form
            return $objForm->show();
        }
        
        private function getFormAction()
        {            
            $action = $this->getParam("action", "add");
            if ($action == "edit") {
                $formAction = $this->uri(array("action" => "update"),
            "computerscience");
            } else {
                $formAction = $this->uri(array("action" => "add"),
            "computerscience");
            }
            return $formAction;
        }
        
        public function show()
        {
              return $this->buildForm();
        }
    }
?>