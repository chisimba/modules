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

// setup and show heading
$header = new htmlHeading();
$header->type = 2;


if (isset($typeId)) {
    $formData = $this->objDbResourceTypes->getResourceTypeById($typeId);
    $formAction = "editResourceTypeSubmit";
    $header->str = $this->objLanguage->languageText('mod_unesco_oer_add_data_editProductType', 'unesco_oer');
} else {
    $header->str = $this->objLanguage->languageText('mod_unesco_oer_add_data_newProductType', 'unesco_oer');
    $formData = array();
    $formAction = "createResourceTypeSubmit";
}
echo '<div id="institutionheading">';
echo $header->show();
echo '</div>';

// setup table and table headings with input options
$table = $this->newObject('htmltable', 'htmlelements');

//input options for resource description
$textinput = new textinput('newTypeDescription');
$textinput->size = 60;
$textinput->setValue($formData['description']);
$table->startRow();
$title = $this->objLanguage->languageText('mod_unesco_oer_theme_description', 'unesco_oer');
$table->addCell($title);
$table->endRow();
$table->startRow();
$table->addCell($textinput->show());
$table->endRow();

//input options for resource table
$textinput = new textinput('newTypeTable');
$table->startRow();
$table->addCell($this->objLanguage->languageText('mod_unesco_oer_product_type_table', 'unesco_oer'));
$table->endRow();
$table->startRow();
$table->startRow();
$tableNames = $this->objDbResourceTypes->getResourceTypes();
$objTableNamesdd = new dropdown('table_name');
$formatTableName = substr($formData['table_name'], 15, strlen($formData['table_name']));
$objTableNamesdd->addFromDB($tableNames, 'table_name', 'id', $formData['table_name']);
$table->addCell($objTableNamesdd->show());
$table->endRow();
$table->endRow();

//input optins for submit button
$button = new button('submitProductType', $this->objLanguage->languageText('mod_unesco_oer_save', 'unesco_oer'));
$button->setToSubmit();
$controlPannel = new button('backButton', $this->objLanguage->languageText('mod_unesco_oer_product_cancel_button', 'unesco_oer'));
$controlPannel->setToSubmit();
$BackToControlPanelLink = new link($this->uri(array('action' => "viewProductTypes")));
$BackToControlPanelLink->link = $controlPannel->show();
$table->startRow();
$table->addCell($button->show() . '&nbsp;' . $BackToControlPanelLink->show());
$table->endRow();

$Legend = $this->objLanguage->languageText('mod_unesco_oer_theme_description', 'unesco_oer');
$newResourceFieldset = $this->newObject('fieldset', 'htmlelements');
$newResourceFieldset->setLegend($Legend);
$newResourceFieldset->addContent($table->show());

//createform, add fields to it and display
$objForm = new form('newResourceType_ui', $this->uri(array('action' => $formAction, 'typeId' => $typeId)));
$objForm->addToForm($newResourceFieldset->show());
$objForm->addRule('newTypeDescription', 'Please enter the product type', 'required');
$objForm->addRule('newTypeTable', 'Please enter the product type table', 'required');
echo $objForm->show();
?>
