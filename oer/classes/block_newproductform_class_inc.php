<?php

/**
 * This creates up a form for creating a new product
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

class block_newproductform extends object {

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

    /**
     * this constructs the form
     * @return type 
     */
    function createForm() {
        $objTable = $this->getObject('htmltable', 'htmlelements');
        $newproductform = new form('newproduct', $this->uri(array('action' => 'saveoriginalproduct')));

        $header = new htmlheading();
        $header->type = 2;
        $header->str = $this->objLanguage->languageText('mod_oer_newproduct', 'oer');

        $newproductform->addToForm($header->show());

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


        //alternative title
        $objTable->startRow();
        $objTable->addCell($this->objLanguage->languageText('mod_oer_alttitle', 'oer'));
        $objTable->endRow();

        $objTable->startRow();

        $textinput = new textinput('alt_title');
        $textinput->size = 60;
        if ($mode == 'edit') {
            $textinput->value = $product['alt_title'];
        }
        if ($mode == "fixup") {
            $textinput->value = $alttitle;
        }

        $objTable->addCell($textinput->show());
        $objTable->endRow();



        //author
        $objTable->startRow();
        $objTable->addCell($this->objLanguage->languageText('mod_oer_author', 'oer'));
        $objTable->endRow();

        $objTable->startRow();

        $textinput = new textinput('author');
        $textinput->size = 60;
        if ($mode == 'edit') {
            $textinput->value = $product['author'];
        }
        if ($mode == "fixup") {
            $textinput->value = $author;
        }

        $objTable->addCell($textinput->show());
        $objTable->endRow();



        //other contributors
        $objTable->startRow();
        $objTable->addCell($this->objLanguage->languageText('mod_oer_othercontributors', 'oer'));
        $objTable->endRow();

        $objTable->startRow();

        $textarea = new textarea('othercontributors', '', 15, 55);

        if ($mode == 'edit') {
            $textarea->value = $product['othercontributors'];
        }
        if ($mode == "fixup") {
            $textarea->value = $othercontributors;
        }


        $objTable->addCell($textarea->show());
        $objTable->endRow();


        //publisher
        $objTable->startRow();
        $objTable->addCell($this->objLanguage->languageText('mod_oer_publisher', 'oer'));
        $objTable->endRow();

        $objTable->startRow();

        $textinput = new textinput('publisher');
        $textinput->size = 60;
        if ($mode == 'edit') {
            $textinput->value = $product['publisher'];
        }
        if ($mode == "fixup") {
            $textinput->value = $publisher;
        }

        $objTable->addCell($textinput->show());
        $objTable->endRow();


        //theme
        $objTable->startRow();
        $objTable->addCell($this->objLanguage->languageText('mod_oer_productthemes', 'oer'));
        $objTable->endRow();

        $objSelectBox = $this->newObject('selectbox', 'htmlelements');
        $leftHeader = $this->objLanguage->languageText('mod_oer_availablethemes', 'oer');
        $rightHeader = $this->objLanguage->languageText('mod_oer_selectedthemes', 'oer');
        $objSelectBox->create($newproductform, 'themesLeftList[]', $leftHeader, 'themesRightList[]', $rightHeader);
        $objDbThemes = $this->getObject('dbthemes', 'oer');
        $themes = $objDbThemes->getThemesFormatted();
        $objSelectBox->insertLeftOptions($themes, 'id', 'theme');
        $objSelectBox->insertRightOptions(array());
        $objTable->startRow();
        $objTable->addCell($objSelectBox->show());
        $objTable->endRow();


        //keywords
        $objTable->startRow();
        $objTable->addCell($this->objLanguage->languageText('mod_oer_keywords', 'oer'));
        $objTable->endRow();

        $objSelectBox = $this->newObject('selectbox', 'htmlelements');
        $leftHeader = $this->objLanguage->languageText('mod_oer_availablekeywords', 'oer');
        $rightHeader = $this->objLanguage->languageText('mod_oer_selectedkeywords', 'oer');
        $objSelectBox->create($newproductform, 'keywordsLeftList[]', $leftHeader, 'keywordsRightList[]', $rightHeader);
        $objDbKeyWords = $this->getObject('dbkeywords', 'oer');
        $keywords = $objDbKeyWords->getKeyWords();
        $objSelectBox->insertLeftOptions($keywords, 'id', 'keyword');
        $objSelectBox->insertRightOptions(array());
        $objTable->startRow();
        $objTable->addCell($objSelectBox->show());
        $objTable->endRow();


        //language
        $objTable->startRow();
        $objTable->addCell($this->objLanguage->languageText('mod_oer_language', 'oer'));
        $objTable->endRow();

        $objTable->startRow();
        $language = new dropdown('language');
        $language->addOption('select', $this->objLanguage->languageText('mod_oer_select', 'oer'));
        $language->addOption('en', $this->objLanguage->languageText('mod_oer_english', 'oer'));
        $objTable->addCell($language->show());
        $objTable->endRow();



        //translation
        $objTable->startRow();
        $objTable->addCell($this->objLanguage->languageText('mod_oer_translationof', 'oer'));
        $objTable->endRow();

        $objTable->startRow();
        $translation = new dropdown('translation');
        $translation->addOption('select', $this->objLanguage->languageText('mod_oer_select', 'oer'));
        $translation->addOption('none', $this->objLanguage->languageText('mod_oer_none', 'oer'));
        $objTable->addCell($translation->show());
        $objTable->endRow();


        //description
        $objTable->startRow();
        $objTable->addCell($this->objLanguage->languageText('mod_oer_description', 'oer'));
        $objTable->endRow();

        $objTable->startRow();
        $description = $this->newObject('htmlarea', 'htmlelements');
        $description->name = 'description';
        $description->height = '150px';
        $description->setBasicToolBar();
        $objTable->addCell($description->show());
        $objTable->endRow();


        //abstract
        $objTable->startRow();
        $objTable->addCell($this->objLanguage->languageText('mod_oer_abstract', 'oer'));
        $objTable->endRow();

        $objTable->startRow();
        $abstract = $this->newObject('htmlarea', 'htmlelements');
        $abstract->name = 'abstract';
        $abstract->height = '150px';
        $abstract->setBasicToolBar();
        $objTable->addCell($abstract->show());
        $objTable->endRow();


        //resource type
        $objTable->startRow();
        $objTable->addCell($this->objLanguage->languageText('mod_oer_oerresource', 'oer'));
        $objTable->endRow();

        $objTable->startRow();
        $oerresource = new dropdown('oerresource');
        $oerresource->addOption('select',$this->objLanguage->languageText('mod_oer_select', 'oer'));
        $oerresource->addOption('curriculum',$this->objLanguage->languageText('mod_oer_curriculum', 'oer'));
        $objTable->addCell($oerresource->show());
        $objTable->endRow();

        //licence
        $objTable->startRow();
        $objTable->addCell($this->objLanguage->languageText('mod_oer_licence', 'oer'));
        $objTable->endRow();

        $objDisplayLicense = $this->getObject('displaylicense', 'creativecommons');
        $objDisplayLicense->icontype = 'big';
        //$license = ($product['cclicense'] == '' ? 'copyright' : $file['cclicense']);
        $rightCell .= $objDisplayLicense->show($license);
        $objTable->startRow();
        $objTable->addCell($rightCell);
        $objTable->endRow();



        //provenonce
        $objTable->startRow();
        $objTable->addCell($this->objLanguage->languageText('mod_oer_provenonce', 'oer'));
        $objTable->endRow();

        $objTable->startRow();
        $provenonce = $this->newObject('htmlarea', 'htmlelements');
        $provenonce->name = 'provenonce';
        $provenonce->height = '150px';
        $provenonce->setBasicToolBar();
        $objTable->addCell($provenonce->show());
        $objTable->endRow();


        //needs accredited
        $objTable->startRow();
        $objTable->addCell($this->objLanguage->languageText('mod_oer_accredited', 'oer') . '?');
        $objTable->endRow();

        $radio = new radio('accredited');
        $radio->addOption('yes', $this->objLanguage->languageText('word_yes', 'system'));
        $radio->addOption('no', $this->objLanguage->languageText('word_no', 'system'));
        $objTable->startRow();
        $objTable->addCell($radio->show());
        $objTable->endRow();

        //accreditationbody
        $objTable->startRow();
        $objTable->addCell($this->objLanguage->languageText('mod_oer_accreditationbody', 'oer'));
        $objTable->endRow();

        $objTable->startRow();

        $textinput = new textinput('accreditationbody');
        $textinput->size = 60;
        if ($mode == 'edit') {
            $textinput->value = $product['accreditationbody'];
        }
        if ($mode == "fixup") {
            $textinput->value = $accreditationbody;
        }

        $objTable->addCell($textinput->show());
        $objTable->endRow();

        //accreditationdate
        $objTable->startRow();
        $objTable->addCell($this->objLanguage->languageText('mod_oer_accreditationdate', 'oer'));
        $objTable->endRow();

        $objTable->startRow();

        $textinput = new textinput('accreditationdate');
        $textinput->size = 60;
        if ($mode == 'edit') {
            $textinput->value = $product['accreditationdate'];
        }
        if ($mode == "fixup") {
            $textinput->value = $accreditationdate;
        }

        $objTable->addCell($textinput->show());
        $objTable->endRow();

        //contacts
        $objTable->startRow();
        $objTable->addCell($this->objLanguage->languageText('mod_oer_contacts', 'oer'));
        $objTable->endRow();

        $objTable->startRow();

        $textinput = new textinput('contacts');
        $textinput->size = 60;
        if ($mode == 'edit') {
            $textinput->value = $product['contacts'];
        }
        if ($mode == "fixup") {
            $textinput->value = $contacts;
        }

        $objTable->addCell($textinput->show());
        $objTable->endRow();


        //relationtype
        $objTable->startRow();
        $objTable->addCell($this->objLanguage->languageText('mod_oer_relationtype', 'oer'));
        $objTable->endRow();

        $objTable->startRow();
        $relationtype = new dropdown('relationtype');
        $relationtype->addOption($this->objLanguage->languageText('mod_oer_select', 'oer'));
        $objTable->addCell($relationtype->show());
        $objTable->endRow();

        //relatedproduct
        $objTable->startRow();
        $objTable->addCell($this->objLanguage->languageText('mod_oer_relatedproduct', 'oer'));
        $objTable->endRow();
        $objTable->startRow();
        $relatedproduct = new dropdown('relatedproduct');
        $relatedproduct->addOption($this->objLanguage->languageText('mod_oer_select', 'oer'));
        $objTable->addCell($relatedproduct->show());
        $objTable->endRow();


        //coverage
        $objTable->startRow();
        $objTable->addCell($this->objLanguage->languageText('mod_oer_coverage', 'oer'));
        $objTable->endRow();

        $objTable->startRow();

        $textinput = new textinput('contacts');
        $textinput->size = 60;
        if ($mode == 'edit') {
            $textinput->value = $product['contacts'];
        }
        if ($mode == "fixup") {
            $textinput->value = $contacts;
        }

        $objTable->addCell($textinput->show());
        $objTable->endRow();

        //published
        $objTable->startRow();
        $objTable->addCell($this->objLanguage->languageText('mod_oer_published', 'oer'));
        $objTable->endRow();
        $objTable->startRow();
        $published = new dropdown('published');
        $published->addOption($this->objLanguage->languageText('mod_oer_select', 'oer'));
        $objTable->addCell($published->show());
        $objTable->endRow();

        $newproductform->addToForm($objTable->show());

        $button = new button('save', $this->objLanguage->languageText('word_save', 'system', 'Save'));
        $button->setToSubmit();
        $newproductform->addToForm('<br/>' . $button->show());


        $button = new button('cancel', $this->objLanguage->languageText('word_cancel'));
        $uri = $this->uri(array());
        $button->setOnClick('javascript: window.location=\'' . $uri . '\'');
        $newproductform->addToForm('&nbsp;&nbsp;' . $button->show());



        return $newproductform->show();
    }

}

?>
