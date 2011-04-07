<?php
/*
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the
 * Free Software Foundation, Inc.,
 * 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

// set up html elements
$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('htmltable','htmlelements');
$this->loadClass('textinput','htmlelements');
//$this->loadClass('adddatautil','unesco_oer');

//$utility = new adddatautil();

// setup and show heading
$header = new htmlHeading();
$header->str = $this->objLanguage->
        languageText('mod_unesco_oer_add_data_heading', 'unesco_oer');
$header->type = 2;
echo $header->show();

// setup table and table headings with input options
$table = $this->newObject('htmltable', 'htmlelements');
$table->cellpadding = '10';

//new product options
$header = new htmlHeading();
$header->str = $this->objLanguage->
        languageText('mod_unesco_oer_add_data_product_heading', 'unesco_oer');
$header->type = 2;
$table->startRow();
$table->addCell($header->show());
$table->endRow();
//$headingText = $this->objLanguage->
//        languageText('mod_unesco_oer_add_data_product_heading', 'unesco_oer');
//$utility->addTitleToTable($headingText, 2, $table);

//new OER product options
$table->startRow();
$table->addCell($this->objLanguage->
        languageText('mod_unesco_oer_add_data_newOERproduct','unesco_oer'));
$button = new button('createOERproduct', "Create OER Product");
$uri = $this->uri(array('action' => 'createOERproduct'));
$button->setOnClick('javascript: window.location=\'' . $uri . '\'');
$table->addCell('&nbsp;&nbsp;' . $button->show());
$table->endRow();
//$headingText = $this->objLanguage->
//        languageText('mod_unesco_oer_add_data_newOERproduct', 'unesco_oer');
//$buttonText = $this->objLanguage->
//        languageText('mod_unesco_oer_add_data_newOERproductBtn', 'unesco_oer');
//$actionURI = $this->uri(array('action' => 'createOERproduct'));
//$utility->addButtonToTable($headingText, 4, $buttonText, $actionURI, $table);

//new adaptation options
$table->startRow();
$table->addCell($this->objLanguage->
        languageText('mod_unesco_oer_add_data_newAdaptation','unesco_oer'));
$button = new button('createAdaptation', "Create Adaptation");
$uri = $this->uri(array('action' => 'chooseProductToAdapt'));
$button->setOnClick('javascript: window.location=\'' . $uri . '\'');
$table->addCell('&nbsp;&nbsp;' . $button->show());
$table->endRow();

//new type of resource options
$header = new htmlHeading();
$header->str = $this->objLanguage->
        languageText('mod_unesco_oer_add_data_newProductType', 'unesco_oer');
$header->type = 2;
$table->startRow();
$table->addCell($header->show());
$button = new button('createProductType', "Create Product Type");
$uri = $this->uri(array('action' => 'newResourceTypeUI'));
$button->setOnClick('javascript: window.location=\'' . $uri . '\'');
$table->addCell('&nbsp;&nbsp;' . $button->show());
$table->endRow();

//new type of theme options
$header = new htmlHeading();
$header->str = $this->objLanguage->
        languageText('mod_unesco_oer_add_data_newTheme', 'unesco_oer');
$header->type = 2;
$table->startRow();
$table->addCell($header->show());
$button = new button('createTheme', "Create Theme");
$uri = $this->uri(array('action' => 'createThemeUI'));
$button->setOnClick('javascript: window.location=\'' . $uri . '\'');
$table->addCell('&nbsp;&nbsp;' . $button->show());
$table->endRow();

//new language options
$header = new htmlHeading();
$header->str = $this->objLanguage->
        languageText('mod_unesco_oer_add_data_newLanguage', 'unesco_oer');
$header->type = 2;
$table->startRow();
$table->addCell($header->show());
$button = new button('createLanguage', "Create Language");
$uri = $this->uri(array('action' => 'createLanguageUI'));
$button->setOnClick('javascript: window.location=\'' . $uri . '\'');
$table->addCell('&nbsp;&nbsp;' . $button->show());
$table->endRow();

//new featured product option
$header = new htmlHeading();
$header->str = $this->objLanguage->
        languageText('mod_unesco_oer_add_data_newFeaturedProduct', 'unesco_oer');
$header->type = 2;
$table->startRow();
$table->addCell($header->show());
$button = new button('createFeaturedProduct', "Create Featured Product");
$uri = $this->uri(array('action' => 'featuredProductUI'));
$button->setOnClick('javascript: window.location=\'' . $uri . '\'');
$table->addCell('&nbsp;&nbsp;' . $button->show());
$table->endRow();

//new group options
$header = new htmlHeading();
$header->str = $this->objLanguage->
        languageText('mod_unesco_oer_add_data_newGroup', 'unesco_oer');
$header->type = 2;
$table->startRow();
$table->addCell($header->show());
$button = new button('createGroup', "Create Group");
$uri = $this->uri(array('action' => 'createGroupUI'));
$button->setOnClick('javascript: window.location=\'' . $uri . '\'');
$table->addCell('&nbsp;&nbsp;' . $button->show());
$table->endRow();

//new institution options
$header = new htmlHeading();
$header->str = $this->objLanguage->
        languageText('mod_unesco_oer_add_data_newInstitution', 'unesco_oer');
$header->type = 2;
$table->startRow();
$table->addCell($header->show());
$button = new button('createInstitution', "Create Institution");
$uri = $this->uri(array('action' => 'createInstitutionUI'));
$button->setOnClick('javascript: window.location=\'' . $uri . '\'');
$table->addCell('&nbsp;&nbsp;' . $button->show());
$table->endRow();

//new comment options
$header = new htmlHeading();
$header->str = $this->objLanguage->
        languageText('mod_unesco_oer_add_data_newcomment','unesco_oer');
$header->type = 2;
$table->startRow();
$table->addCell($header->show());
$button = new button('addComment', "Add Comment");
$uri = $this->uri(array('action' => 'chooseProductToComment'));
$button->setOnClick('javascript: window.location=\'' . $uri . '\'');
$table->addCell('&nbsp;&nbsp;' . $button->show());
$table->endRow();

//return to Home
$table->startRow();
$button = new button('home', "HOME");
$uri = $this->uri(array('action' => 'home'));
$button->setOnClick('javascript: window.location=\'' . $uri . '\'');
$table->addCell('&nbsp;&nbsp;' . $button->show());
$table->endRow();

//createform, add fields to it and display
$form_data = new form('add_products_ui');
$form_data->addToForm($table->show());
echo $form_data->show();
?>
