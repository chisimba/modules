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

/* This is a Edit theme  UI
 *
 */

//Display errors
error_reporting(E_ALL);
ini_set('display_errors', 'On');

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
$header->str = $this->objLanguage->languageText('mod_unesco_oer_languages', 'unesco_oer');
echo '<div id="institutionheading">';
echo $header->show(). '<br><br />';

$buttonLangCaption = $this->objLanguage->languageText('mod_unesco_oer_add_data_newLanguage', 'unesco_oer');
$buttonLanguage = new button('Add Language Button', $buttonLangCaption);
$buttonLanguage->setToSubmit();
$addLanguageLink = new link($this->uri(array('action' => "createLanguageUI")));
$addLanguageLink->link = $buttonLanguage->show();

$buttonBackCaption = $this->objLanguage->languageText('mod_unesco_oer_group_back_button', 'unesco_oer');
$controlPannel = new button('backButton', $buttonBackCaption);
$controlPannel->setToSubmit();
$BackToControlPanelLink = new link($this->uri(array('action' => "controlpanel")));
$BackToControlPanelLink->link = $controlPannel->show();

echo $addLanguageLink->show() . '&nbsp;' . $BackToControlPanelLink->show();
echo '</div>';


$table = $this->newObject('htmltable', 'htmlelements');

$themesTable = $this->newObject('htmltable', 'htmlelements');
$themesTable->width = '100%';
$themesTable->border = '0';
$themesTable->cellspacing = '0';
$themesTable->cellpadding = '0';

$themesTable->startHeaderRow();

$languagesRowTitle = $this->objLanguage->languageText('mod_unesco_oer_language', 'unesco_oer');
$codeRowTitle = $this->objLanguage->languageText('mod_unesco_oer_language_code', 'unesco_oer');
$editRowHeading = $this->objLanguage->languageText('mod_unesco_oer_group_edit', 'unesco_oer');
$deleteThemeRowHeading = $this->objLanguage->languageText('mod_unesco_oer_group_delete', 'unesco_oer');

$themesTable->addHeaderCell($languagesRowTitle, null, null, 'left', "userheader", null);
$themesTable->addHeaderCell($codeRowTitle, null, null, 'left', "userheader", null);
$themesTable->addHeaderCell($editRowHeading, null, null, 'left', "userheader", null);
$themesTable->addHeaderCell($deleteThemeRowHeading, null, null, 'left', "userheader", null);
$themesTable->endHeaderRow();

//get languages from the database
$languageList = $this->objDbProductLanguages->getProductLanguages();

if (count($languageList) > 0) {
    foreach ($languageList as $language) {
        $themesTable->startRow();
        //($str, $width=null, $valign="top", $align=null, $class=null, $attrib=Null,$border = '0')
        $themesTable->addCell($language['name'], null, null, null, "user", null, null);
        $themesTable->addCell($language['code'], null, null, null, "user", null, null);
        
        $objIcon->setIcon('edit');
        $editLink = new link($this->uri(array('action' => "editLanguage", 'languageId' => $language['id'])));
        $editLink->link = $objIcon->show();
        $themesTable->addCell($editLink->show());

        $objIcon->setIcon('delete');
        $deleteLink = new link($this->uri(array('action' => "deleteLanguage", 'languageId' => $language['id'])));
        $deleteLink->link = $objIcon->show();
        $deleteLink->cssClass = 'deleteTheme';
        $themesTable->addCell($deleteLink->show());
        $themesTable->endRow();
    }
}


$fs = new fieldset();
$langFsLegend = $this->objLanguage->languageText('mod_unesco_oer_languages', 'unesco_oer');
$fs->setLegend($langFsLegend);
$fs->addContent($themesTable->show());
echo $fs->show();
?>
<script type="text/javascript">

    jQuery(document).ready(function(){

    jQuery("a[class=deleteLanguage]").click(function(){

    var r=confirm( "Are you sure you want to delete this Language?");
    if(r== true){
    window.location=this.href;
    }
    return false;
    }


    );

    }


    );
</script>