<?php

/**
 * This creates up a form for creating a new product
 *
 * @author davidwaf
 */
class block_newproductform extends object {

    //the language object
    private $objLanguage;
    private $objUser;

    function init() {
        $this->objLanguage = $this->getObject('language', 'language');
        $this->objUser = $this->getObject('user', 'security');
        // Load scriptaclous since we can no longer guarantee it is there
        $scriptaculous = $this->getObject('scriptaculous', 'prototype');
        $this->appendArrayVar('headerParams', $scriptaculous->show('text/javascript'));
        // Load the jquery validate plugin
        $this->appendArrayVar('headerParams', $this->getJavaScriptFile('plugins/validate/jquery.validate.min.js', 'jquery'));
        // Load the helper Javascript
        $this->appendArrayVar('headerParams', $this->getJavaScriptFile('originalproduct.js', 'oer'));
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
    }

    function show() {
        return $this->makeHeading()
                . "<div class='formwrapper'>"
                . $this->createForm()
                . '</div>';
    }

    /**
     *
     * Make a heading for the form
     *
     * @return string The text of the heading
     * @access private
     *
     */
    private function makeHeading() {


        // Get heading based on whether it is edit or add.
        $this->mode = $this->getParam('mode', 'add');
        if ($this->mode == 'edit') {
            $h = $this->objLanguage->languageText(
                    'mod_oer_originalproduct_heading_edit', 'oer');
            $id = $this->getParam('id');
            $this->loadData($id);
        } else {
            $h = $this->objLanguage->languageText(
                    'mod_oer_originalproduct_heading_new', 'oer');
        }
        // Setup and show heading.
        $header = new htmlHeading();
        $header->str = $h;
        $header->type = 2;
        return $header->show();
    }

    /**
     * this constructs the form
     * @return type 
     */
    function createForm() {
        $objTable = $this->getObject('htmltable', 'htmlelements');
        $objTable->cssClass = "buttonHolder";
        
        // Createform, add fields to it and display.
        $uri = $this->uri(array(
            'action' => 'saveoriginalproduct',
            'institutionId' => 'fix this up'
                ));

        $formData = new form('originalProduct', $uri);
      
        //the title
        $objTable->startRow();
        $objTable->addCell($this->objLanguage->languageText('mod_oer_title', 'oer'));
        $objTable->endRow();
        $objTable->startRow();

        $textinput = new textinput('title');
        $textinput->size = 60;
        $textinput->cssClass = 'required';

        $objTable->addCell($textinput->show());
        $objTable->endRow();

        /*
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
          $objSelectBox->create($formData, 'themesLeftList[]', $leftHeader, 'selectedThemes[]', $rightHeader);
          $objDbThemes = $this->getObject('dbthemes', 'oer');
          $themes = $objDbThemes->getThemesFormatted();
          $objSelectBox->insertLeftOptions($themes, 'id', 'theme');
          $objSelectBox->insertRightOptions(array());
          $objTable->startRow();
          $objTable->addCell($objSelectBox->show());
          $arrFormButtons = $objSelectBox->getFormButtons();
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
          $oerresource->addOption('select', $this->objLanguage->languageText('mod_oer_select', 'oer'));
          $oerresource->addOption('curriculum', $this->objLanguage->languageText('mod_oer_curriculum', 'oer'));
          $objTable->addCell($oerresource->show());
          $objTable->endRow();

          //licence
          $objTable->startRow();
          $objTable->addCell($this->objLanguage->languageText('mod_oer_licence', 'oer'));
          $objTable->endRow();

          $objDisplayLicense = $this->getObject('licensechooser', 'creativecommons');
          $objDisplayLicense->setIconSize('big');
          $license = $product['cclicense'] == '' ? 'copyright' : $product['cclicense'];
          $rightCell = $objDisplayLicense->show($license);

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

          $textarea = new textarea('coverage', '', 15, 55);

          $objTable->addCell($textarea->show());
          $objTable->endRow();


          //relationtype
          $objTable->startRow();
          $objTable->addCell($this->objLanguage->languageText('mod_oer_relationtype', 'oer'));
          $objTable->endRow();

          $objTable->startRow();
          $relationtype = new dropdown('relationtype');
          $relationtype->addOption('select', $this->objLanguage->languageText('mod_oer_select', 'oer'));
          $relationtype->addOption('ispartof', $this->objLanguage->languageText('mod_oer_ispartof', 'oer'));
          $relationtype->addOption('requires', $this->objLanguage->languageText('mod_oer_requires', 'oer'));
          $relationtype->addOption('isrequiredby', $this->objLanguage->languageText('mod_oer_isrequiredby', 'oer'));
          $relationtype->addOption('haspartof', $this->objLanguage->languageText('mod_oer_haspartof', 'oer'));
          $relationtype->addOption('references', $this->objLanguage->languageText('mod_oer_references', 'oer'));
          $relationtype->addOption('isversionof', $this->objLanguage->languageText('mod_oer_isversionof', 'oer'));
          $objTable->addCell($relationtype->show());
          $objTable->endRow();

          //relatedproduct
          $objTable->startRow();
          $objTable->addCell($this->objLanguage->languageText('mod_oer_relatedproduct', 'oer'));
          $objTable->endRow();
          $objTable->startRow();
          $relatedproduct = new dropdown('relatedproduct');
          $relatedproduct->addOption('none', $this->objLanguage->languageText('mod_oer_none', 'oer'));
          $objTable->addCell($relatedproduct->show());
          $objTable->endRow();


          //coverage
          $objTable->startRow();
          $objTable->addCell($this->objLanguage->languageText('mod_oer_coverage', 'oer'));
          $objTable->endRow();

          $objTable->startRow();
          $textarea = new textarea('coverage', '', 15, 55);
          $objTable->addCell($textarea->show());
          $objTable->endRow();

          //published
          $objTable->startRow();
          $objTable->addCell($this->objLanguage->languageText('mod_oer_published', 'oer'));
          $objTable->endRow();
          $objTable->startRow();
          $published = new dropdown('published');
          $published->addOption('select', $this->objLanguage->languageText('mod_oer_select', 'oer'));
          $published->addOption($this->objLanguage->languageText('mod_oer_disabled', 'oer'));
          $published->addOption($this->objLanguage->languageText('mod_oer_draft', 'oer'));
          $published->addOption($this->objLanguage->languageText('mod_oer_published', 'oer'));
          $objTable->addCell($published->show());
          $objTable->endRow(); */

        // Save button.
        $objTable->startRow();
        $objTable->addCell("&nbsp;");
        $buttonTitle = $this->objLanguage->languageText('word_save');
        $button = new button('submitOriginalProduct', $buttonTitle);
        //$button->setToSubmit();
        $objTable->addCell($button->show());
        $objTable->endRow();
        // Insert a message area for Ajax result to display.
        $msgArea = "<br /><div id='save_results' class='ajax_results'></div>";



        $formData->extra = ' enctype="multipart/form-data"';
        $formData->addToForm(
                $objTable->show()
                . $msgArea);
        return $formData->show();
        /* $button = new button('save', $this->objLanguage->languageText('word_save', 'system', 'Save'));
          $button->setToSubmit();
          $newproductform->addToForm('<br/>' . $button->show());


          $button = new button('cancel', $this->objLanguage->languageText('word_cancel'));
          $uri = $this->uri(array());
          $button->setOnClick('javascript: window.location=\'' . $uri . '\'');
          $newproductform->addToForm('&nbsp;&nbsp;' . $button->show());

          $newproductform->addToForm('<br/>' . implode(' / ', $arrFormButtons));



          return $newproductform->show(); */
    }

}

?>
