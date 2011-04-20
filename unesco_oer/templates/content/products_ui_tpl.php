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
$this->loadClass('htmltable', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('adddatautil','unesco_oer');

$utility = new adddatautil();

//get parent if any
$product = $this->objDbProducts->getProductByID($productID);

// setup and show heading
$header = new htmlHeading();
$header->str = $this->objLanguage->
                languageText('mod_unesco_oer_product_upload_heading', 'unesco_oer');
$header->type = 2;
echo $header->show();

// setup table and table headings with input fields
$table = $this->newObject('htmltable', 'htmlelements');
$table->cssClass = "moduleHeader";

//field for the title
$title = $this->objLanguage->languageText('mod_unesco_oer_title', 'unesco_oer');
$utility->addTextInputToTable($title, 4, 'title', 0, $product['title'], $table);

//field for the creator
$title = $this->objLanguage->languageText('mod_unesco_oer_creator', 'unesco_oer');
$utility->addTitleToTable($title, 4, $table,"2");

$groupName = '';
if ($this->objDbGroups->isGroup($product['creator'])){
    $groupName = $product['creator'];
}
$institutionName = '';
if ($this->objDbInstitution->isInstitution($product['creator'])){
    $institutionName = $product['creator'];
}

//field for Groups
$title = $this->objLanguage->languageText('mod_unesco_oer_group', 'unesco_oer');
$groups = $this->objDbGroups->getGroups();
$utility->addDropDownToTable($title, 4, 'group', $groups, $groupName, 'name', $table);

//field for Institutions
$title = $this->objLanguage->languageText('mod_unesco_oer_institution', 'unesco_oer');
$institutions = $this->objDbInstitution->getInstitutions();
$utility->addDropDownToTable($title, 4, 'institution', $institutions, $institutionName, 'name', $table);

//field for keywords
$title = $this->objLanguage->languageText('mod_unesco_oer_keywords', 'unesco_oer');
$utility->addTextInputToTable($title, 4, 'keywords', 0, $product['keywords'], $table);

//field for the description
$editor = $this->newObject('htmlarea', 'htmlelements');
$editor->name = 'description';
$editor->height = '150px';
$editor->width = '70%';
$editor->setBasicToolBar();
$editor->setContent($product['description']);
$table->startRow();
$table->addCell($this->objLanguage->languageText('mod_unesco_oer_description', 'unesco_oer'));
$table->addCell($editor->show());
$table->endRow();

//field for resource type
$title = $this->objLanguage->languageText('mod_unesco_oer_resource', 'unesco_oer');
$resourceTypes = $this->objDbResourceTypes->getResourceTypes();
$utility->addDropDownToTable($title, 4, 'resourceType', $resourceTypes, $product['resource_type'], 'description', $table);

//field for the theme
$title = $this->objLanguage->languageText('mod_unesco_oer_theme', 'unesco_oer');
$productThemes = $this->objDbProductThemes->getProductThemes();
$utility->addDropDownToTable($title, 4, 'theme', $productThemes, $product['theme'], 'description', $table);

//field for the language
$title = $this->objLanguage->languageText('mod_unesco_oer_language', 'unesco_oer');
$productLanguages = $this->objDbProductLanguages->getProductLanguages();
$utility->addDropDownToTable($title, 4, 'language', $productLanguages, $product['language'], 'code', $table);

//field for the thumbnail
if ($productID == NULL || !($this->objDbProducts->isAdaptation($productID) || $isNewProduct)) {
    $objUpload = $this->getObject('uploadinput', 'filemanager');
    $table->startRow();
    $table->addCell($this->objLanguage->languageText('mod_unesco_oer_thumbnail', 'unesco_oer'));
    $table->addCell($objUpload->show());
    $table->endRow();
}
// setup button for submission
$button = new button('submitform', $this->objLanguage->
                        languageText('mod_unesco_oer_product_upload_button', 'unesco_oer'));
$button->setToSubmit();

//createform, add fields to it and display
$uri = $this->uri(array(
            'action' => "uploadSubmit",
            'parentID' => $productID,
            'prevAction' => $prevAction,
            'isNewProduct' => $isNewProduct));
$form_data = new form('add_products_ui', $uri);
//TODO find out what this does.
$form_data->extra = 'enctype="multipart/form-data"';
$form_data->addToForm($table->show() . '<br />' . $button->show());
echo $form_data->show();
?>
