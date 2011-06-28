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
        
          
        $origprouct = $this->objDbProducts->getProductByID($featuredAdaptedProduct['id']);  
        
        if ( $origprouct['deleted'] == '0'){
        
        $content = '';

        $content .= '<div class="rightColumnContentPadding">
                                        <img src="' . $featuredAdaptedProduct['thumbnail'] . '" alt=' . $featuredAdaptedProduct['title'] . ' width="45" height="49"class="smallAdaptationImageGrid">
                                        <div class="featuredAdaptationRightContentDiv">
                                            <span class="greyListingHeading">' . $featuredAdaptedProduct['title'] . '</span>
                                            <br><br>
                                            <a href="#" class="adaptationLinks">See all adaptations (' . $featuredAdaptedProduct['noOfAdaptations'] . ')</a>
                                            <br>
                                            <a href="#" class="adaptationLinks">See UNSECO orginals</a>

                                        </div>';

        //If the adaptation was created by a group
        if($featuredAdaptedProduct['group_thumbnail'] != NULL){
                       $content .= '<div class="adaptedByDiv">Managed by:</div>
                                        <img src="' . $featuredAdaptedProduct['group_thumbnail'] . '" alt= ' . $featuredAdaptedProduct['creator'] . ' width="45" height="49" class="smallAdaptationImageGrid">
                                        <span class="greyListingHeading">' . $featuredAdaptedProduct['creator'] . '</span>
                                    </div>
                                </div>';
        }else{  //If the adaptation was created by an institution
            $content .= '<div class="featuredAdaptedBy">Adapted By</div>
                                        <img src="' . $featuredAdaptedProduct['institution_thumbnail'] . '" alt= ' . $featuredAdaptedProduct['creator'] . ' width="45" height="49" class="smallAdaptationImageGrid">
                                        <span class="greyListingHeading">' . $featuredAdaptedProduct['creator'] . '</span>
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
