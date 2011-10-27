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
$header->str = $this->objLanguage->languageText('mod_unesco_oer_product_themes', 'unesco_oer');
echo '<div id="institutionheading">';
echo $header->show(). '<br><br />';

$createButtonCaption = $this->objLanguage->languageText('mod_unesco_oer_add_data_newUmbrellaThemeBtn', 'unesco_oer');
$buttonUmbrellaTheme = new button('Add UbrellaTheme Button', $createButtonCaption);
$buttonUmbrellaTheme->setToSubmit();
$addUmbrellaThemeLink = new link($this->uri(array('action' => "createUmbrellaThemeUI")));
$addUmbrellaThemeLink->link = $buttonUmbrellaTheme->show();
$createButtonCaption = $this->objLanguage->languageText('mod_unesco_oer_add_data_newThemeBtn', 'unesco_oer');
$buttonSubTheme = new button('Add subTheme Button', $createButtonCaption);
$buttonSubTheme->setToSubmit();
$addSubThemeLink = new link($this->uri(array('action' => "createThemeUI")));
$addSubThemeLink->link = $buttonSubTheme->show();
$controlPannel = new button('backButton', "Back");
$controlPannel->setToSubmit();
$BackToControlPanelLink = new link($this->uri(array('action' => "controlpanel")));
$BackToControlPanelLink->link = $controlPannel->show();

echo $addUmbrellaThemeLink->show() . '&nbsp;' . $addSubThemeLink->show() . '&nbsp;' . $BackToControlPanelLink->show();
echo '</div>';


$table = $this->newObject('htmltable', 'htmlelements');

$themesTable = $this->newObject('htmltable', 'htmlelements');
$themesTable->width = '100%';
$themesTable->border = '0';
$themesTable->cellspacing = '0';
$themesTable->cellpadding = '0';

$themesTable->startHeaderRow();

//Add language elements
$themeRowHeading = $this->objLanguage->languageText('mod_unesco_oer_product_themes', 'unesco_oer');
$umbrellaThemeRowHeading = $this->objLanguage->languageText('mod_unesco_oer_Umbrellatheme', 'unesco_oer');
$editRowHeading = $this->objLanguage->languageText('mod_unesco_oer_group_edit', 'unesco_oer');
$deleteThemeRowHeading = $this->objLanguage->languageText('mod_unesco_oer_group_delete', 'unesco_oer');

$themesTable->addHeaderCell($themeRowHeading, null, null, 'left', "userheader", null);
$themesTable->addHeaderCell($umbrellaThemeRowHeading, null, null, 'left', "userheader", null);
$themesTable->addHeaderCell($editRowHeading, null, null, 'left', "userheader", null);
$themesTable->addHeaderCell($deleteThemeRowHeading, null, null, 'left', "userheader", null);
$themesTable->endHeaderRow();

//get themes from the database
$umbrellaThemesList = $this->objDbProductThemes->getUmbrellaThemes();

if (count($umbrellaThemesList) > 0) {
    foreach ($umbrellaThemesList as $umbrellaTheme) {
        $themesTable->startRow();
        $themesTable->addCell($umbrellaTheme['theme'], null, null, null, "user", null, null);
        $themesTable->addCell('-', null, null, null, "theme", null, null);

        $objIcon->setIcon('edit');
        $editLink = new link($this->uri(array('action' => "editUmbrellaTheme", 'themeId' => $umbrellaTheme['id'])));
        $editLink->link = $objIcon->show();
        $themesTable->addCell($editLink->show());

        $objIcon->setIcon('delete');
        $deleteLink = new link($this->uri(array('action' => "deleteUmbrellaTheme", 'themeId' => $umbrellaTheme['id'])));
        $deleteLink->link = $objIcon->show();
        $deleteLink->cssClass = 'deleteTheme';
        $themesTable->addCell($deleteLink->show());
        $themesTable->endRow();

        //Get the subthemes that belong to this umbrella theme
        $subThemesList = $this->objDbProductThemes->getThemesByUmbrellaID($umbrellaTheme['id']);
        if (count($subThemesList) > 0) {
            foreach ($subThemesList as $subTheme) {
                $themesTable->startRow();
                //($str, $width=null, $valign="top", $align=null, $class=null, $attrib=Null,$border = '0')
                $themesTable->addCell($subTheme['theme'], null, null, null, "user", null, null);
                $themesTable->addCell($umbrellaTheme['theme'], null, null, null, "theme", null, null);

                $objIcon->setIcon('edit');
                $editLink = new link($this->uri(array('action' => "editTheme", 'themeId' => $subTheme['id'])));
                $editLink->link = $objIcon->show();
                $themesTable->addCell($editLink->show());

                $objIcon->setIcon('delete');
                $deleteLink = new link($this->uri(array('action' => "deleteTheme", 'themeId' => $subTheme['id'])));
                $deleteLink->link = $objIcon->show();
                $deleteLink->cssClass = 'deleteTheme';
                $themesTable->addCell($deleteLink->show());
                $themesTable->endRow();
            }
        }
    }
}


$fs = new fieldset();
$fieldsetLegend = $this->objLanguage->languageText('mod_unesco_oer_theme', 'unesco_oer');
$fs->setLegend($fieldsetLegend);
$fs->addContent($themesTable->show());
echo $fs->show();
?>
<script type="text/javascript">

    jQuery(document).ready(function(){

    jQuery("a[class=deleteTheme]").click(function(){

    var r=confirm( "<?php echo $this->objLanguage->languageText('mod_unesco_oer_product_theme_delete_confirm', 'unesco_oer'); ?>");
    if(r== true){
    window.location=this.href;
    }
    return false;
    });

    });
    
    
</script>