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

//Display errors
error_reporting(E_ALL);
ini_set('display_errors', 'Off');

// set up html elements
$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('htmltable', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('adddatautil', 'unesco_oer');

$utility = new adddatautil();

// setup and show heading
$header = new htmlHeading();
$header->str = $this->objLanguage->languageText('mod_unesco_oer_theme_heading', 'unesco_oer');
$header->type = 2;

//Check if an the theme is being edited
if (isset($themeId)) {
    $formData = $this->objDbProductThemes->getThemeByID($themeId);
    $formAction = "editThemeSubmit";
    $header->str = $this->objLanguage->languageText('mod_unesco_oer_edit_theme_heading', 'unesco_oer');
} else {
    $formData = array();
    $formAction = "createThemeSubmit";
    $header->str = $this->objLanguage->languageText('mod_unesco_oer_theme_heading', 'unesco_oer');
}

echo '<div id="institutionheading">';
echo $header->show();
echo '</div>';

// setup table and table headings with input options
$table = $this->newObject('htmltable', 'htmlelements');

//theme description input options
$title = $this->objLanguage->languageText('mod_unesco_oer_theme_description', 'unesco_oer');

$table->startRow();
$table->addCell($title);
$table->endRow();
//Add textinput to table
//required Field
$table->startRow();
$newTheme = new textinput('newTheme');
$newTheme->setValue($formData['theme']);
$table->addCell($newTheme->show());
$table->endRow();

//$fieldName = 'umbrellatheme';
$table->startRow();
$title = $this->objLanguage->languageText('mod_unesco_oer_Umbrellatheme', 'unesco_oer');
$table->addCell($title);
$table->endRow();

$table->startRow();
$umbrellaThemes = $this->objDbProductThemes->getUmbrellaThemes();
$objUmbrellaTheme = new dropdown('umbrellaTheme');
$objUmbrellaTheme->addFromDB($umbrellaThemes, 'theme', 'id', $formData['umbrella_theme_id']);
// $objElement->label='User list';
$table->addCell($objUmbrellaTheme->show());
$table->endRow();

$btnSaveCaption = $this->objLanguage->languageText('mod_unesco_oer_save', 'unesco_oer');
$button = new button('submitProductType', $btnSaveCaption);
$button->setToSubmit();
$btnCancelCaption = $this->objLanguage->languageText('mod_unesco_oer_product_cancel_button', 'unesco_oer');
$controlPannel = new button('backButton', $btnCancelCaption);
$controlPannel->setToSubmit();
$BackToControlPanelLink = new link($this->uri(array('action' => "viewProductThemes")));
$BackToControlPanelLink->link = $controlPannel->show();
$table->startRow();
$table->addCell($button->show() . '&nbsp;' . $BackToControlPanelLink->show());
$table->endRow();

$subThemeFieldset = $this->newObject('fieldset', 'htmlelements');
$createThemeLegend = $this->objLanguage->languageText('mod_unesco_oer_add_data_newThemeBtn', 'unesco_oer');
$subThemeFieldset->setLegend($createThemeLegend);
$subThemeFieldset->addContent($table->show());

//createform, add fields to it and display
$objForm = new form('createTheme_ui', $this->uri(array('action' => $formAction, 'themeId' => $themeId)));
$objForm->addRule('newTheme', 'Please enter the name of the subtheme', 'required');
$objForm->addRule('theme', 'Please select an umbrella theme', 'select');
$objForm->addToForm($subThemeFieldset->show());
echo $objForm->show();
?>


