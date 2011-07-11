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
$header->str = "mod_unesco_oer_add_data_newLanguage";
echo '<div id="institutionheading">';
echo $header->show(). '<br><br />';

$buttonTitle = $this->objLanguage->languageText('mod_unesco_oer_add_data_newProductType', 'unesco_oer');
$buttonLanguage = new button('Add Language Button', $buttonTitle);
$buttonLanguage->setToSubmit();
$addLanguageLink = new link($this->uri(array('action' => "newResourceTypeUI")));
$addLanguageLink->link = $buttonLanguage->show();
$controlPannel = new button('backButton', "Back");
$controlPannel->setToSubmit();
$BackToControlPanelLink = new link($this->uri(array('action' => "controlpanel")));
$BackToControlPanelLink->link = $controlPannel->show();

echo $addLanguageLink->show() . '&nbsp;' . $BackToControlPanelLink->show();
echo '</div>';


$table = $this->newObject('htmltable', 'htmlelements');
//$search = new textinput('state');
//$search->size = 10;
//$table->startRow();
//$table->addCell('Search');
//$table->addCell($search->show());
//$table->endRow();
//echo $table->show();



//. '&nbsp;' . $search->show() . '&nbsp;' . $searchLink->show();

$themesTable = $this->newObject('htmltable', 'htmlelements');
$themesTable->width = '100%';
$themesTable->border = '0';
$themesTable->cellspacing = '0';
$themesTable->cellpadding = '0';

$themesTable->startHeaderRow();
//$str, $width=null, $valign="top", $align='left', $class=null, $attrib=Null)
$themesTable->addHeaderCell('Product type', null, null, left, "userheader", null);
$themesTable->addHeaderCell('Product type table', null, null, left, "userheader", null);
$themesTable->addHeaderCell('Edit', null, null, left, "userheader", null);
$themesTable->addHeaderCell('Delete', null, null, left, "userheader", null);
$themesTable->endHeaderRow();

//get languages from the database
$productTypesList = $this->objDbResourceTypes->getResourceTypes();

if (count($productTypesList) > 0) {
    foreach ($productTypesList as $productType) {
        $themesTable->startRow();
        //($str, $width=null, $valign="top", $align=null, $class=null, $attrib=Null,$border = '0')
        $themesTable->addCell($productType['description'], null, null, null, "user", null, null);
        $themesTable->addCell($productType['table_name'], null, null, null, "user", null, null);

        $objIcon->setIcon('edit');
        $editLink = new link($this->uri(array('action' => "editResourceType", 'productTypeId' => $productType['id'])));
        $editLink->link = $objIcon->show();
        $themesTable->addCell($editLink->show());

        $objIcon->setIcon('delete');
        $deleteLink = new link($this->uri(array('action' => "deleteProductType", 'productTypeId' => $productType['id'])));
        $deleteLink->link = $objIcon->show();
        $deleteLink->cssClass = 'deleteTheme';
        $themesTable->addCell($deleteLink->show());
        $themesTable->endRow();
    }
}


$fs = new fieldset();
$fs->setLegend("Product types");
$fs->addContent($themesTable->show());
echo $fs->show();
?>
<script type="text/javascript">

    jQuery(document).ready(function(){

    jQuery("a[class=deleteProductType]").click(function(){

    var r=confirm( "Are you sure you want to delete this Product Type?");
    if(r== true){
    window.location=this.href;
    }
    return false;
    }


    );

    }


    );
</script>