<?php
//Display errors
error_reporting(E_ALL);
ini_set('display_errors', 'Off');
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

/* This is a FEATURED PRODUCT UI
 *
 */


// set up html elements
$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('link', 'htmlelements');
$objIcon = $this->newObject('geticon', 'htmlelements');

// setup and show heading
$header = new htmlheading();
$header->type = 1;
$header->str = "Institutions";
echo '<div id="institutionheading">';
echo $header->show() . '<br><br />';


$institutionGUI = $this->getObject('institutiongui', 'unesco_oer');

echo $institutionGUI->showNewInstitutionLinkThumbnail();
echo $institutionGUI->showNewInstitutionLink();
echo '<br><br />';
echo '</div>';
//$institutionGUI = $this->getObject('institutiongui', 'unesco_oer');
//$institutionGUI->showAllInstitutions();
// retrieve data from tbl_unesco_oer_feturedproducts
$content = '';
$Institution = $institutionGUI->showAllInstitutions();
if (count($Institution) > 0) {
    foreach ($Institution as $Institutions) {
        $institutionGUI->getInstitution($Institutions['id']);
        $name = $institutionGUI->showInstitutionName();
        $creator = $adaptedProduct['creator'];

        $institutionLink = new link($this->uri(array("action" => '4', 'institutionId' => $Institutions['id'])));
        $institutionLink->cssClass = 'darkGreyColour';
        $institutionLink->link = $name;

        //Trim the text of the description
        if (strlen($institutionGUI->showInstitutionDescription()) > 100) {
            $description = substr($institutionGUI->showInstitutionDescription(), 0, 100) . '...';
        } else {
            $description = $institutionGUI->showInstitutionDescription();
        }
        $content.='<div id="institutions"><img align="top"  width="79" height="79" src="' .
                $institutionGUI->showInstitutionThumbnail() . '" />&nbsp;&nbsp;'.$description.'<br/>
          ' . $institutionGUI->showEditInstitutionLinkThumbnail($Institutions['id']) . ' |
          ' . $institutionGUI->showDeleteInstitutionLinkThumbnail($Institutions['id']) . '&nbsp;&nbsp;

          <a class="greyListingHeading">' . $institutionGUI->showInstitutionWebsiteLink() . '</a> |
          <a class="greyListingHeading">' . $institutionGUI->showInstitutionCountry() . '</a> |
          <a class="greyListingHeading">' . $institutionGUI->showInstitutionCity() . '</a>

<br/>
           </div> ';
        /*
          $content .= ' <div class="institutionsListView">
          <div class="productAdaptationListViewLeftColumn">
          <img src="' . $institutionGUI->showInstitutionThumbnail() . '" alt="Adaptation" width="79" height="79" class="smallLisitngIcons">
          <div class="textNextToTheListingIconDiv"><a href="#" class="adaptationLinks">' . $institutionGUI->showEditInstitutionLinkThumbnail($Institutions['id']) . '</a> |
          <a href="#" class="adaptationLinks">' . $institutionGUI->showDeleteInstitutionLinkThumbnail($Institutions['id']) . '</a></div>
          </div>
          <div class="productAdaptationListViewMiddleColumn">

          </div>
          <div class="productAdaptationListViewRightColumn">
          <h2> ' . $institutionLink->show() . '</h2>
          <br>
          <div class="institutionShortDescription">' . $description . ' </div>
          <div class="productAdaptationViewDiv">
          <div class="gridAdaptationLinksDiv">
          <a class="greyListingHeading">' . $institutionGUI->showInstitutionWebsiteLink() . '</a> |
          <a class="greyListingHeading">' . $institutionGUI->showInstitutionCountry() . '</a> |
          <a class="greyListingHeading">' . $institutionGUI->showInstitutionCity() . '</a>
          </div>
          </div>
          </div>
          </div>';
         */
//
//        $myTable->startRow();
//        $myTable->addCell($Institutions['name']);
//        $objIcon->setIcon('delete');
//        $deleteLink = new link($this->uri(array('action' => "deleteInstitution", 'institutionId' => $Institutions['id'])));
//        $deleteLink->link = $objIcon->show();
//        $myTable->addCell($deleteLink->show());
//        $objIcon->setIcon('edit');
//        $EditLink = new link($this->uri(array("action" => "institutionEditor", 'institutionId' => $Institutions['id'])));
//        $EditLink->link = $objIcon->show();
//        $myTable->addCell($EditLink->show());
//        $myTable->endRow();
    }
}
$fieldset1 = $this->newObject('fieldset', 'htmlelements');
$fieldset1->setLegend('Institution List');
$fieldset1->addContent($content);
echo $fieldset1->show();

//echo $myTable->show();
?>
<script type="text/javascript">

    jQuery(document).ready(function(){

    jQuery("a[class=deleteinstitution]").click(function(){

    var r=confirm( "Are you sure you want to delete this institution?");
    if(r== true){
    window.location=this.href;
    }
    return false;
    }


    );

    }


    );
</script>