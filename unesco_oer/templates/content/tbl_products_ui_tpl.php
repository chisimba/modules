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

//get parent if any
$sql = "select * from tbl_unesco_oer_products where id = '$productID'";
$products = $this->objDbProducts->getArray($sql);
$product = $products[0];

if (count($products) > 1)
    echo "warning: product ID not unique";

// setup and show heading
$header = new htmlHeading();
$header->str = $this->objLanguage->
                languageText('mod_unesco_oer_product_upload_heading', 'unesco_oer');
$header->type = 2;
echo $header->show();

// setup table and table headings with input fields
$table = $this->newObject('htmltable', 'htmlelements');

//field for the title
$textinput = new textinput('title');
$textinput->size = 60;
$textinput->setValue($product['title']);
$table->startRow();
$table->addCell($this->objLanguage->languageText('mod_unesco_oer_title', 'unesco_oer'));
$table->addCell($textinput->show());
$table->endRow();

//field for the creator
$sql = "select name from tbl_unesco_oer_groups";
$groups = $this->objDbGroups->getArray($sql);
$creatorDropdown = new dropdown('creator');
foreach ($groups as $group) {
    $creatorDropdown->addOption($group['name']);
}
$sql = "select name from tbl_unesco_oer_institution";
$institutions = $this->objDbInstitution->getArray($sql);
foreach ($institutions as $institution) {
    $creatorDropdown->addOption($institution['name']);
}
$creatorDropdown->setSelected($product['creator']);
$table->startRow();
$table->addCell('Select creator');
$table->addCell($creatorDropdown->show());
$table->endRow();

//field for keywords
$textinput = new textinput('keywords');
$textinput->size = 60;
$textinput->setValue($product['keywords']);
$table->startRow();
$table->addCell($this->objLanguage->languageText('mod_unesco_oer_keywords', 'unesco_oer'));
$table->addCell($textinput->show());
$table->endRow();

//field for the description
$editor = $this->newObject('htmlarea', 'htmlelements');
$editor->name = 'description';
$editor->height = '100px';
$editor->width = '550px';
$editor->setContent($product['description']);
$table->startRow();
$table->addCell($this->objLanguage->languageText('mod_unesco_oer_description', 'unesco_oer'));
$table->addCell($editor->show());
$table->endRow();

//field for resource type
$sql = "select description from tbl_unesco_oer_resource_types";
$resourceTypes = $this->objDbResourceTypes->getArray($sql);
$resourceDropdown = new dropdown('resourceType');
foreach ($resourceTypes as $resourceType) {

    $resourceDropdown->addOption($resourceType['description']);
}
$resourceDropdown->setSelected($product['resource_type']);
$table->startRow();
$table->addCell('Select resource type');
$table->addCell($resourceDropdown->show());
$table->endRow();

//field for the theme
$sql = "select description from tbl_unesco_oer_product_themes";
$productThemes = $this->objDbProductThemes->getArray($sql);
$productThemesDropdown = new dropdown('theme');
foreach ($productThemes as $productTheme) {

    $productThemesDropdown->addOption($productTheme['description']);
}
$resourceDropdown->setSelected($product['theme']);
$table->startRow();
$table->addCell('Select theme');
$table->addCell($productThemesDropdown->show());
$table->endRow();

//field for the language
$sql = "select code from tbl_unesco_oer_product_languages";
$productLanguages = $this->objDbProductLanguages->getArray($sql);
$productLanguagesDropdown = new dropdown('language');
foreach ($productLanguages as $productLanguage) {

    $productLanguagesDropdown->addOption($productLanguage['code']);
}
$resourceDropdown->setSelected($product['language']);
$table->startRow();
$table->addCell('Select language');
$table->addCell($productLanguagesDropdown->show());
$table->endRow();

//field for the thumbnail
if ($productID == NULL) {
    $objUpload = $this->newObject('uploadinput', 'filemanager');
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
            'parentID' => $productID));
$form_data = new form('add_products_ui', $uri);
//TODO find out what this does.
$form_data->extra = 'enctype="multipart/form-data"';
$form_data->addToForm($table->show() . '<br />' . $button->show());
echo $form_data->show();
?>
