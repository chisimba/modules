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
    function buildFilterProductsForm($action,$label) {

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


        $language = new dropdown('language');
        $language->addOption('all', $this->objLanguage->languageText('word_all', 'system'));
        $language->addOption('en', $this->objLanguage->languageText('mod_oer_english', 'oer'));

        $fieldset2 = $this->newObject('fieldset', 'htmlelements');
        $fieldset2->setLegend($this->objLanguage->languageText('mod_oer_language', 'oer'));
        $fieldset2->addContent($language->show());

        $author = new dropdown('author');
        $author->addOption('all', $this->objLanguage->languageText('word_all', 'system'));


        $fieldset3 = $this->newObject('fieldset', 'htmlelements');
        $fieldset3->setLegend($this->objLanguage->languageText('mod_oer_author', 'oer'));
        $fieldset3->addContent($author->show());

        $itemsPerPage = new dropdown('author');
        $itemsPerPage->addOption('15', '15');
        $itemsPerPage->addOption('30', '30');
        $itemsPerPage->addOption('60', '60');
        $itemsPerPage->addOption('120', '120');

        $fieldset4 = $this->newObject('fieldset', 'htmlelements');
        $fieldset4->setLegend($this->objLanguage->languageText('mod_oer_itemsperpage', 'oer'));
        $fieldset4->addContent($itemsPerPage->show());


        $formData = new form('originalproductfilter', $this->uri(array("action" => $action)));
        $formData->addToForm($fieldset1->show() . $fieldset2->show() . $fieldset3->show() . $fieldset4->show());
        $button = new button('searchoriginalproduct', $this->objLanguage->languageText('word_search', 'system'));
        $button->setToSubmit();
        $formData->addToForm('<br/>' . $button->show());


        $button = new button('cancel', $this->objLanguage->languageText('mod_oer_reset','oer'));
        $uri = $this->uri(array("action" => "home"));
        $button->setOnClick('javascript: window.location=\'' . $uri . '\'');
        $formData->addToForm('&nbsp;&nbsp;' . $button->show());

        return $formData->show();
    }

}

?>
