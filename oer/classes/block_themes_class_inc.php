<?php

/**
 * Lists current product themes
 *
 * @author davidwaf
 */
$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('fieldset', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('textarea', 'htmlelements');
$this->loadClass('hiddeninput', 'htmlelements');
$this->loadClass('label', 'htmlelements');
$this->loadClass('checkbox', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('form', 'htmlelements');
$this->loadClass('radio', 'htmlelements');
$this->loadClass('dropdown', 'htmlelements');

class block_themes extends object {

    public $objLanguage;
    private $objDBThemes;
    private $objDBUmbrellaThemes;

    function init() {
        $this->objLanguage = $this->getObject('language', 'language');
        $this->objDBUmbrellaThemes = $this->getObject('dbumbrellathemes', 'oer');
        $this->objDBThemes = $this->getObject('dbthemes', 'oer');
    }

    function show() {
        return $this->createThemeListingTable();
    }

    function createThemeListingTable() {

        $header = new htmlheading();
        $header->type = 2;
        $header->str = $this->objLanguage->languageText('mod_oer_productthemes', 'oer');

        $cp = '';
        $button = new button('newumbrellatheme', $this->objLanguage->languageText('mod_oer_createumbrellatheme', 'oer'));
        $uri = $this->uri(array("action" => "newumbrellatheme"));
        $button->setOnClick('javascript: window.location=\'' . $uri . '\'');
        $cp.=$button->show() . '&nbsp';

        $button = new button('newtheme', $this->objLanguage->languageText('mod_oer_createtheme', 'oer'));
        $uri = $this->uri(array("action" => "newtheme"));
        $button->setOnClick('javascript: window.location=\'' . $uri . '\'');
        $cp.=$button->show() . '&nbsp';

        $button = new button('back', $this->objLanguage->languageText('word_back', 'system'));
        $uri = $this->uri(array());
        $button->setOnClick('javascript: window.location=\'' . $uri . '\'');
        $cp.=$button->show() . '&nbsp';

        $objTable = $this->getObject('htmltable', 'htmlelements');
        $objTable->startHeaderRow();
        $objTable->addHeaderCell($this->objLanguage->languageText('mod_oer_count', 'oer'), "10%");
        $objTable->addHeaderCell(ucfirst($this->objLanguage->languageText('mod_oer_title', 'oer')), "45%");
        $objTable->addHeaderCell(ucfirst($this->objLanguage->languageText('mod_oer_umbrellatheme', 'oer')), "45%");
        $objTable->endHeaderRow();


        $themes = $this->objDBThemes->getThemes();
        $count = 1;
        foreach ($themes as $theme) {
            $objTable->startRow();
            $objTable->addCell($count, "20%");
            $objTable->addCell($theme['theme'], "45%");
            $objTable->addCell($theme['umbrellatheme'], "45%");

            $objTable->endRow();

            $count++;
        }
        return $header->show() . $cp . '<br/>' . $objTable->show();
    }

}

?>
