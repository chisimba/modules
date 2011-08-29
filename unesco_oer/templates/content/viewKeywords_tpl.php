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
$this->loadClass('link', 'htmlelements');
$this->loadClass('fieldset', 'htmlelements');
$objIcon = $this->newObject('geticon', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');

// setup and show heading
$header = new htmlheading();
$header->type = 1;
$header->cssClass = "manageusers";
$header->str = $this->objLanguage->languageText('mod_unesco_oer_keywords', 'unesco_oer');
echo '<div id="institutionheading">';
echo $header->show(). '<br><br />';

$buttonAddKeywordCaption = $this->objLanguage->languageText('mod_unesco_oer_keywords_add', 'unesco_oer');
$buttonAddKeyword = new button('Add Language Button', $buttonAddKeywordCaption);
$actionURI = $this->uri(array("action" => 'createKeywordUI'));
$buttonAddKeyword->setOnClick('javascript: window.location=\'' . $actionURI . '\'');

$buttonBackCaption = $this->objLanguage->languageText('mod_unesco_oer_group_back_button', 'unesco_oer');
$controlPannel = new button('backButton', $buttonBackCaption);
$actionURI = $this->uri(array('action' => "controlpanel"));
$controlPannel->setOnClick('javascript: window.location=\'' . $actionURI . '\'');

echo $buttonAddKeyword->show() . '&nbsp;' . $controlPannel->show();
echo '</div>';

$table = $this->newObject('htmltable', 'htmlelements');

$keywordsTable = $this->newObject('htmltable', 'htmlelements');
$keywordsTable->width = '100%';
$keywordsTable->border = '0';
$keywordsTable->cellspacing = '0';
$keywordsTable->cellpadding = '0';

$keywordsTable->startHeaderRow();

$languagesRowTitle = $this->objLanguage->languageText('mod_unesco_oer_keyword', 'unesco_oer');
$editRowHeading = $this->objLanguage->languageText('mod_unesco_oer_group_edit', 'unesco_oer');
$deleteKeywordRowHeading = $this->objLanguage->languageText('mod_unesco_oer_group_delete', 'unesco_oer');

$keywordsTable->addHeaderCell($languagesRowTitle, null, null, 'left', "userheader", null);
$keywordsTable->addHeaderCell($editRowHeading, null, null, 'left', "userheader", null);
$keywordsTable->addHeaderCell($deleteKeywordRowHeading, null, null, 'left', "userheader", null);
$keywordsTable->endHeaderRow();

//get languages from the database
$keywordList = $this->objDbProductKeywords->getProductKeywords();

if (count($keywordList) > 0) {
    foreach ($keywordList as $keyword) {
        $keywordsTable->startRow();
        //($str, $width=null, $valign="top", $align=null, $class=null, $attrib=Null,$border = '0')
        $keywordsTable->addCell($keyword['keyword'], null, null, null, "user", null, null);

        $objIcon->setIcon('edit');
        $editLink = new link($this->uri(array("action" => 'createKeywordUI', 'keywordId' => $keyword['id'])));
        $editLink->link = $objIcon->show();
        $keywordsTable->addCell($editLink->show());

        $objIcon->setIcon('delete');
        $deleteLink = new link($this->uri(array('action' => "deleteKeyword", 'keywordId' => $keyword['id'])));
        $deleteLink->link = $objIcon->show();
        $deleteLink->cssClass = 'deleteLanguage';
        $keywordsTable->addCell($deleteLink->show());
        $keywordsTable->endRow();
    }
}

$fs = new fieldset();
$langFsLegend = $this->objLanguage->languageText('mod_unesco_oer_keywords', 'unesco_oer');
$fs->setLegend($langFsLegend);
$fs->addContent($keywordsTable->show());
echo $fs->show();

?>
