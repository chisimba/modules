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
$this->loadClass('adddatautil', 'unesco_oer');

//Display errors
error_reporting(E_ALL);
ini_set('display_errors', 'Off');

$fieldsetErrors = $this->newObject('fieldset', 'htmlelements');
$displayErrors = NULL;
$reqiuredField = array();

    $requiredField['name'] = '<span class="required_field"> * </span>';
    $requiredField['code'] = '<span class="required_field"> * </span>';


    //Check if an institution is being edited
    if (isset($languageId)) {
        $formData = $this->objDbProductLanguages->getLanguage($languageId);
        $formAction = "editLanguageSubmit";
    } else {
        $formData = array();
        $formAction = "createLanguageSubmit";
    }

// setup and show heading
$header = new htmlHeading();
//Check if an institution is being edited
if (isset($languageId)) {
    $header->str = $this->objLanguage->
                    languageText('mod_unesco_oer_language_heading', 'unesco_oer');
} else {
    $header->str = $this->objLanguage->
                    languageText('mod_unesco_oer_language_heading', 'unesco_oer');
}


$utility = new adddatautil();
$this->_objAddDataUtil = $this->getObject('adddatautil');

$header->type = 2;
echo '<div id="institutionheading">';
echo $header->show();
echo '</div>';

// setup table and table headings with input options
$table = $this->newObject('htmltable', 'htmlelements');


//input options for resource description
$textinput = new textinput('name');
$textinput->size = 40;
$textinput->setValue($formData['name']);
$table->startRow();
$title = $this->objLanguage->languageText('mod_unesco_oer_language', 'unesco_oer')  . $requiredField['name'];
$table->addCell($title);
$table->endRow();
$table->startRow();
$table->addCell($textinput->show());
$table->endRow();

//input options for resource description
$textinput = new textinput('code');
$textinput->size = 10;
$textinput->setValue($formData['code']);
$table->startRow();
$title = $this->objLanguage->languageText('mod_unesco_oer_language_code', 'unesco_oer')  . $requiredField['code'];
$table->addCell($title);
$table->endRow();
$table->startRow();
$table->addCell($textinput->show());
$table->endRow();

//Submit button
$table->startRow();
$button = new button('submitLanguage', $this->objLanguage->
                        languageText('mod_unesco_oer_save', 'unesco_oer'));
$button->setToSubmit();
$btnCancelCaption = $this->objLanguage->languageText('mod_unesco_oer_product_cancel_button', 'unesco_oer');
$controlPannel = new button('backButton', $btnCancelCaption);
$controlPannel->setToSubmit();
$BackToControlPanelLink = new link($this->uri(array('action' => "viewLanguages")));
$BackToControlPanelLink->link = $controlPannel->show();
$table->startRow();
$table->addCell($button->show() . '&nbsp;' . $BackToControlPanelLink->show());
$table->endRow();

$languageFieldset = $this->newObject('fieldset', 'htmlelements');
$createThemeLegend = $this->objLanguage->languageText('mod_unesco_oer_language_heading', 'unesco_oer');
$languageFieldset->setLegend($createThemeLegend);
$languageFieldset->addContent($table->show());

//createform, add fields to it and display
$objForm = new form('createTheme_ui', $this->uri(array('action' => $formAction, 'languageId' => $languageId)));
$objForm->addToForm($languageFieldset->show());
$objForm->addRule('name', 'Please enter the name of the language', 'required');
$objForm->addRule('code', 'Please enter the three letter code of the language', 'required');
echo $objForm->show();
?>
