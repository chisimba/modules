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
$this->loadClass('adddatautil', 'unesco_oer');

$utility = new adddatautil();

// setup and show heading
$header = new htmlHeading();
$header->str = $this->objLanguage->languageText('mod_unesco_oer_language_heading', 'unesco_oer');
$header->type = 2;
echo '<div id="institutionheading">';
echo $header->show();
echo '</div>';

// setup table and table headings with input options
$table = $this->newObject('htmltable', 'htmlelements');

//input options for language name
$title = $this->objLanguage->languageText('mod_unesco_oer_language_name', 'unesco_oer');
$languageList = &$this->getObject('language', 'language');

$table->startRow();
$table->addCell($languageList->putlanguageChooser());
$table->endRow();

//Submit button
$table->startRow();
$button = new button('submitLanguage', $this->objLanguage->
                                languageText('mod_unesco_oer_save', 'unesco_oer'));
$button->setToSubmit();
$btnCancelCaption = $this->objLanguage->languageText('mod_unesco_oer_product_cancel_button', 'unesco_oer');
$controlPannel = new button('backButton', $btnCancelCaption);
$controlPannel->setToSubmit();
$BackToControlPanelLink = new link($this->uri(array('action' => "viewProductThemes")));
$BackToControlPanelLink->link = $controlPannel->show();
$table->startRow();
$table->addCell($button->show() . '&nbsp;' . $BackToControlPanelLink->show());
$table->endRow();

$languageFieldset = $this->newObject('fieldset', 'htmlelements');
$createThemeLegend = $this->objLanguage->languageText('mod_unesco_oer_language_heading', 'unesco_oer');
$languageFieldset->setLegend($createThemeLegend);
$languageFieldset->addContent($table->show());

//createform, add fields to it and display
$form_data = new form('createTheme_ui',$this->uri(array('action'=>'createLanguageSubmit')));
$form_data->addToForm($languageFieldset->show());
echo $form_data->show();

?>
