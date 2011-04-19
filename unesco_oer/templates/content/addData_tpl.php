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
$this->loadClass('adddatautil','unesco_oer');

$utility = new adddatautil();

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
$headingText = $this->objLanguage->
        languageText('mod_unesco_oer_add_data_product_heading', 'unesco_oer');
$utility->addTitleToTable($headingText, 2, $table);

//new OER product options
$headingText = $this->objLanguage->
        languageText('mod_unesco_oer_add_data_newOERproduct', 'unesco_oer');
$buttonText = $this->objLanguage->
        languageText('mod_unesco_oer_add_data_newOERproductBtn', 'unesco_oer');
$actionURI = $this->uri(array('action' => 'createProduct', 'prevAction' => 'addData'));
$utility->addButtonToTable($headingText, 4, $buttonText, $actionURI, $table);

//new adaptation options
$headingText = $this->objLanguage->
        languageText('mod_unesco_oer_add_data_newAdaptation', 'unesco_oer');
$buttonText = $this->objLanguage->
        languageText('mod_unesco_oer_add_data_newAdaptationBtn', 'unesco_oer');
$actionURI = $this->uri(array('action' => 'chooseProductToAdapt'));
$utility->addButtonToTable($headingText, 4, $buttonText, $actionURI, $table);

//new type of resource options
$headingText = $this->objLanguage->
        languageText('mod_unesco_oer_add_data_newProductType', 'unesco_oer');
$buttonText = $this->objLanguage->
        languageText('mod_unesco_oer_add_data_newProductTypeBtn', 'unesco_oer');
$actionURI = $this->uri(array('action' => 'newResourceTypeUI'));
$utility->addButtonToTable($headingText, 2, $buttonText, $actionURI, $table);

//new type of theme options
$headingText = $this->objLanguage->
        languageText('mod_unesco_oer_add_data_newTheme', 'unesco_oer');
$buttonText = $this->objLanguage->
        languageText('mod_unesco_oer_add_data_newThemeBtn', 'unesco_oer');
$actionURI = $this->uri(array('action' => 'createThemeUI'));
$utility->addButtonToTable($headingText, 2, $buttonText, $actionURI, $table);

//new language options
$headingText = $this->objLanguage->
        languageText('mod_unesco_oer_add_data_newLanguage', 'unesco_oer');
$buttonText = $this->objLanguage->
        languageText('mod_unesco_oer_add_data_newLanguageBtn', 'unesco_oer');
$actionURI = $this->uri(array('action' => 'createLanguageUI'));
$utility->addButtonToTable($headingText, 2, $buttonText, $actionURI, $table);

//new featured product option
$headingText = $this->objLanguage->
        languageText('mod_unesco_oer_add_data_newFeaturedProduct', 'unesco_oer');
$buttonText = $this->objLanguage->
        languageText('mod_unesco_oer_add_data_newFeaturedProductBtn', 'unesco_oer');
$actionURI = $this->uri(array('action' => 'featuredProductUI'));
$utility->addButtonToTable($headingText, 2, $buttonText, $actionURI, $table);

//new featured apaptation option
$headingText = $this->objLanguage->
        languageText('mod_unesco_oer_add_data_newFeaturedAdaptation', 'unesco_oer');
$buttonText = $this->objLanguage->
        languageText('mod_unesco_oer_add_data_newFeaturedAdaptationBtn', 'unesco_oer');
$actionURI = $this->uri(array('action' => 'featuredAdaptationUI'));
$utility->addButtonToTable($headingText, 2, $buttonText, $actionURI, $table);

//new group options
$headingText = $this->objLanguage->
        languageText('mod_unesco_oer_add_data_newGroup', 'unesco_oer');
$buttonText = $this->objLanguage->
        languageText('mod_unesco_oer_add_data_newGroupBtn', 'unesco_oer');
$actionURI = $this->uri(array('action' => 'createGroupUI'));
$utility->addButtonToTable($headingText, 2, $buttonText, $actionURI, $table);

//new institution options
$headingText = $this->objLanguage->
        languageText('mod_unesco_oer_add_data_newInstitution', 'unesco_oer');
$buttonText = $this->objLanguage->
        languageText('mod_unesco_oer_add_data_newInstitutionBtn', 'unesco_oer');
$actionURI = $this->uri(array('action' => 'createInstitutionUI'));
$utility->addButtonToTable($headingText, 2, $buttonText, $actionURI, $table);

//new comment options
$headingText = $this->objLanguage->
        languageText('mod_unesco_oer_add_data_newcomment','unesco_oer');
$buttonText = $this->objLanguage->
        languageText('mod_unesco_oer_add_data_newcommentBtn','unesco_oer');
$actionURI = $this->uri(array('action' => 'chooseProductToComment'));
$utility->addButtonToTable($headingText, 2, $buttonText, $actionURI, $table);

//Rate product options
$headingText = $this->objLanguage->
        languageText('mod_unesco_oer_add_data_addrating','unesco_oer');
$buttonText = $this->objLanguage->
        languageText('mod_unesco_oer_add_data_addratingBtn','unesco_oer');
$actionURI = $this->uri(array('action' => 'chooseProductToRate'));
$utility->addButtonToTable($headingText, 2, $buttonText, $actionURI, $table);

//Edit Institution Option
$headingText = "EDIT INSTITUTION"; //MUST USE OBJECT LANGUAGE
$buttonText = "Edit Institution";
$actionURI = $this->uri(array('action' => 'editInstitutionUI1'));
$utility->addButtonToTable($headingText, 2, $buttonText, $actionURI, $table);

//return to Home
$table->startRow();
$buttonText = $this->objLanguage->
        languageText('mod_unesco_oer_add_data_homeBtn','unesco_oer');
$actionURI = $this->uri(array('action' => 'home'));
$utility->addButtonToRow($buttonText, $actionURI, $table);
$table->endRow();

//createform, add fields to it and display
$form_data = new form('add_products_ui');
$form_data->addToForm($table->show());
echo $form_data->show();
//
//$this->loadClass('commentapi','blogcomments');
//
//$commentUI = $this->newObject('commentapi','blogcomments');
//
//echo $commentUI->commentAddForm('postid', 'unesco_oer', $this->objDbComments, NULL, FALSE, FALSE, FALSE, FALSE, NULL, NULL);
?>
