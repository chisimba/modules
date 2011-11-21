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

class featuredproductutil extends object {

    public function init() {
        $this->objDbProducts = $this->getObject("dbproducts", "unesco_oer");
        $this->objLanguage = $this->getObject("language", "language");
        $this->loadClass('link', 'htmlelements');
    }

    /* This function populates a page with the current FEATURED UNESCO PRODUCTS
     * @param<type> $product
     * return string  a unesco featured product thumbnail and title
     */

    function featuredProductView($product) {

        $origprouct = $this->objDbProducts->getProductByID($product['id']);

        if ($origprouct['deleted'] == '0') {

            $adaplink = new link($this->uri(array("action" => 'FilterAdaptations', 'parentid' => $product['id'])));
            $adaplink->cssClass = 'adaptationLinks';
            $NOofAdaptation = $this->objDbProducts->getNoOfAdaptations($product['id']);
            $adaplink->link = $NOofAdaptation . ' ' . $this->objLanguage->languageText('mod_unesco_oer_adaptations', 'unesco_oer');

            $content = '';
            $content.='
            <img src="' . $product['thumbnail'] . '" alt="Featured" width="136" height="176"><br>
                <div class="greyListingHeading">' . $product['title'] . '</div>
                    <br>
                    <img src="skins/unesco_oer/images/small-icon-adaptations.png" alt="Adaptation" width="18" height="18"class="smallLisitngIcons">
                     <div class="textNextToTheListingIconDiv">
                    ' . $adaplink->show() . '
                </div>
                    ';
        } else {

            $content = '';
            $content.='
            <img src= skins/unesco_oer/images/icon-nofeature.png  alt="Featured" width="136" height="176"><br>
                <div class="greyListingHeading">"' . $this->objLanguage->languageText('mod_unesco_oer_no_featured', 'unesco_oer') . '"</div>
                    <br>
                    <img src="skins/unesco_oer/images/small-icon-adaptations.png" alt="Adaptation" width="18" height="18"class="imgFloatRight">
                    ';
        }
        return $content;
    }

    function featuredProductViewSpan($product) {


        $origprouct = $this->objDbProducts->getProductByID($product['id']);

        if ($origprouct['deleted'] == '0') {
            $content = '';
            $content.='
            <img src="' . $product['thumbnail'] . '" alt="Featured" width="45" height="49"class="smallAdaptationImageGrid">
                                <div class="featuredAdaptationRightContentDiv">
                                    <span class="greyListingHeading">"' . $product['title'] . '</span>
                                    <br><br>
            ';
        } else {

            $content = '';
            $content.='
            <img src="' . "skins/unesco_oer/images/icon-nofeature.png" . '" alt="Featured" width="45" height="49"class="smallAdaptationImageGrid">
                                <div class="featuredAdaptationRightContentDiv">
                                    <span class="greyListingHeading">"' . $this->objLanguage->languageText('mod_unesco_oer_no_featured', 'unesco_oer') . '</span>
                                    <br><br>
            ';
        }
        return $content;
    }

    public function displayFeaturedAdaptedProduct($featuredAdaptedProduct) {

        $adaplink = new link($this->uri(array("action" => 'FilterAdaptations', 'parentid' => $featuredAdaptedProduct->getparentID())));
        $adaplink->cssClass = 'adaptationLinks';
        $adaplink->link = $this->objLanguage->languageText('mod_unesco_oer_see_all_adaptations', 'unesco_oer') . ' (' . $featuredAdaptedProduct->getNoOfAdaptations() . ')';

        $abLink = new link($this->uri(array("action" => 'ViewProduct', "id" => $featuredAdaptedProduct->getparentID())));
        $abLink->cssClass = "adaptationLinks";
        $abLink->link = $this->objLanguage->languageText('mod_unesco_oer_view_original', 'unesco_oer');

        if (!$featuredAdaptedProduct->isDeleted() && !empty($featuredAdaptedProduct)) {

            $content = '';

            $content .= '<div class="rightColumnContentPadding">
                                        <img src="' . $featuredAdaptedProduct->getThumbnailPath() . '" align="left" alt="' . $featuredAdaptedProduct->getTitle() . '" width="45" height="49" class="smallAdaptationImageGrid">
                                         <span class="greyListingHeading">' . $featuredAdaptedProduct->getTitle() . '</span> <br/>  <br/>                                    
                                          <div class="featuredAdaptationRightContentDiv">
                                          
                                           ' . $adaplink->show() . '
                                            <br/>
                                            '.$abLink->show().'
                                             <br/><br/>
                                        </div>
<br/>  <br/>                                        
';


            //If the adaptation was created by an institution
            if ($featuredAdaptedProduct->getInstitutionID()) {
                $objInstitutionManager = $this->getObject('institutionmanager');
                $objInstitutionManager->getInstitution($featuredAdaptedProduct->getInstitutionID());
                $content .= '<div class="adaptedByDiv2a">' . $this->objLanguage->languageText('mod_unesco_oer_managed_by', 'unesco_oer') . ':</div>
                                        <img src="' . $objInstitutionManager->getInstitutionThumbnail() . '" alt= "' . $objInstitutionManager->getInstitutionName() . '" width="45" height="49" class="smallAdaptationImageGrid">
                                        <span class="greyListingHeading">' . $objInstitutionManager->getInstitutionName() . '</span>
                                    </div>
                                </div>';
            } else {  //If the adaptation was created by a group
                $groupInfo = $featuredAdaptedProduct->getGroupInfo();
                $content .= '<div class="adaptedByDiv2a">' . $this->objLanguage->languageText('mod_unesco_oer_managed_by', 'unesco_oer') . '</div>
                                        <img src="' . $groupInfo['thumbnail'] . '" alt= "' . $groupInfo['name'] . '" width="45" height="49" class="smallAdaptationImageGrid">
                                        <span class="greyListingHeading">' . $groupInfo['name'] . '</span>
                                    </div>
                                </div>';
            }
        } else {
            $content = '';

            $content .= '<div class="rightColumnContentPadding">
                                        <img src="' . "skins/unesco_oer/images/icon-nofeature.png" . '" alt=' . "No Featured Product Selected" . ' width="45" height="49"class="smallAdaptationImageGrid">
                                        <div class="featuredAdaptationRightContentDiv">
                                            <span class="greyListingHeading">' . $this->objLanguage->languageText('mod_unesco_oer_no_featured', 'unesco_oer') . '</span>
                                            <br><br>
                                           
                                            <br>
                                          
                                        </div>

      
                                    </div>
                                </div>';
        }

        return $content;
    }

    //
}

?>