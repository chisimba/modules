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
ini_set('display_errors', 'On');

// set up html elements
$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('htmltable', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('adddatautil', 'unesco_oer');


//$utility = new adddatautil();
// setup and show heading
$header = new htmlHeading();
$header->type = 2;

//Check if an institution is being edited
if (isset($themeId)) {
    $formData = $this->objDbProductThemes->getUmbrellaThemeByID($themeId);
    $formAction = "editUmbrellaThemeSubmit";
    $header->str = $this->objLanguage->languageText('mod_unesco_oer_Edit_theme_heading', 'unesco_oer');
} else {
    $header->str = $this->objLanguage->languageText('mod_unesco_oer_theme_heading', 'unesco_oer');
    $formData = array();
    $formAction = "createUmbrellaThemeSubmit";
}

echo '<div id="institutionheading">';
echo $header->show();
echo '</div>';

// setup table and table headings with input options
$table = $this->newObject('htmltable', 'htmlelements');

//theme description input options
$title = $this->objLanguage->languageText('mod_unesco_oer_theme_description', 'unesco_oer');
//$utility->addTextInputToTable($title, 4, 'newUmbrellaTheme', 60, '', $table);
//Add title to table
$table->startRow();
$table->addCell($title);
$table->endRow();
//Add textinput to table
//required Field
$table->startRow();
$newUmbrellaTheme = new textinput('newUmbrellaTheme');
$newUmbrellaTheme->setValue($formData['theme']);
$table->addCell($newUmbrellaTheme->show());
$table->endRow();
//$newUmbrellaTheme->label='Name(must be filled out)';



$button = new button('submitProductType', "Submit Theme");
$button->setToSubmit();
$controlPannel = new button('backButton', "Cancel");
$controlPannel->setToSubmit();
$BackToControlPanelLink = new link($this->uri(array('action' => "viewProductThemes")));
$BackToControlPanelLink->link = $controlPannel->show();
$table->startRow();
$table->addCell($button->show() . '&nbsp;' . $BackToControlPanelLink->show());
$table->endRow();

$umbrellaThemeFieldset = $this->newObject('fieldset', 'htmlelements');
$umbrellaThemeFieldset->setLegend("Create Umbrella Theme");
$umbrellaThemeFieldset->addContent($table->show());

//createform, add fields to it and display
$objForm = new form('createTheme_ui', $this->uri(array('action' => 'createUmbrellaThemeSubmit')));
//Add a rule for validating the field
$objForm->addRule('newUmbrellaTheme', 'Please enter the name of the Umbrella theme', 'required');
$objForm->addToForm($umbrellaThemeFieldset->show());
echo $objForm->show();
?>


