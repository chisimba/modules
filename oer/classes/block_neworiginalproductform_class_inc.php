<?php

/**
 * Builds a form for creating new products
 *
 * @author davidwaf
 */
class block_neworiginalproductform extends object {

    public function init() {
        $this->objLanguage = $this->getObject('language', 'language');
        $this->objUser = $this->getObject('user', 'security');
        $this->loadClass('link', 'htmlelements');
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
        $this->loadClass('fieldset', 'htmlelements');
        $this->addJS();
        $this->setupLanguageItems();
    }

    function addJS() {
        $this->appendArrayVar('headerParams', $this->getJavaScriptFile('plugins/validate/jquery.validate.min.js', 'jquery'));
        $this->appendArrayVar('headerParams', $this->getJavaScriptFile('originalproduct.js', 'oer'));
    }

    function setupLanguageItems() {
        // Serialize language items to Javascript
        $arrayVars['status_success'] = "mod_oer_status_success";
        $arrayVars['status_fail'] = "mod_oer_status_fail";
        $this->serializeVars($arrayVars);
    }

    /**
     *
     * Serialize language elements so they are available to 
     * Javascript. It does this by creating an 
     *  
     * @param string array $arrayVars an array of key value pairs
     * @access private
     * @return void
     * 
     */
    private function serializeVars($arrayVars) {
        $ret = "\n\n<script type='text/javascript'>\n";
        foreach ($arrayVars as $key => $value) {
            $ret .= $key . " = '" . $this->objLanguage->languageText($value, "oer") . "';\n";
        }
        $ret .= "</script>\n\n";
        $this->appendArrayVar('headerParams', $ret);
    }

    /**
     * contructs the form and returns it for display
     * @return type 
     */
    public function show() {
        $formData = new form('originalProductForm', NULL);
        
        $objTable = $this->getObject('htmltable', 'htmlelements');

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


        //alternative title
        $objTable->startRow();
        $objTable->addCell($this->objLanguage->languageText('mod_oer_alttitle', 'oer'));
        $objTable->endRow();

        $objTable->startRow();

        $textinput = new textinput('alternative_title');
        $textinput->size = 60;
        $objTable->addCell($textinput->show());
        $objTable->endRow();



        //author
        $objTable->startRow();
        $objTable->addCell($this->objLanguage->languageText('mod_oer_author', 'oer'));
        $objTable->endRow();

        $objTable->startRow();

        $textinput = new textinput('author');
        $textinput->size = 60;
        $textinput->cssClass = 'required';

        $objTable->addCell($textinput->show());
        $objTable->endRow();



        //other contributors
        $objTable->startRow();
        $objTable->addCell($this->objLanguage->languageText('mod_oer_othercontributors', 'oer'));
        $objTable->endRow();

        $objTable->startRow();

        $textarea = new textarea('othercontributors', '', 5, 55);
        $textarea->cssClass = 'required';
        $objTable->addCell($textarea->show());
        $objTable->endRow();


        //publisher
        $objTable->startRow();
        $objTable->addCell($this->objLanguage->languageText('mod_oer_publisher', 'oer'));
        $objTable->endRow();

        $objTable->startRow();

        $textinput = new textinput('publisher');
        $textinput->size = 60;
        $textinput->cssClass = 'required';
        $objTable->addCell($textinput->show());
        $objTable->endRow();


        //theme
        /*   $objTable->startRow();
          $objTable->addCell($this->objLanguage->languageText('mod_oer_productthemes', 'oer'));
          $objTable->endRow();

          $leftHeader = $this->objLanguage->languageText('mod_oer_availablethemes', 'oer');
          $rightHeader = $this->objLanguage->languageText('mod_oer_selectedthemes', 'oer');

          $objDbThemes = $this->getObject('dbthemes', 'oer');
          $themes = $objDbThemes->getThemesFormatted();
          $themeStr = '<select id="SelectLeft" multiple="multiple">';
          foreach ($themes as $theme) {
          $themeStr.='<option value="' . $theme['id'] . '">' . $theme['theme'] . '(' . $theme['umbrellatheme'] . ')</option>';
          }
          $themeStr.='</select>';

          $themeStr.='<input id="MoveRight" type="button" value=" >> " />';
          $themeStr.='<input id="MoveLeft" type="button" value=" << " />';

          $themeStr.=' <select id="SelectRight" multiple="multiple"></select>';
          $objTable->startRow();
          $objTable->addCell($themeStr);
          $objTable->endRow(); */

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
        $language->cssClass = 'required';
        $language->addOption('', $this->objLanguage->languageText('mod_oer_select', 'oer'));
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
        $oerresource->cssClass = 'required';
        $oerresource->addOption('', $this->objLanguage->languageText('mod_oer_select', 'oer'));
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

        $textarea = new textarea('contacts', '', 5, 55);

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
        $textarea = new textarea('coverage', '', 5, 55);
        $objTable->addCell($textarea->show());
        $objTable->endRow();

        //published status
        $objTable->startRow();
        $objTable->addCell($this->objLanguage->languageText('mod_oer_published', 'oer'));
        $objTable->endRow();
        $objTable->startRow();
        $published = new dropdown('status');
        $published->addOption('', $this->objLanguage->languageText('mod_oer_select', 'oer'));
        $published->cssClass = "required";
        $published->addOption('disabled', $this->objLanguage->languageText('mod_oer_disabled', 'oer'));
        $published->addOption('draft', $this->objLanguage->languageText('mod_oer_draft', 'oer'));
        $published->addOption('published', $this->objLanguage->languageText('mod_oer_published', 'oer'));
        $objTable->addCell($published->show());
        $objTable->endRow();

        $fieldset = $this->newObject('fieldset', 'htmlelements');
        $fieldset->setLegend($this->objLanguage->languageText('mod_oer_originalproduct_heading_new', 'oer'));
        $fieldset->addContent($objTable->show());


        // Table for the buttons
        $table = $this->newObject('htmltable', 'htmlelements');
        $table->cssClass = "buttonHolder";
        $table->startRow();
        $table->addCell("&nbsp;");
        $buttonTitle = $this->objLanguage->languageText('word_save');
        $button = new button('submitInstitution', $buttonTitle);
        $button->setToSubmit();
        $button->cssId = "submitInstitution";
        $table->addCell($button->show());
        $table->endRow();

        // Insert a message area for Ajax result to display.
        $msgArea = "<br /><div id='save_results' class='ajax_results'></div>";

        // Add hidden fields for use by JS
        $hiddenFields = "\n\n";
        $hidMode = new hiddeninput('mode');
        $hidMode->cssId = "mode";
        $hidMode->value = $this->mode;
        $hiddenFields .= $hidMode->show() . "\n";
        $hidId = new hiddeninput('id');
        $hidId->cssId = "id";
        $hidId->value = $this->getParam('id', NULL);
        $hiddenFields .= $hidId->show() . "\n\n";
        $formData->addToForm(
                $fieldset->show()
                . $table->show()
                . $hiddenFields
                . $msgArea
                );
        return $formData->show();

  
    }

}

?>
