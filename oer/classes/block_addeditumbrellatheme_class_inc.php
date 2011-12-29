<?php

/**
 * creates a gui for a new umbrella theme
 *
 * @author davidwaf
 */
$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('hiddeninput', 'htmlelements');
$this->loadClass('label', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('form', 'htmlelements');

class block_addeditumbrellatheme extends object {

    //the language object
    private $objLanguage;
    private $objUser;

    function init() {
        $this->objLanguage = $this->getObject('language', 'language');
        $this->objUser = $this->getObject('user', 'security');
    }

    function show() {
        return $this->createForm();
    }

    function createForm() {
        $objTable = $this->getObject('htmltable', 'htmlelements');
        $newumbrellatheme = new form('newproduct', $this->uri(array('action' => 'saveumbrellatheme')));

        $header = new htmlheading();
        $header->type = 2;
        $header->str = $this->objLanguage->languageText('mod_oer_createumbrellatheme', 'oer');

        $newumbrellatheme->addToForm($header->show());

        //the title
        $objTable->startRow();
        $objTable->addCell($this->objLanguage->languageText('mod_oer_title', 'oer'));
        $objTable->endRow();

        $objTable->startRow();

        $textinput = new textinput('title');
        $textinput->size = 60;
        if ($mode == 'edit') {
            $textinput->value = $product['title'];
        }
        if ($mode == "fixup") {
            $textinput->value = $title;
        }

        $objTable->addCell($textinput->show());
        $objTable->endRow();

        $newumbrellatheme->addToForm($objTable->show());

        $button = new button('save', $this->objLanguage->languageText('word_save', 'system', 'Save'));
        $button->setToSubmit();
        $newumbrellatheme->addToForm('<br/>' . $button->show());


        $button = new button('cancel', $this->objLanguage->languageText('word_cancel'));
        $uri = $this->uri(array("action"=>"viewthemes"));
        $button->setOnClick('javascript: window.location=\'' . $uri . '\'');
        $newumbrellatheme->addToForm('&nbsp;&nbsp;' . $button->show());



        return $newumbrellatheme->show();
    }

}

?>
