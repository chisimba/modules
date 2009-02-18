<?php

class editform extends object {
    public $objLanguage;

    public function init() {
        // language object.
        $this->objLanguage = $this->getObject ( 'language', 'language' );
    }

    private function loadElements() {
        // Load the form class
        $this->loadClass ( 'form', 'htmlelements' );
        // Load the textinput class
        $this->loadClass ( 'textinput', 'htmlelements' );
        // Load the label class
        $this->loadClass ( 'label', 'htmlelements' );
        // Load the textarea class
        $this->loadClass ( 'textarea', 'htmlelements' );
        // Load the button object
        $this->loadClass ( 'button', 'htmlelements' );
        // Load dropdown class
        $this->loadClass ( 'dropdown', 'htmlelements' );
    }

    public function buildForm($catarr) {
        // Load the required form elements that we need
        $this->loadElements ();
        // Create the form
        $objForm = new form ( 'comments', $this->uri ( array ("action" => "add" ) ) );
        $objHeadingLabel = new label ( $this->objLanguage->languageText ( "mod_computerscience_heading", "computerscience" ), "heading" );
        $objForm->addToForm ( "&nbsp;<h1>" . $objHeadingLabel->show () . "</h1>" );

        $gtable = $this->newObject('htmltable', 'htmlelements');
        $gtable->cellpadding = 3;
        $gtable->startRow();

        // description labels - fix!
        $objDescriptionOne = new label ( $this->objLanguage->languageText ( "mod_computerscience_descriptionone", "computerscience" ), "DecriptionOne" );
        $objDescriptionTwo = new label ( $this->objLanguage->languageText ( "mod_computerscience_descriptiontwo", "computerscience" ), "DecriptionTwo" );
        $objForm->addToForm ( $objDescriptionOne->show () . "<br />" );
        $objForm->addToForm ( $objDescriptionTwo->show () . "<br /><br />" );

        // Category One
        $lblCategoryOne = new label ( $this->objLanguage->languageText ( "mod_computerscience_categoryone", "computerscience" ), "categoryOne" );
        $objForm->addToForm ( "<b>" . $lblCategoryOne->show () . "</b><br />" );

        $lblPattern = new label ( $this->objLanguage->languageText ( "mod_computerscience_pattern", "computerscience" ), "pattern" );
        $lblTemplate = new label ( $this->objLanguage->languageText ( "mod_computerscience_template", "computerscience" ), "template" );
        $lblSrai = new label ( $this->objLanguage->languageText ( "mod_computerscience_that", "computerscience" ), "srai" );
        $txtPatternOne = new textinput ( 'txtPatternOne' );
        $txtSrai = new dropdown ( 'txtThatOne' );

        $count = 0;

        foreach($catarr as $cats) {
            $txtSrai->addOption($count, $cats);
            $count++;
        }
        $txtSrai->addOption();
        $txtTemplateOne = new textarea ( 'txtTemplateOne' );
        $txtTemplateOne->value = $this->getParam ( 'txtTemplateOne' );

        // layout the form nicely
        $gtable->addCell($lblPattern->show());
        $gtable->addCell($txtPatternOne->show());
        $gtable->endRow();
        $gtable->startRow();

        // that
        $gtable->addCell($lblSrai->show());
        $gtable->addCell($txtSrai->show());
        $gtable->endRow();

        $gtable->startRow();
        // template
        $gtable->addCell($lblTemplate->show());
        $gtable->addCell($txtTemplateOne->show());
        $gtable->endRow();

        $objForm->addToForm ($gtable->show());

        // Create a button for submitting the form
        $objButton = new button ( 'save' );
        // Set the button type to submit
        $objButton->setToSubmit ();
        // Use the language object to label button
        // with the word save
        $objButton->setValue ( ' ' . $this->objLanguage->languageText ( "mod_computerscience_savecomment", "computerscience" ) . ' ' );
        $objForm->addToForm ( $objButton->show () );

        return $objForm->show ();
    }

    private function getFormAction() {
        $action = $this->getParam ( "action", "add" );
        if ($action == "edit") {
            $formAction = $this->uri ( array ("action" => "update" ), "computerscience" );
        } else {
            $formAction = $this->uri ( array ("action" => "add" ), "computerscience" );
        }
        return $formAction;
    }

    public function show() {
        return $this->buildForm ();
    }

    public function uMenu() {
        return "This will be a menu to add/edit/delete patterns, as well as a way for admin users to reload the bot with the files.";
    }
}
?>