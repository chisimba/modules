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

class featuredproductutil extends object
{

    public function init()
    {
        $this->objDbProducts = $this->getObject("dbproducts", "unesco_oer");

    }

    /* This function populates a page with the current FEATURED UNESCO PRODUCTS
     * @param<type> $product
     * return string  a unesco featured product thumbnail and title
     */

    function featuredProductView($product)
    {
        
        $origprouct = $this->objDbProducts->getProductByID($product['id']);  
        
        if ( $origprouct['deleted'] == '0'){
        
        $content = '';
        $content.='
            <img src="' . $product['thumbnail'] . '" alt="Featured" width="136" height="176"><br>
                <div class="greyListingHeading">"' . $product['title'] . '"</div>
                    <br>
                    <img src="skins/unesco_oer/images/small-icon-adaptations.png" alt="Adaptation" width="18" height="18"class="imgFloatRight">
                    ';
        }
        
        else {
            
              $content = '';
        $content.='
            <img src= skins/unesco_oer/images/icon-nofeature.png  alt="Featured" width="136" height="176"><br>
                <div class="greyListingHeading">"' . "No Featured Product Selected" . '"</div>
                    <br>
                    <img src="skins/unesco_oer/images/small-icon-adaptations.png" alt="Adaptation" width="18" height="18"class="imgFloatRight">
                    ';
            
            
            
            
        }
        return $content;
    }

    function featuredProductViewSpan($product)
    {
        
        
        $origprouct = $this->objDbProducts->getProductByID($product['id']);  
        
        if ( $origprouct['deleted'] == '0'){
        $content = '';
        $content.='
            <img src="' . $product['thumbnail'] . '" alt="Featured" width="45" height="49"class="smallAdaptationImageGrid">
                                <div class="featuredAdaptationRightContentDiv">
                                    <span class="greyListingHeading">"' . $product['title'] . '</span>
                                    <br><br>
            ';
        }
        else{
            
              $content = '';
        $content.='
            <img src="' . "skins/unesco_oer/images/icon-nofeature.png" . '" alt="Featured" width="45" height="49"class="smallAdaptationImageGrid">
                                <div class="featuredAdaptationRightContentDiv">
                                    <span class="greyListingHeading">"' . "No Featured Product Selected" . '</span>
                                    <br><br>
            ';
            
            
            
        }
        return $content;
    }


    public function displayFeaturedAdaptedProduct($featuredAdaptedProduct)
    {
        
        if ( !$featuredAdaptedProduct->isDeleted() && !empty($featuredAdaptedProduct)){
        
        $content = '';

        $content .= '<div class="rightColumnContentPadding">
                                        <img src="' . $featuredAdaptedProduct->getThumbnailPath() . '" alt="' . $featuredAdaptedProduct->getTitle() . '" width="45" height="49" class="smallAdaptationImageGrid">
                                        <div class="featuredAdaptationRightContentDiv">
                                            <span class="greyListingHeading">' . $featuredAdaptedProduct->getTitle() . '</span>
                                            <br><br>
                                            <a href="#" class="adaptationLinks">See all adaptations (' . $featuredAdaptedProduct->getNoOfAdaptations(). ')</a>
                                            <br>
                                            <a href="#" class="adaptationLinks">See UNSECO orginals</a>
                                        </div>';

        
        //If the adaptation was created by an institution
        if ($featuredAdaptedProduct->getInstitutionID()) {
            $objInstitutionManager = $this->getObject('institutionmanager');
            $objInstitutionManager->getInstitution($featuredAdaptedProduct->getInstitutionID());
                       $content .= '<div class="adaptedByDiv">Managed by:</div>
                                        <img src="' . $objInstitutionManager->getInstitutionThumbnail() . '" alt= "' . $objInstitutionManager->getInstitutionName() . '" width="45" height="49" class="smallAdaptationImageGrid">
                                        <span class="greyListingHeading">' . $objInstitutionManager->getInstitutionName() . '</span>
                                    </div>
                                </div>';
        }else{  //If the adaptation was created by a group
            $groupInfo = $featuredAdaptedProduct->getGroupInfo();
            $content .= '<div class="adaptedByDiv">Adapted by:</div>
                                        <img src="' . $groupInfo['thumbnail'] . '" alt= "' . $groupInfo['name'] . '" width="45" height="49" class="smallAdaptationImageGrid">
                                        <span class="greyListingHeading">'. $groupInfo['name'] .'</span>
                                    </div>
                                </div>';
        }
        }
        
        
        else{
            $content = '';

            $content .= '<div class="rightColumnContentPadding">
                                        <img src="' ."skins/unesco_oer/images/icon-nofeature.png"  . '" alt=' . "No Featured Product Selected" . ' width="45" height="49"class="smallAdaptationImageGrid">
                                        <div class="featuredAdaptationRightContentDiv">
                                            <span class="greyListingHeading">' . "No Featured Adaptation Selected"  . '</span>
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
