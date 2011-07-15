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
$header->str = $this->objLanguage->languageText('mod_unesco_oer_control_panel_product_types_title', 'unesco_oer');

echo '<div id="institutionheading">';
echo $header->show() . '<br><br />';

$buttonTitle = $this->objLanguage->languageText('mod_unesco_oer_add_data_newProductType', 'unesco_oer');
$buttonLanguage = new button('Add Language Button', $buttonTitle);
$buttonLanguage->setToSubmit();
$addLanguageLink = new link($this->uri(array('action' => "newResourceTypeUI")));
$addLanguageLink->link = $buttonLanguage->show();
$backButtonTitle = $this->objLanguage->languageText('mod_unesco_oer_group_back_button', 'unesco_oer');
$controlPannel = new button('backButton', $backButtonTitle);
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
//$str, $width=null, $valign="top", $align='left', $class=null, $attrib=Null)
$productTypeRowTitle = $this->objLanguage->languageText('mod_unesco_oer_control_panel_product_types_title', 'unesco_oer');
$editProductTypeRowTitle = $this->objLanguage->languageText('mod_unesco_oer_group_edit', 'unesco_oer');
$deleteProductTypeRowTitle = $this->objLanguage->languageText('mod_unesco_oer_group_delete', 'unesco_oer');

$themesTable->addHeaderCell($productTypeRowTitle, null, null, 'left', "userheader", null);
$themesTable->addHeaderCell($editProductTypeRowTitle, null, null, 'left', "userheader", null);
$themesTable->addHeaderCell($deleteProductTypeRowTitle, null, null, 'left', "userheader", null);
$themesTable->endHeaderRow();

//get languages from the database
$productTypesList = $this->objDbResourceTypes->getResourceTypes();

if (count($productTypesList) > 0) {
    foreach ($productTypesList as $productType) {
        $themesTable->startRow();
        $themesTable->addCell($productType['description'], null, null, null, "user", null, null);
        $objIcon->setIcon('edit');
        $editLink = new link($this->uri(array('action' => "editResourceType", 'productTypeId' => $productType['id'])));
        $editLink->link = $objIcon->show();
        $themesTable->addCell($editLink->show());

        $objIcon->setIcon('delete');
        $deleteLink = new link($this->uri(array('action' => "deleteProductType", 'productTypeId' => $productType['id'])));
        $deleteLink->link = $objIcon->show();
        $deleteLink->cssClass = 'deleteProductType';
        $themesTable->addCell($deleteLink->show());
        $themesTable->endRow();
    }
}

$fs = new fieldset();
$productTypeFieldsetTitle = $this->objLanguage->languageText('mod_unesco_oer_control_panel_product_types_title', 'unesco_oer');
$fs->setLegend($productTypeFieldsetTitle);
$fs->addContent($themesTable->show());
echo $fs->show();
?>
<script type="text/javascript">

    jQuery(document).ready(function(){

    jQuery("a[class=deleteProductType]").click(function(){

    var r=confirm( "
<?php
    echo $this->objLanguage->languageText('mod_unesco_oer_product_type_delete_confirm', 'unesco_oer');
    ?>");
    if(r== true){
    window.location=this.href;
    }
    return false;
    }


    );

    }


    );
</script>