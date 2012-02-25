<?php

/**
 * this contains utils for managing filtering
 *
 * @author davidwaf
 */
class filtermanager extends object {

    function init() {
        $this->objLanguage = $this->getObject('language', 'language');
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
    }

    /**
     * builds a filter products form 
     */
    function buildFilterProductsForm($action, $label) {

        $typeOfProduct = "";
        $objElement = new checkbox('model', $this->objLanguage->languageText('mod_oer_model', 'oer'));
        $typeOfProduct.= $objElement->show() . '<br/>';

        $objElement = new checkbox('guide', $this->objLanguage->languageText('mod_oer_guide', 'oer'));
        $typeOfProduct.= $objElement->show() . '<br/>';

        $objElement = new checkbox('handbook', $this->objLanguage->languageText('mod_oer_handbook', 'oer'));
        $typeOfProduct.= $objElement->show() . '<br/>';

        $objElement = new checkbox('manual', $this->objLanguage->languageText('mod_oer_manual', 'oer'));
        $typeOfProduct.= $objElement->show() . '<br/>';

        $objElement = new checkbox('model', $this->objLanguage->languageText('mod_oer_bestpractice', 'oer'));

        $typeOfProduct.= $objElement->show() . '<br/>';


        $fieldset1 = $this->newObject('fieldset', 'htmlelements');
        $fieldset1->setLegend($this->objLanguage->languageText($label, 'oer'));
        $fieldset1->addContent($typeOfProduct);


        $themes = new dropdown('themes');
        $themes->addOption('all', $this->objLanguage->languageText('word_all', 'system'));
        $dbThemes = $this->getObject("dbthemes", "oer");
        $allThemes = $dbThemes->getThemes();
        foreach ($allThemes as $theme) {
            $themes->addOption($theme['id'], $theme['theme']);
        }
        $themesField = $this->objLanguage->languageText('mod_oer_theme', 'oer') . '<br/>';
        $themesField.=$themes->show() . '<br/><br/>';


        $language = new dropdown('language');
        $language->addOption('all', $this->objLanguage->languageText('word_all', 'system'));
        $language->addOption('en', $this->objLanguage->languageText('mod_oer_english', 'oer'));

        $languageField = $this->objLanguage->languageText('mod_oer_language', 'oer') . '<br/>';
        $languageField.=$language->show() . '<br/><br/>';

        $dbProducts = $this->getObject("dbproducts", "oer");
        $authors = $dbProducts->getProductAuthors();
        $author = new dropdown('author');
        $author->addOption('all', $this->objLanguage->languageText('word_all', 'system'));
        foreach ($authors as $cauthor) {
            $author->addOption($cauthor['author'], $cauthor['author']);
        }

        $authorField = $this->objLanguage->languageText('mod_oer_author', 'oer') . '<br/>';
        $authorField.=$author->show() . '<br/><br/>';


        $institutions = new dropdown('institution');
        $institutions->addOption('all', $this->objLanguage->languageText('word_all', 'system'));
        $dbInstitutions = $this->getObject("dbinstitution", "oer");
        $allIntitutions = $dbInstitutions->getAllInstitutions();
        foreach ($allIntitutions as $institution) {

            $institutions->addOption($institution['id'], $institution['name']);
        }
        $institutionsField = $this->objLanguage->languageText('mod_oer_institutions', 'oer') . '<br/>';
        $institutionsField.=$institutions->show() . '<br/><br/>';

        $regions = new dropdown('region');
        $regions->addOption('all', $this->objLanguage->languageText('word_all', 'system'));
        $dbGroups = $this->getObject("dbgroups", "oer");
        $allRegions = $dbGroups->getGroupRegions();
        foreach ($allRegions as $region) {
            if ($region != null) {
                $regions->addOption($region['region'], $region['region']);
            }
        }
        $regionsField = $this->objLanguage->languageText('mod_oer_region', 'oer') . '<br/>';
        $regionsField.=$regions->show() . '<br/><br/>';



        $countries = new dropdown('country');
        $countries->addOption('all', $this->objLanguage->languageText('word_all', 'system'));

        $allCountries = $dbGroups->getGroupCountries();

        foreach ($allCountries as $country) {
            if ($country != null) {
                $countries->addOption($country['country'], $country['country']);
            }
        }
        $countriesField = $this->objLanguage->languageText('mod_oer_country', 'oer') . '<br/>';
        $countriesField.=$countries->show() . '<br/><br/>';

        $itemsPerPage = new dropdown('itemsperpage');
        $itemsPerPage->addOption('15', '15');
        $itemsPerPage->addOption('30', '30');
        $itemsPerPage->addOption('60', '60');
        $itemsPerPage->addOption('120', '120');

        $itemsPerPageField = $this->objLanguage->languageText('mod_oer_itemsperpage', 'oer') . '<br/>';
        $itemsPerPageField.=$itemsPerPage->show() . '<br/><br/>';


        $formData = new form('productfilter', $this->uri(array("action" => $action)));
        $formData->addToForm($fieldset1->show() . $themesField . $languageField . $authorField . $institutionsField . $regionsField . $countriesField . $itemsPerPageField);
        $button = new button('searchproduct', $this->objLanguage->languageText('word_search', 'system'));
        $button->setToSubmit();
        $formData->addToForm('<br/>' . $button->show());


        $button = new button('cancel', $this->objLanguage->languageText('mod_oer_reset', 'oer'));
        $uri = $this->uri(array("action" => "home"));
        $button->setOnClick('javascript: window.location=\'' . $uri . '\'');
        $formData->addToForm('&nbsp;&nbsp;' . $button->show());

        return $formData->show();
    }

    /**
     * this generates  filter sql to be used for filter products
     */
    function generateFilter() {
        $sql = "";
        $themes = $this->getParam("themes");
        $language = $this->getParam("language");
        $author = $this->getParam("author");
        $institution = $this->getParam("institution");
        $region = $this->getParam("regio");
        $country = $this->getParam("country");
        $itemsPerPage = $this->getParam("itemsperpage");
        if ($themes != 'all') {
            $sql = " and themes like '%" . $themes . "%'";
        }
        if ($language != 'all') {
            $sql.= " and language='" . $language . "'";
        }
        if ($author != 'all') {
            $sql.=" and author = '" . $author . "'";
        }
        if ($institution != 'all') {
            $sql.=" and institutionid like '%" . $institution . "%'";
        }
        $sql.=" limit " . $itemsPerPage;
        
        return $sql;
    }

}

?>
