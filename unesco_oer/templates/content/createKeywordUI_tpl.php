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

$keywordID = $this->getParam('keywordId');
$uri = $this->uri(array('action'=>'createKeywordSubmit'));
if (!empty($keywordID)){
    $uri = $this->uri(array('action'=>'editKeywordSubmit', 'id'=>$keywordID));
}

// set up html elements
$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('htmltable','htmlelements');
$this->loadClass('textinput','htmlelements');
$this->loadClass('adddatautil', 'unesco_oer');

$utility = new adddatautil();

// setup and show heading
$header = new htmlHeading();
$header->str = $this->objLanguage->languageText('mod_unesco_oer_keyword_heading', 'unesco_oer');
$header->type = 2;
echo '<div id="institutionheading">';
echo $header->show();
echo '</div>';

// setup table and table headings with input options
$table = $this->newObject('htmltable', 'htmlelements');

//theme description input options
$title = $this->objLanguage->languageText('mod_unesco_oer_keyword_description', 'unesco_oer');
$keywordInfo = $this->objDbProductKeywords->getProductKeywordByID($keywordID);
$utility->addTextInputToTable($title, 4, 'newKeyword', 60, $keywordInfo['keyword'], $table);

$button = new button('submitProductType', "Submit Keyword");
$button->setToSubmit();
$table->startRow();
$table->addCell($button->show());
$table->endRow();

$keywordFieldset = $this->newObject('fieldset', 'htmlelements');
$createKeywordLegend = $this->objLanguage->languageText('mod_unesco_oer_add_data_newKeywordBtn', 'unesco_oer');
$keywordFieldset->setLegend($createKeywordLegend);
$keywordFieldset->addContent($table->show());

//createform, add fields to it and display
$form_data = new form('createTheme_ui',$uri);
$form_data->addToForm($keywordFieldset->show());
$form_data->addRule('newKeyword', 'Please enter a keyword', 'required');
echo $form_data->show();

?>


