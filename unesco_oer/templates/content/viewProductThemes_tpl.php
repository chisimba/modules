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
ini_set('display_errors', 'Off');

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
$header->str = "Product Themes";
echo '<div id="themeheading">';
echo $header->show();
echo '</div>';
$button = new button('Add Button', "Add Theme");
$button->setToSubmit();
$addThemeLink = new link($this->uri(array('action' => "themeEditor")));
$addThemeLink->link = $button->show();



$table = $this->newObject('htmltable', 'htmlelements');
//$search = new textinput('state');
//$search->size = 10;
//$table->startRow();
//$table->addCell('Search');
//$table->addCell($search->show());
//$table->endRow();
//echo $table->show();


$controlPannel = new button('backButton', "Back");
$controlPannel->setToSubmit();
$BackToControlPanelLink = new link($this->uri(array('action' => "controlpanel")));
$BackToControlPanelLink->link = $controlPannel->show();

//button search theme
//$buttonGO = new button('searchButton', "Go");
//$buttonGO->setToSubmit();
//$searchLink = new link($this->uri(array('action' => "searchtheme",'search'=>$this->getParam('search'))));
//$searchLink->link = $buttonGO->show();
////text input search theme
//$search = new textinput('search','',"",20);


echo $addThemeLink->show() . '&nbsp;' . $BackToControlPanelLink->show();
//. '&nbsp;' . $search->show() . '&nbsp;' . $searchLink->show();

$themesTable = $this->newObject('htmltable', 'htmlelements');
$themesTable->width = '100%';
$themesTable->border = '0';
$themesTable->cellspacing = '0';
$themesTable->cellpadding = '0';

$themesTable->startHeaderRow();
//$str, $width=null, $valign="top", $align='left', $class=null, $attrib=Null)
$themesTable->addHeaderCell('Theme', null, null, left, "userheader", null);
$themesTable->addHeaderCell('Umbrella Theme', null, null, left, "userheader", null);
$themesTable->addHeaderCell('Edit', null, null, left, "userheader", null);
$themesTable->addHeaderCell('Delete', null, null, left, "userheader", null);
$themesTable->endHeaderRow();

//get themes from the database
$umbrellaThemesList = $this->objDbProductThemes->getUmbrellaThemes();
//$subThemesList = $this->objDbProductThemes->getProductThemes();

if (count($umbrellaThemesList) > 0) {
    foreach ($umbrellaThemesList as $umbrellaTheme) {
        $themesTable->startRow();
        //($str, $width=null, $valign="top", $align=null, $class=null, $attrib=Null,$border = '0')
        $themesTable->addCell($umbrellaTheme['theme'], null, null, null, "user", null, null);
        $themesTable->addCell('-', null, null, null, "theme", null, null);

        $objIcon->setIcon('edit');
        $editLink = new link($this->uri(array('action' => "editthemeDetailsForm", 'id' => $umbrellaTheme['id'], 'themeid' => $theme['themeid'], 'themename' => $theme['themename'])));
        $editLink->link = $objIcon->show();
        $themesTable->addCell($editLink->show());

        $objIcon->setIcon('delete');
        $deleteLink = new link($this->uri(array('action' => "deletetheme", 'id' => $umbrellaTheme['id'], 'themeid' => $theme['themeid'])));
        $deleteLink->link = $objIcon->show();
        $deleteLink->cssClass = 'deletetheme';
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
                $editLink = new link($this->uri(array('action' => "editthemeDetailsForm", 'id' => $subTheme['id'], 'themeid' => $theme['themeid'], 'themename' => $theme['themename'])));
                $editLink->link = $objIcon->show();
                $themesTable->addCell($editLink->show());

                $objIcon->setIcon('delete');
                $deleteLink = new link($this->uri(array('action' => "deletetheme", 'id' => $subTheme['id'], 'themeid' => $theme['themeid'])));
                $deleteLink->link = $objIcon->show();
                $deleteLink->cssClass = 'deletetheme';
                $themesTable->addCell($deleteLink->show());
                $themesTable->endRow();
            }
        }
    }
}


$fs = new fieldset();
$fs->setLegend("Themes");
$fs->addContent($themesTable->show());
echo $fs->show();
?>
<script type="text/javascript">

    jQuery(document).ready(function(){

    jQuery("a[class=deletetheme]").click(function(){

    var r=confirm( "Are you sure you want to delete this theme?");
    if(r== true){
    window.location=this.href;
    }
    return false;
    }


    );

    }


    );
</script>